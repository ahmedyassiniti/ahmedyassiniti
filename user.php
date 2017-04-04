<?php 

	require("class.task.php");

	$task = new Tasks();


	if (isset($_GET["action"]) && $_GET["action"] == "alltasks") {
		$task->getAllTasks();
	}

	if (isset($_GET["action"]) && $_GET["action"] == "addnewtask" ) {
		
		$taskname    = $_POST["taskname"];
		$description = $_POST["description"];
		
		$task->addTask($taskname, $description);
	}


	if (isset($_GET["action"]) && $_GET["action"] == "update") {
		$taskId = $_POST["id"];
		$type   = $_POST["type"];

		$task->update($taskId, $type);
	}

	if (isset($_GET['action']) && $_GET['action'] == "remove") {
		$id = $_POST["id"];

		$task->remove($id);
	}

	if (isset($_GET['action']) && $_GET['action'] == "getTaskInfo") {
		$id = $_POST['id'];

		$task->getTaskInfo($id);
	}

	if (isset($_GET['action']) && $_GET['action'] == "editTask") {
		
		$taskId   = $_POST['id'];
		$taskName = $_POST['taskname'];
		$taskDesc = $_POST['description'];

		$task->editTask($taskId, $taskName, $taskDesc);
	}