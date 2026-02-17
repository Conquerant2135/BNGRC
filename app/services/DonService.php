<?php
class DonService {
  private $repo;
  public function __construct(DonRepository $repo) { $this->repo = $repo; }

  private function strLength(string $value): int {
    if (function_exists('mb_strlen')) {
      return mb_strlen($value);
    }

    return strlen($value);
  }

  public function validate(array $input) {
    $errors = [
      'donateur' => '',
      'date_don' => '',
      'id_cat' => '',
      'id_article' => '',
      'quantite' => '',
      'id_etat' => ''
    ];

    $values = [
      'donateur' => trim((string)($input['donateur'] ?? '')),
      'date_don' => trim((string)($input['date_don'] ?? '')),
      'id_cat' => trim((string)($input['id_cat'] ?? '')),
      'id_article' => trim((string)($input['id_article'] ?? '')),
      'quantite' => trim((string)($input['quantite'] ?? '')),
      'id_etat' => trim((string)($input['id_etat'] ?? ''))
    ];

    if ($this->strLength($values['donateur']) < 2) {
      $errors['donateur'] = "Le donateur est obligatoire (min 2 caractères).";
    }

    if ($values['date_don'] === '') {
      $errors['date_don'] = "La date est obligatoire.";
    }

    if ($values['id_cat'] === '' || !ctype_digit($values['id_cat'])) {
      $errors['id_cat'] = "Veuillez choisir une catégorie.";
    }

    if ($values['id_article'] !== '' && !ctype_digit($values['id_article'])) {
      $errors['id_article'] = "Article invalide.";
    }

    if ($values['quantite'] === '' || !is_numeric($values['quantite']) || (float)$values['quantite'] <= 0) {
      $errors['quantite'] = "La quantité doit être un nombre positif.";
    }

    if ($values['id_etat'] === '' || !ctype_digit($values['id_etat'])) {
      $errors['id_etat'] = "Veuillez choisir un état.";
    }

    $ok = true;
    foreach ($errors as $m) {
      if ($m !== '') { $ok = false; break; }
    }

    return ['ok' => $ok, 'errors' => $errors, 'values' => $values];
  }

  public function achatProduit(array $input){
    return $this->validateAchat($input);
  }  
  public function validateAchat(array $input)
  {
    $errors = [
      'id_article' => '',
      'quantite' => '',
      'date_achat' => '',
      'montant' => '',
      'achat' => ''
    ];

    $values = [
      'id_article' => trim((string)($input['id_article'] ?? '')),
      'quantite' => trim((string)($input['quantite'] ?? '')),
      'date_achat' => trim((string)($input['date_achat'] ?? ''))
    ];

    if ($values['id_article'] === '' || !ctype_digit($values['id_article'])) {
      $errors['id_article'] = 'Article invalide.';
    }

    if ($values['quantite'] === '' || !is_numeric($values['quantite']) || (float)$values['quantite'] <= 0) {
      $errors['quantite'] = 'Quantité invalide.';
    }

    if ($values['date_achat'] === '') {
      $errors['date_achat'] = 'Date invalide.';
    }

    // Achat déjà présent (pas de vérif "restant")
    if ($errors['id_article'] === '' && $errors['quantite'] === '') {
      if ($this->repo->getDonSimilaire((float)$values['quantite'], (int)$values['id_article'])) {
        $errors['achat'] = 'Achat déjà présent.';
      }
    }

    if ($errors['id_article'] === '') {
      $prix = $this->repo->getPrixUnitaire((int)$values['id_article']);
      if ($prix === null) {
        $errors['id_article'] = 'Article introuvable.';
      } else {
        $taxe = (float)$this->repo->getTaxeValeur();
        $montantHt = $prix * (float)$values['quantite'];
        $montantTtc = $montantHt * (1 + $taxe / 100);

        $values['prix_unitaire'] = $prix;
        $values['montant_ht'] = $montantHt;
        $values['montant_ttc'] = $montantTtc;
        $values['taxe'] = $taxe;

        // clés attendues par DonRepository::achatProduit()
        $values['valeur_taux'] = $taxe;
        $values['montant_total'] = $montantTtc;
        $values['donateur'] = 'Achat';
        $article = $this->repo->getArticleById((int)$values['id_article']);
        $values['id_cat'] = $article ? (int)$article['id_cat'] : null;
        $values['id_etat'] = $this->repo->getDefaultEtatId();

        $argent = (float)$this->repo->getArgentDisponible();
        if ($argent < $montantTtc) {
          $errors['montant'] = 'Montant insuffisant.';
        }
      }
    }

    $ok = true;
    foreach ($errors as $e) {
      if ($e !== '') { $ok = false; break; }
    }

    return ['ok' => $ok, 'errors' => $errors, 'values' => $values];
  }

}
