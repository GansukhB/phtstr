<?php
	session_start();
	include( "check_login_status.php" );
	
	// SIZES ACTIONS
	
	include( "config_mgr.php" );
	
	if($_SESSION['access_type'] != "mgr"){ echo "Operation cannot be performed in demo mode"; exit; }
	
	$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
	
	//$file_path = "../uploaded_files/";
	//$image_path = "../uploaded_images/";
	
	switch($_GET['pmode']){

/*-----------------------------------------------------------------------------------------------------------------------*/
/*                                                PLUGIN ACTIONS                                                    */
/*-----------------------------------------------------------------------------------------------------------------------*/
	/* SAVE NEW ITEM */	
		case "save_new":
		
			# MAGIC QUOTES FIX
			foreach($_POST as $key => $value){
				if(!get_magic_quotes_gpc()){
					if(is_array($value)){
						foreach($value as $key2 => $value2){
							//${$key}[$key2] = addslashes($value2);
							${$key}[$key2] = mysql_real_escape_string($value2);								
						}	
					} else {
						//${$key} = addslashes($value);
						${$key} = mysql_real_escape_string($value);
					}
				} else {
					${$key} = $value;
				}
			}
			//ADDED IN PS350 TO CLEANUP DATA ENTRY
			$name = cleanup($name);
			$price = price_cleanup($price);
			// SAVE DATA
			$publish_date = $s_year . $s_month . $s_day;
			$sql = "INSERT INTO sizes (name,size,article,sorder,price,visible) VALUES ('$name','$size','$article','$sorder','$price','$visible')";
			$result = mysql_query($sql);
			
			$last_result = mysql_query("SELECT id FROM sizes order by id desc", $db);
			$last = mysql_fetch_object($last_result);
			
			// UPLOAD FILE
			if($_FILES['fileup']['name'] != ""){
				
				//echo $_FILES['fileup']['name']; exit;
				
				upload_file_new($_FILES['fileup'],$_FILES['fileup']['name'],"../uploaded_files/");
				
				$fname = $_FILES['fileup']['name'];
				//echo $fname; exit;
				
				$sql = "INSERT INTO uploaded_files (reference,reference_id,filename,file_text) VALUES ('$_POST[reference]','$last->id','$fname','$_POST[file_text]')";
				$result = mysql_query($sql);

			}
			
		// UPLOAD IMAGE
			if($_FILES['image']['name'] != ""){
				upload_image(
					$_FILES['image'],				  // name of form field to upload
					$_FILES['image']['name'],		  // filename of the image
					$_FILES['image']['type'],		  // type of image
					$_POST['image_path'],          // directory to upload the image to
					"",				  // new width for your image / if blank doesn't resize
					"",				      // new height for your image (cropped) / if blank resizes with ratio
					"125",				  // icon width / if left blank no icon is created
					"100"				  // image quality
				);						  // returns $image_details[1] - $image_details[8]
										  // new image name(1),image type(2), image path(3), image bytes(4), image kb(5), width(6), heigth(7), time(8)
										  // $image_results returns results of upload
										  // $result_code returns 1,2,3 / results of upload... 1 Success, 2 Type Not Supported , 3 Empty
				
				//$iname = $_FILES['image']['name'];
				//echo $result_code; exit;
				
				//echo $_POST['reference'] . "<br />" . $_POST['item_id'] . "<br />" . $image_details[1] . "<br />" . $_POST['image_caption']; 
				if($result_code == 1){						
					$sql = "INSERT INTO uploaded_images (reference,reference_id,filename,caption) VALUES ('$_POST[reference]','$last->id','$image_details[1]','$_POST[image_caption]')";
					$result = mysql_query($sql);
				}
			}		
			header("location: " . $return);

		break;
		
	/* DELETE ITEM(S) */	
		case "delete":
		$result = mysql_query("SELECT id FROM sizes", $db);
		while($rs = mysql_fetch_object($result)) {		
			if($_POST[$rs->id] == "1") {
				// DELETE ANY FILES THAT ARE ATTACHED TO THIS ITEM
				$result_file = mysql_query("SELECT id,filename FROM uploaded_files where reference = '$reference' and reference_id = '$rs->id'", $db);
				while($rs_file = mysql_fetch_object($result_file)) {
					unlink($file_path . $rs_file->filename);
					
					$sql="DELETE FROM uploaded_files WHERE id = '$rs_file->id'";
					$result2 = mysql_query($sql);
						
					//unlink("../images_news/i_" . $rs->image);
				}
				// DELETE ANY IMAGES THAT ARE ATTACHED TO THIS ITEM
				$result_image = mysql_query("SELECT id,filename FROM uploaded_images where reference = '$reference' and reference_id = '$rs->id'", $db);
				while($rs_image = mysql_fetch_object($result_image)) {
					unlink($image_path . $rs_image->filename);
					unlink($image_path . "i_" . $rs_image->filename);
					
					$sql="DELETE FROM uploaded_images WHERE id = '$rs_image->id'";
					$result2 = mysql_query($sql);
				}
				
				$sql="DELETE FROM sizes WHERE id = '$rs->id'";
				$result2 = mysql_query($sql);
			}
		}
		header("location: " . $_POST['return']);
		exit;
		
	/* SAVE EDIT ITEM */	
		case "save_edit":
	
			# MAGIC QUOTES FIX
			foreach($_POST as $key => $value){
				if(!get_magic_quotes_gpc()){
					if(is_array($value)){
						foreach($value as $key2 => $value2){
							//${$key}[$key2] = addslashes($value2);
							${$key}[$key2] = mysql_real_escape_string($value2);								
						}	
					} else {
						//${$key} = addslashes($value);
						${$key} = mysql_real_escape_string($value);
					}
				} else {
					${$key} = $value;
				}
			}
			
			//ADDED IN PS350 TO CLEANUP DATA ENTRY
			$name = cleanup($name);
			$price = price_cleanup($price);
			//SAVE EDITED DATA
			$publish_date = $s_year . $s_month . $s_day;	
			$sql = "UPDATE sizes SET name='$name',size='$size',article='$article',sorder='$sorder',price='$price',visible='$visible' WHERE id = '$item_id'";
			$result = mysql_query($sql);
			
			// UPLOAD FILE
			if($_FILES['fileup']['name'] != ""){
				
				//echo $_FILES['fileup']['name']; exit;
				
				upload_file_new($_FILES['fileup'],$_FILES['fileup']['name'],"../uploaded_files/");
				
				$fname = $_FILES['fileup']['name'];
				//echo $fname; exit;
				
				$sql = "INSERT INTO uploaded_files (reference,reference_id,filename,file_text) VALUES ('$_POST[reference]','$_POST[item_id]','$fname','$_POST[file_text]')";
				$result = mysql_query($sql);

			}
			
		// UPLOAD IMAGE
			if($_FILES['image']['name'] != ""){
				upload_image(
					$_FILES['image'],				  // name of form field to upload
					$_FILES['image']['name'],		  // filename of the image
					$_FILES['image']['type'],		  // type of image
					$_POST['image_path'],          // directory to upload the image to
					"",				  // new width for your image / if blank doesn't resize
					"",				      // new height for your image (cropped) / if blank resizes with ratio
					"125",				  // icon width / if left blank no icon is created
					"100"				  // image quality
				);						  // returns $image_details[1] - $image_details[8]
										  // new image name(1),image type(2), image path(3), image bytes(4), image kb(5), width(6), heigth(7), time(8)
										  // $image_results returns results of upload
										  // $result_code returns 1,2,3 / results of upload... 1 Success, 2 Type Not Supported , 3 Empty
				
				$iname = $_FILES['image']['name'];
				//echo $result_code; exit;
				
				//echo $_POST['reference'] . "<br />" . $_POST['item_id'] . "<br />" . $image_details[1] . "<br />" . $_POST['image_caption']; 
				//exit;
				if($result_code == 1){						
					$sql = "INSERT INTO uploaded_images (reference,reference_id,filename,caption) VALUES ('$_POST[reference]','$_POST[item_id]','$image_details[1]','$_POST[image_caption]')";
					$result = mysql_query($sql);
				}
			}
			header("location: " . $return);
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