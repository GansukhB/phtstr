<?php

	include( "database.php" );
	include( "config_public.php" );
	include( "functions.php" );
	
	$photo_result = mysql_query("SELECT filename, reference_id, added FROM uploaded_images where id = '$id' limit 1", $db);
	$photo = mysql_fetch_object($photo_result);
	
	$filename = strip_ext($photo->filename);												
	$sample_name = array($filename . ".mov", $filename . ".avi", $filename . ".mpg", $filename . ".flv", $filename . ".wmv");
	foreach($sample_name as $key => $value){
	if(is_file($sample_video_path . $sample_name[$key])){
	$sam_name = $sample_name[$key];
	$check_sam = $sample_video_path . $sam_name;
	$ext_sam = substr($sam_name, -3);
		}
	}
	$src = $sample_video_path . $sam_name;
	$display_name = "video_" . $photo->reference_id . "_" . $photo->added . "." . $ext_sam;
	if($ext_sam == "mpg"){
	header("Content-Type: video/mpeg ");
	} else {
	if($ext_sam == "mov"){
	header("Content-Type: video/quicktime ");
	} else {
	if($ext_sam == "avi"){
  header("Content-Type: video/msvideo ");
  } else {
  header("Content-Type: video/mpeg ");
			}
		}
	}
	header("Content-Disposition: attachment; filename=" .basename($display_name));
	readfile($check_sam); 
	
	# CLOSE DATABASE
	mysql_close();
		
?>
