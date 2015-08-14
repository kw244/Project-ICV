<div class="container-fluid">
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		
		<h4>Send SMS</h4>

		<div class="col-sm-8 col-md-6 main bg-light-grey">
			<?php
				//include format checking function
				require_once("classes/formatting.php");
				
				//we test the collection of data from our form elements
				if(isset($_POST['submit_sms'])){
					echo "Sender Name: ".$_POST['send_as_name']."<br/>";
					echo "Campaign Name: ".$_POST['campaign_title']."<br/>";
					echo "SMS Text: ".$_POST['sms_text']."<br/>";	
				}
				
				//handles SMS functionality through phone number entry
				if(isset($_POST['submit_sms']) && isset($_POST['send_to_numbers']))
				{
					echo "Send To Numbers: ".$_POST['send_to_numbers']."<br/>";
					foreach (numbersStringToArray($_POST['send_to_numbers']) as $num){
						echo $num."<br/>";
					}
				}
				
				//handles SMS functionality through CSV upload
				if(isset($_POST['submit_sms']) && isset($_FILES['fileToUpload']))
				{
					 $fname = $_FILES['fileToUpload']['name'];
					 $chk_ext = explode(".",$fname);
					
					 if(strtolower(end($chk_ext)) == "csv")
					 {
						 $filename = $_FILES['fileToUpload']['tmp_name'];
						 $handle = fopen($filename, "r");
						 while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
						 {
							echo $data[0]."<br/>";
						 }
						 fclose($handle);
					 }
					 //we issue a warning for user trying to upload non-CSV file
					 else
					 {
						echo '<div class="row placeholders">';
						echo '<div class="notice warning text-left">';
						echo '<p> Invalid filetype. Please only upload CSV files </p>';
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

