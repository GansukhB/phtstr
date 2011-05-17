<?
	session_start();
	
	include( "database.php" );
	include( "functions.php" );
	
	# CHECK TO MAKE SURE THE GALLERY ISN'T PRIVATE : IF IT IS REDIRECT TO THE CORRECT PAGE
	$gal_result = mysql_query("SELECT * FROM photo_galleries where id = '" . $_GET['gid'] . "'", $db);
	$gal_rows = mysql_num_rows($gal_result);
	$gal = mysql_fetch_object($gal_result);

	if($gal->pub_pri == 1){
		header("location: pri.php?gal=" . $gal->rdmcode . "&gid=" . $gal->id);
		exit;
	}
//ADDED IN PS350 TO CHECK TO SEE IF THE GALLERY REQUIRES MEMBERSHIP
	if($gal->free == 1){
		if($gal->free == 1 && $_SESSION['sub_type'] != "free"){
			$no_view = 1;
			$mes = "galfree";
			$t = "f";
		} else {
		if($gal->monthly == 1 && $_SESSION['sub_type'] == "monthly"){
			$no_view = 0;
			} else {
			if($gal->yearly == 1 && $_SESSION['sub_type'] == "yearly"){
			$no_view = 0;
			} else {
			if($gal->free == 1 && $_SESSION['sub_type'] == "free")
			$no_view = 0;
			}
		}
	}
}
	if($gal->monthly == 1){
		if($gal->monthly == 1 && $_SESSION['sub_type'] != "monthly"){
			$no_view = 1;
			$mes = "galmonthly";
			$t = "m";
		} else {
		if($gal->monthly == 1 && $_SESSION['sub_type'] == "monthly"){
			$no_view = 0;
			} else {
			if($gal->yearly == 1 && $_SESSION['sub_type'] == "yearly"){
			$no_view = 0;
			} else {
			if($gal->free == 1 && $_SESSION['sub_type'] == "free")
			$no_view = 0;
			}
		}
	}
}
	if($gal->yearly == 1){
		if($gal->yearly == 1 && $_SESSION['sub_type'] != "yearly"){
			$no_view = 1;
			$mes = "galyearly";
			$t = "y";
		} else {
		if($gal->monthly == 1 && $_SESSION['sub_type'] == "monthly"){
			$no_view = 0;
			} else {
			if($gal->yearly == 1 && $_SESSION['sub_type'] == "yearly"){
			$no_view = 0;
			} else {
			if($gal->free == 1 && $_SESSION['sub_type'] == "free")
			$no_view = 0;
			}
		}
	}
}
// REDIRECT TO SIGNUP PAGE IF IT DOES REQUIRE MEMBERSHIP
	if($no_view == 1){
	session_register("pub_gid");
	$_SESSION['pub_gid'] = $gal->id;
	header("location: subscribe.php?t=$t&message=$mes");
	exit;
	}
							
	
	if($gal->title == ""){
	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE
	} else {
	$page_title       = $gal->title;
	}
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	//if($gal->description == ""){
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	//} else {
	//$meta_description = $gal->description;
	//}
	
	include( "config_public.php" );
	
	if($_GET['gid']){
		$gid = $_GET['gid'];
	} else {
		$gid = 99999999999999999999999999;
	}
	
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
						<?
							include("crumbs.php");
						?>
						<tr>
							<td class="index_copy_area" colspan="3" height="4"></td>
						</tr>						
						<tr>
							<td colspan="3" valign="top" height="100%" class="homepage_line">
								<table width="100%" border="0">
							<?PHP
							if($_GET['sort_by'] != ""){
								   $sort_by = $_GET['sort_by'];
								  } else {
								   $sort_by = $gal->sort_by;
								  }
								 
							if($_GET['sort_order'] != ""){
								   $sort_order = $_GET['sort_order'];
								  } else {
								   $sort_order = $gal->sort_order;
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
							<tr>
								<td height="6" style="padding-left: 10px;">
							<?PHP if($gal->gallery_search_on == 1){ ?>
							    <form name="gal_search" action="search.php" method="link">
							    <input type="hidden" name="gid_search" value="<? echo $_GET['gid']; ?>">
								<? echo $gallery_gallery_search; ?> <input type="text" name="search" class="search_box"> <input type="submit" value="<? echo $search_button; ?>" class="go_button"><br />
								<input type="radio" name="match_type" value="all"> <? echo $search_match_all; ?> | <input type="radio" name="match_type" value="any" checked> <? echo $search_match_any; ?> <? if($setting->hide_id != 1){ ?><br><input type="radio" name="match_type" value="id"> <? echo $search_match_id; ?><? } ?> | <input type="radio" name="match_type" value="exact"> <? echo $search_match_exact; ?>
							  	</form>
							<?PHP } ?>
							<?PHP if($gal->sort_on == 1){ ?>
									<form name="sort" action="gallery.php" method="link">
											<input type="hidden" name="gid" value="<? echo $_GET['gid']; ?>">
											<? echo $gallery_sort_by; ?><select name="sort_by" id="sort_by" style="font-size: 9; width:150;">
											<option value="id" <? if($sort_by == id){ echo "selected"; } ?>><?PHP echo $gallery_sort_id; ?></option>
											<option value="title" <? if($sort_by == title){ echo "selected"; } ?>><?PHP echo $gallery_sort_title; ?></option>
											<option value="date" <? if($sort_by == date){ echo "selected"; } ?>><?PHP echo $gallery_sort_date; ?></option>
											<option value="popular" <? if($sort_by == popular){ echo "selected"; } ?>><?PHP echo $gallery_sort_popular; ?></option>
											</select>
											<? echo $gallery_order_by; ?><select name="sort_order" id="sort_order" style="font-size: 9; width:120;">
											<option value="ascending" <? if($sort_order == ascending){ echo "selected"; } ?>><?PHP echo $gallery_sort_asc; ?></option>
											<option value="descending" <? if($sort_order == descending){ echo "selected"; } ?>><?PHP echo $gallery_sort_desc; ?></option>
											</select>  <input type="submit" value="GO" class="go_button">
										</td>
									</form>
									</tr>
							<?PHP } else { ?>
										</td>
									</tr>
							<?PHP } ?>
									<tr>
										<td class="gallery_copy">
										<?PHP
											$perpage = $setting->perpage;
											
											# CHECK TO SEE IF THE CURRENT PAGE IS SET
											if($_GET['page_num']){											
												$page_num = $_GET['page_num'];
											} else {
												$page_num = 1;
											}
											# CALCULATE THE STARTING RECORD						
											$startrecord = ($page_num == 1) ? 0 : (($page_num - 1) * $perpage);
											
												$pg_result = mysql_query("SELECT id FROM photo_package where active = '1' and photog_show = '1' and (gallery_id = '$gid' or other_galleries LIKE '%,$gid,%') order by $order_by " . $order);
												$package_rows = mysql_num_rows($pg_result);
												while($pg = mysql_fetch_object($pg_result)){
													$search_array2.="$pg->id,";
												}
												
												$package_result = mysql_query("SELECT id,title,description,code,gallery_id FROM photo_package WHERE active = '1' and photog_show = '1' and (gallery_id = '$gid' or other_galleries LIKE '%,$gid,%') order by $order_by " . $order . "  LIMIT $startrecord,$perpage", $db);
												session_register("imagenav");
												$_SESSION['imagenav'] = $search_array2;
												$_SESSION['imagenav'] = substr($_SESSION['imagenav'], 0, -1);		
											
											if($package_rows > 0){
											?>
												<?php
													if($gal->description){
														print ($gal->description);
													} else {
														copy_area(10,2);
													}													
												?>	
											<?
											} else {
												if($gal->description){
												print ($gal->description);
												}
												if($setting->no_photo_message == 1){											
												echo $gallery_no_photo_message;
												}
											}
										?>
										</td>
									</tr>
									<tr>
										<td style="padding-bottom: 12px;"><br>
										<p align="center">
											<?PHP if($_SESSION['imagenav']){ ?>
												<?PHP if($gal->slideshow == 1){ ?>
												<?PHP if(file_exists("swf/slideshow.swf")){ ?>
											<a href = "javascript:NewWindow('swfslideshow.php','SlideShow','660','520','0','yes');"><img src="images/slideshow.gif" border="0" alt="<?PHP echo $slidshow_image_alt; ?>" title="<?PHP echo $slidshow_image_alt; ?>"></a>
												<?PHP } else { ?>
											<a href = "javascript:NewWindow('preload.php','LargeView','<? echo $setting->sample_width + 100; ?>','<? echo $setting->sample_width + 100; ?>','0','yes');"><img src="images/slideshow.gif" border="0" alt="<?PHP echo $slidshow_image_alt; ?>" title="<?PHP echo $slidshow_image_alt; ?>"></a>
												<?PHP } } ?>
												<?PHP if($gal->pageflip == 1){ ?>
											<a href="pageflip.php" target="_blank"><img src="images/pageflip.gif" border="0" alt="<?PHP echo $album_image_alt; ?>" title="<?PHP echo $album_image_alt; ?>"></a>
												<?PHP } ?>
											<?PHP } ?>
											</p>
											<?PHP if($package_rows > 0){ ?>
											<?PHP include("i_gallery_photos.php"); ?>
											<?PHP } ?>
										</td>
									</tr>
									
										<?php
											if(!$setting->show_private){
												$extrasql = " and pub_pri = '0'";
											} else {
												$extrasql = "";
											}
										
											$ca_result = mysql_query("SELECT * FROM photo_galleries where active = '1' and nest_under = '$gid'" . $extrasql . " order by galorder", $db);
											$ca_rows = mysql_num_rows($ca_result);
											
											if($ca_rows > 0){
										?>
							<tr>
								<td style="padding: 10px 14px 10px 14px; background-color: #F8F8F8;">
								<?PHP echo $gallery_subs; ?>
									<table border="0" callspacing="3" width="100%">
										<tr valign="top">
										<?php
	$photocount = 1;										
	while($ca = mysql_fetch_object($ca_result)){
		if($photocount == 1){
			echo "\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t<tr valign=\"top\">";
		}						

		if($ca->link != "" && $ca->link != "av-."){
			if($ca->pub_pri == 1){
				echo "\t\t\t\t\t\t\t\t\t\t\t\n<td align=\"center\" valign=\"top\" bgcolor=\"#F9F9F9\" style=\"border: 1px solid #eeeeee; padding: 5px 0px 5px 0px;\" width=\"";
				echo round(100/$setting->dis_columns);
				echo "%\"><a href=\"pri.php?gid=$ca->id&gal=$ca->rdmcode\"><img src=\"gal_images/$ca->link\" border=\"1\"><br />$ca->title</a>";
			if($setting->show_num == 1){ 
				imgcount($ca->id,1,0,$item_id,0,$setting->show_private);
				echo " (" .$photo_rows_final . ")</td>";
				$photo_rows_final = 0;
					} else {
					echo "</td>";
				}
			} else {
				echo "\t\t\t\t\t\t\t\t\t\t\t\n<td align=\"center\" valign=\"top\" bgcolor=\"#F9F9F9\" style=\"border: 1px solid #eeeeee; padding: 5px 0px 5px 0px;\" width=\"";
				echo round(100/$setting->dis_columns);
				echo "%\"><a href=\"gallery.php?gid=$ca->id\"><img src=\"gal_images/$ca->link\" border=\"1\"><br />$ca->title</a>";
			if($setting->show_num == 1){ 
				imgcount($ca->id,1,0,$item_id,0,$setting->show_private);
				echo " (" .$photo_rows_final . ")</td>";
				$photo_rows_final = 0;
					} else {
					echo "</td>";
				}
			}
		} else {
			if($ca->pub_pri == 1){
				echo "\t\t\t\t\t\t\t\t\t\t\t\n<td align=\"center\" valign=\"top\" bgcolor=\"#F9F9F9\" style=\"border: 1px solid #eeeeee; padding: 5px 0px 5px 0px;\" width=\"";
				echo round(100/$setting->dis_columns);
				echo "%\"><a href=\"pri.php?gid=$ca->id&gal=$ca->rdmcode\">$ca->title</a>";
				if($setting->show_num == 1){ 
				imgcount($ca->id,1,0,$item_id,0,$setting->show_private);
				echo " (" .$photo_rows_final . ")</td>";
				$photo_rows_final = 0;
					} else {
					echo "</td>";
				}
			} else {
				echo "\t\t\t\t\t\t\t\t\t\t\t\n<td align=\"center\" valign=\"top\" bgcolor=\"#F9F9F9\" style=\"border: 1px solid #eeeeee; padding: 5px 0px 5px 0px;\" width=\"";
				echo round(100/$setting->dis_columns);
				echo "%\"><a href=\"gallery.php?gid=$ca->id\">$ca->title</a>";
				if($setting->show_num == 1){ 
				imgcount($ca->id,1,0,$item_id,0,$setting->show_private);
				echo " (" .$photo_rows_final . ")</td>";
				$photo_rows_final = 0;
					} else {
					echo "</td>";
				}
			}
		}
		
		$photo2_rows = 0;
			
		if($photocount == $setting->dis_columns){
			$photocount = 1;
		} else {
			$photocount++;
		}
		
	}
	echo "\t\t\t\t\t\t\t\t\t\t\n</tr>";
echo "\t\t\t\t\t\t\t\t\t\n</table>";
	
	
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