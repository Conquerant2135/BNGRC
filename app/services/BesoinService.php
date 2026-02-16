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

    public function validate(array $input): array
    {
        $errors = [
            'id_ville' => '',
            'id_cat' => '',
            'id_article' => '',
            'quantite' => '',
            'date_demande' => ''
        ];

        $values = [
            'id_ville' => trim((string)($input['id_ville'] ?? '')),
            'id_cat' => trim((string)($input['id_cat'] ?? '')),
            'id_article' => trim((string)($input['id_article'] ?? '')),
            'quantite' => trim((string)($input['quantite'] ?? '')),
            'date_demande' => trim((string)($input['date_demande'] ?? ''))
        ];

        if ($values['id_ville'] === '' || !ctype_digit($values['id_ville'])) {
            $errors['id_ville'] = "Veuillez sélectionner une ville.";
        }

        if ($values['id_cat'] === '' || !ctype_digit($values['id_cat'])) {
            $errors['id_cat'] = "Veuillez sélectionner une catégorie.";
        }

        if ($values['id_article'] === '' || !ctype_digit($values['id_article'])) {
            $errors['id_article'] = "Veuillez sélectionner un article.";
        }

        if ($values['quantite'] === '' || !is_numeric($values['quantite']) || (float)$values['quantite'] <= 0) {
            $errors['quantite'] = "La quantité doit être un nombre positif.";
        }

        if ($values['date_demande'] === '') {
            $errors['date_demande'] = "La date est obligatoire.";
        } else {
            $dt = DateTime::createFromFormat('Y-m-d', $values['date_demande']);
            if (!$dt || $dt->format('Y-m-d') !== $values['date_demande']) {
                $errors['date_demande'] = "La date est invalide.";
            }
        }

        $prixUnitaire = null;
        if ($errors['id_article'] === '') {
            $prixUnitaire = $this->articleRepo->prixUnitaire((int)$values['id_article']);
            if ($prixUnitaire === null) {
                $errors['id_article'] = "Article introuvable.";
            }
        }

        $ok = true;
        foreach ($errors as $msg) {
            if ($msg !== '') {
                $ok = false;
                break;
            }
        }

        $montant = 0.0;
        if ($ok && $prixUnitaire !== null) {
            $montant = $prixUnitaire * (float)$values['quantite'];
        }

        return [
            'ok' => $ok,
            'errors' => $errors,
            'values' => $values,
            'montant_totale' => $montant
        ];
    }

    public function create(array $values, float $montant): int
    {
        return $this->besoinRepo->insert([
            'id_article' => (int)$values['id_article'],
            'id_ville' => (int)$values['id_ville'],
            'quantite' => (float)$values['quantite'],
            'montant_totale' => $montant,
            'id_traboina' => 1,
            'date_demande' => $values['date_demande']
        ]);
    }

    public function update(int $id, array $values, float $montant): int
    {
        return $this->besoinRepo->update($id, [
            'id_article' => (int)$values['id_article'],
            'id_ville' => (int)$values['id_ville'],
            'quantite' => (float)$values['quantite'],
            'montant_totale' => $montant,
            'id_traboina' => 1,
            'date_demande' => $values['date_demande']
        ]);
    }

    public function all(): array
    {
        return $this->besoinRepo->all();
    }
}