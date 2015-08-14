<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Star SMS Login</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
		<!-- Custom login style -->
	<link href="css/signin.css" rel="stylesheet">
	<link href="css/notifications.css" rel="stylesheet">
    
  </head>


	<body>
		
		<div class = "container">

				<?php
					
					// show potential errors / feedback (from login object)
					if (isset($login)) {
						
						if ($login->errors) {
							foreach ($login->errors as $error) {
								echo '<div class="notice error"><p>';
								echo $error;
								echo '</p></div>';
							}
						}
						if ($login->messages) {
							foreach ($login->messages as $message) {
								echo '<div class="notice info"><p>';
								echo $message;
								echo '</p></div>';
							}
						}
						
					}
				?>

		
		
	
		
		<!-- login form box -->
		<form method="post" action="index.php" name="loginform" class="form-signin">
				<h2 class="form-signin-heading">Star SMS Login</h2>
			
				<label for="login_input_username" class="sr-only">Username</label>
				<input id="login_input_username" class="login_input form-control" type="text" name="user_name" placeholder="Username" required autofocus>

				<label for="login_input_password" class="sr-only">Password</label>
				<input id="login_input_password" class="login_input form-control" type="password" name="user_password" placeholder="Password" autocomplete="off" required >
			
			<button class="btn btn-primary btn-lg btn-block" type="submit"  name="login">Log In</button>

		</form>

		<a href="register.php">Register new account</a>
		
		</div> <!-- /container -->
	</body>





</html>
