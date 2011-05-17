<?php
		$db_name		= "photostore3";	// DATABASE NAME
		$db_username	= "root";	// DATABASE USERNAME
		$db_password	= "root";	// DATABASE PASSWORD
		$db_host		= "localhost";		// DATABASE HOST

		error_reporting(0);
		if(!$db = mysql_connect($db_host, $db_username, $db_password)){
			echo "<span style=\"font-family: verdana; font-size: 12px; color: #ff0000;\"><strong>PhotoStore has encountered a serious error:</strong><br />Could not connect to the database: " . mysql_error() . "</span>";
			exit;
			//or die('Could not connect to the database: ' . mysql_error());
		}
		if(!mysql_select_db($db_name)){
			// or die('Could not select database. Make sure your database name is correct in database.php');
			echo "<span style=\"font-family: verdana; font-size: 12px; color: #ff0000;\"><strong>PhotoStore has encountered a serious error:</strong><br />Could not select database. Make sure your database name is correct in database.php</span>";
			exit;
		}
		$query = "SELECT id FROM settings";
		if(!$result = mysql_query($query)){
			echo "<span style=\"font-family: verdana; font-size: 12px; color: #ff0000;\"><strong>PhotoStore has encountered a serious error:</strong><br />Could not select database tables. Make sure you've imported the database.sql file into your database.</span>";
			exit;
		}
		error_reporting(E_ALL & ~E_NOTICE);
		mysql_query('SET NAMES utf8');
?>
