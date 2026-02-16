<?php

class TraboinaRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Flight::db();
    }

    public function insert(string $nom, string $adresse, string $numero): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO bngrc_traboina (nom, adresse, numero)
             VALUES (:nom, :adresse, :numero)"
        );
        $stmt->execute([
            'nom' => $nom,
            'adresse' => $adresse,
            'numero' => $numero
        ]);
        return (int)$this->pdo->lastInsertId();
    }
}