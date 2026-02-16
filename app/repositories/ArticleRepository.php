<?php

class ArticleRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Flight::db();
    }

    public function all(): array
    {
        $sql = "SELECT id, nom, id_unite, prix_unitaire, id_cat
                FROM bngrc_article
                ORDER BY nom";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function byCategorie(int $idCat): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, nom, id_unite, prix_unitaire, id_cat
             FROM bngrc_article
             WHERE id_cat = :id_cat
             ORDER BY nom"
        );
        $stmt->execute(['id_cat' => $idCat]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function prixUnitaire(int $idArticle): ?float
    {
        $stmt = $this->pdo->prepare("SELECT prix_unitaire FROM bngrc_article WHERE id = :id");
        $stmt->execute(['id' => $idArticle]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (float)$row['prix_unitaire'] : null;
    }

    /**
     * Liste des articles avec catégorie et unité (jointures)
     */
    public function allWithDetails(): array
    {
        $sql = "SELECT a.id, a.nom, a.prix_unitaire,
                       c.id AS id_cat, c.nom AS categorie,
                       u.id AS id_unite, u.libelle AS unite
                FROM bngrc_article a
                LEFT JOIN bngrc_categorie c ON c.id = a.id_cat
                LEFT JOIN bngrc_unite u ON u.id = a.id_unite
                ORDER BY c.nom, a.nom";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Insérer un nouvel article
     */
    public function insert(string $nom, int $idCat, int $idUnite, float $prix): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO bngrc_article (nom, id_cat, id_unite, prix_unitaire)
             VALUES (:nom, :id_cat, :id_unite, :prix)"
        );
        $stmt->execute([
            ':nom'      => $nom,
            ':id_cat'   => $idCat,
            ':id_unite' => $idUnite,
            ':prix'     => $prix,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Modifier un article
     */
    public function update(int $id, string $nom, int $idCat, int $idUnite, float $prix): int
    {
        $stmt = $this->pdo->prepare(
            "UPDATE bngrc_article
             SET nom = :nom, id_cat = :id_cat, id_unite = :id_unite, prix_unitaire = :prix
             WHERE id = :id"
        );
        $stmt->execute([
            ':nom'      => $nom,
            ':id_cat'   => $idCat,
            ':id_unite' => $idUnite,
            ':prix'     => $prix,
            ':id'       => $id,
        ]);
        return $stmt->rowCount();
    }

    /**
     * Supprimer un article par ID
     */
    public function delete(int $id): int
    {
        $stmt = $this->pdo->prepare("DELETE FROM bngrc_article WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }
}