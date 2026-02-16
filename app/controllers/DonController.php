<?php
class DonController {
  public static function index() {
    $pdo = Flight::db();
    $repo = new DonRepository($pdo);

    $req = Flight::request();
    $editId = (string)($req->query->edit ?? '');

    $values = [
      'id_don' => '',
      'donateur' => '',
      'date_don' => date('Y-m-d'),
      'id_cat' => '',
      'id_article' => '',
      'quantite' => '',
      'id_etat' => ''
    ];

    $errors = [
      'donateur' => '',
      'date_don' => '',
      'id_cat' => '',
      'id_article' => '',
      'quantite' => '',
      'id_etat' => ''
    ];

    $isEdit = false;
    if ($editId !== '' && ctype_digit($editId)) {
      $don = $repo->findById((int)$editId);
      if ($don) {
        $values = [
          'id_don' => (string)$don['id_don'],
          'donateur' => (string)$don['donateur'],
          'date_don' => (string)$don['date_don'],
          'id_cat' => (string)$don['id_cat'],
          'id_article' => (string)($don['id_article'] ?? ''),
          'quantite' => (string)$don['quantite'],
          'id_etat' => (string)$don['id_etat']
        ];
        $isEdit = true;
      }
    }

    $flashSuccess = '';
    $flashError = '';
    $success = (string)($req->query->success ?? '');
    $error = (string)($req->query->error ?? '');
    if ($success === '1') $flashSuccess = 'Don enregistré.';
    if ($success === '2') $flashSuccess = 'Don mis à jour.';
    if ($success === '3') $flashSuccess = 'Don supprimé.';
    if ($error === '1') $flashError = 'Action impossible.';

    Flight::render('dons', [
      'dons' => $repo->listAll(),
      'categories' => $repo->listCategories(),
      'articles' => $repo->listArticles(),
      'etats' => $repo->listEtats(),
      'values' => $values,
      'errors' => $errors,
      'isEdit' => $isEdit,
      'flashSuccess' => $flashSuccess,
      'flashError' => $flashError
    ]);
  }

  public static function store() {
    $pdo = Flight::db();
    $repo = new DonRepository($pdo);
    $svc = new DonService($repo);

    $req = Flight::request();
    $input = [
      'donateur' => $req->data->donateur,
      'date_don' => $req->data->date_don,
      'id_cat' => $req->data->id_cat,
      'id_article' => $req->data->id_article,
      'quantite' => $req->data->quantite,
      'id_etat' => $req->data->id_etat
    ];

    $res = $svc->validate($input);
    if ($res['ok']) {
      $repo->create($res['values']);
      Flight::redirect(BASE_URL . '/dons?success=1');
      return;
    }

    Flight::render('dons', [
      'dons' => $repo->listAll(),
      'categories' => $repo->listCategories(),
      'articles' => $repo->listArticles(),
      'etats' => $repo->listEtats(),
      'values' => $res['values'],
      'errors' => $res['errors'],
      'isEdit' => false,
      'flashSuccess' => '',
      'flashError' => 'Veuillez corriger le formulaire.'
    ]);
  }

  public static function update($id) {
    $pdo = Flight::db();
    $repo = new DonRepository($pdo);
    $svc = new DonService($repo);

    if (!ctype_digit((string)$id)) {
      Flight::redirect(BASE_URL . '/dons?error=1');
      return;
    }

    $req = Flight::request();
    $input = [
      'donateur' => $req->data->donateur,
      'date_don' => $req->data->date_don,
      'id_cat' => $req->data->id_cat,
      'id_article' => $req->data->id_article,
      'quantite' => $req->data->quantite,
      'id_etat' => $req->data->id_etat
    ];

    $res = $svc->validate($input);
    if ($res['ok']) {
      $repo->update((int)$id, $res['values']);
      Flight::redirect(BASE_URL . '/dons?success=2');
      return;
    }

    $res['values']['id_don'] = (string)$id;

    Flight::render('dons', [
      'dons' => $repo->listAll(),
      'categories' => $repo->listCategories(),
      'articles' => $repo->listArticles(),
      'etats' => $repo->listEtats(),
      'values' => $res['values'],
      'errors' => $res['errors'],
      'isEdit' => true,
      'flashSuccess' => '',
      'flashError' => 'Veuillez corriger le formulaire.'
    ]);
  }

  public static function delete($id) {
    $pdo = Flight::db();
    $repo = new DonRepository($pdo);

    if (!ctype_digit((string)$id)) {
      Flight::redirect(BASE_URL . '/dons?error=1');
      return;
    }

    $repo->delete((int)$id);
    Flight::redirect(BASE_URL . '/dons?success=3');
  }
}
