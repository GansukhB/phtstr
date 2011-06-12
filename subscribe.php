<?
	session_start();

	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );
	
  if($_SESSION['mem_name'])
  {
    header("Location: login.php");
  }
	$currency_result = mysql_query("SELECT * FROM currency where active = '1'", $db);
	$currency = mysql_fetch_object($currency_result);
		
	// VISITOR ID
	if(!$_SESSION['visitor_id']){
		session_register("visitor_id");
		$_SESSION['visitor_id'] = random_gen(16,"");
	}
       if($_GET['t'] == "f"){
       		$cycle_time = "f";
       	} else {
	if(!$_GET['t']){
		$cycle_time = "m";
	} else {
		$cycle_time = $_GET['t'];
	}
}

//CHECK TO SEE IF THE USER IS SIGNING UP TO CHECKOUT
	if($from == "cart"){
		session_register("cart_from");
		$_SESSION['cart_from'] = "cart";
	}

//UNSET ANY IMAGE VIEWING
if($message != "galfree" && $message != "galmonthly" && $message != "yearly"){
	unset($_SESSION['pub_gid']);
	unset($_SESSION['pub_pid']);
}
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
				if(document.signup.country.value == "") {
					alert("Please enter your <? echo $form_country; ?>.");
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
				document.signup.action = "public_actions.php?pmode=sub_signup";
				document.signup.submit();
			}
		
		</script>
    <?php echo $script1 ?>
				<? print($head); ?>
        
