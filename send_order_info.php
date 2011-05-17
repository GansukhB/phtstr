<?php
	if($_POST['x_response_code'] == 1){
 		$payer_email = $_POST['x_email'];
		$item_number = $_POST['x_invoice_num'];
		$payment_currency = $_POST['mc_currency'];;
		$payment_amount = $_POST['x_amount'];
	}
	if($twochechout_purchase){
		$payer_email = $email;
		$item_number = $cart_order_id;
		$item_name = "Stock Photos" . $order_number;
		$payment_currency = "\$";
		$payment_amount = $total;
	}
	if($mygate_purchase){
		$payer_email = $_EMAIL;
		$item_number = $_VARIABLE1;
		$item_name = "Stock Photos" . $order_number;
		$payment_currency = "R";
		$payment_amount = $_TOTAL;
	}

	function html_email($to,$subject,$body1,$from){
		mail($to, $subject, $body1, "From: " . $from . "\n\n");
	}	
	
			$visitor_result = mysql_query("SELECT id,savings,coupon_id,status,visitor_id FROM visitors where order_num = '$item_number'", $db);
			$visitor_rows = mysql_num_rows($visitor_result);
			$visitor = mysql_fetch_object($visitor_result);
			if($_POST['x_response_code']==1){
			$item_name = "Stock Photos" . $visitor->id;
			}
			$savings = $visitor->savings;
	
			$coupon_result = mysql_query("SELECT used,code,type FROM coupon where id = '$visitor->coupon_id'", $db);
			$coupon_rows = mysql_num_rows($coupon_result);
			$coupon = mysql_fetch_object($coupon_result);

			if($coupon_rows > 0){
			$used = $coupon->used + 1;
			$sql = "UPDATE coupon SET used='$used' WHERE id = '$visitor->coupon_id'";
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
			
			$to = $setting->support_email;
			$subject = "Order Details";
			$from = $payer_email;
		
			
			$body1 = "Order Details \n";
			$body1.= "Order Number: $item_number \n";
			$body1.= "Visitor ID: $item_name \n";
				if($coupon_type != ""){
				$body1.= "Coupon Type: " . $type_name . "\n";
			}
			if($coupon_code != ""){
				$body1.= "Coupon Code: " . $coupon_code . "\n";
			}
			if($savings != ""){
				$body1.= "Coupon Amount: $payment_currency " . $savings . "\n";
			}
			$body1.= "Amount: $payment_currency $payment_amount \n";
			$body1.= "Email: $payer_email \n\n";
														
			if($visitor_rows == 0){
			// NOT A VALID ORDER NUMBER
			//echo "This is not a valid order number";
			} else {
			if($visitor->status == 0){
			// STATUS IS 0 - ORDER HAS NOT BEEN PAID FOR YET
			echo "This order has not been paid for or the payment is still pending.";										
			} else {
			// ORDER OK - DOWNLOAD FILES
			
			$body1.= "\n\nDownloads: \n";
			$body1.= "---------------------------------- \n";
			
			$cart_result = mysql_query("SELECT ptype,photo_id,sid,prid,quantity FROM carts where visitor_id = '$visitor->visitor_id'", $db);
			while($cart = mysql_fetch_object($cart_result)){
																		
			if($cart->ptype == "d" or $cart->ptype == "s"){
			$photo_result = mysql_query("SELECT filename FROM uploaded_images where id = '$cart->photo_id'", $db);
			$photo = mysql_fetch_object($photo_result);
			}
			if($cart->ptype == "s"){
		  $sizes_result = mysql_query("SELECT id,size FROM sizes where id = '$cart->sid'", $db);
			$sizes = mysql_fetch_object($sizes_result);
			}																												
						
			if($cart->ptype == "d" or $cart->ptype == "s"){
			
			$body1.= "File Name: $photo->filename \n";
			if($cart->ptype == "s"){
		  $body1.= "Size ID: $sizes->id \n";
			$body1.= "Size: $sizes->size \n";
			}
			$body1.= "---------------------------------- \n";
		
			} else {
			
			$body1.= "\n\nPrints & Products: \n";
			$body1.= "---------------------------------- \n";
			
			$print_result = mysql_query("SELECT name FROM prints where id = '$cart->prid'", $db);
			$print = mysql_fetch_object($print_result);
																			
			$pg_result = mysql_query("SELECT filename FROM uploaded_images where reference = 'photo_package' and id = '$cart->photo_id'", $db);
			$pg = mysql_fetch_object($pg_result);
																		
																		
			$body1.= "File Name: $pg->filename \n";
			$body1.= "Print: $print->name \n";
			$body1.= "Quantity: $cart->quantity \n";
			$body1.= "---------------------------------- \n";
		
							}
						}
					}
				}

     html_email($to,$subject,$body1,$from);
?>
