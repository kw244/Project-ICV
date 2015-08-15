<div class="container-fluid">
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		
		<h4>Send SMS</h4>

		<div class="col-sm-8 col-md-6 main bg-light-grey">
			<?php
			
				// include the configs / constants for the API connection
				require_once("config/api.php");
			
				//include format checking function
				require_once("classes/formatting.php");

				//Handle sending of SMS from web form
				if(isset($_POST['submit_sms'])){
					
					//we setup the necessary fields for the API call					
					
					$api_fields = array(
								'app_id' => API_ID,
								'access_token' => API_TOKEN,
								'msg' => $_POST['sms_text'],
								'tag' => $_POST['campaign_title'],
								'notify_url' => API_CALLBACK_URL
								);
					
					//we setup the recipients of the SMS according to input source
					//"Enter Number(s)"
					if (isset($_POST['send_to_numbers'])){
						$api_fields['dest'] = $_POST['send_to_numbers'];
					}
					//"Upload CSV"
					elseif (isset($_FILES['fileToUpload'])){
						 $fname = $_FILES['fileToUpload']['name'];
						 $chk_ext = explode(".",$fname);
						
						 if(strtolower(end($chk_ext)) == "csv")
						 {
							 $filename = $_FILES['fileToUpload']['tmp_name'];
							 $handle = fopen($filename, "r");
							 $numbersString = "";
							 while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
							 {
								$numbersString = $numbersString.$data[0].',' ;
							 }
							 fclose($handle);
							 $api_fields['dest'] = rtrim($numbersString,",");
						 }
						 else
						{
							echo '<div class="row placeholders">';
							echo '<div class="notice warning text-left">';
							echo '<p> Invalid filetype. Please only upload CSV files </p>';
							echo '</div>';
							echo '</div>';
						} 
					}
					//TODO: Implement from contacts/tags
					else {

					}
					
					//we send the SMS to the API with the appropriate request parameters in the associative array, $api_fields
					if(isset($api_fields['dest'])){
						echo '<p>'.APISendSMS($api_fields).'</p>';
	
					}
					//Error handling for invalid Send To field
					else {
						echo '<div class="row placeholders">';
						echo '<div class="notice warning text-left">';
						echo '<p> Send To field is invalid. Please try again </p>';
						echo '</div>';
						echo '</div>';
						
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
					<p class="text-right" id="sms_text_comment">160/1</p>
				</div>
				
				<input class="custom-btn btn btn-primary" type="submit" value="Send" name="submit_sms">
			
			</form>
		
		</div>
	</div>

</div>

