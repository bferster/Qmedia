<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); 
header('Expires: Sun, 01 Jul 2005 00:00:00 GMT'); 
header('Pragma: no-cache'); 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
require_once('config7.php');
			
	$email="";
	$show="";
	$password="";
	if (isSet($_GET['email'])) 									// If set
		$email=addslashes($_GET['email']);						// Get email
	if (isSet($_GET['show'])) 									// If set
		$show=addslashes($_GET['show']);						// Get show
	if (isSet($_GET['password'])) 								// If set
		$password=addslashes($_GET['password']);				// Get password
			
	$query="SELECT * FROM qusers WHERE ";						// Query start 
	if ($email)													// If a email spec'd
		$query.="email = '".strtolower($email)."'";				// Add email
	if ($show && $email)										// If both
		$query.=" AND";											// Add AND
	if ($show)													// If a show spec'd
		$query.=" showNum = '".$show."'";						// Look for a particular show
		$result=mysqli_query($link, $query);					// Run query
		if ($result == false) {									// Bad query
		print("-1\n");											// Return error
		mysqli_free_result($result);							// Free
		mysqli_close($link);									// Close session
		exit();													// Quit
		}
	print("<font face='sans-serif'>");							// Font
	while ($row=mysqli_fetch_assoc($result)) {					// Loop through rows
		print("The assessment result for <b>".$row["email"]."</b> in show <b>".$row["showNum"]."</b> is:<br>");	// Header
		print("<blockquote>");									// Indent
		$s=$row["events"];										// Get events
		$s=str_replace("\t","&nbsp;&nbsp;",$s);					// Tabs to spaces	
		$s=str_replace("\n","<br>",$s);							// CRs to BRs		
		print($s);												// Show events
		print("<br></blockquote>");								// BR
		}
	if (!mysqli_num_rows($result))								// If no rows
		print("No assessment results to show for $email");		// Say it
	print("</font>");											// Font
	mysqli_free_result($result);								// Free
	mysqli_close($link);										// Close session
?>
	
