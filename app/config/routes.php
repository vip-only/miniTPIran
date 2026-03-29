<?php

use app\controllers\ElevageController;
use flight\Engine;
use flight\net\Router;
//use Flight;

/** 
 * @var Router $router 
 * @var Engine $app
 */
/*$router->get('/', function() use ($app) {
	$Welcome_Controller = new WelcomeController($app);
	$app->render('welcome', [ 'message' => 'It works!!' ]);
});*/

$Elevage_Controller = new ElevageController();
$router->get('/', function() {
	echo '<h1>Application demarree</h1><p>Routes actives: <a href="/hello-world/dev">/hello-world/dev</a></p>';
});
$router->get('/accueil', function() {
	echo '<h1>Accueil</h1><p>La route fonctionne.</p>';
});

// $router->get('/', function() {
//     $adminModel = new AdminModel(Flight::db());
//     $adminController = new AdminController($adminModel);
//     $adminController->login();
// });

// $router->post('/', function() {
//     $adminModel = new AdminModel(Flight::db());
//     $adminController = new AdminController($adminModel);
//     $adminController->login();
// });



//$router->get('/', \app\controllers\WelcomeController::class.'->home'); 

$router->get('/hello-world/@name', function($name) {
	echo '<h1>Hello world! Oh hey '.$name.'!</h1>';
});

// $router->group('/api', function() use ($router, $app) {
// 	$Api_Example_Controller = new ApiExampleController($app);
// 	$router->get('/users', [ $Api_Example_Controller, 'getUsers' ]);
// 	$router->get('/users/@id:[0-9]', [ $Api_Example_Controller, 'getUser' ]);
// 	$router->post('/users/@id:[0-9]', [ $Api_Example_Controller, 'updateUser' ]);
// });