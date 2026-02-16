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
Flight::route('GET /dons', ['DonController', 'index']);
Flight::route('POST /dons', ['DonController', 'store']);
Flight::route('POST /dons/@id/update', ['DonController', 'update']);
Flight::route('POST /dons/@id/delete', ['DonController', 'delete']);

// Simulation du dispatch
Flight::route('GET /dispatch', function () {
    Flight::render('dispatch');
});

// Gestion des villes
Flight::route('GET /villes', ['VilleController', 'index']);
Flight::route('POST /villes', ['VilleController', 'store']);
Flight::route('POST /villes/@id/update', ['VilleController', 'update']);
Flight::route('POST /villes/@id/delete', ['VilleController', 'delete']);

// Gestion des types d'articles
Flight::route('GET /articles', function () {
    Flight::render('articles');
});

Flight::route('GET /' , function () {
    Flight::render('home');

});