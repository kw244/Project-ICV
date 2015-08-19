<?php

/**
 * helper functions to send database requests
 */

	// include the configs / constants for the database connection
	require_once("config/db.php");
	
	//open database connection
	function openConnection(){
		//Open a new connection to the MySQL server
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	
		//Output any connection error
		if ($mysqli->connect_error) {
			die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);	
		}
		return $mysqli;
	}
	
	// close database connection 
	function closeConnection($mysqli){	
		$mysqli->close();
	}
	
	//returns contact information (contact_name, contact_number) in html table row
	//if $checkbox===true, we include a checkbox in each table row for subsequent record deletion
	function getContactInfo($mysqli,$checkbox){
		$user_name = $_SESSION['user_name'];
		
		//create the prepared statement
		/**	We use a subselect where we (a) get the contact ids associated with the user
			and then (b) get their corresponding contact_name and contact_number from contacts table
		**/
		$query = "SELECT contact_id, contact_name, contact_country_code, contact_number FROM contacts WHERE contact_id IN 
				(SELECT contact_id FROM contacts_users WHERE user_name=?)";
		$statement = $mysqli->prepare($query);
		
		
		//bind parameters for markers where (s=string, i=integer, d=double, b=blob)
		$statement->bind_param('s',$user_name);
		
		//execute query
		$statement->execute();
		
		//bind result variables
		$statement->bind_result($contact_id, $contact_name,$contact_country_code,$contact_number);
		

		//get contacts info in html table row with checkbox for delete_contacts
		if ($checkbox){
			while($statement->fetch()) {
				print '<tr>';
				print '<td><input type="checkbox" name="checkbox[]" value='.$contact_id.' id="checkbox"></td>';
				print '<td class="name">'.$contact_name.'</td>';
				print '<td>'.$contact_country_code." ".$contact_number.'</td>';
				print '<td>Tags go here</td>';
				print '</tr>';
			}
		}
		//get contacts info in html table row with checkbox for display_contacts
		else {
			while($statement->fetch()) {
				print '<tr>';
				print '<td class="name">'.$contact_name.'</td>';
				print '<td>'.$contact_country_code." ".$contact_number.'</td>';
				print '<td>Tags go here</td>';
				print '</tr>';
			}  
		}
		
	}
	
	//returns outbound msg information Date/Time,From,To,Campaign,SMS Text,Status in html table row
	//if $checkbox===true, we include a checkbox in each table row for subsequent record deletion
	function getOutboundInfo($mysqli,$checkbox){
		$user_name = $_SESSION['user_name'];
		
		//create the prepared statement
		/**	We use a subselect where we (a) get the outbound ids associated with the user
			and then (b) get the corresponding outbound msg infomation from outbound table
		**/
		$query = "SELECT outbound_id, outbound_created, outbound_from, outbound_to, outbound_title, outbound_text, outbound_status FROM outbound WHERE outbound_id IN 
				(SELECT outbound_id FROM outbound_users WHERE user_name=?)";
		$statement = $mysqli->prepare($query);
		
		
		//bind parameters for markers where (s=string, i=integer, d=double, b=blob)
		$statement->bind_param('s',$user_name);
		
		//execute query
		$statement->execute();
		
		//bind result variables
		$statement->bind_result($outbound_id,$outbound_created,$outbound_from,$outbound_to,$outbound_title,$outbound_text,$outbound_status);
		

		//get contacts info in html table row with checkbox for delete_contacts
		if ($checkbox){
			while($statement->fetch()) {
			print '<tr>';
			print '<td><input type="checkbox" name="checkbox[]" value='.$outbound_id.' id="checkbox"></td>';
			print '<td>'.$outbound_created.'</td>';
			print '<td>'.$outbound_from.'</td>';
			print '<td>'.$outbound_to.'</td>';
			print '<td>'.$outbound_title.'</td>';
			print '<td>'.$outbound_text.'</td>';
			print '<td>'.$outbound_status.'</td>';
			print '</tr>';
			}
		}
		//get contacts info in html table row with checkbox for display_contacts
		else {
			while($statement->fetch()) {
			print '<tr>';
			print '<td>'.$outbound_created.'</td>';
			print '<td>'.$outbound_from.'</td>';
			print '<td>'.$outbound_to.'</td>';
			print '<td>'.$outbound_title.'</td>';
			print '<td>'.$outbound_text.'</td>';
			print '<td>'.$outbound_status.'</td>';
			print '</tr>';
			}  
		}
		
		
	}
	
	//Takes in a mysqli connection and deletes the contact with $contact_id from contacts table
	//Be careful of cascade effects on related tables (contact_id is a primary key)
	function removeContactInfo($mysqli, $contact_id){
		
		//setup the prepared statement for the delete query
		$query = "DELETE FROM contacts WHERE contact_id=?";
		$statement = $mysqli->prepare($query);
		
		//bind parameters for markers where (s=string, i=integer, d=double, b=blob)
		$statement->bind_param('i',$contact_id);
		
		//execute query and print any errors that occur
		if(!$statement->execute()){
			print 'Failed to DELETE FROM (contacts table): '.$contact_id;
			return false;
		}
		return true;
	}
	//Takes in a mysqli connection and deletes the keyword with $keyword_id from keywords table
	function removeKeyword($mysqli, $keyword_id){
		
		//setup the prepared statement for the delete query
		$query = "DELETE FROM keywords WHERE keyword_id=?";
		$statement = $mysqli->prepare($query);
		
		//bind parameters for markers where (s=string, i=integer, d=double, b=blob)
		$statement->bind_param('i',$keyword_id);
		
		//execute query and print any errors that occur
		if(!$statement->execute()){
			print 'Failed to DELETE FROM (keywords table): '.$keyword_id;
			return false;
		}
		
		addKeywordCredits($mysqli,1);
		return true;
	}
	
	
	//returns list of all keywords in an array
	//no external parameters needed so we don't use prepared statement
	function getAllKeywordArray($mysqli){
		$query = "SELECT keyword_name FROM keywords";
		$results = $mysqli->query($query);
		$output_array = array();
		
		while($row = $results->fetch_assoc()){
			$output_array[] = $row["keyword_name"];
		}

		return $output_array;
	}
	
	//returns the keywords associated with the user in html list item
	//if $checkbox===true, we include a checkbox in each row for subsequent record deletion
	
	function displayKeywords($mysqli, $checkbox){
		$user_name = $_SESSION['user_name'];
	
		//create the prepared statement
		$query = "SELECT keyword_id, keyword_name FROM keywords WHERE user_name=?";
		$statement = $mysqli->prepare($query);
		
		//bind parameters for markers where (s=string, i=integer, d=double, b=blob)
		$statement->bind_param('s',$user_name);
		
		//execute query
		$statement->execute();
		
		//bind result variables
		$statement->bind_result($keyword_id,$keyword_name);
		
		//fetch records into html table row
		if($checkbox){
			while($statement->fetch()) {
				
				print '<tr>';
				print '<td><input type="checkbox" name="checkbox[]" value='.$keyword_id.' id="checkbox"></td>';
				print '<td>'.$keyword_name.'</td>';
				print '</tr>';
			}
		}
		else {
			while($statement->fetch()) {
				print '<tr>';
				print '<td>'.$keyword_name.'</td>';
				print '</tr>';
			}
		}
	}
	
	//returns account information (user_name, account_type,sms_credits) in html table row
	function getAccountInfo($mysqli){

		$user_name = $_SESSION['user_name'];
	
		//create the prepared statement
		$query = "SELECT user_name, account_type, sms_credits, keyword_credits FROM accounts WHERE user_name=?";
		$statement = $mysqli->prepare($query);
		
		//bind parameters for markers where (s=string, i=integer, d=double, b=blob)
		$statement->bind_param('s',$user_name);
		
		//execute query
		$statement->execute();
		
		//bind result variables
		$statement->bind_result($user_name,$account_type,$sms_credits,$keyword_credits);
		
		//fetch records into html table row
		while($statement->fetch()) {
			print '<tr>';
			print '<td>'.$user_name.'</td>';
			print '<td>'.$account_type.'</td>';
			print '<td>'.$sms_credits.'</td>';
			print '<td>'.$keyword_credits.'</td>';
			print '</tr>';
		}  
	}
	
	//updates the accounts table with the necessary $deduction from the user's keyword_credits
	//Returns true if completed successfully, false otherwise
	function subtractKeywordCredits($mysqli,$deduction){

		$user_name = $_SESSION['user_name'];
	
		//create the prepared statement
		$query = "UPDATE accounts SET keyword_credits = keyword_credits - ? WHERE user_name=?";
		$statement = $mysqli->prepare($query);
		
		//bind parameters for markers where (s=string, i=integer, d=double, b=blob)
		$statement->bind_param('ss',$deduction,$user_name);
		
		//execute query and print any errors that occur
		if(!$statement->execute()){
			print 'Failed to update accounts table with deduction: '.$deduction;
			return false;
		}
		return true;
	}
	
	//updates the accounts table with the necessary $addition to the user's keyword_credits
	//Returns true if completed successfully, false otherwise
	function addKeywordCredits($mysqli,$addition){

		$user_name = $_SESSION['user_name'];
	
		//create the prepared statement
		$query = "UPDATE accounts SET keyword_credits = keyword_credits + ? WHERE user_name=?";
		$statement = $mysqli->prepare($query);
		
		//bind parameters for markers where (s=string, i=integer, d=double, b=blob)
		$statement->bind_param('ss',$addition,$user_name);
		
		//execute query and print any errors that occur
		if(!$statement->execute()){
			print 'Failed to update accounts table with addition: '.$addition;
			return false;
		}
		return true;
	}
	
	
	//updates the accounts table with the necessary $deduction from the user's sms_credits
	//Returns true if completed successfully, false otherwise
	function subtractUserCredits($mysqli,$deduction){

		$user_name = $_SESSION['user_name'];
	
		//create the prepared statement
		$query = "UPDATE accounts SET sms_credits = sms_credits - ? WHERE user_name=?";
		$statement = $mysqli->prepare($query);
		
		//bind parameters for markers where (s=string, i=integer, d=double, b=blob)
		$statement->bind_param('ss',$deduction,$user_name);
		
		//execute query and print any errors that occur
		if(!$statement->execute()){
			print 'Failed to update accounts table with deduction: '.$deduction;
			return false;
		}
		return true;
	}
	
	//updates the accounts table with the necessary $addition to the user's sms_credits
	//Returns true if completed successfully, false otherwise
	function addUserCredits($mysqli,$addition){

		$user_name = $_SESSION['user_name'];
	
		//create the prepared statement
		$query = "UPDATE accounts SET sms_credits = sms_credits + ? WHERE user_name=?";
		$statement = $mysqli->prepare($query);
		
		//bind parameters for markers where (s=string, i=integer, d=double, b=blob)
		$statement->bind_param('ss',$addition,$user_name);
		
		//execute query and print any errors that occur
		if(!$statement->execute()){
			print 'Failed to update accounts table with addition: '.$addition;
			return false;
		}
		return true;
	}
	
	
	/*	Takes in an associative array, $data, and inserts its contents into the outbound database
		Also populates the outbound_users database
		Returns true if completed successfully, false otherwise
	*/
	function uploadOutboundInfo($mysqli,$data){
		$user_name = $_SESSION['user_name'];
		
		//create the prepared statement
		$query = "INSERT INTO outbound (outbound_ref_id,outbound_title,outbound_from,outbound_to,outbound_text,outbound_status,outbound_credits_used) VALUES (?,?,?,?,?,?,?)";
		$statement = $mysqli->prepare($query);
		
		//bind parameters for markers where (s=string, i=integer, d=double, b=blob)
		$statement->bind_param('sssssss',$data['api_ref_id'],$data['title'],$data['from'],$data['to'],$data['text'],$data['status'],$data['credits_used']);
		
		//execute query and print any errors that occur
		if(!$statement->execute()){
			print 'Failed to insert (outbound table): '.$data['api_ref_id'].' / '.$data['title'];
			return false;
		}
		//outbound info has been successfully inserted into outbound table
		//we now insert its corresponding (outbound,user) mapping into outbound_users table
		else {
			//We get the last inserted outbound_id
			$query2 = "SELECT MAX(outbound_id) AS outbound_id FROM outbound";
			$outbound_id = $mysqli->query($query2)->fetch_object()->outbound_id;
			
			//and insert it into outbound_users with the user_name
			$query3 = "INSERT INTO outbound_users (outbound_id,user_name) VALUES (?,?)";
			$statement3 = $mysqli->prepare($query3);
			$statement3->bind_param('ss',$outbound_id, $user_name);
			
			//execute query and print any errors that occur
			if(!$statement3->execute()){
				print 'Failed to insert (outbound_users table). outbound_id: '. $outbound_id . ' & user_name: ' . $user_name;
				return false;
			}
			return true;
		}
		
		
	}
	
	/* 	Takes in a keyword, $keyword, and inserts it into the keywords table */
	function uploadKeyword($mysqli,$keyword){
		$user_name = $_SESSION['user_name'];
		
		//create the prepared statement
		$query = "INSERT INTO keywords (keyword_name, user_name) VALUES (?,?)";
		$statement = $mysqli->prepare($query);
		
		//bind parameters for markers where (s=string, i=integer, d=double, b=blob)
		$statement->bind_param('ss',$keyword,$user_name);
		
		//execute query and print any errors that occur
		if(!$statement->execute()){
			print 'Failed to insert (keywords table): '.$keyword;
			return false;
		}
		//keyword inserted successfully, now we deduct it from keyword_credits in accounts table
		else {
			subtractKeywordCredits($mysqli,1);
			return true;
		}
		
	}
	
	/* 	takes in an array, $data, and inserts its contents into the contacts table
		assumes that 1st element holds contact_name and 2nd element holds entire phone number incl. country code
		also populates contacts_users database  */
	function uploadContactsInfo($mysqli, $data){
		//include format checking function
		require_once("classes/formatting.php");
		
		//we check if phone number is in correct format and generate warning if not
		if (!checkSGPhone($data[1])){
			print $data[1].' has invalid phone format. Ensure it is in 65XXXXXXXX';
			return false;
		}

		//boolean used to track success of upload, returns true if all records uploaded successfully, false otherwise
		$successBool = true;
		$user_name = $_SESSION['user_name'];
	
		//create the prepared statement
		$query = "INSERT INTO contacts (contact_name, contact_country_code, contact_number) VALUES (?,?,?)";
		$statement = $mysqli->prepare($query);
		
		//bind parameters for markers where (s=string, i=integer, d=double, b=blob)
		//we parse the $data[1], into country code and phone number
		$contact_name = $data[0];
		$contact_country_code = substr($data[1],0,2);
		$contact_number = substr($data[1],2,8);
		$statement->bind_param('sss',$contact_name,$contact_country_code,$contact_number);
		
		//execute query and print any errors that occur
		if(!$statement->execute()){
			print 'Failed to insert (contacts table): '.$data[0];
			return false;
		}
		
		//contact info has been successfully inserted into contacts table
		//we now insert its corresponding (contact,user) mapping into contacts_users table
		else {
			//We get the last inserted contact_id
			$query2 = "SELECT MAX(contact_id) AS contact_id FROM contacts";
			$contact_id = $mysqli->query($query2)->fetch_object()->contact_id;
			
			//and insert it into contacts_users with the user_name
			$query3 = "INSERT INTO contacts_users (contact_id,user_name) VALUES (?,?)";
			$statement3 = $mysqli->prepare($query3);
			$statement3->bind_param('is',$contact_id, $user_name);
			
			//execute query and print any errors that occur
			if(!$statement3->execute()){
				print 'Failed to insert (contacts_users table). contact_id: '. $contact_id . ' & user_name: ' . $user_name;
				return false;
			}
			return true;
		}

	}
	

	
 ?>