<div class="container-fluid">
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

		
		<!--Show notification on initial successful login and not subsequent visits to Main page-->
		<?php
			if (!isset($_GET["main"])) {
				echo '<div class="row placeholders">';
				echo '<div class="notice success text-left">';
				echo '<p> You are logged in as ' . $_SESSION["user_name"] . '</p>';
				echo '</div>';
				echo '</div>';
			
			}
		?>

        <h2 class="page-header">Account Overview</h2>
        <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Username</th>
                  <th>Account Type</th>
                  <th>SMS Credits Available</th>
				  <th>Keywords Available</th>
                </tr>
              </thead>
              <tbody>

				<?php
					
					// include the configs / constants for the database connection
					require_once("classes/connection.php");

					//open mysql database cconnection
					$mysqli = openConnection();

					//get contacts information for the user
					getAccountInfo($mysqli);
					
					// close connection 
					closeConnection($mysqli);
				?>
			         
              </tbody>
            </table>
        </div>
    </div>
</div>