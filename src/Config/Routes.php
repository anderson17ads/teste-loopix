<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$container = $app->getContainer();

$container['view'] = function ($container) {
	$viewPath = __DIR__ . '/../Views';

	$view = new \Slim\Views\Twig($viewPath, [
		'cache' => false
	]);

	$view->addExtension(new \Slim\Views\TwigExtension(
		$container->get('router'),
		$container->get('request')->getUri()
	));

	return $view;
};

$container['HomeController'] = function($container) {
    return new App\Controllers\HomeController($container);
};

$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages();
};

$app->get('/', 'HomeController:index');

$app->map(['get', 'post'], '/usuarios/{metodo}', App\Controllers\UsersController::class);
$app->map(['get', 'delete', 'put'], '/usuarios/{metodo}/{id}', App\Controllers\UsersController::class);