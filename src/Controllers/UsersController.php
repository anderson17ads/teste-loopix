<?php
namespace App\Controllers;

use App\Controllers\Controller;

class UsersController extends Controller
{
	protected $model = 'User';

	/**
	 * Lista todos os usuários
	 *
	 * @return void
	 */
	public function listar($request, $response, $args)
	{
		$users = $this->User->listar($request, $response, $args);

		return $this->view->render($response, 'Users/listar.twig', [
			'users' => $users
		]);
	}

	/**
	 * Adicionar um novo usuário
	 *
	 * @return void
	 */
	public function adicionar($request, $response, $args)
	{
		if ($request->isPost()) {
			if ($this->User->adicionar($request, $response, $args)) {
				$this->flash->addMessage('Alert', 'Usuário inserido com sucesso!');
				return $response->withRedirect('listar');
			} else {
				$this->flash->addMessage('Alert', 'Erro ao cadastrar usuário!');
			}
		}

		return $this->view->render($response, 'Users/adicionar.twig');
	}

	/**
	 * Editar o usuário
	 *
	 * @return void
	 */
	public function editar($request, $response, $args)
	{

		if ($request->isPut()) {
			if ($this->User->editar($request, $response, $args)) {
				$this->flash->addMessage('Alert', 'Usuário alterado com sucesso!');
				return $response->withRedirect($request->getUri()->getBasePath() . '/usuarios/listar');
			} else {
				$this->flash->addMessage('Alert', 'Erro ao alterar usuário!');
			}
		}

		$user = $this->User->pegar($request->getAttribute('id'));

		return $this->view->render($response, 'Users/editar.twig', [
			'user' => $user
		]);
	}

	/**
	 * Deletar o usuário
	 *
	 * @return void
	 */
	public function deletar($request, $response, $args)
	{
		if ($request->isDelete()) {
			if ($this->User->deletar($request->getAttribute('id'))) {
				$this->flash->addMessage('Alert', 'Usuário excluído com sucesso!');
			} else {
				$this->flash->addMessage('Alert', 'Erro ao excluir usuário!');
			}
		} else {
			$this->flash->addMessage('Alert', 'Erro ao excluir usuário!');
		}

		return $response->withRedirect($request->getUri()->getBasePath() . '/usuarios/listar');
	}
}