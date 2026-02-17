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

    /**
     * Récapitulatif du dispatch : besoins totaux, satisfaits, restants (en montants) par ville
     */
    public function recapParVille(): array
    {
        $sql = "SELECT v.nom_ville,
                       COUNT(b.id_besoin) AS nb_besoins,
                       COALESCE(SUM(b.montant_totale), 0) AS montant_total_besoins,
                       COALESCE(SUM(CASE WHEN b.est_satisfait = 1 THEN b.montant_totale ELSE 0 END), 0) AS montant_satisfait,
                       COALESCE(SUM(
                           CASE WHEN b.est_satisfait = 0 THEN
                               GREATEST(0,
                                   b.montant_totale - b.montant_totale * LEAST(1,
                                       COALESCE(att.total_attribue, 0) / GREATEST(b.quantite, 0.001)
                                   )
                               )
                           ELSE 0 END
                       ), 0) AS montant_restant,
                       COALESCE(SUM(
                           CASE WHEN b.est_satisfait = 0 THEN
                               b.montant_totale * LEAST(1,
                                   COALESCE(att.total_attribue, 0) / GREATEST(b.quantite, 0.001)
                               )
                           ELSE 0 END
                       ), 0) AS montant_partiellement_couvert,
                       COUNT(CASE WHEN b.est_satisfait = 1 THEN 1 END) AS nb_satisfaits,
                       COUNT(CASE WHEN b.est_satisfait = 0 THEN 1 END) AS nb_insatisfaits
                FROM bngrc_besoin b
                JOIN bngrc_ville v ON v.id_ville = b.id_ville
                LEFT JOIN (
                    SELECT id_besoin, SUM(quantite_attribuee) AS total_attribue
                    FROM bngrc_attribution_don
                    GROUP BY id_besoin
                ) att ON att.id_besoin = b.id_besoin
                GROUP BY v.id_ville, v.nom_ville
                ORDER BY v.nom_ville";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Totaux globaux du dispatch (montants)
     */
    public function recapGlobal(): array
    {
        $sql = "SELECT
                    COUNT(b.id_besoin) AS nb_besoins,
                    COALESCE(SUM(b.montant_totale), 0) AS montant_total,
                    COALESCE(SUM(CASE WHEN b.est_satisfait = 1 THEN b.montant_totale ELSE 0 END), 0) AS montant_satisfait,
                    COUNT(CASE WHEN b.est_satisfait = 1 THEN 1 END) AS nb_satisfaits,
                    COUNT(CASE WHEN b.est_satisfait = 0 THEN 1 END) AS nb_insatisfaits,
                    COALESCE(SUM(
                        CASE WHEN b.est_satisfait = 0 THEN
                            b.montant_totale * LEAST(1,
                                COALESCE(att.total_attribue, 0) / GREATEST(b.quantite, 0.001)
                            )
                        ELSE 0 END
                    ), 0) AS montant_partiellement_couvert
                FROM bngrc_besoin b
                LEFT JOIN (
                    SELECT id_besoin, SUM(quantite_attribuee) AS total_attribue
                    FROM bngrc_attribution_don
                    GROUP BY id_besoin
                ) att ON att.id_besoin = b.id_besoin";
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Réinitialiser toutes les données de dispatch :
     * - Vider attribution_don, achat_produit, stock
     * - Remettre besoins à est_satisfait = 0
     * - Remettre dons à id_etat = 1 (En attente)
     */
    public function resetAll(): void
    {
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        $this->pdo->exec("TRUNCATE TABLE bngrc_attribution_don");
        $this->pdo->exec("TRUNCATE TABLE bngrc_achat_produit");
        $this->pdo->exec("TRUNCATE TABLE bngrc_stock");
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

        // Remettre tous les besoins à insatisfait
        $this->pdo->exec("UPDATE bngrc_besoin SET est_satisfait = 0");

        // Remettre tous les dons en attente (id_etat = 1)
        $this->pdo->exec("UPDATE bngrc_don SET id_etat = 1");
    }
}
