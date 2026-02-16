<?php
require_once __DIR__ . '/config.php';

// Database registration (commented out for template phase)
// Flight::register('db', 'PDO', array(
//     "pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME,
//     DB_USER,
//     DB_PASS,
//     array(
//         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
//     )
// ));

Flight::set('flight.views.path', __DIR__ . '/views');

require_once __DIR__ . '/routes.php';
