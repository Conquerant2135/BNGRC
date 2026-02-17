<?php

class BesoinRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = \Flight::db();
    }

    public function insert(array $data): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO bngrc_besoin
             (id_article, id_ville, quantite, montant_totale, id_traboina, date_demande, est_satisfait)
             VALUES (:id_article, :id_ville, :quantite, :montant_totale, :id_traboina, :date_demande, 0)"
        );

        $stmt->execute([
            'id_article' => $data['id_article'],
            'id_ville' => $data['id_ville'],
            'quantite' => $data['quantite'],
            'montant_totale' => $data['montant_totale'],
            'id_traboina' => $data['id_traboina'] ?: null,
            'date_demande' => $data['date_demande']
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): int
    {
        $stmt = $this->pdo->prepare(
            "UPDATE bngrc_besoin
             SET id_article = :id_article,
                 id_ville = :id_ville,
                 quantite = :quantite,
                 montant_totale = :montant_totale,
                 id_traboina = :id_traboina,
                 date_demande = :date_demande
             WHERE id_besoin = :id_besoin"
        );

        $stmt->execute([
            'id_article' => $data['id_article'],
            'id_ville' => $data['id_ville'],
            'quantite' => $data['quantite'],
            'montant_totale' => $data['montant_totale'],
            'id_traboina' => $data['id_traboina'] ?: null,
            'date_demande' => $data['date_demande'],
            'id_besoin' => $id
        ]);

        return $stmt->rowCount();
    }

    public function delete(int $id): int
    {
        $stmt = $this->pdo->prepare("DELETE FROM bngrc_besoin WHERE id_besoin = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT b.id_besoin, b.id_article, b.id_ville, b.quantite, b.montant_totale,
                    b.date_demande, b.est_satisfait, b.id_traboina, a.id_cat
             FROM bngrc_besoin b
             JOIN bngrc_article a ON a.id = b.id_article
             WHERE b.id_besoin = :id
             LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function all(): array
    {
        $sql = "SELECT b.id_besoin, b.quantite, b.montant_totale, b.date_demande, b.est_satisfait,
                       a.id AS id_article, a.nom AS article, a.id_cat,
                       c.nom AS categorie,
                       u.libelle AS unite,
                       v.id_ville, v.nom_ville AS ville
                FROM bngrc_besoin b
                JOIN bngrc_article a ON a.id = b.id_article
                LEFT JOIN bngrc_categorie c ON c.id = a.id_cat
                LEFT JOIN bngrc_unite u ON u.id = a.id_unite
                JOIN bngrc_ville v ON v.id_ville = b.id_ville
                ORDER BY b.date_demande DESC, b.id_besoin DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Nombre total de besoins
     */
    public function countAll(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM bngrc_besoin")->fetchColumn();
    }

    /**
     * Besoins par ville et catégorie avec attributions (pour le dashboard)
     */
    public function parVilleAvecAttributions(): array
    {
        $sql = "SELECT v.id_ville, v.nom_ville, r.nom AS region, v.nb_sinistres,
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
                ORDER BY v.nom_ville, c.nom";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Besoins non satisfaits avec quantités déjà attribuées (pour dispatch simulation)
     */
    public function nonSatisfaits(string $mode = 'fifo'): array
    {
        $orderBy = "b.date_demande ASC, b.id_besoin ASC";
        if ($mode === 'stock') {
            $orderBy = "b.quantite ASC, b.id_besoin";
        }

        $sql = "SELECT b.id_besoin, b.id_article, b.id_ville, b.quantite, b.date_demande,
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
                  AND (b.quantite - COALESCE(att_sum.total_attribue, 0)) > 0
                ORDER BY {$orderBy}";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Besoins non satisfaits avec attributions existantes (pour résumé dispatch)
     */
    public function nonSatisfaitsResume(): array
    {
        $sql = "SELECT b.id_besoin, b.id_ville, b.quantite, v.nom_ville,
                       COALESCE(att_sum.total_attribue, 0) AS deja_attribue
                FROM bngrc_besoin b
                JOIN bngrc_ville v ON v.id_ville = b.id_ville
                LEFT JOIN (
                    SELECT id_besoin, SUM(quantite_attribuee) AS total_attribue
                    FROM bngrc_attribution_don
                    GROUP BY id_besoin
                ) att_sum ON att_sum.id_besoin = b.id_besoin
                WHERE b.est_satisfait = 0";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}