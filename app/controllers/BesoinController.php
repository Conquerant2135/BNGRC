<?php

class BesoinController
{
    public static function index(): void
    {
        $catRepo = new CategorieRepository();
        $articleRepo = new ArticleRepository();
        $villeRepo = new VilleRepository();
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
        Flight::redirect('/besoin');
    }
}