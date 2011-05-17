<?php
	session_start();
	include( "check_login_status.php" );
	
	// COUPON ACTIONS
	
	include( "config_mgr.php" );
	
	if($_SESSION['access_type'] != "mgr"){ echo "Operation cannot be performed in demo mode"; exit; }
	
	$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
	
	switch($_GET['pmode']){

		
	/* SAVE PROMO CODE */	
		case "save_coupon_settings":
			if($_SESSION['access_type'] != "mgr"){ echo "Operation cannot be performed in demo mode"; exit; }
			//ADDED IN PS350 TO CLEANUP DATA ENTRY
			$amount = price_cleanup($_POST['amount']);
			$code = cleanup($_POST['code']);
			//SAVE DATA
			$sql = "INSERT INTO coupon (amount,percent,type,item_count,expire,quantity,code,article,display) VALUES ('$amount','" . $_POST['percent'] . "','" . $_POST['type'] . "','" . $_POST['item_count'] . "','" . $_POST['expire'] . "','" . $_POST['quantity'] . "','$code','" . $_POST['article'] . "','" . $_POST['display'] . "')";
			$result = mysql_query($sql);
			header("location:" . $_POST['return']);	
		exit;
		break;
	/* DELETE PROMO CODE */
		case "delete":
		$result = mysql_query("SELECT * FROM coupon", $db);
		while($rs = mysql_fetch_object($result)) {		
			if($_POST[$rs->id] == "1") {
				
				$sql="DELETE FROM coupon WHERE id = '$rs->id'";
				$result2 = mysql_query($sql);
			}
		}
		header("location: " . $_POST['return']);
		exit;
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