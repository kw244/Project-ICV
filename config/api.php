<?php

/*	We include the authentication to the REST API service here
	We're currently using Hoiio. See http://developer.hoiio.com/docs/sms_bulk_send.html for API details
*/

	define("API_ID","1myqLDr7NFKjrxNU");
	define("API_TOKEN","cwPRV0VtOhWd30Rf"); 
	define("API_SEND_URL","https://secure.hoiio.com/open/sms/bulk_send"); 
	define("API_CALLBACK_URL","http://www.cafechampion.com/StarSMS/callback.php");

	
	/* 	Takes in an associative array with the request parameters for the SEND API call,
		then sets it up and executes it in cURL
	*/
	function APISendSMS($request_params){
		
		$api_handle = curl_init();
		$request_params_string = http_build_query($request_params);
		curl_setopt($api_handle, CURLOPT_URL, API_SEND_URL);
		curl_setopt($api_handle, CURLOPT_POST, true);
		curl_setopt($api_handle, CURLOPT_POSTFIELDS, $request_params_string);
		curl_setopt($api_handle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($api_handle, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($api_handle);
		curl_close($api_handle);
		return $response;		
		
	}

?>