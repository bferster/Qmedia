<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); 
header('Expires: Sun, 01 Jul 2005 00:00:00 GMT'); 
header('Pragma: no-cache'); 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
require_once('config7.php');
			
	$query="SELECT * FROM qshow";								// Query 
	if (isSet($_GET['v'])) 		 								// If v set
		$query.=" WHERE version = '".$_GET['v']."'";			// Add ver
	$query.=" ORDER by date DESC";								// Sort by date
	$result=mysqli_query($link, $query);						// Run query
	if ($result == false) {										// Bad query
		print("Error getting projects");						// Return error
		exit();													// Quit
		}
	$num=min(mysqli_num_rows($result),200);						// Get num rows, cap at max
	$pass=$_GET['pass'];										// Password
	print("<font face='sans-serif'>");							// Font
	print("<b>The current 200 projects</b>:<br>");				// Header
	while ($row=mysqli_fetch_assoc($result)) {					// Loop through rows
		print("<blockquote>");									// Indent
		$d=$row['date'];										// Get date string
		$d=strtotime("-4 hours",strtotime($d));					// Get as time
		$d=date("m/d - g:ia",$d);								// Format
		print($d." &nbsp;");									// Date
		if ($row["version"] == 1)								// If MapScholar
			print("<a href='//www.viseyes.org/mapscholar/?".$row["id"]."' target='blank'>M = ".$row["id"]."</a> &nbsp;");	// Id
		else if ($row["version"] == 4)							// If VisualEyes 5
			print("<a href='//www.viseyes.org/visualeyes/?".$row["id"]."' target='blank'>V = ".$row["id"]."</a> &nbsp;");	// Id
		else if ($row["version"] == 5)							// If Folio
			print("<a href='//www.viseyes.org/folio/?".$row["id"]."' target='blank'>F = ".$row["id"]."</a>  &nbsp;");		// Id
		else 													// Qmedia
			print("<a href='//www.qmediaplayer.com/show.htm?".$row["id"]."' target='blank'>Q = ".$row["id"]."</a> &nbsp;");	// Id
		print($row["email"]." | ");								// Email
		print($row["title"]);									// Title
		if ($pass)												// If wanting password
			print(" | ".$row["password"]);						// Password
		print("<br></blockquote>");								// BR
		}
	print("</font>");											// Font
	mysqli_free_result($result);								// Free
	mysqli_close($link);										// Close session
?>
	
