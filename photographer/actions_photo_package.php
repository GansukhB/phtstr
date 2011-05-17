<?php
	session_start();
	include( "check_login_status.php" );
	
	// PHOTO ACTIONS - UPDATED 11.28.2007
	
	include( "config_mgr.php" );
	
	if($_SESSION['access_type'] != "mgr"){ echo "Operation cannot be performed in demo mode"; exit; }
	
	$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
	
	$image_path      = $stock_photo_path_manager;
	$reference       = "photo_package";
	
	//$file_path = "../uploaded_files/";
	//$image_path = "../uploaded_images/";
		
	switch($_GET['pmode']){

/*-----------------------------------------------------------------------------------------------------------------------*/
/*                                                PLUGIN ACTIONS                                                    */
/*-----------------------------------------------------------------------------------------------------------------------*/
	/* SAVE NEW ITEM */	
		case "save_new":
		//Upload zip files attached
			$zip_result = mysql_query("SELECT id FROM uploaded_images", $db);
			while($zip = mysql_fetch_object($zip_result)){
				$rs = "zip_file_" . $zip->id;
				$fs = "file_name_" . $zip->id;
				if($_FILES[$rs]['name'] != ""){
				$zip_name = strip_ext($_POST[$fs]);
				$zip_name = $zip_name . ".zip";
				upload_file_new($_FILES[$rs],$zip_name,"../files/");
				}
			}
		//Delete uploaded zip files attached
			$zip1_result = mysql_query("SELECT id FROM uploaded_images", $db);
			while($zip1 = mysql_fetch_object($zip1_result)){
				$rs1 = "delete_zip_" . $zip1->id;
				$fs1 = "file_name_" . $zip1->id;
				if($_POST[$rs1] == 1){
				$zip_name1 = strip_ext($_POST[$fs1]);
				$zip_name1 = $zip_name1 . ".zip";
				unlink("../files/" . $zip_name1);
				}
			}
			
		//Upload movie files attached
			$movie_result = mysql_query("SELECT id FROM uploaded_images", $db);
			while($movie = mysql_fetch_object($movie_result)){
				$rs_mov = "movie_file_" . $movie->id;
				$fs_mov = "file_name_" . $movie->id;
				if($_FILES[$rs_mov]['name'] != ""){
				$movie_extension = $_FILES[$rs_mov]['name'];
			  $movie_ext = substr($_FILES[$rs_mov]['name'], -3);
				$movie_name = strip_ext($_POST[$fs_mov]);
				$movie_name = $movie_name . "." . $movie_ext;
				upload_file_new($_FILES[$rs_mov],$movie_name,$stock_video_path_manager);
				}
			}
		//Delete uploaded movie files attached
			$movie1_result = mysql_query("SELECT id FROM uploaded_images", $db);
			while($movie1 = mysql_fetch_object($movie1_result)){
				$rs_mov1 = "delete_movie_" . $movie1->id;
				$fs_mov1 = "file_name_" . $movie1->id;
				if($_POST[$rs_mov1] == 1){
				$movie_name1 = strip_ext($_POST[$fs_mov1]);
				$movie_name1 = array($movie_name1 . ".mov", $movie_name1 . ".avi", $movie_name1 . ".mpg", $movie_name1 . ".flv", $movie_name1 . ".wmv");
				foreach($movie_name1 as $key => $value){
				if(is_file($stock_video_path_manager . $movie_name1[$key])){
				$mov_name1 = $movie_name1[$key];
					}
				}
				unlink($stock_video_path_manager . $mov_name1);
				}
			}
			
		//Upload movie sample files attached
			$movie2_result = mysql_query("SELECT id FROM uploaded_images", $db);
			while($movie2 = mysql_fetch_object($movie2_result)){
				$rs_mov2 = "sample_file_" . $movie2->id;
				$fs_mov2 = "file_name_" . $movie2->id;
				if($_FILES[$rs_mov2]['name'] != ""){
				$movie2_extension = $_FILES[$rs_mov2]['name'];
			  $movie2_ext = substr($_FILES[$rs_mov2]['name'], -3);
				$movie2_name = strip_ext($_POST[$fs_mov2]);
				$movie2_name = $movie2_name . "." . $movie2_ext;
				upload_file_new($_FILES[$rs_mov2],$movie2_name,$sample_video_path_manager);
				}
			}
			//Delete uploaded sample movie files attached
			$movie3_result = mysql_query("SELECT id FROM uploaded_images", $db);
			while($movie3 = mysql_fetch_object($movie3_result)){
				$rs_mov3 = "delete_sample_" . $movie3->id;
				$fs_mov3 = "file_name_" . $movie3->id;
				if($_POST[$rs_mov3] == 1){
				$movie_name3 = strip_ext($_POST[$fs_mov3]);
				$movie_name3 = array($movie_name3 . ".mov", $movie_name3 . ".avi", $movie_name3 . ".mpg", $movie_name3 . ".flv", $movie_name3 . ".wmv");
				foreach($movie_name3 as $key => $value){
				if(is_file($sample_video_path_manager . $movie_name3[$key])){
				$mov_name3 = $movie_name3[$key];
					}
				}
				unlink($sample_video_path_manager . $mov_name3);
				}
			}
				
			if($prod){
				foreach($prod as $value){
					$prod2 = $prod2 . "," . $value;
				}
			}
			$prod = $prod2;
			
			if($size){
				foreach($size as $value){
					$size2 = $size2 . "," . $value;
				}
			}
			$size = $size2;
			
			$added = date("Ymd");
			
			$keywords = strtolower($_POST['keywords']);
			
			if($_POST['other_galleries']){
				$other_galleries2 = "," . implode(",", $_POST['other_galleries']) . ",";
			}
			
			if($_FILES['image'] != ""){
				# GRAB IPTC INFO
				get_iptc_data($_FILES['image']['tmp_name']);
				# ADD IPTC INFO TO OTHER INFO	
				if($iptc_keywords){
					$keywords = addslashes($iptc_keywords);
				}
				if($iptc_description){
					$description = addslashes($iptc_description);
				}
				if($iptc_title){
					$title = addslashes($iptc_title);
				}
			}
			
			//ADDED IN PS350 TO CLEANUP DATA ENTRY
			$title = cleanup($title);
			$description = cleanup($description);
			$keywords = cleanup($keywords);
			$price = price_cleanup($price);
			
			// SAVE DATA
			$publish_date = $s_year . $s_month . $s_day;
			$sql = "INSERT INTO photo_package (user_uploaded, title,gallery_id,keywords,active,added,photographer,description,prod,sizes,update_29,all_prints,all_sizes,other_galleries,act_download,featured) VALUES ('$user_id', '$title','$gallery_id','$keywords','$active','$added','$photographer','$description','$prod','$size','1','$all_prints','$all_sizes','$other_galleries2','$act_download','$featured')";
			$result = mysql_query($sql);
			
			$last_result = mysql_query("SELECT id FROM photo_package where user_uploaded = '$user_id' order by id desc", $db);
			$last = mysql_fetch_object($last_result);
			
			/*
			// UPLOAD FILE
			upload_file($fileup,$fileup_name,$file_path);
			
			if($file_result_code == 1){
				$sql = "INSERT INTO uploaded_files (reference,reference_id,filename,file_text) VALUES ('$reference','$last->id','$file_details[1]','$file_text')";
				$result = mysql_query($sql);
			}
			*/
			
			// UPLOAD IMAGE
			if($_FILES['image'] != ""){
				upload_image_g(
					$_FILES['image'],				  // name of form field to upload
					$_FILES['image']['name'],		  // filename of the image
					$_FILES['image']['type'],		  // type of image
					$image_path,          // directory to upload the image to
					"",				  	  // new width for your image / if blank doesn't resize
					"",				      // new height for your image (cropped) / if blank resizes with ratio
					"125",				  // icon width / if left blank no icon is created
					"100"				  // image quality
				);						  // returns $image_details[1] - $image_details[8]
										  // new image name(1),image type(2), image path(3), image bytes(4), image kb(5), width(6), heigth(7), time(8)
										  // $image_results returns results of upload
										  // $result_code returns 1,2,3 / results of upload... 1 Success, 2 Type Not Supported , 3 Empty
				if($result_code == 1){
					$added = date("Ymd");					
					$sql = "INSERT INTO uploaded_images (user_uploaded, reference,reference_id,filename,price,quality,quality_order,added,price_contact,original) VALUES ('$user_id', '$reference','$last->id','$image_details[1]','$price','$quality','$quality_order','$added','$price_contact','1')";
					$result = mysql_query($sql);
				}
			}
					
			$get_size = getimagesize($image_path . $image_details[1]);
			
			if($_POST['aprofile']){
				foreach($_POST['aprofile'] as $key => $value){
					if($get_size[0] > $p_size[$value]){
					
					/*
						echo "Key: " . $key . "<br />";
						echo "Value: " . $value . "<br />";
						echo "Name: " . $p_name[$value] . "<br />";
						echo "Price: " . $_POST["p_price_" . $value] . "<br />";
						echo "Size: " . $p_size[$value] . "<br /><br />";
					*/
						
						$price1 = $_POST["p_price_" . $value];
						$quality = $p_name[$value];
						$original = $p_number[$value];
						$quality_order = $p_order[$value];
						
						upload_image_g(
							$_FILES['image'],				  // name of form field to upload
							$_FILES['image']['name'],		  // filename of the image
							$_FILES['image']['type'],		  // type of image
							$image_path,          // directory to upload the image to
							$p_size[$value],	  // new width for your image / if blank doesn't resize
							"",				      // new height for your image (cropped) / if blank resizes with ratio
							"125",				  // icon width / if left blank no icon is created
							"100"				  // image quality
						);						  // returns $image_details[1] - $image_details[8]
												  // new image name(1),image type(2), image path(3), image bytes(4), image kb(5), width(6), heigth(7), time(8)
												  // $image_results returns results of upload
												  // $result_code returns 1,2,3 / results of upload... 1 Success, 2 Type Not Supported , 3 Empty
						
						if($result_code == 1){
							$added = date("Ymd");					
							$sql = "INSERT INTO uploaded_images (user_uploaded, reference,reference_id,filename,price,quality,quality_order,added,price_contact,original) VALUES ('$user_id', '$reference','$last->id','$image_details[1]','$price1','$quality','$quality_order','$added','$price_contact','$original')";
							$result = mysql_query($sql);
						}
					}
					
				}
			}
			//exit;
			header("location: " . $_POST['return']);
		break;
		
		case "rotate":
		//ADDED IN PS350 FOR PHOTO ROTATION
			function rotateimage($rotate,$photo_name,$prefix){
				global $stock_photo_path_manager;
				// THUMBNAIL QUALITY
				$imgquality = 100;
				// File and rotation
				$photo_name =  $stock_photo_path_manager.$prefix.$photo_name;
				$degrees = $rotate;
				// Load
				$source = imagecreatefromjpeg($photo_name);
				// Rotate
				$rotate = imagerotate($source, $degrees, 0);
				// Output
				//imagejpeg($rotate);
				if(!@imagejpeg($rotate,$photo_name, $imgquality)){
					echo "Sorry this photo no longer belongs to PHP so you can't rotate it.<br>This can happen cause your hosting company runs a script each night or month to assign all files to you on your hosting so they can track disk usage.<br>Once this happens your files which use to belong to PHP (user \"nobody\") now has a different user and PHP doens't have permission to modify the photo, PHP can only delete it.<br>If you need to rotate this photo you will have to delete this photo and re-upload it already rotated.";
					exit;
				}
				//$src = $photo_name;
				//include("image.php");
				imagedestroy($source);
				imagedestroy($rotate);
			}
			if(file_exists($stock_photo_path_manager."i_".$photo_name)){
				rotateimage($rotate,$photo_name,"i_");
			}
			if(file_exists($stock_photo_path_manager."s_".$photo_name)){
				rotateimage($rotate,$photo_name,"s_");
			}
			if(file_exists($stock_photo_path_manager."m_".$photo_name)){
				rotateimage($rotate,$photo_name,"m_");
			}
			header("location: ".$return."&message=".$message."&order_by=".$order_by."&order_type=".$order_type."&item_id=".$item_id."&gid=".$gid);
			exit;
			break;
			
	/* PS330 search addon */
	  case "search":
	  $search = $_POST['search'];
	  $result_search = mysql_query("SELECT * FROM photo_package WHERE id = '$search'", $db);
	  $result_rows = mysql_num_rows($result_search);
	  $result = mysql_fetch_object($result_search);
	  $gal = $result->gallery_id;
	  if($result_rows > 0){
	  header("location: " . $_POST['return'] . "&gid=" . $gal . "&item_id=" . $search);
	 	} else {
	 	header("location: " . $_POST['return'] . "&message=no_match&search=" . $search);
		}
	  break;
	  
	/* DELETE ITEM(S) */	
		case "delete":
		
		$image_path = $stock_photo_path_manager;
		
		$delete_array = implode(",",$_POST['delete']);
		
		//echo $delete_array; exit;
		
		$result = mysql_query("SELECT id FROM photo_package WHERE id IN ($delete_array)", $db);
		while($rs = mysql_fetch_object($result)) {		
			//echo $rs->id . " | ";
			
			//if($_POST[$rs->id] == "1") {
				
				// DELETE ANY FILES THAT ARE ATTACHED TO THIS ITEM
				$result_file = mysql_query("SELECT id,filename FROM uploaded_files where reference = '$reference' and reference_id = '$rs->id'", $db);
				while($rs_file = mysql_fetch_object($result_file)) {
					if(file_exists($file_path . $rs_file->filename)){
						unlink($file_path . $rs_file->filename);
					}
					
					$sql="DELETE FROM uploaded_files WHERE id = '$rs_file->id'";
					$result2 = mysql_query($sql);
						
					//unlink("../images_news/i_" . $rs->image);
				}
				
				
				
				// DELETE ANY IMAGES THAT ARE ATTACHED TO THIS ITEM
				$result_image = mysql_query("SELECT id,filename FROM uploaded_images where reference = 'photo_package' and reference_id = '$rs->id'", $db);
				while($rs_image = mysql_fetch_object($result_image)) {
					if(file_exists($image_path . $rs_image->filename)){
						unlink($image_path . $rs_image->filename);
					}
					if(file_exists($image_path . "i_" . $rs_image->filename)){
						unlink($image_path . "i_" . $rs_image->filename);
					}
					if(file_exists($image_path . "s_" . $rs_image->filename)){
						unlink($image_path . "s_" . $rs_image->filename);
					}
					if(file_exists($image_path . "m_" . $rs_image->filename)){
						unlink($image_path . "m_" . $rs_image->filename);
					}
					
					$sql="DELETE FROM uploaded_images WHERE id = '$rs_image->id'";
					$result2 = mysql_query($sql);
					
					//echo $rs_image->filename; exit;
				}
				
				
				$sql="DELETE FROM photo_package WHERE id = '$rs->id'";
				$result2 = mysql_query($sql);
			//}
		}
		header("location: " . $_POST['return']);
		exit;
		
	/* SAVE EDIT ITEM */	
		case "save_edit";		
		
		//Upload zip files attached
			$zip_result = mysql_query("SELECT id FROM uploaded_images", $db);
			while($zip = mysql_fetch_object($zip_result)){
				$rs = "zip_file_" . $zip->id;
				$fs = "file_name_" . $zip->id;
				if($_FILES[$rs]['name'] != ""){
				$zip_name = strip_ext($_POST[$fs]);
				$zip_name = $zip_name . ".zip";
				upload_file_new($_FILES[$rs],$zip_name,"../files/");
				}
			}
		//Delete uploaded zip files attached
			$zip1_result = mysql_query("SELECT id FROM uploaded_images", $db);
			while($zip1 = mysql_fetch_object($zip1_result)){
				$rs1 = "delete_zip_" . $zip1->id;
				$fs1 = "file_name_" . $zip1->id;
				if($_POST[$rs1] == 1){
				$zip_name1 = strip_ext($_POST[$fs1]);
				$zip_name1 = $zip_name1 . ".zip";
				unlink("../files/" . $zip_name1);
				}
			}
			
		//Upload movie files attached
			$movie_result = mysql_query("SELECT id FROM uploaded_images", $db);
			while($movie = mysql_fetch_object($movie_result)){
				$rs_mov = "movie_file_" . $movie->id;
				$fs_mov = "file_name_" . $movie->id;
				if($_FILES[$rs_mov]['name'] != ""){
				$movie_extension = $_FILES[$rs_mov]['name'];
			  $movie_ext = substr($_FILES[$rs_mov]['name'], -3);
				$movie_name = strip_ext($_POST[$fs_mov]);
				$movie_name = $movie_name . "." . $movie_ext;
				upload_file_new($_FILES[$rs_mov],$movie_name,$stock_video_path_manager);
				}
			}
		//Delete uploaded movie files attached
			$movie1_result = mysql_query("SELECT id FROM uploaded_images", $db);
			while($movie1 = mysql_fetch_object($movie1_result)){
				$rs_mov1 = "delete_movie_" . $movie1->id;
				$fs_mov1 = "file_name_" . $movie1->id;
				if($_POST[$rs_mov1] == 1){
				$movie_name1 = strip_ext($_POST[$fs_mov1]);
				$movie_name1 = array($movie_name1 . ".mov", $movie_name1 . ".avi", $movie_name1 . ".mpg", $movie_name1 . ".flv", $movie_name1 . ".wmv");
				foreach($movie_name1 as $key => $value){
				if(is_file($stock_video_path_manager . $movie_name1[$key])){
				$mov_name1 = $movie_name1[$key];
					}
				}
				unlink($stock_video_path_manager . $mov_name1);
				}
			}
			
		//Upload movie sample files attached
			$movie2_result = mysql_query("SELECT id FROM uploaded_images", $db);
			while($movie2 = mysql_fetch_object($movie2_result)){
				$rs_mov2 = "sample_file_" . $movie2->id;
				$fs_mov2 = "file_name_" . $movie2->id;
				if($_FILES[$rs_mov2]['name'] != ""){
				$movie2_extension = $_FILES[$rs_mov2]['name'];
			  $movie2_ext = substr($_FILES[$rs_mov2]['name'], -3);
				$movie2_name = strip_ext($_POST[$fs_mov2]);
				$movie2_name = $movie2_name . "." . $movie2_ext;
				upload_file_new($_FILES[$rs_mov2],$movie2_name,$sample_video_path_manager);
				}
			}
			//Delete uploaded sample movie files attached
			$movie3_result = mysql_query("SELECT id FROM uploaded_images", $db);
			while($movie3 = mysql_fetch_object($movie3_result)){
				$rs_mov3 = "delete_sample_" . $movie3->id;
				$fs_mov3 = "file_name_" . $movie3->id;
				if($_POST[$rs_mov3] == 1){
				$movie_name3 = strip_ext($_POST[$fs_mov3]);
				$movie_name3 = array($movie_name3 . ".mov", $movie_name3 . ".avi", $movie_name3 . ".mpg", $movie_name3 . ".flv", $movie_name3 . ".wmv");
				foreach($movie_name3 as $key => $value){
				if(is_file($sample_video_path_manager . $movie_name3[$key])){
				$mov_name3 = $movie_name3[$key];
					}
				}
				unlink($sample_video_path_manager . $mov_name3);
				}
			}
			
			if($prod){
				foreach($prod as $value){
					$prod2 = $prod2 . "," . $value;
				}
			}
			$prod = $prod2;
			
			if($size){
				foreach($size as $value){
					$size2 = $size2 . "," . $value;
				}
			}
			$size = $size2;
			
			$keywords = strtolower($_POST['keywords']);
			
			$added = date("Ymd");
						
			if($_POST['other_galleries']){
				$other_galleries2 = "," . implode(",", $_POST['other_galleries']) . ",";
			}
			
			//ADDED IN PS350 TO CLEANUP DATA ENTRY
			$title = cleanup($title);
			$description = cleanup($description);
			$keywords = cleanup($keywords);
			$price = price_cleanup($price);
			//SAVE DATA
			$publish_date = $s_year . $s_month . $s_day;	
			$sql = "UPDATE photo_package SET title='$title',gallery_id='$gallery_id',keywords='$keywords',active='$active',added='$added',photographer='$photographer',description='$description',prod='$prod',sizes='$size',update_29='1',all_prints='$all_prints',all_sizes='$all_sizes',other_galleries='$other_galleries2',act_download='$act_download',featured='$featured' WHERE id = '" . $_POST['item_id'] . "'";
			$result = mysql_query($sql);
			
			// UPLOAD FILE
			if($fileup != ""){
				upload_file($fileup,$fileup_name,$file_path);
				
				if($file_result_code == 1){
					$sql = "INSERT INTO uploaded_files (reference,reference_id,filename,file_text) VALUES ('$reference','$item_id','$file_details[1]','$file_text')";
					$result = mysql_query($sql);
				}
			}		
			
			// UPLOAD IMAGE
			if($_FILES['image'] != ""){
				upload_image_g(
					$_FILES['image'],				  // name of form field to upload
					$_FILES['image']['name'],		  // filename of the image
					$_FILES['image']['type'],		 // type of image
					$image_path,         // directory to upload the image to
					"",				     // new width for your image / if blank doesn't resize
					"",				     // new height for your image (cropped) / if blank resizes with ratio
					"125",				 // icon width / if left blank no icon is created
					"100"				 // image quality
				);						 // returns $image_details[1] - $image_details[8]
										 // new image name(1),image type(2), image path(3), image bytes(4), image kb(5), width(6), heigth(7), time(8)
										 // $image_results returns results of upload
										 // $result_code returns 1,2,3 / results of upload... 1 Success, 2 Type Not Supported , 3 Empty
				if($result_code == 1){
					$added = date("Ymd");
					$sql = "INSERT INTO uploaded_images (user_uploaded, reference,reference_id,filename,price,quality,quality_order,added,price_contact) VALUES ('$user_id', '$reference','$item_id','$image_details[1]','$price','$quality','$quality_order','$added','$price_contact')";
					$result = mysql_query($sql);
				}
			}
			
			header("location: " . $_POST['return']);
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
