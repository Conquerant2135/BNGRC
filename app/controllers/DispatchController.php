<?php

class DispatchController
{
    private static function redir(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    /**
     * GET /dispatch — Affiche la page de simulation
     */
    public static function index()
    {
        // Paramètres de filtre
        $dateDebut = $_GET['date_debut'] ?? null;
        $dateFin   = $_GET['date_fin']   ?? date('Y-m-d');
        $mode      = $_GET['mode']       ?? 'fifo';
        $lancer    = isset($_GET['lancer']);

        $resultats = [];
        $resume    = [];
        $nbOps     = 0;

        if ($lancer) {
            $donRepo    = new DonRepository(Flight::db());
            $besoinRepo = new BesoinRepository();

            $resultats = self::simulerDispatch($donRepo, $besoinRepo, $dateDebut, $dateFin, $mode);
            $resume    = self::calculerResume($besoinRepo, $resultats);
            $nbOps     = count($resultats);
        }

        Flight::render('dispatch', [
            'resultats' => $resultats,
            'resume'    => $resume,
            'dateDebut' => $dateDebut,
            'dateFin'   => $dateFin,
            'mode'      => $mode,
            'lancer'    => $lancer,
            'nbOps'     => $nbOps,
        ]);
    }

    /**
     * POST /dispatch/valider — Valider et enregistrer le dispatch en base
     */
    public static function valider()
    {
        $donRepo         = new DonRepository(Flight::db());
        $besoinRepo      = new BesoinRepository();
        $attributionRepo = new AttributionRepository();

        $dateDebut = $_POST['date_debut'] ?? null;
        $dateFin   = $_POST['date_fin']   ?? date('Y-m-d');
        $mode      = $_POST['mode']       ?? 'fifo';

        $resultats = self::simulerDispatch($donRepo, $besoinRepo, $dateDebut, $dateFin, $mode);

        if (empty($resultats)) {
            self::redir('/dispatch?error=' . urlencode('Aucune attribution à valider.'));
        }

        try {
            $attributionRepo->beginTransaction();

            foreach ($resultats as $r) {
                $attributionRepo->insert($r['id_don'], $r['id_besoin'], $r['quantite_attribuee']);
            }

            // Marquer les besoins entièrement satisfaits
            $attributionRepo->marquerBesoinsSatisfaits();

            // Marquer les dons entièrement distribués
            $etatDistribue = $donRepo->findEtatDistribue();
            if ($etatDistribue) {
                $donRepo->marquerDistribues($etatDistribue);
            }

            $attributionRepo->commit();

            self::redir('/dispatch?success=1');
        } catch (Exception $e) {
            $attributionRepo->rollBack();
            self::redir('/dispatch?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Simuler le dispatch des dons par ordre chronologique (FIFO ou proportionnel)
     */
    private static function simulerDispatch(DonRepository $donRepo, BesoinRepository $besoinRepo, ?string $dateDebut, ?string $dateFin, string $mode): array
    {
        // ── 1. Récupérer les dons disponibles ──
        $dons = $donRepo->disponibles($dateDebut, $dateFin);

        // ── 2. Récupérer les besoins non satisfaits ──
        $besoins = $besoinRepo->nonSatisfaits($mode === 'stock' ? 'stock' : 'fifo');

        // Index des quantités restantes pour la simulation (sans modifier la BDD)
        $besoinRestant = [];
        foreach ($besoins as $b) {
            $besoinRestant[$b['id_besoin']] = $b['quantite'] - $b['deja_attribue'];
        }

        // ── 3. Simuler le dispatch ──
        $resultats = [];

        foreach ($dons as $don) {
            $resteDon = $don['quantite'] - $don['deja_attribue'];
            if ($resteDon <= 0) continue;

            // Besoins correspondant au même article
            $matchingBesoins = array_filter($besoins, function ($b) use ($don, $besoinRestant) {
                return $b['id_article'] == $don['id_article']
                    && ($besoinRestant[$b['id_besoin']] ?? 0) > 0;
            });

            if (empty($matchingBesoins)) continue;

            if ($mode === 'proportionnel') {
                // ── Mode proportionnel ──
                $totalBesoin = 0;
                foreach ($matchingBesoins as $b) {
                    $totalBesoin += $besoinRestant[$b['id_besoin']];
                }
                if ($totalBesoin <= 0) continue;

                $qteADistribuer = min($resteDon, $totalBesoin);
                $precision      = 3;

                foreach ($matchingBesoins as $b) {
                    if ($resteDon <= 0) break;

                    $ratio    = $besoinRestant[$b['id_besoin']] / $totalBesoin;
                    $attribue = self::floorToPrecision($ratio * $qteADistribuer, $precision);
                    $attribue = min($attribue, $besoinRestant[$b['id_besoin']], $resteDon);

                    if ($attribue <= 0) continue;

                    $resultats[] = [
                        'id_don'             => $don['id_don'],
                        'donateur'           => $don['donateur'],
                        'date_don'           => $don['date_don'],
                        'article_nom'        => $don['article_nom'],
                        'unite'              => $don['unite'] ?? '',
                        'quantite_don'       => $don['quantite'],
                        'reste_don_avant'    => $resteDon,
                        'id_besoin'          => $b['id_besoin'],
                        'nom_ville'          => $b['nom_ville'],
                        'quantite_attribuee' => $attribue,
                    ];

                    $resteDon                       -= $attribue;
                    $besoinRestant[$b['id_besoin']] -= $attribue;
                }

                // Distribuer le reste (arrondi inferieur) en FIFO
                if ($resteDon > 0) {
                    foreach ($matchingBesoins as $b) {
                        if ($resteDon <= 0) break;
                        $besoinQte = $besoinRestant[$b['id_besoin']];
                        if ($besoinQte <= 0) continue;

                        $attribue = min($resteDon, $besoinQte);
                        if ($attribue <= 0) continue;

                        $resultats[] = [
                            'id_don'             => $don['id_don'],
                            'donateur'           => $don['donateur'],
                            'date_don'           => $don['date_don'],
                            'article_nom'        => $don['article_nom'],
                            'unite'              => $don['unite'] ?? '',
                            'quantite_don'       => $don['quantite'],
                            'reste_don_avant'    => $resteDon,
                            'id_besoin'          => $b['id_besoin'],
                            'nom_ville'          => $b['nom_ville'],
                            'quantite_attribuee' => $attribue,
                        ];

                        $resteDon                       -= $attribue;
                        $besoinRestant[$b['id_besoin']] -= $attribue;
                    }
                }
            } else {
                // ── Mode FIFO (défaut) — premier besoin servi d'abord ──
                foreach ($matchingBesoins as $b) {
                    if ($resteDon <= 0) break;

                    $besoinQte = $besoinRestant[$b['id_besoin']];
                    $attribue  = min($resteDon, $besoinQte);

                    if ($attribue <= 0) continue;

                    $resultats[] = [
                        'id_don'             => $don['id_don'],
                        'donateur'           => $don['donateur'],
                        'date_don'           => $don['date_don'],
                        'article_nom'        => $don['article_nom'],
                        'unite'              => $don['unite'] ?? '',
                        'quantite_don'       => $don['quantite'],
                        'reste_don_avant'    => $resteDon,
                        'id_besoin'          => $b['id_besoin'],
                        'nom_ville'          => $b['nom_ville'],
                        'quantite_attribuee' => $attribue,
                    ];

                    $resteDon                        -= $attribue;
                    $besoinRestant[$b['id_besoin']]  -= $attribue;
                }
            }
        }

        return $resultats;
    }

    private static function floorToPrecision(float $value, int $precision): float
    {
        $factor = pow(10, $precision);
        return floor($value * $factor) / $factor;
    }

    /**
     * Calculer le résumé par ville après simulation
     */
    private static function calculerResume(BesoinRepository $besoinRepo, array $resultats): array
    {
        // Récupérer tous les besoins non satisfaits avec leur état actuel
        $besoins = $besoinRepo->nonSatisfaitsResume();

        // Attributions simulées par besoin
        $simAttrib = [];
        foreach ($resultats as $r) {
            $simAttrib[$r['id_besoin']] = ($simAttrib[$r['id_besoin']] ?? 0) + $r['quantite_attribuee'];
        }

        // Agrégation par ville
        $resume = [];
        foreach ($besoins as $b) {
            $ville = $b['nom_ville'];
            if (!isset($resume[$ville])) {
                $resume[$ville] = [
                    'nom_ville'    => $ville,
                    'total_besoin' => 0,
                    'total_recu'   => 0,
                ];
            }
            $resume[$ville]['total_besoin'] += $b['quantite'];
            $resume[$ville]['total_recu']   += $b['deja_attribue'] + ($simAttrib[$b['id_besoin']] ?? 0);
        }

        // Calculer reste et taux de couverture
        foreach ($resume as &$r) {
            $r['reste'] = max(0, round($r['total_besoin'] - $r['total_recu'], 3));
            $r['taux']  = $r['total_besoin'] > 0
                ? round(($r['total_recu'] / $r['total_besoin']) * 100, 1)
                : 0;
        }

        // Trier par taux de couverture (les moins couverts d'abord)
        usort($resume, fn($a, $b) => $a['taux'] <=> $b['taux']);

        return $resume;
    }
}
