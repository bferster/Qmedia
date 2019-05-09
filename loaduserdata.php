<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); 
header('Expires: Sun, 01 Jul 2005 00:00:00 GMT'); 
header('Pragma: no-cache'); 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
require_once('config7.php');
	
	$email=addEscapes(strtolower($_GET['email']));				// Get email
	$show=addEscapes($_GET['show']);							// Get show
										
	$query="SELECT * FROM qusers WHERE email = '".$email."' AND showNum = '".$show."'";	// Look for data
	$result=mysqli_query($link, $query);						// Run query
	if (mysqli_num_rows($result)) {								// If no rows
		$row=mysqli_fetch_assoc($result);						// Get row
		$s=$row["events"];										// Get events field
		$s=str_replace("\r\n","\n",$s);							// No crlf
		$s=str_replace("\n\r","\n",$s);							// No lfcr
		$s=str_replace("\n","\\n",$s);							// Escape
		$s=str_replace("\t","\\t",$s);							// Escape
		print("LoadUserData({ \"data\":\"$s\"})");				// Send JSONP
		}
	mysqli_free_result($result);								// Free
	mysqli_close($link);										// Close session
	
	function addEscapes($str)									// ESCAPE ENTRIES
	{
		if (!$str)												// If nothing
			return $str;										// Quit
		$str=addslashes($str);									// Add slashes
		$str=str_replace("\r","",$str);							// No crs
		return $str;
	}
	
?>