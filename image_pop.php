<?php
	//INCLUDE FILES NEEDED TO GRAB INFO
	include( "database.php" );
	
	//GET SETTINGS
	$setting_result = mysql_query("SELECT hover_size,hover_display_quality,photo_dir FROM settings where id = '1' limit 1", $db);
	$setting = mysql_fetch_object($setting_result);
	
	//GET PHOTO INFO
	$photo_result = mysql_query("SELECT filename FROM uploaded_images where id = '" . $_GET['src'] . "' order by original limit 1", $db);
	$photo = mysql_fetch_object($photo_result);
	
	
	$default_size = $setting->hover_size;
	
	//SETTINGS FOR THE IMAGE QUALITY
	$imgquality = $setting->hover_display_quality;
	
	//SETTING FOR LOCATION AND FILE PATH
	$stock_photo_path = "./" . $setting->photo_dir . "/";
	$src = $stock_photo_path . "s_" . $photo->filename;
	
	
	//GET THE SIZE OF THE PHOTO
	$imageInfo = getimagesize($src);
	
	//FIND THE SCALE RATIOS		
	if($imageInfo[0] >= $imageInfo[1]){
		if($imageInfo[0] > $default_size){
			$width = $default_size;
		} else {
			$width = $imageInfo[0];
		}
		$ratio = $width/$imageInfo[0];
		$height = $imageInfo[1] * $ratio;				
	} else {
		if($imageInfo[1] > $default_size){
			$height = $default_size;	
		} else {
			$height = $imageInfo[1];	
		}
		$ratio = $height/$imageInfo[1];
		$width = $imageInfo[0] * $ratio;						
	}
	
	$photoImage = ImageCreateFromJPEG($src);
	$dst_img = ImageCreateTrueColor($width, $height);												
	imagecopyresampled($dst_img, $photoImage, 0, 0, 0, 0, $width, $height, imagesx($photoImage), imagesy($photoImage));
	
  $watermark = "./images/watermark";
  
  if($width < $height)
  {
    $watermark .= "_v";
  }
    $watermark .= ".png"; 
  $logoImage = ImageCreateFromPNG($watermark);
  
	imagecopyresampled($dst_img, $logoImage, 0, 0, 0, 0, $width, $height, imagesx($logoImage), imagesy($logoImage));
  
	imagejpeg($dst_img,'', $imgquality);
	imagedestroy($photoImage); 
	imagedestroy($dst_img);
	
	# CLOSE DATABASE
	mysql_close();
		
?>
