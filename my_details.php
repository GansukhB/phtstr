<?
	session_start();

	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );
	
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
				document.signup.action = "public_actions.php?pmode=sub_member_save";
				document.signup.submit();
			}
		
		</script>
				<? print($head); ?>
 <?php include('head_navbar.php'); ?>
	<div class="container">
<?php include('header.php');?>
				<? //include("search_bar.php"); ?>
      <div id="main">
			<? include("i_gallery_nav.php"); ?>
      <div class="right-main">
        
        <!--
										<td class="gallery_copy">
										<?
										echo $my_details_profile_text . "<br>";
										?>
										<? if($_GET['message'] == "saved"){
												echo $my_details_profile_saved;
												}
									  ?>
										</td>
									
											<table>
												<form method="post" name="signup">
													<input type="hidden" name="name" value="<? echo $member->name; ?>">
													<input type="hidden" name="email" value="<? echo $member->email; ?>">
												<tr>
													<td><b><? echo $form_name; ?>:</b><br>
													<? echo $member->name; ?>
													</td>
												</tr>
												<tr>
													<td><b><? echo $form_email; ?>:</b><br>
													<? echo $member->email ?>
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
													<tr>
													<td><b><? echo $form_country; ?>:</b><br>
													<input type="text" name="country_display" value="<? echo $member->country ?>" style="width: 250px"><br>
													<? echo $form_country_change; ?><br>
													<? include("country.php"); ?>
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
													<td height="15"></td>
												</tr>
												<tr>
													<td align="right"><input type="button" value="<?PHP echo $my_details_save_button; ?>" onClick="js_signup();"></td>
												</tr>												
												</form>
                      </table>
          -->
          <div class="login-main">
                	<div class="login-left">
                    	<h3 align="center">Шинээр бүртгүүлэх</h3>
                      
                      
                      
										  
										                          
                      
                      	
                        &nbsp;
                      
                                                                                                                              <form name="signup" method="post">
                            <label>
                            	<div class="lb"><div class="title">Нэр</div>
                              <input type="text" value="" name="name" class="text1">
													                                  </div>
                            </label>
                            
                            
                            <label>
                            	<div class="lb"><div class="title">Имэйл</div>
                              <input type="email" name="email" class="text1">
													
                              </div>
                            </label>
                            
                            <label>
                            	<div class="lb"><div class="title">Утас</div>
                              <input type="text" name="phone" class="text1">
													
                              </div>
                            </label>
                            
                            <label>
                            	<div class="lb"><div class="title">Хаяг 1</div>
                              <input type="text" name="address1" class="text1">
													
                              </div>
                            </label>
                            <label>
                            	<div class="lb"><div class="title">Хаяг 2</div>
                              <input type="text" name="address2" class="text1">
													
                              </div>
                            </label>
                            
                            <label>
                            	<div class="lb"><div class="title">Хот</div>
                              <input type="text" name="city" class="text1">
													
                              </div>
                            </label>
												
                        
                        
                            <label>
                            	<div class="lb"><div class="title">Zip код</div>
                              <input type="text" name="zip" class="text1">
													
                              </div>
                            </label>
                            
                            <label>
                            	<div class="lb"><div class="title">Бүс </div>
                              <input type="text" name="state" class="text1">
													
                              </div>
                            </label>
                            
                            <label>
                            	<div class="lb">
                                	<div class="title">Улс</div>
                                	
                                </div>
                            </label>
                            
                            
                            <label class="label1">
                            	<div class="lb"><div class="title">Нууц үг</div>
                              <input type="password" name="password" class="text1">
                              </div>
                            </label>
                            
                            <label>
                            	<div class="lb"><div class="title">Нууц үгийг давтан оруул</div>
                            
                              <input type="password" name="password2" class="text1">
                              </div>
                            </label>
                            

                            <label class="label1">
                            	<div align="center" onclick="js_signup();"><button>Signup</button></div>
                            </label>
                            
                            
                        </form>
                    </div>
                    
                    
                    
			</div>
        </div> <!-- end class right main -->
      </div> <!-- end id main -->
    </div> <!-- end container -->
    <? include("footer.php"); ?>	
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	
