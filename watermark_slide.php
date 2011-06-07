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
$setting_result = mysql_query("SELECT show_watermark,sample_display_quality,sample_width,photo_dir FROM settings where id = '1' limit 1", $db); 
$setting = mysql_fetch_object($setting_result); 

//GET WATERMARK 
if($setting->show_watermark == "1"){ 
	$water_img = "./images/watermark"; 
} else { 
	$water_img = "./images/watermark_off.png"; 
} 

//QUALITY LEVEL AND PHOTO PATH 
$quality = $setting->sample_display_quality; 
$stock_photo_path = "./" . $setting->photo_dir . "/"; 

//OVERLAY THE WATERMARK - EXAMPLE s_E283261A4863BE.JPG + WATERMARK.PNG - MAKE SURE TO LEAVE THE "TEST.JPG" IN THE CODE BELOW! 
watermark($stock_photo_path . "" . $photo->filename,"test.jpg",$water_img,$quality,695); 
?>
