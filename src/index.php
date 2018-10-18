<?php
require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$app = new \Slim\App([
	'settings' => [
		'displayErrorDetails' => true
	]
]);

require __DIR__ . '/Config/Routes.php';

$app->run();
