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

	switch($payment_status){
		case "Pending":
		
		
		break;
		
		
		case "Completed":
		
			// GET VISITOR INFORMATION
			$member_result = mysql_query("SELECT id FROM members where order_num = '$item_number'", $db);
			$member = mysql_fetch_object($member_result);
			
			$today = date("Ymd");
			
			$down_y_new = $setting->down_limit_y;
			$down_m_new = $setting->down_limit_m;
			
			// UPDATE ORDER INFORMATION
			$sql = "UPDATE members SET status='1',paypal_email='$payer_email',added='$today',down_limit_m='$down_m_new',down_limit_y='$down_y_new' WHERE id = '$member->id'";
			$result = mysql_query($sql);
		
			
			// SEND EMAIL TO BUYER FOR COMPLETED ORDER
			$to = $payer_email;
			email(12,$to);
		break;	
		
		case "Failed":
		
		break;
		
	}
	
?>
