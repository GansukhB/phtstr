<?php
	session_start();
	
	// COPY ACTIONS UPDATED 3.4.04
	
	include( "config_mgr.php" );
	
	$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
	
	//$file_path = "../uploaded_files/";
	//$image_path = "../uploaded_images/";
	
	$result_file = mysql_query("SELECT * FROM uploaded_images", $db);
	while($rs_file = mysql_fetch_object($result_file)) {
		$sql = "INSERT INTO photo_package (title,keywords,active,added,cart_count) VALUES ('$rs_file->photo_title','$rs_file->keywords','1','$rs_file->added','$$rs_file->cart_count')";
		$result = mysql_query($sql);
		
		$result_last = mysql_query("SELECT * FROM photo_package order by id desc", $db);
		$rs_last = mysql_fetch_object($result_last);
		
		$sql2 = "UPDATE uploaded_images SET reference_id='$rs_last->id',reference='photo_package' WHERE id = '$rs_file->id'";
		$result2 = mysql_query($sql2);
		
		
	}

?>