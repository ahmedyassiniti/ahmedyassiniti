<?php
	
	session_start();
	require("class.user.php");

	$login = new Users;

	$loginStatus = $login::isLogedIn();

	if (isset($loginStatus)) {
		$login::redirect("./dashboard.php");
	}

	require("inc/header.php");
?>
<body id="home">
	
	<div class="fullscreen-bg">
		<div class="overlay"></div>
	    <video loop muted autoplay poster="img/bg.jpg" class="fullscreen-bg__video">
	        <source src="video/bg.mp4" type="video/mp4">
	    </video>
	</div>

	<nav class="navbar">
  		<div class="container-fluid">
    		<div class="collapse navbar-collapse">
	     		<ul class="nav navbar-nav navbar-right">
	        		<li>
	        			<a href="#" class="btn btn-round btn-success" data-target="#login" data-toggle="modal">Login</a>
	        		</li>
	        		<li>
	        			<a href="#" class="btn btn-round btn-primary" data-target="#register" data-toggle="modal">Register</a>
	        		</li>
	      		</ul>
	    	</div>
	  	</div>
	</nav>

	<div class="container">
		<div class="row">
			
		</div>
	</div>

	<!-- Login Modal -->
	<div class="modal fade" tabindex="-1" id="login">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
        			<h4 class="modal-title text-center">Login</h4>
				</div>
				<div class="modal-body">
					<form action="#" method="POST" id="loginForm">
						<div class="form-group">
							<input type="email" placeholder="Email Address" name="email" class="form-control">
						</div>
						<div class="form-group">
							<input type="password" placeholder="Password" name="password" class="form-control">
						</div>
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="remember"> Remember me
								</label>
							</div>
						</div>
						<div class="form-group">
							<input type="submit" value="Login" class="btn btn-success btn-block">
						</div>
					</form>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>

	<!-- Register Modal -->
	<div class="modal fade" tabindex="-1" id="register">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
        			<h4 class="modal-title text-center">Register</h4>
				</div>
				<div class="modal-body">
					<form action="#" method="POST" id="registerForm" enctype="multipart/form-data">
						<div class="form-group">
							<input type="text" placeholder="Name" name="username" class="form-control">
						</div>
						<div class="form-group">
							<input type="email" placeholder="Email Address" name="email" class="form-control">
						</div>
						<!-- <div class="form-group">
							<input type="file" name="avatar" class="form-control">
						</div> -->
						<div class="form-group">
							<input type="password" placeholder="Password" name="password" class="form-control">
						</div>
						<div class="form-group">
							<input type="submit" value="Register" class="btn btn-info btn-block">
						</div>
					</form>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>

	<?php require("inc/footer.php"); ?>
	<script>
		$(function(){
			$("form#loginForm").on("submit", function(e) {
				e.preventDefault();

				$.ajax({
					url: "./auth.php?action=login",
					type: "POST",
					data: $(this).serializeArray(),
					success: function(response) {
						stopWait();
						var data = JSON.parse(response);
						if (data.success) {
							window.location = data.url;
						} else {
							swal("OoOopps!", "Please, Check E-mail and Password", "error");
						}
					},
					beforeSend: loadWait()
				});
			});

			$("form#registerForm").on("submit", function(e) {
				e.preventDefault();

				$.ajax({
					url: "./auth.php?action=register",
					type: "POST",
					data: $(this).serialize(),
					success: function(response) {
						var data = JSON.parse(response);
						stopWait();
						if (data.success) {
							$("#register").modal("hide");
							swal("Good job!", "You Registered Successfully!", "success");
						} else {
							swal("OoOopps!", "Check Your information and try again", "error");
						}
					},
					beforeSend: loadWait()
				});
			});
		});
	</script>
</body>
</html>