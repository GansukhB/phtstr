<?php
	session_start();
	include( "check_login_status.php" );
	// Batch Edit Actions - UPDATED 3.24.07
	include( "config_mgr.php" );
	
	if($_SESSION['access_type'] != "mgr"){ echo "Operation cannot be performed in demo mode"; exit; }
	
	$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
	
	
	$image_path = $stock_photo_path_manager;
		
	switch($_GET['pmode']){

/*-----------------------------------------------------------------------------------------------------------------------*/
/*                                                PLUGIN ACTIONS                                                    */
/*-----------------------------------------------------------------------------------------------------------------------*/
##################################################
#############---DELETE---#########################
##################################################	
	/* DELETE ITEM(S) */	
		case "delete":
		
	// SELECT EVERYTHING FOR SUB ID IMAGES FROM DATABASE
				$result1 = mysql_query("SELECT id FROM uploaded_images", $db);
				while($rs1 = mysql_fetch_object($result1)){	
						
	// COMPARE THE SUB IDS ON THE LIST TO THE DATABASE AND SELECT ONLY THE ONES SELECTED
			if($_POST[$rs1->id] == "1"){
				$sub_id = $rs1->id . "_sub_id";
				if($_POST[$sub_id] == "1"){
					
	// NOW DELETE THE ACTUAL SELECTED IMAGES	  
			$result_image1 = mysql_query("SELECT * FROM uploaded_images where id = '$rs1->id'", $db);
				while($rs_image1 = mysql_fetch_object($result_image1)){
					if(file_exists($image_path . $rs_image1->filename)){
						unlink($image_path . $rs_image1->filename);
					}
					if(file_exists($image_path . "i_" . $rs_image1->filename)){
						unlink($image_path . "i_" . $rs_image1->filename);
					}
					if(file_exists($image_path . "s_" . $rs_image1->filename)){
						unlink($image_path . "s_" . $rs_image1->filename);
					}
					if(file_exists($image_path . "m_" . $rs_image1->filename)){
						unlink($image_path . "m_" . $rs_image1->filename);
					}
					$sql1="DELETE FROM uploaded_images WHERE id = '$rs_image1->id'";
					$result21 = mysql_query($sql1);
				}
			}
		}
	}
	
	// SELECT EVERYTHING FOR MAIN ID IMAGES FROM DATABASE
				$result = mysql_query("SELECT id FROM photo_package", $db);
				while($rs = mysql_fetch_object($result)){		
			
	// COMPARE THE MAIN IDS ON THE LIST TO THE DATABASE AND SELECT ONLY THE ONES SELECTED
			if($_POST[$rs->id] == "1"){
				$main_id = $rs->id . "_main_id";
				if($_POST[$main_id] == "1"){
				
  // NOW DELETE THE ACTUAL SELECTED IMAGES
			$result_image = mysql_query("SELECT * FROM uploaded_images where reference_id = '$rs->id'", $db);
				$result_image_row = mysql_num_rows($result_image);
				if($result_image_row < 2){
				while($rs_image = mysql_fetch_object($result_image)){
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
					$sql="DELETE FROM photo_package WHERE id = '$rs->id'";
					$result2 = mysql_query($sql);
					
					$sql2="DELETE FROM uploaded_images WHERE id = '$rs_image->id'";
					$result3 = mysql_query($sql2);
				}
			} else {
				header("location: " . $return . "&message=main_id");
				exit;
			  }
			}
		}
	}
			
		header("location: " . $return . "&message=deleted");
		break;
	
###################################################
##########---MASS MOVE---##########################
###################################################
		case "move":
// move the selected photos to new selected category
   if($_POST['move3'] == "1"){
   	$result_pg = mysql_query("SELECT id FROM photo_package", $db);
		while($rs = mysql_fetch_object($result_pg)){
			if($_POST[$rs->id . "_main_id"] == "1"){
				$gallery = $_POST['gallery_id_to'];
					$id = $rs->id;
					$sql = "UPDATE photo_package SET gallery_id='$gallery' WHERE id = '$id'";
 					$result = mysql_query($sql);
 				}
 			}
 		} else {
		$result_pg = mysql_query("SELECT id FROM photo_package", $db);
		while($rs = mysql_fetch_object($result_pg)){
			if($_POST[$rs->id] == "1"){
				if($_POST[$rs->id . "_main_id"] == "1"){
					$gallery = $_POST['gallery_id_to'];
					$id = $rs->id;
					$sql = "UPDATE photo_package SET gallery_id='$gallery' WHERE id = '$id'";
 					$result = mysql_query($sql);
 				}
 			}
 		}
 	}
 		
 		header("location: " . $return . "&message=moved");
		break;				

#############################################################
##################---MASS EDIT---############################
#############################################################				
		case "save_edit":
// save most the data input on the form for the main photos
		$result_pg = mysql_query("SELECT id FROM photo_package", $db);
		$i = 0;
		while($rs3 = mysql_fetch_object($result_pg)){
			if($_POST[$rs3->id . "_main_id"] == "1"){
				$id = $rs3->id;
				if($_POST['title3'] != ""){
					$title = $_POST['title3'];
				} else {
					$ti = "title_" . $rs3->id;
					$title = $_POST[$ti];
				}
				if($_POST['description3'] != ""){
					$description = $_POST['description3'];
				} else {
					$descr = "description_" . $rs3->id;
					$description = $_POST[$descr];
				}
				if($_POST['keywords3'] != ""){
					$keywords = $_POST['keywords3'];
				} else {
					$keyw = "keywords_" . $rs3->id;
					$keywords = $_POST[$keyw];
				}
				if($_POST['photographer3'] != ""){
					$photographer = $_POST['photographer3'];
				} else {
					$phot = "photographer_" . $rs3->id;
					$photographer = $_POST[$phot];
				}
				
				//ADDED IN PS350 TO CLEANUP DATA ENTRY
				$title = cleanup($title);
				$keywords = cleanup($keywords);
				$description = cleanup($description);
				//SAVE DATA
	    	$sql = "UPDATE photo_package SET title='$title',description='$description',keywords='$keywords',photographer='$photographer' WHERE id = '$id'";
 				$result = mysql_query($sql);
 				$photo_result = mysql_query("SELECT * FROM uploaded_images where reference_id = '$id' and original = '1'", $db);
				$photo_rows = mysql_num_rows($photo_result);
				$photo = mysql_fetch_object($photo_result);
				if($_POST['price3'] != ""){
					$price = $_POST['price3'];
				} else {
					$pric = "price_" . $rs3->id;
					$price = $_POST[$pric];
				}
				if($_POST['quality3'] != ""){
					$quality = $_POST['quality3'];
				} else {
					$quali = "quality_" . $rs3->id;
					$quality = $_POST[$quali];
				}
				if($_POST['quality_order3'] != ""){
					$quality_order = $_POST['quality_order3'];
				} else {
					$quali_o = "quality_order_" . $rs3->id;
					$quality_order = $_POST[$quali_o];
				}
				//ADDED IN PS350 TO CLEANUP DATA ENTRY
				$price = price_cleanup($price);
				//SAVE DATA
 				$sql = "UPDATE uploaded_images SET price='$price',quality='$quality',quality_order='$quality_order' WHERE id = '$photo->id'";
 				$result = mysql_query($sql);
 				$i++;
 				// DEBUGGING CODE
 				/*
 			  echo $ti . "=ti post code<br>";
 			  echo $descr . "=descr post code<br>";
 			  echo $keyw . "=keyw post code<br>";
 			  echo $phot . "=phot post code<br>";
 			  echo $pric . "=pric post code<br>";
 			  echo $quali . "=quali post code<br>";
 			  echo $quali_o . "=quali_o post code<br>";
 				echo $id . "=id or rs->id<br>";
 				echo $photo->id . "=photo_id<br>";
 				echo $price . "=price<br>";
 				echo $quality . "=quality<br>";
 				echo $quality_order . "=quality_order<br>";
 				echo $title . "=title<br>";
 				echo $keywords . "=keywords<br>";
 				echo $description . "=description<br>";
 				echo $photographer . "=photographer<br>";
 				echo "<hr>";
 				*/
 		}
  }
   unset($id);
   unset($price);
   unset($quality);
   unset($quality_order);
// exit;

#####################################################
#############---SAVE FEATURED---#####################
#####################################################
// save featured state of main photos
    if($_POST['featured3'] == "1"){
    	$result_pg9 = mysql_query("SELECT id FROM photo_package", $db);
			while($rs9 = mysql_fetch_object($result_pg9)){
				if($_POST[$rs9->id . "_main_id"] == "1"){
					$id = $rs9->id;
					$featured = 1;
					$sql = "UPDATE photo_package SET featured='$featured' WHERE id = '$id'";
 					$result = mysql_query($sql);
 				}
 			}
 		} else {
		 $result_pg9 = mysql_query("SELECT id FROM photo_package", $db);
			while($rs9 = mysql_fetch_object($result_pg9)){
			if($_POST["featured_" . $rs9->id] == "1"){
				$featured = 1;
				$id = $rs9->id;
   			$sql = "UPDATE photo_package SET featured='$featured' WHERE id = '$id'";
 				$result = mysql_query($sql);
			} else {
				if($_POST[$rs9->id . "_main_id"] == "1"){
				$featured = 0;
				$id = $rs9->id;
   			$sql = "UPDATE photo_package SET featured='$featured' WHERE id = '$id'";
 				$result = mysql_query($sql);
 			}
		}
	}
}
   unset($id);
   unset($price);
   unset($quality);
   unset($quality_order);
// exit;

#####################################################
#############---SAVE ALL SIZES---####################
#####################################################
// save all_sizes state of main photos
    if($_POST['sizes3'] == "1"){
    	$result_pg10 = mysql_query("SELECT id FROM photo_package", $db);
			while($rs10 = mysql_fetch_object($result_pg10)){
				if($_POST[$rs10->id . "_main_id"] == "1"){
					$id = $rs10->id;
					$all_sizes = 1;
					$sql = "UPDATE photo_package SET all_sizes='$all_sizes' WHERE id = '$id'";
 					$result = mysql_query($sql);
 				}
 			}
 		} else {
		 $result_pg10 = mysql_query("SELECT id FROM photo_package", $db);
			while($rs10 = mysql_fetch_object($result_pg10)){
			if($_POST["sizes_" . $rs10->id] == "1"){
				$all_sizes = 1;
				$id = $rs10->id;
   			$sql = "UPDATE photo_package SET all_sizes='$all_sizes' WHERE id = '$id'";
 				$result = mysql_query($sql);
			} else {
				if($_POST[$rs10->id . "_main_id"] == "1"){
				$all_sizes = 0;
				$id = $rs10->id;
   			$sql = "UPDATE photo_package SET all_sizes='$all_sizes' WHERE id = '$id'";
 				$result = mysql_query($sql);
 			}
		}
	}
}
   unset($id);
   unset($price);
   unset($quality);
   unset($quality_order);
// exit;

##############################################
##########---SAVE ALL PRINTS--################
##############################################
// save all print state of main photos
    if($_POST['all_prints3'] == "1"){
    	$result_pg1 = mysql_query("SELECT id FROM photo_package", $db);
			while($rs4 = mysql_fetch_object($result_pg1)){
				if($_POST[$rs4->id . "_main_id"] == "1"){
					$id = $rs4->id;
					$all_prints = 1;
					$sql = "UPDATE photo_package SET all_prints='$all_prints' WHERE id = '$id'";
					$result = mysql_query($sql);
				}
			}
 		} else {
   		$result_pg1 = mysql_query("SELECT id FROM photo_package", $db);
			while($rs4 = mysql_fetch_object($result_pg1)){
			if($_POST["a_" . $rs4->id] == "1"){
				$all_prints = 1;
				$id = $rs4->id;
   			$sql = "UPDATE photo_package SET all_prints='$all_prints' WHERE id = '$id'";
 				$result = mysql_query($sql);
			} else {
				if($_POST[$rs4->id . "_main_id"] == "1"){
				$all_prints = 0;
				$id = $rs4->id;
   			$sql = "UPDATE photo_package SET all_prints='$all_prints' WHERE id = '$id'";
 				$result = mysql_query($sql);
 				}
			}
		}
	}
	 unset($id);
   unset($price);
   unset($quality);
   unset($quality_order);
   
#####################################################
#############---SAVE DOWNLOAD---#####################
#####################################################
// save download state of main photos
    if($_POST['act_download3'] == "1"){
    	$result_pg2 = mysql_query("SELECT id FROM photo_package", $db);
			while($rs5 = mysql_fetch_object($result_pg2)){
				if($_POST[$rs5->id . "_main_id"] == "1"){
					$id = $rs5->id;
					$act_download = 1;
					$sql = "UPDATE photo_package SET act_download='$act_download' WHERE id = '$id'";
 					$result = mysql_query($sql);
 				}
 			}
 		} else {
		 $result_pg2 = mysql_query("SELECT id FROM photo_package", $db);
			while($rs5 = mysql_fetch_object($result_pg2)){
			if($_POST["b_" . $rs5->id] == "1"){
				$act_download = 1;
				$id = $rs5->id;
   			$sql = "UPDATE photo_package SET act_download='$act_download' WHERE id = '$id'";
 				$result = mysql_query($sql);
			} else {
				if($_POST[$rs5->id . "_main_id"] == "1"){
				$act_download = 0;
				$id = $rs5->id;
   			$sql = "UPDATE photo_package SET act_download='$act_download' WHERE id = '$id'";
 				$result = mysql_query($sql);
 			}
		}
	}
}
   unset($id);
   unset($price);
   unset($quality);
   unset($quality_order);
   
################################################
###########---SAVE ACTIVE---####################
################################################
// save active state of main photos
    if($_POST['active3'] == "1"){
    	$result_pg3 = mysql_query("SELECT id FROM photo_package", $db);
			while($rs7 = mysql_fetch_object($result_pg3)){
			  if($_POST[$rs7->id . "_main_id"] == "1"){
			  	$id = $rs7->id;
			  	$active = 1;
			  	$sql = "UPDATE photo_package SET active='$active' WHERE id = '$id'";
 				$result = mysql_query($sql);
 			}
 		}
 	} else {
			$result_pg3 = mysql_query("SELECT id FROM photo_package", $db);
			while($rs7 = mysql_fetch_object($result_pg3)){
			if($_POST["c_" . $rs7->id] == "1"){
				$active = 1;
				$id = $rs7->id;
   			$sql = "UPDATE photo_package SET active='$active' WHERE id = '$id'";
 				$result = mysql_query($sql);
			} else {
				if($_POST[$rs7->id . "_main_id"] == "1"){
				$active = 0;
				$id = $rs7->id;
   			$sql = "UPDATE photo_package SET active='$active' WHERE id = '$id'";
 				$result = mysql_query($sql);
 			}
		}
	}
}
   unset($id);
   unset($price);
   unset($quality);
   unset($quality_order);
   
#######################################################
#################---SAVE SUB PHOTO DATA---#############
#######################################################
// sub id photos save data 
		$result_ui = mysql_query("SELECT id FROM uploaded_images", $db);
		$i = 0;
		while($rs6 = mysql_fetch_object($result_ui)){
			$sub_id = $rs6->id . "_sub_id";
			if($_POST[$sub_id] == "1"){
			$id = $rs6->id;
			$pri = "price_" . $rs6->id;
			$price = $_POST[$pri];
			$qual = "quality_" . $rs6->id;
			$quality = $_POST[$qual];
			$qual_o = "quality_order_" . $rs6->id;
			$quality_order = $_POST[$qual_o];
			//ADDED IN PS350 TO CLEANUP DATA ENTRY
			$price = price_cleanup($price);
			//SAVE DATA
			$sql2 = "UPDATE uploaded_images SET price='$price',quality='$quality',quality_order='$quality_order' WHERE id = '$id'";
			$result2 = mysql_query($sql2);
		  $i++;
		}
		// DEBUGGING CODE
		/*
		echo $id . "=id<br>";
		echo $price . "=price<br>";
		echo $quality . "=quality<br>";
		echo $quality_order . "=quality_order1<br>";
		echo "<hr>";
		*/
	}
	  // exit;
			header("location: " . $return . "&message=saved");
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