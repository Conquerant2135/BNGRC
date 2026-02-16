<?php

class VilleRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Flight::db();
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT id_ville, nom_ville FROM bngrc_ville ORDER BY nom_ville");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}