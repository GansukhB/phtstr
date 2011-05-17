<?
	
	error_reporting(0);
	
	include( "config_mgr.php" );

//echo "tesT" . $error;
?>

<html>
	<head>
		<title><? echo $manager_title; ?></title>
		<link rel="stylesheet" href="mgr_style.css">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
	</head>
	<body bgcolor="#13387E" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onLoad="document.login_form.username.focus();">
		<center>
		<form action="mgr_actions.php?pmode=login" name="login_form" method="post">
		<table width="100%" height="100%">
			
			<?php
				if($db_error){
			?>
				<tr>
					<td align="left"><img src="images/mgr_check_loop_forever.gif" align="left" valign="absmiddle"><font color="#FFE400" style="font-size: 12;">&nbsp;Database error: <?php echo $db_error; ?></td>
				</tr>
			<?php
				}
			?>
			
			<?php
			if(file_exists("../password.php") or file_exists("../_password.php") or file_exists("../cleanup.php")){
				if(file_exists("../password.php")){
					$filename = " password.php";
				}
				if(file_exists("../_password.php")){
					$filename = " _password.php" . $filename;
				}
				if(file_exists("../cleanup.php")){
					$filename = " cleanup.php" . $filename;
				}
				$site_title = $setting->site_title;
				$site_url = $setting->site_url;
				$email_from = $setting->support_email;
			}
			/*
				# CHECK FOR REGISTER GLOBALS
				if(ini_get("register_globals") == 0){
			
				<tr>
					<td align="left"><img src="images/mgr_check_loop_forever.gif" align="left" valign="absmiddle"><font color="#FFE400" style="font-size: 12;">&nbsp;The register_globals setting in PHP is set to OFF. PhotoStore will not function properly until register_globals is ON. You will not be able to login to the management area until this setting is changed. Please contact your host and ask them to turn register_globals ON.</td>
				</tr>
			
				}
				*/
			?>
			
			<?php
				if(!function_exists('imagecreatetruecolor')){
			?>
				<tr>
					<td align="left"><img src="images/mgr_check_loop_forever.gif" align="left" valign="absmiddle"><font color="#FFE400" style="font-size: 12;">&nbsp;Your server does not have GD Library 2 (or higher) installed. GD Library 2 is one of the requirements for PhotoStore. Please contact your host and have them compile PHP with GD Library 2 support.</td>
				</tr>
			<?php
				}
			?>
			
			<tr>
				<td align="center" valign="middle">
					<table cellpadding="0" cellspacing="0" width="285">
						<tr>
							<td background="images/mgr_login_header.gif" height="23" align="center" valign="bottom"><font color="#ffffff"><b><? echo $manager_title; ?> Login</td>
						</tr>
						<tr>
							<td background="images/mgr_login_bg.gif" height="96" align="center" valign="middle">
								<table cellpadding="0" cellspacing="0" width="230">
									<tr>
										<td height="7"></td>
									</tr>
									<?
										if($setting->status != MD5(1)){
									?>
										<tr>
											<td align="left" bgcolor="#2C5EAA" style="padding: 4px; border: 1px solid #4C7FCE"><img src="images/mgr_check_loop_forever.gif" align="left" valign="absmiddle"><font color="#FFE400" style="font-size: 10;">&nbsp;<? echo $setting->error_message; ?></td>
										</tr>
									<?
										}
										else{
											if($logout == 1){
										?>
											<!-- LOGGED OUT -->
											<tr>
												<td align="left"><img src="images/mgr_check_loop_forever.gif" align="left" valign="absmiddle"><font color="#FFE400" style="font-size: 10;">&nbsp;You have been logged out.</td>
											</tr>
										<?
											}
											if($error == 1){
										?>
											<!-- LOGIN FAILED -->
											<tr>
												<td align="left"><img src="images/mgr_check_loop_forever.gif" align="left" valign="absmiddle"><font color="#FFE400" style="font-size: 10;">&nbsp;Login failed. Please try again.</td>
											</tr>
									<?
											}
										}
									?>
									
									<tr>
										<td height="7"></td>
									</tr>
									<!-- LOGIN FORM AREA -->
									<tr>
										<td><font color="#ffffff" style="font-size: 10;">USERNAME:</td>
									</tr>
									<tr>
										<td><input type="text" name="username" style="font-size: 10; font-weight: bold; color: #13387E; width: 230; border: 1px solid #000000;"></td>
									</tr>
									<tr>
										<td><font color="#ffffff" style="font-size: 10;">PASSWORD:</td>
									</tr>
									<tr>
										<td><input type="password" name="password" style="font-size: 10; font-weight: bold; color: #13387E; width: 230; border: 1px solid #000000;"></td>
									</tr>
									<tr>
										<td height="5"></td>
									</tr>
									<tr>
										<td align="right"><input type="image" src="images/mgr_button_login.gif"></td>
									</tr>
								</table>						
							</td>
						</tr>
						<tr>
							<td><img src="images/mgr_login_footer.gif"></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<? if(!file_exists("../nobranding.php") or $_GET['show'] == 1){ ?>
							<tr>
								<td align="center" class="footer">PhotoStore Version <b><? echo $ktools_product_version; ?></b> Installed</td>
							</tr>
							<tr>
								<td align="center" class="footer">Powered By <? if($author_website != ""){ ?><a href="<? echo $author_website; ?>" target="new" class="footer_link"><? } ?><img src="images/mgr_ktools_logo.gif" border="0" align="absmiddle"></a></td>
							</tr>
						<? } ?>
					</table>
				</td>
			</tr>
			<tr>
				<td height="140"></td>
			</tr>
		</table>
		</form>
		</center>
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	
