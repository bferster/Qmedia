<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); 
header('Expires: Sun, 01 Jul 2005 00:00:00 GMT'); 
header('Pragma: no-cache'); 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
require_once('config7.php');
	
	$id=$_GET['id'];											// Get ID
	if (isSet($_GET['password'])) {								// Password
		$password=$_GET['password'];							// Get	
		$password=addEscapes($password);						// Escape 
		}	
	$id=addEscapes($id);										// ID
	$query="SELECT * FROM qshow WHERE id = '$id'";				// Make query
	$result=mysqli_query($link, $query);						// Run query
	if (($result == false) || (!mysqli_num_rows($result)))		// Error
		print("LoadShow({ \"qmfmsg\":\"error\"})");				// Send error msg
	else{														// Good result
		$row=mysqli_fetch_assoc($result);						// Get row
		if ($row["private"] && ($row["password"] != $password) && ($password != "*"))
			print("LoadShow({ \"qmfmsg\":\"private\"})");		// Send private msg
		else													// OK
			print($row["script"]);								// Send data
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