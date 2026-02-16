<?php
require_once __DIR__ . '/config.php';

Flight::register('db', 'PDO', array(
	"mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
	DB_USER,
	DB_PASS,
	array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
	)
));

require_once __DIR__ . '/repositories/DonRepository.php';
require_once __DIR__ . '/services/DonService.php';
require_once __DIR__ . '/controllers/DonController.php';
require_once __DIR__ . '/repositories/VilleRepository.php';
require_once __DIR__ . '/services/VilleService.php';
require_once __DIR__ . '/controllers/VilleController.php';

Flight::set('flight.views.path', __DIR__ . '/views');

// Controllers
require_once __DIR__ . '/controllers/ArticleController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/DispatchController.php';

require_once __DIR__ . '/routes.php';
