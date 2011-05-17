<?php
	session_start();
	include( "check_login_status.php" );
	
	// Photographers ACTIONS - UPDATED 6.30.05
	
	include( "config_mgr.php" );
	
	if($_SESSION['access_type'] != "mgr"){ echo "Operation cannot be performed in demo mode"; exit; }
	
	$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
	
	//$file_path = "../uploaded_files/";
	//$image_path = "../uploaded_images/";
	
	switch($_GET['pmode']){

/*-----------------------------------------------------------------------------------------------------------------------*/
/*                                                    PLUGIN ACTIONS                                                     */
/*-----------------------------------------------------------------------------------------------------------------------*/
	/* SAVE NEW ITEM */	
		case "save_new":
			/*
			$name = $_POST['name'];
			$password = $_POST['password'];
			$email = $_POST['email'];
			$article = $_POST['article'];
			$status = $_POST['status'];
		 	$return = $_POST['return'];
		 	$s_year = $_POST['s_year'];
			$s_month = $_POST['s_month'];
			$s_day = $_POST['s_day'];
			*/
			
			
			// REPLACE BAD CHARACTERS IN TITLE
			//$new_title = $title;
			//$new_title = str_replace("\"", "", $new_title);
			//$new_title = str_replace("\'", "", $new_title);
			//$title = $new_title;	
		
			// SAVE DATA
			$email = strtolower($email);
			$password = strtolower($password);
			$publish_date = $s_year . $s_month . $s_day;
			$sql = "INSERT INTO photographers (name,password,email,notes,status,added) VALUES ('$name','$password','$email','$article','$status','$publish_date')";
			$result = mysql_query($sql);
			
			$last_result = mysql_query("SELECT id FROM photographers order by id desc", $db);
			$last = mysql_fetch_object($last_result);
			
			// UPLOAD FILE
			if($fileup != ""){
				upload_file($fileup,$fileup_name,$file_path);
				
				if($file_result_code == 1){
					$sql = "INSERT INTO uploaded_files (reference,reference_id,filename,file_text) VALUES ('$reference','$last->id','$file_details[1]','$file_text')";
					$result = mysql_query($sql);
				}
			}				
			
			// UPLOAD IMAGE
			if($image != ""){
				upload_image(
					$image,				  // name of form field to upload
					$image_name,		  // filename of the image
					$image_type,		  // type of image
					$image_path,          // directory to upload the image to
					"300",				  // new width for your image / if blank doesn't resize
					"",				      // new height for your image (cropped) / if blank resizes with ratio
					"150",				  // icon width / if left blank no icon is created
					"80"				  // image quality
				);						  // returns $image_details[1] - $image_details[8]
										  // new image name(1),image type(2), image path(3), image bytes(4), image kb(5), width(6), heigth(7), time(8)
										  // $image_results returns results of upload
										  // $result_code returns 1,2,3 / results of upload... 1 Success, 2 Type Not Supported , 3 Empty
				if($result_code == 1){						
					$sql = "INSERT INTO uploaded_images (reference,reference_id,filename,caption) VALUES ('$reference','$last->id','$image_details[1]','$image_caption')";
					$result = mysql_query($sql);
				}
			}
			header("location: " . $return);

		break;
		
	/* DELETE ITEM(S) */	
		case "delete":
		$result = mysql_query("SELECT id FROM photographers", $db);
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
				
				$sql="DELETE FROM photographers WHERE id = '$rs->id'";
				$result2 = mysql_query($sql);
			}
		}
		header("location: " . $_POST['return']);
		exit;
		
	/* SAVE EDIT ITEM */	
		case "save_edit":
			/*
			$name = $_POST['name'];
			$password = $_POST['password'];
			$email = $_POST['email'];
			$article = $_POST['article'];
			$status = $_POST['status'];
		  	$return = $_POST['return'];
		  	$item_id = $_POST['item_id'];
		  	$s_year = $_POST['s_year'];
			$s_month = $_POST['s_month'];
			$s_day = $_POST['s_day'];
			*/
			
			
			
			$email = strtolower($email);
			$password = strtolower($password);
			$publish_date = $s_year . $s_month . $s_day;
			$sql = "UPDATE photographers SET name='$name',password='$password',email='$email',notes='$article',status='$status',added='$publish_date' WHERE id = '$item_id'";
			$result = mysql_query($sql);
			
			/*
			// UPLOAD FILE			
			if($fileup_size > 0){
				
				$new_filename = $fileup_name;
				$new_filename = str_replace(" ", "_", $new_filename);
				
				if(!file_exists($file_path . $new_filename)) {
					$new_filename = $new_filename;
				}
				else {
					// FILE EXISTS - RENAME (RENAME IN ORDER / myfile_1.ext, myfile_2.ext, etc.)
					$filename_array = split("\.", $new_filename);
					$array_count = count($filename_array);
					
					$x_count2 = 0;
					while($x_count2 < $array_count - 1){ // 5
						if($x_count2 != 0){
							// IF THERE ARE PERIODS ADD THEM ONLY AFTER THE FIRST WORD
							$new_filename2 = $new_filename2 . "." . $filename_array[$x_count2];
						}
						else {
							$new_filename2 = $new_filename2 . $filename_array[$x_count2];
						}
						$x_count2++;
					}
					$x_count3 = 1;
					while(file_exists($file_path . $new_filename)) {
						$new_filename = $new_filename2 . "_" . $x_count3 . "." . $filename_array[$x_count2];
						$x_count3++;	
					}						
				}
				
				copy($fileup, $file_path . $new_filename);
				
				$sql = "INSERT INTO uploaded_files (reference,reference_id,filename,file_text) VALUES ('news','$item_id','$new_filename','$file_text')";
				$result = mysql_query($sql);
				
				
			}
			*/
			
			/*
			// UPLOAD FILE
			upload_file($fileup,$fileup_name,$file_path);
			
			if($file_result_code == 1){
				$sql = "INSERT INTO uploaded_files (reference,reference_id,filename,file_text) VALUES ('$reference','$item_id','$file_details[1]','$file_text')";
				$result = mysql_query($sql);
			}
			*/
			
			// UPLOAD FILE
			if($fileup != ""){
				upload_file($fileup,$fileup_name,$file_path);
				
				if($file_result_code == 1){
					$sql = "INSERT INTO uploaded_files (reference,reference_id,filename,file_text) VALUES ('$reference','$item_id','$file_details[1]','$file_text')";
					$result = mysql_query($sql);
				}
			}			
			
			// UPLOAD IMAGE
			if($image != ""){
				upload_image(
					$image,				 // name of form field to upload
					$image_name,		 // filename of the image
					$image_type,		 // type of image
					$image_path,         // directory to upload the image to
					"300",				 // new width for your image / if blank doesn't resize
					"",				     // new height for your image (cropped) / if blank resizes with ratio
					"150",				 // icon width / if left blank no icon is created
					"80"				 // image quality
				);						 // returns $image_details[1] - $image_details[8]
										 // new image name(1),image type(2), image path(3), image bytes(4), image kb(5), width(6), heigth(7), time(8)
										 // $image_results returns results of upload
										 // $result_code returns 1,2,3 / results of upload... 1 Success, 2 Type Not Supported , 3 Empty
				if($result_code == 1){
					$sql = "INSERT INTO uploaded_images (reference,reference_id,filename,caption) VALUES ('$reference','$item_id','$image_details[1]','$image_caption')";
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