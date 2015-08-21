<?php

/*	We include the authentication to the REST API service here
	We're currently using Hoiio. See http://developer.hoiio.com/docs/sms_bulk_send.html for API details
*/

	define("API_AUTH",'a3cyNDQ6UXdlciExMjM=');
	define("API_SEND_URL","https://api.infobip.com/sms/1/text/advanced"); 
	define("API_CALLBACK_URL","http://www.spark-sms.com/callback.php");

	
	/* 	Takes in an associative array with the request parameters for the SEND API call,
		then sets it up and executes it in cURL
	*/
	function APISendSMS($request_params){
		
		//Setup the json object in correct format to be sent to the API
		$dest_num = array();
		foreach ($request_params['to'] as $num) {
			$dest_num[] = array('to'=>$num);
		}
		$messages = array(array(
						'from' => $request_params['from'],
						'destinations' => $dest_num,
						'text' => $request_params['text'],
						'notifyUrl' => API_CALLBACK_URL,
						'notifyContentType' => "application/json"
					)); 
		$json_obj = json_encode(array('messages'=>$messages));
		
		$api_handle = curl_init();
		curl_setopt($api_handle, CURLOPT_URL, API_SEND_URL);
		curl_setopt($api_handle, CURLOPT_POST, true);
		curl_setopt($api_handle, CURLOPT_HTTPHEADER, array('Authorization: Basic '.API_AUTH ,'Content-Type: application/json'));
		curl_setopt($api_handle, CURLOPT_POSTFIELDS, $json_obj);
		curl_setopt($api_handle, CURLOPT_SSL_VERIFYPEER, false); //come back to this
		curl_setopt($api_handle, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($api_handle);
		curl_close($api_handle);
		return $response;		
		
	}

?>