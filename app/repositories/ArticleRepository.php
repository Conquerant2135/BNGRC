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
}