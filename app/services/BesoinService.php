<?php

class BesoinService
{
    private BesoinRepository $besoinRepo;
    private ArticleRepository $articleRepo;

    public function __construct()
    {
        $this->besoinRepo = new BesoinRepository();
        $this->articleRepo = new ArticleRepository();
    }

    public function create(array $input): int
    {
        $prixUnitaire = $this->articleRepo->prixUnitaire((int)$input['id_article']);
        if ($prixUnitaire === null) {
            throw new RuntimeException("Article introuvable.");
        }

        $quantite = (float)$input['quantite'];
        $montant = $prixUnitaire * $quantite;

        return $this->besoinRepo->insert([
            'id_article' => (int)$input['id_article'],
            'id_ville' => (int)$input['id_ville'],
            'quantite' => $quantite,
            'montant_totale' => $montant,
            'id_traboina' => 1,
            'date_demande' => $input['date_demande']
        ]);
    }

    public function all(): array
    {
        return $this->besoinRepo->all();
    }
}