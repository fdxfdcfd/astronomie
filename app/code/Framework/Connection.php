<?php
namespace Framework;

class Connection
{
	protected $conn = false;
	protected $configs = [
		'host' => 'localhost',
		'username' => 'weatherv',
		'pass' => 'TainSjqG67y92CD',
		'database' => 'weatherv_vn'
	];
	
	public function getConnection()
	{
		if (!$this->conn) {
			$conn = new \mysqli(
				$this->configs['host'],
				$this->configs['username'],
				$this->configs['pass'],
				$this->configs['database']
			);
			if ($conn->connect_error) {
				return false;
			}
			$this->conn = $conn;
		}
		return $this->conn;
	}
}
