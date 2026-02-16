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
Flight::route('GET /dispatch', function () {
    Flight::render('dispatch');
});

// Gestion des villes
Flight::route('GET /villes', function () {
    Flight::render('villes');
});

// Gestion des types d'articles
Flight::route('GET /articles', function () {
    Flight::render('articles');
});
