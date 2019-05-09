<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); 
header('Expires: Sun, 01 Jul 2005 00:00:00 GMT'); 
header('Pragma: no-cache'); 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
require_once('config7.php');
			
	$query="SELECT * FROM qshow";								// Query start
	if (isSet($_GET['email'])) {		 						// If set
		$email=strtolower(addslashes($_GET['email']));			// Get email
		$query.=" WHERE LOWER(email) = '".$email."' ORDER by date DESC";	// WHERE email search
		}
	else{
		print("Sorry, there are no projects with this email\n");// Return error
		return;
		}
	$result=mysqli_query($link, $query);						// Run query
		if ($result == false) {									// Bad query
		print("Error getting projects");						// Return error
		mysqli_free_result($result);							// Free
		mysqli_close($link);									// Close session
		exit();													// Quit
		}
	$pass=strtolower(addslashes($_GET['pass']));				// Get pass option
	$body="Here are projects saved under the email ".$email.":\n\n";// Header
	if ($pass == 5) {											// Getting folio password
		$body="Here is your password for ".$email.":\n\n";		// Header
		while ($row=mysqli_fetch_assoc($result)) {				// Loop through rows
			if ($row["version"] == 5)							// If folio
				$body.="Password: ".$row["password"]."\n";		// Add password
			}
		}			
	else{
		while ($row=mysqli_fetch_assoc($result)) {				// Loop through rows
			$body.="Id:" .$row["id"]."\t";						// Add id
			$body.="Priv: ".$row["private"])."\t";				// Add private
			$body.="Del: ".$row["deleted"]."\t";				// Add Deleted
			$body.="Date: ".$row["date"]."\t";					// Add Date
			$body.="Pass: ".$row["password"]."\t";				// Add password
			$body.="Title: ".$row["title"]."\n";				// Add title
			}		
		}
	
	mysqli_free_result($result);								// Free
	mysqli_close($link);										// Close session
	ini_set("sendmail_from",$email);							// Close
	$sub="Here are your saved projects...";						// Subject			
	mail($email,$sub,$body,"From: $email\nReply-To: $email\n");	// Mail it
?>
	
