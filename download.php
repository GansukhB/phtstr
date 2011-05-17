<?
	session_start();

	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	
	include("database.php");
	include("functions.php");
	include("config_public.php");
	
	$_SESSION['visitor_id'] = "";
	unset($_SESSION['type']);
	unset($_SESSION['percent']);
	unset($_SESSION['amount']);
	unset($_SESSION['item_free']);
	unset($_SESSION['free_ship']);
	unset($_SESSION['no_tax']);
	unset($_SESSION['coupon_id']);
	unset($_SESSION['paypal_number']);
	
	//UNSET ANY IMAGE VIEWING
	unset($_SESSION['pub_gid']);
	unset($_SESSION['pub_pid']);
	
	if($_GET['item_number']){
		$order = $_GET['item_number'];
	}
?>
<html>
	<head>
		<? print($head); ?>
		<center>
        <table cellpadding="0" cellspacing="0"><tr><td valign="top">
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
							<td class="crumb" width="100%"><a href="index.php" class="crumb_links">Home</a> <img src="images/nav_arrow.gif" align="middle"> <b>Download</b></td>
							<td align="left" class="featured_news_header"><img src="images/triangle_1.gif"></td>
							<td class="other_photos_tabs2" nowrap><a href="new_photos.php" class="white_bold_link"><? echo $misc_newest; ?></a> &nbsp; <a href="popular_photos.php" class="white_bold_link"><? echo $misc_popular; ?></a></td>
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
											<? copy_area(28,1); ?>
										</td>
									</tr>
									<tr>
										<td colspan="3" valign="top" height="100%" style="padding: 10px;">
											<table width="100%">
												<?
													$visitor_result = mysql_query("SELECT visitor_id,order_num,status,done,added FROM visitors where order_num = '$order'", $db);
													$visitor_rows = mysql_num_rows($visitor_result);
													$visitor = mysql_fetch_object($visitor_result);
												?>
													<tr>
														<td class="gallery_copy">
															<b><?PHP echo $download_order_details; ?><a href="<? echo $setting->site_url; ?>/money_return.php?order=<? echo $visitor->order_num; ?>&item=<? echo $visitor->visitor_id; ?>"><?PHP echo $download_order_details2; ?></a></b>.
														</td>
													</tr>
												<?
														if($visitor_rows == 0){
															// NOT A VALID ORDER NUMBER
															echo $download_order_bad;
														} else {
															if($visitor->status == 0 or $visitor->done == 0){
																// STATUS IS 0 - ORDER HAS NOT BEEN PAID FOR YET
																echo $download_order_pending;										
															} else {
																$order_date = round(substr($visitor->added, 4, 2)) . "/" . round(substr($visitor->added, 6, 2)) . "/" . round(substr($visitor->added, 0, 4));
																$expire_date = date("Ymd", strtotime($order_date . '+' . $download_days . ' days'));
																if($expire_date < date("Ymd")){
																	echo "<font color=\"#ff0000\"><b>" . $download_order_expired . "</b></font><br /><br />";
																	//echo date("Ymd", strtotime($order_date . '+' . $download_days . ' days'));
																	//echo date("g:i a", strtotime('+1 hour'));															
																} else {
															
																	// ORDER OK - DOWNLOAD FILES
																	$cart_result = mysql_query("SELECT photo_id,ptype,prid,sid,quantity FROM carts where visitor_id = '$visitor->visitor_id'", $db);
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
																<td align="left" valign="middle" bgcolor="#F9F9F9" style="border: 1px solid #eeeeee; padding: 5px 0px 5px 10px;">
																<span style="padding: 5;">
																	<table border="0">
																		<tr>
																			<? if($setting->show_watermark_thumb == 1){ ?>
																			<td><a href="download_file.php?order=<?php echo $_GET['order']; ?>&pid=<? echo $photo->id; ?>"><img src="thumb_mark.php?i=<? echo $photo->id; ?>" width="75" class="photos" border="0"></a></td>
																			<? } else { ?>
																			<td><a href="download_file.php?order=<?php echo $_GET['order']; ?>&pid=<? echo $photo->id; ?>"><img src="image.php?src=<? echo $photo->id; ?>" width="75" class="photos" border="0"></a></td>
																			<? } ?>
																			<td>
																				<font color="#A4A4A4"><a href="download_file.php?order=<?php echo $_GET['order']; ?>&pid=<? echo $photo->id; ?>" class="photo_links"><?PHP echo $download_download; ?></a>
																			</td>
																		</tr>
																	</table>
																</span>
																</td>
															</tr>
															<?
																} else {
																	if($cart->ptype == "s"){
																		$sizes_result = mysql_query("SELECT id,size FROM sizes where id = '$cart->sid'", $db);
																		$sizes = mysql_fetch_object($sizes_result);
																	
																		$pg1_result = mysql_query("SELECT id FROM uploaded_images where reference = 'photo_package' and id = '$cart->photo_id' order by original", $db);
																		$pg1 = mysql_fetch_object($pg1_result);
																	?>
														<tr>
															<td align="left" valign="middle" bgcolor="#F9F9F9" style="border: 1px solid #eeeeee; padding: 5px 0px 5px 10px;">
																<span style="padding: 5;">
																	<table border="0">
																		<tr>
																			<? if($setting->show_watermark_thumb == 1){ ?>
																			<td><a href="download_file.php?order=<?php echo $_GET['order']; ?>&pid=<? echo $pg1->id; ?>&sid=<? echo $sizes->id; ?>"><img src="thumb_mark.php?i=<? echo $pg1->id; ?>" width="75" class="photos" border="0"></a></td>
																			<? } else { ?>
																			<td><a href="download_file.php?order=<?php echo $_GET['order']; ?>&pid=<? echo $pg1->id; ?>&sid=<? echo $sizes->id; ?>"><img src="image.php?src=<? echo $pg1->id; ?>" width="75" class="photos" border="0"></a></td>
																			<? } ?>
																			<td>
																				<font color="#A4A4A4"><a href="download_file.php?order=<?php echo $_GET['order']; ?>&pid=<? echo $pg1->id; ?>&sid=<? echo $sizes->id; ?>" class="photo_links"><?PHP echo $download_download; ?></a><br>
																				<? echo $download_size . $sizes->size . $download_size_px; ?>
																			</td>
																		</tr>
																	</table>
																</span>
															</td>
														</tr>
															<?
														} else {
																	$print_result = mysql_query("SELECT name FROM prints where id = '$cart->prid'", $db);
																	$print = mysql_fetch_object($print_result);
																	
																	$pg_result = mysql_query("SELECT id FROM uploaded_images where reference = 'photo_package' and id = '$cart->photo_id'", $db);
																	$pg = mysql_fetch_object($pg_result);
																
															?>
															
															<tr>
																<td align="left" valign="middle" bgcolor="#F9F9F9" style="border: 1px solid #eeeeee; padding: 5px 0px 5px 10px;">
																<span style="padding: 5;">
																	<table border="0">
																		<tr>
																			<? if($setting->show_watermark_thumb == 1){ ?>
																			<td><img src="thumb_mark.php?i=<? echo $pg->id; ?>" width="75" class="photos" border="0"></td>
																			<? } else { ?>
																			<td><img src="image.php?src=<? echo $pg->id; ?>" width="75" class="photos" border="0"></td>
																			<? } ?>
																			<td>
																				<font color="#A4A4A4">
																				<b><?PHP echo $download_product; ?></b><br>
																				<?php echo $print->name; ?>
																				<br>
																				<?php echo $download_quantity . $cart->quantity; ?>
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
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>				
				</td>
			</tr>
			<? include("footer.php"); ?>			
		</table>
        </td>
        <td valign="top">
			<?php
				if($pf_feed_status){
					include('pf_feed.php');
				}
			?>
        </td>
        </tr></table>
		</center>
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	