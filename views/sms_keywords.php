<div class="container-fluid">
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		
		<h4> Create a keyword</h4>
		<?php
			//handles the creating of new keyword in database
			if(isset($_POST['submit_keyword']) && isset($_POST['keyword_input'])){
				// include the configs / constants for the database connection
				// include helper functions for formatting phone number
				require_once("classes/connection.php");
				
				//include format checking function and notification module
				require_once("classes/formatting.php");
				
				//we check again if keyword is in correct format - i.e. alphanumeric & single word
				//and upload it to keyword table if it is
				$regex = "/^[a-zA-Z0-9_]+$/";
				if(preg_match($regex,$_POST['keyword_input'])){
					
					$num_msg = (int) $_POST["sms_text_num_msg"];
					if($num_msg > 0){
						//open mysql database connection
						$mysqli = openConnection();
						
						//upload keyword into keyword table
						if(uploadKeyword($mysqli, $_POST['keyword_input'], $_POST['sms_text'], $num_msg)){
							createNotif('success','"'.$_POST['keyword_input'].'", created as keyword');
						}
						
						// close connection 
						closeConnection($mysqli);
					}
					else {
						createNotif("warning","Maximum number of characters exceeded");
					}
				}
				else {
					createNotif('warning','"'.$_POST['keyword_input'].'" isn\'t an accepted keyword format');
				}

			}
		
		?>
		<div class="col-sm-6 col-md-6 main bg-light-grey">
			<form action="index.php?SMS&menu=sms_keywords" method="post">
			
				<div class="form-group">
					<label>Keyword</label>
					<input type="text" class="form-control" placeholder="Enter keyword" id="keyword_input" name="keyword_input" required="required" />
					<p class="text-right" id="keyword_text_comment" name="keyword_text_comment"></p>
				</div>
				<div class="form-group autoreply-group">
					<div class="form-group sms_text_form">	
						<label>Auto-Reply (Leave blank if you don't want to setup) </label>	
						<textarea class="form-control" rows="4" id="sms_text" name="sms_text" placeholder="Enter Auto-Reply Text" required="required"></textarea>
						<p class="text-right" id="sms_text_comment" name="sms_text_comment">GSM: 160/1</p>
						<input type="hidden" id="sms_text_num_msg" name="sms_text_num_msg">
						<input type="hidden" id="sms_text_is_gsm" name="sms_text_is_gsm">
					</div>
					
				</div>
				<input class="custom-btn btn btn-primary" type="submit" value="Create" name="submit_keyword">
			</form>
		
		</div>
	</div>
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main custom-section">
		<h4> Existing Keywords</h4>
		<div class="col-sm-6 col-md-6 main bg-light-grey">	
			<form action="index.php?SMS&menu=sms_keywords" method="post">
			
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
						  <th>Select</th>
						  <th>Keyword</th>
						</tr>
					</thead>
					<tbody class="list">
				
					<?php
						
						// include the configs / constants for the database connection
						require_once("classes/connection.php");
						
						//include format checking function and notification module
						require_once("classes/formatting.php");
						
						//handles the removal of keyword from database
						if(isset($_POST['remove_keyword'])){

							//open mysql database connection
							$mysqli = openConnection();
							
							//boolean used to track success of deletion, returns true if all records deleted successfully, false otherwise
							$successBool = true;
							
							//we take contact_ids in the array and delete those records from the contacts table
							foreach ($_POST['checkbox'] as $keyword_id){
								$successBool = $successBool && removeKeyword($mysqli,$keyword_id);
							}
							
							//notification after removal operation
							if ($successBool){
								//we notify user of successful deletion
								createNotif("success","Selected keywords successfully removed");
							}
							else {
								//we notify user of unsuccessful deletion
								createNotif("warning","Removal unsuccessful. Please see error messages");
							}
							
							// close connection 
							closeConnection($mysqli);
									
						}
						
						
						//open mysql database connection
						$mysqli = openConnection();

						//get the list of keywords and print them to html list
						displayKeywords($mysqli, true);	
						
						// close connection 
						closeConnection($mysqli);
						

						
						
					?>
					 
					</tbody>
				</table>
			</div>
			<input class="remove btn btn-primary" type="submit" value="Remove" name="remove_keyword">
			</form>
	    </div>
	</div>

</div>