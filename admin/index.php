<?php
	error_reporting(0);
	
	include( "../database.php" );
	
	if($db_error){
		echo "<body bgcolor=\"#13387E\"><table><tr><td align=\"left\"><img src=\"images/mgr_check_loop_forever.gif\" align=\"left\" valign=\"absmiddle\"><font color=\"#FFE400\" style=\"font-family: verdana; font-size: 12px;\">&nbsp;<b>Database error: </b>" . $db_error . " | Contact your host for more information.</td></tr></table></body>";
		exit;
	}
	
	$settings_result = mysql_query("SELECT access_id,status FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
	
	//if($setting->access_id == "0" or $setting->status != MD5("1")){ 
	/*if($setting->access_id == "0"){
		header("location: activate.php");
		exit;
	}
	else{*/
		// REDIRECT TO LOGIN PAGE
		header("location: login.php");
		// IF REDIRECT FAILS PRINT LINK TO LOGIN PAGE
		echo "<a href=\"login.php\"><font face=\"arial\" size=\"2\" color=\"#0065FC\">Click here to log into your site manager.</font></a>";
		exit;
	//}
?>