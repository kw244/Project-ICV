<?php

	/* 	checks if $raw_number fits the standard SG phone number format 65XXXXYYYY
		returns true if so, false otherwise */
	function checkSGNum($raw_number) {

		$length_ok = strlen($raw_number)===10;
		$cc_ok = substr($raw_number,0,2)==='65';
		$num_ok = isDigits($raw_number);

		return $length_ok && $cc_ok && $num_ok;
	}
	
	/*	Takes in a string input and removes whitespace from it */
	function removeWhitespace($input){
		return preg_replace('/\s+/', '', $input);
	}
	
	/*	Takes in a string input and removes non-digit characters from it */
	function removeNonDigit($input){
		return preg_replace('/\D+/', '', $input);
	}
	
	/*	Takes in a string $input and checks that it is all digits 0-9 */
	function isDigits($input){
		return preg_match('/\d+/',$input);
	}
	
	/*	Takes in a phone number, $inputNum, and converts it to 65XXXXYYYY format  
		Handles phone numbers in (a) XXXXYYYY  (b) with +65-XXXXYYYY (c) 65-XXXXYYYY (d) 65 XXXXYYYY
	*/
	function cleanSGNum($inputNum){
		//we remove whitespace and non-digits
		$outputNum = removeWhitespace($inputNum);
		$outputNum = removeNonDigit($outputNum);
		
		//we add the 65 prefix if $outputNum is only 8-digits length
		if(strlen($outputNum)===8){
			$outputNum = '65'.$outputNum;
		}
		return $outputNum;
		
	}
	
	
	/*	Takes in a string input of comma-separated phone numbers and counts it 	*/
	function countRecipients($recipientsStr){
		$recipientsArray = explode(",", $recipientsStr);
		return count($recipientsArray);
	}

	
	/*  Generates a notification element displaying $inputStr with notification type of $notifType*/
	function createNotif($notifType, $inputStr){
		echo '<div class="row placeholders">';
		echo '<div class="notice '.$notifType.' text-left">';
		echo '<p> '.$inputStr.' </p>';
		echo '</div>';
		echo '</div>';
	}
	

?>