<?PHP
	include("../session_include.php");
	include( "check_login_status.php" );
	include( "config_mgr.php" );
	
	//SECURITY CHECKS, CHECK FOR DEMO USER FIRST AND SHOW A FRIENDLY MESSAGE OR ELSE SHOW A DIFFERENT MESSAGE
	if($_SESSION['access_type'] == "demo"){
	  echo "Sorry but you can not use this feature in demo mode.";
	  exit;
	}
	if($_SESSION['access_type'] != "mgr"){
	  echo "You do not have permission to look at this file!"; 
	  exit; 
	}
	
	$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
	
	//$file_path = "../uploaded_files/";
	//$image_path = "../uploaded_images/";
	
	switch($_REQUEST['pmode']){

/*-----------------------------------------------------------------------------------------------------------------------*/
/*                                                    PLUGIN ACTIONS                                                     */
/*-----------------------------------------------------------------------------------------------------------------------*/
	/* SAVE NEW ITEM */	
		case "save_new":
			
			foreach($_POST as $key => $value){				
				if(is_array($value)){
					foreach($value as $key2 => $value2){
						//${$key}[$key2] = addslashes($value2);
						${$key}[$key2] = quote_smart($value2);								
					}	
				} else {
					${$key} = quote_smart($value);
				}
			}
			
			if($country == ""){
				$country = $country_display;
			}			
		
			//CORRECT THE URL AS NEEDED
			if($url != ""){
				$replace_char = array("http://");
				$url = str_replace($replace_char, "", $url);
				$url = "http://".$url;
			}
			
			// SAVE DATA
			$email = strtolower($email);
			$password = strtolower($password);
			$publish_date = $s_year . $s_month . $s_day;
			
			//CHECK TO SEE IF THE EMAIL OR DISPLAY NAME IS ALREADY IN USE
			//SET VARIABLES
			$same_email = 0;
			$same_display_name = 0;
			$error = "";
			//EMAIL CHECK
				$check1_name = mysql_query("SELECT email FROM photographers where email = '$email'", $db);
				$check1_rows = mysql_num_rows($check1_name);
				if($check1_rows > 0){
					$same_email = 1;
					$error = "email";
				}
			//DISPLAY CHECK
				$check2_name = mysql_query("SELECT display_name FROM photographers where display_name = '$display_name'", $db);
				$check2_rows = mysql_num_rows($check2_name);
				if($check2_rows > 0){
					$same_display_name = 1;
					$error = "display_name";
				}
			//RETURN ERROR IF ANY
			if($same_email > 0 or $same_display_name > 0){
				header("location:" . $return."&error=$error&item_id=new");
				exit;
			}
			
			$sql = "INSERT INTO photographers (name,display_name,password,email,phone,address1,address2,city,state,zip,country,notes,status,added,com_percent,approved,featured,upload_on,edit_on,com_download,com_download_default,payment_type,paypal_email,url) VALUES ('$name','$display_name','$password','$email','$phone','$address1','$address2','$city','$state','$zip','$country','$article','$status','$publish_date','$com_percent','$approved','$featured','$upload_on','$edit_on','$com_download','$com_download_default','$payment','$paypal','$url')";
			$result = mysql_query($sql);
			
			$last_result = mysql_query("SELECT id FROM photographers order by id desc", $db);
			$last = mysql_fetch_object($last_result);
			
			//ADDED IN PS350 TO CREATE FOLDER FOR PHOTOGRAPHER FOR TEMP STORAGE OF THE PHOTOS THEY BATCH UPLOAD
			//POINT THIS TO YOUR PHOTOGRAPHERS UPLOAD DIR "photog_upload/" IS DEFAULT
			$upload_dir = "../".$setting->photog_dir."/";
			//CREATE A DIR FOR PHOTOGRAPHER
			$new_path = $upload_dir . $last->id;
			if(!is_dir($upload_dir . $last->id)){
				mkdir($new_path, 0777);
				chmod($new_path, 0777);
			}
			
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
				
				if($result_code == 1){						
					$sql = "INSERT INTO uploaded_images (reference,reference_id,filename,caption) VALUES ('$_POST[reference]','$last->id','$image_details[1]','$_POST[image_caption]')";
					$result = mysql_query($sql);
				}
			}		
			
			header("location:" . $_POST['return']);

		break;
		
	/* DELETE ITEM(S) */	
		case "delete":
		$result = mysql_query("SELECT id FROM photographers", $db);
		while($rs = mysql_fetch_object($result)){
			if($_POST[$rs->id] == "1"){
				// DELETE ANY FILES THAT ARE ATTACHED TO THIS ITEM
				$result_file = mysql_query("SELECT id,filename FROM uploaded_files where reference = '$reference' and reference_id = '$rs->id'", $db);
				while($rs_file = mysql_fetch_object($result_file)){
					unlink($file_path . $rs_file->filename);
					$sql="DELETE FROM uploaded_files WHERE id = '$rs_file->id'";
					$result2 = mysql_query($sql);
				}
				// DELETE ANY IMAGES THAT ARE ATTACHED TO THIS ITEM
				$result_image = mysql_query("SELECT id,filename FROM uploaded_images where reference = '$reference' and reference_id = '$rs->id'", $db);
				while($rs_image = mysql_fetch_object($result_image)){
					if(is_file($image_path . $rs_image->filename)){
						unlink($image_path . $rs_image->filename);
					}
					if(is_file($image_path . "i_" . $rs_image->filename)){
						unlink($image_path . "i_" . $rs_image->filename);
					}
					if(is_file($image_path . "s_" . $rs_image->filename)){
						unlink($image_path . "s_" . $rs_image->filename);
					}
					if(is_file($image_path . "m_" . $rs_image->filename)){
						unlink($image_path . "m_" . $rs_image->filename);
					}
					$sql="DELETE FROM uploaded_images WHERE id = '$rs_image->id'";
					$result2 = mysql_query($sql);
				}
				
				// DELETE ANY IMAGES UPLOADED BY THIS PHOTOGRAPHER
				if($delete_photog_photos == 1){
				$result_upload = mysql_query("SELECT id FROM photo_package where photographer = '$rs->id'", $db);
				while($rs_upload = mysql_fetch_object($result_upload)){
					$result_delete = mysql_query("SELECT id,filename FROM uploaded_images where reference_id = '$rs_upload->id'", $db);
					while($del = mysql_fetch_object($result_delete)){
					if(is_file($stock_photos_path_manager . $del->filename)){
						unlink($stock_photos_path_manager . $del->filename);
					}
					if(is_file($stock_photos_path_manager . "i_" . $del->filename)){
						unlink($stock_photos_path_manager . "i_" . $del->filename);
					}
					if(is_file($stock_photos_path_manager . "s_" . $del->filename)){
						unlink($stock_photos_path_manager . "s_" . $del->filename);
					}
					if(is_file($stock_photos_path_manager . "m_" . $del->filename)){
						unlink($stock_photos_path_manager . "m_" . $del->filename);
					}
					$sql="DELETE FROM uploaded_images WHERE id = '$del->id'";
					$result2 = mysql_query($sql);
					}
					$sql="DELETE FROM photo_package WHERE id = '$rs_upload->id'";
					$result2 = mysql_query($sql);
				}
			}
				
					//CHECK TEMP DIRECTORY AND DELETE ALL FILES AND FOLDER
					if(is_dir("../".$setting->photog_dir."/".$rs->id)){
					$filepath = "../".$setting->photog_dir."/".$rs->id;
					// FIND THE FILES THAT ARE LEFT IN THE FOLDER AND DELETE THEM
					if($handle = opendir($filepath)){
						while (false !== ($file = readdir($handle))){
							if(is_file($filepath."/".$file)){
								unlink($filepath."/".$file);
							}
						}
						closedir($handle);
					}
					//NOW DELETE THE FOLDER
				  rmdir($filepath);
				}
				 	//NOW DELETE THE ACTUAL PHOTOGRAPHER ENTRY
				$sql="DELETE FROM photographers WHERE id = '$rs->id'";
				$result2 = mysql_query($sql);
			}
		}
		header("location:" . $_POST['return']);
		exit;
		
	/* SAVE EDIT ITEM */	
		case "save_edit":
			# MAGIC QUOTES FIX
			foreach($_POST as $key => $value){				
				if(is_array($value)){
					foreach($value as $key2 => $value2){
						//${$key}[$key2] = addslashes($value2);
						${$key}[$key2] = quote_smart($value2);								
					}	
				} else {
					${$key} = quote_smart($value);
				}
			}
			
			if($country == ""){
				$country = $country_display;
			}			
			
			//ADDED IN PS350 TO DEACTIVATE ALL PHOTOG PHOTOS WHEN A PHOTOG IS DEACTIVATED
			if($status == 0){
				$deactivate = mysql_query("SELECT id FROM photo_package where photographer = '$item_id'", $db);
				while($rs = mysql_fetch_object($deactivate)){
					$sql = "UPDATE photo_package SET photog_show='$status' WHERE id = '$rs->id'";
					$result = mysql_query($sql);
				}
			}
			//ADDED IN PS350 TO REACTIVATE ALL PHOTOG PHOTOS WHEN A PHOTOG IS ACTIVE
			if($status == 1){
				$activate = mysql_query("SELECT id FROM photo_package where photographer = '$item_id'", $db);
				while($rs = mysql_fetch_object($activate)){
					$sql = "UPDATE photo_package SET photog_show='$status' WHERE id = '$rs->id'";
					$result = mysql_query($sql);
				}
			}
			
			//CORRECT THE URL AS NEEDED
			if($url != ""){
				$replace_char = array("http://");
				$url = str_replace($replace_char, "", $url);
				$url = "http://".$url;
			}
			
			//CHECK TO SEE IF THE EMAIL OR DISPLAY NAME IS ALREADY IN USE
			$check_name = mysql_query("SELECT email,display_name FROM photographers where id = '$item_id'", $db);
			$check = mysql_fetch_object($check_name);
			//SET VARIABLES
			$same_email = 0;
			$same_display_name = 0;
			$error = "";
			//EMAIL CHECK
			if($check->email != $email){
				$check1_name = mysql_query("SELECT email FROM photographers where email = '$email'", $db);
				$check1_rows = mysql_num_rows($check1_name);
				if($check1_rows > 0){
					$same_email = 1;
					$error = "email";
				}
			}
			//DISPLAY CHECK
			if($check->display_name != $display_name){
				$check2_name = mysql_query("SELECT display_name FROM photographers where display_name = '$display_name'", $db);
				$check2_rows = mysql_num_rows($check2_name);
				if($check2_rows > 0){
					$same_display_name = 1;
					$error = "display_name";
				}
			}
			//RETURN ERROR IF ANY
			if($same_email > 0 or $same_display_name > 0){
				header("location:" . $return."&error=$error");
				exit;
			}
			
			$email = strtolower($email);
			$password = strtolower($password);
			$publish_date = $s_year . $s_month . $s_day;
			$sql = "UPDATE photographers SET name='$name',display_name='$display_name',password='$password',email='$email',phone='$phone',address1='$address1',address2='$address2',city='$city',state='$state',zip='$zip',country='$country',notes='$article',status='$status',added='$publish_date',com_percent='$com_percent',approved='$approved',featured='$featured',upload_on='$upload_on',edit_on='$edit_on',com_download='$com_download',com_download_default='$com_download_default',payment_type='$payment',paypal_email='$paypal',url='$url' WHERE id = '$item_id'";
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
				
				//$iname = $_FILES['image']['name'];
				//echo $result_code; exit;
				
				//echo $_POST['reference'] . "<br />" . $_POST['item_id'] . "<br />" . $image_details[1] . "<br />" . $_POST['image_caption']; 
				if($result_code == 1){						
					$sql = "INSERT INTO uploaded_images (reference,reference_id,filename,caption) VALUES ('$_POST[reference]','$_POST[item_id]','$image_details[1]','$_POST[image_caption]')";
					$result = mysql_query($sql);
				}
			}
			
			header("location:" . $return);
		break;
		
		
		case "update_com":
			$sql = "UPDATE settings SET com_level='$_POST[com_level]' WHERE id = '1'";
			$result = mysql_query($sql);
			header("location:" . $return);
		break;
		
