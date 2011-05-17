<?
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);

// assign posted variables to local variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];

if (!$fp) {
// HTTP ERROR
} else {
fputs ($fp, $header . $req);
while (!feof($fp)) {
$res = fgets ($fp, 1024);
if (strcmp ($res, "VERIFIED") == 0) {
// check the payment_status is Completed
// check that txn_id has not been previously processed
// check that receiver_email is your Primary PayPal email
// check that payment_amount/payment_currency are correct
// process payment
}
else if (strcmp ($res, "INVALID") == 0) {
// log for manual investigation
}
}
fclose ($fp);
}

	include("database.php");
	include("functions.php");
	include("config_public.php");
  
	switch($payment_status){
		case "Pending":
		break;
		
		
		case "Completed":
		
			// GET VISITOR INFORMATION
			$visitor_result = mysql_query("SELECT id,visitor_id,coupon_id FROM visitors where order_num = '$item_number'", $db);
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
			$sql = "UPDATE visitors SET status='1',paypal_email='$payer_email' WHERE id = '$visitor->id'";
			$result = mysql_query($sql);
		
			############################### PHOTOGRAPHERS
			if(file_exists("photog_main.php")){
				$sql = "UPDATE photog_sales SET completed='1' WHERE visitor_id = '$visitor->visitor_id'";
				$result = mysql_query($sql);
			}
			
			# UPDATE QUANTITY INFORMATION
			$cart_result = mysql_query("SELECT * FROM carts where visitor_id = '$visitor->visitor_id'", $db);
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
			
			include("send_order_info.php");
			
			// SEND EMAIL TO BUYER FOR COMPLETED ORDER
			$to = $payer_email;
			email(4,$to);
		break;	
		
		case "Failed":
		
		break;
		
	}
	
?>
