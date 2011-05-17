<?
		include("database.php");
		include("functions.php");
		include("config_public.php");
		
		$visitor_result = mysql_query("SELECT visitor_id,status,done FROM visitors where order_num = '" . $_GET['order'] . "'", $db);
		$visitor_rows = mysql_num_rows($visitor_result);
		$visitor = mysql_fetch_object($visitor_result);

		$photo_result = mysql_query("SELECT reference_id,filename,added FROM uploaded_images where id = '" . $_GET['pid'] . "'", $db);
		$photo = mysql_fetch_object($photo_result);
				
		//Create new name for the photo file or zip file so when visitor is
		//ask to save it will display a different name than what the real photo is
		$photo_name = "photo_" . $photo->reference_id . "_" . $photo->added . ".jpg";
		$photo_zip = "photo_" . $photo->reference_id . "_" . $photo->added . ".zip";
		
		$filename = strip_ext($photo->filename);
		$check = "./files/" . $filename . ".zip";
		$dispo = $filename . ".zip";	

		$stock_name = array($filename . ".mov", $filename . ".avi", $filename . ".mpg", $filename . ".flv", $filename . ".wmv");
		foreach($stock_name as $key => $value){
			if(is_file($stock_video_path . $stock_name[$key])){
				$video_name = $stock_name[$key];
				$check_sto = $stock_video_path . $video_name;
				$ext_sto = substr($video_name, -3);
			}
		}
	 	$src_video = $stock_video_path . $video_name;
		$hide_video_name = "video_" . $photo->reference_id . "_" . $photo->added . "." . $ext_sto;

		if($visitor->status == 0 or $visitor_rows == 0 or $visitor->done == 0){
			echo $download_file_bad;		
		} else {
		
			$cart_result = mysql_query("SELECT ptype FROM carts where visitor_id = '$visitor->visitor_id' and photo_id = '" . $_GET['pid'] . "'", $db);
			$cart_rows = mysql_num_rows($cart_result);
			$cart = mysql_fetch_object($cart_result);
			
			if($cart_rows == 0){
				echo $download_file_bad;
			} else {
		if($cart->ptype == "s"){
			if($_GET['sid'] != ""){
				$sizes_result = mysql_query("SELECT size FROM sizes where id = '" . $_GET['sid'] . "'", $db);
				$sizes = mysql_fetch_object($sizes_result);
			
				$pg1_result = mysql_query("SELECT filename FROM uploaded_images where id = '" . $_GET['pid'] . "'", $db);
				$pg1 = mysql_fetch_object($pg1_result);
				
				# GET THE SIZE OF THE PHOTO
				$path = "./temp/";
				$src = $stock_photo_path . $pg1->filename;
				$imageInfo = getimagesize($src);
	      $default_size = $sizes->size;
	      $imgquality = 100;

				# FIND THE SCALE RATIOS		
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
			imagejpeg($dst_img, $path . $photo_name, $imgquality);
			imagedestroy($photoImage); 
			imagedestroy($dst_img);
			header("Content-Type: image/jpeg "); 
			header("Content-Disposition: attachment; filename=" .basename($photo_name)); 
			readfile($path . $photo_name);
			unlink($path . $photo_name);
			exit;
				}
			} else {
		if($cart->ptype == "d"){
				if(ini_get('zlib.output_compression'))
				ini_set('zlib.output_compression', 'Off');
				
		if(file_exists($check_sto)){
				if($ext_sto == "mpg"){
			header("Content-Type: video/mpeg ");
				} else {
					if($ext_sto == "mov"){
			header("Content-Type: video/quicktime ");
						} else {
							if($ext_sto == "avi"){
  		header("Content-Type: video/msvideo ");
  							} else {
  		header("Content-Type: video/mpeg ");
			}
		}
	}
	header("Content-Disposition: attachment; filename=" .basename($hide_video_name));
	readfile($check_sto);
	} else {
		if(file_exists($check)){
			header("Content-Type: application/zip ");
			header("Content-Disposition: attachment; filename=" .basename($photo_zip));
			readfile($check); 
			exit;
		} else {
				header("Pragma: public"); // required
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: private",false); // required for certain browsers
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: ".filesize($stock_photo_path . $photo->filename));			
				header("Content-Type: image/jpeg "); 
				header("Content-Disposition: attachment; filename=" .basename($photo_name)); 
				readfile($stock_photo_path . $photo->filename); 
				exit;
				}
			}
		}
	}
			}
		}
?> 
