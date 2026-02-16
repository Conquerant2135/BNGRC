<?php

class DispatchController
{
    /**
     * GET /dispatch — Affiche la page de simulation
     */
    public static function index()
    {
        $db = Flight::db();

        // Paramètres de filtre
        $dateDebut = $_GET['date_debut'] ?? null;
        $dateFin   = $_GET['date_fin']   ?? date('Y-m-d');
        $mode      = $_GET['mode']       ?? 'fifo';
        $lancer    = isset($_GET['lancer']);

        $resultats = [];
        $resume    = [];
        $nbOps     = 0;

        if ($lancer) {
            $resultats = self::simulerDispatch($db, $dateDebut, $dateFin, $mode);
            $resume    = self::calculerResume($db, $resultats);
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
        $db = Flight::db();

        $dateDebut = $_POST['date_debut'] ?? null;
        $dateFin   = $_POST['date_fin']   ?? date('Y-m-d');
        $mode      = $_POST['mode']       ?? 'fifo';

        $resultats = self::simulerDispatch($db, $dateDebut, $dateFin, $mode);

        if (empty($resultats)) {
            Flight::redirect(BASE_URL . '/dispatch?error=' . urlencode('Aucune attribution à valider.'));
            return;
        }

        try {
            $db->beginTransaction();

            $stmtAttrib = $db->prepare(
                "INSERT INTO bngrc_attribution_don (id_don, id_besoin, quantite_attribuee, date_attribution)
                 VALUES (:id_don, :id_besoin, :qte, CURDATE())"
            );

            foreach ($resultats as $r) {
                $stmtAttrib->execute([
                    ':id_don'    => $r['id_don'],
                    ':id_besoin' => $r['id_besoin'],
                    ':qte'       => $r['quantite_attribuee'],
                ]);
            }

            // Mettre à jour les besoins entièrement satisfaits
            $db->exec("
                UPDATE bngrc_besoin b
                SET est_satisfait = 1
                WHERE (
                    SELECT COALESCE(SUM(a.quantite_attribuee), 0)
                    FROM bngrc_attribution_don a
                    WHERE a.id_besoin = b.id_besoin
                ) >= b.quantite
            ");

            // Mettre à jour l'état des dons entièrement distribués (id_etat = 2 si existant)
            $etatDistribue = $db->query("SELECT id FROM bngrc_etat_don WHERE nom LIKE '%distribu%' OR nom LIKE '%attribu%' LIMIT 1")->fetchColumn();
            if ($etatDistribue) {
                $db->exec("
                    UPDATE bngrc_don d
                    SET id_etat = {$etatDistribue}
                    WHERE (
                        SELECT COALESCE(SUM(a.quantite_attribuee), 0)
                        FROM bngrc_attribution_don a
                        WHERE a.id_don = d.id_don
                    ) >= d.quantite
                ");
            }

            $db->commit();

            Flight::redirect(BASE_URL . '/dispatch?success=1');
        } catch (Exception $e) {
            $db->rollBack();
            Flight::redirect(BASE_URL . '/dispatch?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Simuler le dispatch des dons par ordre chronologique (FIFO ou proportionnel)
     */
    private static function simulerDispatch(PDO $db, ?string $dateDebut, ?string $dateFin, string $mode): array
    {
        // ── 1. Récupérer les dons disponibles ──
        $sqlDons = "
            SELECT d.id_don, d.donateur, d.date_don, d.id_article, d.quantite,
                   a.nom AS article_nom, u.libelle AS unite,
                   COALESCE(att_sum.total_attribue, 0) AS deja_attribue
            FROM bngrc_don d
            JOIN bngrc_article a ON a.id = d.id_article
            LEFT JOIN bngrc_unite u ON u.id = a.id_unite
            LEFT JOIN (
                SELECT id_don, SUM(quantite_attribuee) AS total_attribue
                FROM bngrc_attribution_don
                GROUP BY id_don
            ) att_sum ON att_sum.id_don = d.id_don
        ";

        $conditions = [];
        $params     = [];

        if ($dateDebut) {
            $conditions[]          = "d.date_don >= :date_debut";
            $params[':date_debut'] = $dateDebut;
        }
        if ($dateFin) {
            $conditions[]        = "d.date_don <= :date_fin";
            $params[':date_fin'] = $dateFin;
        }

        if ($conditions) {
            $sqlDons .= " WHERE " . implode(' AND ', $conditions);
        }

        // Ne prendre que les dons ayant encore du stock à distribuer
        $sqlDons .= " HAVING (d.quantite - COALESCE(att_sum.total_attribue, 0)) > 0";
        $sqlDons .= " ORDER BY d.date_don ASC, d.id_don ASC";

        $stmtDons = $db->prepare($sqlDons);
        $stmtDons->execute($params);
        $dons = $stmtDons->fetchAll(PDO::FETCH_ASSOC);

        // ── 2. Récupérer les besoins non satisfaits ──
        $sqlBesoins = "
            SELECT b.id_besoin, b.id_article, b.id_ville, b.quantite, b.date_demande,
                   v.nom_ville, a.nom AS article_nom,
                   COALESCE(att_sum.total_attribue, 0) AS deja_attribue
            FROM bngrc_besoin b
            JOIN bngrc_ville v ON v.id_ville = b.id_ville
            JOIN bngrc_article a ON a.id = b.id_article
            LEFT JOIN (
                SELECT id_besoin, SUM(quantite_attribuee) AS total_attribue
                FROM bngrc_attribution_don
                GROUP BY id_besoin
            ) att_sum ON att_sum.id_besoin = b.id_besoin
            WHERE b.est_satisfait = 0
            HAVING (b.quantite - COALESCE(att_sum.total_attribue, 0)) > 0
            ORDER BY b.date_demande ASC, b.id_besoin ASC
        ";
        $besoins = $db->query($sqlBesoins)->fetchAll(PDO::FETCH_ASSOC);

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

                foreach ($matchingBesoins as $b) {
                    if ($resteDon <= 0) break;

                    $ratio    = $besoinRestant[$b['id_besoin']] / $totalBesoin;
                    $attribue = round($ratio * $qteADistribuer, 3);
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

                    $resteDon                        -= $attribue;
                    $besoinRestant[$b['id_besoin']]  -= $attribue;
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

    /**
     * Calculer le résumé par ville après simulation
     */
    private static function calculerResume(PDO $db, array $resultats): array
    {
        // Récupérer tous les besoins non satisfaits avec leur état actuel
        $besoins = $db->query("
            SELECT b.id_besoin, b.id_ville, b.quantite, v.nom_ville,
                   COALESCE(att_sum.total_attribue, 0) AS deja_attribue
            FROM bngrc_besoin b
            JOIN bngrc_ville v ON v.id_ville = b.id_ville
            LEFT JOIN (
                SELECT id_besoin, SUM(quantite_attribuee) AS total_attribue
                FROM bngrc_attribution_don
                GROUP BY id_besoin
            ) att_sum ON att_sum.id_besoin = b.id_besoin
            WHERE b.est_satisfait = 0
        ")->fetchAll(PDO::FETCH_ASSOC);

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
