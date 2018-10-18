<?php 
namespace App\Controllers;

use App\Config\Database;

abstract class Controller
{
	protected $container;

	public function __construct(\Slim\Container $container)
	{
		$this->container = $container;

		$db = new Database;
		$this->container['db'] = $db->conn;
	}

	public function __get($key)
	{
		if ($this->container->{$key}) {
			return $this->container->{$key};
		}
	}

	public function __invoke($request, $response, $args) {
		$this->view->offsetSet('flash', $this->flash);
		
		if (isset($args['metodo']) && method_exists($this, $args['metodo'])) {
			return $this->{$args['metodo']}($request, $response, $args);
		}
   	}
}