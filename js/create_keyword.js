	$(function(){
		//Generates comment about validity of keyword
		$('#keyword_input').keyup(function() {
			var keyword = $(this).val();

			
		
			//if user has input a keyword candidate
			if(keyword != ''){
				//we check if keyword is a single alphanumeric word; keywords made of multiple words not permitted
				var regex = new RegExp("^[a-zA-Z0-9_]+$");
				if(regex.test(keyword)){
					//we post the keyword to the check_keyword.php script to check against the keywords table
					$.ajax({
						url: 'check_keyword.php',
						type: 'POST',
						data: {keyword_check: keyword},
						success: function(data){
							$('#keyword_text_comment').text(data);
						}
					});
					
				}
				else {
					$('#keyword_text_comment').text('"' + keyword + '" isn\'t an accepted keyword format');
				}
				
			} 
			//keyword candidate is empty string
			else {
				$('#keyword_text_comment').text('');
			}
		});

		
	});



	
	
