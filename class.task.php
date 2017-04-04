<?php

require_once("dbconnect.php");

class Tasks 
{
	
	private $connection;

	public function __construct()
	{
		global $connection;
		$dbconnect = new dbconnect();
		$this->connection = $dbconnect->connect();
	}

	public function addTask($taskname, $description)
	{
		session_start();
		$userId = $_SESSION["user_session"];

		$query = "INSERT INTO tasks (taskname, description, user_id) VALUES (:taskname, :description, :user_id)";

		$stmt  = $this->connection->prepare($query);

		$stmt->execute([":taskname" => $taskname, ":description" => $description, ":user_id" => $userId]);
		$task = $stmt->fetch(PDO::FETCH_OBJ);
	}

	public function runQuery($query)
	{
		$userId = $_SESSION["user_session"];
		
		$stmt   = $this->connection->prepare($query);

		$stmt->execute([":userId" => $userId]);
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		return $result;
	}

	public function getToDo()
	{
		session_start();
		$userId = $_SESSION["user_session"];
		
		$query  = "SELECT * FROM tasks WHERE user_id = :userId";
		$stmt   = $this->connection->prepare($query);

		$stmt->execute([":userId" => $userId]);
		$tasks = $stmt->fetch(PDO::FETCH_OBJ);	

		echo json_encode($tasks);
	}

	public function getAllTasks() {
		session_start();

		$todo_query    = "SELECT * FROM tasks WHERE user_id = :userId AND type = 'todo'";
		$data['todo']  = self::runQuery($todo_query);

		$doing_query   = "SELECT * FROM tasks WHERE user_id = :userId AND type = 'doing'";
		$data['doing'] = self::runQuery($doing_query);

		$done_query    = "SELECT * FROM tasks WHERE user_id = :userId AND type = 'done'";
		$data['done']  = self::runQuery($done_query);


		echo json_encode($data);
	}

	public function update($id, $type)
	{
		$update_query = "UPDATE tasks SET type = :type, updated_at = now() WHERE id = :id";
		$stmt         = $this->connection->prepare($update_query);

		$stmt->execute([":type" => $type, ":id" => $id]);
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		return $result;
	}

	public function remove($id)
	{
		$remove_query = "DELETE FROM tasks WHERE id = :id";
		$stmt         = $this->connection->prepare($remove_query);

		$stmt->execute([":id" => $id]);
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		return $result;
	}

	public function getTaskInfo($id)
	{
		$taskinfo_query = "SELECT * FROM tasks WHERE id = :id";
		$stmt           = $this->connection->prepare($taskinfo_query);

		$stmt->execute([':id' => $id]);
		$result = $stmt->fetch(PDO::FETCH_OBJ);

		echo json_encode($result);
	}

	public function editTask($id, $name, $description)
	{
		$edit_query = "UPDATE tasks SET taskname = :name, description = :description ,updated_at = now() WHERE id = :id";
		$stmt       = $this->connection->prepare($edit_query);

		$stmt->execute([':name' => $name, ':description' => $description, ':id' => $id]);
		$result = $stmt->fetch(PDO::FETCH_OBJ);
		echo json_encode(['success' => true]);
	}

}