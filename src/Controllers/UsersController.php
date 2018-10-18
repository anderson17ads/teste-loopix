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
	public function listar($request, $response, $args)
	{
		try {
			$stmt = $this->db->query('SELECT * FROM users');

			$users = $stmt->fetchAll(\PDO::FETCH_OBJ);
		} catch (\PDOException $e) {
			$retorno = $e->getMessage();
		}

		$this->flash->addMessage('Alert', 'Teste!');

		return $this->view->render($response, 'Users/listar.twig', [
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

		if ($request->isPost()) {
			$name 	  = $request->getParam('name');
			$password = $request->getParam('password');
			$email 	  = $request->getParam('email');

			try {
				$stmt = $this->db->prepare("INSERT INTO users (name, password, email) VALUES (:name, :password, :email)");

				$stmt->bindParam(':name', $name);
				$stmt->bindParam(':password', $password);
				$stmt->bindParam(':email', $email);

				$stmt->execute();

				$this->flash->addMessage('Alert', 'Usuário inserido com sucesso!');				
			} catch (\PDOException $e) {
				$retorno = $e->getMessage();
			}
		}

		return $this->view->render($response, 'Users/adicionar.twig');
	}

	/**
	 * Edita o usuário
	 *
	 * @return void
	 */
	public function editar($request, $response, $args)
	{
		$retorno  = '';

		$id = $request->getAttribute('id');

		if ($request->isPost()) {
			$name 	  = $request->getParam('name');
			$password = $request->getParam('password');
			$email 	  = $request->getParam('email');

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
		}

		return $this->view->render($response, 'Users/editar.twig', [
			'retorno' => $retorno
		]);
	}

	/**
	 * Deleta o usuário
	 *
	 * @return void
	 */
	public function deletar($request, $response, $args)
	{
		$retorno  = '';

		$id = $request->getAttribute('id');

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