<?php
	session_start();
	include( "check_login_status.php" );
	
	// FTP UPLOAD ACTIONS - UPDATED 11.5.04
	
	include( "config_mgr.php" );
	
	if($_SESSION['access_type'] != "mgr"){ echo "Operation cannot be performed in demo mode"; exit; }
	
	$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
	
	if($_SESSION['page'] == ""){
	session_register("page");
	$_SESSION['page'] = $nav;
	}
	
	//$file_path = "../uploaded_files/";
	//$image_path = "../uploaded_images/";
	
	switch($_GET['pmode']){

/*-----------------------------------------------------------------------------------------------------------------------*/
/*                                                PLUGIN ACTIONS                                                    */
/*-----------------------------------------------------------------------------------------------------------------------*/
	/* FTP UPLOAD */	
		case "ftp_upload":
		
			header("Content-type: text/html\n\n");
// Unset previous sessions before processing--------------
     		 unset($_SESSION['new_image_name']);
      		unset($_SESSION['left']);
      
// Creat Session of Form----------------------------------

		if(!$_SESSION['title']){
			session_register("title");
			$_SESSION['title'] = $_POST['title'];
		}
		if(!$_SESSION['gallery_id']){
			session_register("gallery_id");
			$_SESSION['gallery_id'] = $_POST['gallery_id'];
		}
// IF OTHER GALLERIES ARE LISTED GROUP THEM HERE----------
			if($_SESSION['other_galleries2'] != ""){
				$other_galleries2 = $_SESSION['other_galleries2'];
			} else {
			if($_POST['other_galleries']){
				//$other_galleries2 = implode(",", $_POST['other_galleries']);
				$other_galleries2 = "," . implode(",", $_POST['other_galleries']) . ",";
			}
		}
// Back to sessions---------------------------------------
				if(!$_SESSION['other_galleries2']){
				session_register("other_galleries2");
				$_SESSION['other_galleries2'] = $other_galleries2;
			}
				if(!$_SESSION['keywords']){
				session_register("keywords");
				$_SESSION['keywords'] = $_POST['keywords'];
			}
				if(!$_SESSION['description']){
				session_register("description");
				$_SESSION['description'] = $_POST['description'];
			}
				if(!$_SESSION['quality1']){
				session_register("quality1");
				$_SESSION['quality1'] = $_POST['quality1'];
			}
				if(!$_SESSION['quality_order1']){
				session_register("quality_order1");
				$_SESSION['quality_order1'] = $_POST['quality_order1'];
			}
			if(!$_SESSION['featured']){
				session_register("featured");
				$_SESSION['featured'] = $_POST['featured'];
			}
			if(!$_SESSION['active']){
				session_register("active");
				$_SESSION['active'] = $_POST['active'];
			}
				if(!$_SESSION['act_download']){
				session_register("act_download");
				$_SESSION['act_download'] = $_POST['act_download'];
			}
				if(!$_SESSION['price']){
				session_register("price");
				if($_POST['price'] == "0"){
					$_SESSION['price'] = "0.00";
				} else {
				$_SESSION['price'] = $_POST['price'];
			}
		}
				if(!$_SESSION['price_contact']){
				session_register("price_contact");
				$_SESSION['price_contact'] = $_POST['price_contact'];
			}
				if(!$_SESSION['photographer']){
				session_register("photographer");
				$_SESSION['photographer'] = $_POST['photographer'];
			}
			if(!$_SESSION['all_prints']){
				session_register("all_prints");
				$_SESSION['all_prints'] = $_POST['all_prints'];
			}
			if(!$_SESSION['all_sizes']){
				session_register("all_sizes");
				$_SESSION['all_sizes'] = $_POST['all_sizes'];
			}
				if(!$_SESSION['aprofile']){
				session_register("aprofile");
				$_SESSION['aprofile'] = $_POST['aprofile'];
			}
			
			
			// TURN OFF ERROR REPORTING
			error_reporting(E_ALL & ~E_NOTICE);
			// TRY TO UP THE MEMORY LIMIT
			ini_set(memory_limit,"512M");
			
			$reference = "photo_package";
			$ftp_path = realpath("../ftp/");
			
			$chmod = substr(sprintf('%o', fileperms('../ftp')), -4);
			if($chmod != "0777"){
				echo "Your FTP directory doesn't have sufficient premission to delete the images as they are added.<br />";
				echo "Make sure you set the CHMOD to 777 on the folder (read, write, and execute), or contact support for help.";
				exit;
			} else {
			
			if(ini_get("memory_limit")){
				$memory_limit = ini_get("memory_limit");
			} else {
				$memory_limit = 256;
			}
			
			// FIND THE FILES THAT ARE LEFT IN THE FTP FOLDER
			if ($handle = opendir($ftp_path)){
				$count_check = 0;
				while (false !== ($file = readdir($handle))){
					if(is_file("../ftp/" . $file)){
						$count_check++;
						$imgtype = getimagesize("../ftp/" . $file);
						
						if($imgtype[2] == 2){
							$mfs = figure_memory_needed("../ftp/" . $file);
							if($mfs < $memory_limit){
								$img_files[]  = $file;
							}
							//echo $file . "<br>";
						} else {
							//echo $file  . " is not a supported image file<br>";
						}
					}
				}
				if($count_check > 1){
					natcasesort($img_files);
				}
				closedir($handle);
			}
			
			$left_over = count($img_files);
			if(!$_SESSION['start']){
			session_register("start");
			$_SESSION['start'] = $left_over;
		}
			session_register("left");
			$_SESSION['left'] = $left_over;
			
			// LIST THE PRODUCTS TO ATTACH
			if($_SESSION['prod'] != ""){
				$prod = $_SESSION['prod'];
			} else {
			if($_POST['prod']){
				foreach($_POST['prod'] as $value){
					$prod2 = $prod2 . "," . $value;
				}
			}
			$prod = $prod2;
			session_register("prod");
			$_SESSION['prod'] = $prod;
		}
			// LIST THE SIZES TO ATTACH
			if($_SESSION['sizes'] != ""){
				$sizes = $_SESSION['sizes'];
			} else {
			if($_POST['sizes']){
				foreach($_POST['sizes'] as $value){
					$sizes2 = $sizes2 . "," . $value;
				}
			}
			$sizes = $sizes2;
			session_register("sizes");
			$_SESSION['sizes'] = $sizes;
		}
			
				if($_SESSION['title'] != ""){
					$title2 = $_SESSION['title'];
				} else {
					if($_POST['title']){
					$title2 = $_POST['title'];
				}
			}
				if($_SESSION['gallery_id'] != ""){
					$gallery_id2 = $_SESSION['gallery_id'];
				} else {
					if($_POST['gallery_id']){
					$gallery_id2 = $_POST['gallery_id'];
				}
			}
				if($_SESSION['keywords'] != ""){
					$keywords2 = $_SESSION['keywords'];
				} else {
					if($_POST['keywords']){
					$keywords2 = $_POST['keywords'];
				}
			}
				if($_SESSION['description'] != ""){
					$description2 = $_SESSION['description'];
				} else {
					if($_POST['description']){
					$description2 = $_POST['description'];
				}
			}
				if($_SESSION['photographer'] != ""){
					$photographer2 = $_SESSION['photographer'];
				} else {
					if($_POST['photographer']){
					$photographer2 = $_POST['photographer'];
				}
			}
				if($_SESSION['all_prints'] != ""){
					$all_prints2 = $_SESSION['all_prints'];
				} else {
					if($_POST['all_prints']){
					$all_prints2 = $_POST['all_prints'];
				}
			}
				if($_SESSION['all_sizes'] != ""){
					$all_sizes2 = $_SESSION['all_sizes'];
				} else {
					if($_POST['all_sizes']){
					$all_sizes2 = $_POST['all_sizes'];
				}
			}
				if($_SESSION['featured'] != ""){
					$featured2 = $_SESSION['featured'];
				} else {
					if($_POST['featured']){
					$featured2 = $_POST['featured'];
				}
			}
				if($_SESSION['active'] != ""){
					$active2 = $_SESSION['active'];
				} else {
					if($_POST['active']){
					$active2 = $_POST['active'];
				}
			}
				if($_SESSION['act_download'] != ""){
					$act_download2 = $_SESSION['act_download'];
				} else {
					if($_POST['act_download']){
					$act_download2 = $_POST['act_download'];
				}
			}
				if($_SESSION['quality1'] != ""){
					$quality1 = $_SESSION['quality1'];
				} else {
					if($_POST['quality1']){
				$quality1 = $_POST['quality1'];
				}
			}
				if($_SESSION['quality_order1'] != ""){
					$quality_order1 = $_SESSION['quality_order1'];
				} else {
					if($_POST['quality_order1']){
				$quality_order1 = $_POST['quality_order1'];
				}
			}
			  if($_SESSION['price'] != ""){
			 		$price1 = $_SESSION['price'];
				} else {
					if($_POST['price']){
					$price1 = $_POST['price'];
				}
			}
			  if($_SESSION['price_contact'] != ""){
					$price_contact1 = $_SESSION['price_contact'];
				} else {
				if($_POST['price_contact']){
					$price_contact1 = $_POST['price_contact'];
				}
			}
				
			// START PROCESSING THE PHOTOS
			if($left_over > 0){
			$x = 1;
			$i = 0;
			while($i < $x){		
				$image = "../ftp/" . $img_files[$i];
				
				// IPTC INFORMATION
				if($image != ""){
					# GRAB IPTC INFO
					if(function_exists(get_iptc_data)){
						get_iptc_data($image);
						# ADD IPTC INFO TO OTHER INFO	
						if($iptc_keywords){
							$keywords1= addslashes($iptc_keywords);
						}
						if($iptc_description){
							$description1= addslashes($iptc_description);
						}
						if($iptc_title){
							$title1= addslashes($iptc_title);
						}
					}
				}
				
				if($title1 != ""){
				$title_new = $title1 . $title2;
				} else {
				$title_new = $title2;
				}
				if($keywords1 != ""){
				$keywords_new = $keywords1 . $keywords2;
				} else {
				$keywords_new = $keywords2;
				}
				if($description1 != ""){
				$description_new = $description1 . $description2;
				} else {
				$description_new = $description2;
				}
				
				trim($title_new);
				trim($keywords_new);
				trim($description_new);
				
				
				# MAGIC QUOTES FIX
				
					if(!get_magic_quotes_gpc()){
						$title_new = mysql_real_escape_string($title_new);
						$gallery_id2 = mysql_real_escape_string($gallery_id2);
						$keywords_new = mysql_real_escape_string($keywords_new);
						$added1 = mysql_real_escape_string($added1);
						$description_new = mysql_real_escape_string($description_new);
						$photographer2 = mysql_real_escape_string($photographer2);
						$prod = mysql_real_escape_string($prod);
						$sizes = mysql_real_escape_string($sizes);
						$all_prints2 = mysql_real_escape_string($all_prints2);
						$all_sizes2 = mysql_real_escape_string($all_sizes2);
						$other_galleries2 = mysql_real_escape_string($other_galleries2);
						$featured2 = mysql_real_escape_string($featured2);
						$active2 = mysql_real_escape_string($active2);
						$act_download2 = mysql_real_escape_string($act_download2);
					}	
					
					//ADDED IN PS350 TO CLEANUP DATA ENTRY
					$title_new = cleanup($title_new);
					$keywords_new = cleanup($keywords_new);
					$description_new = cleanup($description_new);
					$price1 = price_cleanup($price1);
					
				// ADD A RECORD TO THE DATABASE FOR THIS PHOTO
				$added1 = date("Ymd");
				$sql = "INSERT INTO photo_package (title,gallery_id,keywords,active,featured,added,description,photographer,prod,update_29,all_prints,other_galleries,act_download,sizes,all_sizes) VALUES ('$title_new','$gallery_id2','$keywords_new','$active2','$featured2','$added1','$description_new','$photographer2','$prod','1','$all_prints2','$other_galleries2','$act_download2','$sizes','$all_sizes2')";
				$result = mysql_query($sql);
				
				// GET THE LAST ID THAT WAS ADDED
				$last_result = mysql_query("SELECT id FROM photo_package order by id desc", $db);
				$last = mysql_fetch_object($last_result);
				$last_id = $last->id;
				
				$image = "../ftp/" . $img_files[$i];
				
				// SETTINGS
				$gd_gif_support = "on";
				$from_path = "../ftp/";
				$path = $stock_photo_path_manager;
				$thumb_quality = $setting->upload_thumb_quality;
				$sample_quality = $setting->upload_sample_quality;
				$large_quality = $setting->upload_large_quality;
				$quality = 100;
				
				if($image != ""){
					// RANAME THE FILE
					
					// REPLACE BAD CHARACTERS				
					$illegal_char = array(1 => "?",",","'","\'","%20","%");
					$x_count = count($illegal_char);
					$i_count = 1;
					
					$new_image_name = $img_files[$i];
					while($i_count <= $x_count){
						$new_image_name = str_replace($illegal_char[$i_count], "_", $new_image_name);
						$i_count++;
					}
					$new_image_name = str_replace(" ", "_", $new_image_name); // Replace spaces with underscores
					
					// CHECK IF FILE EXISTS
					if(!file_exists($path . $new_image_name)) {
						$new_image_name = $new_image_name;
					} else {
						// FILE EXISTS - RENAME (RENAME IN ORDER / myfile_1.jpg, myfile_2.jpg, etc.)
						$filename_array = split("\.", $new_image_name);
						$array_count = count($filename_array);
						
						$x_count2 = 0;
						while($x_count2 < ($array_count - 1)){ // 5
							if($x_count2 != 0){
								// IF THERE ARE PERIODS ADD THEM ONLY AFTER THE FIRST WORD
								$new_image_name2 = $new_image_name2 . "." . $filename_array[$x_count2];
							}
							else {
								$new_image_name2 = $new_image_name2 . $filename_array[$x_count2];
							}
							$x_count2++;
						}
						$x_count3 = 1;
						while(file_exists($path . $new_image_name)) {
							$new_image_name = $new_image_name2 . "_" . $x_count3 . "." . $filename_array[$x_count2];
							$x_count3++;	
						}			
					}
					
					//echo "New Name: " . $new_image_name . "<br>";
					session_register("new_image_name");
					$_SESSION['new_image_name'] = $new_image_name;
					
				
					// COPY IMAGE TO DIRECTORY
					copy($from_path . $img_files[$i], $path . $new_image_name);
					
					
					// GET IMAGE WIDTH & HEIGHT
					$the_size = getimagesize($path . $new_image_name);
					
					// RESIZE IMAGE IF NEEDED					
					if($new_width != ""){
						if($the_size[0] > $new_width) {							
							$ratio = $new_width/$the_size[0];
							// IF A NEW HEIGHT (CROPPED) IS FILLED OUT CROP TO THAT HEIGHT. OTHERWISE SCALE.
							if($new_height_c != ""){
								$new_height = $new_height_c;
							}
							else{
								$new_height = $the_size[1] * $ratio;
							}
							
							if(@ImageCreateFromJPEG($path . $new_image_name)){
							$src_img = ImageCreateFromJPEG($path . $new_image_name);
         			$dst_img = ImageCreateTrueColor($new_width, $new_height);
							imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, imagesx($src_img), imagesy($src_img));
							imagejpeg($dst_img, $path . $new_image_name, $quality);
							imagedestroy($src_img); 
							imagedestroy($dst_img);
    				} else {
         			$sql="DELETE FROM photo_package WHERE id = '$last->id'";
         			$result2 = mysql_query($sql);
         				if(is_file($from_path . $img_files[$i])){
               		unlink($from_path . $img_files[$i]);
           			} 
           			if(is_file($path . "/" . $new_image_name)){
               		unlink($path . "/" . $new_image_name);
           			}
           			if(is_file($path . "/s_" . $new_image_name)){
               		unlink($path . "/s_" . $new_image_name);
           			}
           			if(is_file($path . "/i_" . $new_image_name)){
               		unlink($path . "/i_" . $new_image_name);
           			}
           			if(is_file($path . "/m_" . $new_image_name)){
               		unlink($path . "/m_" . $new_image_name);
           			}
           		echo "Sorry the photo $new_image_name is unable to be uploaded due to issue (see email sent to you) \n <a href=\"processing.php?error=1\">Click to Continue</a>";
           		$to = $setting->support_email;
							$no_upload_message = "There was an issue with your photostore not being able to upload a photo \"$new_image_name\". \n";
							$no_upload_message.= "Chances are this is usually caused by low resources on the server your site is hosted with. Either your php settings are low, not enough memory allocated, or something else along those lines. \n";
							$no_upload_message.= "You will need to adjust your php settings to allow you to upload this photo, or you can try again and see if it will upload. This error can also be because the photo your trying to upload is corrupted. \n";
							$no_upload_message.= "------------ERROR INFO-----------\n";
							$no_upload_message.= "ERROR:15 Failed to resize the image during FTP import so the process was stopped and all records of this photo were deleted \n";
							$no_upload_message.= "---------------------------------\n";
							mail($to, $setting->site_title . " failed to upload a photo (NEEDS FIXED)", $no_upload_message, "From: " . $setting->support_email);
           		exit;
      			}
      			/*
							$dst_img = ImageCreateTrueColor($new_width, $new_height);
							imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, imagesx($src_img), imagesy($src_img));
							imagejpeg($dst_img, $path . $new_image_name, $quality);
							imagedestroy($src_img); 
							imagedestroy($dst_img);
						*/
						}
					}
					
					$icon_width = 250;
			
				//NEW LARGE PREVIEW SIZE
			if($setting->large_size == 1){
					if($icon_width != ""){
						$samplel_width = $setting->preview_size;
						
						if($the_size[0] >= $the_size[1]){
							
							if($the_size[0] > $samplel_width){
								$newl_width = $samplel_width;
							} else {
								$newl_width = $the_size[0];
							}
								$ratio = $newl_width/$the_size[0];
								$newl_height = $the_size[1] * $ratio;		
										
						} else {
							
							if($the_size[1] > $samplel_width){
								$newl_height = $samplel_width;	
							} else {
								$newl_height = $the_size[1];	
							}		
								$ratio = $newl_height/$the_size[1];
								$newl_width = $the_size[0] * $ratio;
						}
						
						if(@ImageCreateFromJPEG($path . $new_image_name)){
         			$src_img = ImageCreateFromJPEG($path . $new_image_name);										
							$dst_img = ImageCreateTrueColor($newl_width, $newl_height);					
							imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $newl_width, $newl_height, imagesx($src_img), imagesy($src_img));
							imagejpeg($dst_img, $path . "m_" . $new_image_name, $large_quality);
							imagedestroy($src_img);
							imagedestroy($dst_img);
    				} else {
         			$sql="DELETE FROM photo_package WHERE id = '$last->id'";
         			$result2 = mysql_query($sql);
         			  if(is_file($from_path . $img_files[$i])){
               		unlink($from_path . $img_files[$i]);
           			} 
           			if(is_file($path . "/" . $new_image_name)){
               		unlink($path . "/" . $new_image_name);
           			}
           			if(is_file($path . "/s_" . $new_image_name)){
               		unlink($path . "/s_" . $new_image_name);
           			}
           			if(is_file($path . "/i_" . $new_image_name)){
               		unlink($path . "/i_" . $new_image_name);
           			}
           			if(is_file($path . "/m_" . $new_image_name)){
               		unlink($path . "/m_" . $new_image_name);
           			}
           		echo "Sorry the photo $new_image_name is unable to be uploaded due to issue (see email sent to you) \n <a href=\"processing.php?error=1\">Click to Continue</a>";
							$no_upload_message = "There was an issue with your photostore not being able to upload a photo \"$new_image_name\". \n";
							$no_upload_message.= "Chances are this is usually caused by low resources on the server your site is hosted with. Either your php settings are low, not enough memory allocated, or something else along those lines. \n";
							$no_upload_message.= "You will need to adjust your php settings to allow you to upload this photo, or you can try again and see if it will upload. This error can also be because the photo your trying to upload is corrupted. \n";
							$no_upload_message.= "-----------ERROR INFO------------\n";
							$no_upload_message.= "ERROR:16 Failed to create the m_ (click to enlarge sample image) during FTP import so the process was stopped and all records of this photo were deleted \n";
							$no_upload_message.= "---------------------------------\n";
							mail($to, $setting->site_title . " failed to upload a photo (NEEDS FIXED)", $no_upload_message, "From: " . $setting->support_email);
           		exit;
      			}
      			/*
						$src_img = ImageCreateFromJPEG($path . $new_image_name);										
						$dst_img = ImageCreateTrueColor($newl_width, $newl_height);					
						imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $newl_width, $newl_height, imagesx($src_img), imagesy($src_img));
						imagejpeg($dst_img, $path . "m_" . $new_image_name, $large_quality);
						imagedestroy($src_img); 
						imagedestroy($dst_img);
						*/
					}
				}
				
					// CREATE 600px SAMPLE
					if($icon_width != ""){
						
						$sample_width = 600;
						if($the_size[0] < $sample_width){
							$sample_width = $the_size[0];
						}
						
						if(@ImageCreateFromJPEG($path . $new_image_name)){
         			$src_img = ImageCreateFromJPEG($path . $new_image_name);
         			$ratio = $sample_width/$the_size[0];
							$new_height = $the_size[1] * $ratio;
							$dst_img = ImageCreateTrueColor($sample_width, $new_height);				
							imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $sample_width, $new_height, imagesx($src_img), imagesy($src_img));
							imagejpeg($dst_img, $path . "s_" . $new_image_name, $sample_quality);
							imagedestroy($src_img); 
							imagedestroy($dst_img);
    				} else {
         			$sql="DELETE FROM photo_package WHERE id = '$last->id'";
         			$result2 = mysql_query($sql);
         				if(is_file($from_path . $img_files[$i])){
               		unlink($from_path . $img_files[$i]);
           			} 
           			if(is_file($path . "/" . $new_image_name)){
               		unlink($path . "/" . $new_image_name);
           			}
           			if(is_file($path . "/s_" . $new_image_name)){
               		unlink($path . "/s_" . $new_image_name);
           			}
           			if(is_file($path . "/i_" . $new_image_name)){
               		unlink($path . "/i_" . $new_image_name);
           			}
           			if(is_file($path . "/m_" . $new_image_name)){
               		unlink($path . "/m_" . $new_image_name);
           			}
           		echo "Sorry the photo $new_image_name is unable to be uploaded due to issue (see email sent to you) \n <a href=\"processing.php?error=1\">Click to Continue</a>";
							$no_upload_message = "There was an issue with your photostore not being able to upload a photo \"$new_image_name\". \n";
							$no_upload_message.= "Chances are this is usually caused by low resources on the server your site is hosted with. Either your php settings are low, not enough memory allocated, or something else along those lines. \n";
							$no_upload_message.= "You will need to adjust your php settings to allow you to upload this photo, or you can try again and see if it will upload. This error can also be because the photo your trying to upload is corrupted. \n";
							$no_upload_message.= "-----------ERROR INFO-----------\n";
							$no_upload_message.= "ERROR:17 Failed to create the s_ (sample preview image) during FTP import so the process was stopped and all records of this photo were deleted \n";
							$no_upload_message.= "---------------------------------\n";
							mail($to, $setting->site_title . " failed to upload a photo (NEEDS FIXED)", $no_upload_message, "From: " . $setting->support_email);
           		exit;
      			}
      			/*
						$src_img = ImageCreateFromJPEG($path . $new_image_name);								
						$ratio = $sample_width/$the_size[0];
						$new_height = $the_size[1] * $ratio;						
						$dst_img = ImageCreateTrueColor($sample_width, $new_height);
						imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $sample_width, $new_height, imagesx($src_img), imagesy($src_img));
						imagejpeg($dst_img, $path . "s_" . $new_image_name, $sample_quality);
						imagedestroy($src_img); 
						imagedestroy($dst_img);
						//watermark("../stock_photos/s_" . $new_image_name,"s_" . $new_image_name,"../images/watermark.png",85);
						*/
					}
					
					// CREATE ICON
					$icon_width = 250;
					if($the_size[0] < $icon_width){
						$icon_width = $the_size[0];
					}
					
					$new_width = $icon_width;
					
					if($icon_width != ""){						
						if($the_size[0] >= $the_size[1]){
							$ratio = $new_width/$the_size[0];
							$new_height = $the_size[1] * $ratio;
						} else {
							$new_height = $icon_width;
							$ratio = $new_height/$the_size[1];
							$new_width = $the_size[0] * $ratio;
						}
							
						if(@ImageCreateFromJPEG($path . $new_image_name)){
         			$src_img = ImageCreateFromJPEG($path . $new_image_name);
         			$dst_img = ImageCreateTrueColor($new_width, $new_height);						
							imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, imagesx($src_img), imagesy($src_img));
							imagejpeg($dst_img, $path . "i_" . $new_image_name, $thumb_quality);
							imagedestroy($src_img); 
							imagedestroy($dst_img);
    				} else {
         			$sql="DELETE FROM photo_package WHERE id = '$last_id'";
         			$result2 = mysql_query($sql);
         				if(is_file($from_path . $img_files[$i])){
               		unlink($from_path . $img_files[$i]);
           			} 
           			if(is_file($path . "/" . $new_image_name)){
               		unlink($path . "/" . $new_image_name);
           			}
           			if(is_file($path . "/s_" . $new_image_name)){
               		unlink($path . "/s_" . $new_image_name);
           			}
           			if(is_file($path . "/i_" . $new_image_name)){
               		unlink($path . "/i_" . $new_image_name);
           			}
           			if(is_file($path . "/m_" . $new_image_name)){
               		unlink($path . "/m_" . $new_image_name);
           			}
           		echo "Sorry the photo $new_image_name is unable to be uploaded due to issue (see email sent to you) \n <a href=\"processing.php?error=1\">Click to Continue</a>";
           		$to = $setting->support_email;
							$no_upload_message = "There was an issue with your photostore not being able to upload a photo \"$new_image_name\". \n";
							$no_upload_message.= "Chances are this is usually caused by low resources on the server your site is hosted with. Either your php settings are low, not enough memory allocated, or something else along those lines. \n";
							$no_upload_message.= "You will need to adjust your php settings to allow you to upload this photo, or you can try again and see if it will upload. This error can also be because the photo your trying to upload is corrupted. \n";
							$no_upload_message.= "------------ERROR INFO-----------\n";
							$no_upload_message.= "ERROR:18 Failed to create the i_ (thumbnail image) during FTP import so the process was stopped and all records of this photo were deleted \n";
							$no_upload_message.= "---------------------------------\n";
							mail($to, $setting->site_title . " failed to upload a photo (NEEDS FIXED)", $no_upload_message, "From: " . $setting->support_email);
           		exit;
      			}
      			/*				
						$dst_img = ImageCreateTrueColor($new_width, $new_height);
						imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, imagesx($src_img), imagesy($src_img));
						imagejpeg($dst_img, $path . "i_" . $new_image_name, $thumb_quality);
						imagedestroy($src_img); 
						imagedestroy($dst_img);
						//watermark("../stock_photos/i_" . $new_image_name,"i_" . $new_image_name,"../images/watermark.png",100);
						*/
					}
					// GET IMAGE DETAILS
					$img_src = $path . $new_image_name;
					$image_bytes = filesize($img_src);
					$image_kb = round($image_bytes/1024);
					$the_size = getimagesize($img_src);
					$image_width = $the_size[0];
					$image_height = $the_size[1];
					$current_time = date("YmdHis");
					
					$image_details = array(1 => $new_image_name,$img_type,$path,$image_bytes,$image_kb,$image_width,$image_height,$current_time);
					
					$image_results = "Image Uploaded Successfully";
					$oresult_code   = 1;
					
					$quality_order=1;
					$lquality_order=1;
					
					
					##############################################################################################
					# CREATE OTHER SIZES
					##############################################################################################
					if($_SESSION['aprofile'] != ""){
						$aprofile = $_SESSION['aprofile'];
				   } else {
				   	if($_POST['aprofile']){
				   	$aprofile = $_POST['aprofile'];
				  }
				}
				    if($aprofile != ""){
						foreach($aprofile as $key => $value){
							if(!$_SESSION["p_price_" . $value]){
							session_register("p_price_" . $value);
							$_SESSION["p_price_" . $value] = $_POST["p_price_" . $value];
						}
							
							if($the_size[0] > $p_size[$value]){
						
								$lnew_image_name = $value . "_" . $new_image_name;
								if($_SESSION["p_price_" . $value] != ""){
									$lprice = $_SESSION["p_price_" . $value];
								} else {								
									$lprice = $_POST["p_price_" . $value];
								}
								
								$lquality = $p_name[$value];
								$loriginal = $p_number[$value];
								$lquality_order = $p_order[$value];
							
								// RESIZE IMAGE IF NEEDED
								$src_img = ImageCreateFromJPEG($path . $new_image_name);								
								$ratio = $p_size[$value]/$the_size[0];
								$lnew_height = $the_size[1] * $ratio;
								
								$dst_img = ImageCreateTrueColor($p_size[$value], $lnew_height);
								imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $p_size[$value], $lnew_height, imagesx($src_img), imagesy($src_img));
								imagejpeg($dst_img, $path . $lnew_image_name, $quality);
								imagedestroy($src_img); 
								imagedestroy($dst_img);								
								
								// CREATE 300px SAMPLE
								$sample_width = 600;
								$src_img = ImageCreateFromJPEG($path . $lnew_image_name);								
								$ratio = $sample_width/$the_size[0];
								$new_height = $the_size[1] * $ratio;						
								$dst_img = ImageCreateTrueColor($sample_width, $new_height);
														
								imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $sample_width, $new_height, imagesx($src_img), imagesy($src_img));
								imagejpeg($dst_img, $path . "s_" . $lnew_image_name, $sample_quality);
								
								imagedestroy($src_img); 
								imagedestroy($dst_img);
								
								// CREATE ICON
								$icon_width = 250;
								$new_width = $icon_width;
								
								$src_img = ImageCreateFromJPEG($path . $lnew_image_name);								
								if($the_size[0] >= $the_size[1]){
									$ratio = $new_width/$the_size[0];
									$new_height = $the_size[1] * $ratio;
								} else {
									$new_height = $icon_width;
									$ratio = $new_height/$the_size[1];
									$new_width = $the_size[0] * $ratio;
																
								}
								$dst_img = ImageCreateTrueColor($new_width, $new_height);
														
								imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, imagesx($src_img), imagesy($src_img));
								imagejpeg($dst_img, $path . "i_" . $lnew_image_name, $thumb_quality);
								
								imagedestroy($src_img); 
								imagedestroy($dst_img);
								
								//ADDED IN PS350 TO CLEANUP DATA ENTRY
								$lprice = price_cleanup($lprice);
																	
								$sql2 = "INSERT INTO uploaded_images (reference,reference_id,filename,price,quality,quality_order,added,price_contact,original) VALUES ('$reference','$last_id','$lnew_image_name','$lprice','$lquality','$lquality_order','$added','$price_contact1','$loriginal')";
								$result2 = mysql_query($sql2);
								
								
								unset($lnew_image_name);
								unset($new_width);
								unset($new_height);
								unset($lnew_width);
								unset($lnew_height);
								//unset($the_size);
								
							}
						}
					}
				
					##############################################################################################
				
				$lquality_order = 1;
				
				}
			
				if($oresult_code == 1){
					$added = date("Ymd");					
					$sql3 = "INSERT INTO uploaded_images (reference,reference_id,filename,price,added,price_contact,quality,quality_order,original) VALUES ('$reference','$last_id','$new_image_name','$price1','$added','$price_contact1','$quality1','$quality_order1','1')";
					$result3 = mysql_query($sql3);
					
					$added_count++;
				}
				
				
				// DONE WITH THE FILE DELETE IT
				unlink("../ftp/" . $img_files[$i]);
				
				// WASN'T BEING USED
				//if($new_image_name != $img_files[$i]){
					//echo "...adding " . $img_files[$i] . " (File already exists: Renaming to " . $new_image_name . ")<br>";
				//} else {
					//echo "...adding " . $img_files[$i] . "<br>";
				//}
				
				$i++;				
							
				unset($iptc_keywords);
				unset($iptc_description);
				unset($iptc_title);
				
				unset($last_id);
				
				unset($quality_order);
				unset($new_image_name2);
				unset($illegal_char);
				unset($new_image_name);
				unset($filename_array);
				unset($new_width);
				unset($new_height);
				unset($the_size);
				
				set_time_limit(120);
				
			}
		}
			
			//echo "<br>Your photos have been added. <a href=\"mgr.php?nav=" . $nav . "\">Continue</a>";
			//echo "<br />Upload Completed: <a href=\"mgr.php?mes=uploaded&nav=$nav&added=$added_count\">Continue</a>";
			
			
			//$goto = "mgr.php?mes=uploaded&nav=" . $nav . "&added=" . $added_count;
			//header("location: $goto");
		?>
<SCRIPT LANGUAGE="JavaScript">
if (navigator.javaEnabled()) 
window.location = "processing.php";
else 
window.location = "processing.php";
</script>
<?
			//header("location: processing.php");
			//exit;
			
	}
		break;

/*-----------------------------------------------------------------------------------------------------------------------*/
/*                                                      DEFAULT                                                          */
/*-----------------------------------------------------------------------------------------------------------------------*/	
		default:
			header("location: login.php");
			exit;
		break;
	}
?>