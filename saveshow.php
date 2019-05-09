<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); 
header('Expires: Sun, 01 Jul 2005 00:00:00 GMT'); 
header('Pragma: no-cache'); 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
require_once('config7.php');
			
	$id="";
	$password="";												
	$email="";												
	$title="";													
	$script="";												
	$private='0';												
	$deleted='0';
	$ver=0;												

	$email=$_REQUEST['email'];									// Get email
	$password=$_REQUEST['password'];							// Get password
	
	if (isSet($_REQUEST['id'])) 								// If set
		$id=$_REQUEST['id'];									// Get it
	if (isSet($_REQUEST['title'])) 								// If setzzzz
		$title=$_REQUEST['title'];								// Get it
	if (isSet($_REQUEST['script'])) 							// If set
		$script=$_REQUEST['script'];							// Get it
	if (isSet($_REQUEST['private'])) 							// If set
		$private=$_REQUEST['private'];							// Get it
	if (isSet($_REQUEST['deleted'])) 							// If set
		$deleted=$_REQUEST['deleted'];							// Get it
	if (isSet($_REQUEST['ver'])) 								// If set
		$ver=$_REQUEST['ver'];									// Get it
		
	$id=addEscapes($id);										// Escape id
	$query="SELECT * FROM qshow WHERE id = '".$id."'"; 			// Look existing one	
	$result=mysqli_query($link, $query);						// Run query
	if ($result == false) {										// Bad query
		print("-1");											// Show error 
		mysqli_free_result($result);							// Free
		mysqli_close($link);									// Close session
		exit();													// Quit
		}
	if (!mysqli_num_rows($result)) {							// No result
		$query="INSERT INTO qshow (title, script, email, password, version, private) VALUES ('";
		$query.=addEscapes($title)."','";
		$query.=addEscapes($script)."','";
		$query.=addEscapes($email)."','";
		$query.=addEscapes($password)."','";
		$query.=addEscapes($ver)."','";
		$query.=addEscapes($private)."')";
		$result=mysqli_query($link, $query);					// Run query
		if ($result == false)									// Bad save
			print("-2");										// Show error 
		else
			print( mysqli_insert_id($link)."\n");				// Return ID of new resource
		}
	else{														// We have one already
		$row=mysqli_fetch_assoc($result);						// Get row		
		$oldpass=$row["password"]);								// Get old password		
		if ($oldpass && ($password != $oldpass)) {				// Passwords don't match
			print("-3");										// Show error 
			mysqli_free_result($result);						// Free
			mysqli_close($link);								// Close session
			exit();												// Quit
			}
		if (!isSet($_REQUEST['title'])) 						// If not set
			$title=$row["title"];								// Get from POST
		if (!isSet($_REQUEST['script'])) 						// If not set
			$script=$row["script"];								// Get from POST
		if (!isSet($_REQUEST['private'])) 						// If not set
			$private=$row["private"];							// Get from POST
		if (!isSet($_REQUEST['deleted'])) 						// If not set
			$deleted=$row["deleted"];							// Get from POST
		
		$id=$row["id"]);										// Get id
		$id=addEscapes($id);									// Escape id
		if ($id != "") {										// If valid
			$query="UPDATE qshow SET title='".addEscapes($title)."' WHERE id = '".$id."'";
			$result=mysqli_query($link, $query);				// Run query
			$query="UPDATE qshow SET script='".addEscapes($script)."' WHERE id = '".$id."'";
			$result=mysqli_query($link, $query);				// Run query
			$query="UPDATE qshow SET private='".addEscapes($private)."' WHERE id = '".$id."'";
			$result=mysqli_query($link, $query);				// Run query
			$query="UPDATE qshow SET deleted='".addEscapes($deleted)."' WHERE id = '".$id."'";
			$result=mysqli_query($link, $query);				// Run query
			$query="UPDATE qshow SET date='".date("Y-m-d H:i:s")."' WHERE id = '".$id."'";
			$result=mysqli_query($link, $query);				// Run query
			}
		if ($result == false)									// Bad update
			print("-4");										// Show error 
		else
			print($id);											// Show id 
			}													// End if valid
		mysqli_free_result($result);							// Free
		mysqli_close($link);									// Close session
		}	
	
	function addEscapes($str)									// ESCAPE ENTRIES
	{
		if (!$str)												// If nothing
			return $str;										// Quit
		$str=mysqli_real_escape_string($link,$str);				// Add slashes
		$str=str_replace("\r","",$str);							// No crs
		return $str;
	}

?>
	
