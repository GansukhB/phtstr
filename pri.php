<?
	session_start();

	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	
	include( "database.php" );
	include( "functions.php" );
	
//ADDED IN PS350 TO GET GALLERY INFO AN USE AS THE PAGE TITLE
	$gal_result = mysql_query("SELECT * FROM photo_galleries where id = '" . $_GET['gid'] . "'", $db);
	$pgal = mysql_fetch_object($gal_result);

//ADDED IN PS350 TO CHECK TO SEE IF THE GALLERY REQUIRES MEMBERSHIP
	if($pgal->free == 1){
		if($pgal->free == 1 && $_SESSION['sub_type'] != "free"){
			$no_view = 1;
			$mes = "galfree";
			$t = "f";
		} else {
		if($pgal->monthly == 1 && $_SESSION['sub_type'] == "monthly"){
			$no_view = 0;
			} else {
			if($pgal->yearly == 1 && $_SESSION['sub_type'] == "yearly"){
			$no_view = 0;
			} else {
			if($pgal->free == 1 && $_SESSION['sub_type'] == "free")
			$no_view = 0;
			}
		}
	}
}
	if($pgal->monthly == 1){
		if($pgal->monthly == 1 && $_SESSION['sub_type'] != "monthly"){
			$no_view = 1;
			$mes = "galmonthly";
			$t = "m";
		} else {
		if($pgal->monthly == 1 && $_SESSION['sub_type'] == "monthly"){
			$no_view = 0;
			} else {
			if($pgal->yearly == 1 && $_SESSION['sub_type'] == "yearly"){
			$no_view = 0;
			} else {
			if($pgal->free == 1 && $_SESSION['sub_type'] == "free")
			$no_view = 0;
			}
		}
	}
}
	if($pgal->yearly == 1){
		if($pgal->yearly == 1 && $_SESSION['sub_type'] != "yearly"){
			$no_view = 1;
			$mes = "galyearly";
			$t = "y";
		} else {
		if($pgal->monthly == 1 && $_SESSION['sub_type'] == "monthly"){
			$no_view = 0;
			} else {
			if($pgal->yearly == 1 && $_SESSION['sub_type'] == "yearly"){
			$no_view = 0;
			} else {
			if($pgal->free == 1 && $_SESSION['sub_type'] == "free")
			$no_view = 0;
			}
		}
	}
}
// REDIRECT TO SIGNUP PAGE IF IT DOES REQUIRE MEMBERSHIP
	if($no_view == 1){
	session_register("pub_gid");
	$_SESSION['pub_gid'] = $pgal->id;
	header("location: subscribe.php?t=$t&message=$mes");
	exit;
	}
// ADDED IN 350 IN CASE WE NEED TO BRING SOMEONE BACK TO A PHOTO
	if($_GET['gal'] && $_GET['gid']){
		session_register("pri_gid");
		$_SESSION['pri_gid'] = $_GET['gid'];
		session_register("pri_pid");
		$_SESSION['pri_pid'] = $_GET['pid'];
	}				
	
	if($pgal->title == ""){
	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE
	} else {
	$page_title       = $pgal->title;
	}
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	//if($gal->description == ""){
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	//} else {
	//$meta_description = $gal->description;
	//}
	
	include( "config_public.php" );
	
	// VISITOR ID
	if(!$_SESSION['visitor_id']){
		session_register("visitor_id");
		$_SESSION['visitor_id'] = random_gen(16,"");
	}
	
	if(!empty($_REQUEST['gal'])){
		session_register("gal");
		$_SESSION['gal'] = $_REQUEST['gal'];
	}
	
	//UNSET ANY IMAGE VIEWING
	unset($_SESSION['pub_gid']);
	unset($_SESSION['pub_pid']);
	
