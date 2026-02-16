<?php

require_once __DIR__ . '/repositories/ArticleRepository.php';
require_once __DIR__ . '/repositories/BesoinRepository.php';
require_once __DIR__ . '/repositories/CategorieRepository.php';
require_once __DIR__ . '/repositories/TraboinaRepository.php';
require_once __DIR__ . '/repositories/UniteRepository.php';
require_once __DIR__ . '/repositories/UserRepository.php';
require_once __DIR__ . '/repositories/VilleRepository.php';

// Services
require_once __DIR__ . '/services/BesoinService.php';
require_once __DIR__ . '/services/UserService.php';
require_once __DIR__ . '/services/Validator.php';

// Controllers
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/BesoinController.php';
require_once __DIR__ . '/controllers/HomeController.php';


// ========== Pages principales ==========

// Tableau de bord (accueil)
Flight::route('GET /', function () {
    Flight::render('dashboard');
});

// Saisie des besoins
Flight::route('GET /besoins', ['besoinController' , 'index']);

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

Flight::route('GET /' , function () {
    Flight::render('home');

});

// Routes besoins (GET/POST)
BesoinController::register();