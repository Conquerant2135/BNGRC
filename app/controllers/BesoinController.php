<?php

class BesoinController
{
    public static function register(): void
    {
        Flight::route('GET /besoin', [self::class, 'index']);
        Flight::route('POST /besoin', [self::class, 'store']);
    }

    public static function index(): void
    {
        $catRepo = new CategorieRepository();
        $articleRepo = new ArticleRepository();
        $villeRepo = new VilleRepository(Flight::db());
        $besoinService = new BesoinService();

        Flight::render('besoins.php', [
            'cat' => $catRepo->all(),
            'article' => $articleRepo->all(),
            'ville' => $villeRepo->listAll(),
            'besoins' => $besoinService->all()
        ]);
    }

    public static function store(): void
    {
        $besoinService = new BesoinService();
        $besoinService->create($_POST);
        \Flight::redirect('/besoin');
    }
}