<?php

class DashboardController
{
    public static function index()
    {
        $villeRepo       = new VilleRepository(Flight::db());
        $besoinRepo      = new BesoinRepository();
        $donRepo         = new DonRepository(Flight::db());
        $attributionRepo = new AttributionRepository();
        $categorieRepo   = new CategorieRepository();

        // ── 1. Statistiques globales ──
        $nbVilles   = $villeRepo->countSinistrees();
        $nbBesoins  = $besoinRepo->countAll();
        $nbDons     = $donRepo->countAll();
        $nbDispatch = $attributionRepo->countAll();

        // ── 2. Besoins par ville avec dons attribués, groupés par catégorie ──
        $villesRows = $besoinRepo->parVilleAvecAttributions();

        // Agréger par ville
        $villes = [];
        foreach ($villesRows as $row) {
            $id = $row['id_ville'];
            if (!isset($villes[$id])) {
                $villes[$id] = [
                    'nom_ville'    => $row['nom_ville'],
                    'region'       => $row['region'],
                    'nb_sinistres' => $row['nb_sinistres'],
                    'categories'   => [],
                    'total_besoin' => 0,
                    'total_recu'   => 0,
                ];
            }
            $cat = $row['categorie'] ?? 'Autre';
            $villes[$id]['categories'][$cat] = [
                'besoin' => (float) $row['total_besoin'],
                'recu'   => (float) $row['total_recu'],
            ];
            $villes[$id]['total_besoin'] += (float) $row['total_besoin'];
            $villes[$id]['total_recu']   += (float) $row['total_recu'];
        }

        // Calculer le taux de couverture
        foreach ($villes as &$v) {
            $v['taux'] = $v['total_besoin'] > 0
                ? round(($v['total_recu'] / $v['total_besoin']) * 100, 1)
                : 0;
        }
        unset($v);

        // ── 3. Derniers dons ──
        $derniersDons = $donRepo->derniers(10);

        // ── 4. Liste des catégories distinctes pour le header du tableau ──
        $categories = $categorieRepo->all();

        Flight::render('dashboard', [
            'nbVilles'     => $nbVilles,
            'nbBesoins'    => $nbBesoins,
            'nbDons'       => $nbDons,
            'nbDispatch'   => $nbDispatch,
            'villes'       => $villes,
            'derniersDons' => $derniersDons,
            'categories'   => $categories,
        ]);
    }
}
