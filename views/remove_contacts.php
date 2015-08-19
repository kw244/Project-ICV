<div class="container-fluid">
	<div id = "contacts" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		
		<form action="index.php?contacts&menu=remove_contacts" method="post">
			
			<input class="search" placeholder="Search Names" />
			<input class="remove btn btn-primary" type="submit" value="Remove" name="submit">
		<?php
			//handles the deletion of selected contact records from the database
			if(isset($_POST['submit'])){
				// include the configs / constants for the database connection
				require_once("classes/connection.php");

				//include format checking function and notification module
				require_once("classes/formatting.php");
				
				//open mysql database connection
				$mysqli = openConnection();
				
				//boolean used to track success of deletion, returns true if all records deleted successfully, false otherwise
				$successBool = true;
				
				//we take contact_ids in the array and delete those records from the contacts table
				foreach ($_POST['checkbox'] as $contact_id){
					$successBool = $successBool && removeContactInfo($mysqli,$contact_id);
				}
				
				//notification after removal operation
				if ($successBool){
					//we notify user of successful deletion
					createNotif("success","Selected contacts successfully removed");
				}
				else {
					//we notify user of unsuccessful deletion
					createNotif("warning","Removal unsuccessful. Please see error messages");
				}
				
				// close connection 
				closeConnection($mysqli);
			}					
		
		?>
		
		
		
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
					  <th>Select</th>
					  <th>Contact Name</th>
					  <th>Contact Number</th>
					  <th>Tags</th>
					</tr>
				</thead>
				<tbody class="list">

				<?php
					
					// include the configs / constants for the database connection
					require_once("classes/connection.php");

					//open mysql database connection
					$mysqli = openConnection();

					//display contacts information for the logged in user in table rows
					getContactInfo($mysqli,true);
										
					// close connection 
					closeConnection($mysqli);
				?>
					 
				</tbody>
			</table>
	    </div>
		</form>
	</div>
</div>