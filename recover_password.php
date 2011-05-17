<?
	session_start();

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
							$crumb = $recover_crumb_link;
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
									<? if(!($_GET['message'])){ ?>
									<tr>
										<td class="gallery_copy">
											<? copy_area(26,2); ?>
										</td>
									</tr>
									<? } ?>
									<tr>
										<td style="padding-left: 10px;">
											<table>
												<form method="post" action="public_actions.php?pmode=recover_password" name="recover_password">
												<? if($_GET['message'] == "no_account"){ ?>
												<tr>
													<td><font color="#ff0000"><?PHP echo $recover_bad_email; ?></td>
												</tr>
												<? } ?>
												<? if($_GET['message'] == "email_sent"){ ?>
												<tr>
													<td><font color="#ff0000"><?PHP echo $recover_good_email; ?></td>
												</tr>
												<? } else { ?>
												<tr>
													<td height="15">&nbsp;</td>
												</td>
												<tr>
													<td><?PHP echo $recover_enter_email; ?><br>
													<input type="text" name="email" style="width: 250px">
													</td>
												</tr>
												<tr>
													<td align="right"><input type="submit" value="<?PHP echo $recover_form_submit_button; ?>"></td>
												</tr>
												<? } ?>
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