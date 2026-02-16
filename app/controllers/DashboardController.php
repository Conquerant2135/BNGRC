<?php

class DashboardController
{
    public static function index()
    {
        $db = Flight::db();

        // ── 1. Statistiques globales ──
        $nbVilles   = (int) $db->query("SELECT COUNT(*) FROM bngrc_ville WHERE nb_sinistres > 0")->fetchColumn();
        $nbBesoins  = (int) $db->query("SELECT COUNT(*) FROM bngrc_besoin")->fetchColumn();
        $nbDons     = (int) $db->query("SELECT COUNT(*) FROM bngrc_don")->fetchColumn();
        $nbDispatch = (int) $db->query("SELECT COUNT(*) FROM bngrc_attribution_don")->fetchColumn();

        // ── 2. Besoins par ville avec dons attribués, groupés par catégorie ──
        $villesRows = $db->query("
            SELECT v.id_ville, v.nom_ville, r.nom AS region, v.nb_sinistres,
                   c.nom AS categorie,
                   COALESCE(SUM(b.quantite), 0) AS total_besoin,
                   COALESCE(SUM(att.total_attribue), 0) AS total_recu
            FROM bngrc_ville v
            JOIN bngrc_region r ON r.id = v.id_region
            LEFT JOIN bngrc_besoin b ON b.id_ville = v.id_ville
            LEFT JOIN bngrc_article a ON a.id = b.id_article
            LEFT JOIN bngrc_categorie c ON c.id = a.id_cat
            LEFT JOIN (
                SELECT ad.id_besoin, SUM(ad.quantite_attribuee) AS total_attribue
                FROM bngrc_attribution_don ad
                GROUP BY ad.id_besoin
            ) att ON att.id_besoin = b.id_besoin
            WHERE v.nb_sinistres > 0
            GROUP BY v.id_ville, v.nom_ville, r.nom, v.nb_sinistres, c.nom
            ORDER BY v.nom_ville, c.nom
        ")->fetchAll(PDO::FETCH_ASSOC);

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
        $derniersDons = $db->query("
            SELECT d.date_don, d.donateur, d.quantite,
                   a.nom AS article_nom, u.libelle AS unite,
                   c.nom AS categorie,
                   e.nom AS etat
            FROM bngrc_don d
            JOIN bngrc_article a ON a.id = d.id_article
            LEFT JOIN bngrc_unite u ON u.id = a.id_unite
            LEFT JOIN bngrc_categorie c ON c.id = d.id_cat
            LEFT JOIN bngrc_etat_don e ON e.id = d.id_etat
            ORDER BY d.date_don DESC, d.id_don DESC
            LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);

        // ── 4. Liste des catégories distinctes pour le header du tableau ──
        $categories = $db->query("SELECT id, nom FROM bngrc_categorie ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

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
