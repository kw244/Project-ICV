	$(function(){
		
		//this dynamically manages the dropdown for the choice of Send To input
		$(".send_to_options li a").on('click',function(){
			var selText = $(this).text();
			// we find & replace the inner html elements that match .dropdown-toggle 
			$(this).parents('.input-group-btn').find('.dropdown-toggle').html(selText+' <span class="caret"></span>');
			
			//we then setup the necessary form elements for the sub-menu 
			$('input').remove('.send_to_input');
			switch(selText){
				case 'Enter Number(s) ':
					$('.input-group').append('<input type="text" class="form-control send_to_input" name="send_to_numbers" required="required">');
					break;
				case 'Upload CSV ':
					$('.input-group').append('<input type="file" class="form-control send_to_input" name="fileToUpload" required="required">');
					break;
				//TODO: not implemented yet
				case 'Choose from Contacts ':
					$('.input-group').append('<input type="text" class="form-control send_to_input" name="send_to_numbers" placeholder="This function has not been implemented yet." required="required">');
					break;
				default:
					break;
			}
		});
		
		/* 	we need to check if the message is GSM or Unicode.
			if GSM, then we can use 1 msg of 160 char or up to 3 msgs of 153 char
			if Unicode, then we can use 1 msg of 70 char or up to 3 msgs of 67 char
		*/
		
		
		//we check the SMS text form for non-GSM characters
		$('#sms_text').on("keyup", function(){
			var raw_text = $(this).val();
			var num_char_available;
			var num_char_left;
			var num_msg = 1;
			var text_length = $(this).val().length; //length of string in SMS text form

			//Handling of GSM SMS messages and POST the appropriate meta info for PHP handling
			if(isGSMAlphabet(raw_text)){
				$("#sms_text_is_gsm").val("1");
			
				//we update text_length to account for extended GSM characters
				text_length = text_length + countGSMExtended(raw_text);
								
				//we check if max message length exceeded
				if(text_length > (153*3)){
					$("#sms_text_comment").text("Maximum number of characters exceeded");
					$("#sms_text_num_msg").val("-1");
				}
				else {
					if(text_length > 160){	//multi-msg
						num_msg = Math.ceil(text_length/153);
						num_char_left = num_msg * 153 - text_length;
						$("#sms_text_comment").text('GSM: ' + num_char_left + '/' + num_msg);					
					}
					else {	//single msg
						num_char_left = 160 - text_length;
						$("#sms_text_comment").text('GSM: ' + num_char_left + '/' + num_msg);
					}
					$("#sms_text_num_msg").val(num_msg);
				}
			
			}
			//Handling of Unicode SMS messages
			else {
				$("#sms_text_is_gsm").val("0");
				
				//we check if max message length exceeded
				if(text_length > (67*3)){
					$("#sms_text_comment").text("Maximum number of characters exceeded");
					$("#sms_text_num_msg").val("-1");

				}
				else {
					if(text_length > 70){	//multi-msg
						num_msg = Math.ceil(text_length/67);
						num_char_left = num_msg * 67 - text_length;
						$("#sms_text_comment").text('UTF: ' + num_char_left + '/' + num_msg);
						
					}
					else {	//single msg
						num_char_left = 70 - text_length;
						$("#sms_text_comment").text('UTF: ' + num_char_left + '/' + num_msg);
					}
					$("#sms_text_num_msg").val(num_msg);
					
				}
			}
		});
		
	});

	
	//checks for GSM Alphabets and returns true if the input, text, is all GSM 
	function isGSMAlphabet(text) {
		var regexp = new RegExp("^[A-Za-z0-9 \\r\\n@£$¥èéùìòÇØøÅå\u0394_\u03A6\u0393\u039B\u03A9\u03A0\u03A8\u03A3\u0398\u039EÆæßÉ!\"#$%&'()*+,\\-./:;<=>?¡ÄÖÑÜ§¿äöñüà^{}\\\\\\[~\\]|\u20AC]*$");
		return regexp.test(text);
	}

	//returns the number of GSM extended characters in text
	function countGSMExtended(text) {
		var gsmExtended = "|^€{}[~]\\";
		var letter="";
		var count = 0;
		for(i = 0; i < text.length; i++){
			letter = text[i];
			if(gsmExtended.indexOf(letter) !== -1){
				count++;
			}
		}
		return count;
	}

	
	