/* MARK HIDE PHOTOG_SALES & DOWNLOADS */
	case "mark_hide":
		$hide = mysql_query("SELECT * FROM photog_sales", $db);
		while($rs_hide = mysql_fetch_object($hide)){
			if($_POST[$rs_hide->id] == "1"){
			$sql = "UPDATE photog_sales SET done='1' WHERE id = '$rs_hide->id'";
			$result_hide = mysql_query($sql);
			}
		}
	header("location: " . $return);
	break;
	
/* MARK HIDE PHOTOG_UPLOADS */
	case "mark_hide_upload":
		$hide = mysql_query("SELECT * FROM photog_earning", $db);
		while($rs_hide = mysql_fetch_object($hide)){
			if($_POST[$rs_hide->id] == "1"){
			$sql = "UPDATE photog_earning SET done='1' WHERE id = '$rs_hide->id'";
			$result_hide = mysql_query($sql);
			}
		}
	header("location: " . $return);
	break;

/* DELETE PHOTOG_SALES (ALL CHECKED ITEMS)*/
	case "delete_sales":
		$result = mysql_query("SELECT id FROM photog_sales", $db);
		while($rs = mysql_fetch_object($result)) {		
			if($_POST[$rs->id] == "1") {
				$sql="DELETE FROM photog_sales WHERE id = '$rs->id'";
				$result2 = mysql_query($sql);
			}
		}
		header("location: " . $return);
		break;

