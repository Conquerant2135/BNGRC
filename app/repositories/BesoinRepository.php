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

    public function all(): array
    {
        $sql = "SELECT b.id_besoin, b.quantite, b.montant_totale, b.date_demande, b.est_satisfait,
                       a.nom AS article, v.nom_ville AS ville
                FROM bngrc_besoin b
                JOIN bngrc_article a ON a.id = b.id_article
                JOIN bngrc_ville v ON v.id_ville = b.id_ville
                ORDER BY b.date_demande DESC, b.id_besoin DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}