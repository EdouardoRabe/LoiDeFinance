<?php

use flight\Engine;
use flight\net\Router;
use app\controllers\ApiController;

// Charge explicitement le contrôleur si l'autoload Composer ne couvre pas le namespace app\controllers
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'ApiController.php';

/** 
 * @var Router $router 
 * @var Engine $app
 */

// API JSON (only controller methods, no SQL/business logic here)
$router->get('/api/ping', [ApiController::class, 'ping']);
$router->get('/api/years', [ApiController::class, 'years']);
$router->get('/api/kpis', [ApiController::class, 'kpis']);
$router->get('/api/recettes', [ApiController::class, 'recettes']);
$router->get('/api/depenses', [ApiController::class, 'depenses']);
$router->get('/api/dispositions', [ApiController::class, 'dispositions']);
$router->get('/api/recette-types', [ApiController::class, 'recetteTypes']);
$router->get('/api/depense-types', [ApiController::class, 'depenseTypes']);
$router->get('/api/recettes-compare', [ApiController::class, 'recettesCompare']);
$router->get('/api/depenses-compare', [ApiController::class, 'depensesCompare']);
$router->get('/api/recettes-compare-multi', [ApiController::class, 'recettesCompareMulti']);
$router->get('/api/depenses-compare-multi', [ApiController::class, 'depensesCompareMulti']);
$router->get('/api/health', [ApiController::class, 'health']);

// Page d'accueil minimaliste (consommera l'API via JS)
$router->get('/', function() use ($app) {
	$app->render('home.php');
});

$router->get('/recettes', function() use ($app) {
	$app->render('recettes.php');
});

$router->get('/depenses', function() use ($app) {
	$app->render('depenses.php');
});

$router->get('/dispositions', function() use ($app) {
	$app->render('dispositions.php');
});

// Page de comparaison multi-années
$router->get('/comparaison', function() use ($app) {
	$app->render('comparaison.php');
});