/* DELETE PHOTOG_DOWNLOADS (ALL CHECKED ITEMS)*/
	case "delete_download":
		$result = mysql_query("SELECT id FROM photog_sales", $db);
		while($rs = mysql_fetch_object($result)) {		
			if($_POST[$rs->id] == "1") {
				$sql="DELETE FROM photog_sales WHERE id = '$rs->id'";
				$result2 = mysql_query($sql);
			}
		}
		header("location: " . $return);
		break;
		
/* DELETE PHOTOG_UPLOADS (ALL CHECK ITEMS)*/
	case "delete_upload":
		$result = mysql_query("SELECT id FROM photog_earning", $db);
		while($rs = mysql_fetch_object($result)) {		
			if($_POST[$rs->id] == "1") {
				$sql="DELETE FROM photog_earning WHERE id = '$rs->id'";
				$result2 = mysql_query($sql);
			}
		}
		header("location: " . $return);
		break;
		
/* MARK PAID NON SUBSCRIBER SALES SINGLE ITEM AT A TIME*/		
		case "paid_status":
			$return = "mgr.php?nav=" . $nav . "&item_id=" . $item_id;
			$sql = "UPDATE photog_sales SET status='1' WHERE id = '$comid'";
			$result = mysql_query($sql);
			header("location: $return");
			exit;
		break;
		
