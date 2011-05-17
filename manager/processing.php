<?php
	session_start();
	
	// processing FTP Uploads
	
	include( "config_mgr.php" );
	$return_page = $_SESSION['page'];
	$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
	
	$upload_result = mysql_query("SELECT * FROM status where id = '1'", $db);
	$upload = mysql_fetch_object($upload_result);
	
	$x = $_SESSION['left'];
	$added_count = $_SESSION['start'];
	if($x < 1){
		foreach($p_name as $key => $value){
			unset($_SESSION["p_price_" . $key]);
		}
		unset($_SESSION['title']);
		unset($_SESSION['gallery_id']);
		unset($_SESSION['other_galleries2']);
		unset($_SESSION['keywords']);
		unset($_SESSION['description']);
		unset($_SESSION['quality1']);
		unset($_SESSION['act_downloads']);
		unset($_SESSION['price']);
		unset($_SESSION['price_contact']);
		unset($_SESSION['photographer']);
		unset($_SESSION['all_prints']);
		unset($_SESSION['all_sizes']);
		unset($_SESSION['aprofile']);
		unset($_SESSION['prod']);
		unset($_SESSION['sizes']);
		unset($_SESSION['new_image_name']);
		unset($_SESSION['title']);
		unset($_SESSION['featured']);
		unset($_SESSION['quality_order1']);
		unset($_SESSION['active']);
		echo "Upload Completed:<br />";
		if($setting->show_tree == 1){
		echo "If you have your left menu on your public site displaying photo counts, it is recommended now to rebuild your sites menu.<br>Click to rebuild menu <a href=\"menu_creator.php\" target=\"_blank\">REBUILD MENU</a><br />";
		}
		echo "To Continue: <a href=\"mgr.php?mes=uploaded&nav=$return_page&added=$added_count\">CONTINUE</a><br />";
		echo $upload->status . "<br />";

		exit;
	} else {
		if($upload->status != ""){
			$status = $upload->status;
		}	
			if($_GET['error'] == 1){
			$status_complete = "<font color=\"red\">" . $_SESSION['new_image_name'] . " FAILED TO UPLOAD!<br></font>" . $status;
			} else {
		  $status_complete = "<font color=\"green\">" . $_SESSION['new_image_name'] . " has been uploaded successfully!<br></font>" . $status;
			}
			$sql = "UPDATE status SET status='$status_complete' WHERE id = '1'";
			$result = mysql_query($sql);
			echo $upload->status . "<br />";
	?>
<SCRIPT LANGUAGE="JavaScript">
if (navigator.javaEnabled()) 
window.location = "actions_ftp_upload.php?pmode=ftp_upload";
else 
window.location = "actions_ftp_upload.php?pmode=ftp_upload";
</script>
<?php
	}
?>