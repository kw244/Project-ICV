<?php

	/*	Listen for any HTTP GET requests -> Incoming SMSes, 
		do the appropriate request response and log the notifications to a text file
		URL Format: http://www.spark-sms.com/letterbox.php?who=%sender%&what=%text%
	*/
	
	if(isset($_GET['who'])){

		//we prep the file for writing and logging information
		$myfile = fopen("letterbox_log.txt", "a") or die("Unable to open file!");
		fwrite($myfile, 'Sender: '.$_GET['who']."\n");
		fwrite($myfile, 'Text: '.$_GET['what']."\n");
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
