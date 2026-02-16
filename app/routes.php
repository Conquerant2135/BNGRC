<?php

// ========== Pages principales ==========

// Tableau de bord (accueil)
Flight::route('GET /', function () {
    Flight::render('dashboard');
});

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
Flight::route('GET /articles', function () {
    Flight::render('articles');
});

Flight::route('GET /' , function () {
    Flight::render('home');

});