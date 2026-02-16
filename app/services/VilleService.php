<?php
class VilleService {
  private $repo;
  public function __construct(VilleRepository $repo) { $this->repo = $repo; }

  public function validate(array $input) {
    $errors = [
      'nom_ville' => '',
      'id_region' => '',
      'nb_sinistres' => ''
    ];

    $values = [
      'nom_ville' => trim((string)($input['nom_ville'] ?? '')),
      'id_region' => trim((string)($input['id_region'] ?? '')),
      'nb_sinistres' => trim((string)($input['nb_sinistres'] ?? '0'))
    ];

    if (mb_strlen($values['nom_ville']) < 2) {
      $errors['nom_ville'] = "Le nom de la ville est obligatoire (min 2 caractères).";
    }

    if ($values['id_region'] === '' || !ctype_digit($values['id_region'])) {
      $errors['id_region'] = "Veuillez choisir une région.";
    }

    if ($values['nb_sinistres'] === '') $values['nb_sinistres'] = '0';
    if (!ctype_digit($values['nb_sinistres'])) {
      $errors['nb_sinistres'] = "Le nombre de sinistrés doit être un entier positif.";
    }

    $ok = true;
    foreach ($errors as $m) {
      if ($m !== '') { $ok = false; break; }
    }

    return ['ok' => $ok, 'errors' => $errors, 'values' => $values];
  }
}
