<?php

class CategorieRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Flight::db();
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT id, nom FROM bngrc_categorie ORDER BY nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Liste des catégories avec le nombre d'articles associés
     */
    public function allWithArticleCount(): array
    {
        $sql = "SELECT c.id, c.nom, COUNT(a.id) AS nb_articles
                FROM bngrc_categorie c
                LEFT JOIN bngrc_article a ON a.id_cat = c.id
                GROUP BY c.id, c.nom
                ORDER BY c.nom";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}