<?php 

class dbconnect {
	private $hostname = "144.208.71.18";      // Server name or Server IP
	private $username = "cashze5_kanban";	  // Database Username
	private $password = "kanban";             // Database Password
	private $db_name  = "cashze5_kanban";     // Database Name
	private $en_type  = "mysql";              // Engine Type
	
	public $conn;

	public function connect() {
		
		try {
			$this->conn = new PDO("$this->en_type:host=".$this->hostname.";dbname=".$this->db_name,$this->username,$this->password);
		} catch (PDOException $e) {
			echo "Connection Error: " . $e->getMessage();
		}
		
		return $this->conn;
	}
}