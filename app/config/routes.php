<?php

use app\controllers\FrontOfficeController;
use app\controllers\BackOfficeController;
use flight\Engine;
use flight\net\Router;

/**
 * @var Router $router
 * @var Engine $app
 */

$frontOfficeController = new FrontOfficeController();
$backOfficeController = new BackOfficeController();

// FrontOffice routes
$router->get('/', [ $frontOfficeController, 'home' ]);
$router->get('/articles/article.php', [ $frontOfficeController, 'articleLegacyQuery' ]);
$router->get('/articles/@legacy', [ $frontOfficeController, 'articleLegacyRoute' ]);
$router->get('/article/@slug:[a-z0-9-]+', [ $frontOfficeController, 'article' ]);
$app->map('notFound', [ $frontOfficeController, 'notFound' ]);

// BackOffice routes
$router->get('/admin/login', [ $backOfficeController, 'adminLoginGet' ]);
$router->post('/admin/login', [ $backOfficeController, 'adminLoginPost' ]);
$router->post('/admin/logout', [ $backOfficeController, 'adminLogoutPost' ]);
$router->get('/admin', [ $backOfficeController, 'adminDashboard' ]);
$router->get('/admin/articles/create', [ $backOfficeController, 'adminArticleCreateGet' ]);
$router->post('/admin/articles/create', [ $backOfficeController, 'adminArticleCreatePost' ]);
$router->get('/admin/articles/@id:[0-9]+/edit', [ $backOfficeController, 'adminArticleEditGet' ]);
$router->post('/admin/articles/@id:[0-9]+/edit', [ $backOfficeController, 'adminArticleEditPost' ]);
$router->post('/admin/articles/@id:[0-9]+/delete', [ $backOfficeController, 'adminArticleDeletePost' ]);
$router->get('/backoffice.html', [ $backOfficeController, 'adminDashboard' ]);
$router->get('/backoffice/login.html', [ $backOfficeController, 'adminLoginGet' ]);
$router->post('/backoffice/login.html', [ $backOfficeController, 'adminLoginPost' ]);
$router->post('/backoffice/logout.html', [ $backOfficeController, 'adminLogoutPost' ]);
$router->get('/backoffice/articles/create.html', [ $backOfficeController, 'adminArticleCreateGet' ]);
$router->post('/backoffice/articles/create.html', [ $backOfficeController, 'adminArticleCreatePost' ]);
$router->get('/backoffice/articles/edit-@id:[0-9]+.html', [ $backOfficeController, 'adminArticleEditGet' ]);
$router->post('/backoffice/articles/edit-@id:[0-9]+.html', [ $backOfficeController, 'adminArticleEditPost' ]);
$router->post('/backoffice/articles/delete-@id:[0-9]+.html', [ $backOfficeController, 'adminArticleDeletePost' ]);
