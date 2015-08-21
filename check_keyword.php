		<?php
			//handles the checking of keywords in database
			if(isset($_POST['keyword_check'])){
				// include the configs / constants for the database connection
				// include helper functions for formatting phone number
				require_once("classes/connection.php");
				
				//open mysql database connection
				$mysqli = openConnection();
				
				//grab an array of all keywords in keywords table
				$keyword_array = getAllKeywordArray($mysqli);
				$response_array = array();
				
				//check if desired keyword exists in keyword array
				if(in_array($_POST['keyword_check'],$keyword_array)){
					$response_array = array("keyword_check_response"=>"Keyword is not available");
				}
				else {
					$response_array = array("keyword_check_response"=>"Keyword is available");
				}
				echo json_encode($response_array);
				
				// close connection 
				closeConnection($mysqli);
				
			}
		
		?>