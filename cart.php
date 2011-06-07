<?
	session_start();

	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );
	
	$currency_result = mysql_query("SELECT sign,code FROM currency where active = '1'", $db);
	$currency = mysql_fetch_object($currency_result);
	
	// VISITOR ID
	if(!$_SESSION['visitor_id']){
		session_register("visitor_id");
		$_SESSION['visitor_id'] = random_gen(16,"");
	}
	
	//UNSET ANY IMAGE VIEWING
	unset($_SESSION['pub_gid']);
	unset($_SESSION['pub_pid']);
	unset($_SESSION['imagenav']);
	
	$product_array = array();
	$prodcalc_array = array();
	$product_prices = array();
?>
<html>
	<head>
	<script language=javascript type="text/javascript">
    	function NewWindow(page, name, w, h, location, scroll) {
        var winl = (screen.width - w) / 2;
        var wint = (screen.height - h) / 2;
        winprops = 'height='+h+',width='+w+',location='+location+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable'
        win = window.open(page, name, winprops)
        if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
    	}
	</script>
    <?php echo $script1; ?>
		<? print($head); ?>
    <? include("header.php"); ?>
<div class="container">
    
		<div id="main">
			<? include("i_gallery_nav.php"); ?>
      <div class="right-main">
        
            	<!--r-left-main ehlel-->
            	<div class="r-left-main">
                  <div class="r-content-main">
                    	<div align="center"><h2><?php echo $cart_my_cart; ?></h2></div>
                      
                        <ul class="menu1">
                        	<li><a href="#">Send</a></li>
                            <li><a href="#">Share</a></li>
                            <li><a href="#">Rename</a></li>
                            <li><a href="#">Delete</a></li>
                            <li><a href="#">Remove</a></li>
                            <li><a href="#">Copy</a></li>
                            <li><a href="#">Move</a></li>
                        </ul>
                        <div class="lighbox-main">
                          <style>
                            
                          </style>
												<?
													$total = 0;
													$shipping = 0;
													$cart_result = mysql_query("SELECT id,ptype,prid,sid,photo_id,quantity FROM carts where visitor_id = '" . $_SESSION['visitor_id'] . "'", $db);
													$cart_rows = mysql_num_rows($cart_result);
													while($cart = mysql_fetch_object($cart_result)){
														
														if($cart->ptype == "d"){
															$photo_result = mysql_query("SELECT id,reference_id,price FROM uploaded_images where id = '$cart->photo_id'", $db);
															$photo = mysql_fetch_object($photo_result);	
															//$info_result = mysql_query("SELECT * FROM photo_package where id = '$photo->reference_id'", $db);
															//$info = mysql_fetch_object($info_result);														
														}
														
														if($cart->ptype == "s"){
															$photo_s_result = mysql_query("SELECT reference_id,id FROM uploaded_images where id = '$cart->photo_id'", $db);
															$photo_s = mysql_fetch_object($photo_s_result);	
															//$info_s_result = mysql_query("SELECT * FROM photo_package where id = '$photo_s->reference_id'", $db);
															//$info_s = mysql_fetch_object($info_s_result);												
														}
												?>
                        <div class="box-content">
                        <?php
                              if($cart->ptype == "d"){
																		$price = "0.00";
																		$prices = "0.00";
																		$minus_ship = "0.00";
																		if($setting->show_watermark_thumb == 1){
																			?>
																		<a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $photo->reference_id; ?>"><img src="thumb_mark.php?i=<? echo $photo->id; ?>"  class="photos" border="0"></a>
																     <? } else { ?>
																    <a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $photo->reference_id; ?>"><img src="image.php?src=<? echo $photo->id; ?>"  class="photos" border="0"></a>
																     	<? } ?>
                                    
                                      <?
                                        //echo $cart_digital_price;						
                                        if($photo->price){
                                          $price_down = $photo->price;	
                                        } else {
                                          $price_down = $setting->default_price;	
                                        }
                                        if($setting->tax_download == 0){
                                          $minus_tax = $price_down;
                                        } else {
                                          $minus_tax = "0.00";
                                        }
                                        echo $currency->sign . $price_down;
                                        
                                      ?>
                                      <!--
                                      <a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $photo->reference_id; ?>" class="photo_links"><?PHP echo $cart_details_link; ?></a> | <a href="public_actions.php?pmode=delete_cart&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&cid=<? echo $cart->id; ?>&prid=<? echo $cart->prid; ?>" class="photo_links"><?PHP echo $cart_remove_from_cart; ?></a>
                                      -->
																<?
																	} else {
																	if($cart->ptype == "s"){
																		$price_down = "0.00";
																		$price = "0.00";
																		$minus_ship = "0.00";
																	if($setting->show_watermark_thumb == 1){
																			?>
																	<a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $photo_s->reference_id; ?>"><img src="thumb_mark.php?i=<? echo $photo_s->id; ?>"  class="photos" border="0"></a>
																	<?php } else { ?>
																	<a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $photo_s->reference_id; ?>"><img src="image.php?src=<? echo $photo_s->id; ?>"  class="photos" border="0"></a>
																	<?php } ?>
																	
																		<?
																		$sizes_result = mysql_query("SELECT price FROM sizes where id = '$cart->sid'", $db);
																		$sizes = mysql_fetch_object($sizes_result);
																			echo $cart_digital_price;
																			if($sizes->price){
																				$prices = $sizes->price;	
																			} else {
																				$prices = $setting->default_price;	
																			}
																			if($setting->tax_download == 0){
																				$minus_tax = $prices;
																			} else {
																				$minus_tax = "0.00";
																			}
																			echo $currency->sign . $prices ;
																			
																		?>
																		<a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $photo_s->reference_id; ?>" class="photo_links"><?PHP echo $cart_details_link; ?></a> | <a href="public_actions.php?pmode=delete_cart&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&cid=<? echo $cart->id; ?>&prid=<? echo $cart->prid; ?>" class="photo_links"><?PHP echo $cart_remove_from_cart; ?></a>
																	
																<?
																	} else {
																		$print_result = mysql_query("SELECT id,name,ship_price1,ship_price2,price,quan_avail,bypass,taxable FROM prints where id = '$cart->prid'", $db);
																		$print = mysql_fetch_object($print_result);
																		
																		$pg_result = mysql_query("SELECT id,reference_id FROM uploaded_images where reference = 'photo_package' and id = '$cart->photo_id'", $db);
																		$pg = mysql_fetch_object($pg_result);
																		
																		$price_down = "0.00";
																		$prices = "0.00";
																		
																		if($print->bypass == 1){
																			$minus_ship = $print->price;
																			$minus_ship = $minus_ship * $cart->quantity;
																		} else {
																			$minus_ship = "0.00";
																		}
																		if($print->taxable == 0){
																			$minus_tax = $print->price;
																			$minus_tax = $minus_tax * $cart->quantity;
																		} else {
																			$minus_tax = "0.00";
																		}
																		for($x = 1; $x <= $cart->quantity; $x++){																		
																			if(!in_array($print->id,$product_array)){
																				$product_array[] = $print->id;
																				$product_prices[] = $print->ship_price1;
																			} else {
																				$product_array[] = $print->id;
																				if(!$print->ship_price2){
																					$product_prices[] = $print->ship_price1;
																				} else {
																					$product_prices[] = $print->ship_price2;
																				}
																			}	
																		}																										
																																			
																	if($setting->show_watermark_thumb == 1){
																 	?>
																 	<a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $pg->reference_id; ?>"><img src="thumb_mark.php?i=<? echo $pg->id; ?>"  class="photos" border="0"></a>
																 	<?php } else { ?>
																	<a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $pg->reference_id; ?>"><img src="image.php?src=<? echo $pg->id; ?>"  class="photos" border="0"></a>
																	<?php } ?>
																	<?
																					
																				
																					echo $print->name;
																					echo $cart_print_price;						
																					if($print->price){
																						$price1 = $print->price;
																						$price = $print->price * $cart->quantity;
																					} else {
																						$price1 = "5.00";
																						$price = "5.00" * $cart->quantity;
																					}
																					echo $currency->sign . dollar2($price1) . " (each)";
																					if($print->bypass == 1){
																						echo "<br>" . $misc_pickup . "<br>";
																					}
																					if($setting->fix_price1 == "0.00" & $setting->fix_price2 == "0.00" & $setting->fix_price3 == "0.00" & $setting->fix_price4 == "0.00"){
																						$shipping = array_sum($product_prices);
																					
																						if($print->ship_price1 != "0.00"){
																							echo $cart_shipping . $currency->sign . $print->ship_price1;
																							if($print->ship_price1 == $print->ship_price2){
																								echo " (each)";
																							} else {
																								echo $cart_shipping2 . $currency->sign . $print->ship_price2 . $cart_shipping3;
																							}
																						}
																					
																					} else {
																																
																					}	
																				?>
																		<!--		<a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $pg->reference_id; ?>" class="photo_links"><?PHP echo $cart_details_link; ?></a> | <a href="public_actions.php?pmode=delete_cart&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&cid=<? echo $cart->id; ?>&prid=<? echo $cart->prid; ?>" class="photo_links"><?PHP echo $cart_remove_from_cart; ?></a> -->
																		
                                    <div class="box-content"></div>
                            <div class="box-content"></div>
                            <div class="box-content"></div>
                            <div class="box-content"></div>
                            <div class="box-content"></div>
                            <div class="box-content"></div>
                            <div class="box-content"></div>
                            <div class="box-content"></div>
                            <div class="box-content"></div>
                            <div class="box-content"></div>
                        </div>		
                        <!--
																				<form action="public_actions.php?pmode=update_quantity" method="post">
																				<input type="hidden" name="cid" value="<?php echo $cart->id; ?>">
																				<input type="hidden" name="quan_avail" value="<?php echo $print->quan_avail; ?>">
																				<input type="hidden" name="prid" value="<?php echo $print->id; ?>">
																				<div valign="top" align="left" style="padding-left: 10px;" nowrap>
																					Quantity:<br><input type="text" value="<?php echo $cart->quantity; ?>" style="width: 50px;" name="quantity"> <input type="submit" value="Update">
																					<?php
																						if($print->quan_avail != "999"){ 
																							echo  $cart_quantity . $print->quan_avail;
																						}
																					?>
																				</div>
																				</form>-->
																			
  
																<?
																	}
																}
																?>
                              </div>
                        <?php 
                          }  
                        ?>
                    
                        
                        	
                  </div>
                </div>
                <!--r-left-main tugsgul-->
                <!--r-right-main ehlel-->
                <div class="r-right-main">
                	
                </div>
                <!--r-right-main tugsgul-->
                <!--content ehlel-->
                <div class="content">
                	<img class="image" src="images/image2.jpg">
                	Template Monster website templates, Flash templates and other web design products are famous for being top quality solution for a quick, easy and affordable website production. The best part about our templates is the simplicity - you purchase the template package, customize it a little bit and upload it to your hosting. And there you have it - your website is up and running within as little as several hours from the moment you've been choosing a website template! So when
                </div>
                <!--content tugsgul-->
                <!--content ehlel-->
                <div class="content">
                	<!-- video-content ehlel-->
                	<div class="video-content line">
                    	<h1>7 ХОНОГИЙН ҮНЭГҮЙ ЗУРАГ</h1>
                        <img class="image" src="images/image2.jpg">
                        <div class="copyright">by <a href="#">miamore</a></div>
                        <div><span>web design colection</span></div>
                        <div class="download">
                        	<a href="#">татах</a>
                        </div>
                    </div>
                    <!-- video-content tugsgul-->
                    <!-- video-content ehlel-->
                    <div class="video-content line">
                    	<h1>7 ХОНОГИЙН ҮНЭГҮЙ ЗУРАГ</h1>
                        <img class="image" src="images/image2.jpg">
                        <div class="copyright">by <a href="#">miamore</a></div>
                        <div><span>web design colection</span></div>
                        <div class="download">
                        	<a href="#">татах</a>
                        </div>
                    </div>
                    <!-- video-content tugsgul-->
                    <!-- video-content ehlel-->
                    <div class="video-content">
                    	<h1>7 ХОНОГИЙН ҮНЭГҮЙ ЗУРАГ</h1>
                        <img class="image" src="images/image2.jpg">
                        <div class="copyright">by <a href="#">miamore</a></div>
                        <div><span>web design colection</span></div>
                        <div class="download">
                        	<a href="#">татах</a>
                        </div>
                    </div>
                    <!-- video-content tugsgul-->
                </div>
                <!--content tugsgul-->
                <?php include('i_nav_photo.php'); ?>
            
        <tr>
							<td colspan="3" valign="top" height="100%" class="homepage_line">
								<table width="100%" border="0">
									<tr>
										<td height="6"></td>
									</tr>
									<tr>
										<td align="right">
											<a href="javascript:history.go(-1)"><? echo $cart_continue_shopping; ?></a>
										</td>
									</tr>
									<tr>
										<td>
											
											<table width="100%">
												<?
													$total = 0;
													$shipping = 0;
													$cart_result = mysql_query("SELECT id,ptype,prid,sid,photo_id,quantity FROM carts where visitor_id = '" . $_SESSION['visitor_id'] . "'", $db);
													$cart_rows = mysql_num_rows($cart_result);
													while($cart = mysql_fetch_object($cart_result)){
														
														if($cart->ptype == "d"){
															$photo_result = mysql_query("SELECT id,reference_id,price FROM uploaded_images where id = '$cart->photo_id'", $db);
															$photo = mysql_fetch_object($photo_result);	
															//$info_result = mysql_query("SELECT * FROM photo_package where id = '$photo->reference_id'", $db);
															//$info = mysql_fetch_object($info_result);														
														}
														
														if($cart->ptype == "s"){
															$photo_s_result = mysql_query("SELECT reference_id,id FROM uploaded_images where id = '$cart->photo_id'", $db);
															$photo_s = mysql_fetch_object($photo_s_result);	
															//$info_s_result = mysql_query("SELECT * FROM photo_package where id = '$photo_s->reference_id'", $db);
															//$info_s = mysql_fetch_object($info_s_result);												
														}
												?>
												<tr>
													<td align="left" valign="middle" bgcolor="#F9F9F9" style="border: 1px solid #eeeeee; padding: 5px 0px 5px 0px;">
													<span style="padding: 5;">
														<table border="0">
															<tr>
																<?
																	if($cart->ptype == "d"){
																		$price = "0.00";
																		$prices = "0.00";
																		$minus_ship = "0.00";
																		if($setting->show_watermark_thumb == 1){
																			?>
																		<td><a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $photo->reference_id; ?>"><img src="thumb_mark.php?i=<? echo $photo->id; ?>"  class="photos" border="0"></a></td>
																     <? } else { ?>
																    <td><a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $photo->reference_id; ?>"><img src="image.php?src=<? echo $photo->id; ?>" class="photos" border="0"></a></td>
																     	<? } ?>
																	<td>
																		<?
																			//echo $cart_digital_price;						
																			if($photo->price){
																				$price_down = $photo->price;	
																			} else {
																				$price_down = $setting->default_price;	
																			}
																			if($setting->tax_download == 0){
																				$minus_tax = $price_down;
																			} else {
																				$minus_tax = "0.00";
																			}
																			echo $currency->sign . $price_down . "<br><br>";
																			
																		?>
																		<font color="#A4A4A4"><a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $photo->reference_id; ?>" class="photo_links"><?PHP echo $cart_details_link; ?></a> | <a href="public_actions.php?pmode=delete_cart&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&cid=<? echo $cart->id; ?>&prid=<? echo $cart->prid; ?>" class="photo_links"><?PHP echo $cart_remove_from_cart; ?></a>
																	</td>
																<?
																	} else {
																	if($cart->ptype == "s"){
																		$price_down = "0.00";
																		$price = "0.00";
																		$minus_ship = "0.00";
																	if($setting->show_watermark_thumb == 1){
																			?>
																	<td><a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $photo_s->reference_id; ?>"><img src="thumb_mark.php?i=<? echo $photo_s->id; ?>"  class="photos" border="0"></a></td>
																	<?php } else { ?>
																	<td><a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $photo_s->reference_id; ?>"><img src="image.php?src=<? echo $photo_s->id; ?>" class="photos" border="0"></a></td>
																	<?php } ?>
																	<td>
																		<?
																		$sizes_result = mysql_query("SELECT price FROM sizes where id = '$cart->sid'", $db);
																		$sizes = mysql_fetch_object($sizes_result);
																			echo $cart_digital_price;
																			if($sizes->price){
																				$prices = $sizes->price;	
																			} else {
																				$prices = $setting->default_price;	
																			}
																			if($setting->tax_download == 0){
																				$minus_tax = $prices;
																			} else {
																				$minus_tax = "0.00";
																			}
																			echo $currency->sign . $prices . "<br><br>";
																			
																		?>
																		<font color="#A4A4A4"><a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $photo_s->reference_id; ?>" class="photo_links"><?PHP echo $cart_details_link; ?></a> | <a href="public_actions.php?pmode=delete_cart&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&cid=<? echo $cart->id; ?>&prid=<? echo $cart->prid; ?>" class="photo_links"><?PHP echo $cart_remove_from_cart; ?></a>
																	</td>
																<?
																	} else {
																		$print_result = mysql_query("SELECT id,name,ship_price1,ship_price2,price,quan_avail,bypass,taxable FROM prints where id = '$cart->prid'", $db);
																		$print = mysql_fetch_object($print_result);
																		
																		$pg_result = mysql_query("SELECT id,reference_id FROM uploaded_images where reference = 'photo_package' and id = '$cart->photo_id'", $db);
																		$pg = mysql_fetch_object($pg_result);
																		
																		$price_down = "0.00";
																		$prices = "0.00";
																		
																		if($print->bypass == 1){
																			$minus_ship = $print->price;
																			$minus_ship = $minus_ship * $cart->quantity;
																		} else {
																			$minus_ship = "0.00";
																		}
																		if($print->taxable == 0){
																			$minus_tax = $print->price;
																			$minus_tax = $minus_tax * $cart->quantity;
																		} else {
																			$minus_tax = "0.00";
																		}
																		for($x = 1; $x <= $cart->quantity; $x++){																		
																			if(!in_array($print->id,$product_array)){
																				$product_array[] = $print->id;
																				$product_prices[] = $print->ship_price1;
																			} else {
																				$product_array[] = $print->id;
																				if(!$print->ship_price2){
																					$product_prices[] = $print->ship_price1;
																				} else {
																					$product_prices[] = $print->ship_price2;
																				}
																			}	
																		}																										
																																			
																	if($setting->show_watermark_thumb == 1){
																 	?>
																 	<td><a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $pg->reference_id; ?>"><img src="thumb_mark.php?i=<? echo $pg->id; ?>" width="75" class="photos" border="0"></a></td>
																 	<?php } else { ?>
																	<td><a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $pg->reference_id; ?>"><img src="image.php?src=<? echo $pg->id; ?>" width="75" class="photos" border="0"></a></td>
																	<?php } ?>
																	<td>
																		<table>
																			<tr>
																				<td width="300">
																				<?
																					
																				
																					echo $print->name;
																					echo $cart_print_price;						
																					if($print->price){
																						$price1 = $print->price;
																						$price = $print->price * $cart->quantity;
																					} else {
																						$price1 = "5.00";
																						$price = "5.00" * $cart->quantity;
																					}
																					echo $currency->sign . dollar2($price1) . " (each)";
																					if($print->bypass == 1){
																						echo "<br>" . $misc_pickup . "<br>";
																					}
																					if($setting->fix_price1 == "0.00" & $setting->fix_price2 == "0.00" & $setting->fix_price3 == "0.00" & $setting->fix_price4 == "0.00"){
																						$shipping = array_sum($product_prices);
																					
																						if($print->ship_price1 != "0.00"){
																							echo $cart_shipping . $currency->sign . $print->ship_price1;
																							if($print->ship_price1 == $print->ship_price2){
																								echo " (each)";
																							} else {
																								echo $cart_shipping2 . $currency->sign . $print->ship_price2 . $cart_shipping3;
																							}
																						}
																					
																						echo  "<br><br>";
																					} else {
																																
																					}	
																				?>
																				<font color="#A4A4A4"><a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $pg->reference_id; ?>" class="photo_links"><?PHP echo $cart_details_link; ?></a> | <a href="public_actions.php?pmode=delete_cart&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&cid=<? echo $cart->id; ?>&prid=<? echo $cart->prid; ?>" class="photo_links"><?PHP echo $cart_remove_from_cart; ?></a>
																				</td>
																				<form action="public_actions.php?pmode=update_quantity" method="post">
																				<input type="hidden" name="cid" value="<?php echo $cart->id; ?>">
																				<input type="hidden" name="quan_avail" value="<?php echo $print->quan_avail; ?>">
																				<input type="hidden" name="prid" value="<?php echo $print->id; ?>">
																				<td valign="top" align="left" style="padding-left: 10px;" nowrap>
																					Quantity:<br><input type="text" value="<?php echo $cart->quantity; ?>" style="width: 50px;" name="quantity"> <input type="submit" value="Update">
																					<?php
																						if($print->quan_avail != "999"){ 
																							echo  $cart_quantity . $print->quan_avail;
																						}
																					?>
																				</td>
																				</form>
																			</tr>
																		</table>																		
																	</td>
																<?
																	}
																}
																?>
															</tr>
														</table>
													</span>
													</td>
												</tr>
												<?
													$sub_minus_tax = $sub_minus_tax + $minus_tax;
												  $sub_minus = $sub_minus + $minus_ship;
													$sub_print = $sub_print + $price;
													$fix_total = $sub_print - $sub_minus;													
													if($cart->ptype == "p" && $fix_total > 0){
													if($setting->fix_price1 == "0.00" & $setting->fix_price2 == "0.00" & $setting->fix_price3 == "0.00" & $setting->fix_price4 == "0.00"){
													} else {
																				if($fix_total >= $setting->fix_cart7){
																					$shipping = $setting->fix_price4;
																				} else {
																				if($fix_total >= $setting->fix_cart5 & $setting->fix_cart6 >= $fix_total){
																					$shipping = $setting->fix_price3;
																				} else {
																				if($fix_total >= $setting->fix_cart3 & $setting->fix_cart4 >= $fix_total){
																					$shipping = $setting->fix_price2;
																				} else {
																				if($fix_total <= $setting->fix_cart2){
																					$shipping = $setting->fix_price1;
																				}	
																			}
																		}
																	}
																}
															} else {
															}
															$sub_price_down = $sub_price_down + $price_down;
															$sub_price_sid = $sub_price_sid + $prices;
												 	}
												 	if($setting->print_ship == 1 && $sub_minus > 0){
														$shipping = "0.00";
													}						
												 	$sub_total = $sub_price_down + $sub_print + $sub_price_sid;				
												?>
												<? if($cart_rows > 0){ ?>
												<tr>
														<td align="right">
														<table>
															<?php
															
															if($_SESSION['percent']){
																$savings = $_SESSION['percent'] * $sub_total;
															}
															if($_SESSION['amount']){
																$savings = $_SESSION['amount'];
															}
															if($_SESSION['free_ship']){
																$shipping = "0.00";
																$savyes = "1";
															}		
															$sub_total = dollar2($sub_total);
															$shipping = dollar2($shipping);
															if($currency->code == "JPY"){
															$sub_total = round($sub_total);
															$shipping = round($shipping);
															$savings = round($savings);
															}
															    if($savings != "" or $_SESSION['free_ship'] != "" or $_SESSION['no_tax'] != ""){															
																	echo "<tr><td><span><b><a href=\"cart.php?message=reset\">" . $cart_remove . "</a></span></td></tr>";																	
																	}
																	echo "<tr><td><span><b>" . $cart_subtotal . "</td><td><font color=\"#ff0000\">" . $currency->sign . $sub_total . "</font></b></span></td></tr>";
																
																	echo "<tr><td><span><b>" . $cart_shipping4 . "</td><td> <font color=\"#ff0000\">" . $currency->sign . $shipping . "</font></b></span></td></tr>";
																	
																if($savings != "" or $savyes == "1"){
																	if($savyes == "1"){
																	echo "<tr><td><span><b>" . $cart_coupon . "</td><td> <font color=\"#4AAD4B\">" . $cart_free_shipping . "</font></b></span></td></tr>";
																	} else {
																	echo "<tr><td><span><b>" . $cart_coupon . "</td><td> <font color=\"#4AAD4B\">-" . $currency->sign; if($currency->code == "JPY"){ echo round($savings); } else { echo dollar2($savings); } echo "</font></b></span></td></tr>";
																 }
																}
																
																$sub_total = $sub_total - dollar2($savings);
																
															if(!$_SESSION['no_tax']){													
															if($setting->tax_total == "0"){
															$tax1 = $setting->tax1;
															$tax2 = $setting->tax2;
															$temp_total1 = $sub_total - $sub_minus_tax;
															if($tax1 > 0){
																$tax1_amount = $temp_total1 * ($tax1 / 100);
															}
															if($tax2 > 0){
																$tax2_amount = $temp_total1 * ($tax2 / 100);
															}
																$tax = $tax1_amount + $tax2_amount;
															} else {
															$tax1 = $setting->tax1;
															$tax2 = $setting->tax2;
															$temp_total1 = $sub_total + $shipping - $sub_minus_tax;
															if($tax1 > 0){
																$tax1_amount = $temp_total1 * ($tax1 / 100);
															}
															if($tax2 > 0){
																$tax2_amount = $temp_total1 * ($tax2 / 100);
															}
																$tax = $tax1_amount + $tax2_amount;
															}
															$tax1_amount = dollar2(round($tax1_amount, 2));
															$tax2_amount = dollar2(round($tax2_amount, 2));
															$tax = dollar2(round($tax, 2));
															if($currency->code == "JPY"){
															$tax1_amount = round($tax1_amount);
															$tax2_amount = round($tax2_amount);
															$tax = round($tax);
															}
																	if($setting->tax1_name != "" or $setting->tax2_name != ""){
																	if($setting->tax1_name != ""){
																	echo "<tr><td><span><b>" . $setting->tax1_name . ": </td><td> <font color=\"#ff0000\">" . $currency->sign . $tax1_amount . "</font></b></span></td></tr>";
																}
																	if($setting->tax2_name != ""){
																	echo "<tr><td><span><b>" . $setting->tax2_name . ": </td><td> <font color=\"#ff0000\">" . $currency->sign . $tax2_amount . "</font></b></span></td></tr>";									
																}
																} else {
																	echo "<tr><td><span><b>" . $cart_tax_static . "</td><td> <font color=\"#ff0000\">" . $currency->sign . $tax . "</font></b></span></td></tr>";
																}
															} else {
																echo "<tr><td><span><b>" . $cart_tax_static . "</td><td> <font color=\"#4AAD4B\">" . $cart_no_tax . "</font></b></span></td></tr>";
															}
															if($currency->code == "JPY"){
																if($sub_total == "0" && $_SESSION['coupon_id'] > 0){
																	$shipping = "0.00";
																}
																	$grand_total = $sub_total + $shipping + $tax;
																	$grand_total = round($grand_total);
																} else {
																	if($sub_total == "0" && $_SESSION['coupon_id'] > 0){
																		$shipping = "0.00";
																	}
																	$grand_total = $sub_total + $shipping + $tax;
															 	}
															?>
															<tr>
															<? if($currency->code == "JPY"){ ?>
																<td><span><b><? echo $cart_grandtotal; ?></td><td><font color="#ff0000"><? echo $currency->sign; ?><? echo round($grand_total); ?></font></b></span>
															<?	} else { ?>
																<td><span><b><? echo $cart_grandtotal; ?></td><td><font color="#ff0000"><? echo $currency->sign; ?><? echo dollar($grand_total); ?></font></b></span>
															<? } ?>
															</td>
														</table>
													</td>
												</tr>
												<? } ?>
												<?
													if($cart_rows == 0){
														echo $cart_empty;
													}
														
												?>
												<tr>
													<td height="15">&nbsp;</td>
												</tr>
												<? if($cart_rows > 0){ ?>
												<tr>
													<td height="15" align="center"><? echo $cart_coupon_box; ?></td>
												</tr>
												<tr>
													<td height="15" align="center">
													<form style="margin: 0px; padding: 0px;" name="coupon" method="post" action="public_actions.php?pmode=coupon">
													<input type="text" name="code" maxlength="300"><input type="submit" value="Enter">
													</form>
													</tr>
												<?PHP
													$coupon_display_result = mysql_query("SELECT expire,used,quantity,article FROM coupon where display = '1' order by id desc", $db);
													$coupon_dis_numrows = mysql_num_rows($coupon_display_result);
													if($coupon_dis_numrows > 0){
													while($coupon_display = mysql_fetch_object($coupon_display_result)){
														if($coupon_display->expire != ""){
															$expire = strtotime($coupon_display->expire);
															$expire = date("Ymd", $expire);
														} else {
															$expire = "";
														}
															$today = date("Ymd");	

														if($expire != ""){
														if($expire > $today or $coupon_display->used < $coupon_display->quantity){
												?>
												<tr>
													<td height="15" align="center"><? echo $coupon_display->article; ?></td>
												</tr>
												<?PHP
															}
														} else {
													if($coupon_display->used < $coupon_display->quantity){
															?>
															<tr>
																<td height="15" align="center"><? echo $coupon_display->article; ?></td>
															</tr>
														<?PHP
															}
														}
													}
												}
												?>
												<tr>
													<td height="20" align="center"><? echo $cart_buy; ?></td>
												</tr>
												<tr>
													<td align="center">
													<!-- NOT USED RIGHT NOW -->
													<script>
														function submit_payform(paytype){
															if(paytype == 1){																
																document.payform.action = "public_actions.php?pmode=purchase";
																document.payform.submit();
															}
															if(paytype == 2){																
																document.payform.action = "order.php";
																document.payform.submit();
															}
															if(paytype == 3){																
																document.payform.action = "public_actions.php?pmode=purchase2";
																document.payform.submit();
															}
															if(paytype == 4){																
																document.payform.action = "ppcheckout.php";
																document.payform.submit();
															}														
														}													
													</script>
													<!------------->
													<form name="payform" method="post">
														<input type="hidden" name="coupon" value="<?php echo dollar2($savings); ?>" />
														<input type="hidden" name="shipping" value="<?php echo $shipping; ?>" />
														<input type="hidden" name="tax" value="<? echo round($tax, 2); ?>" />
														<input type="hidden" name="total" value="<? echo $sub_total; ?>" />
														<?php
															# MAKE COUPON INTO SESSION
															if(!empty($_SESSION['ses_coupon'])){
																unset($_SESSION['ses_coupon']);
															}
															session_register("ses_coupon");
															$_SESSION['ses_coupon'] = dollar2($savings);
															
															# MAKE SHIPPING INTO SESSION
															if(!empty($_SESSION['ses_shipping'])){
																unset($_SESSION['ses_shipping']);
															}
															session_register("ses_shipping");
															$_SESSION['ses_shipping'] = $shipping;
															
															# MAKE TAX INTO SESSION
															if(!empty($_SESSION['ses_tax'])){
																unset($_SESSION['ses_tax']);
															}
															session_register("ses_tax");
															$_SESSION['ses_tax'] = round($tax, 2);
															
															# MAKE TOTAL INTO SESSION
															if(!empty($_SESSION['ses_total'])){
																unset($_SESSION['ses_total']);
															}
															session_register("ses_total");
															$_SESSION['ses_total'] = $sub_total;
														?>
													<table>
														<tr><td>
												<? if($setting->force_members == 1 && $_SESSION['sub_member'] == ""){ ?>
												<p align="center"><? echo $cart_checkout; ?><br><a href="login.php?from=cart"><img src="images/login.gif" border="0" alt="<?PHP echo $cart_alt_login; ?>"></a> <a href="subscribe.php?from=cart&message=cart&t=f"><img src="images/register.gif" border="0" alt="<?PHP echo $cart_alt_login; ?>"></a></p>
									          	<? } else { ?>
									          	<?
									          	if($sub_total >= $setting->cart_price){
									          	?>
									          	<p align="center">
									          	<?PHP if($setting->tos_check == 1){ ?>
									          	<?PHP echo $checkout_tos_title; ?>
									          	<a href = "javascript:NewWindow('checkout_terms.php','Terms','500','500','0','1');"><?PHP echo $checkout_tos_agree; ?></a>
									         		<?PHP } ?>
															<?PHP
									          	// Added for PS311 for allowing free order if coupon exist and it is for complete value of order
									          	// Create a session so the store knows to mark it as a free order for the payment method
									          	if($sub_total == "0" && $_SESSION['coupon_id'] > 0){
									          	session_register("ses_free_order");
															$_SESSION['ses_free_order'] = "1";
															?>
									          	<a href="order.php"><img src="images/free_order.gif" border="0"></a>
									          	<? } else { ?>
															<? if($setting->use_paypal == 1){ ?><a href="public_actions.php?pmode=purchase"><img src="images/paypal.gif" border="0"></a><? } ?>
															<? if($setting->use_money == 1){ ?><a href="order.php"><img src="images/money.gif" border="0"></a><? } ?>
															<? if($setting->use_authorize_net == 1){ ?><a href="public_actions.php?pmode=purchase3"><img src="images/authorize_net.gif" width="150" height="52" border="0"></a><? } ?>
															<? if($setting->use_2checkout == 1){ ?><a href="public_actions.php?pmode=purchase2"><img src="images/2checkout.gif" border="0"></a><? } ?>
															<? if($setting->pnpstatus == 1){ ?><a href="ppcheckout.php"><img src="images/pnp.gif" border="0"></a><? } ?>
                              <? if($setting->mygatesupport == 1){ ?><a href="public_actions.php?pmode=mygate"><img src="images/mygate.gif" border="0"></a><? } ?>
												<?PHP 
														}
														echo "</p>";
													} else {
													echo $cart_minimum_price1;
													echo $currency->sign;
													echo dollar($setting->cart_price);
													echo $cart_minimum_price2;
													}
												} 
															
												?>
														</td></tr>
													</table>
													</form>
												</tr>
												<tr>
													<td height="15">&nbsp;</td>
												</tr>
												<tr>
													<td align="center"><? echo $cart_bottomline; ?></td>
												</tr>
												<? } ?>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>				
				</td>
			</tr>
			</div> <!-- end class right-main -->
      </div><!-- end main id-->
      </div> <!-- end container class -->
      </div>
      <? include("footer.php"); ?>
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	
