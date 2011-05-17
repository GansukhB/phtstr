<?

		# FOR MEMBERS DOWNLOADING PHOTOS & PHOTOGRAPHERS PHOTOS
		session_start();
		
		include("database.php");
		include("functions.php");
		include("config_public.php");
		
		$sizes_result = mysql_query("SELECT id,price FROM sizes where id = '" . $_GET['sid'] . "'", $db);
		$sizes = mysql_fetch_object($sizes_result);
																		
		$photo_result = mysql_query("SELECT reference_id,price,added,filename FROM uploaded_images where id = '" . $_GET['pid'] . "'", $db);
		$photo = mysql_fetch_object($photo_result);
	
	if($_GET['sid']){
		$package_result = mysql_query("SELECT sizes,all_sizes FROM photo_package where id = '$photo->reference_id'", $db);
		$package = mysql_fetch_object($package_result);
		$sc_array = explode(",",$package->sizes);
		if($package->all_sizes == 1){
			$price = $sizes->price;
			if($price != 0 or $price != "0.00"){
				$member_use = 1;
			}
		} else {
			if(in_array($sizes->id,$sc_array)){
				$price = $sizes->price;
				if($price != 0 or $price != "0.00"){
				$member_use = 1;
				}
			} else {
				if($photo->price != ""){
				$price = $photo->price;	
			} else {
				$price = $setting->default_price;	
			}
		}
		}
	} else {
		if($photo->price != ""){
				$price = $photo->price;	
			} else {
				$price = $setting->default_price;	
			}
	}
	
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
	  
		$package_result = mysql_query("SELECT photographer FROM photo_package where id = '$photo->reference_id'", $db);
		$package = mysql_fetch_object($package_result);
		
		$added = date("Ymd");	
		
		$member_result = mysql_query("SELECT sub_length,down_limit_y,down_limit_m FROM members where id = '" . $_SESSION['sub_member'] . "'", $db);
		$member_row = mysql_num_rows($member_result);
		$member = mysql_fetch_object($member_result);
		
	if($member_row > 0){
		if($member->sub_length == "Y"){
			if($member->down_limit_y == 99999){
				$free_download = 1;
			} else {
				$free_download = 0;
			}
		}
		} else {
			if($member->down_limit_m == 99999){
			$free_download = 1;
			} else {
			$free_download = 0;
			}
		}
		
		
