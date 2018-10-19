<?php 
namespace App\Controllers;

use App\Config\Database;

abstract class Controller
{
	protected $container;

	public function __construct(\Slim\Container $container)
	{
		$this->container = $container;

		$this->view->offsetSet('rand', rand(111111, 999999));
	}

	public function __get($key)
	{
		if ($this->container->{$key}) {
			return $this->container->{$key};
		}
	}

	public function __invoke($request, $response, $args) {
		$this->view->offsetSet('flash', $this->flash);
		
		$this->loadModel();

		if (isset($args['metodo']) && method_exists($this, $args['metodo'])) {
			return $this->{$args['metodo']}($request, $response, $args);
		}
   	}

   	/**
   	 * Carrega o model da classe e exitir
   	 *
   	 * @return void
   	 */
   	private function loadModel()
   	{
   		if (isset($this->model) && !is_null($this->model)) {
   			$class = "App\Model\\{$this->model}";
   			$this->container[$this->model] = new $class;
   		}
   	}
}