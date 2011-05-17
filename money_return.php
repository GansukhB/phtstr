<?
	session_unset('visitor_id');

	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );
	
	// VISITOR ID
	if(!$_SESSION['visitor_id']){
		session_register("visitor_id");
		$_SESSION['visitor_id'] = random_gen(16,"");
	}
	
		unset($_SESSION['type']);
		unset($_SESSION['percent']);
		unset($_SESSION['amount']);
		unset($_SESSION['item_free']);
		unset($_SESSION['free_ship']);
		unset($_SESSION['no_tax']);
		unset($_SESSION['coupon_id']);
		
		//UNSET ANY IMAGE VIEWING
	unset($_SESSION['pub_gid']);
	unset($_SESSION['pub_pid']);
	
		
	$currency_result = mysql_query("SELECT sign,code FROM currency where active = '1'", $db);
	$currency = mysql_fetch_object($currency_result);
	
	$visitor_result = mysql_query("SELECT visitor_id,status,price,tax,shipping,savings,payment_method,done,coupon_id,added,paypal_email,ups,fedex,dhl,track FROM visitors where order_num = '" . $order . "'", $db);
	$visitor_rows = mysql_num_rows($visitor_result);
	$visitor = mysql_fetch_object($visitor_result);
	
	if($visitor_rows > 0){
	$total = dollar2($visitor->price);
	$tax = dollar2($visitor->tax);
	$shipping = dollar2($visitor->shipping);
	$savings = dollar2($visitor->savings);
	$grand = dollar2($total + $shipping + $tax - $savings);
	$coupon_id = $visitor->coupon_id;
	$odate = $visitor->added;
	$email = $visitor->paypal_email;
	$method = $visitor->payment_method;
	$order_num = $_GET['order'];
	
	if($visitor->done == 0){
		$pending = $cmo_process_order_message;
	} else {
		$pending = $cmo_completed_order_message;
	}
	
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
		
	if($_SESSION['sub_type'] == "sub"){
		$email = $_SESSION['sub_email'];
		$method = "Check / Money Order";
		$pending = "Pending Payment";
		$order_num = $_GET['order'];
		$sub_number = $_SESSION['id'];
		$total = $_SESSION['ses_total'];
		$odate = date("Ymd");
	}
	if($currency->code == "JPY"){
				$total = $visitor->price;
				$tax = $visitor->tax;
				$shipping = $visitor->shipping;
				$savings = $visitor->savings;
				$grand = $total + $shipping + $tax - $savings;
				$total = round($total);
				$tax = round($tax);
				$shipping = round($shipping);
				$savings = round($savings);
				$grand = round($grand);
	}	
	$item = $_GET['item'];
	$date = date("F j, Y", strtotime($odate));
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
						<tr>
						<?php
							$crumb = $cmo_thanks;
							include("crumbs.php");
						?>
						</tr>
						<tr>
							<td class="index_copy_area" colspan="3" height="4"></td>
						</tr>						
						<tr>
							<td colspan="3" valign="top" height="100%" class="homepage_line">
								<table width="100%" border="0">
									<tr>
										<td height="6"></td>
									</tr>
											<tr>
												<td class="gallery_copy">
												<a href="print.php?total=<? echo $total; ?>&shipping=<? echo $shipping; ?>&grand=<? echo $grand; ?>&order=<? echo $order_num; ?>&item=<? echo $item; ?>" target="_blank"><img src="images/print.gif" border="0">
												<?PHP echo $cmo_print_version; ?></a><br><br>
												<?PHP echo $cmo_your_order_is; ?><br><br></td>
											</tr>
									</tr>
										<tr>
										<td width="200" class="gallery_copy"><?PHP echo $cmo_order_date; ?></td><td><? echo $date; ?></td>
									</tr>
										<tr>
										<td width="200" class="gallery_copy"><?PHP echo $cmo_order_email; ?></td><td><? echo $email; ?></td>
									</tr>
										<tr>
										<td width="200" class="gallery_copy"><?PHP echo $cmo_order_method; ?></td><td><? echo $method; ?></td>
									</tr>
									<tr>
										<td width="200" class="gallery_copy"><?PHP echo $cmo_order_status; ?></td><td><? echo $pending; ?></td>
									</tr>
										<tr>
										<td width="200" class="gallery_copy"><?PHP echo $cmo_order_id; ?></td><td><? echo $order_num; ?></td>
									</tr>
										<tr>
										<td width="200" class="gallery_copy"><?PHP echo $cmo_order_visitor_id; ?></td><td><?echo $item; ?></td>
									</tr>
									<tr>
										<td colspan="2" class="gallery_copy"><br><?PHP echo $cmo_order_tracking; ?><? if($visitor->ups != ""){ ?><br><a href="http://www.ups.com/WebTracking/track?loc=en_US&WT.svl=PriNav" class="gallery_copy" target="_blank"><?PHP echo $cmo_ups; ?><? echo $visitor->ups; ?></a><? } if($visitor->fedex != ""){ ?><br><a href="http://www.fedex.com/Tracking?link=1&cntry_code=us&lid=//Track//Pack+Track+Corp" class="gallery_copy" target="_blank"><?PHP echo $cmo_fedex; ?><? echo $visitor->fedex; ?></a><? } if($visitor->dhl != ""){ ?><br><a href="http://track.dhl-usa.com/TrackByNbr.asp?nav=TrackBynumber" class="gallery_copy" target="_blank"><?PHP echo $cmo_dhl; ?><? echo $visitor->dhl; ?></a><? } if($visitor->track != ""){ ?><br><a href="http://www.usps.com/shipping/trackandconfirm.htm?from=home&page=0035trackandconfirm" class="gallery_copy" target="_blank"><?PHP echo $cmo_postal; ?><? echo $visitor->track; ?></a><? } ?></td>
									<tr>
										<td colspan="2" class="gallery_copy"><hr width="100%" align="left"></td>
									</tr>
										<tr>
										<td width="200" class="gallery_copy"><?PHP echo $cmo_order_total; ?></td><td><? echo $currency->sign; ?><? echo $total; ?></td>
									</tr>
								<? if($_SESSION['sub_type'] != "sub"){ ?>
										<tr>
									<td width="200" class="gallery_copy"><?PHP echo $cmo_order_tax; ?><? if($setting->tax1_name != "" or $setting->tax2_name != ""){ ?><br><? if($setting->tax1_name != ""){ echo "(" . $setting->tax1_name; } else { echo "("; }?><? if($setting->tax1_name != "" && $setting->tax2_name != ""){ echo " & "; } ?><? if($setting->tax2_name != ""){ echo $setting->tax2_name . ")"; } else { echo ")"; } }?></td><td><? echo $currency->sign; ?><? echo $tax; ?></td>
									</tr>
										<tr>
										<td width="200" class="gallery_copy"><?PHP echo $cmo_order_shipping; ?></td><td><? echo $currency->sign; ?><? echo $shipping; ?></td>
									</tr>
										<? if($savings > 0){ ?>
									<tr>
										<td width="150" class="gallery_copy"><?PHP echo $cmo_order_coupon; ?></td><td><? echo $currency->sign; ?><? echo $savings; ?></td>
									</tr>
								<? } ?>
								<? if($coupon_rows > 0){ ?>
									<tr>
										<td width="150" class="gallery_copy"><?PHP echo $cmo_order_coupon_used; ?></td><td><? echo $type_name; ?></td>
									</tr>
								<? } ?>
										<tr>
										<td width="150" class="gallery_copy"><?PHP echo $cmo_order_grand_total; ?></td><td><? echo $currency->sign; ?><? echo $grand; ?></td>
									</tr>
								<? } ?>
										<tr>
										<td  class="gallery_copy" colspan="2">
										<hr width="100%" align="left"><br><br>										
										<? copy_area(11,3); ?><br><br>
										<?PHP echo $cmo_item_details; ?>
										<hr width="100%">
									</td>
								</tr>
										<?			
										 			if($_SESSION['sub_type'] != "sub"){		
														if($visitor_rows == 0){
															// NOT A VALID ORDER NUMBER
														} else {
															if($visitor->status == 0){
																// STATUS IS 0 - ORDER HAS NOT BEEN PAID FOR YET
																echo $cmo_order_pending_payment;										
															} else {
															
																	// ORDER OK - DOWNLOAD FILES
																	$cart_result = mysql_query("SELECT photo_id,ptype,price,sid,prid,quantity FROM carts where visitor_id = '$visitor->visitor_id'", $db);
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
																<td colspan="2" align="left" valign="middle" bgcolor="#F9F9F9" style="border: 1px solid #eeeeee; padding: 5px 0px 5px 10px;">
																<span style="padding: 5;">
																	<table border="0">
																		<tr>
																		<? if($setting->show_watermark_thumb == 1){ ?>
																			<td><img src="thumb_mark.php?i=<? echo $photo->id; ?>" width="75" class="photos" border="0"></td>
																		<? } else { ?>
																			<td><img src="image.php?src=<? echo $photo->id; ?>" width="75" class="photos" border="0"></td>
																		<? } ?>
																			<td>
																				<?PHP echo $cmo_product_download; ?> <? if($visitor->payment_method == "Check/Money Order"){ ?><?PHP echo $cmo_product_download2; ?><? } ?><br>
																			<?PHP echo $cmo_download_price; ?> <? echo $currency->sign; ?><? echo dollar($cart->price); ?>
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
																<td colspan="2" align="left" valign="middle" bgcolor="#F9F9F9" style="border: 1px solid #eeeeee; padding: 5px 0px 5px 10px;">
																<span style="padding: 5;">
																	<table border="0">
																		<tr>
																		<? if($setting->show_watermark_thumb == 1){ ?>
																			<td><img src="thumb_mark.php?i=<? echo $pg1->id; ?>" width="75" class="photos" border="0"></td>
																		<? } else { ?>
																			<td><img src="image.php?src=<? echo $pg1->id; ?>" width="75" class="photos" border="0"></td>
																		<? } ?>
																			<td>
																				<?PHP echo $cmo_size_product; ?> <?php echo $sizes->name; ?><br>
																				<?PHP echo $cmo_size_size; ?> <? echo $sizes->size; ?><br>
																				<?PHP echo $cmo_size_price; ?> <? echo $currency->sign; ?><? echo dollar($cart->price); ?><br>
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
																<td colspan="2" align="left" valign="middle" bgcolor="#F9F9F9" style="border: 1px solid #eeeeee; padding: 5px 0px 5px 10px;">
																<span style="padding: 5;">
																	<table border="0">
																		<tr>
																		<? if($setting->show_watermark_thumb == 1){ ?>
																			<td><img src="thumb_mark.php?i=<? echo $pg->id; ?>" width="75" class="photos" border="0"></td>
																		<? } else { ?>
																			<td><img src="image.php?src=<? echo $pg->id; ?>" width="75" class="photos" border="0"></td>
																		<? } ?>
																			<td>
																				<?PHP echo $cmo_print_product; ?> <?php echo $print->name; ?><br>
																				<?PHP echo $cmo_print_price; ?> <? echo $currency->sign; ?><? echo dollar($cart->price); ?> (each)<br>
																				<?PHP echo $cmo_print_quantity; ?> <?php echo $cart->quantity; ?><br>
																				<? $qtotal = $cart->price * $cart->quantity; ?>
																				<?PHP echo $cmo_price_total; ?> <? echo $currency->sign; ?><? echo dollar($qtotal); ?>
																				<?PHP if($print->bypass == 1){ echo "<br>" . $misc_pickup . "<br>"; } ?>
																			</td>
																		</tr>
																	</table>
																</span>
																</td>
															</tr>
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
										 								echo $cmo_sub_order;
										 								echo "</span>";
										 								echo "<span style=\"padding: 5;\">";
										 								echo $cmo_sub_id . $sub_number;
										 								echo "</span>";
										 								echo "</td></tr>";
										 							}		
									?>						
								</table>
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