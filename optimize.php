<?php
	## UPGRADE 2.9.8 - OPTIMIZER
	
	# OPTIMIZE THE DATABASE
	/*
	session_start();
	if(!isset($_SESSION['done'])){
		session_register('done');
		$_SESSION['done'] = 0;
	}
	
	include( "database.php" );
	
	if($_SESSION['done'] == 0){
	
		$sql = "ALTER TABLE `carts` ADD INDEX ( `visitor_id` )";
		$results = mysql_query($sql,$db);
		
		$sql = "ALTER TABLE `carts` ADD INDEX ( `photo_id` )";
		$results = mysql_query($sql,$db);
		
		$sql = "ALTER TABLE `photo_galleries` ADD INDEX ( `nest_under` )";
		$results = mysql_query($sql,$db);
		
		$sql = "ALTER TABLE `photo_package` ADD INDEX ( `gallery_id` )";
		$results = mysql_query($sql,$db);
		
		$sql = "ALTER TABLE `uploaded_images` ADD INDEX ( `reference_id` )";
		$results = mysql_query($sql,$db);
		
		$sql = "ALTER TABLE `visitors` ADD INDEX ( `visitor_id` )";
		$results = mysql_query($sql,$db);
		
		echo "<font color=#ff0000 face=Verdana>Optimization Complete<br /><br />";
	} else {
		echo "You've already optimized.";	
	}

	$_SESSION['done'] = 1;
	*/
?>

