<?php
namespace App\Controllers;

use App\Controllers\Controller;

class UsersController extends Controller
{
	/**
	 * Recupera todos os usuários
	 *
	 * @return void
	 */
	public function index($request, $response, $args)
	{
		try {
			$stmt = $this->db->query('SELECT * FROM users');

			$users = $stmt->fetchAll(\PDO::FETCH_OBJ);
		} catch (\PDOException $e) {
			$retorno = $e->getMessage();
		}

		return $this->view->render($response, 'Users/index.twig', [
			'users' => $users
		]);
	}

	/**
	 * Adiciona um novo usuário
	 *
	 * @return void
	 */
	public function adicionar($request, $response, $args)
	{
		$retorno = '';

		$name 	  = $this->request->getParam('name');
		$password = $this->request->getParam('password');
		$email 	  = $this->request->getParam('email');

		try {
			$db = new Database;
			$stmt = $db->conn->prepare("INSERT INTO users (name, password, email) VALUES (:name, :password, :email)");

			$stmt->bindParam(':name', $name);
			$stmt->bindParam(':password', $password);
			$stmt->bindParam(':email', $email);

			$stmt->execute();

			$retorno = 'Usuário inserido com sucesso!';
			
		} catch (\PDOException $e) {
			$retorno = $e->getMessage();
		}

		return $this->view->render($response, 'Usuarios/adicionar.twig', [
			'retorno' => $retorno
		]);
	}

	/**
	 * Edita o usuário
	 *
	 * @return void
	 */
	public function edit($request, $response, $args)
	{
		$retorno  = '';

		$id 	  = $this->request->getAttribute('id');
		$name 	  = $this->request->getParam('name');
		$password = $this->request->getParam('password');
		$email 	  = $this->request->getParam('email');

		$sql = "UPDATE users SET name = :name, password = :password, email = :email WHERE id = {$id}";

		try {
			$db = new Database;
			$stmt = $db->conn->prepare($sql);

			$stmt->bindParam('name', $name);
			$stmt->bindParam('password', $password);
			$stmt->bindParam('email', $email);

			$stmt->execute();

			$retorno = 'Usuário alterado com sucesso!';
		} catch (\PDOException $e) {
			$retorno = $e->getMessage();
		}

		return $this->view->render($response, 'Usuarios/editar.twig', [
			'retorno' => $retorno
		]);
	}

	/**
	 * Deleta o usuário
	 *
	 * @return void
	 */
	public function delete($request, $response, $args)
	{
		$retorno  = '';

		$id = $this->request->getAttribute('id');

		try {
			$db = new Database;
			$stmt = $db->conn->prepare("DELETE FROM users WHERE id = {$id}");
			
			$stmt->execute();

			echo '{"notice" : {"message" : "Usuário deletado com sucesso!"}}';

		} catch (\PDOException $e) {
			echo '{"error" : {"message" : '. $e->getMessage() .'}}';
		}

		$retorno  = '';
	}
}