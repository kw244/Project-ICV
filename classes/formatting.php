<?php

	/* 	checks if $raw_number fits the standard SG phone number format 65XXXXYYYY
		returns true if so, false otherwise */
	function checkSGPhone($raw_number) {
		
		
		$length_ok = strlen($raw_number)===10;
		$cc_ok = substr($raw_number,0,2)==='65';
		$num_ok = is_numeric(substr($raw_number,2,8)); //TODO: not a perfect check

		return $length_ok && $cc_ok && $num_ok;
	}
	
	/*	Takes in a string input and removes whitespace from it */
	function removeWhitespace($input){
		return preg_replace('/\s+/', '', $input);
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