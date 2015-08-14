<div class="container-fluid">
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

		<div class="row placeholders">
			<div class="notice success text-left">
				<h4> You are logged in as <?php echo $_SESSION['user_name']; ?></h4>
			</div>
		</div> 

        <h2 class="sub-header">Account Overview</h2>
        <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Username</th>
                  <th>Account Type</th>
                  <th>SMS Credits Available</th>
                </tr>
              </thead>
              <tbody>

				<?php
					
					// include the configs / constants for the database connection
					require_once("classes/connection.php");

					//open mysql database cconnection
					$mysqli = openConnection();

					//get contacts information for the user
					loadAccountInfo($mysqli);
					
					// close connection 
					closeConnection($mysqli);
				?>
			         
              </tbody>
            </table>
        </div>
    </div>
</div>