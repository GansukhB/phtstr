<?php
	session_start();
	include( "check_login_status.php" );
	
	// PHOTO GALLERY ACTIONS - UPDATED 3.22.04
	
	include( "config_mgr.php" );
	
	if($_SESSION['access_type'] != "mgr"){ echo "Operation cannot be performed in demo mode"; exit; }
	
	$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
	
	$default_file_path = $stock_photo_path_manager;
	$default_image_path = $stock_photo_path_manager;
	
	switch($_GET['pmode']){

/*-----------------------------------------------------------------------------------------------------------------------*/
/*                                                PLUGIN ACTIONS                                                    */
/*-----------------------------------------------------------------------------------------------------------------------*/
	/* SAVE NEW ITEM */	
		case "save_new":
			//ADDED IN PS350 FOR CLEANUP OF TITLES AND MORE
			//$title = cleanup($title);
			// SAVE DATA
			$sql = "INSERT INTO photo_galleries (title,active,nest_under,pub_pri,password,galorder,rdmcode,description,link,slideshow,pageflip,gallery_search_on,sort_by,sort_order,sort_on,free,monthly,yearly,photog_use) VALUES ('$title','$active','$nest','$pub_pri','$password','$galorder','$rdmcode','$article','$link','$slideshow','$pageflip','$gallery_search_on','$sort_by','$sort_order','$sort_on','$free','$monthly','$yearly','$photog_use')";
			$result = mysql_query($sql);
			
			$last_result = mysql_query("SELECT id FROM photo_galleries order by id desc", $db);
			$last = mysql_fetch_object($last_result);
			
			// UPLOAD Avatar
			if($_FILES['avatarFile']['name'] != ""){
				$ext = substr($_FILES['avatarFile']['name'],-3);
				upload_file_new($_FILES['avatarFile'],"av-$id.$ext","../gal_images/");
				if($file_result_code == 1)
				{					
					$sql = "UPDATE photo_galleries SET link='$file_details[1]' WHERE id = '$last->id'";
					$result = mysql_query($sql);
				}
			}
			
			// UPLOAD IMAGE
			if($image != ""){
				upload_image_g(
					$image,				  // name of form field to upload
					$image_name,		  // filename of the image
					$image_type,		  // type of image
					$image_path,          // directory to upload the image to
					"",				  // new width for your image / if blank doesn't resize
					"",				      // new height for your image (cropped) / if blank resizes with ratio
					"125",				  // icon width / if left blank no icon is created
					"95"				  // image quality
				);						  // returns $image_details[1] - $image_details[8]
										  // new image name(1),image type(2), image path(3), image bytes(4), image kb(5), width(6), heigth(7), time(8)
										  // $image_results returns results of upload
										  // $result_code returns 1,2,3 / results of upload... 1 Success, 2 Type Not Supported , 3 Empty
				if($result_code == 1){
					$added = date("Ymd");					
					$sql = "INSERT INTO uploaded_images (reference,reference_id,filename,photo_title,keywords,price,added) VALUES ('$reference','$last->id','$image_details[1]','$photo_title','$keywords','$price','$added')";
					$result = mysql_query($sql);
				}
			}
			header("location: " . $_POST['return']);

		break;
		
	/* DELETE ITEM(S) */	
		case "delete":
		$item_id = $_GET['item_id'];
		$result = mysql_query("SELECT id FROM photo_galleries", $db);
		while($rs = mysql_fetch_object($result)) {		
			if($_POST[$rs->id] == "1") {
				
				$item_id = $rs->id;
				//echo $item_id;
				//exit;
				
				// REASSIGN ANY PACKAGES THAT ARE ATTACHED TO THIS ITEM
				$result_package = mysql_query("SELECT * FROM photo_package where gallery_id = '$rs->id'", $db);
				while($rs_package = mysql_fetch_object($result_package)) {
					$sql2 = "UPDATE photo_package SET gallery_id='' WHERE id = '$rs_package->id'";
					$result2 = mysql_query($sql2);
				}
				
				$result_nest = mysql_query("SELECT id FROM photo_galleries where nest_under = '$item_id'", $db);
				while($rs_nest = mysql_fetch_object($result_nest)) {
					/*
					$result_package = mysql_query("SELECT * FROM photo_package where gallery_id = '$rs_nest->id'", $db);
					while($rs_package = mysql_fetch_object($result_package)) {
						$sql3 = "UPDATE photo_package SET gallery_id='' WHERE id = '$rs_package->id'";
						$result3 = mysql_query($sql3);
					}
					*/
					$sql2 = "UPDATE photo_galleries SET nest_under='0' WHERE id = '$rs_nest->id'";
					$result2 = mysql_query($sql2);
				}
					
				$sql="DELETE FROM photo_galleries WHERE id = '$rs->id'";
				$result2 = mysql_query($sql);
			}
		}
		header("location: " . $_POST['return']);
		exit;
		
	/* SAVE EDIT ITEM */	
		case "save_edit":
		//ADDED IN PS350 TO CLEANUP THE TITLE AND MORE
		$title = cleanup($title);
			$publish_date = $s_year . $s_month . $s_day;	
			$sql = "UPDATE photo_galleries SET title='$title',active='$active',nest_under='$nest',pub_pri='$pub_pri',password='$password',galorder='$galorder',rdmcode='$rdmcode',description='$article',slideshow='$slideshow',pageflip='$pageflip',gallery_search_on='$gallery_search_on',sort_by='$sort_by',sort_order='$sort_order',sort_on='$sort_on',free='$free',monthly='$monthly',yearly='$yearly',photog_use='$photog_use' WHERE id = '$item_id'";
			$result = mysql_query($sql);
			
			$x = 0;
			while($x < $i){
				$current_gallery = ${"gallery" . $x};
				$x2 = $x + 1;		
				$sql2 = "UPDATE photo_galleries SET display_order='$x2' WHERE id = '$current_gallery'";
				$result2 = mysql_query($sql2);
				//echo $current_gallery;
				$x++;
			}


			$avresult = mysql_query("SELECT link FROM photo_galleries WHERE id = '$item_id' AND link IS NOT NULL", $db);
			$av = mysql_fetch_object($avresult);

			// Clear out the previous avatar, or deleted it if requested
			if($deleteAv == "1" OR $_FILES['avatarFile']['name'] != ""){
				if($av->link){
					if(file_exists("../gal_images/$av->link")){
						unlink("../gal_images/$av->link");
					}
					$sql = "UPDATE photo_galleries SET link = NULL WHERE id = '$item_id'";
					$result = mysql_query($sql);
				}
			// UPLOAD Avatar
			if($_FILES['avatarFile']['name'] != ""){
				$ext = substr($_FILES['avatarFile']['name'],-3);
				//$avFileName = "av".time().".$ext";
				$avFileName = "av-". $id .".$ext";
				upload_file_new($_FILES['avatarFile'],$avFileName,"../gal_images/");
				if($file_result_code == 1){					
					$sql = "UPDATE photo_galleries SET link='$file_details[1]' WHERE id = '$item_id'";
					$result = mysql_query($sql);
				}
			}
		}
		
			// UPLOAD IMAGE
			if($image != ""){
				upload_image_g(
					$image,				 // name of form field to upload
					$image_name,		 // filename of the image
					$image_type,		 // type of image
					$image_path,         // directory to upload the image to
					"",				     // new width for your image / if blank doesn't resize
					"",				     // new height for your image (cropped) / if blank resizes with ratio
					"125",				 // icon width / if left blank no icon is created
					"95"				 // image quality
				);						 // returns $image_details[1] - $image_details[8]
										 // new image name(1),image type(2), image path(3), image bytes(4), image kb(5), width(6), heigth(7), time(8)
										 // $image_results returns results of upload
										 // $result_code returns 1,2,3 / results of upload... 1 Success, 2 Type Not Supported , 3 Empty
				if($result_code == 1){
					$added = date("Ymd");
					$sql = "INSERT INTO uploaded_images (reference,reference_id,filename,photo_title,keywords,price,added) VALUES ('$reference','$item_id','$image_details[1]','$photo_title','$keywords','$price','$added')";
					$result = mysql_query($sql);
				}
			}
			header("location: " . $_POST['return']);
		break;
		
	/* DELETE IMAGE */	
		case "delete_image":
		$result = mysql_query("SELECT id,filename FROM uploaded_images where id = '$id'", $db);
		$rs = mysql_fetch_object($result);
			unlink($default_image_path . $rs->filename);	
			unlink($default_image_path . "i_" . $rs->filename);
			unlink($default_image_path . "s_" . $rs->filename);
			
			$sql="DELETE FROM uploaded_images WHERE id = '$rs->id'";
			$result2 = mysql_query($sql);
			
		header("location: mgr.php?nav=" . $_GET['nav'] . "&item_id=" . $_GET['item_id']);
		exit;
		
	/* SET THUMBNAIL */	
		case "set_thumbnail":
		//echo $reference . "|" . $item_id . "|" . $gallery_thumbnail;
		
		$result = mysql_query("SELECT id,filename,thumbnail FROM uploaded_images where reference = '$reference' and reference_id = '$item_id'", $db);
		while($rs = mysql_fetch_object($result)){			
			if($gallery_thumbnail == $rs->id){
				$sql = "UPDATE uploaded_images SET thumbnail='1' WHERE id = '$rs->id'";
				$result2 = mysql_query($sql);
			}
			else{
				$sql = "UPDATE uploaded_images SET thumbnail='0' WHERE id = '$rs->id'";
				$result2 = mysql_query($sql);
			}
		}				
		header("location: " . $_POST['return']);
		exit;
		
	/* MOVE IMAGE UP/DOWN */	
		case "move_image":
		
		if($move == "up") {
			$result = mysql_query("SELECT id FROM uploaded_images WHERE display_order < " . $_GET['arrange'] . " ORDER BY arrange DESC");
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			$result = mysql_query("UPDATE test SET arrange = arrange + 1 WHERE test_id = " . $row["test_id"]);
			$result = mysql_query("UPDATE test SET arrange = arrange - 1 WHERE test_id = " . $_GET['id']);
		}
		if($move == "down") {
			$result = mysql_query("SELECT id FROM uploaded_images WHERE display_order > " . $_GET['arrange'] . " ORDER BY arrange");
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			$result = mysql_query("UPDATE uploaded_images SET arrange = arrange - 1 WHERE test_id = " . $row["test_id"]);
			$result = mysql_query("UPDATE uploaded_images SET arrange = arrange + 1 WHERE test_id = " . $_GET['id']);
		}
		header("location: photo_galleries_iframe.php?item_id=" . $item_id . "&reference=" . $reference . "&image_path=" . $image_path . "&image_upload=" . $image_upload);
		exit;

/*-----------------------------------------------------------------------------------------------------------------------*/
/*                                                 SAVE PHOTO DETAILS                                                    */
/*-----------------------------------------------------------------------------------------------------------------------*/	

	/* UPDATE IMAGE DETAILS */	
		case "update_image_details":
		// Register globals bypass
		$price = $_POST['price'];
		$quality = $_POST['quality'];
		$quality_order = $_POST['quality_order'];
		$price_contact = $_POST['price_contact'];
		//ADDED IN PS350 TO CLEANUP DATA
		$price = price_cleanup($price);
		//SAVE DATA TO DATABASE
			$sql = "UPDATE uploaded_images SET price='$price',quality='$quality',quality_order='$quality_order',price_contact='$price_contact' WHERE id = " . $_POST['id'];
			$result = mysql_query($sql);					
		header("location: " . $_POST['return'] . "&image_path=" . $_POST['image_path']);
		exit;

/*-----------------------------------------------------------------------------------------------------------------------*/
/*                                                      DEFAULT                                                          */
/*-----------------------------------------------------------------------------------------------------------------------*/	
		default:
			header("location: login.php");
			exit;
		break;
	}
?>
