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
	
	//CHECK TO SEE IF THE USER IS LOGGING IN TO CHECKOUT
	if($from == "cart"){
			session_register("cart_from");
		$_SESSION['cart_from'] = "cart";
	}
	
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
						<?php
							$crumb = $login_member_login;
							include("crumbs.php");
						?>
						<tr>
							<td class="index_copy_area" colspan="3" height="4"></td>
						</tr>						
						<tr>
							<td colspan="3" valign="top" height="100%" class="homepage_line">
								<table width="100%" border="0">
									<tr>
										<td height="6"></td>
									</tr>
									<? if($_GET['message'] != "logged"){ ?>
									<tr>
										<td class="gallery_copy">
											<? copy_area(24,2); ?>
										</td>
									</tr>
									<? } ?>
									<? if($_GET['message'] == "free"){ ?>
									<tr>
										<td class="gallery_copy">
											<? echo $login_logged_thanks; ?>
										</td>
									</tr>
									<? } ?>
									<? if($_GET['message'] == "cart"){ ?>
									<tr>
										<td class="gallery_copy">
											<? echo $login_cart_login; ?>
										</td>
									</tr>
									<? } ?>
									<tr>
										<td style="padding-left: 10px;">
											<table>
												<form action="public_actions.php?pmode=login" method="post">
												<? if($_GET['message'] == "login_failed"){ ?>
												<tr>
													<td><font color="#ff0000"><? echo $login_login_failed; ?></td>
												</tr>
												<? } ?>
												<? if($_GET['message'] == "pending"){ ?>
												<tr>
													<td><font color="#ff0000"><? echo $login_pending; ?></td>
												</tr>
												<? } ?>
												<? if($_GET['message'] == "logged_out"){ ?>
												<tr>
													<td><font color="#ff0000"><? echo $login_logged_out; ?></td>
												</tr>
												<? } ?>
												<? if($_GET['message'] == "logged"){ ?>
												<tr>
													<td><font color="#ff0000"><b><? echo $login_login; ?></td>
												</tr>
												<? } else { ?>
												<tr>
													<td>
														<? echo $form_email; ?><br>
														<input type="text" name="email" style="width: 250px;">
													</td>
												</tr>
												<tr>
													<td>
														<? echo $form_pass3; ?><br>
														<input type="password" name="password" style="width: 250px;"><br>
													</td>
												</tr>
												<tr>
													<td align="right">
													<input type="submit" value="<?PHP echo $login_form_submit_button; ?>">
													</td>
												</tr>
												</form>
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