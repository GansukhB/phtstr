<?PHP 

//DECLARE HEADER TYPE SO IT IS AN IMAGE 
Header("Content-type: image/jpeg"); 

//INCLUDE FILES NEEDED TO GET SETTINGS AND WATERMARK 
include( "database.php" ); 
include( "functions.php" ); 

//GET PHOTO INFO 
$photo_result = mysql_query("SELECT filename FROM uploaded_images where id = '" . $_GET['i'] . "' order by original", $db); 
$photo = mysql_fetch_object($photo_result); 


$width = $_GET['width'];
$height = $_GET['height'];

//GET SETTINGS 
$setting_result = mysql_query("SELECT show_watermark,sample_display_quality,sample_width,photo_dir FROM settings where id = '1' limit 1", $db); 
$setting = mysql_fetch_object($setting_result); 

$stock_photo_path = "./" . $setting->photo_dir . "/"; 

$srcfilename = $stock_photo_path . "m_" . $photo->filename;

$src_img = ImageCreateFromJPEG($srcfilename);

      $dst_img = ImageCreateTrueColor($width, $height);

      $startx = ($width - imagesx($src_img))/2;
      $starty = ($height - imagesy($src_img))/2;
      
      imagecopyresampled($dst_img, $src_img, $startx, $starty, 0, 0, imagesx($src_img),imagesy($src_img), imagesx($src_img), imagesy($src_img));

imagejpeg($dst_img,"",100)
//imagejpeg($src_img,"",100)
?>
