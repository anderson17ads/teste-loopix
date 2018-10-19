<?php
namespace App\Model;

use App\Model\Model;

class User extends Model
{
	private $table = 'usuarios';

	/**
	 * Editar um usuário
	 *
	 * @param array $request
	 * @param array $response
	 * @param array $args
	 *
	 * @return array
	 */
	public function listar($request, $response, $args)
	{
		try {
			$stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");

			return $stmt->fetchAll(\PDO::FETCH_OBJ);
		} catch (\PDOException $e) {
			die(print_r($e->getMessage()));
		}
	}

	/**
	 * Adicioanr um usuário
	 *
	 * @param array $request
	 * @param array $response
	 * @param array $args
	 *
	 * @return bool
	 */
	public function adicionar($request, $response, $args)
	{
		$nome  = $request->getParam('nome');
		$senha = $request->getParam('senha');
		$email = $request->getParam('email');

		try {
			$stmt = $this->db->prepare("INSERT INTO {$this->table} (nome, senha, email) VALUES (:nome, :senha, :email)");

			$stmt->bindParam(':nome', $nome);
			$stmt->bindParam(':senha', $senha);
			$stmt->bindParam(':email', $email);

			$stmt->execute();

			return true;
		} catch (\PDOException $e) {
			return false;
		}		
	}

	/**
	 * Editar um usuário
	 *
	 * @param array $request
	 * @param array $response
	 * @param array $args
	 *
	 * @return bool
	 */
	public function editar($request, $response, $args)
	{
		$id   = $request->getAttribute('id');
		$nome  = $request->getParam('nome');
		$senha = $request->getParam('senha');
		$email = $request->getParam('email');

		$sql = "UPDATE {$this->table} SET nome = :nome, senha = :senha, email = :email WHERE id = {$id}";

		try {
			$stmt = $this->db->prepare($sql);

			$stmt->bindParam('nome', $nome);
			$stmt->bindParam('senha', $senha);
			$stmt->bindParam('email', $email);

			$stmt->execute();

			return true;
		} catch (\PDOException $e) {
			return false;
		}		
	}

	/**
	 * Deletar um usuário
	 *
	 * @param int $id
	 *
	 * @return bool
	 */
	public function deletar($id = null)
	{
		if (!is_null($id)) {
			try {
				$stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = {$id}");
				$stmt->execute();

				return true;
			} catch (\PDOException $e) {
				return false;
			}			
		}
	}

	/**
	 * Pega um usuário
	 *
	 * @param int $id
	 *
	 * @return bool
	 */
	public function pegar($id = null)
	{
		if (!is_null($id)) {
			$stmt = $this->db->query("SELECT * FROM {$this->table} WHERE id = {$id}");
			$user = $stmt->fetch(\PDO::FETCH_OBJ);

			if ($user) return $user;
		}
	}
}