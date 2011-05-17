<?php
	session_start();
	include( "check_login_status.php" );
	
	// PRINTS ACTIONS
	
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
			$name = $_POST['name'];
			$porder = $_POST['porder'];
			$price = $_POST['price'];
			$return = $_POST['return'];
			$s_year = $_POST['s_year'];
			$s_month = $_POST['s_month'];
			$s_day = $_POST['s_day'];
			
			// REPLACE BAD CHARACTERS IN TITLE
			$new_title = $title;
			$new_title = str_replace("\"", "", $new_title);
			$new_title = str_replace("\'", "", $new_title);
			$title = $new_title;	
		
			// SAVE DATA
			$publish_date = $s_year . $s_month . $s_day;
			$sql = "INSERT INTO prints (name,porder,price) VALUES ('$name','$porder','$price')";
			$result = mysql_query($sql);
			
			$last_result = mysql_query("SELECT id FROM prints order by id desc", $db);
			$last = mysql_fetch_object($last_result);
			
			/*
			// UPLOAD FILE
			upload_file($fileup,$fileup_name,$file_path);
			
			if($file_result_code == 1){
				$sql = "INSERT INTO uploaded_files (reference,reference_id,filename,file_text) VALUES ('$reference','$last->id','$file_details[1]','$file_text')";
				$result = mysql_query($sql);
			}
			*/
			
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
		$result = mysql_query("SELECT id FROM visitors", $db);
		while($rs = mysql_fetch_object($result)) {		
			if($_POST[$rs->id] == "1") {
				
				$sql="UPDATE visitors SET hide='1' WHERE id = '$rs->id'";
				$result2 = mysql_query($sql);
			}
		}
		header("location: " . $_POST['return']);
		exit;
		break;
	
	/* Check/Money Order Approval */
	case "check_approval":
	  $return = $_GET['return'];
	  $id = $_GET['item_id'];
	  $message1 = $_GET['message'];
	  
		$sql = "UPDATE visitors SET done='1' WHERE id = '$id'";
		$result = mysql_query($sql);
		
		$visitor_result = mysql_query("SELECT * FROM visitors where id = '$id'", $db);
		$visitor = mysql_fetch_object($visitor_result);
		$order_num = $visitor->order_num;
		$link = "/download.php?order=";
		$to = $visitor->paypal_email;
		email(11,$to);
		header("location: " . $return . "&item_id=" . $id . "&message=" . $message1);
		exit;
		break;
		
		/* Tracking Number Save */
	case "track_save":
		$sql = "UPDATE visitors SET ups='" . $_POST['ups'] . "',fedex='" . $_POST['fedex'] . "',dhl='" . $_POST['dhl'] . "',track='" . $_POST['track'] . "' WHERE id = '" . $_POST['tracking'] . "'";
		$result = mysql_query($sql);
		header("location: " . $return . "&item_id=" . $tracking . "&message=" . $message);
		exit;
		break;
		
	/* STATUS */	
		case "status":
			$email = $_POST['email'];
			$return = "mgr.php?nav=$_POST[nav]";
			$sql = "UPDATE visitors SET paypal_email='$email',status='1' WHERE id = '$id'";
			$result = mysql_query($sql);
			
			// GET VISITOR INFORMATION
			$visitor_result = mysql_query("SELECT * FROM visitors where id = '$id'", $db);
			$visitor_rows = mysql_num_rows($visitor_result);
			$visitor = mysql_fetch_object($visitor_result);
			
			$coupon_result = mysql_query("SELECT * FROM coupon where id = '$visitor->coupon_id'", $db);
			$coupon_rows = mysql_num_rows($coupon_result);
			$coupon = mysql_fetch_object($coupon_result);

			if($coupon_rows > 0){
			$used = $coupon->used + 1;
			$sql = "UPDATE coupon SET used='$used' WHERE id = '$coupon->id'";
			$result = mysql_query($sql);
		}
		
			############################### PHOTOGRAPHERS
			if(file_exists("../photog_main.php")){
				$sql = "UPDATE photog_sales SET completed='1' WHERE visitor_id = '$visitor->visitor_id'";
				$result = mysql_query($sql);
			}
			
			# UPDATE QUANTITY INFORMATION
			$cart_result = mysql_query("SELECT * FROM carts where visitor_id = '$visitor->visitor_id'", $db);
			while($cart = mysql_fetch_object($cart_result)){
				
				if($cart->ptype == "p"){
					$print_result = mysql_query("SELECT * FROM prints where id = '$cart->prid'", $db);
					$print_rows = mysql_num_rows($print_result);
					$print = mysql_fetch_object($print_result);
					
					$quantity = $print->quan_avail - $cart->quantity;
					
					if($print->quan_avail != "999"){
						$sql = "UPDATE prints SET quan_avail='$quantity' WHERE id = '$print->id'";
						$result = mysql_query($sql);
					}
				}		
			}
			
		header("location: " . $return);
		exit;
		
	/* SAVE EDIT ITEM */	
		case "save_edit":
			$name = $_POST['name'];
			$porder = $_POST['porder'];
			$price = $_POST['price'];
			$return = $_POST['return'];$name = $_POST['name'];
			$porder = $_POST['porder'];
			$price = $_POST['price'];
			$return = $_POST['return'];
			$s_year = $_POST['s_year'];
			$s_month = $_POST['s_month'];
			$s_day = $_POST['s_day'];
			$item_id = $_POST['item_id'];
			$new_title = $title;
			$new_title = str_replace("\"", "", $new_title);
			$new_title = str_replace("\'", "", $new_title);
			$title = $new_title;
			
			$publish_date = $s_year . $s_month . $s_day;	
			$sql = "UPDATE prints SET name='$name',porder='$porder',price='$price' WHERE id = '$item_id'";
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