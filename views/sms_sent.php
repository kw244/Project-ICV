<div class="container-fluid">
	<div id = "contacts" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		<input class="search" placeholder="Search Names" />
		<button class="sort btn btn-primary" data-sort="name">
			Sort by name
		</button>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
					  <th>Date/Time</th>
					  <th>From</th>
					  <th>To</th>
					  <th>Campaign</th>
					  <th>SMS Text</th>
					  <th>Status</th>
					</tr>
				</thead>
				<tbody class="list">

				<?php
					
					// include the configs / constants for the database connection
					require_once("classes/connection.php");

					//open mysql database connection
					$mysqli = openConnection();

					//display outbound msg information for the logged in user in table rows
					getOutboundInfo($mysqli,false);
										
					// close connection 
					closeConnection($mysqli);
				?>
					 
				</tbody>
			</table>
	    </div>
	</div>
</div>