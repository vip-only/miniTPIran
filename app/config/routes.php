<?php

use app\controllers\FrontOfficeController;
use flight\Engine;
use flight\net\Router;

/**
 * @var Router $router
 * @var Engine $app
 */

$frontOfficeController = new FrontOfficeController();

// FrontOffice routes
$router->get('/', [ $frontOfficeController, 'home' ]);
$router->get('/articles/article.php', [ $frontOfficeController, 'articleLegacyQuery' ]);
$router->get('/articles/@legacy', [ $frontOfficeController, 'articleLegacyRoute' ]);
$app->map('notFound', [ $frontOfficeController, 'notFound' ]);

// BackOffice routes
$router->get('/admin/login', [ $frontOfficeController, 'adminLoginGet' ]);
$router->post('/admin/login', [ $frontOfficeController, 'adminLoginPost' ]);
$router->get('/admin', [ $frontOfficeController, 'adminDashboard' ]);
$router->get('/admin/articles/create', [ $frontOfficeController, 'adminArticleCreateGet' ]);
$router->post('/admin/articles/create', [ $frontOfficeController, 'adminArticleCreatePost' ]);
$router->get('/admin/articles/@id:[0-9]+/edit', [ $frontOfficeController, 'adminArticleEditGet' ]);
$router->post('/admin/articles/@id:[0-9]+/edit', [ $frontOfficeController, 'adminArticleEditPost' ]);
$router->post('/admin/articles/@id:[0-9]+/delete', [ $frontOfficeController, 'adminArticleDeletePost' ]);
