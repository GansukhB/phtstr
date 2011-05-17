<?
	session_start();

	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );
	
  //session_destroy();
	// VISITOR ID
	if(!$_SESSION['visitor_id']){
		session_register("visitor_id");
		$_SESSION['visitor_id'] = random_gen(16,"");
	}
	
	//UNSET ANY IMAGE VIEWING
	unset($_SESSION['pub_gid']);
	unset($_SESSION['pub_pid']);
	unset($_SESSION['imagenav']);
  
  
  //echo $_SESSION['lang'];  
?>
<html>
	<head>
		<script language=JavaScript src='./js/xns.js'></script>
	<? print($head); ?>
		<center>
<table cellpadding="0" cellspacing="0"><tr>
	<td valign="top">
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
				<td valign="top">
					<table cellpadding="0" cellspacing="0" width="560" height="100%">
						<tr>
							<td colspan="3" height="5"></td>
						</tr>
						<tr>
							<td class="crumb"><!--Location: <a href="#" class="crumb_links">Home</a> <? if($_GET['gid']){ echo " <img src=\"images/nav_arrow.gif\" align=\"middle\"> <a href=\"gallery.php?gid=" . $_GET['gid'] . "\" class=\"crumb_links\">" . $current_gallery_name . "</a>"; } ?><? if($_GET['sgid']){ echo " <img src=\"images/nav_arrow.gif\" align=\"middle\"> <a href=\"gallery.php?gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "\" class=\"crumb_links\">" . $current_sub_gallery_name . "</a>"; } ?>-->&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<? if($setting->show_news == 1){ ?>
								<td align="left" width="18" class="featured_news_header"><img src="images/triangle_1.gif"></td>
								<td align="right" class="featured_news_header" style="padding-right: 120px;" nowrap></td>
							<? } else { ?>
								<td></td>
								<td></td>
							<? } ?>
						</tr>
						<tr>
							<td colspan="3" valign="top">
								<table cellpadding="0" cellspacing="0" width="100%" height="100%">
									<tr>
										<td valign="top" class="index_copy_area">
											<div class="body_copy">
												<span class="body_header_text"><?PHP echo $homepage_welcome_to_message; ?><? echo $setting->site_title; ?></span><br><br>
												<?
													$copy_area_id = 15;
													$ca_result = mysql_query("SELECT article FROM copy_areas where id = '$copy_area_id'", $db);
													$ca = mysql_fetch_object($ca_result);
													
													$hp_copy = str_replace("<P>", "", $ca->article);
													$hp_copy = str_replace("</P>", "<br><br>", $hp_copy);
													$hp_copy = str_replace("{COMPANY_NAME}", $setting->site_title, $hp_copy);
													
													echo $hp_copy;
												?>
											</div>
										</td>
										<? if($setting->show_news == 1){ ?>
										<td width="18" class="index_copy_area">&nbsp;</td>
										<td width="200" valign="top" class="index_copy_area">
											<? include("i_news.php"); ?>
										</td>
										<? } else { ?>
											<td class="index_copy_area"></td>
											<td class="index_copy_area"></td>
										<? } ?>
									</tr>
									<tr>
										<td colspan="3" valign="top">
											<table cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<td class="featured_photos_tab" nowrap><?PHP echo $homepage_featured; ?></td>
													<td align="left" class="other_photos_tabs"><img src="images/triangle_2.gif"></td>
													<td class="other_photos_tabs" valign="top"><span class="other_photos_tabs"><a href="new_photos.php" class="white_bold_link"><?PHP echo $homepage_newest; ?></a></span><span class="other_photos_tabs"><a href="popular_photos.php" class="white_bold_link"><?PHP echo $homepage_popular; ?></a></span></td>
												</tr>
												<tr>
													<td colspan="3" align="center" valign="top" style="padding: 5px;">
														<?
														if(!file_exists("swf/featured.swf")){
														include("i_featured_photos.php"); 
														} else {
														if($_SESSION['visitor_flash'] != 1 && $setting->flash_featured_on == 1){
														?>
														<div id="flashcontent2"/>
														<?PHP echo $no_flashplayer; ?>
														</div>
														<script type="text/javascript" src="js/swfobject.js"></script>
                            <script>
                                <!--
                                    var flashObj = new SWFObject ("swf/featured.swf", "featured photos", "550", "325", 8, "<?PHP echo $setting->pf_bgcolor; ?>", true);
                                    flashObj.addVariable ("mousewheelflip", "<?PHP echo $setting->pf_mousewheelflip; ?>");
                                    flashObj.addVariable ("autoflipseconds", "<?PHP echo $setting->pf_autoflipseconds; ?>");
                                    flashObj.addVariable ("flipsound", "<?PHP echo $setting->pf_flipsound; ?>");
                                    flashObj.addVariable ("flipspeed", "<?PHP echo $setting->pf_flipspeed; ?>");
                                    flashObj.addVariable ("namebold", "<?PHP echo $setting->pf_namebold; ?>");
                                    flashObj.addVariable ("namecolor", "<?PHP echo $setting->pf_namecolor; ?>");
                                    flashObj.addVariable ("namedistance", "<?PHP echo $setting->pf_namedistance; ?>");
                                    flashObj.addVariable ("nameposition", "<?PHP echo $setting->pf_nameposition; ?>");
                                    flashObj.addVariable ("namesize", "<?PHP echo $setting->pf_namesize; ?>");
                                    flashObj.addVariable ("namefont", "<?PHP echo $setting->pf_namefont; ?>");
                                    flashObj.addVariable ("preloadset", "<?PHP echo $setting->pf_preloadset; ?>");
                                    flashObj.addVariable ("hpers", "<?PHP echo $setting->pf_hpers; ?>");
                                    flashObj.addVariable ("vpers", "<?PHP echo $setting->pf_vpers; ?>");
                                    flashObj.addVariable ("view", "<?PHP echo $setting->pf_view; ?>");
                                    flashObj.addVariable ("reflectionalpha", "<?PHP echo $setting->pf_reflectionalpha; ?>");
                                    flashObj.addVariable ("reflectiondepth", "<?PHP echo $setting->pf_reflectiondepth; ?>");
                                    flashObj.addVariable ("reflectiondistance", "<?PHP echo $setting->pf_reflectiondistance; ?>");
                                    flashObj.addVariable ("reflectionextend", "<?PHP echo $setting->pf_reflectionextend; ?>");
                                    flashObj.addVariable ("selectedreflectionalpha", "<?PHP echo $setting->pf_selectedreflectionalpha; ?>");
                                    flashObj.addVariable ("showname", "<?PHP echo $setting->pf_showname; ?>");
                                    flashObj.addVariable ("showreflection", "<?PHP echo $setting->pf_showreflection; ?>");
                                    flashObj.addVariable ("photoheight", "<?PHP echo $setting->pf_photoheight; ?>");
                                    flashObj.addVariable ("photowidth", "<?PHP echo $setting->pf_photowidth; ?>");
                                    flashObj.addVariable ("selectedy", "<?PHP echo $setting->pf_selectedy; ?>");
                                    flashObj.addVariable ("defaultid", "<?PHP echo $setting->pf_defaultid; ?>");
                                    flashObj.addVariable ("holderalpha", "<?PHP echo $setting->pf_holderalpha; ?>");
                                    flashObj.addVariable ("holderborderalpha", "<?PHP echo $setting->pf_holderborderalpha; ?>");
                                    flashObj.addVariable ("holderbordercolor", "<?PHP echo $setting->pf_holderbordercolor; ?>");
                                    flashObj.addVariable ("holdercolor", "<?PHP echo $setting->pf_holdercolor; ?>");
                                    flashObj.addVariable ("scalemode", "<?PHP echo $setting->pf_scalemode; ?>");
                                    flashObj.addVariable ("selectedscale", "<?PHP echo $setting->pf_selectedscale; ?>");
                                    flashObj.addVariable ("spacing", "<?PHP echo $setting->pf_spacing; ?>");
                                    flashObj.addVariable ("zoom", "<?PHP echo $setting->pf_zoom; ?>");
                                    flashObj.addVariable ("zoomtype", "<?PHP echo $setting->pf_zoomtype; ?>");                                    
                                    flashObj.write ("flashcontent2");
                                // -->
                            </script>
            							<?PHP
            						} else {
            							include("i_featured_photos.php"); 
            						}
            					} 
            							?>
													</td>
												</tr>
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
