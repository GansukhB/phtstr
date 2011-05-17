<?
	session_start();

	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );
	
	$currency_result = mysql_query("SELECT sign FROM currency where active = '1'", $db);
	$currency = mysql_fetch_object($currency_result);
	
	// VISITOR ID
	if(!$_SESSION['visitor_id']){
		session_register("visitor_id");
		$_SESSION['visitor_id'] = random_gen(16,"");
	}
?>
<html>
	<head>
		<? print($head); ?>
		<center>
		<table cellpadding="0" cellspacing="0" width="765" class="main_table" style="border: 5px solid #<? echo $border_color; ?>;">
			<? include("header.php"); ?>
			<tr>
				<td class="left_nav_header"><? echo $misc_photocat; ?></td>
				<td></td>
				<? include("search_bar.php"); ?>
			</tr>
			<tr>
				<td rowspan="1" valign="top"><? include("i_gallery_nav.php"); ?></td>
				<td background="images/col2_shadow.gif" valign="top"><img src="images/col2_white.gif"></td>
				<td valign="top" height="18">
					<table cellpadding="0" cellspacing="0" width="560" height="100%">
						<tr>
							<td colspan="3" height="5"></td>
						</tr>
						<?php
							$crumb = $pp_crumb_link;
							include("crumbs.php");
							
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
							$cart_result = mysql_query("SELECT ptype,prid,photo_id,sid,quantity FROM carts where visitor_id = '" . $_SESSION['visitor_id'] . "'", $db);
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
							
							$added = date("Ymd");
							$sql = "INSERT INTO visitors (visitor_id,added,order_num,shipping,price,payment_method,tax,coupon_id,savings,done,member_id) VALUES ('" . $_SESSION['visitor_id'] . "','$added','$order_num','$shipping','$total','Plug N Pay','$tax','" . $_SESSION['coupon_id'] . "','$savings','$approve','$member_id')";
							$result = mysql_query($sql);
							
						if($_SESSION['type'] != ""){
							if($_SESSION['type'] == 1){
								$shipping = "0";
								$grand_total = round($total + $shipping + $tax, 2);
							}
							if($_SESSION['type'] == 5){
								$tax = "0";
								$grand_total = round($total + $shipping + $tax, 2);
							}
							if($_SESSION['type'] == 2){
								$grand_total = round($total - $savings + $shipping + $tax, 2);
							}
							if($_SESSION['type'] == 3){
								$grand_total = round($total - $savings + $shipping + $tax, 2);
							}
						} else {
							$grand_total = round($total - $savings + $shipping + $tax, 2);
						}
						?>
						<tr>
							<td class="index_copy_area" colspan="3" height="4"></td>
						</tr>						
						<tr>
							<td colspan="3" valign="top" height="100%" class="homepage_line" style="padding: 10px 10px 10px 20px;">
								<FORM method="post" action="plugnpay.php">

								<input type="hidden" name="publisher_name" value="pnpdemo">
								<input type="hidden" name="publisher_email" value="<?php echo $setting->support_email; ?>">
								<input type="hidden" name="card_amount" value="<?php echo  $grand_total; ?>">
								<input type="hidden" name="item_number" value="<? echo $order_num; ?>">
								
								<?PHP echo $pp_enter_info_to_complete; ?><br /><br />
								<strong><?PHP echo $pp_order_total; ?><?php echo $currency->sign . $grand_total; ?></strong><br /><br />
								
								<?PHP echo $pp_order_email; ?><input type="text" name="email" size="30" maxlength="100">
								<?PHP echo $pp_order_name; ?><input type="text" name="card_name" size="30" maxlength="30">
								<?PHP echo $pp_order_address1; ?><input type="text" name="card_address1" size="30" maxlength="30">
								<?PHP echo $pp_order_address2; ?><input type="text" name="card_address2" size="30" maxlength="30">
								<?PHP echo $pp_order_city; ?><input type="text" name="card_city" size="30" maxlength="30">
								<?PHP echo $pp_order_state; ?><input type="text" name="card_state" size="2" maxlength="2">
								<?PHP echo $pp_order_zip; ?><input type="text" name="card_zip" size="12" maxlength="12">
								
								<?PHP echo $pp_order_cc_number; ?><input type="text" name="card_number" size="20" maxlength="20">
								<?PHP echo $pp_order_cc_expire_date; ?><input type="text" name="card_exp" size="6" maxlength="6"><?PHP echo $pp_order_cc_expire_date2; ?>
								<?PHP echo $pp_order_cc_security_number; ?><br /><input type="text" name="card_cvv" size="6" maxlength="6">
		
								<p align="left"><input type="submit" value="<?PHP echo $pp_order_form_submit; ?>"></p>
							</td>
						</tr>
					</table>				
				</td>
			</tr>
			<? include("footer.php"); ?>			
		</table>
		</center>
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	