/* MARK PAID PHOTOG_SALES & DOWNLOADS (ALL CHECKED ITEMS)*/
	case "mark_paid":
		$paid = mysql_query("SELECT * FROM photog_sales", $db);
		while($rs_paid = mysql_fetch_object($paid)){
			if($_POST[$rs_paid->id] == "1"){
			$sql = "UPDATE photog_sales SET status='1' WHERE id = '$rs_paid->id'";
			$result_paid = mysql_query($sql);
			}
		}
	header("location: " . $return);
	break;
		
/* MARK PAID PHOTOG_UPLOADS (ALL CHECKED ITEMS)*/
	case "mark_paid_upload":
		$paid = mysql_query("SELECT * FROM photog_earning", $db);
		while($rs_paid = mysql_fetch_object($paid)){
			if($_POST[$rs_paid->id] == "1"){
			$sql = "UPDATE photog_earning SET status='1' WHERE id = '$rs_paid->id'";
			$result_paid = mysql_query($sql);
			}
		}
	header("location: " . $return);
	break;
		
/* MARK PAID UPLOAD SINGLE ITEM AT A TIME */
		case "mark_paid_upload_single":
			$return = "mgr.php?nav=" . $nav . "&item_id=" . $item_id;
			$sql = "UPDATE photog_earning SET status='1' WHERE id = '$comid'";
			$result = mysql_query($sql);
			header("location: $return");
			break;
			
/* HIDE DOWNLOAD SINGLE ITEM AT A TIME */
		case "delete_down":
			$return = "mgr.php?nav=" . $nav . "&item_id=" . $item_id;
			$sql = "UPDATE photog_sales SET done='1' WHERE id = '$comid'";
			$result = mysql_query($sql);
			header("location:" . $return);
		break;
		
/* HIDE UPLOAD SINGLE */
		case "mark_hide_upload_single":
			$return = "mgr.php?nav=" . $nav . "&item_id=" . $item_id;
			$sql = "UPDATE photog_earning SET done='1' WHERE id = '$comid'";
			$result = mysql_query($sql);
		header("location:" . $return);
		break;
		
/* UPDATE DOWNLOAD COMMISSION */
		case "update_download_com":
		  $com_download_default = $com_download_default;
		  $com_download = $com_download;
		  $return = $return;
			$sql = "UPDATE settings SET com_download='$com_download',com_download_default='$com_download_default' WHERE id = '1'";
			$result = mysql_query($sql);
		header("location:" . $return);
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