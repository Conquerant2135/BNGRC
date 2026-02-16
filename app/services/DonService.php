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
}