<?php include("head_navbar.php"); ?>
<div class="container">
			<? include("header.php"); ?>
				<? //include("search_bar.php"); ?>
      <div id="main">
			<? include("i_gallery_nav.php"); ?>
      <div class="right-main">
        
            	<div class="login-main">
                	<div class="login-left">
                    	<h3 align="center">Шинээр бүртгүүлэх</h3>
                      
                      
                      <tr>
										  <td class="gallery_copy">
										  <? if($message != "cart"){
                         copy_area(20,2);
                         } ?>
                        </td>
                      </tr>
                      <tr>	
                        <td height="10">&nbsp;</td>
                      </tr>
                      <? if($message == "cart"){ ?>
                      <tr>
                        <td class="gallery_copy">
                          <? echo $subscribe_cartsignup; ?>
                        </td>
                      </tr>
                      <? } ?>
                    <?PHP if($error == "null"){ ?>
                    <tr>
                      <td class="gallery_copy">
                          <? echo $subscribe_null_error; ?>
                        </td>
                      </tr>
                    <?PHP } ?>
                    <?PHP if($message == "galfree"){ ?>
                    <tr>
                      <td class="gallery_copy">
                          <? echo $subscribe_galfree_required; ?>
                        </td>
                      </tr>
                    <?PHP } ?>
                    <?PHP if($message == "galmonthly"){ ?>
                    <tr>
                      <td class="gallery_copy">
                          <? echo $subscribe_galmonthly_required; ?>
                        </td>
                      </tr>
                    <?PHP } ?>
                    <?PHP if($message == "galyearly"){ ?>
                    <tr>
                      <td class="gallery_copy">
                          <? echo $subscribe_galyearly_required; ?>
                        </td>
                      </tr>
                    <?PHP } ?>
                        <form method="post" name="signup">
                            <label>
                            	<div class="lb"><div class="title"><? echo $form_name; ?></div>
                              <input type="text" class="text1" name="name" <?PHP if($_SESSION['form_name1']){ ?>value="<?PHP echo $_SESSION['form_name1']; ?>"<?PHP } else { ?>value="<?PHP echo $_GET['name']; ?>" <?PHP } ?>>
													    <? if($_GET['error'] == "email_exists"){ ?>
                              <tr>
                                <td><font color="#ff0000"><?PHP echo $subscribe_bad_email; ?></td>
                              </tr>
                              <? } ?>
                              </div>
                            </label>
                            
                            
                            <label>
                            	<div class="lb"><div class="title"><? echo $form_email; ?></div>
                              <input type="email" class="text1" name="email" <?PHP if($_SESSION['form_email1']){ ?>value="<?PHP echo $_SESSION['form_email1']; ?>"<?PHP } ?> />
													
                              </div>
                            </label>
                            
                            <label>
                            	<div class="lb"><div class="title"><? echo $form_phone; ?></div>
                              <input type="text" class="text1" name="phone" <?PHP if($_SESSION['form_phone1']){ ?>value="<?PHP echo $_SESSION['form_phone1']; ?>"<?PHP } ?> >
													
                              </div>
                            </label>
                            
                            <label>
                            	<div class="lb"><div class="title"><? echo $form_address1; ?></div>
                              <input type="text" class="text1" name="address1" <?PHP if($_SESSION['form_address11']){ ?>value="<?PHP echo $_SESSION['form_address11']; ?>"<?PHP } ?> >
													
                              </div>
                            </label>
                            <label>
                            	<div class="lb"><div class="title"><? echo $form_address2; ?></div>
                              <input type="text" class="text1" name="address2" <?PHP if($_SESSION['form_address21']){ ?>value="<?PHP echo $_SESSION['form_address21']; ?>"<?PHP } ?> >
													
                              </div>
                            </label>
                            
                            <label>
                            	<div class="lb"><div class="title"><? echo $form_city; ?></div>
                              <input type="text" class="text1" name="city" <?PHP if($_SESSION['form_city1']){ ?>value="<?PHP echo $_SESSION['form_city1']; ?>"<?PHP } ?> >
													
                              </div>
                            </label>
												
                        
                        
                            <label>
                            	<div class="lb"><div class="title"><? echo $form_zip; ?></div>
                              <input type="text" class="text1" name="zip" <?PHP if($_SESSION['form_zip1']){ ?>value="<?PHP echo $_SESSION['form_zip1']; ?>"<?PHP } ?> >
													
                              </div>
                            </label>
                            
                            <label>
                            	<div class="lb"><div class="title"><? echo $form_State; ?> </div>
                              <input type="text" class="text1" name="state" <?PHP if($_SESSION['form_state1']){ ?>value="<?PHP echo $_SESSION['form_state1']; ?>"<?PHP } ?> >
													
                              </div>
                            </label>
                            
                            <label>
                            	<div class="lb">
                                	<div class="title"><? echo $form_country; ?></div>
                                	<? include("country.php"); ?>
                                </div>
                            </label>
                            
                            
                            <label class="label1">
                            	<div class="lb"><div class="title"><? echo $form_pass1; ?></div>
                              <input type="password" class="text1" name="password" >
                              </div>
                            </label>
                            
                            <label>
                            	<div class="lb"><div class="title"><? echo $form_pass2; ?></div>
                            
                              <input type="password" class="text1" name="password2" >
                              </div>
                            </label>
                            
                            
                            
                        <? if($message != "cart"){ ?>
												  <? if($setting->allow_subs == 1 or $setting->allow_subs_month == 1){ ?>
												  <? if($cycle_time == "f"){ ?>
												  <tr>
													  <td><b><? echo $form_payment; ?>:</b><br>
													  <? if($setting->use_paypal == 1 && $setting->sub_paypal == 1){ ?><input type="radio" name="p_method" value="paypal"> PayPal (Mastercard, Visa, Discover)<? } ?><? if($setting->use_2checkout == 1 && $setting->sub_2co == 1){ ?><br><input type="radio" name="p_method" value="2checkout"> 2Checkout (Mastercard, Visa, Discover)<? } ?><? if($setting->pnpstatus == 1 && $setting->sub_pnp == 1){ ?><br><input type="radio" name="p_method" value="plugnpay"> Plug N Pay (Mastercard, Visa, Discover)<? } ?><? if($setting->use_authorize_net == 1 && $setting->sub_auth == 3){ ?><br><input type="radio" name="p_method" value="authorize"> Authorize.net (Mastercard, Visa, Discover)<? } ?><? if($setting->mygatesupport == 1 && $setting->sub_mygate == 1){ ?><br><input type="radio" name="p_method" value="mygate"> MyGate (Mastercard, Visa, Discover)<? } ?><? if($setting->use_money == 1 && $setting->sub_cmo == 1){ ?><br><input type="radio" name="p_method" value="checkmoney"> Check or Money Order<? } ?></td>
												  </tr>
												      <? } else { ?>
                                <tr>
                                  <td><b><? echo $form_payment; ?>:</b><br>
                                  <? if($setting->use_paypal == 1 && $setting->sub_paypal == 1){ ?><input type="radio" name="p_method" value="paypal" > PayPal (Mastercard, Visa, Discover)<? } ?><? if($setting->use_2checkout == 1 && $setting->sub_2co == 1){ ?><br><input type="radio" name="p_method" value="2checkout"> 2Checkout (Mastercard, Visa, Discover)<? } ?><? if($setting->pnpstatus == 1 && $setting->sub_pnp == 1){ ?><br><input type="radio" name="p_method" value="plugnpay"> Plug N Pay (Mastercard, Visa, Discover)<? } ?><? if($setting->use_authorize_net == 1 && $setting->sub_auth == 3){ ?><br><input type="radio" name="p_method" value="authorize"> Authorize.net (Mastercard, Visa, Discover)<? } ?><? if($setting->mygatesupport == 1 && $setting->sub_mygate == 1){ ?><br><input type="radio" name="p_method" value="mygate"> MyGate (Mastercard, Visa, Discover)<? } ?><? if($setting->use_money == 1 && $setting->sub_cmo == 1){ ?><br><input type="radio" name="p_method" value="checkmoney"> Check or Money Order<? } ?></td>
                                </tr>
                                <? } } }?>
												<br />
												<?
													if(!$_GET['t']){
														$cycle_time = "m";
													} else {
														$cycle_time = $_GET['t'];
													}
													
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
													<td>
												  <? if($message != "cart"){ ?>
													<b><? echo $form_billing; ?>:</b><br>
													<? if($setting->allow_subs_month == 1 && $message != "cart"){ ?>
														<input type="radio" name="cycle_time" value="month" <?php if($cycle_time == "m"){ echo "checked"; } ?>> <? echo $currency->sign; ?><? echo $setting->sub_price_month; ?><? if($tax_amount_month > 0){ ?> + Tax: (<? echo $tax_amount_month; ?>)<? } ?><?PHP echo $subscribe_one_month; ?><br>
													<? } ?>
													<? if($setting->allow_subs == 1 && $message != "cart"){ ?>
														<input type="radio" name="cycle_time" value="year" <?php if($cycle_time == "y"){ echo "checked"; } ?>> <? echo $currency->sign; ?><? echo $setting->sub_price; ?><? if($tax_amount_year > 0){ ?> + Tax: (<? echo $tax_amount_year; ?>)<? } ?><?PHP echo $subscribe_one_year; ?>
													<? } ?>
													<? if($setting->allow_sub_free == 1){ ?>
													<br>													
														<input type="radio" name="cycle_time" value="free" <?php if($cycle_time == "f"){ echo "checked"; } ?>><?PHP echo $subscribe_free_account; ?>
													<? } }?>
													</td>
												</tr>
                        
                            <label class="label1">
                            	<div align="center" onClick="js_signup();"><button><?PHP echo $subscribe_form_button; ?></button></div>
                            </label>
                            
                            
                        </form>
                    </div>
                    
                    
                    
                    
                    
                    
                    <div class="login-right">
                    	<h3 align="center"><? echo $login_member_login; ?></h3>
                        <script>
                            function clearText(field){
                                if (field.defaultValue == field.value) field.value = '';
                                else if (field.value == '') field.value = field.defaultValue;
                            }

                          </script>
                        <form action="public_actions.php?pmode=login" name="loginform" method="post">
                                <label> 
                                  <div style="margin-left: 10px;">
                                    <? if($_GET['message'] == "login_failed"){ ?>
                                      <? echo $login_login_failed; ?>
                                    <? } ?>
                                    <? if($_GET['message'] == "pending"){ ?>
                                      <? echo $login_pending; ?>
                                    <? } ?>
                                    <? if($_GET['message'] == "logged_out"){ ?>
                                      <? echo $login_logged_out; ?>
                                    <? } ?>                
                                </label>
                                <label>
                                  <div class="lb" valign="middle">
                                    <div class="title"><?php echo $form_email; ?></div>
                                    <input class="text1" type="text" name="email" value="<?php echo $form_email; ?>" onFocus="clearText(this)" onBlur="clearText(this)">
                                  </div>
                                </label>
                                
                                <label>
                                  <div class="lb">
                                    <div class="title"><?php echo $form_pass3; ?></div>
                                    <input class="text1" type="password" name="password" value="password" onFocus="clearText(this)" onBlur="clearText(this)">
                                  </div>
                                </label>
                              
                               <!--<input type="submit" value="<?PHP echo $login_form_submit_button; ?>"> 
                               <input class="button" src="images/button2.jpg" type="image">-->
                               <div align="center"><button onclick="document.loginform.submit();">Нэвтрэх</button></div>
                          </form>
                    </div>
                </div>
            
  <!-- 
        
						<tr>
							<td colspan="3" valign="top" height="100%" class="homepage_line">
								<table width="100%" border="0">
									
									<tr>
										<td class="gallery_copy">
										 <? if($message != "cart"){
										 copy_area(20,2);
										 } ?>
										</td>
									</tr>
									<tr>	
										<td height="10">&nbsp;</td>
									</tr>
									<? if($message == "cart"){ ?>
									<tr>
								    <td class="gallery_copy">
								      <? echo $subscribe_cartsignup; ?>
								    </td>
								  </tr>
									<? } ?>
								<?PHP if($error == "null"){ ?>
								<tr>
									<td class="gallery_copy">
								      <? echo $subscribe_null_error; ?>
								    </td>
								  </tr>
								<?PHP } ?>
								<?PHP if($message == "galfree"){ ?>
								<tr>
									<td class="gallery_copy">
								      <? echo $subscribe_galfree_required; ?>
								    </td>
								  </tr>
								<?PHP } ?>
								<?PHP if($message == "galmonthly"){ ?>
								<tr>
									<td class="gallery_copy">
								      <? echo $subscribe_galmonthly_required; ?>
								    </td>
								  </tr>
								<?PHP } ?>
								<?PHP if($message == "galyearly"){ ?>
								<tr>
									<td class="gallery_copy">
								      <? echo $subscribe_galyearly_required; ?>
								    </td>
								  </tr>
								<?PHP } ?>
                
                
									<tr>
										<td style="padding-left: 10px;">
											<table>
												<form method="post" name="signup">
												<tr>
													<td><b><? echo $form_name; ?>:</b><br>
													<input type="text" name="name" <?PHP if($_SESSION['form_name1']){ ?>value="<?PHP echo $_SESSION['form_name1']; ?>"<?PHP } else { ?>value="<?PHP echo $_GET['name']; ?>" <?PHP } ?> style="width: 250px">
													</td>
												</tr>
												<? if($_GET['error'] == "email_exists"){ ?>
												<tr>
													<td><font color="#ff0000"><?PHP echo $subscribe_bad_email; ?></td>
												</tr>
												<? } ?>
												<tr>
													<td><b><? echo $form_email; ?>:</b><br>
													<input type="text" name="email" <?PHP if($_SESSION['form_email1']){ ?>value="<?PHP echo $_SESSION['form_email1']; ?>"<?PHP } ?> style="width: 250px">
													</td>
												</tr>
												<tr>
													<td><b><? echo $form_phone; ?>:</b><br>
													<input type="text" name="phone" <?PHP if($_SESSION['form_phone1']){ ?>value="<?PHP echo $_SESSION['form_phone1']; ?>"<?PHP } ?> style="width: 250px">
													</td>
												</tr>
												<tr>
													<td><b><? echo $form_address1; ?>:</b><br>
													<input type="text" name="address1" <?PHP if($_SESSION['form_address11']){ ?>value="<?PHP echo $_SESSION['form_address11']; ?>"<?PHP } ?> style="width: 250px">
													</td>
												</tr>
												<tr>
													<td><b><? echo $form_address2; ?>:</b><br>
													<input type="text" name="address2" <?PHP if($_SESSION['form_address21']){ ?>value="<?PHP echo $_SESSION['form_address21']; ?>"<?PHP } ?> style="width: 250px">
													</td>
												</tr>
												<tr>
													<td><b><? echo $form_city; ?>:</b><br>
													<input type="text" name="city" <?PHP if($_SESSION['form_city1']){ ?>value="<?PHP echo $_SESSION['form_city1']; ?>"<?PHP } ?> style="width: 250px">
													</td>
												</tr>
												<tr>
													<td><b><? echo $form_State; ?>:</b><br>
													<input type="text" name="state" <?PHP if($_SESSION['form_state1']){ ?>value="<?PHP echo $_SESSION['form_state1']; ?>"<?PHP } ?> style="width: 250px">
													</td>
												</tr>
												<tr>
													<td><b><? echo $form_zip; ?>:</b><br>
													<input type="text" name="zip" <?PHP if($_SESSION['form_zip1']){ ?>value="<?PHP echo $_SESSION['form_zip1']; ?>"<?PHP } ?> style="width: 250px">
													</td>
												</tr>
												<tr>
													<td><b><? echo $form_country; ?>:</b><br>
														<? include("country.php"); ?>
													</td>
												</tr>
												<tr>
													<td><b><? echo $form_pass1; ?>:</b><br>
													<input type="password" name="password" style="width: 250px">
												</tr>
												<tr>
													<td><b><? echo $form_pass2; ?>:</b><br>
													<input type="password" name="password2" style="width: 250px">
												</tr>
											  <? if($message != "cart"){ ?>
												<? if($setting->allow_subs == 1 or $setting->allow_subs_month == 1){ ?>
												<? if($cycle_time == "f"){ ?>
												<tr>
													<td><b><? echo $form_payment; ?>:</b><br>
													<? if($setting->use_paypal == 1 && $setting->sub_paypal == 1){ ?><input type="radio" name="p_method" value="paypal"> PayPal (Mastercard, Visa, Discover)<? } ?><? if($setting->use_2checkout == 1 && $setting->sub_2co == 1){ ?><br><input type="radio" name="p_method" value="2checkout"> 2Checkout (Mastercard, Visa, Discover)<? } ?><? if($setting->pnpstatus == 1 && $setting->sub_pnp == 1){ ?><br><input type="radio" name="p_method" value="plugnpay"> Plug N Pay (Mastercard, Visa, Discover)<? } ?><? if($setting->use_authorize_net == 1 && $setting->sub_auth == 3){ ?><br><input type="radio" name="p_method" value="authorize"> Authorize.net (Mastercard, Visa, Discover)<? } ?><? if($setting->mygatesupport == 1 && $setting->sub_mygate == 1){ ?><br><input type="radio" name="p_method" value="mygate"> MyGate (Mastercard, Visa, Discover)<? } ?><? if($setting->use_money == 1 && $setting->sub_cmo == 1){ ?><br><input type="radio" name="p_method" value="checkmoney"> Check or Money Order<? } ?></td>
												</tr>
												<? } else { ?>
												<tr>
													<td><b><? echo $form_payment; ?>:</b><br>
													<? if($setting->use_paypal == 1 && $setting->sub_paypal == 1){ ?><input type="radio" name="p_method" value="paypal" checked> PayPal (Mastercard, Visa, Discover)<? } ?><? if($setting->use_2checkout == 1 && $setting->sub_2co == 1){ ?><br><input type="radio" name="p_method" value="2checkout"> 2Checkout (Mastercard, Visa, Discover)<? } ?><? if($setting->pnpstatus == 1 && $setting->sub_pnp == 1){ ?><br><input type="radio" name="p_method" value="plugnpay"> Plug N Pay (Mastercard, Visa, Discover)<? } ?><? if($setting->use_authorize_net == 1 && $setting->sub_auth == 3){ ?><br><input type="radio" name="p_method" value="authorize"> Authorize.net (Mastercard, Visa, Discover)<? } ?><? if($setting->mygatesupport == 1 && $setting->sub_mygate == 1){ ?><br><input type="radio" name="p_method" value="mygate"> MyGate (Mastercard, Visa, Discover)<? } ?><? if($setting->use_money == 1 && $setting->sub_cmo == 1){ ?><br><input type="radio" name="p_method" value="checkmoney"> Check or Money Order<? } ?></td>
												</tr>
												<? } } }?>
												<tr>
													<td height="15"></td>
												</tr>
												<?
													if(!$_GET['t']){
														$cycle_time = "m";
													} else {
														$cycle_time = $_GET['t'];
													}
													
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
													<td>
												  <? if($message != "cart"){ ?>
													<b><? echo $form_billing; ?>:</b><br>
													<? if($setting->allow_subs_month == 1 && $message != "cart"){ ?>
														<input type="radio" name="cycle_time" value="month" <?php if($cycle_time == "m"){ echo "checked"; } ?>> <? echo $currency->sign; ?><? echo $setting->sub_price_month; ?><? if($tax_amount_month > 0){ ?> + Tax: (<? echo $tax_amount_month; ?>)<? } ?><?PHP echo $subscribe_one_month; ?><br>
													<? } ?>
													<? if($setting->allow_subs == 1 && $message != "cart"){ ?>
														<input type="radio" name="cycle_time" value="year" <?php if($cycle_time == "y"){ echo "checked"; } ?>> <? echo $currency->sign; ?><? echo $setting->sub_price; ?><? if($tax_amount_year > 0){ ?> + Tax: (<? echo $tax_amount_year; ?>)<? } ?><?PHP echo $subscribe_one_year; ?>
													<? } ?>
													<? if($setting->allow_sub_free == 1){ ?>
													<br>													
														<input type="radio" name="cycle_time" value="free" <?php if($cycle_time == "f"){ echo "checked"; } ?>><?PHP echo $subscribe_free_account; ?>
													<? } }?>
													</td>
												</tr>
												<tr>
													<td align="center"><input type="button" value="<?PHP echo $subscribe_form_button; ?>" onClick="js_signup();"></td>
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
      -->
      
			</div> <!-- end class right main -->
      </div> <!-- end id main -->
      <?php include('i_banner.php'); ?>
    </div> <!-- end container --></div>
    <? include("footer.php"); ?>	
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	
