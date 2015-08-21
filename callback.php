<?php

	/*	Listen for any HTTP POST requests -> SMS delivery reports, 
		do the appropriate request response and log the notifications to a text file
	*/
	$json_array = json_decode(file_get_contents('php://input'),true);
	$delivery_summary = array('success'=>true);
	$log_msg = "";
	
	if(isset($json_array['results'])){
		
		//we prep the file for writing and logging information
		$myfile = fopen("delivery_log.txt", "a") or die("Unable to open file!");
		fwrite($myfile, 'bulkId: '.$json_array['results'][0]['bulkId']."\n");
		fwrite($myfile, "Undelivered Msgs: \n");
		$log_msg = $log_msg."bulkId: ".$json_array['results'][0]['bulkId']."\n";
		$log_msg = $log_msg."Undelivered Msgs: \n";

		/*	we loop through each delivery report to:
			(a) collect the statistics of each bulk send - total SMS, number of delivered
			(b) log the delivery output */
		foreach($json_array['results'] as $result){
			
			$delivery_summary['total_SMS'] = $delivery_summary['total_SMS'] + $result['smsCount'];
			//if msg is successfuly delivered			
			if($result['status']['groupId']===3){
				$delivery_summary['num_delivered'] = $delivery_summary['num_delivered'] + $result['smsCount'];
				$delivery_summary['success'] = $delivery_summary['success'] && true;
			}
			//else we take note of affected recipient and error message
			else{	
				fwrite($myfile, 'To: '.$result['to']."\n");
				fwrite($myfile, 'MsgId: '.$result['messageId']."\n");
				fwrite($myfile, 'Issue: '.$result['status']['groupName'].'/'.$result['error']['groupName']."\n");
				$log_msg = $log_msg."To: ".$result['to']."\n";
				$log_msg = $log_msg."MsgId: ".$result['messageId']."\n";
				$log_msg = $log_msg."Issue: ".$result['status']['groupName']."/".$result['error']['groupName']."\n";
				$delivery_summary['success'] = $delivery_summary['success'] && false;
			}
		}
		
		fwrite($myfile, 'Total SMS: '.$delivery_summary['total_SMS']."\n");
		fwrite($myfile, 'Num Delivered: '.$delivery_summary['num_delivered']."\n");
		fwrite($myfile, "\n\n\n");
		fclose($myfile);
		$log_msg = $log_msg."Total SMS: ".$delivery_summary['total_SMS']."\n";
		$log_msg = $log_msg."Num Delivered: ".$delivery_summary['num_delivered']."\n";
		$log_msg = $log_msg."\n\n\n";
		
		
		//include the classes for recording outbound messages to database
		require_once("classes/connection.php");
		
		//we setup database connection to update for delivery results
		$mysqli = openConnection();
		updateOutboundStatus($mysqli,$json_array['results'][0]['bulkId'],$delivery_summary['success']);
		updateOutboundLog($mysqli,$json_array['results'][0]['bulkId'],$log_msg);
		updateOutboundDelivery($mysqli,$json_array['results'][0]['bulkId'],$delivery_summary['num_delivered']);
		closeConnection($mysqli);

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
