<?php

	/*	Listen for any HTTP GET requests -> Incoming SMSes, 
		do the appropriate request response and log the notifications to a text file
	*/
	$json_array = json_decode(file_get_contents('php://input'),true);
	
	if(isset($json_array['who'])){
		
		//we prep the file for writing and logging information
		$myfile = fopen("letterbox_log.txt", "a") or die("Unable to open file!");
		fwrite($myfile, 'Sender: '.$json_array['who']."\n");
		fwrite($myfile, 'Text: '.$json_array['what']."\n");
		fwrite($myfile, "\n\n\n");
		fclose($myfile);
		
		//then send back a HTTP response
		deliverResponse(true);
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
