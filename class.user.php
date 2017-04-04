<?php 

require_once("dbconnect.php");

class Users {
	
	private $connection;

	public function __construct()
	{
		global $connection;
		$dbconnect = new dbconnect();
		$this->connection = $dbconnect->connect();
	}
	
	public function register($username, $email, $password)
	{	
		try {
			$hash_password = password_hash($password, PASSWORD_DEFAULT);
			$query = "INSERT INTO users(username,email,password) VALUES(:username,:email,:password)";
			$stmt  = $this->connection->prepare($query);

			$stmt->bindparam(":username", $username);
			$stmt->bindparam(":email", $email);
			$stmt->bindparam(":password", $hash_password);
			if($stmt->execute()) {
				return json_encode(['success' => true]);
			} else {
				return json_encode(['success' => false]);
			}
		} 
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}


	public function login($email, $password)
	{
		try {
			$query = "SELECT * FROM users WHERE email = :email";
			$stmt  = $this->connection->prepare($query);

			$stmt->execute([":email" => $email]);
			$userInfo = $stmt->fetch(PDO::FETCH_OBJ);

			if ($stmt->rowCount() == 1) {
				if (password_verify($password, $userInfo->password)) {
					session_start();
					$_SESSION['user_session'] = $userInfo->id;
					$_SESSION['user_name']    = $userInfo->username;
					return json_encode(["success" => true, "url" => "dashboard.php"]);
				}
			}
			return json_encode(["success" => false]);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}


	static function isLogedIn()
	{
		if (isset($_SESSION['user_session'])) {
			return true;
		}
	}


	static function redirect($url)
	{
		header("Location: $url");
	}


	static function logout()
	{
		session_destroy();
		unset($_SESSION["user_session"]);
		return true;
	}

}