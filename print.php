<?PHP
	session_start();
	unset($_SESSION['visitor_id']);

	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );
	
	if($_SESSION['sub_type'] == "sub"){
	$bypass = 1;
	$email = $_SESSION['sub_email'];
	$sub_number = $_SESSION['id'];
	$method = $print_order_method;
	$total = $_SESSION['ses_total'];
	$odate = date("Ymd");
	$date = date("F j, Y", strtotime($odate));
	$order_num = $_GET['order'];
	$item = $_GET['item'];
}

	$currency_result = mysql_query("SELECT sign FROM currency where active = '1'", $db);
	$currency = mysql_fetch_object($currency_result);
	
	if($bypass != 1){
	$visitor_result = mysql_query("SELECT * FROM visitors where order_num = '" . $order . "'", $db);
	$visitor_rows = mysql_num_rows($visitor_result);
	$visitor = mysql_fetch_object($visitor_result);
	
	$total = $visitor->price;
	$shipping = $visitor->shipping;
	$savings = $visitor->savings;
	$coupon_id = $visitor->coupon_id;
	$tax = $visitor->tax;
	$odate = $visitor->added;
	$email = $visitor->paypal_email;
	$method = $visitor->payment_method;
	$grand = $total + $shipping + $tax - $savings;
	$order_num = $_GET['order'];
	$item = $_GET['item'];
	$date = date("F j, Y", strtotime($odate));
	
	$coupon_result = mysql_query("SELECT type FROM coupon where id = '$coupon_id'", $db);
	$coupon_rows = mysql_num_rows($coupon_result);
	$coupon = mysql_fetch_object($coupon_result);
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
?>
	<table width="600" align="center">
										<tr>
										<td colspan="2" class="gallery_copy">
										<a href="#" onclick="window.print ()"><?PHP echo $print_print; ?></a><br><br>
										<?PHP echo $print_thank_you; ?><br><br>
									</tr>
										<tr>
										<td width="150" class="gallery_copy"><?PHP echo $print_order_date; ?></td><td><? echo $date; ?></td>
									</tr>
										<tr>
										<td width="150" class="gallery_copy"><?PHP echo $print_order_email; ?></td><td><? echo $email; ?></td>
									</tr>
										<tr>
										<td width="150" class="gallery_copy"><?PHP echo $print_order_payment_type; ?></td><td><? echo $method; ?></td>
									</tr>
										<tr>
										<td width="150" class="gallery_copy"><?PHP echo $print_order_id; ?></td><td><? echo $order_num; ?></td>
									</tr>
										<tr>
										<td width="150" class="gallery_copy"><?PHP echo $print_order_visitor_id; ?></td><td><? echo $item; ?></td>
									</tr>
										<tr>
										<td colspan="2" class="gallery_copy"><hr width="100%" align="left"></td>
									</tr>
										<tr>
										<td width="150" class="gallery_copy"><?PHP echo $print_order_total; ?></td><td><? echo $currency->sign; ?><? echo dollar($total); ?></td>
									</tr>
								<? if($bypass != 1){ ?>
										<tr>
										<td width="150" class="gallery_copy"><?PHP echo $print_order_tax; ?><? if($setting->tax1_name != "" or $setting->tax2_name != ""){ ?><br><? if($setting->tax1_name != ""){ echo "(" . $setting->tax1_name; } else { echo "("; }?><? if($setting->tax1_name != "" && $setting->tax2_name != ""){ echo " & "; } ?><? if($setting->tax2_name != ""){ echo $setting->tax2_name . ")"; } else { echo ")"; } }?></td><td><? echo $currency->sign; ?><? echo dollar($tax); ?></td>
									</tr>
										<tr>
										<td width="150" class="gallery_copy"><?PHP echo $print_order_shipping; ?></td><td><? echo $currency->sign; ?><? echo dollar($shipping); ?></td>
									</tr>
											<? if($savings > 0){ ?>
									<tr>
										<td width="150" class="gallery_copy"><?PHP echo $print_order_coupon; ?></td><td><? echo $currency->sign; ?><? echo dollar($savings); ?></td>
									</tr>
								<? } ?>
								<? if($coupon_rows > 0){ ?>
									<tr>
										<td width="150" class="gallery_copy"><?PHP echo $print_order_coupon_used; ?></td><td><? echo $type_name; ?></td>
									</tr>
								<? } ?>
										<tr>
										<td width="150" class="gallery_copy"><?PHP echo $print_order_grand_total; ?></td><td><? echo $currency->sign; ?><? echo dollar($grand); ?></td>
									</tr>
								<? } ?>
										<tr>
										<td  class="gallery_copy" colspan="2">
										<hr width="100%" align="left"><br><br>										
										<? copy_area(11,3); ?><br><br>
										<?PHP echo $print_order_item_details; ?>
										<hr width="100%">
									</td>
								</tr>
										<?
													if($bypass != 1){
													$visitor_result = mysql_query("SELECT status,visitor_id,payment_method FROM visitors where order_num = '" . $order . "'", $db);
													$visitor_rows = mysql_num_rows($visitor_result);
													$visitor = mysql_fetch_object($visitor_result);
														
														if($visitor_rows == 0){
															// NOT A VALID ORDER NUMBER
															echo $print_order_invalid;
														} else {
															if($visitor->status == 0){
																// STATUS IS 0 - ORDER HAS NOT BEEN PAID FOR YET
																echo $print_order_pending;										
															} else {
															
																	// ORDER OK - DOWNLOAD FILES
																	$cart_result = mysql_query("SELECT ptype,photo_id,price,sid,prid,quantity FROM carts where visitor_id = '$visitor->visitor_id'", $db);
																	while($cart = mysql_fetch_object($cart_result)){
																	
																		if($cart->ptype == "d"){
																			$photo_result = mysql_query("SELECT id FROM uploaded_images where id = '$cart->photo_id'", $db);
																			$photo = mysql_fetch_object($photo_result);
																		}
												?>
															<?
																if($cart->ptype == "d"){
															?>
															
															<tr>
																<td align="left" valign="middle" colspan="2">
																<span style="padding: 5;">
																	<table border="0">
																		<tr>
																			<? if($setting->show_watermark_thumb == 1){ ?>
																				<td><img src="thumb_mark.php?i=<? echo $photo->id; ?>" width="75" class="photos" border="0"></td>
																			<? } else { ?>
																				<td><img src="image.php?src=<? echo $photo->id; ?>" width="75" class="photos" border="0"></td>
																			<? } ?>
																			<td>
																				<?PHP echo $print_item_download; ?><? if($visitor->payment_method == "Check/Money Order"){ ?><?PHP echo $print_item_download2; ?><? } ?><br>
																			<?PHP echo $print_item_download_price; ?><? echo $currency->sign; ?><? echo dollar($cart->price); ?>
																			</td>
																		</tr>
																	</table>
																</span>
																</td>
															</tr>
															<?
																} else {
																if($cart->ptype == "s"){
																	$sizes_result = mysql_query("SELECT name,size FROM sizes where id = '$cart->sid'", $db);
																	$sizes = mysql_fetch_object($sizes_result);
																	
																	$pg1_result = mysql_query("SELECT id FROM uploaded_images where reference = 'photo_package' and id = '$cart->photo_id' order by original", $db);
																	$pg1 = mysql_fetch_object($pg1_result);
																?>
																<tr>
																<td align="left" valign="middle" colspan="2">
																<span style="padding: 5;">
																	<table border="0">
																		<tr>
																			<? if($setting->show_watermark_thumb == 1){ ?>
																				<td><img src="thumb_mark.php?i=<? echo $pg1->id; ?>" width="75" class="photos" border="0"></td>
																			<? } else { ?>
																				<td><img src="image.php?src=<? echo $pg1->id; ?>" width="75" class="photos" border="0"></td>
																			<? } ?>
																			<td class="gallery_copy">
																				<?PHP echo $print_size_product; ?><?php echo $sizes->name; ?><br>
																				<?PHP echo $print_size_size; ?><? echo $sizes->size; ?><br>
																				<?PHP echo $print_size_price; ?><? echo $currency->sign; ?><? echo dollar($cart->price); ?><br>
																			</td>
																		</tr>
																	</table>
																</span>
																</td>
															</tr>
															<?
																} else {
																	$print_result = mysql_query("SELECT name,bypass FROM prints where id = '$cart->prid'", $db);
																	$print = mysql_fetch_object($print_result);
																	
																	$pg_result = mysql_query("SELECT id FROM uploaded_images where reference = 'photo_package' and id = '$cart->photo_id'", $db);
																	$pg = mysql_fetch_object($pg_result);
															?>
															<tr>
																<td align="left" valign="middle" colspan="2">
																<span style="padding: 5;">
																	<table border="0">
																		<tr>
																			<? if($setting->show_watermark_thumb == 1){ ?>
																				<td><img src="thumb_mark.php?i=<? echo $pg->id; ?>" width="75" class="photos" border="0"></td>
																			<? } else { ?>
																				<td><img src="image.php?src=<? echo $pg->id; ?>" width="75" class="photos" border="0"></td>
																			<? } ?>
																			<td class="gallery_copy">
																				<?PHP echo $print_print_product; ?><?php echo $print->name; ?><br>
																				<?PHP echo $print_print_price; ?><? echo $currency->sign; ?><? echo dollar($cart->price); ?><?PHP echo $print_print_each; ?>|<?PHP echo $print_print_quantity; ?><?php echo $cart->quantity; ?><br>
																				<? $qtotal = $cart->price * $cart->quantity; ?>
																				<?PHP echo $print_print_total_price; ?><? echo $currency->sign; ?><? echo dollar($qtotal); ?>
																				<?PHP if($print->bypass == 1){ echo "<br>" . $misc_pickup . "<br>"; } ?>
																			</td>
																		</tr>
																	</table>
																</span>
																</td>
															</tr>
															}
														}
												<?
																}
															}
														}
													}
												}
											}
												?>
										</td>
									</tr>
									<? 
									if($_SESSION['sub_type'] == "sub"){
																		echo "<tr><td>";
																		echo "<span style=\"padding: 5;\">";
										 								echo $print_sub_order;
										 								echo "</span>";
										 								echo "<span style=\"padding: 5;\">";
										 								echo $print_sub_order_id . $sub_number;
										 								echo "</span>";
										 								echo "</td></tr>";
										 							}		
										 							?>
</table>