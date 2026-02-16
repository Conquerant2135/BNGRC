<?php

// ========== Pages principales ==========

// Tableau de bord (accueil)
Flight::route('GET /', [DashboardController::class, 'index']);

// Saisie des besoins
Flight::route('GET /besoins', function () {
    Flight::render('besoins');
});

// Saisie des dons
Flight::route('GET /dons', function () {
    Flight::render('dons');
});

// Simulation du dispatch
Flight::route('GET /dispatch', [DispatchController::class, 'index']);

// Valider le dispatch
Flight::route('POST /dispatch/valider', [DispatchController::class, 'valider']);

// Gestion des villes
Flight::route('GET /villes', function () {
    Flight::render('villes');
});

// Gestion des types d'articles
Flight::route('GET /articles', [ArticleController::class, 'index']);
Flight::route('POST /articles/ajouter', [ArticleController::class, 'ajouter']);
Flight::route('POST /articles/modifier', [ArticleController::class, 'modifier']);
Flight::route('POST /articles/supprimer', [ArticleController::class, 'supprimer']);

// Flight::route('GET /' , function () {
//     Flight::render('home');
// });