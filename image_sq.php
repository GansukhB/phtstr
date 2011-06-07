<?php
  //INCLUDE FILES NEED TO GET INFO
	include( "database.php" );
	session_start();
	//DECLARE THIS AS AN IMAGE
	header("Content-type: image/jpeg");
	
	//GET PHOTO FILENAME
	$photo_result = mysql_query("SELECT filename FROM uploaded_images where id = '" . $_GET['src'] . "' limit 1", $db);
	$photo = mysql_fetch_object($photo_result);
	
	//GET SETTINGS
	$setting_result = mysql_query("SELECT thumb_width,thumb_display_quality,show_watermark_thumb,photo_dir FROM settings where id = '1' limit 1", $db);
	$setting = mysql_fetch_object($setting_result);
	
	//GET PHOTO PATH
	$stock_photo_path = "./" . $setting->photo_dir . "/";
	//$stock_photo_path = "../". "stock_photos1" . "/";
  
	//SECURITY CHECK FOR WATERMARK
	if($setting->show_watermark_thumb == 1){
		echo "watermarking is on, you can't bypass it sorry!";
		exit;
	}
	
	//THUMBNAIL QUALITY, SIZE, & FILENAME
	$default_size = $setting->thumb_width;
  
	$imgquality = $setting->thumb_display_quality;
	
  $src = $stock_photo_path . "i_" . $photo->filename;
  
  if($_GET['gal_size'] == 'large')
  {
    $src = $stock_photo_path . "m_" . $photo->filename;
    $default_size *= 2;
  }
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
	
 
	//CREATE THE IMAGE FOR DISPLAY
	$photoImage = ImageCreateFromJPEG($src);
   $side = min(imagesx($photoImage), imagesy($photoImage));
   //$side = 91;
	$dst_img = ImageCreateTrueColor($side, $side);
  
  if($width > $height)
  {
    $src_x = ($width - $height) ;
    $src_y = 0;
  }
  else {
    $src_x = 0;
    $src_y = ($height - $width) ;
  }
  
	//imagecopyresampled($dst_img, $photoImage, 0, 0, $src_x, $src_y, $side, $side, imagesx($photoImage), imagesy($photoImage));
  imagecopyresampled($dst_img, $photoImage, 0, 0, $src_x, $src_y, $side, $side, $side, $side);
  
  
	imagejpeg($dst_img,'', $imgquality);
	imagedestroy($photoImage);
	imagedestroy($dst_img);
	
	//CLOSE DATABASE
	mysql_close();

?>
