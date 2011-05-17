<?php
	if(!function_exists('curl_init')){
		echo "<strong>***cURL must be compiled into PHP to use the Plug n' Pay payment gateway. To have cURL compiled please contact your host.</strong><br /><br />";
		exit;
	}
	
	include("database.php");
	include("functions.php");
	include( "config_public.php" );

    // Is curl complied into PHP? 
    $is_curl_compiled_into_php = "yes"; 
    // Possible answers are: 
    //  'yes' -> means that curl is compiled into PHP [DEFAULT]
    //  'no'  -> means that curl is not-compiled into PHP & must be called externally

    // If you selected 'no' to the above question, then set the absolute path to curl
    $curl_path = "/usr/bin/curl";
    // [e.g.: '/usr/bin/curl' on Unix/Linux or 'c:/curl/curl.exe' on Windows servers] 
    // If you are unsure of this, check with your hosting company.

    // Set URL that you will post the transaction to
    $pnp_post_url = "https://pay1.plugnpay.com/payment/pnpremote.cgi";
    // This should never need to be changed...

    if ($pnp_post_values == "") {
        $pnp_post_values .= "publisher-name=" . $setting->pnpid . "&";
		$pnp_post_values .= "publisher-email=" . $setting->support_email . "&";
		$pnp_post_values .= "order-id=" . $item_number . "&";
		$pnp_post_values .= "card-allowed=Visa,Mastercard,Amex&";
        $pnp_post_values .= "card-number=" . $card_number . "&";
        $pnp_post_values .= "card-cvv=" . $card_cvv . "&";
        $pnp_post_values .= "card-exp=" . $card_exp . "&";
        $pnp_post_values .= "card-amount=" . $card_amount . "&";
        $pnp_post_values .= "card-name=" . $card_name . "&";
        $pnp_post_values .= "email=" . $email . "&";
        $pnp_post_values .= "ipaddress=" . $email . "&";
        // billing address info
        $pnp_post_values .= "card-address1=" . $card_address1 . "&";
        $pnp_post_values .= "card-address2=" . $card_address2 . "&";
        $pnp_post_values .= "card-zip=" . $card_zip . "&";
        $pnp_post_values .= "card-city=" . $card_city . "&";
        $pnp_post_values .= "card-state=" . $card_state . "&";
        $pnp_post_values .= "card-country=" . $card_country . "&";
        // shipping address info
        $pnp_post_values .= "shipname=" . $shipname . "&";
        $pnp_post_values .= "address1=" . $card_address1 . "&";
        $pnp_post_values .= "address2=" . $card_address2 . "&";
        $pnp_post_values .= "zip=" . $card_zip . "&";
        $pnp_post_values .= "state=" . $card_state . "&";
        $pnp_post_values .= "country=" . $card_country . "&";
    }


    /**************************************************************************
      UNLESS YOU KNOW WHAT YOU ARE DOING YOU SHOULD NOT CHANGE THE BELOW CODE
    **************************************************************************/

    if ($is_curl_compiled_into_php == "no") {
      // do external PHP curl connection 
      exec("$curl_path -d \"$pnp_post_values\" https://pay1.plugnpay.com/payment/pnpremote.cgi", $pnp_result_page);
      // NOTES:
      // -- The '-k' attribute can be added before the '-d' attribute to turn off curl's SSL certificate validation feature.
      // -- Only use the '-k' attribute if you know your curl path is correct & are getting back a blank response in $pnp_result_page.

      $pnp_result_decoded = urldecode($pnp_result_page[1]);
    }
    else {
      // do internal PHP curl connection
      // init curl handle
      $pnp_ch = curl_init($pnp_post_url);
      curl_setopt($pnp_ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($pnp_ch, CURLOPT_POSTFIELDS, $pnp_post_values);
      #curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  // Upon problem, uncomment for additional Windows 2003 compatibility

      // perform ssl post
      $pnp_result_page = curl_exec($pnp_ch);

      $pnp_result_decoded = urldecode($pnp_result_page);
    }

    // decode the result page and put it into transaction_array
    $pnp_temp_array = split('&',$pnp_result_decoded);
    foreach ($pnp_temp_array as $entry) {
        list($name,$value) = split('=',$entry);
        $pnp_transaction_array[$name] = $value;
    }

    /**************************************************************************
        UNLESS YOU KNOW WHAT YOU ARE DOING DO NOT CHANGE THE ABOVE CODE
    **************************************************************************/
    if ($pnp_handle_post_process != "no") {
      if ($pnp_transaction_array['FinalStatus'] == "success") {
		$email = urldecode($_POST['email']);
		$cart_order_id = $_POST['item_number'];
		
		// IF TYPE IS A SUBSCRIPTION
		if($_POST['subscription'] == "subscription"){
			?>
			<?PHP echo $plugnpay_ipn_sub_thank_you; ?>
			<?
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
			$to = $email;
			email(19,$to);
			exit;
		}
		
		// GET VISITOR INFORMATION
		$visitor_result = mysql_query("SELECT id,visitor_id,price,shipping,tax FROM visitors where order_num = '$cart_order_id'", $db);
		$visitor = mysql_fetch_object($visitor_result);
		
		 if($setting->force_approve == 1){
			  	$approve = 1;
			  } else {
			  	$approve = 0;
			  }
		
		// UPDATE ORDER INFORMATION
		$sql = "UPDATE visitors SET status='1',paypal_email='$email',done='$approve',member_id='" . $_SESSION['sub_member'] . "' WHERE id = '$visitor->id'";
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
		
		// SEND EMAIL TO BUYER FOR COMPLETED ORDER
		$to = $email;
		$amount = $visitor->price + $visitor->shipping + $visitor->tax;
		$amount = doubleval($amount); 
		$amount = sprintf("%.2f", $amount);
		$status = $pnp_transaction_array['FinalStatus'];
		email(18,$to);
		$total = $amount;
		$order_number = $visitor->visitor_id;
		$twochechout_purchase = 1;
		include("send_order_info.php");
		header("location: download.php?order=$cart_order_id");
		exit;
      }
      elseif ($pnp_transaction_array['FinalStatus'] == "badcard") {
       // include("badcard.html");
		header("location: pperror.php");
		exit;
      }
      elseif ($pnp_transaction_array['FinalStatus'] == "fraud") {
        //include("fraud.html");
		header("location: pperror.php");
		exit;
      }
      elseif ($pnp_transaction_array['FinalStatus'] == "problem") {
        //include("problem.html");
		header("location: pperror.php");
		exit;
      }
      else {
        // this should not happen
        //include("error.html");
		header("location: pperror.php");
		exit;
      }
    }
?>
