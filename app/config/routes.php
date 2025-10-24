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

// Extensions API (schéma étendu)
$router->get('/api/secteurs', [ApiController::class, 'secteurs']);
$router->get('/api/croissance-secteur', [ApiController::class, 'croissanceSecteur']);
$router->get('/api/projets', [ApiController::class, 'projets']);
$router->get('/api/dette', [ApiController::class, 'dette']);
$router->get('/api/compare-dette', [ApiController::class, 'compareDette']);
$router->get('/api/postes', [ApiController::class, 'postes']);
$router->get('/api/indicateurs', [ApiController::class, 'indicateurs']);
$router->get('/api/indicateurs-serie', [ApiController::class, 'indicateursSerie']);
$router->get('/api/glossaire', [ApiController::class, 'glossaire']);
$router->get('/api/search-recettes', [ApiController::class, 'searchRecettes']);
$router->get('/api/search-depenses', [ApiController::class, 'searchDepenses']);

// Lang switcher (persist in session then redirect back)
$router->post('/lang', function() use ($app) {
    $lang = $_POST['lang'] ?? 'fr';
    if (function_exists('set_lang')) { set_lang($lang); }
    $back = $_SERVER['HTTP_REFERER'] ?? '/';
    $app->redirect($back);
});
$router->get('/lang', function() use ($app) {
    $lang = $_GET['lang'] ?? 'fr';
    if (function_exists('set_lang')) { set_lang($lang); }
    $back = $_SERVER['HTTP_REFERER'] ?? '/';
    $app->redirect($back);
});

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

$router->get('/secteurs', function() use ($app) {
	$app->render('secteurs.php');
});

$router->get('/projets', function() use ($app) {
	$app->render('projets.php');
});

$router->get('/dette', function() use ($app) {
	$app->render('dette.php');
});

$router->get('/postes', function() use ($app) {
	$app->render('postes.php');
});

$router->get('/indicateurs', function() use ($app) {
	$app->render('indicateurs.php');
});

$router->get('/glossaire', function() use ($app) {
	$app->render('glossaire.php');
});
