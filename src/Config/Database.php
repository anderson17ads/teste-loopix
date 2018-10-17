<?php
namespace App\Config;

use PDO;

class Database
{
	private $host 	  = 'localhost';
	private $username = 'teste-loopix';
	private $password = 'teste-loopix';
	private $dbname	  = 'teste-loopix';

	public $conn;

	public function __construct()
	{
		$this->connect();
	}

	public function connect()
	{
		$this->conn = '';

		try {
			$this->conn = new PDO(
				"mysql:host={$this->host};dbname={$this->dbname}",
				$this->username,
				$this->password
			);

			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (\PDOException $e) {
			echo 'Error Connect: ' . $e->getMessage();
		}

		return $this->conn;
	}
}