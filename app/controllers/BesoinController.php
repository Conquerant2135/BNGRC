<?php

class BesoinController
{
    public static function register(): void
    {
        Flight::route('GET /besoins', [self::class, 'index']);
        Flight::route('POST /besoins', [self::class, 'store']);
        Flight::route('POST /besoins/@id/update', [self::class, 'update']);
        Flight::route('POST /besoins/@id/delete', [self::class, 'delete']);
    }

    public static function index(): void
    {
        $catRepo = new CategorieRepository();
        $articleRepo = new ArticleRepository();
        $villeRepo = new VilleRepository(Flight::db());
        $besoinService = new BesoinService();

        $req = Flight::request();
        $editId = (string)($req->query->edit ?? '');

        $values = [
            'id_besoin' => '',
            'id_ville' => '',
            'id_cat' => '',
            'id_article' => '',
            'quantite' => '',
            'date_demande' => date('Y-m-d')
        ];

        $errors = [
            'id_ville' => '',
            'id_cat' => '',
            'id_article' => '',
            'quantite' => '',
            'date_demande' => ''
        ];

        $isEdit = false;
        if ($editId !== '' && ctype_digit($editId)) {
            $besoinRepo = new BesoinRepository();
            $besoin = $besoinRepo->findById((int)$editId);
            if ($besoin) {
                $values = [
                    'id_besoin' => (string)$besoin['id_besoin'],
                    'id_ville' => (string)$besoin['id_ville'],
                    'id_cat' => (string)$besoin['id_cat'],
                    'id_article' => (string)$besoin['id_article'],
                    'quantite' => (string)$besoin['quantite'],
                    'date_demande' => (string)$besoin['date_demande']
                ];
                $isEdit = true;
            }
        }

        $flashSuccess = '';
        $flashError = '';
        $success = (string)($req->query->success ?? '');
        $error = (string)($req->query->error ?? '');
        if ($success === '1') $flashSuccess = 'Besoin enregistré.';
        if ($success === '2') $flashSuccess = 'Besoin mis à jour.';
        if ($success === '3') $flashSuccess = 'Besoin supprimé.';
        if ($error === '1') $flashError = 'Action impossible.';

        Flight::render('besoins.php', [
            'cat' => $catRepo->all(),
            'article' => $articleRepo->all(),
            'ville' => $villeRepo->listAll(),
            'besoins' => $besoinService->all(),
            'values' => $values,
            'errors' => $errors,
            'isEdit' => $isEdit,
            'flashSuccess' => $flashSuccess,
            'flashError' => $flashError
        ]);
    }

    public static function store(): void
    {
        $besoinService = new BesoinService();
        $res = $besoinService->validate($_POST);
        if ($res['ok']) {
            $besoinService->create($res['values'], (float)$res['montant_totale']);
            Flight::redirect('/besoins?success=1');
            return;
        }

        $catRepo = new CategorieRepository();
        $articleRepo = new ArticleRepository();
        $villeRepo = new VilleRepository(Flight::db());

        Flight::render('besoins.php', [
            'cat' => $catRepo->all(),
            'article' => $articleRepo->all(),
            'ville' => $villeRepo->listAll(),
            'besoins' => $besoinService->all(),
            'values' => $res['values'],
            'errors' => $res['errors'],
            'isEdit' => false,
            'flashSuccess' => '',
            'flashError' => 'Veuillez corriger le formulaire.'
        ]);
    }

    public static function update($id): void
    {
        if (!ctype_digit((string)$id)) {
            Flight::redirect(BASE_URL . '/besoins?error=1');
            return;
        }

        $besoinService = new BesoinService();
        $res = $besoinService->validate($_POST);
        if ($res['ok']) {
            $besoinService->update((int)$id, $res['values'], (float)$res['montant_totale']);
            Flight::redirect('/besoins?success=2');
            return;
        }

        $res['values']['id_besoin'] = (string)$id;

        $catRepo = new CategorieRepository();
        $articleRepo = new ArticleRepository();
        $villeRepo = new VilleRepository(Flight::db());

        Flight::render('besoins.php', [
            'cat' => $catRepo->all(),
            'article' => $articleRepo->all(),
            'ville' => $villeRepo->listAll(),
            'besoins' => $besoinService->all(),
            'values' => $res['values'],
            'errors' => $res['errors'],
            'isEdit' => true,
            'flashSuccess' => '',
            'flashError' => 'Veuillez corriger le formulaire.'
        ]);
    }

    public static function delete($id): void
    {
        if (!ctype_digit((string)$id)) {
            Flight::redirect('/besoins?error=1');
            return;
        }

        $repo = new BesoinRepository();
        $repo->delete((int)$id);
        Flight::redirect('/besoins?success=3');
    }
}
