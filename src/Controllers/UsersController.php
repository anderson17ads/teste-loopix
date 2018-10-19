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
			$stmt = $this->db->query('SELECT * FROM usuarios ORDER BY id DESC');

			$users = $stmt->fetchAll(\PDO::FETCH_OBJ);
		} catch (\PDOException $e) {
			die(print_r($e->getMessage()));
		}

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
			$nome  = $request->getParam('nome');
			$senha = $request->getParam('senha');
			$email = $request->getParam('email');

			try {
				$stmt = $this->db->prepare("INSERT INTO usuarios (nome, senha, email) VALUES (:nome, :senha, :email)");

				$stmt->bindParam(':nome', $nome);
				$stmt->bindParam(':senha', $senha);
				$stmt->bindParam(':email', $email);

				$stmt->execute();

				$this->flash->addMessage('Alert', 'Usuário inserido com sucesso!');
				return $response->withRedirect('listar');
			} catch (\PDOException $e) {
				$this->flash->addMessage('Alert', 'Erro ao cadastrar usuário!');
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
		$id = $request->getAttribute('id');

		if ($request->isPut()) {
			$nome  = $request->getParam('nome');
			$senha = $request->getParam('senha');
			$email = $request->getParam('email');

			$sql = "UPDATE usuarios SET nome = :nome, senha = :senha, email = :email WHERE id = {$id}";

			try {
				$stmt = $this->db->prepare($sql);

				$stmt->bindParam('nome', $nome);
				$stmt->bindParam('senha', $senha);
				$stmt->bindParam('email', $email);

				$stmt->execute();

				$this->flash->addMessage('Alert', 'Usuário alterado com sucesso!');
				return $response->withRedirect($request->getUri()->getBasePath() . '/usuarios/listar');
			} catch (\PDOException $e) {
				$this->flash->addMessage('Alert', 'Erro ao alterar usuário!');
			}
		}

		$stmt = $this->db->query("SELECT * FROM usuarios WHERE id = {$id}");
		$user = $stmt->fetch(\PDO::FETCH_OBJ);

		return $this->view->render($response, 'Users/editar.twig', [
			'user' => $user
		]);
	}

	/**
	 * Deleta o usuário
	 *
	 * @return void
	 */
	public function deletar($request, $response, $args)
	{
		$id = $request->getAttribute('id');

		if ($id && $request->isDelete()) {
			try {
				$stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = {$id}");
				
				$stmt->execute();

				$this->flash->addMessage('Alert', 'Usuário excluído com sucesso!');
			} catch (\PDOException $e) {
				$this->flash->addMessage('Alert', 'Erro ao excluir usuário!');
			}
		} else {
			$this->flash->addMessage('Alert', 'Erro ao excluir usuário!');
		}

		return $response->withRedirect($request->getUri()->getBasePath() . '/usuarios/listar');
	}
}