if($price == 0 or $free_download == 1 or $_SESSION['access_status'] or $member_use == 1){
	if($_GET['sid']){
				$sizes1_result = mysql_query("SELECT size FROM sizes where id = '" . $_GET['sid'] . "'", $db);
				$sizes1 = mysql_fetch_object($sizes1_result);
			
				$pg1_result = mysql_query("SELECT filename FROM uploaded_images where id = '" . $_GET['pid'] . "'", $db);
				$pg1 = mysql_fetch_object($pg1_result);
				
				# GET THE SIZE OF THE PHOTO
				$path = "./temp/";
				$src = $stock_photo_path . $pg1->filename;
				$imageInfo = getimagesize($src);
	      $default_size = $sizes1->size;
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
	    if($free_download == 1){
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
			} else {
			if($price == 0 or $price == "0.00"){
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
			} else {
		if($member_use == 1){
			if($member->sub_length == "M"){
				if($member->down_limit_m > 0 && $member->down_limit_m < 99999){
						$download = $member->down_limit_m - 1;
						$sql = "UPDATE members SET down_limit_m='$download' WHERE id = '" . $_SESSION['sub_member'] ."'";
						$result = mysql_query($sql);
						$_SESSION['mem_down_limit']--;
					} else {
						echo $download_monthly_limit_reached;
						exit;
				}
			}
			if($member->sub_length == "Y"){
				if($member->down_limit_y > 0 && $member->down_limit_y < 99999){
						$download = $member->down_limit_y - 1;
						$sql = "UPDATE members SET down_limit_y='$download' WHERE id = '" . $_SESSION['sub_member'] ."'";
						$result = mysql_query($sql);
						$_SESSION['mem_down_limit']--;
					} else {
						echo $download_yearly_limit_reached;
						exit;
				}
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
				}
			}
		}
		} else {
	if(file_exists($check)){
			header("Content-Type: application/zip ");
			header("Content-Disposition: attachment; filename=" .basename($photo_zip));
			readfile($check); 
			exit;
		} else {
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
	readfile($src_video);
	exit;
} else {
	header("Content-Disposition: attachment; filename=" .basename($display_name));
	readfile($check_sam); 	
			header("Content-Type: image/jpeg "); 
			header("Content-Disposition: attachment; filename=" .basename($photo_name)); 
			readfile($stock_photo_path . $photo->filename);
			}
		}
	}
} else {		
	if($_SESSION['sub_member']){
		//$photog_result = mysql_query("SELECT com_download_default,com_download FROM photographers where id = '$package->photographer'", $db);
		//$photog_rows = mysql_num_rows($photog_result);
		//$photog = mysql_fetch_object($photog_result);
		
	if($photog_rows > 0 && $_SESSION['mem_download_limit'] != "0"){
		$photog_sales_result = mysql_query("SELECT id FROM photog_sales where photo_id = '" . $_GET['pid'] . "' and download_by = '" . $_SESSION['sub_member'] . "' and odate = '$added'", $db);
		$photog_sales_num_row = mysql_num_rows($photog_sales_result);
		
		if($photog_sales_num_row > 0){
			echo $download_today;
		} else {
			if($photog->com_download_default != "0"){
				$com_download_default = $photog->com_download_default;
			}
			if($photog->com_download != ""){
				$com_download = $photog->com_download;
			} else {
				$com_download = $setting->com_download;
			}
			if($package->photographer > 0){										
				if($photo->price){
					$pprice = $photo->price;
				} else {
					$pprice = $setting->default_price;
				}
			
			$sql2 = "INSERT INTO photog_sales (visitor_id,photo_id,odate,p_type,photographer,com_percent,price,download_by,completed,com_download_default) VALUES ('" . $_SESSION['visitor_id'] . "','" . $_GET['pid'] . "','$added','d','$package->photographer','$com_download','$pprice','" . $_SESSION['sub_member'] . "','1','$com_download_default')";
			$result2 = mysql_query($sql2);
			
			if($member->sub_length == "Y"){
				if($member->down_limit_y > 0 && $member->down_limit_y < 99999){
						$download = $member->down_limit_y - 1;
						$sql = "UPDATE members SET down_limit_y='$download' WHERE id = '" . $_SESSION['sub_member'] ."'";
						$result = mysql_query($sql);
						$_SESSION['mem_down_limit']--;
					if(file_exists($check)){
						header("Content-Type: application/zip ");
						header("Content-Disposition: attachment; filename=" .basename($photo_zip));
						readfile($check); 
						exit;
					} else {
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
						readfile($src_video);
						exit;
					} else {
						header("Content-Type: image/jpeg "); 
						header("Content-Disposition: attachment; filename=" .basename($photo_name));
						readfile($stock_photo_path . $photo->filename); 
						}
					}
				} else {
						echo $download_yearly_limit_reached;
				}
			} else {
				if($member->down_limit_m > 0 && $member->down_limit_m < 99999){
						$download = $member->down_limit_m - 1;
						$sql = "UPDATE members SET down_limit_m='$download' WHERE id = '" . $_SESSION['sub_member'] ."'";
						$result = mysql_query($sql);
						$_SESSION['mem_down_limit']--;
					if(file_exists($check)){
						header("Content-Type: application/zip ");
						header("Content-Disposition: attachment; filename=" .basename($photo_zip));
						readfile($check); 
						exit;
					} else {
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
						readfile($src_video);
						exit;
						} else {
						header("Content-Type: image/jpeg "); 
						header("Content-Disposition: attachment; filename=" .basename($photo_name)); 
						readfile($stock_photo_path . $photo->filename); 
						}
					}
				} else {
						echo $download_monthly_limit_reached;
				}
			}
		}
	}
} else {
			if($member->sub_length == "Y"){
				if($member->down_limit_y > 0 && $member->down_limit_y < 99999){
						$download = $member->down_limit_y - 1;
						$sql = "UPDATE members SET down_limit_y='$download' WHERE id = '" . $_SESSION['sub_member'] ."'";
						$result = mysql_query($sql);
						$_SESSION['mem_down_limit']--;
					if(file_exists($check)){
						header("Content-Type: application/zip ");
						header("Content-Disposition: attachment; filename=" .basename($photo_zip));
						readfile($check); 
						exit;
					} else {
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
						readfile($src_video);
						exit;
						} else {
						header("Content-Type: image/jpeg "); 
						header("Content-Disposition: attachment; filename=" .basename($photo_name)); 
						readfile($stock_photo_path . $photo->filename); 
						}
					}
				} else {
						echo $download_yearly_limit_reached;
				}
			} else {
				if($member->down_limit_m > 0 && $member->down_limit_m < 99999){
						$download = $member->down_limit_m - 1;
						$sql = "UPDATE members SET down_limit_m='$download' WHERE id = '" . $_SESSION['sub_member'] ."'";
						$result = mysql_query($sql);
						$_SESSION['mem_down_limit']--;
					if(file_exists($check)){
						header("Content-Type: application/zip ");
						header("Content-Disposition: attachment; filename=" .basename($photo_zip));
						readfile($check); 
						exit;
					} else {
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
						readfile($src_video);
						exit;
						} else {
						header("Content-Type: image/jpeg "); 
						header("Content-Disposition: attachment; filename=" .basename($photo_name));
						readfile($stock_photo_path . $photo->filename); 
						}
					}
				} else {
						echo $download_monthly_limit_reached;
				}
			}
		}
} else {
			echo $download_illegal;
		}
	}
?> 
