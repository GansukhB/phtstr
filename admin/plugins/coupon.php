<?php
	/*
		Manager Settings Plus	4.7.04
			1 manager user login version
			This version includes metatags and other information
	*/
	
		// OPTIONS
			$image_upload     = 20; // number of images that can be uploaded per news item / 0 for no files
			$image_path       = "../uploaded_images/";
			$image_caption    = 1; // 1 on | 0 off / Allow captions on images
			$image_area_name  = "";
			
			$file_upload      = 20; // number of files that can be uploaded per news item / 0 for no files
			$file_path        = "../uploaded_files/";
			$file_area_name   = "";
			$file_active      = 1; // allow files to be set active/inactive
			
			$copy_link_option = 1;  // 1 on | 0 off / Allow image links to be copied
			
			$homepage_option  = 1; // 1 on | 0 off				
			$editor           = 1; // 1 on | 0 off
			$reference        = "coupon"; // used when saving and pulling images or files from/to the database
			$actions_page = "actions_coupon.php";
			
	if($execute_nav == 1){
		$nav_order = 22; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "Coupons"; // name of the nav that will appear on the page
	}
	else{
		$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
		$setting = mysql_fetch_object($settings_result);
		
		$currency_result = mysql_query("SELECT * FROM currency where active = '1'", $db);
		$currency = mysql_fetch_object($currency_result);
		
		$mgr_result = mysql_query("SELECT * FROM mgr_users where id = '1'", $db);
		$mgr_users = mysql_fetch_object($mgr_result);
?>
	<table width="700" cellpadding="0" cellspacing="0" bgcolor="#577EC4" style="border: 1px solid #5B8BD8;">
	
			<script language="javascript">

	var i_status;
	var c_status;
	var o_status;

			function save_coupon_creation() {
			var agree=confirm("Are you sure you want to save these settings?");
			if (agree) {
				<?php											
					$agent = $_SERVER['HTTP_USER_AGENT'];
					if(!eregi("mac", $agent) or $setting->force_mac == 1){
				?>
				//document.forms.data_form.onsubmit();
				
				document.getElementById("article").value=oEdit1.getHTMLBody();
				
				<?php } ?>
				document.forms.coupon_creation.submit();
			}
			else {
				false
			}
		}			
			
		function delete_data() {
		var agree=confirm("Are you sure you would like to delete the selected items?");
		if (agree) {
			document.coupon.action = "actions_coupon.php?pmode=delete";
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
		<form name="coupon_creation" action="<?PHP echo $actions_page; ?>?pmode=save_coupon_settings" method="post" ENCTYPE="multipart/form-data">
		<input type="hidden" value="mgr.php?nav=<? echo $_GET['nav']; ?>&message=saved" name="return">
		<tr>
			<td bgcolor="#3C6ABB" align="center" style="padding: 4px; border-bottom: 1px solid #355894;">
				<table cellpadding="0" cellspacing="0" width="95%">
					<tr>	
						<td width="100%" nowrap><font face="arial" color="#ffffff" style="font-size: 11;"><b>COUPON | SAVINGS CODE</b></font>
						<? if($_GET['message'] == "deleted"){ ?>
						<td align="right" valign="bottom">
							<img src="images/mgr_check2_loop_3.gif" valign="absmiddle">
						</td>
						<td align="right" valign="middle" nowrap>
							<font color="#FFE400" style="font-size: 10;">&nbsp;The selected coupons have been deleted.
						</td>
						<? } ?>
						<? if($_GET['message'] == "saved"){ ?>
						<td align="right" valign="bottom">
							<img src="images/mgr_check2_loop_3.gif" valign="absmiddle">
						</td>
						<td align="right" valign="middle" nowrap>
							<font color="#FFE400" style="font-size: 10;">&nbsp;The coupon has been created.
						</td>
						<? } ?>
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
									<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>ADD NEW COUPON | SAVINGS CODE</b></td>
									<td align="right"><img src="images/mgr_section_header_r.gif"></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;"><b>Coupon | Savings Code types:</b><br>(Check one type and enter the data for that type. Do not check more than one type! Create one at a time.)<br><hr width="90%">
							<b>1. Select a type of coupon you would like to create</b><br>
							<font color="#ffffff" style="font-size: 11;"><input name="type" type="radio" value="2"> <b>Percentage Off</b><br>(Percentage off on total of the cart.)<br>
							<font color="#ffffff" style="font-size: 11;"><input name="type" type="radio" value="3"> <b>Dollar Amount Off</b><br>(Dollar amount off on total of the cart.)<br>
							<font color="#ffffff" style="font-size: 11;"><input name="type" type="radio" value="5"> <b>Tax Exempt</b><br>(Remove tax from overall total in cart.<b> Skip section 2</b>)<br>
							<font color="#ffffff" style="font-size: 11;"><input name="type" type="radio" value="1"> <b>Free Shipping</b><br>(Give free shipping to anyone that types this code in during checkout.<b> Skip section 2</b>)<br>
							<hr width="90%">
							<b>2. Enter Data</b> (depending on type of coupon you selected)<br>
					    <input type="text" name="percent" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">% <b>Percent Amount</b><br>(Example: 10 | This is for the percentage off coupon)<br>
					    <b>--OR--</b><br>
					    <input type="text" name="amount" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">$ <b>Dollar Amount</b><br>(Example: 10 | This is for the dollar amount off coupon)<br>
					   	<hr width="90%">
							<b>3. Enter Expire Data</b> (Can be either a date or amount of times it is used)<br>
							<input type="text" name="expire" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"> <b>Expire Date</b><br>(Example: mm/dd/yyyy | On what day will this coupon expire <b>OR</b> you can enter a quantity below)<br>
					    <b>--OR--</b><br>
					    <input type="text" name="quantity" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"> <b>Quantity</b><br>(Example: 100 | How many do you want to issue <b>OR</b> you can enter a date it will expire above)<br>
					    <hr width="90%">
					    <b>4. Enter Code</b> (This is the code the customer will use to validate a coupon during checkout)<br>(You CAN NOT use these characters in the promo code: /"\|][;:)(*^%$#@<>)<br>
					    <input type="text" name="code" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"> <b>Code</b><br>(Example: 01ag8xe | This is the code used by the customer to activate the coupon)<br><hr width="90%">
					</tr>
					<tr>
						<td bgcolor="#5E85CA" class="data_box">
							<input type="checkbox" name="display" value="1"><font face="arial" color="#ffffff" style="font-size: 11;"><b>Display this coupon on the cart page:</b> (If you check this, the data in the content field below will show on the cart page, try to keep it short and simple.)<br>
						</td>
					</tr>
					<? if($setting->editor == 1 and $editor == 1){ ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box"><b>Content:</b><br><? $sContent = $ca->article; ?>
										<?php											
											$agent = $_SERVER['HTTP_USER_AGENT'];
											if(eregi("mac", $agent) && $setting->force_mac == 0){
										?>
											<textarea name="article" id="article" rows=8 cols=30 style="width: 100%"><?php echo $sContent; ?></textarea>										
										<?php
											} else {
										?>										
										<script language=JavaScript src='./scripts/innovaeditor.js'></script>
										<textarea name="article" id="article" rows=4 cols=30>
										<?
										function encodeHTML($sHTML)
										{
										$sHTML=ereg_replace("&","&amp;",$sHTML);
										$sHTML=ereg_replace("<","&lt;",$sHTML);
										$sHTML=ereg_replace(">","&gt;",$sHTML);
										return $sHTML;
										}
										if(isset($sContent)) echo encodeHTML($sContent);
										?>
										</textarea>
										<script>
										var oEdit1 = new InnovaEditor("oEdit1");
										oEdit1.initialRefresh=true;
										oEdit1.REPLACE("article");
										</script>
										<?php
											}
										?>
									</td>
								</tr>
								<? } else { ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<b>Copy</b><br>
										<textarea name="article" id="text_box" style="width:100%; height:200; border: 1px solid #000000;" rows="1" cols="20"><? echo $ca->article; ?></textarea>
										<p align="right"><a href="#" onclick="window.open('<? echo $setting->help_tips_link; ?>help_tips.php?pmode=html_tags', 'tips_win', ['HEIGHT=500', 'WIDTH=600', 'scrollbars=yes', 'dependent']);" class="edit_links">HTML Tips</a>
									</td>
								</tr>
								<? } ?>
					<tr>
						<td align="right"><? echo "<a href=\"javascript:save_coupon_creation();\">"; ?><img src="images/mgr_button_save.gif" border="0"></a></td>
					</tr>
				</form>
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
					<? 
						$coupon_result = mysql_query("SELECT * FROM coupon order by id desc", $db);
						$coupon_num_row = mysql_num_rows($coupon_result);
					?>
					<tr>
						<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;"><b>Coupons:</b> <? echo $download_num_rows; ?></td>
					</tr>
					<table width="95%" border="1" bordercolordark="#89A6DB" bordercolorlight="#5078BF">
					<tr>
						<form name="coupon" method="post">
						<input type="hidden" value="mgr.php?nav=<? echo $_GET['nav']; ?>&message=deleted&order_by=<? echo $_GET['order_by']; ?>&order_type=<? echo $_GET['order_type']; ?>&search=<? echo $_GET['search']; ?>" name="return">
						<td align="Center" bgcolor="#89A6DB"><b>ID</b></td>
						<td align="center" bgcolor="#89A6DB"><b>Code</b></td>
						<td align="center" bgcolor="#89A6DB"><b>Type</b></td>
						<td align="center" bgcolor="#89A6DB"><b>Amount</b></td>
						<td align="center" bgcolor="#89A6DB"><b>Percentage</b></td>
						<td align="center" bgcolor="#89A6DB"><b>Expire</b></td>
						<td align="center" bgcolor="#89A6DB"><b>Quantity</b></td>
						<td align="center" bgcolor="#89A6DB"><b>Used</b></td>
						<td align="center" bgcolor="#89A6DB"><b>Delete</b></td>
						
					</tr>
					<?
						while($coupon = mysql_fetch_object($coupon_result)){
					?>
					<tr>
						<td align="Center" bgcolor="#89A6DB"><b><? echo $coupon->id; ?></b></td>
						<td align="center" bgcolor="#89A6DB"><b><? echo $coupon->code; ?></b></td>
						<? 
						if($coupon->type == "1"){
							$type_is = "Free Shipping";
						}
						if($coupon->type == "2"){
							$type_is = "Percent Off";
						}
						if($coupon->type == "3"){
							$type_is = "Dollar Off";
						}
						if($coupon->type == "4"){
							$type_is = "Free Items";
						}
						if($coupon->type == "5"){
							$type_is = "Tax Exempt";
						}
						?>
						<td align="center" bgcolor="#89A6DB"><b><? echo $type_is; ?></b></td>
					<td align="center" bgcolor="#89A6DB"><b><? echo $currency->sign; ?><? echo dollar($coupon->amount); ?></b></td>
						<td align="center" bgcolor="#89A6DB"><b><? echo $coupon->percent; ?>%</b></td>
					<td align="center" bgcolor="#89A6DB"><b><? if($coupon->expire != ""){ ?><? echo $coupon->expire; ?><? } else { echo "-----"; } ?></b></td>
						<td align="center" bgcolor="#89A6DB"><b><? echo $coupon->quantity; ?></b></td>
					<td align="center" bgcolor="#89A6DB"><b><? if($coupon->used != ""){ ?><? echo $coupon->used; ?><? } else { echo "0"; } ?></b></td>
						<td align="center" bgcolor="#89A6DB"><input name="<? echo $coupon->id; ?>" type="checkbox" value="1"></td>
					</tr>
					<? } ?>				
					</table>
					<table width="95%">
						<tr>
						<td align="right"><a href="javascript:selectAll(document.coupon,0);"><img src="images/mgr_button_select_all.gif" border="0"></a>&nbsp;<? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:delete_data();\">"; } ?><img src="images/mgr_button_delete_sel.gif" border="0"></a></td>
					</form>
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