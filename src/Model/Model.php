<?php 
namespace App\Model;

use App\Config\Database;

abstract class Model
{
	protected $db;

	public function __construct()
	{
		$conn = new Database;
		$this->db = $conn->conn;
	}
}