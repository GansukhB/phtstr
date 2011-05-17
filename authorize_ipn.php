<?
	include("database.php");
	include("functions.php");
	include( "config_public.php" );
	
$cart_order_id=$_POST['x_invoice_num'];
$email = $_POST['x_email'];
if($email == ""){
	$email = "Was not a member";
}
$member_id=$_GET['memberid'];
	if($member_id != ""){
		$sql="SELECT * FROM `members` where id = " . $member_id;
		$member_result = mysql_query($sql, $db);
		$member = mysql_fetch_object($member_result);
	}
	
	if($_POST['x_response_code'] == 1){
		
		// GET VISITOR INFORMATION
		$visitor_result = mysql_query("SELECT id,visitor_id,coupon_id,price,shipping,tax FROM visitors where order_num = '$cart_order_id'", $db);
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
		
		include("send_order_info.php");
		
		// IF EMAIL EXIST SEND EMAIL TO BUYER FOR COMPLETED ORDER
		if($email != ""){
		$to = $email;
		$amount = $visitor->price + $visitor->shipping + $visitor->tax;
		$amount = doubleval($amount); 
		$amount = sprintf("%.2f", $amount);
		$status = "Completed";
		email(20,$to);
		}
		header("location: download.php?order=$cart_order_id");
		exit;
	} else {
		echo $auth_ipn_pending;
		echo $auth_ipn_pending2 . $_POST['x_response_reason_text'];
		exit;
	}	
?>