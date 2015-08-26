<?php

	/*	Listen for any HTTP GET requests -> Incoming SMSes, 
		do the appropriate request response and log the notifications to a text file
		URL Format: http://www.spark-sms.com/letterbox.php?who=%sender%&what=%text%
	*/
	
	if(isset($_GET['who'])){
		
		$sender = $_GET['who'];
		$msg = $_GET['what'];
		//we prep the file for writing and logging information
		$myfile = fopen("letterbox_log.txt", "a") or die("Unable to open file!");
		fwrite($myfile, 'Sender: '.$sender."\n");
		fwrite($myfile, 'Text: '.$msg."\n");
		fwrite($myfile, "\n\n\n");
		fclose($myfile);
		
		//send back a HTTP response
		deliverResponse(true);
		
		/*  we parse each incoming msg to match against the list of keywords
			so as to assign each msg to the appropriate user */
		
		// include the configs / constants for the API connection
		require_once("config/api.php");
		
		//include the classes for recording outbound messages to database
		require_once("classes/connection.php");

		//include format checking function and notification module
		require_once("classes/formatting.php");
		
		//pull in the list of existing keywords from keywords table
		$mysqli = openConnection();
		$keyword_array = getAllKeywordArray($mysqli);
		
		//we set the default assignment of msgs to admin account
		$assoc_user = "kw244";
		
		//we match the inbound msg to the appropriate user by keyword
		foreach($keyword_array as $keyword => $user_name){
			//we check for each $keyword if the $msg contains it
			if(containsKeyword($msg, $keyword)){
				$assoc_user = $user_name;
				
				
				//we send the autoreply if it is not null
				$autoreply = getAutoReply($mysqli, $keyword);
				
				
				if(!is_null($autoreply['text'])){
					//we setup the necessary fields for the API call					
					$api_fields = array(	//recipients to be handled below according to input source
									'from' => '6590249973',
									'to'   => array($sender),
									'text' => $autoreply['text']
								);
					
					$api_result = APISendSMS($api_fields);
					$json_result = json_decode($api_result, true);
								
					//we log the SMS as an outbound message in the database
					$outbound_data = array(
									//a bulkId is generated regardless of # recipients as long as a recipients_array is submitted
									'api_ref_id'=>$json_result['bulkId'],  
									'title'=>'Auto-Reply ['.$keyword.']',
									'from'=>'6590249973',
									'to'=>implode(', ',$api_fields['to']) ,	//Convert recipients array
									'text'=>$autoreply['text'],
									'status'=>$json_result['messages'][0]['status']['groupName'],  //TODO might need to 
									'credits_used'=> $autoreply['num_msg']
								);
					//enter details of outbound msg into outbound table and deduct appropriate user credits
					uploadOutboundInfo($mysqli,$outbound_data);
					subtractUserCredits($mysqli,$outbound_data['credits_used']);
												
				}
				break;
			} 
		}
		//we upload the inbound msg
		uploadInboundInfo($mysqli, $sender, $msg, $assoc_user);

		// close connection 
		closeConnection($mysqli);
		
	}
	else {
		deliverResponse(false);
	}
	
	//We provide 2 possible responses for any requests to this callback URL
	//no body is returned
	function deliverResponse($status_ok){
		if($status_ok){
			header("HTTP/1.1 200 OK");
		}
		else{
			header("HTTP/1.1 400 Bad Request");
		}
		
	}
	

?>
