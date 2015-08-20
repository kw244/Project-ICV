<?php

	/*	Listen for any HTTP POST requests -> SMS delivery reports, 
		do the appropriate request response and log the notifications to a text file
	*/
	$json_array = json_decode(file_get_contents('php://input'),true);
	$delivery_summary = array();
	
	if(isset($json_array['results'])){
		
		//we prep the file for writing and logging information
		$myfile = fopen("delivery_log.txt", "a") or die("Unable to open file!");
		fwrite($myfile, 'bulkId: '.$json_array['results'][0]['bulkId']."\n");
		fwrite($myfile, "Undelivered Msgs: \n");
		
		/*	we loop through each delivery report to:
			(a) collect the statistics of each bulk send - total SMS, number of delivered
			(b) log the delivery output */
		foreach($json_array['results'] as $result){
			
			$delivery_summary['total_SMS'] = $delivery_summary['total_SMS'] + $result['smsCount'];
			//if msg is successfuly delivered			
			if($result['status']['groupId']===3){
				$delivery_summary['num_delivered'] = $delivery_summary['num_delivered'] + $result['smsCount'];
			}
			//else we take note of affected recipient and error message
			else {	
				fwrite($myfile, 'To: '.$result['to']."\n");
				fwrite($myfile, 'MsgId: '.$result['messageId']."\n");
				fwrite($myfile, 'Issue: '.$result['status']['groupName'].'/'.$result['error']['groupName']."\n");
			}
		}
		
		fwrite($myfile, 'Total SMS: '.$delivery_summary['total_SMS']."\n");
		fwrite($myfile, 'Num Delivered: '.$delivery_summary['num_delivered']."\n");
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
