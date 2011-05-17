<?
	/*
		order_number - 2Checkout.com order number 
		card_holder_name - Card holder's name 
		street_address - Card holder's address 
		city - Card holder's city 
		state - Card holder's state 
		zip - Card holder's zip 
		country - Card holder's country 
		email - Card holder's email 
		phone - Card holder's phone 
		cart_order_id - Your cart ID number passed in. 
		cart_id - Your cart ID number passed in. 
		credit_card_processed - Y if successful, K if waiting for approval 
		total - Total purchase amount. 
		ship_name - Shipping information 
		ship_street_address - Shipping information 
		ship_city - Shipping information 
		ship_state - Shipping information 
		ship_zip - Shipping information 
		ship_country - Shipping information 
	*/
	
	include("database.php");
	include("functions.php");
	include("config_public.php");
?>
<? echo $twoco_ipn_thank_you; ?> <a href="<?php echo $setting->site_url; ?>/download.php?order=<?php echo $_POST['cart_order_id']; ?>"><? echo $twoco_ipn_thank_you2; ?></a>.
<?
	
	
	
	foreach($_GET as $key => $value){				
		if(is_array($value)){
			foreach($value as $key2 => $value2){
				//${$key}[$key2] = addslashes($value2);
				${$key}[$key2] = quote_smart($value2);								
			}	
		} else {
			${$key} = quote_smart($value);
		}
	}
	
	if($_POST['credit_card_processed'] == "Y"){
		
		$email = urldecode($_POST['email']);
		
		// GET VISITOR INFORMATION
		$visitor_result = mysql_query("SELECT id,visitor_id,coupon_id FROM visitors where order_num = '$cart_order_id'", $db);
		$visitor = mysql_fetch_object($visitor_result);
		
		$coupon_result = mysql_query("SELECT id,used FROM coupon where id = '$visitor->coupon_id'", $db);
		$coupon_rows = mysql_num_rows($coupon_result);
		$coupon = mysql_fetch_object($coupon_result);

		if($coupon_rows > 0){
			$used = $coupon->used + 1;
			$sql = "UPDATE coupon SET used='$used' WHERE id = '$coupon->id'";
			$result = mysql_query($sql);
		}
		
		// UPDATE ORDER INFORMATION
		$sql = "UPDATE visitors SET status='1',paypal_email='$email' WHERE id = '$visitor->id'";
		$result = mysql_query($sql);
		
		# UPDATE QUANTITY INFORMATION
		$cart_result = mysql_query("SELECT ptype,prid,quantity FROM carts where visitor_id = '$visitor->visitor_id'", $db);
		while($cart = mysql_fetch_object($cart_result)){
			
			if($cart->ptype == "p"){
				$print_result = mysql_query("SELECT id,quan_avail FROM prints where id = '$cart->prid'", $db);
				$print = mysql_fetch_object($print_result);
				
				$quantity = $print->quan_avail - $cart->quantity;
				
				if($print->quan_avail != "999"){
					$sql = "UPDATE prints SET quan_avail='$quantity' WHERE id = '$print->id'";
					$result = mysql_query($sql);
				}
			}		
		}
	
		
		############################### PHOTOGRAPHERS
		if(file_exists("photog_main.php")){
			$sql = "UPDATE photog_sales SET completed='1' WHERE visitor_id = '$visitor->visitor_id'";
			$result = mysql_query($sql);
		}
		
		$twochechout_purchase = 1;
		include("send_order_info.php");
		
		// SEND EMAIL TO BUYER FOR COMPLETED ORDER
		$to = $_POST['email'];
		email(5,$to);
	} else {		
		echo $twoco_ipn_pending;
	}	
?>