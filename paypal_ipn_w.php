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
	include( "config_public.php" );
	
	$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);

	switch($payment_status){
		case "Pending":
		
		
		break;
		
		
		case "Completed":
		
			// GET VISITOR INFORMATION
			$visitor_result = mysql_query("SELECT * FROM visitors where order_num = '$item_number'", $db);
			$visitor_rows = mysql_num_rows($visitor_result);
			$visitor = mysql_fetch_object($visitor_result);
			
			// UPDATE ORDER INFORMATION
			$sql = "UPDATE visitors SET status='1',paypal_email='$payer_email' WHERE id = '$visitor->id'";
			$result = mysql_query($sql);			
			
			// SEND EMAIL TO BUYER FOR COMPLETED ORDER
			$buyer_message = "";
			$buyer_message.= "Thank you for your purchase from " . $setting->site_title . ".\n\n";
			
			$buyer_message.= "Please click on the following link to view your order. If you purchased digital photos you will be able to download them by visiting the link. " . $setting->site_url . "/download.php?order=" . $item_number . "\n\n";
			$buyer_message.= "If you have any questions please contact us.";
			
			mail($payer_email, $setting->site_title . " Order #" . $item_number, $buyer_message, "From: " . $setting->support_email);
			
		break;	
		
		case "Failed":
		
		break;
		
	}
	
?>
