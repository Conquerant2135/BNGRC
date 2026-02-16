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

  public function validateAchat(array $input) {
    $errors = [
      'id_article' => '',
      'quantite' => '',
      'date_achat' => '',
      'montant' => ''
    ];

    $values = [
      'id_article' => trim((string)($input['id_article'] ?? '')),
      'quantite' => trim((string)($input['quantite'] ?? '')),
      'date_achat' => trim((string)($input['date_achat'] ?? ''))
    ];

    if ($values['id_article'] === '' || !ctype_digit($values['id_article'])) {
      $errors['id_article'] = "Veuillez choisir un article.";
    }

    if ($values['quantite'] === '' || !is_numeric($values['quantite']) || (float)$values['quantite'] <= 0) {
      $errors['quantite'] = "La quantité doit être un nombre positif.";
    }

    if ($values['date_achat'] === '') {
      $errors['date_achat'] = "La date est obligatoire.";
    }

    $ok = true;
    foreach ($errors as $m) {
      if ($m !== '') { $ok = false; break; }
    }

    if (!$ok) {
      return ['ok' => false, 'errors' => $errors, 'values' => $values];
    }

    $article = $this->repo->getArticleById((int)$values['id_article']);
    if (!$article) {
      $errors['id_article'] = "Article introuvable.";
      return ['ok' => false, 'errors' => $errors, 'values' => $values];
    }

    $taxe = $this->repo->getTaxeValeur();
    $quantite = (float)$values['quantite'];
    $prixUnitaire = (float)$article['prix_unitaire'];
    $montantHT = $quantite * $prixUnitaire;
    $montantTTC = $montantHT * (1 + ($taxe / 100));

    $disponible = $this->repo->getArgentDisponible();
    if ($montantTTC > $disponible) {
      $errors['montant'] = "Montant insuffisant en dons d'argent (TTC).";
      return ['ok' => false, 'errors' => $errors, 'values' => $values, 'montant_total' => $montantTTC];
    }

    $etatId = $this->repo->getDefaultEtatId();

    return [
      'ok' => true,
      'errors' => $errors,
      'values' => [
        'id_article' => (int)$values['id_article'],
        'quantite' => $quantite,
        'date_achat' => $values['date_achat'],
        'valeur_taux' => $taxe,
        'montant_total' => $montantTTC,
        'donateur' => 'Achat produit',
        'id_cat' => (int)$article['id_cat'],
        'id_etat' => $etatId
      ]
    ];
  }
}
