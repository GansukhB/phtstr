<?
	include("database.php");
	include("functions.php");
	include( "config_public.php" );
	
	if($credit_card_processed == "Y"){
			// GET VISITOR INFORMATION
			$member_result = mysql_query("SELECT id,email FROM members where order_num = '$cart_order_id'", $db);
			$member = mysql_fetch_object($member_result);
			
			$today = date("Ymd");
			
		$down_y_new = $setting->down_limit_y;
		$down_m_new = $setting->down_limit_m;
			
			// UPDATE ORDER INFORMATION
			$sql = "UPDATE members SET status='1',paypal_email='$email',added='$today',down_limit_y='$down_y_new',down_limit_m='$down_m_new' WHERE id = '$member->id'";
			$result = mysql_query($sql);
		
			
			// SEND EMAIL TO BUYER FOR COMPLETED ORDER
			$to = $member->email;
			email(14,$to);
		
	} else {		
		echo $twoco_ipn_pending_sub;
	}	
?>