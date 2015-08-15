<?php

	/*	Listen for any HTTP POST requests -> SMS transaction and delivery notifications, 
		do the appropriate request response and log the notifications to a text file
	*/

	//Handle transaction notifications
	if(isset($_POST['status'])){
		
		$result_tn = array('status'=>$_POST['status']);
		if($_POST['status']==='success_ok'){
			$result_tn['txn_ref']=$_POST['txn_ref'];
			$result_tn['dest']=$_POST['dest'];
			$result_tn['bulk_txn_ref']=$_POST['bulk_txn_ref'];
		}
		//we log the notifications
		$myfile = fopen("txn_notif.txt", "a") or die("Unable to open file!");
		foreach($result_tn as $key=>$value){
			fwrite($myfile, $key.": ".$value."\n");
		}
		fwrite($myfile, "\n\n\n");
		fclose($myfile);
		
		//then send back a HTTP response
		deliverResponse(true);
	}
	//Handle delivery notifications
	elseif(isset($_POST['sms_status'])){
		
		$result_dn = array(
						'sms_status'=>$_POST['sms_status'],
						'txn_ref'=>$_POST['txn_ref'],
						'tag'=>$_POST['tag'],
						'date'=>$_POST['date'],
						'dest'=>$_POST['dest'],
						'split_count'=>$_POST['split_count'],
						'currency'=>$_POST['currency'],
						'rate'=>$_POST['rate'],
						'debit'=>$_POST['debit'],
					);
		
		//we log the notifications
		$myfile = fopen("delivery_notif.txt", "a") or die("Unable to open file!");
		foreach($result_dn as $key=>$value){
			fwrite($myfile, $key.": ".$value."\n");
		}
		fwrite($myfile, "\n\n\n");
		fclose($myfile);
		
		//then send back a HTTP response
		deliverResponse(true);
	}
	//Invalid HTTP request
	else{
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