?>
<html>
	<head>
		<script language=JavaScript src='./js/xns.js'></script>
		<script language=javascript type="text/javascript">
    	function NewWindow(page, name, w, h, location, scroll) {
        var winl = (screen.width - w) / 2;
        var wint = (screen.height - h) / 2;
        winprops = 'height='+h+',width='+w+',location='+location+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable'
        win = window.open(page, name, winprops)
        if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
    	}
	</script>
  <?php echo $script1; ?>
	<? print($head); ?>
		<div class="container">
    <? include("header.php"); ?>
		<div id="main">
			<? include("i_gallery_nav.php"); ?>
      <div class="right-main">
        <? include("crumbs.php"); ?>
        
		<table cellpadding="0" cellspacing="0" width="765" class="main_table" style="border: 5px solid #<? echo $border_color; ?>;">
			<? //include("header.php"); ?>
			<tr>
				<? include("search_bar.php"); ?>
			</tr>
			<tr>
				<td valign="top" height="18">
					<table cellpadding="0" cellspacing="0" width="560" >
						
						
							
						<tr>
							<td colspan="3" valign="top" height="100%" class="homepage_line">
								<table width="100%" border="0">
									<tr>
							<? 
							if($_GET['sort_by'] != ""){
								   $sort_by = $_GET['sort_by'];
								  } else {
								   $sort_by = $pgal->sort_by;
								  }
								 
							if($_GET['sort_order'] != ""){
								   $sort_order = $_GET['sort_order'];
								  } else {
								   $sort_order = $pgal->sort_order;
								  }
								  
								  if($sort_by == "id"){
								  	$order_by = "id";
								  }
								  if($sort_by == "title"){
								  	$order_by = "title";
								  }
								  if($sort_by == "date"){
								  	$order_by = "added";
								  }
								  if($sort_by == "popular"){
								  	$order_by = "code";
								  }
								  
								  if($sort_order == "ascending"){
								  	$order = "";
								  } else {
								  	$order = "desc";
								  }
								  
							?>
										<td height="6" style="padding-left: 10px;">
											<?PHP if($pgal->gallery_search_on == 1 && $setting->private_search == 1){ ?>
							    			<form name="gal_search" action="search.php" method="link">
							    			<input type="hidden" name="gid_search" value="<? echo $_GET['gid']; ?>">
											<? echo $gallery_gallery_search; ?> <input type="text" name="search" class="search_box"> <input type="submit" value="<? echo $search_button; ?>" class="go_button"><br />
											<input type="radio" name="match_type" value="all"> <? echo $search_match_all; ?> | <input type="radio" name="match_type" value="any" checked> <? echo $search_match_any; ?> <? if($setting->hide_id != 1){ ?><br><input type="radio" name="match_type" value="id"> <? echo $search_match_id; ?><? } ?> | <input type="radio" name="match_type" value="exact"> <? echo $search_match_exact; ?>
							  				</form>
											<?PHP } ?>
											<?PHP if($pgal->sort_on == 1){ ?>	
											<form name="sort" action="pri.php" method="link">
											<input type="hidden" name="gal" value="<? echo $_GET['gal']; ?>">
											<input type="hidden" name="gid" value="<? echo $_GET['gid']; ?>">
											<?PHP echo $pri_gal_sort_by; ?><select name="sort_by" id="sort_by" style="font-size: 9; width:150;">
																<option value="id" <? if($sort_by == id){ echo "selected"; } ?>><?PHP echo $pri_gal_sort_id; ?></option>
																<option value="title" <? if($sort_by == title){ echo "selected"; } ?>><?PHP echo $pri_gal_order_title; ?></option>
																<option value="date" <? if($sort_by == date){ echo "selected"; } ?>><?PHP echo $pri_gal_order_date; ?></option>
																<option value="popular" <? if($sort_by == popular){ echo "selected"; } ?>><?PHP echo $pri_gal_order_popular; ?></option>
															</select>
											<?PHP echo $pri_gal_order_by; ?><select name="sort_order" id="sort_order" style="font-size: 9; width:120;">
																<option value="ascending" <? if($sort_order == ascending){ echo "selected"; } ?>><?PHP echo $pri_gal_order_asc; ?></option>
																<option value="descending" <? if($sort_order == descending){ echo "selected"; } ?>><?PHP echo $pri_gal_order_desc; ?></option>
															</select>  <input type="submit" value="GO" class="go_button">
											<?PHP } ?>
											</form>
										</td>
									</tr>
									<tr>
										<td class="gallery_copy">
										<?
											$pg_result = mysql_query("SELECT id,password,description,slideshow,pageflip FROM photo_galleries where rdmcode = '$_SESSION[gal]'", $db);
											$pg_rows = mysql_num_rows($pg_result);
											$pg = mysql_fetch_object($pg_result);
										
											if($pg_rows > 0){
										
												if($pg->password and $_SESSION['galaccess'] != $_SESSION['gal']){
													$logged=0;
										?>
													<form action="pri_actions.php" method="post">
													<input type="hidden" value="<?php echo $gal; ?>" name="gal">
													<input type="hidden" value="<?php echo $gid; ?>" name="gid">
														<table>
															<? if($mes == "failed"){ ?>
															<tr>
																<td><?PHP echo $pri_gal_login_failed; ?><br><br></td>
															</tr>
															<? } ?>
															<tr>
																<td>
																	<?PHP echo $pri_gal_enter_password; ?><br><br>
																	<input type="password" name="password" style="width: 150px;"><input type="submit" value="<?PHP echo $pri_gal_form_submit_button; ?>">
																</td>
															</tr>
														</table>
													</form>
										<?											
												} else {
													$perpage = $setting->perpage;
													if($_GET['page_num']){												
														$page_num = $_GET['page_num'];
													} else {
														$page_num = 1;
													}
													# CALCULATE THE STARTING RECORD						
													$startrecord = ($page_num == 1) ? 0 : (($page_num - 1) * $perpage);	
													
													$logged=1;
													
													$package_rows = mysql_result(mysql_query("SELECT COUNT(id) FROM photo_package where (gallery_id = '$pg->id' or other_galleries LIKE '%$pg->id%') and active = '1' and photog_show = '1'"),0);
													$package_result = mysql_query("SELECT id,title,description,gallery_id,code FROM photo_package where (gallery_id = '$pg->id' or other_galleries LIKE '%$pg->id%') and active = '1' and photog_show = '1' order by $order_by " . $order . "  LIMIT $startrecord,$perpage", $db);
													
													
													if($package_rows > 0){
													?>
														<?php
															if($pg->description){
																print ($pg->description);
															} else {
																copy_area(10,2);
															}													
														?>
													<?
													} else {
														if($pg->description){
														echo $pg->description;
														}
														if($setting->no_photo_message == 1){											
														echo $pri_no_photo_message;
														}
													}
												}
												
											} else {
												echo $pri_gal_bad_link;
											}
										?>
										</td>
									</tr>
									<tr>
										<td>
											<?
												if($logged == 1){
													$perpage = $setting->perpage;
													if($_GET['page_num']){												
														$page_num = $_GET['page_num'];
													} else {
														$page_num = 1;
													}
													# CALCULATE THE STARTING RECORD						
													$startrecord = ($page_num == 1) ? 0 : (($page_num - 1) * $perpage);	
													
													$package_rows = mysql_result(mysql_query("SELECT COUNT(id) FROM photo_package where (gallery_id = '$pg->id' or other_galleries LIKE '%$pg->id%') and active = '1' and photog_show = '1'"),0);
													$package_result = mysql_query("SELECT * FROM photo_package where (gallery_id = '$pg->id' or other_galleries LIKE '%$pg->id%') and active = '1' and photog_show = '1' order by $order_by " . $order . "  LIMIT $startrecord,$perpage", $db);
													
												//$package_result = mysql_query("SELECT * FROM photo_package where (gallery_id = '$pg->id' or other_galleries LIKE '%$pg->id%') and active = '1' order by $order_by " . $order, $db);
												//$package_rows = mysql_num_rows($package_result);
												?>
												<p align="center">
												<?PHP
												if($pg->slideshow ==1){
													?>
													<a href = "javascript:NewWindow('preload.php','LargeView','<? echo $setting->sample_width + 100; ?>','<? echo $setting->sample_width + 100; ?>','0','yes');"><img src="images/slideshow.gif" alt="<?PHP echo $slideshow_image_alt; ?>" title="<?PHP echo $slideshow_image_alt; ?>" border="0"></a>
													<?
												}
												if($pg->pageflip == 1){
													?>
												<a href="pageflip.php" target="_blank"><img src="images/pageflip.gif" border="0" alt="<?PHP echo $album_image_alt; ?>" title="<?PHP echo $album_image_alt; ?>"></a>
												<?PHP 
													}
												?></p><?PHP
													include("i_pri_photos.php");													
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
        <td valign="top">
			<?php
				if($pf_feed_status){
					include('pf_feed.php');
				}
			?>
       </div> <!-- end class right-main -->
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
