<?php

use app\controllers\AchatAlimentController;
use app\controllers\ElevageController;
use app\controllers\AnimalController;
use flight\Engine;
use flight\net\Router;
use App\Models\ElevageModel;
use App\Models\VenteModel;
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
$Animal_Controller = new AnimalController();
$AchatAnimal_Controller = new AchatAlimentController();
$router->get('/', [ $Elevage_Controller, 'insertionCapital' ]);
$router->post('/', [ $Elevage_Controller, 'insertionCapital' ]);
$router->get('/accueil', [ $Elevage_Controller, 'accueil' ]);
$router->get('/nourrir', [ $Elevage_Controller, 'nourrirAnimal' ]);
$router->get('/venteAnimal', [ $Elevage_Controller, 'venteAnimal' ]);
$router->get('/updateTypes', [ $Elevage_Controller, 'updateTypes' ]);
$router->post('/updateTypes', [ $Elevage_Controller, 'updateTypes' ]);
$router->get('/ajoutType', [ $Elevage_Controller, 'ajouterType' ]);
$router->post('/ajoutType', [ $Elevage_Controller, 'ajouterType' ]);
$router->get('/statit', [ $Elevage_Controller, 'goStat' ]);
$router->get('/stat', [ $Elevage_Controller, 'stat' ]);


$router->get('/achatAnimal', [ $Animal_Controller, 'achatAnimal' ]);
$router->get('/trachatAnimal', [ $Animal_Controller, 'trachatAnimal' ]);
$router->get('/achatAliment', [ $AchatAnimal_Controller, 'acheterAliment' ]);
$router->post('/acheterAliment', [ $AchatAnimal_Controller, 'acheterAliment' ]);
$router->get('/acheterAliment', [ $AchatAnimal_Controller, 'acheterAliment' ]);


$router->get('/ajoutCapital', [ $Animal_Controller, 'ajoutCapital' ]);
$router->get('/trajoutCapital', [ $Animal_Controller, 'trajoutCapital' ]);

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