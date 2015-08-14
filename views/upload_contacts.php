<div class="container-fluid">
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		
		<h4> Upload contacts manually</h4>
		<?php
			//handles the insertion of single contact into database
			if(isset($_POST['submit_single'])){
				// include the configs / constants for the database connection
				// include helper functions for formatting phone number
				require_once("classes/connection.php");
				
				//open mysql database connection
				$mysqli = openConnection();
				
				//handle upload of contact information
				//parse contact_name and contact phone into array					
				$contactsData = array($_POST['contact_name'],$_POST['contact_phone']);
				
				//upload contact info into contacts and contacts_users tables
				if (uploadContactsInfo($mysqli,$contactsData)){
					//we notify user of successful upload of contact
					echo '<div class="row placeholders">';
					echo '<div class="notice success text-left">';
					echo '<p>'.$_POST['contact_name'].' successfully imported </p>';
					echo '</div>';
					echo '</div>';
				}
				else {
					//we notify user of unsuccessful upload
					echo '<div class="row placeholders">';
					echo '<div class="notice warning text-left">';
					echo '<p>Upload unsuccessful. Please see error messages</p>';
					echo '</div>';
					echo '</div>';	
				}

				
				// close connection 
				closeConnection($mysqli);
			}
		?>
		<div class="col-sm-6 col-md-6 main bg-light-grey">
			<form action="index.php?contacts&menu=upload_contacts" method="post">
			
				<div class="form-group">
					<label>Contact Name</label>
					<input type="text" class="form-control" placeholder="Enter name" name="contact_name" required="required" />
				</div>
				<div class="form-group">
					<label>Contact Number</label>
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></span>
						
						<input type="tel" class="form-control" placeholder="Enter phone  65XXXXXXXX" name="contact_phone" required="required" />
					</div>
				</div>

				<input class="custom-btn btn btn-primary" type="submit" value="Upload" name="submit_single">
			</form>
		
		</div>
	</div>
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main custom-section">
		<h4> Upload contacts using .csv file</h4>
		<div class="input-group">
			<?php
					
				// include the configs / constants for the database connection
				require_once("classes/connection.php");

				//open mysql database connection
				$mysqli = openConnection();

				//handles the upload of the CSV file
				if(isset($_POST['submit']))
				{
					 $fname = $_FILES['fileToUpload']['name'];
					 //echo 'Upload file name: '.$fname.' ';
					 $chk_ext = explode(".",$fname);
					
					 if(strtolower(end($chk_ext)) == "csv")
					 {
					
						 $filename = $_FILES['fileToUpload']['tmp_name'];
						 //echo 'Upload file name (b): '.$filename.' ';
						 $handle = fopen($filename, "r");
				   
						//boolean used to track success of upload, returns true if all records uploaded successfully, false otherwise
						$successBool = true;
						
						 while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
						 {
							$successBool = $successBool && uploadContactsInfo($mysqli,$data);
						 }
				   
						 fclose($handle);
						
						if ($successBool){
							//we notify user of successful upload of CSV file
							echo '<div class="row placeholders">';
							echo '<div class="notice success text-left">';
							echo '<p>'.$fname.' successfully imported </p>';
							echo '</div>';
							echo '</div>';
						}
						else {
							//we notify user of unsuccessful upload
							echo '<div class="row placeholders">';
							echo '<div class="notice warning text-left">';
							echo '<p>Upload unsuccessful. Please see error messages</p>';
							echo '</div>';
							echo '</div>';	
						}

								 
					 }
					 
					 //we issue a warning for user trying to upload non-CSV file
					 else
					 {
						echo '<div class="row placeholders">';
						echo '<div class="notice warning text-left">';
						echo '<p> Invalid filetype. Please only upload CSV files </p>';
						echo '</div>';
						echo '</div>';
		
					 }   
				}					
				// close connection 
				closeConnection($mysqli);
			?>		
		
			<form action="index.php?contacts&menu=upload_contacts" method="post" enctype="multipart/form-data">

				<input type="file" name="fileToUpload" id="fileToUpload">
				<input class="custom-btn btn btn-primary" type="submit" value="Upload" name="submit">
				
			</form>


			
		</div>
	</div>
</div>