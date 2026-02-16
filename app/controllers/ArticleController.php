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
        $db = Flight::db();

        // Articles avec catégorie et unité
        $articles = $db->query("
            SELECT a.id, a.nom, a.prix_unitaire,
                   c.id AS id_cat, c.nom AS categorie,
                   u.id AS id_unite, u.libelle AS unite
            FROM bngrc_article a
            LEFT JOIN bngrc_categorie c ON c.id = a.id_cat
            LEFT JOIN bngrc_unite u ON u.id = a.id_unite
            ORDER BY c.nom, a.nom
        ")->fetchAll(PDO::FETCH_ASSOC);

        // Catégories avec compteur
        $categories = $db->query("
            SELECT c.id, c.nom, COUNT(a.id) AS nb_articles
            FROM bngrc_categorie c
            LEFT JOIN bngrc_article a ON a.id_cat = c.id
            GROUP BY c.id, c.nom
            ORDER BY c.nom
        ")->fetchAll(PDO::FETCH_ASSOC);

        // Unités pour le formulaire
        $unites = $db->query("SELECT id, libelle FROM bngrc_unite ORDER BY libelle")->fetchAll(PDO::FETCH_ASSOC);

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
        $db = Flight::db();

        $nom       = trim($_POST['nom_article'] ?? '');
        $idCat     = (int) ($_POST['categorie'] ?? 0);
        $idUnite   = (int) ($_POST['unite_defaut'] ?? 0);
        $prix      = (float) ($_POST['prix_unitaire'] ?? 0);

        if ($nom === '' || $idCat === 0 || $idUnite === 0) {
            self::redir('/articles?error=' . urlencode('Veuillez remplir tous les champs obligatoires.'));
        }

        try {
            $stmt = $db->prepare("
                INSERT INTO bngrc_article (nom, id_cat, id_unite, prix_unitaire)
                VALUES (:nom, :id_cat, :id_unite, :prix)
            ");
            $stmt->execute([
                ':nom'     => $nom,
                ':id_cat'  => $idCat,
                ':id_unite'=> $idUnite,
                ':prix'    => $prix,
            ]);

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
        $db = Flight::db();
        $id = (int) ($_POST['id'] ?? 0);

        if ($id === 0) {
            self::redir('/articles?error=' . urlencode('Article introuvable.'));
        }

        try {
            $stmt = $db->prepare("DELETE FROM bngrc_article WHERE id = :id");
            $stmt->execute([':id' => $id]);
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
        $db = Flight::db();

        $id        = (int) ($_POST['id'] ?? 0);
        $nom       = trim($_POST['nom_article'] ?? '');
        $idCat     = (int) ($_POST['categorie'] ?? 0);
        $idUnite   = (int) ($_POST['unite_defaut'] ?? 0);
        $prix      = (float) ($_POST['prix_unitaire'] ?? 0);

        if ($id === 0 || $nom === '' || $idCat === 0 || $idUnite === 0) {
            self::redir('/articles?error=' . urlencode('Veuillez remplir tous les champs obligatoires.'));
        }

        try {
            $stmt = $db->prepare("
                UPDATE bngrc_article
                SET nom = :nom, id_cat = :id_cat, id_unite = :id_unite, prix_unitaire = :prix
                WHERE id = :id
            ");
            $stmt->execute([
                ':nom'     => $nom,
                ':id_cat'  => $idCat,
                ':id_unite'=> $idUnite,
                ':prix'    => $prix,
                ':id'      => $id,
            ]);

            self::redir('/articles?success=' . urlencode('Article modifié avec succès.'));
        } catch (Exception $e) {
            self::redir('/articles?error=' . urlencode($e->getMessage()));
        }
    }
}
