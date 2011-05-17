<?php
	/*
		Manager Stats
	*/
	
	if($execute_nav == 1){
		$nav_order = 26; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "Stats"; // name of the nav that will appear on the page
		$actions_stats = "actions_stats.php";
	}
	else{
		$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
		$setting = mysql_fetch_object($settings_result);
		
		$currency_result = mysql_query("SELECT * FROM currency where active = '1'", $db);
		$currency = mysql_fetch_object($currency_result);
		
		$mgr_result = mysql_query("SELECT * FROM mgr_users where id = '1'", $db);
		$mgr_users = mysql_fetch_object($mgr_result);
if(!$_GET['img_x']){
	$img_x = 10;
	}
	$nav = $_GET['nav'];

?>
	<table width="700" cellpadding="0" cellspacing="0" bgcolor="#577EC4" style="border: 1px solid #5B8BD8;">
	
			<script language="javascript">

	var i_status;
	var c_status;
	var o_status;

			function save_coupon_creation() {
			var agree=confirm("Are you sure you want to save these settings?");
			if (agree) {
				document.coupon_creation.action = "mgr_actions.php?pmode=save_coupon_settings";
				document.coupon_creation.submit();
			}
			else {
				false
			}
		}			
			
		function delete_data() {
		var agree=confirm("Are you sure you would like to delete the selected items?");
		if (agree) {
			document.coupon.action = "<? echo $actions_stats; ?>?pmode=delete";
			document.coupon.submit();
		}
		else {
			false
		}
	}
			
		function selectAll(formObj, isInverse) {
		if(c_status != 1){
			for (var i=0;i < formObj.length;i++){
		      fldObj = formObj.elements[i];
		      if (fldObj.type == 'checkbox')
		      {
			      fldObj.checked = true;			      
		      }
	      }
	      c_status = 1;
		}
		else {
			for (var i=0;i < formObj.length;i++){
		      fldObj = formObj.elements[i];
		      if (fldObj.type == 'checkbox')
		      {
			      fldObj.checked = false;
			      
			  }
		    }
		    c_status = 0;
		}  
	}
		</script>
		<tr>
			<td bgcolor="#3C6ABB" align="center" style="padding: 4px; border-bottom: 1px solid #355894;">
				<table cellpadding="0" cellspacing="0" width="95%">
					<tr>	
						<td width="100%" nowrap><font face="arial" color="#ffffff" style="font-size: 11;"><b>STATISTICS</b></font>
						
					</tr>
				</table>
			</td>
		</tr>
		
		<tr>
			<td bgcolor="#577EC4" align="center" style="padding-top: 10px; padding-bottom: 10px;" background="images/mgr_bg_texture.gif">
				<table width="95%" cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td align="left"><img src="images/mgr_section_header_l.gif"></td>
									<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>TOP <? echo $img_x; ?></b></td>
									<td align="right"><img src="images/mgr_section_header_r.gif"></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>									<form name="count_display" method="post">
																<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;"><b>VEIW TOP</b>
																<select name="display" id="display" style="font-size: 9; width:40;" onChange="location=document.count_display.display.options[document.count_display.display.selectedIndex].value;" value="GO">
																<option value="mgr.php?nav=<? echo $nav; ?>&img_x=1" <? if($img_x == 1){ echo "selected"; } ?>>1</option>
																<option value="mgr.php?nav=<? echo $nav; ?>&img_x=3" <? if($img_x == 3){ echo "selected"; } ?>>3</option>
																<option value="mgr.php?nav=<? echo $nav; ?>&img_x=5" <? if($img_x == 5){ echo "selected"; } ?>>5</option>
																<option value="mgr.php?nav=<? echo $nav; ?>&img_x=10" <? if($img_x == 10){ echo "selected"; } ?>>10</option>
																<option value="mgr.php?nav=<? echo $nav; ?>&img_x=15" <? if($img_x == 15){ echo "selected"; } ?>>15</option>
																<option value="mgr.php?nav=<? echo $nav; ?>&img_x=20" <? if($img_x == 20){ echo "selected"; } ?>>20</option>
																<option value="mgr.php?nav=<? echo $nav; ?>&img_x=50" <? if($img_x == 50){ echo "selected"; } ?>>50</option>
															</select> <b>AT A TIME.</b><br /></i></b><hr width="90%">
																<?
																	$photo_result = mysql_query("SELECT * FROM photo_package order by code desc LIMIT $img_x", $db);
																	$photo_num_row = mysql_num_rows($photo_result);
																?>
																	Top <? echo $img_x; ?> Viewed Photos:
														<table width="95%" border="1" bordercolordark="#89A6DB" bordercolorlight="#5078BF">
															<tr>
																<td align="Center" bgcolor="#89A6DB"><b>ID</b></td>																
																<td align="center" bgcolor="#89A6DB"><b>Photo</b></td>
																<td align="center" bgcolor="#89A6DB"><b>Title</b></td>
																<td align="center" bgcolor="#89A6DB"><b>FileName</b></td>
																<td align="center" bgcolor="#89A6DB"><b>Main Category</b></td>
																<td align="center" bgcolor="#89A6DB"><b>Photographer</b></td>											
																<td align="center" bgcolor="#89A6DB"><b>Viewed</b></td>
															</tr>
																<? while($photo = mysql_fetch_object($photo_result)){ 
																	
																	$pho_result = mysql_query("SELECT * FROM uploaded_images where reference_id = '$photo->id' order by original", $db);
																	$pho_num_row = mysql_num_rows($pho_result);
																	$pho = mysql_fetch_object($pho_result);
																	
																	$gal_result = mysql_query("SELECT * FROM photo_galleries where id = '$photo->gallery_id'", $db);
																	$gal_num_row = mysql_num_rows($gal_result);
																	$gal = mysql_fetch_object($gal_result);
																	
																	$photog_result = mysql_query("SELECT * FROM photographers where id = '$photo->photographer'", $db);
																	$photog_num_row = mysql_num_rows($photog_result);
																	$photog = mysql_fetch_object($photog_result);
																	?>
																<tr>
																<td align="Center" bgcolor="#89A6DB"><b><? echo $photo->id; ?></b></td>
																<td align="center" bgcolor="#89A6DB"><a href="<?PHP echo $stock_photo_path_manager; ?><? echo $pho->filename; ?>" target="_blank"><img src="<?PHP echo $stock_photo_path_manager; ?>i_<? echo $pho->filename; ?>"  border="0" width="100"></a></td>
																<td align="Center" bgcolor="#89A6DB"><b><? echo $photo->title; ?></b></td>
														    <td align="Center" bgcolor="#89A6DB"><b><? echo $pho->filename; ?></b></td>
																<td align="Center" bgcolor="#89A6DB"><b><? echo $gal->title; ?></b></td>
																<? if($photog->name == ""){ ?>
																<td align="Center" bgcolor="#89A6DB"><b>---</b></td>
																<? } else { ?>
																<td align="Center" bgcolor="#89A6DB"><b><? echo $photog->name; ?></b></td>
															<? } ?>
																<td align="Center" bgcolor="#89A6DB"><b><? echo $photo->code; ?></b></td>
																</tr>
														<? } ?>
																<br><br>
													</tr>
													</table><br>
													<hr width="90%">
																<?
																	$photo1_result = mysql_query("SELECT * FROM photo_package order by cart_count desc LIMIT $img_x", $db);
																	$photo1_num_row = mysql_num_rows($photo1_result);
																?>
																	Top <? echo $img_x; ?> Added To Cart:
																<table width="95%" border="1" bordercolordark="#89A6DB" bordercolorlight="#5078BF">
															<tr>
																<td align="Center" bgcolor="#89A6DB"><b>ID</b></td>																
																<td align="center" bgcolor="#89A6DB"><b>Photo</b></td>
																<td align="center" bgcolor="#89A6DB"><b>Title</b></td>
																<td align="center" bgcolor="#89A6DB"><b>FileName</b></td>
																<td align="center" bgcolor="#89A6DB"><b>Main Category</b></td>
																<td align="center" bgcolor="#89A6DB"><b>Photographer</b></td>											
																<td align="center" bgcolor="#89A6DB"><b>Cart Count</b></td>
															</tr>
																<? while($photo1 = mysql_fetch_object($photo1_result)){ 
																	
																	$pho1_result = mysql_query("SELECT * FROM uploaded_images where reference_id = '$photo1->id' order by original", $db);
																	$pho1_num_row = mysql_num_rows($pho1_result);
																	$pho1 = mysql_fetch_object($pho1_result);
																	
																	$gal1_result = mysql_query("SELECT * FROM photo_galleries where id = '$photo1->gallery_id'", $db);
																	$gal1_num_row = mysql_num_rows($gal1_result);
																	$gal1 = mysql_fetch_object($gal1_result);
																	
																	$photog1_result = mysql_query("SELECT * FROM photographers where id = '$photo1->photographer'", $db);
																	$photog1_num_row = mysql_num_rows($photog1_result);
																	$photog1 = mysql_fetch_object($photog1_result);
																	?>
																<tr>
																<td align="Center" bgcolor="#89A6DB"><b><? echo $photo1->id; ?></b></td>
																<td align="center" bgcolor="#89A6DB"><a href="<?PHP echo $stock_photo_path_manager; ?><? echo $pho1->filename; ?>" target="_blank"><img src="<?PHP echo $stock_photo_path_manager; ?>i_<? echo $pho1->filename; ?>"  border="0" width="100"></a></td>
																<td align="Center" bgcolor="#89A6DB"><b><? echo $photo1->title; ?></b></td>
														    <td align="Center" bgcolor="#89A6DB"><b><? echo $pho1->filename; ?></b></td>
																<td align="Center" bgcolor="#89A6DB"><b><? echo $gal1->title; ?></b></td>
																<? if($photog1->name == ""){ ?>
																<td align="Center" bgcolor="#89A6DB"><b>---</b></td>
																<? } else { ?>
																<td align="Center" bgcolor="#89A6DB"><b><? echo $photog1->name; ?></b></td>
															<? } ?>
																<td align="Center" bgcolor="#89A6DB"><b><? echo $photo1->cart_count; ?></b></td>
																</tr>
														<? } ?>
																<br><br>
							</form>
					</tr>
				</table>
					<tr><td height="10">&nbsp;</td></tr>
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td align="left"><img src="images/mgr_section_header_l.gif"></td>
									<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>STATISTICS</b></td>
									<td align="right"><img src="images/mgr_section_header_r.gif"></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;"><b>Stats:</b></td>
					</tr>
					<tr>
						<tr>
							<? 
							$member2_result = mysql_query("SELECT * FROM members", $db);
							$member2_rows = mysql_num_rows($member2_result);
	
							$photo2_result = mysql_query("SELECT * FROM photo_package where active = '1'", $db);
							$photo2_rows = mysql_num_rows($photo2_result);
							
							$sales2_result = mysql_query("SELECT * FROM visitors where status = '1'", $db);
							$sales2_rows = mysql_num_rows($sales2_result);
							
							$sales3_result = mysql_query("SELECT * FROM visitors where status = '1'", $db);
							while($sales3 = mysql_fetch_object($sales3_result)){
								$price = $price + $sales3->price;
								$shipping = $shipping + $sales3->shipping;
								$tax = $tax + $sales3->tax;
								$savings = $savings + $sales3->savings;
							}
						
							
							$photo3_result = mysql_query("SELECT * FROM photo_package where active = '1' and photographer > 0", $db);
							$photo3_rows = mysql_num_rows($photo3_result);
							
							$photo4_result = mysql_query("SELECT * FROM photo_package", $db);
							$photo4_rows = mysql_query($photo4_result);
							while($photo4 = mysql_fetch_object($photo4_result)){
								$view = $view + $photo4->code;
							}
							
							$photog_result = mysql_query("SELECT * FROM photographers", $db);
							$photog_rows = mysql_num_rows($photog_result);
							
							$visit_result = mysql_query("SELECT * FROM counter", $db);
							$visit_rows = mysql_num_rows($visit_result);
							
								?>

										<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;" width="150"><b>Total Photos:</b> <? echo $photo2_rows; ?></td>
									</tr>
										<tr>
										<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;" width="150"><b>Total Photographers Photos:</b> <? echo $photo3_rows; ?></td>
									</tr>
									<tr>
										<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;" width="150"><b>Total Photographers:</b> <? echo $photog_rows; ?></td>
									</tr>
									<tr>
										<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;" width="150"><b>Total Members:</b> <?echo $member2_rows; ?></td>
									</tr>
									<tr>
										<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;" width="150"><b>Total Sales Orders:</b> <? echo $sales2_rows; ?></td>
									</tr>
									<tr>
										<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;" width="150"><b>Total Sales Price:</b> <? echo $currency->sign . dollar2($price); ?></td>
									</tr>
									<tr>
										<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;" width="150"><b>Total Views:</b> <? echo $view; ?></td>
									</tr>
									<tr>
										<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;" width="150"><b>Total Visitors:</b> <? echo $visit_rows; ?></td>
									</tr>
										<tr>
										<td colspan="2"><hr width="100%" align="left"></td>
									</tr>
					<table width="95%">
						<tr>
					</tr>
				</table>
				</td>
			</tr>
		
		
		<!--
		<tr>
			<td bgcolor="#5E85CA" style="border-top: 1px solid #6F97DE;border-bottom: 1px solid #476DB0;"><br><br><br><br><br></td>
		</tr>
		<tr>
			<td bgcolor="#577EC4" style="border-bottom: 1px solid #476DB0;border-top: 1px solid #6F97DE;"><br><br><br><br><br></td>
		</tr>
		-->
	</table>
<?
	}
?>