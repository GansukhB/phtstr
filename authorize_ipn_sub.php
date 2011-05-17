<?
	include("database.php");
	include("functions.php");
	include("config_public.php");

$cart_order_id=$_POST['x_invoice_num'];	
	
	if($_POST['x_response_code'] == 1){ //transaction aproved for authorize.net
			?>
			<?PHP echo $auth_ipn_sub_thanks; ?><a href="index.php"><?PHP echo $auth_ipn_sub_thanks2; ?></a>.
			<?
		$email = $_POST['x_email'];
	// GET VISITOR INFORMATION
			$member_result = mysql_query("SELECT id FROM members where order_num = '$cart_order_id'", $db);
			$member = mysql_fetch_object($member_result);
			
			$today = date("Ymd");
			
			$down_y_new = $setting->down_limit_y;
			$down_m_new = $setting->down_limit_m;
			
			// UPDATE ORDER INFORMATION
			$sql = "UPDATE members SET status='1',paypal_email='$email',added='$today',down_limit_m='$down_m_new',down_limit_y='$down_y_new' WHERE id = '$member->id'";
			$result = mysql_query($sql);
		
			
			// SEND EMAIL TO BUYER FOR COMPLETED ORDER
			$buyer_message = "";
			$buyer_message.= "Thank you for your subscription to " . $setting->site_title . ".\n\n";
			
			$buyer_message.= "You may now log into the site.\n\n";
			$buyer_message.= "If you have any questions please contact us.";
			
			mail($email, $setting->site_title . " Subscription", $buyer_message, "From: " . $setting->support_email);
	
	} else {		
		echo $auth_ipn_sub_pending;
		echo $auth_ipn_sub_pending2 . $_POST['x_response_reason_text'];
	}	
?>
