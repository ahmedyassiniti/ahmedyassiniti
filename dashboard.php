<?php

	/*******************************************************************
		kanban Database
			-> users Table
				-> id         [int(11) | PRIMARY_KEY | AUTO_INCREMENT]
				-> username   [char(200)]
				-> email      [cahr(200)]
				-> password   [char(50)]
				-> avatar     [char(200)]
				-> created_at [timestamps]
				-> updated_at [timestamps]

			-> Tasks Table
				-> id          [int(11) | PRIMARY_KEY | AUTO_INCREMENT]
				-> name        [char(200)]
				-> description [text]
				-> user_id     [int(11) | FOREIGN_KEY(users.id)]
				-> type        [enum(1|2|3)]
					-> 1 ==> ToDo
					-> 2 ==> Doing
					-> 3 ==> Done
				-> created_at  [timestamps]
				-> updated_at  [timestamps]
	********************************************************************/
	
	session_start();
	include("class.user.php");
	
	$user = new Users();

	if (!isset($_SESSION["user_session"]) || !isset($_SESSION['user_name'])) {
		$user::redirect("index.php");
	}

	require("inc/header.php");
?>
<body>

	<nav class="navbar navbar-default">
	  	<div class="container-fluid">
	    	<div class="collapse navbar-collapse">
	    		<ul class="nav navbar-nav">
	    			<li>
	    				<a href="#addNew" class="btn bg-success" data-toggle="modal" data-target="#addNew"> 
							<i class="glyphicon glyphicon-plus-sign"></i>
	    					Add Task
	    				</a>
	    			</li>
	    		</ul>
	      		<ul class="nav navbar-nav navbar-right">
	        		<li class="dropdown">
		          		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		          			<?= $_SESSION['user_name']; ?>
		          			<span class="caret"></span>
		          		</a>
		          		<ul class="dropdown-menu">
		            		<!-- <li><a href="#">Profile</a></li> -->
		            		<!-- <li role="separator" class="divider"></li> -->
		            		<li><a href="javascript:;" id="logout">Logout</a></li>
		          		</ul>
		        	</li>
	      		</ul>
	    	</div>
	  	</div>
	</nav>

	<div class="container" id="container">
		<div class="row">
			
			<div class="col-md-4">
				<div class="panel panel-danger">
				  	<div class="panel-heading text-center">
				  		ToDo Tasks
				  	</div>
				  	<div class="panel-body" id="todo">
				  		<div class="col-md-12">
				  			<div class="items"></div>
				  		</div>
				  	</div>
				  	<div class="panel-footer text-center">
				  		<p>The way to get started is to quit talking and begin doing.</p>
				  	</div>
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="panel panel-info">
				  	<div class="panel-heading text-center">
				  		Doing Tasks
				  	</div>
				  	<div class="panel-body" id="doing">
				  		<div class="col-md-12">
				  			<div class="items"></div>
				  		</div>
				  	</div>
				  	<div class="panel-footer text-center">
				  		<p>The best preparation for tomorrow is doing your best today.</p>
				  	</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="panel panel-success">
				  	<div class="panel-heading text-center">
				  		Done Tasks
				  	</div>
				  	<div class="panel-body" id="done">
				  		<div class="col-md-12">
				  			<div class="items"></div>
				  		</div>
				  	</div>
				  	<div class="panel-footer text-center">
				  		<p>Great things are done by a series of small things brought together.</p>
				  	</div>
				</div>
			</div>
		
		</div>
	</div>

	<!-- AddNewTask Modal -->
	<div id="addNew" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
        			<h4 class="modal-title text-center">Add New Task</h4>
				</div>
				<div class="modal-body">
					<form action="#" method="POST" id="taskForm">
						<div class="form-group">
							<input type="text" name="taskname" placeholder="Taskname" class="form-control">
						</div>
						<div class="form-group">
							<textarea name="description" placeholder="Task Description" rows="5" class="form-control"></textarea>
						</div>
						<div class="form-group">
							<input type="submit" value="Add Task" class="btn btn-block btn-success">
						</div>
					</form>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>


	<!-- EditTask Modal -->
	<div id="editTask" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
        			<h4 class="modal-title text-center">Edit Task</h4>
				</div>
				<div class="modal-body">
					<form action="#" method="POST" id="editForm">
						<div class="form-group">
							<input type="hidden" name="id">
							<input type="text" name="taskname" placeholder="Taskname" class="form-control">
						</div>
						<div class="form-group">
							<textarea name="description" placeholder="Task Description" rows="5" class="form-control"></textarea>
						</div>
						<div class="form-group">
							<input type="submit" value="Edit Task" class="btn btn-block btn-primary">
						</div>
					</form>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>


	<?php require("inc/footer.php") ?>
	<script src="js/jquery.touch-punch.min.js"></script>
	<script src="js/jquery.shapeshift.min.js"></script>
	<script>
		$(document).ready(function(){

			var getAllTasks = function() {
				$.ajax({
					url: "./user.php?action=alltasks",
					type: "GET",
					success: function(response) {
						var data = JSON.parse(response);

						data.todo.forEach(function(element) {
							$("#todo .items").append("<div id='"+element.id+"' class='item'><div class='actions'><span class='remove glyphicon glyphicon-minus'></span><span class='edit glyphicon glyphicon-edit'></span></div><h4 class='text-center'>"+
								element.taskname+"</h4><p>"+element.description+"</p></div>");
						});

						data.doing.forEach(function(element) {
							$("#doing .items").append("<div id='"+element.id+"' class='item'><div class='actions'><span class='remove glyphicon glyphicon-minus'></span><span class='edit glyphicon glyphicon-edit'></span></div><h4 class='text-center'>"+
								element.taskname+"</h4><p>"+element.description+"</p></div>");
						});

						data.done.forEach(function(element) {
							$("#done .items").append("<div id='"+element.id+"' class='item'><div class='actions'><span class='remove glyphicon glyphicon-minus'></span><span class='edit glyphicon glyphicon-edit'></span></div><h4 class='text-center'>"+
								element.taskname+"</h4><p>"+element.description+"</p></div>");
						});
					}
				});
			}

			getAllTasks();

			$("#logout").on("click", function(event){
				$.ajax({
					url: "./auth.php?action=logout",
					type: "POST",
					success: function(response) {
						var data = JSON.parse(response);
						if (data.success) {
							window.location = data.url;
						}
					}
				});
			});

			$("form#taskForm").on("submit", function(event){
				event.preventDefault();

				$.ajax({
					url: "./user.php?action=addnewtask",
					type: "POST",
					data: $(this).serialize(),
					success: function(response) {
						window.location.reload();
					}
				});
			});

			$(".items").sortable({connectWith: ".items"});
			$('.items').droppable({
			    drop: function(event, element){
			    	setTimeout(function(){
			    		
			    		var selectedTaskId = element.draggable[0].id,
			    			taskPosition   = element.draggable.parents('.panel-body').attr("id");

			    		$.ajax({
			    			url: "./user.php?action=update",
			    			type: "POST",
			    			data: {id: selectedTaskId, type: taskPosition}
			    		});

			    	}, 300);
			    }
			});

			$(".items").on("click", ".item .remove",function(event) {
				var task = $(this).parents('.item');
				swal({
				  	title: "Are you sure?",
				  	text: "You will not be able to recover this task!",
				  	type: "warning",
				  	showCancelButton: true,
				  	confirmButtonColor: "#DD6B55",
				  	confirmButtonText: "Yes, delete it!",
					closeOnConfirm: false
				},
				function(isConfirm){
					if(isConfirm) {
						$.ajax({
							url: "./user.php?action=remove",
							type: "POST",
							data: {id: task.attr("id")},
							success: function(response) {
								task.remove();
								swal("Deleted!", "Your task has been deleted.", "success");
							}
						});
					}
				});
			});

			$(".items").on("click", ".item .edit",function(event) {
				var taskId = $(this).parents('.item').attr("id");
				$.ajax({
					url: "./user.php?action=getTaskInfo",
					type: "POST",
					data: {id: taskId},
					success: function(response) {
						var data = JSON.parse(response);

						$("#editForm [name='taskname']").val(data.taskname);
						$("#editForm [name='id']").val(data.id);
						$("#editForm [name='description']").val(data.description);

						$("#editTask").modal("show");
					}
				});
			});

			$("#editForm").on("submit", function(event) {
				event.preventDefault();

				$.ajax({
					url: "./user.php?action=editTask",
					type: "POST",
					data: $(this).serialize(),
					success: function(response) {
						var data = JSON.parse(response);

						if (data.success) {
							stopWait();
							$("#editTask").modal("hide");
							swal("Good job!", "Task Edited Successfully!", "success");
							window.location.reload();
						}
					},
					beforeSend: loadWait()
				});
			});

		});
	</script>
</body>
</html>