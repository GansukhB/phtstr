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
	
	if($_SESSION['id']){
		$id = $_SESSION['id'];
	} else {
		$id = $_SESSION['sub_member'];
	}
	
	$member_result = mysql_query("SELECT * FROM members where id = '$id'", $db);
	$member = mysql_fetch_object($member_result);
		
	// VISITOR ID
	if(!$_SESSION['visitor_id']){
		session_register("visitor_id");
		$_SESSION['visitor_id'] = random_gen(16,"");
	}
	
	//UNSET ANY IMAGE VIEWING
	unset($_SESSION['pub_gid']);
	unset($_SESSION['pub_pid']);
	
?>
<html>
	<head>
		<script language="javascript">
			function js_signup() {
				if(document.signup.name.value == "") {
					alert("Please enter your <? echo $form_name; ?>.");
					return false;
				}
				if(document.signup.email.value == "") {
					alert("Please enter your <? echo $form_email; ?>.");
					return false;
				}
				if(document.signup.address1.value == "") {
					alert("Please enter your <? echo $form_address1; ?>.");
					return false;
				}
				if(document.signup.city.value == "") {
					alert("Please enter your <? echo $form_city; ?>.");
					return false;
				}
				if(document.signup.state.value == "") {
					alert("Please enter your <? echo $form_State; ?>.");
					return false;
				}
				if(document.signup.zip.value == "") {
					alert("Please enter your <? echo $form_zip; ?>.");
					return false;
				}
				if(document.signup.password.value == "") {
					alert("Please enter your password.");
					return false;
				}
				if(document.signup.password2.value == "") {
					alert("Please verify your password.");
					return false;
				}
				if(document.signup.password.value != document.signup.password2.value) {
					alert("The password and verify password fields do not match");
					return false;
				}
				document.signup.action = "public_actions.php?pmode=sub_signup_full";
				document.signup.submit();
			}
		
		</script>
				<? print($head); ?>
   <?php echo $script1; ?>
				<? print($head); ?>
        
        <? include("header.php"); ?>
      <div class="container">
			
			
      <div id="main">
				
				
				<? //include("search_bar.php"); ?>
			
				<? include("i_gallery_nav.php"); ?>
        
        <div class="right-main">				
						<tr>
							<td colspan="3" valign="top" height="100%" class="homepage_line">
								<table width="100%" border="0">
									<tr>
										<td height="6"></td>
									</tr>
									<tr>
										<td class="gallery_copy">
										<?
										if($_GET['message'] != "free" && $member->sub_length != "F"){
										?>
											<?PHP echo $renew_full_bad_account; ?>
										<?
										} else {
										?>
											<?PHP echo $renew_full_upgrade; ?>
										<?
										}
										?>									
										</td>
									</tr>
									<tr>	
										<td height="10">&nbsp;</td>
									</tr>
									<tr>
										<td style="padding-left: 10px;">
											<table>
												<form method="post" name="signup">
												<tr>
													<td><b><? echo $form_name; ?>:</b><br>
													<input type="text" name="name" value="<? echo $member->name; ?>" style="width: 250px">
													</td>
												</tr>
												<? if($_GET['error'] == "email_exists"){ ?>
												<tr>
													<td><font color="#ff0000"><?PHP echo $renew_full_email_exist; ?></td>
												</tr>
												<? } ?>
												<tr>
													<td><b><? echo $form_email; ?>:</b><br>
													<input type="text" name="email" value="<? echo $member->email ?>" style="width: 250px">
													</td>
												</tr>
													<tr>
													<td><b><? echo $form_phone; ?>:</b><br>
													<input type="text" name="phone" value="<? echo $member->phone ?>" style="width: 250px">
													</td>
												</tr>
												<tr>
													<td><b><? echo $form_address1; ?>:</b><br>
													<input type="text" name="address1" value="<? echo $member->address1 ?>" style="width: 250px">
													</td>
												</tr>
												<tr>
													<td><b><? echo $form_address2; ?>:</b><br>
													<input type="text" name="address2" value="<? echo $member->address2 ?>" style="width: 250px">
													</td>
												</tr>
												<tr>
													<td><b><? echo $form_city; ?>:</b><br>
													<input type="text" name="city" value="<? echo $member->city ?>" style="width: 250px">
													</td>
												</tr>
												<tr>
													<td><b><? echo $form_State; ?>:</b><br>
													<input type="text" name="state" value="<? echo $member->state ?>" style="width: 250px">
													</td>
												</tr>
												<tr>
													<td><b><? echo $form_zip; ?>:</b><br>
													<input type="text" name="zip" value="<? echo $member->zip ?>" style="width: 250px">
													</td>
												<tr>
													<td><b><? echo $form_pass1; ?>:</b><br>
													<input type="password" name="password" value="<? echo $member->password ?>" style="width: 250px">
												</tr>
												<tr>
													<td><b><? echo $form_pass2; ?>:</b><br>
													<input type="password" name="password2" value="<? echo $member->password ?>" style="width: 250px">
												</tr>
												<tr>
													<td><b><? echo $form_payment; ?>:</b><br>
													<? if($setting->use_paypal == 1 && $setting->sub_paypal == 1){ ?><input type="radio" name="p_method" value="paypal" checked> PayPal (Mastercard, Visa, Discover)<? } ?><? if($setting->use_2checkout == 1 && $setting->sub_2co == 1){ ?><br><input type="radio" name="p_method" value="2checkout"> 2Checkout (Mastercard, Visa, Discover)<? } ?><? if($setting->pnpstatus == 1 && $setting->sub_pnp == 1){ ?><br><input type="radio" name="p_method" value="plugnpay"> Plug N Pay (Mastercard, Visa, Discover)<? } ?><? if($setting->use_authorize_net == 1 && $setting->sub_auth == 3){ ?><br><input type="radio" name="p_method" value="authorize"> Authorize.net (Mastercard, Visa, Discover)<? } ?><? if($setting->mygatesupport == 1 && $setting->sub_mygate == 1){ ?><br><input type="radio" name="p_method" value="mygate"> MyGate (Mastercard, Visa, Discover)<? } ?><? if($setting->use_money == 1 && $setting->sub_cmo == 1){ ?><br><input type="radio" name="p_method" value="checkmoney"> Check or Money Order<? } ?></td>
												</tr>
												<tr>
													<td height="15"></td>
												</tr>
												<? 
															$tax1 = $setting->tax1;
															$tax2 = $setting->tax2;
															$year_price = $setting->sub_price;
															$month_price = $setting->sub_price_month;
															
															if($tax1 > 0){
																$year_tax1 = $year_price * ($tax1 / 100);
																$month_tax1 = $month_price * ($tax1 / 100);
															}
															if($tax2 > 0){
																$year_tax2 = $year_price * ($tax2 / 100);
																$month_tax2 = $month_price * ($tax2 / 100);
															}
																$tax_amount_year = dollar2($year_tax1 + $year_tax2);
																$tax_amount_month = dollar2($month_tax1 + $month_tax2);
													?>													
													<input type="hidden" name="tax_amount_year" value="<? echo $tax_amount_year; ?>">
													<input type="hidden" name="tax_amount_month" value="<? echo $tax_amount_month; ?>">
												<tr>
													<td>Billing Cycle:<br>
													<? if($setting->allow_subs_month == 1){ ?>
														<input type="radio" name="cycle_time" value="month" <?php if($member->sub_length == "M"){ echo "checked"; } ?>> <? echo $currency->sign; ?><? echo $setting->sub_price_month; ?><? if($tax_amount_month > 0){ ?> + Tax: (<? echo $tax_amount_month; ?>)<? } ?><?PHP echo $renew_full_one_month; ?><br>
													<? } ?>
													<? if($setting->allow_subs == 1){ ?>
														<input type="radio" name="cycle_time" value="year" <?php if($member->sub_length == "Y"){ echo "checked"; } ?>> <? echo $currency->sign; ?><? echo $setting->sub_price; ?><? if($tax_amount_year > 0){ ?> + Tax: (<? echo $tax_amount_year; ?>)<? } ?><?PHP echo $renew_full_one_year; ?><br>
													<? } ?>
													<? if($setting->allow_sub_free == 1 && $member->sub_length != "F"){ ?>
													<hr width="95%">
													Free:<br>													
														<input type="radio" name="cycle_time" value="free" <?php if($member->sub_length == "F"){ echo "checked"; } ?>><?PHP echo $renew_full_free_info; ?><? if($setting->allow_subs == 1 or $setting->allow_subs_month == 1){ ?><?PHP echo $renew_full_must_buy; ?><? } ?>)
													<? } ?>
													</td>
												</tr>
												<tr>
													<td align="right"><input type="button" value="<?PHP echo $renew_full_form_submit_button; ?>" onClick="js_signup();"></td>
												</tr>
												
												</form>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>				
				</td>
			</tr>
						</div> <!-- end class right main -->
      </div> <!-- end id main -->
    </div> <!-- end container -->
    <?php include('footer.php'); ?>
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	
