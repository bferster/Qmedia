<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); 
header('Expires: Sun, 01 Jul 2005 00:00:00 GMT'); 
header('Pragma: no-cache'); 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
require_once('config7.php');
			
	$email=strtolower($_REQUEST['email']);						// Get email
	$password=$_REQUEST['password'];							// Get password
	$event=$_REQUEST['event'];									// Get event
	$show=$_REQUEST['show'];									// Get show
	
	if (!$email || !$show || !$event) {							// Not enough data
		if (!$email)											// If no email
			print("data");										// Show error 
		mysqli_close($link);									// Close session
		exit();													// Quit
		}
										
	$query="SELECT * FROM qusers WHERE email = '".$email."' AND showNum = '".$show."'";	// Look for existing one
	$result=mysqli_query($link, $query);						// Run query
	if ($result == false) {										// Bad query
		print(-2);												// Show error 
		mysqli_close($link);									// Close session
		exit();													// Quit
		}
	if (!mysqli_num_rows($result)) {							// If nothing there yet
		$query="INSERT INTO qusers (email, password, showNum, events ) VALUES ('";
		$query.=addEscapes($link,$email)."','";					// Email
		$query.=addEscapes($link,$password)."','";				// Password
		$query.=addEscapes($link,$show)."','";					// Show num
		$query.=addEscapes($link,$event)."')";					// Event
		$result=mysqli_query($link, $query);					// Run query
		if ($result == false)									// Bad save
			print("-3");										// Show error 
		else
			print("new:".mysqli_insert_id($link)."\n");			// Return ID of new user
		}
	else{														// We have one already
		$row=mysqli_fetch_assoc($result);						// Get row
		$oldpass=$row["password"];								// Get old password		
		if ($oldpass && ($password != $oldpass)) {				// Passwords don't match
			print("pass");										// Show error 
			if ($result)	mysqli_free_result($result);		// Free
			mysqli_close($link);								// Close session
			exit();												// Quit
			}
		$id=$row["id"];											// Get id
		if ($id != "") {										// If valid
			if (isSet($_REQUEST['replace'])) 					// If replacing field
				$query="UPDATE qusers SET events='".addEscapes($link,$event)."' WHERE id = '".$id."'";
			else 												// Adding to
				$query="UPDATE qusers SET events = CONCAT(events, '$event') WHERE id = '".$id."'";
			$result=mysqli_query($link, $query);				// Run query
			}
		if ($result == false)									// Bad update
			print("-4");										// Show error 
		else
			print("exist:".$id);								// Show id 
		if ($result)	mysqli_free_result($result);			// Free
		mysqli_close($link);									// Close session
		}

	function addEscapes($lnk, $str)								// ESCAPE ENTRIES
		{
			if (!$str)												// If nothing
				return $str;										// Quit
			$str=mysqli_real_escape_string($lnk, $str);				// Add slashes
			$str=str_replace("\r","", $str);						// No crs
			return $str;
		}
	
?>
	
