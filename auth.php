<?php
	
	require("class.user.php");

	$user = new Users();

	if ($user->isLogedIn()) {
		$user::redirect("dashboard.php");
	}

	if (isset($_GET['action']) && $_GET['action'] == 'login') {
		
		$email    = $_POST["email"];
		$password = $_POST["password"];

		echo $user->login($email, $password);
	}

	if (isset($_GET['action']) && $_GET['action'] == 'register') {
		
		$username = $_POST["username"];
		$email    = $_POST["email"];
		$password = $_POST["password"];

		echo $user->register($username, $email, $password);
	}

	if (isset($_GET["action"]) && $_GET["action"] == "logout") {
		session_start();
		if ($user::logout()) {
			echo json_encode(["success" => true,"url" => "index.php"]);	
		}
	}