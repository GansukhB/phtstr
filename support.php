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
	
	session_register("mail_id");
	$_SESSION['mail_id'] = random_gen(16,"");
	
	//UNSET ANY IMAGE VIEWING
	unset($_SESSION['pub_gid']);
	unset($_SESSION['pub_pid']);
	
?>
<html>
	<head>		
		<script language="javascript">
	
			function js_support() {
				if(document.contact_form.name.value == "") {
					alert("Please enter your <? echo $support_name; ?>.");
					return false;
				}
				if(document.contact_form.email.value == "") {
					alert("Please enter your <? echo $support_email; ?>.");
					return false;
				}
				if(document.contact_form.comments.value == "") {
					alert("Please enter a question or comment.");
					return false;
				}
				document.contact_form.action = "public_actions.php?pmode=send_mail";
				document.contact_form.submit();
			}
		
		</script>
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
						<?php
							$crumb = $support_crumb_link;
							include("crumbs.php");
						?>
						<tr>
							<td class="index_copy_area" colspan="3" height="4"></td>
						</tr>						
						<tr>
							<td colspan="3" valign="top" height="100%" class="homepage_line">
								<table border="0">
									<tr>
										<td height="6"></td>
									</tr>
									<?
										if($_GET['message'] == "sent"){
									?>
									<tr>
										<td class="gallery_copy">
											<? copy_area(25,2); ?>
										</td>
									</tr>
									<?
										} else {
									?>
									<tr>
										<td class="gallery_copy">
											<? copy_area(21,2); ?>
										</td>
									</tr>
									<form method="post" name="contact_form">
									<input type="hidden" value="<?php echo $_SESSION['mail_id']; ?>" name="reg" />
									<tr>
										<td style="padding: 10px;">
											<table>
												<tr>
													<td>
													<? echo $support_name; ?>:<br>
													<input name="name" type="textbox" style="width: 350px;">
													</td>
												</tr>
												<tr>
													<td>
													<? echo $support_email; ?>:<br>
													<input name="email" type="textbox" style="width: 350px;">
													</td>
												</tr>
												<tr>
													<td>
													<? echo $support_comments; ?>:<br>
													<textarea name="comments" style="width: 450px;height: 200px;"><?php
														if($_GET['pid']){
															$package_result = mysql_query("SELECT * FROM photo_package where id = '" . $pid . "'", $db);
															$package_rows = mysql_num_rows($package_result);
															$package = mysql_fetch_object($package_result);
															
															$pg_result = mysql_query("SELECT * FROM photo_galleries where id = '$package->gallery_id'", $db);
															$pg_rows = mysql_num_rows($pg_result);
															$pg = mysql_fetch_object($pg_result);
																														
															echo "I would like purchasing/licensing information on photo #" . $pid . " located in the " . $pg->title . " category of the site.";
														}
														if($_GET['order_info']){
															echo "Order " . $_GET['order_info'] . $support_enter_below;
														}
													?></textarea>
													</td>
												</tr>
												<tr>
													<td align="right"><input type="button" value="<?PHP echo $support_form_send_button; ?>" onClick="js_support();"></td>
												</tr>
											</table>
										</td>
									</tr>
									</form>
									<?
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