<?php

require_once __DIR__ . '/repositories/ArticleRepository.php';
require_once __DIR__ . '/repositories/BesoinRepository.php';
require_once __DIR__ . '/repositories/CategorieRepository.php';
require_once __DIR__ . '/repositories/TraboinaRepository.php';
require_once __DIR__ . '/repositories/UniteRepository.php';
require_once __DIR__ . '/repositories/VilleRepository.php';

// Services
require_once __DIR__ . '/services/BesoinService.php';

// Controllers
require_once __DIR__ . '/controllers/BesoinController.php';


// ========== Pages principales ==========

// Tableau de bord (accueil)
Flight::route('GET /', [DashboardController::class, 'index']);

// Saisie des besoins
// Flight::route('GET /besoins', ['besoinController' , 'index']);

// Saisie des dons
Flight::route('GET /dons', ['DonController', 'index']);
Flight::route('POST /dons', ['DonController', 'store']);
Flight::route('POST /dons/@id/update', ['DonController', 'update']);
Flight::route('POST /dons/@id/delete', ['DonController', 'delete']);

// Simulation du dispatch
Flight::route('GET /dispatch', [DispatchController::class, 'index']);

// Valider le dispatch
Flight::route('POST /dispatch/valider', [DispatchController::class, 'valider']);

// Gestion des villes
Flight::route('GET /villes', ['VilleController', 'index']);
Flight::route('POST /villes', ['VilleController', 'store']);
Flight::route('POST /villes/@id/update', ['VilleController', 'update']);
Flight::route('POST /villes/@id/delete', ['VilleController', 'delete']);

// Gestion des types d'articles
Flight::route('GET /articles', [ArticleController::class, 'index']);
Flight::route('POST /articles/ajouter', [ArticleController::class, 'ajouter']);
Flight::route('POST /articles/modifier', [ArticleController::class, 'modifier']);
Flight::route('POST /articles/supprimer', [ArticleController::class, 'supprimer']);

// Flight::route('GET /' , function () {
//     Flight::render('home');
// });

// Routes besoins (GET/POST)
BesoinController::register();