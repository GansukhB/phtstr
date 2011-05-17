<?PHP

	//DECLARE HEADER TYPE SO IT IS AN IMAGE
	Header("Content-type: image/jpeg");
	
	//INCLUDE FILES NEEDED TO GET SETTINGS AND WATERMARK
	include( "database.php" );
	include( "functions.php" );
	
	//GET PHOTO INFO
	$photo_result = mysql_query("SELECT filename FROM uploaded_images where id = '" . $_GET['i'] . "'", $db);
	$photo = mysql_fetch_object($photo_result);
	
	//GET SETTINGS
	$setting_result = mysql_query("SELECT thumb_width,thumb_display_quality,photo_dir FROM settings where id = '1' limit 1", $db);
	$setting = mysql_fetch_object($setting_result);
	
	//GET WATERMARK IMAGE 
	$water_img = "./images/thumb_mark.png";
	
	//LOAD THE QUALITY LEVEL TO DISPLAY THE THUMB AT
	$quality = $setting->thumb_display_quality;
	
	//GET PHOTO PATH
	$stock_photo_path = "./" . $setting->photo_dir . "/";
	
	//WATERMARK AND SHOW
	watermark($stock_photo_path . "i_" . $photo->filename,"test.jpg",$water_img,$quality,$setting->thumb_width);
?>