<div class="container-fluid">
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		
		<h4>Send SMS</h4>

		<div class="col-sm-8 col-md-6 main bg-light-grey">
			<?php
			
				// include the configs / constants for the API connection
				require_once("config/api.php");
			
				//include the classes for recording outbound messages to database
				require_once("classes/connection.php");
			
				//include format checking function and notification module
				require_once("classes/formatting.php");

				//Handle sending of SMS from web form
				if(isset($_POST['submit_sms'])){
					
					//we collect the msg meta info created by send_sms.js from the hidden fields
					$is_gsm = (bool) $_POST["sms_text_is_gsm"];
					$num_msg = (int) $_POST["sms_text_num_msg"];
					
				
					//we setup the necessary fields for the API call					
					$api_fields = array(	//recipients to be handled below according to input source
									'from' => $_POST['send_as_name'],
									'text' => $_POST['sms_text']
								);
					
					//we setup the recipients of the SMS according to input source
					//"Enter Number(s)"
					$recipients_array = array();
					
					if (isset($_POST['send_to_numbers'])){
						$input_num_array = explode(',',$_POST['send_to_numbers']);
						
						foreach($input_num_array as $raw_num){
							$input_num = cleanSGNum($raw_num);
							
							//check if phone number is valid
							if(checkSGNum($input_num)){
								$recipients_array[] = $input_num;
							}
							else {
								echo $input_num.' is not a valid SG phone number';
							}
						}
						$api_fields['to'] =  $recipients_array;
					}
					//"Upload CSV"
					elseif (isset($_FILES['fileToUpload'])){
						 $fname = $_FILES['fileToUpload']['name'];
						 $chk_ext = explode(".",$fname);
						
						 if(strtolower(end($chk_ext)) == "csv")
						 {
							 $filename = $_FILES['fileToUpload']['tmp_name'];
							 $handle = fopen($filename, "r");
							 
							 while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
							 {
								$input_num = cleanSGNum($data[0]);
								//check if phone number is valid
								if(checkSGNum($input_num)){
									$recipients_array[] = $input_num;
								}
								else {
									echo $input_num.' is not a valid SG phone number';
								}
								
							 }
							 fclose($handle);
							 $api_fields['to'] =  $recipients_array;
						 }
						 else
						{
							createNotif("warning","Invalid filetype. Please only upload CSV files");
						} 
					}
					//TODO: Implement from contacts/tags
					else {

					}
					
					//we send the SMS to the API with the appropriate request parameters in the associative array, $api_fields
					if(isset($api_fields['to'])){
						
						//$num_msgs is permitted: we go ahead and call the API to send SMS
						if($num_msg > 0){
							$api_result = APISendSMS($api_fields);
							echo $api_result."<br/>";
							$json_result = json_decode($api_result, true);
								
							//we log the SMS as an outbound message in the database
							$outbound_data = array(
											//a bulkId is generated regardless of # recipients as long as a recipients_array is submitted
											'api_ref_id'=>$json_result['bulkId'],  
											'title'=>$_POST['campaign_title'],
											'from'=>$_POST['send_as_name'],
											'to'=>implode(', ',$api_fields['to']) ,	//Convert recipients array
											'text'=>$_POST['sms_text'],
											'status'=>$json_result['messages'][0]['status']['groupName'],  //TODO might need to 
											'credits_used'=> $num_msg*countRecipients($_POST['send_to_numbers'])
		
										);
										
							//open mysql database connection
							$mysqli = openConnection();

							//enter details of outbound msg into outbound table and deduct appropriate user credits
							uploadOutboundInfo($mysqli,$outbound_data);
							subtractUserCredits($mysqli,$outbound_data['credits_used']);
												
							// close connection 
							closeConnection($mysqli);
							createNotif("success","SMS successfully sent");
						}
						//number of msgs restriction(3) exceeded
						else{
							createNotif("warning","Maximum number of characters exceeded");
						}						

					}
					//Error handling for invalid Send To field
					else {
						createNotif("warning","Send To field is invalid. Please try again");				
					}
				}


			?>
			
			<form action="index.php?SMS&menu=send_sms" method="post" enctype="multipart/form-data">
		
				<div class="form-group">	
					<label>Send To </label>					
					<div class="input-group">
						<div class="input-group-btn">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Select Input <span class="caret"></span></button>
							<ul class="dropdown-menu send_to_options">
							  <li><a href="#">Enter Number(s) </a></li>
							  <li><a href="#">Upload CSV </a></li>
							  <li><a href="#">Choose from Contacts </a></li>
							</ul>
						</div><!-- /btn-group -->
						<!--<input type="text" class="form-control">-->
					</div><!-- /input-group -->

				</div>
				<div class="form-group">	
					<label>Send As </label>	
					<input type="text" class="form-control" placeholder="Enter Brand/Number" name="send_as_name" required="required" />
				</div>
				<div class="form-group">	
					<label>Title </label>	
					<input type="text" class="form-control" placeholder="Enter Campaign Title" name="campaign_title" required="required" />
				</div>
				<div class="form-group sms_text_form">	
					<label>Message </label>	
					<textarea class="form-control" rows="4" id="sms_text" name="sms_text" placeholder="Enter SMS Text" required="required"></textarea>
					<p class="text-right" id="sms_text_comment" name="sms_text_comment">GSM: 160/1</p>
					<input type="hidden" id="sms_text_num_msg" name="sms_text_num_msg">
					<input type="hidden" id="sms_text_is_gsm" name="sms_text_is_gsm">
				</div>
				
				<input class="custom-btn btn btn-primary" type="submit" value="Send" name="submit_sms">
			</form>
		
		</div>
	</div>

</div>

