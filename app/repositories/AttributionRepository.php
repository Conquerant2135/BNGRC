<?php

class AttributionRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Flight::db();
    }

    /**
     * Nombre total d'attributions
     */
    public function countAll(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM bngrc_attribution_don")->fetchColumn();
    }

    /**
     * Insérer une attribution
     */
    public function insert(int $idDon, int $idBesoin, float $quantite): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO bngrc_attribution_don (id_don, id_besoin, quantite_attribuee, date_attribution)
             VALUES (:id_don, :id_besoin, :qte, CURDATE())"
        );
        $stmt->execute([
            ':id_don'    => $idDon,
            ':id_besoin' => $idBesoin,
            ':qte'       => $quantite,
        ]);
    }

    /**
     * Marquer les besoins entièrement satisfaits
     */
    public function marquerBesoinsSatisfaits(): void
    {
        $this->pdo->exec("
            UPDATE bngrc_besoin b
            SET est_satisfait = 1
            WHERE (
                SELECT COALESCE(SUM(a.quantite_attribuee), 0)
                FROM bngrc_attribution_don a
                WHERE a.id_besoin = b.id_besoin
            ) >= b.quantite
        ");
    }

    /**
     * Démarrer une transaction
     */
    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    /**
     * Valider la transaction
     */
    public function commit(): void
    {
        $this->pdo->commit();
    }

    /**
     * Annuler la transaction
     */
    public function rollBack(): void
    {
        $this->pdo->rollBack();
    }
}
