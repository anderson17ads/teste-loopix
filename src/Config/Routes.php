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

$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages();
};

$app->map(['get', 'post', 'delete', 'put'], '/usuarios/{metodo}', App\Controllers\UsersController::class);