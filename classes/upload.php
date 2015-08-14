<?php
	// include the configs / constants for the database connection
	require_once("config/db.php");
	
	//open mysql database connection
	$mysqli = openConnection();

	//display contacts information for the logged in user in table rows
	if(isset($_POST['submit']))
    {
         $fname = $_FILES['fileToUpload']['name'];
         echo 'upload file name: '.$fname.' ';
         $chk_ext = explode(".",$fname);
        
         if(strtolower(end($chk_ext)) == "csv")
         {
        
             $filename = $_FILES['fileToUpload']['tmp_name'];
             $handle = fopen($filename, "r");
       
             while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
             {
                uploadContactsInfo($mysqli,$data);
             }
       
             fclose($handle);
             echo "Successfully Imported";
            
         }
         else
         {
             echo "Invalid filetype. Please only upload CSV files";
         }   
    }
						
	// close connection 
	closeConnection($mysqli);
	
    
?>