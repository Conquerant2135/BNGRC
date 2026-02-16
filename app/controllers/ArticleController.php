<?php

class ArticleController
{
    private static function redir(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    /**
     * GET /articles — Liste des articles
     */
    public static function index()
    {
        $articleRepo   = new ArticleRepository();
        $categorieRepo = new CategorieRepository();
        $uniteRepo     = new UniteRepository();

        $articles   = $articleRepo->allWithDetails();
        $categories = $categorieRepo->allWithArticleCount();
        $unites     = $uniteRepo->all();

        $success = $_GET['success'] ?? null;
        $error   = $_GET['error']   ?? null;

        Flight::render('articles', [
            'articles'   => $articles,
            'categories' => $categories,
            'unites'     => $unites,
            'success'    => $success,
            'error'      => $error,
        ]);
    }

    /**
     * POST /articles/ajouter — Ajouter un article
     */
    public static function ajouter()
    {
        $nom       = trim($_POST['nom_article'] ?? '');
        $idCat     = (int) ($_POST['categorie'] ?? 0);
        $idUnite   = (int) ($_POST['unite_defaut'] ?? 0);
        $prix      = (float) ($_POST['prix_unitaire'] ?? 0);

        if ($nom === '' || $idCat === 0 || $idUnite === 0) {
            self::redir('/articles?error=' . urlencode('Veuillez remplir tous les champs obligatoires.'));
        }

        try {
            $articleRepo = new ArticleRepository();
            $articleRepo->insert($nom, $idCat, $idUnite, $prix);
            self::redir('/articles?success=' . urlencode('Article ajouté avec succès.'));
        } catch (Exception $e) {
            self::redir('/articles?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * POST /articles/supprimer — Supprimer un article
     */
    public static function supprimer()
    {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id === 0) {
            self::redir('/articles?error=' . urlencode('Article introuvable.'));
        }

        try {
            $articleRepo = new ArticleRepository();
            $articleRepo->delete($id);
            self::redir('/articles?success=' . urlencode('Article supprimé.'));
        } catch (Exception $e) {
            self::redir('/articles?error=' . urlencode('Impossible de supprimer : cet article est utilisé dans des besoins ou des dons.'));
        }
    }

    /**
     * POST /articles/modifier — Modifier un article
     */
    public static function modifier()
    {
        $id        = (int) ($_POST['id'] ?? 0);
        $nom       = trim($_POST['nom_article'] ?? '');
        $idCat     = (int) ($_POST['categorie'] ?? 0);
        $idUnite   = (int) ($_POST['unite_defaut'] ?? 0);
        $prix      = (float) ($_POST['prix_unitaire'] ?? 0);

        if ($id === 0 || $nom === '' || $idCat === 0 || $idUnite === 0) {
            self::redir('/articles?error=' . urlencode('Veuillez remplir tous les champs obligatoires.'));
        }

        try {
            $articleRepo = new ArticleRepository();
            $articleRepo->update($id, $nom, $idCat, $idUnite, $prix);
            self::redir('/articles?success=' . urlencode('Article modifié avec succès.'));
        } catch (Exception $e) {
            self::redir('/articles?error=' . urlencode($e->getMessage()));
        }
    }
}
