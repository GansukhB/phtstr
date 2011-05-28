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
	
	if($lightbox){
	unset($_SESSION['lightbox_id']);
	session_register("lightbox_id");
	$_SESSION['lightbox_id'] = $lightbox;
	}
	
	//Unset any image storage details
	unset($_SESSION['imagenav']);
	
?>
<html>
	<head>
    <?php echo $script1; ?>
		<? print($head); ?>
<div class="container">
    <? include("header.php"); ?>
		<div id="main">
			<? include("i_gallery_nav.php"); ?>
      <div class="right-main">
        
					<table cellpadding="0" cellspacing="0" width="560" height="100%">
						<tr>
							<td colspan="3" height="5"></td>
						</tr>
						<tr>
							<?php
							$crumb = $lightbox_my_lightbox;
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
									<table width="95%" cellpadding="20">
									<tr>
									<?									
									$lightbox_group_result = mysql_query("SELECT id,name FROM lightbox_group WHERE member_id = '" . $_SESSION['sub_member'] . "'", $db);
									?>																	
										<form name="create" action="public_actions.php?pmode=create_lightbox" method="post">
										<td width="95%" align="left"><font face="arial" style="font-size: 11;"><?PHP echo $lightbox_create_new; ?><br />
										<input type="text" name="name" value="" style="font-size: 13; font-weight: bold; width: 100; border: 1px solid #000000;"  maxlength="50"> <input type="submit" value="<?PHP echo $lightbox_go_button; ?>" class="go_button">										
										</form>
										<? 	
											echo "<form method=\"post\" name=\"select\" action=''>";
											echo $lightbox_select_one;
											echo "<select name='cat' onchange=\"location=document.select.cat.options[document.select.cat.selectedIndex].value;\"><option value=\"lightbox.php\">" . $lightbox_select_lightbox . "</option>";
											while($lightbox_group = mysql_fetch_array($lightbox_group_result)) {
											if($lightbox_group[id] == $_SESSION['lightbox_id']){
											echo "<option selected value=\"lightbox.php?lightbox=" . $lightbox_group[id] . "\">" . $lightbox_group[name] . "</option><BR>";
											} else {
											echo "<option value=\"lightbox.php?lightbox=" . $lightbox_group[id] . "\">" . $lightbox_group[name] . "</option><BR>";
											}
										}
											echo "</select>";
											?>
											</form>
										<form name="email" action="public_actions.php?pmode=email_lightbox" method="post">
										<font face="arial" style="font-size: 11;"><?PHP echo $lightbox_email_it; ?>
										<input type="text" name="email" value="" style="font-size: 13; font-weight: bold; width: 250; border: 1px solid #000000;"><br />
										<?PHP echo $lightbox_message_optional; ?>
										<textarea name="note" rows="5" cols="30"></textarea> <input type="submit" value="<?PHP echo $lightbox_go_button; ?>" class="go_button">										
										</form>
										</td>								
									</tr>
									</table>
									<table width="95%">							
									<? if($message == "removed"){
										echo "<tr><td style=\"padding: 0px 0px 0px 20px\">" . $lightbox_removed . "</td></tr>";
									}
									?>
									<? if($message == "added"){
										echo "<tr><td style=\"padding: 0px 0px 0px 20px\">" . $lightbox_added . "</td></tr>";
									}
									?>
									<? if($message == "empty"){
										echo "<tr><td style=\"padding: 0px 0px 0px 20px\">" . $lightbox_delete_all . "</td></tr>";
									}
									?>
									<? if($message == "select"){
										echo "<tr><td style=\"padding: 0px 0px 0px 20px\">" . $lightbox_need_to_create . "</td></tr>";
									}
									?>
									<? if($message == "created"){
										echo "<tr><td style=\"padding: 0px 0px 0px 20px\">" . $lightbox_is_created . "</td></tr>";
									}
									?>
									<? if($message == "lightbox_deleted"){
										echo "<tr><td style=\"padding: 0px 0px 0px 20px\">" . $lightbox_deleted_lightbox . "</td></tr>";
									}
									?>
									<? if($message == "lightbox_emailed"){
										echo "<tr><td style=\"padding: 0px 0px 0px 20px\">" . $lightbox_was_emailed . "</td></tr>";
									}
									?>
									<? if(!$_SESSION['lightbox_id']){
										echo "<tr><td valign=\"top\" style=\"padding: 0px 0px 0px 20px\">" . $lightbox_please_select . "</td></tr>";
									}
									if($_SESSION['pub_pid'] && $_SESSION['pub_gid']){
										echo "<tr><td valign=\"top\" style=\"padding: 0px 0px 0px 20px\"><b><a href=\"details.php?gid=" . $_SESSION['pub_gid'] . "&pid=" . $_SESSION['pub_pid'] . "\">" . $lightbox_return_to_page . "</a></b></td></tr>";
									}
									?>
									</table>
					<? 
						$lightbox_result = mysql_query("SELECT id,photo_id FROM lightbox where reference_id = '" . $_SESSION['lightbox_id'] . "' and member_id = '" . $_SESSION['sub_member'] . "' order by id desc", $db);
						$lightbox_rows = mysql_num_rows($lightbox_result);
						
						$lightbox_name_result = mysql_query("SELECT name FROM lightbox_group WHERE id = '" . $_SESSION['lightbox_id'] . "'", $db);
						$lightbox_name = mysql_fetch_object($lightbox_name_result);
			if($_SESSION['lightbox_id'] AND $lightbox_rows <= 0){
			echo "<table width=\"95%\">";
			if($lightbox_name->name){
			echo "<tr><td style=\"padding: 0px 0px 0px 20px\"><b>" . $lightbox_page_name . $lightbox_name->name . "</b></td></tr>";
					}
			echo "<tr><td style=\"padding: 0px 0px 0px 20px\">" . $lightbox_no_image . "</td></tr>";
			if($_SESSION['lightbox_id']){
		?>
					<tr>
					<td style="padding: 0px 0px 0px 20px"><br /><b><a href="public_actions.php?pmode=delete_lightbox_group"><?PHP echo $lightbox_delete_it_now; ?>(<? echo $lightbox_name->name; ?>)</a></b></td>
					</tr>
		<? } ?>
			</table>
		<?
		} else {
						if($lightbox_name->name){
							echo "<table width=\"95%\">";
						echo "<tr><td style=\"padding: 0px 0px 0px 20px\"><b>" . $lightbox_lightbox_name . $lightbox_name->name . "</b></td></tr>";
							echo "</table>";
					}
						while($lightbox = mysql_fetch_object($lightbox_result)){
															
						$package_result = mysql_query("SELECT id,title,description,gallery_id FROM photo_package where id = '$lightbox->photo_id'", $db);
						$package = mysql_fetch_object($package_result);
						
						$photo_result = mysql_query("SELECT id,filename FROM uploaded_images where reference = 'photo_package' and reference_id = '$package->id' order by id desc", $db);
						$photo = mysql_fetch_object($photo_result);
		
		?>
		<? if($lightbox_rows > 0 && $photo->filename){ ?>
			<table width="95%">
				<tr>
					<? if($setting->show_watermark_thumb == 1){ ?>
					<td width="300" align="left" valign="middle" style="padding: 0px 0px 0px 20px;"><a href="details.php?gid=<? echo $package->gallery_id; ?>&sgid=<? echo $sgid; ?>&pid=<? echo $package->id; ?>"><img src="thumb_mark.php?i=<? echo $photo->id; ?>" class="photos" border="0"><br><?PHP echo $lightbox_click_for_details; ?></a></td>
					<? } else { ?>
					<td width="300" align="left" valign="middle" style="padding: 0px 0px 0px 20px;"><a href="details.php?gid=<? echo $package->gallery_id; ?>&sgid=<? echo $sgid; ?>&pid=<? echo $package->id; ?>"><img src="image.php?src=<? echo $photo->id; ?>" class="photos" border="0"><br><?PHP echo $lightbox_click_for_details; ?></a></td>
					<? } ?>
					<td width="250" align="left" valign="top" style="padding: 20px 10px 10px 10px;"><a href="public_actions.php?pmode=remove_lightbox&lid=<? echo $lightbox->id; ?>"><?PHP echo $lightbox_remove_photo; ?></a><br><br>
					<? if($package->title){ ?><?PHP echo $lightbox_photo_title; ?><br /><? echo $package->title; ?></br><? } ?>
					<? if($package->description){ ?><?PHP echo $lightbox_photo_description; ?><br /><? echo $package->description; } ?></td>
				</tr>
			</table>
				<hr width="95%">
				<? } ?>
					<? }
					if($lightbox_rows > 0){ ?>	
			<table width="95%">
				<tr>
					<td style="padding: 0px 0px 0px 20px"><b><a href="public_actions.php?pmode=delete_lightbox"><?PHP echo $lightbox_delete_all_photos; ?></a></b></td>				
				</tr>
				<tr>
					<td style="padding: 0px 0px 0px 20px"><br /><b><a href="public_actions.php?pmode=delete_lightbox_group"><?PHP echo $lightbox_delete_this_lightbox; ?>(<? echo $lightbox_name->name; ?>)</a></b><br /></td>
				</tr>
			</table>
				<? } } ?>
							</td>
						</tr>
					</table>				
				</td>
			</tr>
			</div> <!-- end class right-main -->
      
      <?php include('i_banner.php'); ?>
      
      </div><!-- end main id-->
      </div> <!-- end container class -->
      <? include("footer.php"); ?>
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	
