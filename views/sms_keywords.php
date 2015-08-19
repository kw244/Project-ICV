<div class="container-fluid">
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		
		<h4> Create a keyword</h4>
		<?php
			//handles the creating of new keyword in database
			if(isset($_POST['keyword_input'])){
				// include the configs / constants for the database connection
				// include helper functions for formatting phone number
				require_once("classes/connection.php");
				
				//include format checking function and notification module
				require_once("classes/formatting.php");
				
				//we check again if keyword is in correct format - i.e. alphanumeric & single word
				//and upload it to keyword table if it is
				$regex = "/^[a-zA-Z0-9_]+$/";
				if(preg_match($regex,$_POST['keyword_input'])){
					//open mysql database connection
					$mysqli = openConnection();
					
					//upload keyword into keyword table
					if(uploadKeyword($mysqli,$_POST['keyword_input'])){
						createNotif('success','"'.$_POST['keyword_input'].'", successfully created as keyword');
					}
					
					// close connection 
					closeConnection($mysqli);
					
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
				

				<input class="custom-btn btn btn-primary" type="submit" value="Create" name="submit_keyword">
			</form>
		
		</div>
	</div>

</div>