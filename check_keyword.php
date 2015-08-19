		<?php
			//handles the checking of keywords in database
			if(isset($_POST['keyword_check'])){
				// include the configs / constants for the database connection
				// include helper functions for formatting phone number
				require_once("classes/connection.php");
				
				//open mysql database connection
				$mysqli = openConnection();
				
				//grab an array of all keywords in keywords table
				$keyword_array = getKeywordInfo($mysqli);
				
				// close connection 
				closeConnection($mysqli);
				
				//check if desired keyword exists in keyword array
				if(in_array($_POST['keyword_check'],$keyword_array)){
					echo 'The keyword: "'.$_POST['keyword_check'].'" is taken';
				}
				else {
					echo '"'.$_POST['keyword_check'].'" is available';
				}
				
				
			}
		
		?>