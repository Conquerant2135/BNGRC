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
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}