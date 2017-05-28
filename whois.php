<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); 
header('Expires: Sun, 01 Jul 2005 00:00:00 GMT'); 
header('Pragma: no-cache'); 
require_once('config.php');
			
	$query="SELECT * FROM qshow ORDER by date DESC";			// Query 
	$result=mysql_query($query);								// Query
	if ($result == false) {										// Bad query
		print("Error getting projects");						// Return error
		exit();													// Quit
		}
	$num=min(mysql_numrows($result),100);						// Get num rows, cap at 100
	$pass=$_GET['pass'];									// Password
	
	print("<font face='sans-serif'>");							// Font
	print("<b>The current 100 projects</b>:<br>");				// Header
	for ($i=0;$i<$num;++$i) {									// For each record
		print("<blockquote>");									// Indent
		print(mysql_result($result,$i,"date")." | ");			// Date
		print(mysql_result($result,$i,"email")." | ");			// Email
		if (mysql_result($result,$i,"version") == 1)			// If MapScholar
			print("<a href='//www.viseyes.org/mapscholar/?".mysql_result($result,$i,"id")."'>M = ".mysql_result($result,$i,"id")."</a> | ");	// Id
		else if (mysql_result($result,$i,"version") == 4)		// If VisualEyes 5
			print("<a href='//www.viseyes.org/visualeyes/?".mysql_result($result,$i,"id")."'>V = ".mysql_result($result,$i,"id")."</a> | ");	// Id
		else if (mysql_result($result,$i,"version") == 5)		// If Folio
			print("<a href='//www.viseyes.org/folio/?".mysql_result($result,$i,"id")."'>F = ".mysql_result($result,$i,"id")."</a> | ");			// Id
		else 													// Qmedia
			print("<a href='//www.qmediaplayer.com/show.htm?".mysql_result($result,$i,"id")."'>Q = ".mysql_result($result,$i,"id")."</a> | ");	// Id
		print(mysql_result($result,$i,"title"));				// Title
		if ($pass)												// If wanting password
			print(" | ".mysql_result($result,$i,"password"));	// Password
		print("<br></blockquote>");								// BR
		}
	print("</font>");											// Font
	mysql_close();												// Close session
?>
	
