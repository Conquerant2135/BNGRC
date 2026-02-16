<?php

class UniteRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Flight::db();
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT id, libelle FROM bngrc_unite ORDER BY libelle");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}