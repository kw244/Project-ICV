<div class="container-fluid">
	<div id = "contacts" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		</button>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
					  <th>Date/Time</th>
					  <th>From</th>
					  <th>Message</th>
					  <th>Action</th>
					</tr>
				</thead>
				<tbody class="list">

				<?php
					// include the configs / constants for the database connection
					require_once("classes/connection.php");

					//open mysql database connection
					$mysqli = openConnection();

					//display inbound information for the logged in user in table rows
					getInboundInfo($mysqli,false);
										
					// close connection 
					closeConnection($mysqli);

				?>
					 
				</tbody>
			</table>
	    </div>
	</div>
</div>