<?php
	
	include("database.php");
	include("functions.php");
	include( "config_public.php" );
	
	$_RESULT=$_POST['_RESULT'];
	$_ERROR_CODE=$_POST['_ERROR_CODE'];
	$_ERROR_SOURCE=$_POST['_ERROR_SOURCE'];
	$_ERROR_MESSAGE=$_POST['_ERROR_MESSAGE'];
	$_ERROR_DETAIL=$_POST['_ERROR_DETAIL'];
	$_VARIABLE1=$_POST['VARIABLE1'];
	$_EMAIL=$_POST['VARIABLE2'];
	$_TOTAL=$_POST['VARIABLE3'];
	
	//echo "test: " . $_RESULT;
	
?>
<? if($_GET['message'] == "failed"){ ?>
<?PHP echo $mygate_ipn_failed_message; ?>
<? } else { ?>
<?PHP echo $mygate_ipn_thank_you; ?><a href="<?php echo $setting->site_url; ?>/download.php?order=<?php echo $_VARIABLE1; ?>"><?PHP echo $mygate_ipn_thank_you2; ?></a>.
<?
	}
	
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
	
	if($_RESULT == 0){
		
		$email = urldecode($_EMAIL);
		
		// GET VISITOR INFORMATION
		$visitor_result = mysql_query("SELECT id,visitor_id,price,shipping,tax,coupon_id FROM visitors where order_num = '$_VARIABLE1'", $db);
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
		
		$mygate_purchase = 1;
		include("send_order_info.php");
		
		// SEND EMAIL TO BUYER FOR COMPLETED ORDER
		$amount = $visitor->price + $visitor->shipping + $vistor->tax;
		$amount = doubleval($amount);
		$amount = sprintf("%.2f", $amount);
		$status = $mygate_ipn_status;
		$to = $email;
		email(15,$to);
	} else {
		echo $mygate_ipn_pending;
	}	
?>