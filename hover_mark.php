<?PHP

	//DECLARE HEADER TYPE SO IT IS AN IMAGE
	Header("Content-type: image/jpeg");
	
	//INCLUDE FILES NEEDED TO GET SETTINGS AND WATERMARK
	include( "database.php" );
	include( "functions.php" );
	
	//GET PHOTO INFO
	$photo_result = mysql_query("SELECT filename FROM uploaded_images where id = '" . $_GET['i'] . "' order by original", $db);
	$photo = mysql_fetch_object($photo_result);
	
	//GET SETTINGS
	$setting_result = mysql_query("SELECT hover_size,hover_display_quality,photo_dir FROM settings where id = '1' limit 1", $db);
	$setting = mysql_fetch_object($setting_result);
	
	//GET WATERMARK IMAGE 
	$water_img = "./images/hover_mark.png";
	
	//LOAD THE QUALITY LEVEL & PHOTO PATH
	$quality = $setting->hover_display_quality;
	$stock_photo_path = "./" . $setting->photo_dir . "/";
	
	watermark($stock_photo_path . "s_" . $photo->filename,"test.jpg",$water_img,$quality,$setting->hover_size);
?>