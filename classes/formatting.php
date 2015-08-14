<?php

	/* 	checks if $raw_number fits the standard SG phone number format 65XXXXYYYY
		returns true if so, false otherwise */
	function checkSGPhone($raw_number) {
		
		
		$length_ok = strlen($raw_number)===10;
		$cc_ok = substr($raw_number,0,2)==='65';
		$num_ok = is_numeric(substr($raw_number,2,8)); //TODO: not a perfect check

		return $length_ok && $cc_ok && $num_ok;
	}
	
	/*	Takes in the form inputs of a String of phone numbers (comma-separated)
		and returns them in an array 
	*/
	function numbersStringToArray($input){
		//removes whitespace and separates elements by commas
		return array_map('trim', explode(',', $input));
	}

?>