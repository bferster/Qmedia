<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); 
header('Expires: Sun, 01 Jul 2005 00:00:00 GMT'); 
header('Pragma: no-cache'); 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
require_once('config.php');
			
	$password="";												
	$email="";												
	$title="";													
	$script="";												
	$private='0';												

	$email=$_GET['email'];										// Get email
	$password=$_GET['password'];								// Get password
	
	if (isSet($_GET['title'])) 									// If set
		$title=$_GET['title'];									// Get it
	if (isSet($_GET['script'])) 								// If set
		$script=$_GET['script'];								// Get it
	if (isSet($_GET['private'])) 								// If set
		$private=$_GET['private'];								// Get it
		
	$query="SELECT * FROM qshow WHERE email = '".$email."' AND version = '5'"; 	// Look existing one	
	$result=mysql_query($query);								// Query
	if ($result == false) {										// Bad query
		print("-1");											// Show error 
		mysql_close();											// Close session
		exit();													// Quit
		}
	if (mysql_numrows($result)) 								// If already exists
		print("-3");											// Show error 
	else{														// If not found, add it
		$query="INSERT INTO qshow (title, script, email, password, version, private) VALUES ('";
		$query.=addEscapes($title)."','";
		$query.=addEscapes($script)."','";
		$query.=addEscapes($email)."','";
		$query.=addEscapes($password)."','";
		$query.=addEscapes(5)."','";
		$query.=addEscapes($private)."')";
		$result=mysql_query($query);							// Add row
		if ($result == false)									// Bad save
			print("-2");										// Show error 
		else
			print(mysql_insert_id()."\n");						// Return ID of new resource
		}
	mysql_close();												// Close session

	
	function addEscapes($str)									// ESCAPE ENTRIES
	{
		if (!$str)												// If nothing
			return $str;										// Quit
		$str=mysql_real_escape_string($str);					// Add slashes
		$str=str_replace("\r","",$str);							// No crs
		return $str;
	}

?>
	
