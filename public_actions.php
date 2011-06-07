<?
	session_start();
	
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );
	
	$currency_result = mysql_query("SELECT code,sign FROM currency where active = '1'", $db);
	$currency = mysql_fetch_object($currency_result);
	
	$to = $setting->support_email;
	
	function sanitize($content) {
		 return str_replace(array("\r", "\n"), "", $content);
	 }		
			
	switch($pmode){
		case "select_lang":
    if(isset($_GET['lang']))
    {
      $lang = $_GET['lang'];
      if(isset($_GET['return'])) 
        $return = $_GET['return'];
      //else $return = 'ind'
    }
		if($lang != ""){
      session_register("lang");
      $_SESSION['lang'] = $lang;
      $expire=time()+60*60;
      setcookie("lang", $lang, $expire);
		}
    
    $return = str_replace(array("and"), array("&"), $return);
		header("location: " . $return);
		exit;
		break;
		
		case "add_cart":
			$added = date("Ymd");
				$cart_result = mysql_query("SELECT id FROM carts where visitor_id = '" . $_SESSION['visitor_id'] . "' and photo_id = '$pid' and prid = '$prid' and sid = '$sid'", $db);
				$cart_rows = mysql_num_rows($cart_result);
				
				if($cart_rows > 0){
					header("location: cart.php?message=already_added");
					exit;
				} else {
						$photo_result = mysql_query("SELECT id,reference_id,price FROM uploaded_images where id = '$pid'", $db);
						$photo = mysql_fetch_object($photo_result);
					
					if($ptype == "d"){	
						$reference = $photo->reference_id;
						if($photo->price){
									$price = $photo->price;
								} else {
									$price = $setting->default_price;
								}
					} else {
					if($ptype == "s"){
						$reference = $sid;
						$sizes_result = mysql_query("SELECT * FROM sizes where id = '$sid'", $db);
						$sizes_rows = mysql_num_rows($sizes_result);
						$sizes = mysql_fetch_object($sizes_result);
						$price = $sizes->price;
					} else {
						$reference = $pid;
						$print_result = mysql_query("SELECT * FROM prints where id = '$prid'", $db);
						$print_rows = mysql_num_rows($print_result);
						$print = mysql_fetch_object($print_result);
						$price = $print->price;
					}
				}

					if(!empty($_SESSION['visitor_id'])){
						$sql = "INSERT INTO carts (visitor_id,photo_id,added,ptype,prid,sid,price) VALUES ('" . $_SESSION['visitor_id'] . "','$pid','$added','$ptype','$prid','$sid','$price')";
						$result = mysql_query($sql);
					}
					
					$package_result = mysql_query("SELECT id,photographer,cart_count FROM photo_package where id = '$photo->reference_id'", $db);
					$package_rows = mysql_num_rows($package_result);
					$package = mysql_fetch_object($package_result);
					
					$cart_count = $package->cart_count + 1;
					
					$sql = "UPDATE photo_package SET cart_count='$cart_count' WHERE id = '$package->id'";
					$result = mysql_query($sql);
					
					
					//echo $package->photographer; exit;
					
					##################################
					## PHOTOGRAPHERS #################
					##################################
					if(file_exists("photog_main.php") and !empty($_SESSION['visitor_id'])){
						$photog_result = mysql_query("SELECT * FROM photographers where id = '$package->photographer'", $db);
						$photog_rows = mysql_num_rows($photog_result);
						$photog = mysql_fetch_object($photog_result);
						
						if($photog->com_percent != ""){
							$com_percent = $photog->com_percent;
						} else {
							$com_percent = $setting->com_level;
						}
											
						if($photog_rows > 0){
							if($ptype == "d"){							
								if($photo->price){
									$pprice = $photo->price;
								} else {
									$pprice = $setting->default_price;
								}
							} else {
							if($ptype == "s"){
								$sizes_result = mysql_query("SELECT price FROM sizes where id = '$sid'", $db);
								$sizes = mysql_fetch_object($sizes_result);
								$pprice = $sizes->price;
							} else {
								$print_result = mysql_query("SELECT price FROM prints where id = '$prid'", $db);
								$print = mysql_fetch_object($print_result);
								$pprice = $print->price;
							}
						}
						
							$sql2 = "INSERT INTO photog_sales (visitor_id,photo_id,odate,p_type,prid,sid,photographer,com_percent,price) VALUES ('" . $_SESSION['visitor_id'] . "','$pid','$added','$ptype','$prid','$sid','$package->photographer','$com_percent','$pprice')";
							$result2 = mysql_query($sql2);
						
						}	
					}		
					
					##################################
					##################################
					
					header("location: cart.php?gid=$gid&sgid=$sgid");
					exit;
				}
				
		break;
		
		case "update_quantity":
		if($quantity <= 0){
			$cart_result = mysql_query("SELECT photo_id FROM carts where id = '$cid'", $db);
			$cart = mysql_fetch_object($cart_result);
		
			$sql="DELETE FROM carts WHERE id = '$cid'";
			$result2 = mysql_query($sql);
			
			if(file_exists("photog_main.php")){
				$sql="DELETE FROM photog_sales WHERE visitor_id = '" . $_SESSION['visitor_id'] . "' and photo_id = '$cart->photo_id' AND prid = '$prid'";
				$result2 = mysql_query($sql);
			}
		
			header("location: cart.php?gid=$gid&sgid=$sgid");
			exit;
		} else {
			//$quan_avail = $quan_avail;
			//$quantity = $quantity;
				if($quan_avail <= $quantity){
					$quantity = $quan_avail;
				}
				$sql = "UPDATE carts SET quantity='$quantity' WHERE id = '$cid'";
				$result = mysql_query($sql);
				header("location: cart.php?gid=$gid&sgid=$sgid");
				exit;
			}
				
		break;
		
		case "delete_cart":
		
			$cart_result = mysql_query("SELECT photo_id FROM carts where id = '$cid'", $db);
			$cart = mysql_fetch_object($cart_result);
		
			$sql="DELETE FROM carts WHERE id = '$cid'";
			$result2 = mysql_query($sql);
			
			if(file_exists("photog_main.php")){
				$sql="DELETE FROM photog_sales WHERE visitor_id = '" . $_SESSION['visitor_id'] . "' and photo_id = '$cart->photo_id' AND prid = '$prid'";
				$result2 = mysql_query($sql);
			}
		
			header("location: cart.php?gid=$gid&sgid=$sgid");
			exit;
		break;
		
		case "purchase":
		//ADDED IN PS350 FOR EXTRA SECURITY
		if($setting->force_members == 1 && !$_SESSION['sub_member']){
			echo $order_error_no_login;
			exit;
		}
		// GENERATE ORDER ID
		if($_SESSION['paypal_number']){
			$order_num = $_SESSION['paypal_number'];
		} else {
			$order_num = random_gen(10,"");
			session_register("paypal_number");
			$_SESSION['paypal_number'] = $order_num;
		}
			$shipping = $_SESSION['ses_shipping'];
			$tax = $_SESSION['ses_tax'];
			$savings = $_SESSION['ses_coupon'];
			$member_id = $_SESSION['sub_member'];
			
			$visitor_result = mysql_query("SELECT id FROM visitors where visitor_id = '" . $_SESSION['visitor_id'] . "'", $db);
			$visitor_rows = mysql_num_rows($visitor_result);
			$visitor = mysql_fetch_object($visitor_result);
			
			$total = 0;
			$cart_result = mysql_query("SELECT ptype,photo_id,sid,prid,quantity FROM carts where visitor_id = '" . $_SESSION['visitor_id'] . "'", $db);
			while($cart = mysql_fetch_object($cart_result)){
			
				if($cart->ptype == "d"){
				
					$photo_result = mysql_query("SELECT price FROM uploaded_images where id = '$cart->photo_id'", $db);
					$photo = mysql_fetch_object($photo_result);			
						if($photo->price){
							$price = $photo->price;	
						} else {
							$price = $setting->default_price;
						}
					
					
				} else {
				if($cart->ptype == "s"){
					$sizes_result = mysql_query("SELECT price FROM sizes where id = '$cart->sid'", $db);
					$sizes = mysql_fetch_object($sizes_result);		
					$price = $sizes->price;
				} else {
					$print_result = mysql_query("SELECT price FROM prints where id = '$cart->prid'", $db);
					$print = mysql_fetch_object($print_result);		
				
						if($print->price){
							$price1 = $print->price;
							$price = $print->price * $cart->quantity;
						} else {
							$price1 = "5.00";
							$price = "5.00" * $cart->quantity;
						}
					}
				}
			$total = $total + $price;
			}
			
				if($visitor_rows > 0){
				$sql="DELETE FROM visitors WHERE id = '$visitor->id'";
				$result2 = mysql_query($sql);			
			}
			
			  if($setting->force_approve == 1){
			  	$approve = 1;
			  } else {
			  	$approve = 0;
			  }
			  
			if($currency->code == "JPY"){
				$total = round($total);
				$tax = round($tax);
				$shipping = round($shipping);
				$savings = round($savings);
			}
			
			$added = date("Ymd");
			$sql = "INSERT INTO visitors (visitor_id,added,order_num,shipping,price,payment_method,tax,coupon_id,savings,done,member_id) VALUES ('" . $_SESSION['visitor_id'] . "','$added','$order_num','$shipping','$total','Paypal','$tax','" . $_SESSION['coupon_id'] . "','$savings','$approve','$member_id')";
			$result = mysql_query($sql);
			
		if($_SESSION['type'] != ""){
			if($_SESSION['type'] == 1){
				$shipping = "0";
				$grand_total = round($total, 2);
			}
			if($_SESSION['type'] == 5){
				$tax = "0";
				$grand_total = round($total, 2);
			}
			if($_SESSION['type'] == 2){
				$grand_total = round($total - $savings, 2);
			}
			if($_SESSION['type'] == 3){
				$grand_total = round($total - $savings, 2);
			}
		} else {
			$grand_total = round($total - $savings, 2);
		}
			if($currency->code == "JPY"){
				$grand_total = round($grand_total);
			}	
			// FORM FOR PAYPAL
?>
			<body onLoad="document.order_form.submit();">
			<form action="https://www.paypal.com/cgi-bin/webscr" name="order_form" method="post">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="<? echo $setting->paypal_email; ?>">
			<input type="hidden" name="item_name" value="<? echo $setting->site_title;?> Photos - <? echo $_SESSION['visitor_id'] ; ?>">
			<input type="hidden" name="item_number" value="<? echo $order_num; ?>">
			<input type="hidden" name="amount" value="<? echo $grand_total; ?>">
			<input type="hidden" name="return" value="<? echo $setting->site_url; ?>/download.php?order=<?php echo $order_num; ?>">
			<input type="hidden" name="cancel_return" value="<? echo $setting->site_url; ?>">
			<input type="hidden" name="tax" value="<? echo dollar($tax); ?>">
			<input type="hidden" name="no_shipping" value="2">
			<input type="hidden" name="shipping" value="<?php echo dollar($shipping); ?>">	
			<input type="hidden" name="no_note" value="1">		
			<input type="hidden" name="notify_url" value="<? echo $setting->site_url; ?>/paypal_ipn.php">	
			<input type="hidden" name="currency_code" value="<? echo $currency->code; ?>">
			<!--<input type="hidden" name="currency_code" value="EUR"> EUROS-->
			<!--<input type="hidden" name="lc" value="US"> NOT USED-->
			</form>
			<?PHP echo $public_paypal_redirect; ?>
<?	
		break;
		
		case "purchase3":
		//ADDED IN PS350 FOR EXTRA SECURITY
		if($setting->force_members == 1 && !$_SESSION['sub_member']){
			echo $order_error_no_login;
			exit;
		}
			$order_num = random_gen(10,"");
			$shipping = $_SESSION['ses_shipping'];
			$tax = $_SESSION['ses_tax'];
			$savings = $_SESSION['ses_coupon'];
			$member_id = $_SESSION['sub_member'];
			
		if($member_id > "0"){
			$sql="SELECT * FROM `members` where id = " . $member_id;
			$member_result = mysql_query($sql, $db);
			$member = mysql_fetch_object($member_result);
			$member_address1 = $member->address1;
			$member_city = $member->city;
			$member_state = $member->state;
			$member_zip = $member->zip;
			$member_email = $member->email;
		}
			
			$visitor_result = mysql_query("SELECT id FROM visitors where visitor_id = '" . $_SESSION['visitor_id'] . "'", $db);
			$visitor_rows = mysql_num_rows($visitor_result);
			$visitor = mysql_fetch_object($visitor_result);
			
			$total = 0;
			$cart_result = mysql_query("SELECT ptype,photo_id,sid,prid,quantity FROM carts where visitor_id = '" . $_SESSION['visitor_id'] . "'", $db);
			while($cart = mysql_fetch_object($cart_result)){
			
				if($cart->ptype == "d"){
				
					$photo_result = mysql_query("SELECT price FROM uploaded_images where id = '$cart->photo_id'", $db);
					$photo = mysql_fetch_object($photo_result);			
						if($photo->price){
							$price = $photo->price;	
						} else {
							$price = $setting->default_price;
						}
					
					
				} else {
				if($cart->ptype == "s"){
					$sizes_result = mysql_query("SELECT price FROM sizes where id = '$cart->sid'", $db);
					$sizes = mysql_fetch_object($sizes_result);		
					$price = $sizes->price;
				} else {
					$print_result = mysql_query("SELECT price FROM prints where id = '$cart->prid'", $db);
					$print = mysql_fetch_object($print_result);		
				
						if($print->price){
							$price1 = $print->price;
							$price = $print->price * $cart->quantity;
						} else {
							$price1 = "5.00";
							$price = "5.00" * $cart->quantity;
						}
					}
				}
			$total = $total + $price;
			}
			
				if($visitor_rows > 0){
				$sql="DELETE FROM visitors WHERE id = '$visitor->id'";
				$result2 = mysql_query($sql);			
			}
			 if($setting->force_approve == 1){
			  	$approve = 1;
			  } else {
			  	$approve = 0;
			  }
			  
			 if($currency->code == "JPY"){
				$total = round($total);
				$tax = round($tax);
				$shipping = round($shipping);
				$savings = round($savings);
			}
			
			$added = date("Ymd");
			$sql = "INSERT INTO visitors (visitor_id,added,order_num,shipping,price,payment_method,tax,coupon_id,savings,done,member_id) VALUES ('" . $_SESSION['visitor_id'] . "','$added','$order_num','$shipping','$total','Authorize.net','$tax','" . $_SESSION['coupon_id'] . "','$savings','$approve','$member_id')";
			$result = mysql_query($sql);
			
			$grand_total = round($total - $savings + $shipping + $tax, 2);
			
			if($currency->code == "JPY"){
				$grand_total = round($grand_total);
			}	

?>
<!--<FORM action="https://test.authorize.net/gateway/transact.dll" method="POST">-->
  <p>
  <!-- Uncomment the line ABOVE for test accounts or BELOW for live merchant accounts -->
	<body onLoad="document.order_form.submit();">
  <FORM action="https://secure.authorize.net/gateway/transact.dll" name="order_form" method="post">
  <?

include ("simlib.php");

// Insert the form elements required for SIM by calling InsertFP
$ret = InsertFP ($setting->api_login_id, $setting->transaction_key, $grand_total, $order_num); 


//*** IF YOU ARE PASSING CURRENCY CODE uncomment and use the following instead of the InsertFP invocation above  ***
// $ret = InsertFP ($loginid, $x_tran_key, $total, $sequence, $currencycode);

// Insert rest of the form elements similiar to the legacy weblink integration
$x_Description = $setting->site_title . " Photos -".  $_SESSION['visitor_id']; 
echo ("<input type=\"hidden\" name=\"x_description\" value=\"" . $x_Description . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_invoice_num\" value=\"" . $order_num . "\">\n" );
//echo ("<input type=\"hidden\" name=\"x_first_name\" value=\"" . $member->name . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_address\" value=\"" . $member_address1 . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_city\" value=\"" . $member_city . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_state\" value=\"" . $member_state . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_zip\" value=\"" . $member_zip . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_email\" value=\"" . $member_email . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_login\" value=\"" . $setting->api_login_id . "\">\n");
echo ("<input type=\"hidden\" name=\"x_amount\" value=\"" . $grand_total . "\">\n");
echo ("<input type=\"hidden\" name=\"x_tax\" value=\"" . $tax . "\">\n");
echo ("<input type=\"hidden\" name=\"x_freight\" value=\"" . $shipping . "\">\n");
echo ("<input type=\"hidden\" name=\"x_receipt_link_method\" value=\"POST\">\n");
echo ("<input type=\"hidden\" name=\"x_receipt_link_url\" value=\"" . $setting->site_url . "/authorize_ipn.php?order="  . $order_num . "&memberid=" . $member_id . "\">\n");
//echo ("<input type=\"hidden\" name=\"x_relay_url\" value=\"" .$setting->site_url. "/authorize_ipn.php?order="  . $order_num ."&memberid=". $member_id . "\">\n");
// *** IF YOU ARE PASSING CURRENCY CODE uncomment the line below *****
//echo ("<input type=\"hidden\" name=\"x_currency_code\" value=\"" . $currencycode . "\">\n");
?>
 <!-- <INPUT type="hidden" name="x_test_request" value="TRUE"> ---->
<INPUT type="hidden" name="x_relay_response" value="TRUE"> 
<!-- <INPUT type="hidden" name="x_receipt_link_method" value="POST"> ----> 
<INPUT type="hidden" name="x_show_form" value="PAYMENT_FORM">  
</FORM>
	<?PHP echo $public_authorize_redirect; ?>
<?	
		break;
		
		case "purchase2":
		//ADDED IN PS350 FOR EXTRA SECURITY
		if($setting->force_members == 1 && !$_SESSION['sub_member']){
			echo $order_error_no_login;
			exit;
		}
			// GENERATE ORDER ID
			$order_num = random_gen(10,"");
			$shipping = $_SESSION['ses_shipping'];
			$tax = $_SESSION['ses_tax'];
			$savings = $_SESSION['ses_coupon'];
			$member_id = $_SESSION['sub_member'];
			
			$visitor_result = mysql_query("SELECT id FROM visitors where visitor_id = '" . $_SESSION['visitor_id'] . "'", $db);
			$visitor_rows = mysql_num_rows($visitor_result);
			$visitor = mysql_fetch_object($visitor_result);
			
			$total = 0;
			$cart_result = mysql_query("SELECT ptype,photo_id,sid,prid,quantity FROM carts where visitor_id = '" . $_SESSION['visitor_id'] . "'", $db);
			while($cart = mysql_fetch_object($cart_result)){
				
				if($cart->ptype == "d"){
				
					$photo_result = mysql_query("SELECT price FROM uploaded_images where id = '$cart->photo_id'", $db);
					$photo = mysql_fetch_object($photo_result);			
						if($photo->price){
							$price = $photo->price;	
						} else {
							$price = $setting->default_price;
						}
					
					
				} else {
				if($cart->ptype == "s"){
					$sizes_result = mysql_query("SELECT price FROM sizes where id = '$cart->sid'", $db);
					$sizes = mysql_fetch_object($sizes_result);		
					$price = $sizes->price;
				} else {
					$print_result = mysql_query("SELECT price FROM prints where id = '$cart->prid'", $db);
					$print = mysql_fetch_object($print_result);		
				
						if($print->price){
							$price1 = $print->price;
							$price = $print->price * $cart->quantity;
						} else {
							$price1 = "5.00";
							$price = "5.00" * $cart->quantity;
						}
					}
				}
			$total = $total + $price;
			}
			
			if($visitor_rows > 0){
				$sql="DELETE FROM visitors WHERE id = '$visitor->id'";
				$result2 = mysql_query($sql);			
			}
			
			 if($setting->force_approve == 1){
			  	$approve = 1;
			  } else {
			  	$approve = 0;
			  }
			  
			 if($currency->code == "JPY"){
				$total = round($total);
				$tax = round($tax);
				$shipping = round($shipping);
				$savings = round($savings);
			}
			
			$added = date("Ymd");
			$sql = "INSERT INTO visitors (visitor_id,added,order_num,shipping,price,payment_method,tax,coupon_id,savings,done,member_id) VALUES ('" . $_SESSION['visitor_id'] . "','$added','$order_num','$shipping','$total','2Checkout','$tax','" . $_SESSION['coupon_id'] . "','$savings','$approve','$member_id')";
			$result = mysql_query($sql);
			
			$grand_total = round($total - $savings + $shipping + $tax, 2);
			
			if($currency->code == "JPY"){
				$grand_total = round($grand_total);
			}	
			// GO TO 2CHECKOUT.COM
			//header("location: https://www.2checkout.com/cgi-bin/sbuyers/cartpurchase.2c?sid=" . $setting->twocheck_account . "&total=" . $grand_total . "&cart_order_id=" . $order_num);
			
			
			// 2checkout V2
			if($checkout_demo_mode == 0){
				//header("location: https://www.2checkout.com/2co/buyer/purchase?sid=" . $setting->twocheck_account . "&total=" . $total . "&cart_order_id=" . $order_num);
				//2CO Update
				header("location: https://www.2checkout.com/2co/buyer/purchase?sid=" . $setting->twocheck_account . "&total=" . $grand_total . "&cart_order_id=" . $order_num . "&c_prod=" . $order_num . "&id_type=1&c_name=Photos&c_price=" . $grand_total . "&x_receipt_link_url=" . $setting->site_url . "/2co_ipn.php&c_description=" . $setting->site_title . " Photos.");
			} else {
				header("location: https://www.2checkout.com/2co/buyer/purchase?sid=" . $setting->twocheck_account . "&total=" . $grand_total . "&cart_order_id=" . $order_num . "&demo=Y");
			}
			
			
			exit;
		break;
		
		case "mygate":
		//ADDED IN PS350 FOR EXTRA SECURITY
		if($setting->force_members == 1 && !$_SESSION['sub_member']){
			echo $order_error_no_login;
			exit;
		}
			// GENERATE ORDER ID
			$order_num = random_gen(10,"");
			$shipping = $_SESSION['ses_shipping'];
			$tax = $_SESSION['ses_tax'];
			$savings = $_SESSION['ses_coupon'];
			$member_id = $_SESSION['sub_member'];
			
			$visitor_result = mysql_query("SELECT id FROM visitors where visitor_id = '" . $_SESSION['visitor_id'] . "'", $db);
			$visitor_rows = mysql_num_rows($visitor_result);
			$visitor = mysql_fetch_object($visitor_result);
			
			$total = 0;
			$cart_result = mysql_query("SELECT ptype,photo_id,sid,prid,quantity FROM carts where visitor_id = '" . $_SESSION['visitor_id'] . "'", $db);
			while($cart = mysql_fetch_object($cart_result)){
				
				if($cart->ptype == "d"){
				
					$photo_result = mysql_query("SELECT price FROM uploaded_images where id = '$cart->photo_id'", $db);
					$photo = mysql_fetch_object($photo_result);			
						if($photo->price){
							$price = $photo->price;	
						} else {
							$price = $setting->default_price;
						}
					
					
				} else {
				if($cart->ptype == "s"){
					$sizes_result = mysql_query("SELECT price FROM sizes where id = '$cart->sid'", $db);
					$sizes = mysql_fetch_object($sizes_result);		
					$price = $sizes->price;
				} else {
					$print_result = mysql_query("SELECT price FROM prints where id = '$cart->prid'", $db);
					$print = mysql_fetch_object($print_result);		
				
						if($print->price){
							$price1 = $print->price;
							$price = $print->price * $cart->quantity;
						} else {
							$price1 = "5.00";
							$price = "5.00" * $cart->quantity;
						}
					}
				}
			$total = $total + $price;
			}
			
			if($visitor_rows > 0){
				$sql="DELETE FROM visitors WHERE id = '$visitor->id'";
				$result2 = mysql_query($sql);			
			}
			
			 if($setting->force_approve == 1){
			  	$approve = 1;
			  } else {
			  	$approve = 0;
			  }
			  
			 if($currency->code == "JPY"){
				$total = round($total);
				$tax = round($tax);
				$shipping = round($shipping);
				$savings = round($savings);
			}
			
			$added = date("Ymd");
			$sql = "INSERT INTO visitors (visitor_id,added,order_num,shipping,price,payment_method,tax,coupon_id,savings,done,member_id) VALUES ('" . $_SESSION['visitor_id'] . "','$added','$order_num','$shipping','$total','MyGate','$tax','" . $_SESSION['coupon_id'] . "','$savings','$approve','$member_id')";
			$result = mysql_query($sql);
			
			$grand_total = round($total - $savings + $shipping + $tax, 2);
			
			if($currency->code == "JPY"){
				$grand_total = round($grand_total);
			}	
			
			// MAKE SURE CUSTOMER ADDS REFERRAL URL TO THEIR MYGATE ACCOUNT TO GET IT WORKING

?>
			<body onLoad="document.order_form.submit();">
			<?PHP echo $public_mygate_redirect; ?>
            <form name="order_form" action="https://www.mygate.co.za/virtual/4x0x0/dsp_details.cfm" method="post">

                <!--Transaction Mode (Mode)-->
                <input name="Mode" type="hidden" class="SmallLabel" width="50px"  value="1" readonly="true" style="" ><!-- 0=Test | 1=Live --> 
                <!--Merchant ID (txtMerchantID)-->
                <input name="txtMerchantID" type="hidden" class="SmallLabel" value="<? echo $setting->mygateid; ?>" readonly="true">
                <!--Application ID (txtApplicationID)-->
                <input name="txtApplicationID" type="hidden" class="SmallLabel" value="<? echo $setting->mygateaid; ?>" readonly="true">
                <!--Merchant Reference (txtMerchantReference)-->
                <input name="txtMerchantReference" type="hidden" class="SmallLabel" value="<? echo $order_num; ?>" readonly="true" style="width:400" >
                <!--Price (txtPrice) -->
                <input name="txtPrice" type="hidden" class="SmallLabel" value="<?php echo $grand_total; ?>" readonly="true" >
                <!--Currency Code (txtCurrencyCode)-->
                <input name="txtCurrencyCode" type="hidden" class="SmallLabel" value="ZAR" readonly="true">
                <!--Display Price (txtDisplayPrice)-->
                <input name="txtDisplayPrice" type="hidden" class="SmallLabel" value="<?php echo $grand_total; ?>" readonly="true">
                <!--Display Currency Code (txtDisplayCurrencyCode)-->
                <input name="txtDisplayCurrencyCode" type="hidden" class="SmallLabel" value="ZAR" readonly="true">
                <!--Success Re-Direct URL (txtRedirectSuccessfulURL)-->
                <input name="txtRedirectSuccessfulURL" type="hidden" class="SmallLabel" value="<? echo $setting->site_url; ?>/mygate_ipn.php?order=<?php echo $order_num; ?>" readonly="true" >
                <!--Failure Re-Direct URL (txtRedirectFailedURL)-->
                <input name="txtRedirectFailedURL" type="hidden" class="SmallLabel" value="<? echo $setting->site_url; ?>/mygate_ipn.php?order=<?PHP echo $order_num; ?>&message=failed" readonly="true" >
                <!--Additional Variable 1 (Variable1) -->
                <input name="Variable1" type="hidden" class="SmallLabel" value="<? echo $order_num; ?>" readonly="true" >
                <!--Additional Variable 2 (Variable1)-->
                <input name="Variable2" type="hidden" class="SmallLabel" value="<? echo $_SESSION['mem_email']; ?>" readonly="true">
                <!--Additional Variable 3 (Variable1)-->
                <input name="Variable3" type="hidden" class="SmallLabel" value="<?php echo $grand_total; ?>" readonly="true">
                <!--<input type="submit">-->
            </form>
            
            </body>
<?php
			
			
			exit;
		break;
		
		case "sub_signup":
			//ADDED IN PS330 TO STORE FORM VARIBLES FOR AUTO FILL ON FAILED SIGNUPS
		  unset($_SESSION['form_name1']);
		  unset($_SESSION['form_email1']);
		  unset($_SESSION['form_phone1']);
		  unset($_SESSION['form_address11']);
		  unset($_SESSION['form_address21']);
		  unset($_SESSION['form_city1']);
		  unset($_SESSION['form_state1']);
		  unset($_SESSION['form_zip1']);
		  unset($_SESSION['form_bio1']);
		  unset($_SESSION['form_note1']);
		  
			session_register("form_name1");
			$_SESSION['form_name1'] = $_POST['name'];
			session_register("form_email1");
			$_SESSION['form_email1'] = $_POST['email'];
			session_register("form_phone1");
			$_SESSION['form_phone1'] = $_POST['phone'];
			session_register("form_address11");
			$_SESSION['form_address11'] = $_POST['address1'];
			session_register("form_address21");
			$_SESSION['form_address21'] = $_POST['address2'];
			session_register("form_city1");
			$_SESSION['form_city1'] = $_POST['city'];
			session_register("form_state1");
			$_SESSION['form_state1'] = $_POST['state'];
			session_register("form_zip1");
			$_SESSION['form_zip1'] = $_POST['zip'];
			
			$replace_char = array("%20", " ", "+", "\"", ">", "<", "'", "%22", "%3E", "@");
			$check_name = str_replace($replace_char, "", $_POST['name']);
			$check_email = str_replace($replace_char, "", $_POST['email']);
			$check_password = str_replace($replace_char, "", $_POST['password']);
			if(strlen(trim($check_name)) == 0 OR strlen(trim($check_email)) == 0 OR strlen(trim($check_password)) == 0){
				header("location: subscribe.php?error=null");
				exit;
			}

			// GENERATE ORDER ID
			$order_num = random_gen(10,"");
		
			$member_result = mysql_query("SELECT id FROM members where email = '$email'", $db);
			$member_rows = mysql_num_rows($member_result);
			
			if($member_rows > 0){
				header("location: subscribe.php?error=email_exists&name=$name");
				exit;		
			}
			
			if($cycle_time == "year"){
				$sub_length = "Y";
			} else {
				if($cycle_time == "month"){
				$sub_length = "M";
			} else {
				$sub_length = "F";
			}
		}
			
			$tax_amount_year = $tax_amount_year;
			$tax_amount_month = $tax_amount_month;
		if($sub_length == "F"){
			$added = date("Ymd");
			$sql = "INSERT INTO members (name,email,password,added,order_num,sub_length,status,phone,address1,address2,city,state,zip,country) VALUES ('$name','$email','$password','$added','$order_num','$sub_length','1','$phone','$address1','$address2','$city','$state','$zip','$country')";
			$result = mysql_query($sql);
		} else {
			$added = date("Ymd");
			$sql = "INSERT INTO members (name,email,password,added,order_num,sub_length,phone,address1,address2,city,state,zip,country) VALUES ('$name','$email','$password','$added','$order_num','$sub_length','$phone','$address1','$address2','$city','$state','$zip','$country')";
			$result = mysql_query($sql);
			$member1_result = mysql_query("SELECT id FROM members where email = '$email'", $db);
			$member1 = mysql_fetch_object($member1_result);
		}
		email(13,$to);
			if($sub_length == "F"){
					if($_SESSION['cart_from'] == "cart"){
						unset($_SESSION['cart_from']);
						header("location: login.php?from=cart&message=cart");
						exit;
					} else {
						header("location: login.php?message=free");
						exit;
					}
			} else {
			if($p_method == "2checkout"){
				if($cycle_time == "year"){
				if($tax_amount_year > 0){
					$subprice = $setting->sub_price + $tax_amount_year;
				} else {
					$subprice = $setting->sub_price;
				}
				} else {
					if($tax_amount_month > 0){
					$subprice = $setting->sub_price_month + $tax_amount_month;
				} else {
					$subprice = $setting->sub_price_month;
				}
				}	
				
				header("location: https://www.2checkout.com/cgi-bin/sbuyers/cartpurchase.2c?sid=" . $setting->twocheck_account . "&total=" . $subprice . "&cart_order_id=" . $order_num);
				exit;
			}
			 if($p_method == "paypal"){
				if($cycle_time == "year"){
					if($tax_amount_year > 0){
					$subprice = $setting->sub_price + $tax_amount_year;
				} else {
					$subprice = $setting->sub_price;
				}
				} else {
					if($tax_amount_month > 0){
					$subprice = $setting->sub_price_month + $tax_amount_month;
				} else {
					$subprice = $setting->sub_price_month;
				}
			}
			
?>
			<body onLoad="document.order_form.submit();">
			<form action="https://www.paypal.com/cgi-bin/webscr" name="order_form" method="post">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="<? echo $setting->paypal_email; ?>">
			<input type="hidden" name="item_name" value="<? echo $setting->site_title;?> Subscription - <? echo $order_num; ?>">
			<input type="hidden" name="item_number" value="<? echo $order_num; ?>">
			<input type="hidden" name="amount" value="<? echo $subprice; ?>">
			<input type="hidden" name="return" value="<? echo $setting->site_url; ?>/pp_return_sub.php">
			<input type="hidden" name="cancel_return" value="<? echo $setting->site_url; ?>">
			<input type="hidden" name="no_shipping" value="1">
			<input type="hidden" name="no_note" value="1">			
			<input type="hidden" name="notify_url" value="<? echo $setting->site_url; ?>/paypal_ipn_sub.php">			
			<input type="hidden" name="currency_code" value="<? echo $currency->code; ?>">
			<!--<input type="hidden" name="currency_code" value="EUR"> EUROS-->
			<!--<input type="hidden" name="lc" value="US"> NOT USED-->
			</form>
			<?PHP echo $public_paypal_sub_redirect; ?>

<?		
			}
			
		if($p_method == "mygate"){
			if($cycle_time == "year"){
				if($tax_amount_year > 0){
					$subprice = $setting->sub_price + $tax_amount_year;
				} else {
					$subprice = $setting->sub_price;
				}
				} else {
				if($tax_amount_month > 0){
					$subprice = $setting->sub_price_month + $tax_amount_month;
				} else {
					$subprice = $setting->sub_price_month;
				}
			}
			?>
			<body onLoad="document.order_form.submit();">
			<?PHP echo $public_mygate_sub_redirect; ?>
            <form name="order_form" action="https://www.mygate.co.za/virtual/4x0x0/dsp_details.cfm" method="post">
                <!--Transaction Mode (Mode)-->
                <input name="Mode" type="hidden" class="SmallLabel" width="50px"  value="1" readonly="true" style="" ><!-- 0=Test | 1=Live --> 
                <!--Merchant ID (txtMerchantID)-->
                <input name="txtMerchantID" type="hidden" class="SmallLabel" value="<? echo $setting->mygateid; ?>" readonly="true">
                <!--Application ID (txtApplicationID)-->
                <input name="txtApplicationID" type="hidden" class="SmallLabel" value="<? echo $setting->mygateaid; ?>" readonly="true">
                <!--Merchant Reference (txtMerchantReference)-->
                <input name="txtMerchantReference" type="hidden" class="SmallLabel" value="<? echo $order_num; ?>" readonly="true" style="width:400" >
                <!--Price (txtPrice) -->
                <input name="txtPrice" type="hidden" class="SmallLabel" value="<?php echo $subprice; ?>" readonly="true" >
                <!--Currency Code (txtCurrencyCode)-->
                <input name="txtCurrencyCode" type="hidden" class="SmallLabel" value="ZAR" readonly="true">
                <!--Display Price (txtDisplayPrice)-->
                <input name="txtDisplayPrice" type="hidden" class="SmallLabel" value="<?php echo $subprice; ?>" readonly="true">
                <!--Display Currency Code (txtDisplayCurrencyCode)-->
                <input name="txtDisplayCurrencyCode" type="hidden" class="SmallLabel" value="ZAR" readonly="true">
                <!--Success Re-Direct URL (txtRedirectSuccessfulURL)-->
                <input name="txtRedirectSuccessfulURL" type="hidden" class="SmallLabel" value="<? echo $setting->site_url; ?>/mygate_ipn_sub.php?order=<?php echo $order_num; ?>" readonly="true" >
                <!--Failure Re-Direct URL (txtRedirectFailedURL)-->
                <input name="txtRedirectFailedURL" type="hidden" class="SmallLabel" value="<? echo $setting->site_url; ?>/mygate_ipn_sub.php?order=<?PHP echo $order_num; ?>&message=failed" readonly="true" >
                <!--Additional Variable 1 (Variable1) -->
                <input name="Variable1" type="hidden" class="SmallLabel" value="<? echo $order_num; ?>" readonly="true" >
                <!--Additional Variable 2 (Variable1)-->
                <input name="Variable2" type="hidden" class="SmallLabel" value="<? echo $email; ?>" readonly="true">
                <!--Additional Variable 3 (Variable1)-->
                <input name="Variable3" type="hidden" class="SmallLabel" value="<?php echo $subprice; ?>" readonly="true">
                <!--<input type="submit">-->
            </form>
            </body>
      <?
		}
		
		if($p_method == "plugnpay"){
			if($cycle_time == "year"){
				if($tax_amount_year > 0){
					$subprice = $setting->sub_price + $tax_amount_year;
				} else {
					$subprice = $setting->sub_price;
				}
				} else {
				if($tax_amount_month > 0){
					$subprice = $setting->sub_price_month + $tax_amount_month;
				} else {
					$subprice = $setting->sub_price_month;
				}
			}
			?>
			<FORM method="post" action="plugnpay.php">

								<input type="hidden" name="publisher_name" value="pnpdemo">
								<input type="hidden" name="publisher_email" value="<?php echo $setting->support_email; ?>">
								<input type="hidden" name="card_amount" value="<?php echo  $subprice; ?>">
								<input type="hidden" name="item_number" value="<? echo $order_num; ?>">
								<input type="hidden" name="subscription" value="subscription">
								
								<?PHP echo $public_pnp_sub_enter_info; ?><br /><br />
								<strong><?PHP echo $public_pnp_sub_total; ?><?php echo $currency->sign . $subprice; ?></strong><br /><br />
								
								<?PHP echo $public_pnp_email; ?><input type="text" name="email" <?PHP if($_SESSION['form_email']){ ?>value="<?PHP echo $_SESSION['form_email']; ?>"<? } ?> size="30" maxlength="100">
							<?PHP echo $public_pnp_name; ?><input type="text" name="card_name" <?PHP if($_SESSION['form_name']){ ?>value="<?PHP echo $_SESSION['form_name']; ?>"<? } ?> size="30" maxlength="30">
								<?PHP echo $public_pnp_address1; ?><input type="text" name="card_address1" <?PHP if($_SESSION['form_address1']){ ?>value="<?PHP echo $_SESSION['form_address1']; ?>"<? } ?> size="30" maxlength="30">
								<?PHP echo $public_pnp_address2; ?><input type="text" name="card_address2" <?PHP if($_SESSION['form_address2']){ ?>value="<?PHP echo $_SESSION['form_address2']; ?>"<? } ?> size="30" maxlength="30">
								<?PHP echo $public_pnp_city; ?><input type="text" name="card_city" size="30" <?PHP if($_SESSION['form_city']){ ?>value="<?PHP echo $_SESSION['form_city']; ?>"<? } ?> maxlength="30">
								<?PHP echo $public_pnp_state; ?><input type="text" name="card_state" size="2" <?PHP if($_SESSION['form_state']){ ?>value="<?PHP echo $_SESSION['form_state']; ?>"<? } ?> maxlength="2">
								<?PHP echo $public_pnp_zip; ?><input type="text" name="card_zip" size="12" <?PHP if($_SESSION['form_zip']){ ?>value="<?PHP echo $_SESSION['form_zip']; ?>"<? } ?> maxlength="12">
								
								<?PHP echo $public_pnp_cc_number; ?><input type="text" name="card_number" size="20" maxlength="20">
								<?PHP echo $public_pnp_cc_expire; ?><input type="text" name="card_exp" size="6" maxlength="6"> (MM/YY)
								<?PHP echo $public_pnp_cc_security_number; ?><input type="text" name="card_cvv" size="6" maxlength="6">
		
								<p align="left"><input type="submit" value="<?PHP echo $public_pnp_sub_form_submit; ?>"></p>
							</form>
							<?
		}
		
		if($p_method == "checkmoney"){
			if($cycle_time == "year"){
				if($tax_amount_year > 0){
					$subprice = $setting->sub_price + $tax_amount_year;
				} else {
					$subprice = $setting->sub_price;
				}
				} else {
				if($tax_amount_month > 0){
					$subprice = $setting->sub_price_month + $tax_amount_month;
				} else {
					$subprice = $setting->sub_price_month;
				}
			}
			session_register("sub_type");
			$_SESSION['sub_type'] = "sub";
			session_register("sub_email");
			$_SESSION['sub_email'] = $email;
			session_register("id");
			$_SESSION['id'] = $member1->id;
			session_register("ses_total");
			$_SESSION['ses_total'] = $subprice;
			header("location: order.php?type=sub&order=$order_num");
		}
		
		if($p_method == "authorize"){
			if($cycle_time == "year"){
				if($tax_amount_year > 0){
					$subprice = $setting->sub_price + $tax_amount_year;
				} else {
					$subprice = $setting->sub_price;
				}
				} else {
				if($tax_amount_month > 0){
					$subprice = $setting->sub_price_month + $tax_amount_month;
				} else {
					$subprice = $setting->sub_price_month;
				}
			}
			?>
<!--<FORM action="https://test.authorize.net/gateway/transact.dll" method="POST">-->
  <p>
  <!-- Uncomment the line ABOVE for test accounts or BELOW for live merchant accounts -->
	<body onLoad="document.order_form.submit();">
  <FORM action="https://secure.authorize.net/gateway/transact.dll" name="order_form" method="post">
  <?

include ("simlib.php");

// Insert the form elements required for SIM by calling InsertFP
$ret = InsertFP ($setting->api_login_id, $setting->transaction_key, $subprice, $order_num); 


//*** IF YOU ARE PASSING CURRENCY CODE uncomment and use the following instead of the InsertFP invocation above  ***
// $ret = InsertFP ($loginid, $x_tran_key, $total, $sequence, $currencycode);

// Insert rest of the form elements similiar to the legacy weblink integration
$x_Description = $setting->site_title . "Subscription -".  $order_num; 
echo ("<input type=\"hidden\" name=\"x_description\" value=\"" . $x_Description . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_invoice_num\" value=\"" . $order_num . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_address\" value=\"" . $address1 . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_city\" value=\"" . $city . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_state\" value=\"" . $state . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_zip\" value=\"" . $zip . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_email\" value=\"" . $email . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_login\" value=\"" . $setting->api_login_id . "\">\n");
echo ("<input type=\"hidden\" name=\"x_amount\" value=\"" . $subprice . "\">\n");
echo ("<input type=\"hidden\" name=\"x_receipt_link_method\" value=\"POST\">\n");
echo ("<input type=\"hidden\" name=\"x_receipt_link_url\" value=\"" . $setting->site_url . "/authorize_ipn.php?order="  . $order_num . "&memberid=" . $member_id . "\">\n");
//echo ("<input type=\"hidden\" name=\"x_relay_url\" value=\"" .$setting->site_url. "/authorize_ipn.php?order="  . $order_num ."&memberid=". $member_id . "\">\n");
//echo ("<input type=\"hidden\" name=\"x_currency_code\" value=\"" . $currencycode . "\">\n");
?>
 <!-- <INPUT type="hidden" name="x_test_request" value="TRUE"> ---->
<INPUT type="hidden" name="x_relay_response" value="TRUE"> 
<!-- <INPUT type="hidden" name="x_receipt_link_method" value="POST"> ----> 
<INPUT type="hidden" name="x_show_form" value="PAYMENT_FORM">  
</FORM>
	<?PHP echo $public_authorize_sub_redirect; ?>	
<?	
	}	
			}
		break;
		
		case "sub_signup_full":
		
			//ADDED IN PS330 TO STORE FORM VARIBLES FOR AUTO FILL ON FAILED SIGNUPS
			unset($_SESSION['form_name1']);
		  unset($_SESSION['form_email1']);
		  unset($_SESSION['form_phone1']);
		  unset($_SESSION['form_address11']);
		  unset($_SESSION['form_address21']);
		  unset($_SESSION['form_city1']);
		  unset($_SESSION['form_state1']);
		  unset($_SESSION['form_zip1']);
		  unset($_SESSION['form_bio1']);
		  unset($_SESSION['form_note1']);
		  
			session_register("form_name1");
			$_SESSION['form_name1'] = $_POST['name'];
			session_register("form_email1");
			$_SESSION['form_email1'] = $_POST['email'];
			session_register("form_phone1");
			$_SESSION['form_phone1'] = $_POST['phone'];
			session_register("form_address11");
			$_SESSION['form_address11'] = $_POST['address1'];
			session_register("form_address21");
			$_SESSION['form_address21'] = $_POST['address2'];
			session_register("form_city1");
			$_SESSION['form_city1'] = $_POST['city'];
			session_register("form_state1");
			$_SESSION['form_state1'] = $_POST['state'];
			session_register("form_zip1");
			$_SESSION['form_zip1'] = $_POST['zip'];
			
			if($_SESSION['id']){
				$id = $_SESSION['id'];
			} else {
				$id = $_SESSION['sub_member'];
			}
	
			$member_result = mysql_query("SELECT order_num,id FROM members where id = '$id'", $db);
			$member = mysql_fetch_object($member_result);
			
			if($member->order_num == ""){
				$order_num = random_gen(10,"");
			} else {
				$order_num = $member->order_num;
			}
			
			if($cycle_time == "year"){
				$sub_length = "Y";
			} else {
				if($cycle_time == "month"){
				$sub_length = "M";
			} else {
				$sub_length = "F";
			}
		}
		$tax_amount_year = $tax_amount_year;
		$tax_amount_month = $tax_amount_month;
		
		if($sub_length == "F"){
			$added = date("Ymd");
			$sql = "UPDATE members SET added='$added',name='$name',email='$email',password='$password',sub_length='$sub_length',status='1',phone='$phone',address1='$address1',address2='$address2',city='$city',state='$state',zip='$zip',country='$country' WHERE id = '$member->id'";
			$result = mysql_query($sql);
			header("location: login.php?message=free");
			exit;
		} else {
			$added = date("Ymd");
			$sql = "UPDATE members SET name='$name',email='$email',password='$password',added='$added',order_num='$order_num',sub_length='$sub_length',status='0',phone='$phone',address1='$address1',address2='$address2',city='$city',state='$state',zip='$zip',country='$country' WHERE id = '$member->id'";
			$result = mysql_query($sql);
		}
		
		unset($_SESSION['sub_member']);
		unset($_SESSION['mem_name']);
		unset($_SESSION['mem_down_limit']);
		unset($_SESSION['sub_type']);
			
			if($sub_length == "F"){
				header("location: login.php?message=free");
				exit;
			} else {
			if($p_method == "2checkout"){
				if($cycle_time == "year"){
				if($tax_amount_year > 0){
					$subprice = $setting->sub_price + $tax_amount_year;
				} else {
					$subprice = $setting->sub_price;
				}
				} else {
					if($tax_amount_month > 0){
					$subprice = $setting->sub_price_month + $tax_amount_month;
				} else {
					$subprice = $setting->sub_price_month;
				}
				}
				header("location: https://www.2checkout.com/cgi-bin/sbuyers/cartpurchase.2c?sid=" . $setting->twocheck_account . "&total=" . $subprice . "&cart_order_id=" . $order_num);
				exit;
			} 
			if($p_method == "paypal"){
				if($cycle_time == "year"){
					if($tax_amount_year > 0){
					$subprice = $setting->sub_price + $tax_amount_year;
				} else {
					$subprice = $setting->sub_price;
				}
				} else {
					if($tax_amount_month > 0){
					$subprice = $setting->sub_price_month + $tax_amount_month;
				} else {
					$subprice = $setting->sub_price_month;
				}
			}
?>
			<body onload="document.order_form.submit();">
			<form action="https://www.paypal.com/cgi-bin/webscr" name="order_form" method="post">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="<? echo $setting->paypal_email; ?>">
			<input type="hidden" name="item_name" value="<? echo $setting->site_title;?> Subscription - Renewal | Upgrade">
			<input type="hidden" name="item_number" value="<? echo $order_num; ?>">
			<input type="hidden" name="amount" value="<? echo $subprice; ?>">
			<input type="hidden" name="return" value="<? echo $setting->site_url; ?>/pp_return_sub.php">
			<input type="hidden" name="cancel_return" value="<? echo $setting->site_url; ?>">
			<input type="hidden" name="no_shipping" value="1">
			<input type="hidden" name="no_note" value="1">			
			<input type="hidden" name="notify_url" value="<? echo $setting->site_url; ?>/paypal_ipn_sub.php">			
			<input type="hidden" name="currency_code" value="<? echo $currency->code; ?>">
			<!--<input type="hidden" name="currency_code" value="EUR"> EUROS-->
			<!--<input type="hidden" name="lc" value="US"> NOT USED-->
			</form>
			<?PHP echo $public_twoco_sub_redirect; ?>

<?		
				}
				
		if($p_method == "mygate"){
			if($cycle_time == "year"){
				if($tax_amount_year > 0){
					$subprice = $setting->sub_price + $tax_amount_year;
				} else {
					$subprice = $setting->sub_price;
				}
				} else {
				if($tax_amount_month > 0){
					$subprice = $setting->sub_price_month + $tax_amount_month;
				} else {
					$subprice = $setting->sub_price_month;
				}
			}
			?>
			<body onLoad="document.order_form.submit();">
			<?PHP echo $public_mygate_sub_redirect; ?>
            <form name="order_form" action="https://www.mygate.co.za/virtual/4x0x0/dsp_details.cfm" method="post">
                <!--Transaction Mode (Mode)-->
                <input name="Mode" type="hidden" class="SmallLabel" width="50px"  value="1" readonly="true" style="" ><!-- 0=Test | 1=Live --> 
                <!--Merchant ID (txtMerchantID)-->
                <input name="txtMerchantID" type="hidden" class="SmallLabel" value="<? echo $setting->mygateid; ?>" readonly="true">
                <!--Application ID (txtApplicationID)-->
                <input name="txtApplicationID" type="hidden" class="SmallLabel" value="<? echo $setting->mygateaid; ?>" readonly="true">
                <!--Merchant Reference (txtMerchantReference)-->
                <input name="txtMerchantReference" type="hidden" class="SmallLabel" value="<? echo $order_num; ?>" readonly="true" style="width:400" >
                <!--Price (txtPrice) -->
                <input name="txtPrice" type="hidden" class="SmallLabel" value="<?php echo $subprice; ?>" readonly="true" >
                <!--Currency Code (txtCurrencyCode)-->
                <input name="txtCurrencyCode" type="hidden" class="SmallLabel" value="ZAR" readonly="true">
                <!--Display Price (txtDisplayPrice)-->
                <input name="txtDisplayPrice" type="hidden" class="SmallLabel" value="<?php echo $subprice; ?>" readonly="true">
                <!--Display Currency Code (txtDisplayCurrencyCode)-->
                <input name="txtDisplayCurrencyCode" type="hidden" class="SmallLabel" value="ZAR" readonly="true">
                <!--Success Re-Direct URL (txtRedirectSuccessfulURL)-->
                <input name="txtRedirectSuccessfulURL" type="hidden" class="SmallLabel" value="<? echo $setting->site_url; ?>/mygate_ipn_sub.php?order=<?php echo $order_num; ?>" readonly="true" >
                <!--Failure Re-Direct URL (txtRedirectFailedURL)-->
                <input name="txtRedirectFailedURL" type="hidden" class="SmallLabel" value="<? echo $setting->site_url; ?>/mygate_ipn_sub.php?order=<?PHP echo $order_num; ?>&message=failed" readonly="true" >
                <!--Additional Variable 1 (Variable1) -->
                <input name="Variable1" type="hidden" class="SmallLabel" value="<? echo $order_num; ?>" readonly="true" >
                <!--Additional Variable 2 (Variable1)-->
                <input name="Variable2" type="hidden" class="SmallLabel" value="<? echo $email; ?>" readonly="true">
                <!--Additional Variable 3 (Variable1)-->
                <input name="Variable3" type="hidden" class="SmallLabel" value="<?php echo $subprice; ?>" readonly="true">
                <!--<input type="submit">-->
            </form>
            </body>
      <?
		}
		
		if($p_method == "plugnpay"){
			if($cycle_time == "year"){
				if($tax_amount_year > 0){
					$subprice = $setting->sub_price + $tax_amount_year;
				} else {
					$subprice = $setting->sub_price;
				}
				} else {
				if($tax_amount_month > 0){
					$subprice = $setting->sub_price_month + $tax_amount_month;
				} else {
					$subprice = $setting->sub_price_month;
				}
			}
			?>
			<FORM method="post" action="plugnpay.php">

								<input type="hidden" name="publisher_name" value="pnpdemo">
								<input type="hidden" name="publisher_email" value="<?php echo $setting->support_email; ?>">
								<input type="hidden" name="card_amount" value="<?php echo  $subprice; ?>">
								<input type="hidden" name="item_number" value="<? echo $order_num; ?>">
								<input type="hidden" name="subscription" value="subscription">
								
								<?PHP echo $public_pnp_sub_enter_info; ?><br /><br />
								<strong><?PHP echo $public_pnp_sub_total; ?><?php echo $currency->sign . $subprice; ?></strong><br /><br />
								
								<?PHP echo $public_pnp_email; ?><input type="text" name="email" <?PHP if($_SESSION['form_email']){ ?>value="<?PHP echo $_SESSION['form_email']; ?>"<? } ?> size="30" maxlength="100">
							<?PHP echo $public_pnp_name; ?><input type="text" name="card_name" <?PHP if($_SESSION['form_name']){ ?>value="<?PHP echo $_SESSION['form_name']; ?>"<? } ?> size="30" maxlength="30">
								<?PHP echo $public_pnp_address1; ?><input type="text" name="card_address1" <?PHP if($_SESSION['form_address1']){ ?>value="<?PHP echo $_SESSION['form_address1']; ?>"<? } ?> size="30" maxlength="30">
								<?PHP echo $public_pnp_address2; ?><input type="text" name="card_address2" <?PHP if($_SESSION['form_address2']){ ?>value="<?PHP echo $_SESSION['form_address2']; ?>"<? } ?> size="30" maxlength="30">
								<?PHP echo $public_pnp_city; ?><input type="text" name="card_city" size="30" <?PHP if($_SESSION['form_city']){ ?>value="<?PHP echo $_SESSION['form_city']; ?>"<? } ?> maxlength="30">
								<?PHP echo $public_pnp_state; ?><input type="text" name="card_state" size="2" <?PHP if($_SESSION['form_state']){ ?>value="<?PHP echo $_SESSION['form_state']; ?>"<? } ?> maxlength="2">
								<?PHP echo $public_pnp_zip; ?><input type="text" name="card_zip" size="12" <?PHP if($_SESSION['form_zip']){ ?>value="<?PHP echo $_SESSION['form_zip']; ?>"<? } ?> maxlength="12">
								
								<?PHP echo $public_pnp_cc_number; ?><input type="text" name="card_number" size="20" maxlength="20">
								<?PHP echo $public_pnp_cc_expire; ?><input type="text" name="card_exp" size="6" maxlength="6"> (MM/YY)
								<?PHP echo $public_pnp_cc_security_number; ?><input type="text" name="card_cvv" size="6" maxlength="6">
		
								<p align="left"><input type="submit" value="<?PHP echo $public_pnp_sub_form_submit; ?>"></p>
							</form>
							<?
		}
		
		if($p_method == "checkmoney"){
			if($cycle_time == "year"){
				if($tax_amount_year > 0){
					$subprice = $setting->sub_price + $tax_amount_year;
				} else {
					$subprice = $setting->sub_price;
				}
				} else {
				if($tax_amount_month > 0){
					$subprice = $setting->sub_price_month + $tax_amount_month;
				} else {
					$subprice = $setting->sub_price_month;
				}
			}
			session_register("sub_type");
			$_SESSION['sub_type'] = "sub";
			session_register("sub_email");
			$_SESSION['sub_email'] = $email;
			session_register("id");
			$_SESSION['id'] = $member->id;
			session_register("ses_total");
			$_SESSION['ses_total'] = $subprice;
			header("location: order.php?type=sub&order=$order_num");
		}
		
		if($p_method == "authorize"){
			if($cycle_time == "year"){
				if($tax_amount_year > 0){
					$subprice = $setting->sub_price + $tax_amount_year;
				} else {
					$subprice = $setting->sub_price;
				}
				} else {
				if($tax_amount_month > 0){
					$subprice = $setting->sub_price_month + $tax_amount_month;
				} else {
					$subprice = $setting->sub_price_month;
				}
			}
			?>
<!--<FORM action="https://test.authorize.net/gateway/transact.dll" method="POST">-->
  <p>
  <!-- Uncomment the line ABOVE for test accounts or BELOW for live merchant accounts -->
	<body onLoad="document.order_form.submit();">
  <FORM action="https://secure.authorize.net/gateway/transact.dll" name="order_form" method="post">
  <?

include ("simlib.php");

// Insert the form elements required for SIM by calling InsertFP
$ret = InsertFP ($setting->api_login_id, $setting->transaction_key, $subprice, $order_num); 


//*** IF YOU ARE PASSING CURRENCY CODE uncomment and use the following instead of the InsertFP invocation above  ***
// $ret = InsertFP ($loginid, $x_tran_key, $total, $sequence, $currencycode);

// Insert rest of the form elements similiar to the legacy weblink integration
$x_Description = $setting->site_title . "Subscription -".  $order_num; 
echo ("<input type=\"hidden\" name=\"x_description\" value=\"" . $x_Description . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_invoice_num\" value=\"" . $order_num . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_address\" value=\"" . $address1 . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_city\" value=\"" . $city . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_state\" value=\"" . $state . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_zip\" value=\"" . $zip . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_email\" value=\"" . $email . "\">\n" );
echo ("<input type=\"hidden\" name=\"x_login\" value=\"" . $setting->api_login_id . "\">\n");
echo ("<input type=\"hidden\" name=\"x_amount\" value=\"" . $subprice . "\">\n");
echo ("<input type=\"hidden\" name=\"x_receipt_link_method\" value=\"POST\">\n");
echo ("<input type=\"hidden\" name=\"x_receipt_link_url\" value=\"" . $setting->site_url . "/authorize_ipn.php?order="  . $order_num . "&memberid=" . $member_id . "\">\n");
//echo ("<input type=\"hidden\" name=\"x_relay_url\" value=\"" .$setting->site_url. "/authorize_ipn.php?order="  . $order_num ."&memberid=". $member_id . "\">\n");
//echo ("<input type=\"hidden\" name=\"x_currency_code\" value=\"" . $currencycode . "\">\n");
?>
 <!-- <INPUT type="hidden" name="x_test_request" value="TRUE"> ---->
<INPUT type="hidden" name="x_relay_response" value="TRUE"> 
<!-- <INPUT type="hidden" name="x_receipt_link_method" value="POST"> ----> 
<INPUT type="hidden" name="x_show_form" value="PAYMENT_FORM">  
</FORM>
	<?PHP echo $public_authorize_sub_redirect; ?>	
<?	
	}	
}
		break;
		
		//ADDED FOR VERSION 304 TO ALLOW MEMBERS TO EDIT THIER DETAILS/PROFILE
		case "sub_member_save":
			
			if($_SESSION['id']){
				$id = $_SESSION['id'];
			} else {
				$id = $_SESSION['sub_member'];
			}
	
			$member_result = mysql_query("SELECT id,country FROM members where id = '$id'", $db);
			$member = mysql_fetch_object($member_result);
			
			if($country == ""){
				$country = $member->country;
			}
			$sql = "UPDATE members SET added='$added',name='$name',email='$email',password='$password',phone='$phone',address1='$address1',address2='$address2',city='$city',state='$state',zip='$zip',country='$country' WHERE id = '$member->id'";
			$result = mysql_query($sql);
			header("location: my_details.php?message=saved");
			exit;
			
		break;		
		
		case "renew1":
		
			// GENERATE ORDER ID
			//$order_num = random_gen(10,"");
		
			$member_result = mysql_query("SELECT sub_length,order_num FROM members where id = '$id'", $db);
			$member = mysql_fetch_object($member_result);
			
			if($member->sub_length == "Y"){
				$subprice = $setting->sub_price;
			} else {
				$subprice = $setting->sub_price_month;
			}
			
?>
			<body onload="document.order_form.submit();">
			<form action="https://www.paypal.com/cgi-bin/webscr" name="order_form" method="post">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="<? echo $setting->paypal_email; ?>">
			<input type="hidden" name="item_name" value="<? echo $setting->site_title;?> Subscription Renewal - <? echo $member->order_num; ?>">
			<input type="hidden" name="item_number" value="<? echo $member->order_num; ?>">
			<input type="hidden" name="amount" value="<? echo $subprice; ?>">
			<input type="hidden" name="return" value="<? echo $setting->site_url; ?>/pp_return_sub.php">
			<input type="hidden" name="cancel_return" value="<? echo $setting->site_url; ?>">
			<input type="hidden" name="no_shipping" value="1">
			<input type="hidden" name="no_note" value="1">			
			<input type="hidden" name="notify_url" value="<? echo $setting->site_url; ?>/paypal_ipn_sub.php">			
			<input type="hidden" name="currency_code" value="<? echo $currency->code; ?>">
			<input type="hidden" name="lc" value="US">
			</form>
			<?PHP echo $public_paypal_sub_renewal_redirect; ?>

<?		
		break;
		
		case "renew2":
		
			// GENERATE ORDER ID
			//$order_num = random_gen(10,"");
		
			$member_result = mysql_query("SELECT sub_length,order_num FROM members where id = '$id'", $db);
			$member = mysql_fetch_object($member_result);
			
			if($member->sub_length == "Y"){
				$subprice = $setting->sub_price;
			} else {
				$subprice = $setting->sub_price_month;
			}
			
			header("location: https://www.2checkout.com/cgi-bin/sbuyers/cartpurchase.2c?sid=" . $setting->twocheck_account . "&total=" . $subprice . "&cart_order_id=Subscription_Renewal-" . $member->order_num);
			exit;
		
		break;
		
		case "login":
			$member_result = mysql_query("SELECT id,name,email,status,added,sub_length,down_limit_m,down_limit_y,visits FROM members where email = '$email' and password = '$password'", $db);
			$member_rows = mysql_num_rows($member_result);
			$member = mysql_fetch_object($member_result);
			
			if($member_rows > 0){
				if($member->status == 1){
					$sub_start = $member->added;
					
					if($member->sub_length == "Y"){
						$sub_end = $sub_start + 10000;
						$addmonths = 12;
					} else {
						$sub_end = $sub_start + 100;
						$addmonths = 1;
					}
					
					$basedate = strtotime($member->added);
					$mydate = strtotime("+$addmonths month", $basedate);							
					$future_month = date("Ymd", $mydate);
						
					$today = date("Ymd");		
							
					if($today <= $future_month or $member->sub_length == "F" ){
						session_register("sub_member");
						session_register("mem_name");
						session_register("mem_down_limit");
					if($member->sub_length == "F"){
						session_register("sub_type");
						$_SESSION['sub_type'] = "free";
					}
					if($member->sub_length == "M"){
						session_register("sub_type");
						$_SESSION['sub_type'] = "monthly";
					}
					if($member->sub_length == "Y"){
						session_register("sub_type");
						$_SESSION['sub_type'] = "yearly";
					}
						$_SESSION['sub_member'] = $member->id;
						$_SESSION['mem_name'] = $member->name;
						$_SESSION['mem_email'] = $member->email;
					if($member->sub_length == "Y"){
						$_SESSION['mem_down_limit'] = $member->down_limit_y;
					} else {
						$_SESSION['mem_down_limit'] = $member->down_limit_m;
					}
						
						$visits = $member->visits + 1;
						$sql = "UPDATE members SET visits='$visits' WHERE id = '$member->id'";
						$result = mysql_query($sql);
					if($_SESSION['pub_pid'] && $_SESSION['pub_gid']){
						header("location: details.php?gid=" . $_SESSION['pub_gid'] . "&pid=" . $_SESSION['pub_pid']);
					  exit;
					} else {
						if($_SESSION['pub_pid'] == "" && $_SESSION['pub_gid'] != ""){
							header("location: gallery.php?gid=" . $_SESSION['pub_gid']);
							exit;
						}
						if($_SESSION['cart_from'] == "cart"){
							unset($_SESSION['cart_from']);
							header("location: cart.php");
							exit;
						} else {
							header("location: index.php");
							exit;
						}
					}					
				} else {
						session_register("id");
						$_SESSION['id'] = $member->id;
						header("location: renew_full.php");
						exit;
					}				
				} else {
					header("location: subscribe.php?message=pending");
					exit;
				}
			} else {
				header("location: subscribe.php?message=login_failed");
				exit;
			}
		break;

		case "recover_password":
			$member_result = mysql_query("SELECT password FROM members where email = '$email'", $db);
			$member_rows = mysql_num_rows($member_result);
			$member = mysql_fetch_object($member_result);
			
			if($member_rows > 0){
				// Send Email
				$message = $public_your_password . $member->password;
				mail($email, $public_password_for . $setting->site_title, $message, "From: " . $setting->support_email);
				
				header("location: recover_password.php?message=email_sent");
				exit;
			} else {
				header("location: recover_password.php?message=no_account");
				exit;
			}
		break;
		
		// ADDED FOR PS3 FOR USER TO TURN ON AND OFF HOVER VIEW
		case "hover_on":
			session_register($_SESSION['visitor_hover']);
	    $_SESSION['visitor_hover'] = 1;
			header("location: " . $return);
			exit;
		break;
		
		case "hover_off":
			unset($_SESSION['visitor_hover']);
			header("location: " . $return);
			exit;
		break;
		//############# END OF HOVER ON OFF CODE ###############
		// ADDED IN PS340 FOR THE FLASH VEIW ON AND OFF
		case "flash_on":
			session_register($_SESSION['visitor_flash']);
		  $_SESSION['visitor_flash'] = 1;
			header("location: " . $return);
			exit;
		break;
		
		case "flash_off":
			unset($_SESSION['visitor_flash']);
			header("location: " . $return);
			exit;
		break;
		//############### END OF FLASH ON OFF CODE ###############
		
		case "send_mail":
			email(3,$to);
			exit;
		break;
		
		case "coupon":
		
			unset($_SESSION['type']);
			unset($_SESSION['percent']);
			unset($_SESSION['amount']);
			unset($_SESSION['item_free']);
			unset($_SESSION['free_ship']);
			unset($_SESSION['no_tax']);
			unset($_SESSION['coupon_id']);
			
			$coupon_result = mysql_query("SELECT id,type,expire,quantity,used,percent,amount,item_count FROM coupon where code = '$code'", $db);
			$coupon = mysql_fetch_object($coupon_result);
			
			$quantity = $coupon->quantity;
			$used = $coupon->used;
		if($coupon->expire != ""){
			$expire = strtotime($coupon->expire);
			$expire = date("Ymd", $expire);
		} else {
			$expire = "";
		}
			$today = date("Ymd");	

			if($expire != ""){
				if($expire > $today){
					$not_expire_date = "true";
				} else {
					$not_expire_date = "false";
				}
			}
		
		
			if($quantity > 0){
				if($quantity > $used){
					$not_used_up = "true";
				} else {
					$not_used_up = "false";
				}
			}
		
		if($expire != ""){
			if($not_expire_date != "false"){
				session_register("coupon_id");
				$_SESSION['coupon_id'] = $coupon->id;
				
				if($coupon->type == 2){
					$percent = $coupon->percent / 100;
				}
				if($coupon->type == 3){
					$amount = $coupon->amount;
				}
				if($coupon->type == 4){
					$item_free = $coupon->item_count;
				}
			
				session_register("type");
				$_SESSION['type'] = $coupon->type;
			
				if($percent != ""){
					session_register("percent");
					$_SESSION['percent'] = $percent;
				}
				if($amount != ""){
					session_register("amount");
					$_SESSION['amount'] = $amount;
				}
				if($item_free != ""){
					session_register("item_free");
					$_SESSION['item_free'] = $item_free;
				}
				if($coupon->type == 1){
					session_register("free_ship");
					$_SESSION['free_ship'] = "yes";
				}
				if($coupon->type == 5){
					session_register("no_tax");
					$_SESSION['no_tax'] = "yes";
				}
		
				header("location: cart.php?message=coupon_good");
				exit;
			} else {
				header("location: cart.php?message=coupon_bad");
				exit;
			}
		}
		
	
		if($quantity > 0){
			if($not_used_up != "false"){
				session_register("coupon_id");
				$_SESSION['coupon_id'] = $coupon->id;
				
				if($coupon->type == 2){
					$percent = $coupon->percent / 100;
				}
				if($coupon->type == 3){
					$amount = $coupon->amount;
				}
				if($coupon->type == 4){
					$item_free = $coupon->item_count;
				}
			
				session_register("type");
				$_SESSION['type'] = $coupon->type;
			
				if($percent != ""){
					session_register("percent");
					$_SESSION['percent'] = $percent;
				}
				if($amount != ""){
					session_register("amount");
					$_SESSION['amount'] = $amount;
				}
				if($item_free != ""){
					session_register("item_free");
					$_SESSION['item_free'] = $item_free;
				}
				if($coupon->type == 1){
					session_register("free_ship");
					$_SESSION['free_ship'] = "yes";
				}
				if($coupon->type == 5){
					session_register("no_tax");
					$_SESSION['no_tax'] = "yes";
				}
		
				header("location: cart.php?message=coupon_good");
				exit;
			} else {
				header("location: cart.php?message=coupon_bad");
				exit;
			}
		}
		header("location: cart.php?message=coupon_bad");
		exit;
		break;
		
		case "logout":
			unset($_SESSION['sub_member']);
			unset($_SESSION['mem_name']);
			unset($_SESSION['mem_down_limit']);
			unset($_SESSION['sub_type']);
			header("location: index.php");
			exit;
		break;
		
		case "send_order":
		//ADDED IN PS350 FOR EXTRA SECURITY
		if($_POST['key'] == md5($_SESSION['visitor_id'].$setting->access_id)){
			unset($_SESSION['form_name1']);
		  unset($_SESSION['form_email1']);
		  unset($_SESSION['form_phone1']);
		  unset($_SESSION['form_address11']);
		  unset($_SESSION['form_address21']);
		  unset($_SESSION['form_city1']);
		  unset($_SESSION['form_state1']);
		  unset($_SESSION['form_zip1']);
		  unset($_SESSION['form_bio1']);
		  unset($_SESSION['form_note1']);
		  
			session_register("form_name1");
			$_SESSION['form_name1'] = $_POST['name'];
			session_register("form_email1");
			$_SESSION['form_email1'] = $_POST['email'];
			session_register("form_phone1");
			$_SESSION['form_phone1'] = $_POST['phone'];
			session_register("form_address11");
			$_SESSION['form_address11'] = $_POST['address1'];
			session_register("form_address21");
			$_SESSION['form_address21'] = $_POST['address2'];
			session_register("form_city1");
			$_SESSION['form_city1'] = $_POST['city'];
			session_register("form_state1");
			$_SESSION['form_state1'] = $_POST['state'];
			session_register("form_zip1");
			$_SESSION['form_zip1'] = $_POST['zip'];
			session_register("form_note1");
			$_SESSION['form_note1'] = $_POST['note'];
			
		$item = $_SESSION['visitor_id'];
		$order_num = random_gen(10,"");
		$savings = $coupon;
		$member_id = $_SESSION['sub_member'];
		if($_POST['type'] == "sub"){
			$total = $_SESSION['ses_total'];
			$grand_total = $total;
			$bypass = 1;
		}
			
		if($bypass != 1){
			$coupon_result = mysql_query("SELECT type,code,used FROM coupon where id = '$coupon_id'", $db);
			$coupon_rows = mysql_num_rows($coupon_result);
			$coupon = mysql_fetch_object($coupon_result);

			if($coupon_rows > 0){
			$used = $coupon->used + 1;
			$sql = "UPDATE coupon SET used='$used' WHERE id = '$coupon_id'";
			$result = mysql_query($sql);
			$coupon_code = $coupon->code;
			$coupon_type = $coupon->type;
			
			if($coupon_type == "1"){
				$type_name = "Free Shipping";
			}
			if($coupon_type == "2"){
				$type_name = "Percent Off";
			}
			if($coupon_type == "3"){
				$type_name = "Dollar Amount Off";
			}
			if($coupon_type == "4"){
				$type_name = "Number of Free Items";
			}
			if($coupon_type == "5"){
				$type_name = "Tax Exempt";
			}
		}
			
			$visitor_result = mysql_query("SELECT id FROM visitors where visitor_id = '" . $_SESSION['visitor_id'] . "'", $db);
			$visitor_rows = mysql_num_rows($visitor_result);
			$visitor = mysql_fetch_object($visitor_result);
			
			if(file_exists("photog_main.php")){
				$sql = "UPDATE photog_sales SET completed='1' WHERE visitor_id = '" . $_SESSION['visitor_id'] . "'";
				$result = mysql_query($sql);
			}
			
			$total = 0;
			$cart_result = mysql_query("SELECT ptype,photo_id,sid,prid,quantity FROM carts where visitor_id = '" . $_SESSION['visitor_id'] . "'", $db);
			while($cart = mysql_fetch_object($cart_result)){
			
				if($cart->ptype == "d"){
				
					$photo_result = mysql_query("SELECT price FROM uploaded_images where id = '$cart->photo_id'", $db);
					$photo = mysql_fetch_object($photo_result);			
						if($photo->price){
							$price = $photo->price;	
						} else {
							$price = $setting->default_price;
						}
					
					
				} else {
				if($cart->ptype == "s"){
					$sizes_result = mysql_query("SELECT price FROM sizes where id = '$cart->sid'", $db);
					$sizes = mysql_fetch_object($sizes_result);		
					$price = $sizes->price;
				} else {
					$print_result = mysql_query("SELECT price FROM prints where id = '$cart->prid'", $db);
					$print = mysql_fetch_object($print_result);		
				
						if($print->price){
							$price1 = $print->price;
							$price = $print->price * $cart->quantity;
						} else {
							$price1 = "5.00";
							$price = "5.00" * $cart->quantity;
						}
				}
			}
			$total = $total + $price;
			}
					
					if($visitor_rows > 0){
				$sql="DELETE FROM visitors WHERE id = '$visitor->id'";
				$result2 = mysql_query($sql);			
			}
			
			if($_SESSION['ses_free_order'] == 1){
				$p_method = "Free Order (coupon)";
				if($setting->free_approve == 1){
					$free_approve = 1;
				} else {
					$free_approve = 0;
				}
			} else {
	      $p_method = "Check or Money Order";
	      $free_approve = 0;
	    }

			$added = date("Ymd");
			$sql = "INSERT INTO visitors (visitor_id,added,order_num,status,paypal_email,shipping,price,payment_method,tax,coupon_id,savings,member_id,done) VALUES ('" . $_SESSION['visitor_id'] . "','$added','$order_num','1','$email','$shipping','$total','$p_method','$tax','$coupon_id','$savings','$member_id','$free_approve')";
			$result = mysql_query($sql);
			
			$grand_total = $total + $shipping + $tax - $savings;
		}
		
		if($bypass == 1){
			$subject = "Subscription Order";
		} else {
			$subject = "Order Details";
		}
		
			$body1= "Business: " . $_POST['business'] . "\n";
			$body1.= "Item: " . $_POST['item_name'] . "\n\n";
			$body1.= "Order Number: " . $order_num . "\n";
			$body1.= "Total Price: \$" . $total . "\n";
			if($bypass != 1){
			$body1.= "Tax Amount: \$" . $tax . "\n";
			$body1.= "Shipping: \$" . $shipping . "\n";
			if($coupon_type != ""){
				$body1.= "Coupon Type: " . $type_name . "\n";
			}
			if($coupon_code != ""){
				$body1.= "Coupon Code: " . $coupon_code . "\n";
			}
			if($savings != ""){
				$body1.= "Coupon Amount: \$" . $savings . "\n";
			}
			$body1.= "Grand Total: \$" . $grand_total . "\n";
			}
			$body1.= "Email: " . $_POST['email'] . "\n";
			$body1.= "Phone Number: " . $_POST['phone'] . "\n";
			$body1.= "Name: " . $_POST['name'] . "\n";
			$body1.= "Address: " . $_POST['address1'] . "\n";
			$body1.= "Address: " . $_POST['address2'] . "\n";
			$body1.= "City: " . $_POST['city'] . "\n";
			$body1.= "State: " . $_POST['state'] . "\n";
			$body1.= "Zip: " . $_POST['zip'] . "\n";
			$body1.= "Notes: " . $_POST['note'] . "\n";		
			if($bypass != 1){
			$body1.= "Once payment is received for this order, log into your store manage and click on the orders tab, and then click on the order in the list. Then click on the link \"Click to Approve\" and that will automatically send the customer this link below so they can download or view thier order info. They have already received this link but until you approve it, they can't download anything.\n";
			$body1.= $setting->site_url . "/download.php?order=" . $order_num . "\n\n\n\n";
			}
			if($bypass == 1){
			$id = $_SESSION['id'];
			$type = $_POST['type'];
			$body1.= "Subscription: \n";
			$body1.= "--------------------------------- \n";
			$body1.= "Subscription ID = $id \n";
			}
			if($bypass != 1){
			$body1.= "Downloads: \n";
			$body1.= "---------------------------------- \n";	
		
			$visitor_result = mysql_query("SELECT visitor_id,status FROM visitors where order_num = '$order_num'", $db);
			$visitor_rows = mysql_num_rows($visitor_result);
			$visitor = mysql_fetch_object($visitor_result);	
									
			if($visitor_rows == 0){
				// NOT A VALID ORDER NUMBER
				echo $order_error_not_valid;
				exit;
			}
			if($visitor->status == 0){
				// STATUS IS 0 - ORDER HAS NOT BEEN PAID FOR YET
				echo $order_error_not_paid;
				exit;										
			} else {
				// ORDER OK - DOWNLOAD FILES
				$cart_result = mysql_query("SELECT ptype,photo_id,sid FROM carts where visitor_id = '$visitor->visitor_id'", $db);
				while($cart = mysql_fetch_object($cart_result)){
			  if($cart->ptype == "d" or $cart->ptype == "s"){
					$photo_result = mysql_query("SELECT filename FROM uploaded_images where id = '$cart->photo_id'", $db);
					$photo = mysql_fetch_object($photo_result);
					
				if($cart->ptype == "s"){
		  		$sizes_result = mysql_query("SELECT id,size FROM sizes where id = '$cart->sid'", $db);
					$sizes = mysql_fetch_object($sizes_result);
				}								
																	
					$body1.= "File Name: $photo->filename \n";
					if($cart->ptype == "s"){
		  		$body1.= "Size ID: $sizes->id \n";
					$body1.= "Size: $sizes->size \n";
					}
					$body1.= "---------------------------------- \n";
				 }
				} 
				
				$body1.= "\n\nPrints & Products: \n";
				$body1.= "---------------------------------- \n";
				
				$cart_result = mysql_query("SELECT ptype,photo_id,prid,quantity FROM carts where visitor_id = '$visitor->visitor_id'", $db);
				while($cart = mysql_fetch_object($cart_result)){
						
					$print_result = mysql_query("SELECT name FROM prints where id = '$cart->prid'", $db);
					$print = mysql_fetch_object($print_result);
																					
					$pg_result = mysql_query("SELECT filename FROM uploaded_images where reference = 'photo_package' and id = '$cart->photo_id'", $db);
					$pg = mysql_fetch_object($pg_result);
																				
					if($cart->ptype == "p"){														
					$body1.= "File Name: $pg->filename \n";
					$body1.= "Print: $print->name \n";
					$body1.= "Quantity: $cart->quantity \n";
					$body1.= "---------------------------------- \n";
					}
				}
			}
		}
			 mail($to, $subject, $body1, "From: " . $_POST['email'] . "<" . $_POST['email'] . ">");
			 unset($_SESSION['ses_free_order']);
			 $to = $_POST['email'];
			 email(17,$to);
			header("location: money_return.php?item=$item&order=$order_num&type=$type");
		} else {
		echo $order_error_no_key;
	}
		break;	
		
		case "add_lightbox":
			$added = date("Ymd");
        //$lightbox_id = $_SESSION['lightbox_id'];
        if(isset($_GET['lb_id'])) $lightbox_id = $_GET['lb_id'];
        else $lightbox_id = $_SESSION['lightbox_id'];
				$lightbox_result = mysql_query("SELECT id FROM lightbox where member_id = '" . $_SESSION['sub_member'] . "' and reference_id = '" . $lightbox_id . "' and photo_id = '$pid'", $db);
				$lightbox_rows = mysql_num_rows($lightbox_result);
				$lightbox = mysql_fetch_object($lightbox_result);
				
				if($lightbox_rows > 0){
					echo $public_already_added;
				} else {
					
					$package_result = mysql_query("SELECT id FROM photo_package where id = '" . $_GET['pid'] . "'", $db);
					$package = mysql_fetch_object($package_result);
		
					if($_SESSION['sub_member'] AND $lightbox_id){
						$sql = "INSERT INTO lightbox (member_id,photo_id,ptype,prid,reference_id) VALUES ('" . $_SESSION['sub_member'] . "',$package->id,'$ptype','$prid','" . $lightbox_id . "')";
						$result = mysql_query($sql);
					} else {
						header("location: lightbox.php?message=select");
					exit;
					}
					
					if($_SESSION['url'] != ""){
						header("location: " . $_SESSION['url']);
					} else {
						header("location: lightbox.php?message=added");
					}
					exit;
				}
				
		break;
		
		case "remove_lightbox":
		
			$sql="DELETE FROM lightbox WHERE id = '$lid' and member_id = '" . $_SESSION['sub_member'] . "'";
			$result2 = mysql_query($sql);
		if($_SESSION['url'] != ""){
			header("location: " . $_SESSION['url']);
		} else {
			header("location: lightbox.php?message=removed");
		}
			exit;
		break;
		
		case "delete_lightbox":
		
			$sql="DELETE FROM lightbox WHERE member_id = '" . $_SESSION['sub_member'] . "' and reference_id = '" . $_SESSION['lightbox_id'] . "'";
			$result2 = mysql_query($sql);
		
			header("location: lightbox.php?message=empty");
			exit;
		break;
		
		case "create_lightbox":
		  $name = $_POST['name'];
		  $name = addslashes($name);
			$sql="INSERT INTO lightbox_group (member_id, name) VALUES ('" . $_SESSION['sub_member'] . "','$name')";
			$result = mysql_query($sql);
		
			header("location: lightbox.php?message=created");
			exit;
		break;
    
		case "rename_lightbox":
		  $name = $_POST['name'];
		  $name = addslashes($name);
      $id = $_GET['id'];
			$sql="UPDATE lightbox_group SET name = '$name' WHERE member_id='" . $_SESSION['sub_member'] . "' AND id='$id' ";
			$result = mysql_query($sql);
		
			header("location: lightbox.php?lightbox=$id&message=renamed");
			exit;
		break;
    
		case "delete_lightbox_group":
		
			$sql="DELETE FROM lightbox_group WHERE id = '" . $_SESSION['lightbox_id'] . "'";
			$result2 = mysql_query($sql);
			
			$sql="DELETE FROM lightbox WHERE reference_id = '" . $_SESSION['lightbox_id'] . "'";
			$result1 = mysql_query($sql);
			
			$_SESSION['lightbox_id'] = "";
		
			header("location: lightbox.php?message=lightbox_deleted");
			exit;
		break;
		
		case "email_lightbox":
			$to = strtolower($_POST['email']);
			email(1,$to);
			exit;
		break;
		
		case "email_photo":
			$email_addy = sanitize($_POST['email']);
			$to = strtolower($email_addy);
			email(2,$to);
			exit;
		break;
		
	}
?>
