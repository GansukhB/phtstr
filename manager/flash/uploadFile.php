<?php
	/*
	session_start();
		if($_SESSION['access_type'] != "mgr"){ 
			echo "Operation cannot be performed!<br>YOU NEED TO LOG IN!";
			exit; 
			}
	*/

include("../../database.php");
$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
$setting = mysql_fetch_object($settings_result);

if(md5($setting->access_id) != $_GET['pass']){ 
	echo "Operation cannot be performed!<BR>YOU NEED TO LOG IN!";
	exit; 
	}
	

//path to storage
$storage = '../../ftp/';
//path name of file for storage
$uploadfile = "$storage" . basename( $_FILES['Filedata']['name'] );
//if the file is moved successfully
if ( move_uploaded_file( $_FILES['Filedata']['tmp_name'] , $uploadfile ) ) {
  echo( '1 ' . $_FILES['Filedata']['name']);
 //file failed to move
}else{
  echo( '0');
}
?>
