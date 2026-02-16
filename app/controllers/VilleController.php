<?php
class VilleController {
  public static function index() {
    $pdo = Flight::db();
    $repo = new VilleRepository($pdo);

    $req = Flight::request();
    $editId = (string)($req->query->edit ?? '');

    $values = [
      'id_ville' => '',
      'nom_ville' => '',
      'id_region' => '',
      'nb_sinistres' => '0'
    ];

    $errors = [
      'nom_ville' => '',
      'id_region' => '',
      'nb_sinistres' => ''
    ];

    $isEdit = false;
    if ($editId !== '' && ctype_digit($editId)) {
      $ville = $repo->findById((int)$editId);
      if ($ville) {
        $values = [
          'id_ville' => (string)$ville['id_ville'],
          'nom_ville' => (string)$ville['nom_ville'],
          'id_region' => (string)$ville['id_region'],
          'nb_sinistres' => (string)$ville['nb_sinistres']
        ];
        $isEdit = true;
      }
    }

    $flashSuccess = '';
    $flashError = '';
    $success = (string)($req->query->success ?? '');
    $error = (string)($req->query->error ?? '');
    if ($success === '1') $flashSuccess = 'Ville enregistrée.';
    if ($success === '2') $flashSuccess = 'Ville mise à jour.';
    if ($success === '3') $flashSuccess = 'Ville supprimée.';
    if ($error === '1') $flashError = 'Action impossible.';

    Flight::render('villes', [
      'villes' => $repo->listAll(),
      'regions' => $repo->listRegions(),
      'values' => $values,
      'errors' => $errors,
      'isEdit' => $isEdit,
      'flashSuccess' => $flashSuccess,
      'flashError' => $flashError
    ]);
  }

  public static function store() {
    $pdo = Flight::db();
    $repo = new VilleRepository($pdo);
    $svc = new VilleService($repo);

    $req = Flight::request();
    $input = [
      'nom_ville' => $req->data->nom_ville,
      'id_region' => $req->data->id_region,
      'nb_sinistres' => $req->data->nb_sinistres
    ];

    $res = $svc->validate($input);
    if ($res['ok']) {
      $repo->create($res['values']);
      Flight::redirect( '/villes?success=1');
      return;
    }

    Flight::render('villes', [
      'villes' => $repo->listAll(),
      'regions' => $repo->listRegions(),
      'values' => $res['values'],
      'errors' => $res['errors'],
      'isEdit' => false,
      'flashSuccess' => '',
      'flashError' => 'Veuillez corriger le formulaire.'
    ]);
  }

  public static function update($id) {
    $pdo = Flight::db();
    $repo = new VilleRepository($pdo);
    $svc = new VilleService($repo);

    if (!ctype_digit((string)$id)) {
      Flight::redirect( '/villes?error=1');
      return;
    }

    $req = Flight::request();
    $input = [
      'nom_ville' => $req->data->nom_ville,
      'id_region' => $req->data->id_region,
      'nb_sinistres' => $req->data->nb_sinistres
    ];

    $res = $svc->validate($input);
    if ($res['ok']) {
      $repo->update((int)$id, $res['values']);
      Flight::redirect('/villes?success=2');
      return;
    }

    $res['values']['id_ville'] = (string)$id;

    Flight::render('villes', [
      'villes' => $repo->listAll(),
      'regions' => $repo->listRegions(),
      'values' => $res['values'],
      'errors' => $res['errors'],
      'isEdit' => true,
      'flashSuccess' => '',
      'flashError' => 'Veuillez corriger le formulaire.'
    ]);
  }

  public static function delete($id) {
    $pdo = Flight::db();
    $repo = new VilleRepository($pdo);

    if (!ctype_digit((string)$id)) {
      Flight::redirect('/villes?error=1');
      return;
    }

    $repo->delete((int)$id);
    Flight::redirect('/villes?success=3');
  }
}
