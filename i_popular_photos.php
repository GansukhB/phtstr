<?php  if($setting->flashthumbs == 1){ ?><script type="text/javascript" src="js/swfobject.js"></script><?php } ?>
<table border="0" callspacing="3" width="100%">
	<tr>
	<?
	$perpage = $setting->perpage;
	if($_GET['page_num']){												
		$page_num = $_GET['page_num'];
	} else {
		$page_num = 1;
	}
	# CALCULATE THE STARTING RECORD						
	$startrecord = ($page_num == 1) ? 0 : (($page_num - 1) * $perpage);	

	
	$photocount=1;
	$pop_display_limit = $setting->popular;
	$gallery_result = mysql_query("SELECT id FROM photo_galleries where active = '1' and pub_pri = '0' and free = '0' and monthly = '0' and yearly = '0'", $db);
	while($gallery = mysql_fetch_object($gallery_result)){
		$approved_cats[] = $gallery->id;
	}
	
	$approved_cats = implode(", ", $approved_cats);
	
	$package_rows = mysql_result(mysql_query("SELECT COUNT(id) FROM photo_package where active = '1' and photog_show = '1' and gallery_id IN ($approved_cats)"),0);
	if($package_rows > $pop_display_limit){
		$package_rows = $pop_display_limit;
	}
	
	$pages = ceil($package_rows/$perpage);
	if($pages == $page_num){
		$shownpages = $pages;
		$shownpages = $shownpages - 1;
		$shown = $shownpages * $perpage;
		$needshown =  $pop_display_limit - $shown;
		$perpage = $needshown;
	}
	$package_result = mysql_query("SELECT id,title,description,gallery_id,code FROM photo_package where active = '1' and photog_show = '1' and gallery_id IN ($approved_cats) order by code desc LIMIT $startrecord,$perpage", $db);
	while($package = mysql_fetch_object($package_result)){

		$photo_result = mysql_query("SELECT id,filename FROM uploaded_images where reference = 'photo_package' and reference_id = '$package->id' order by original", $db);
		$photo_rows = mysql_num_rows($photo_result);
		$photo = mysql_fetch_object($photo_result);
		
		// Rating system added for PS320
			if($setting->rate_on == 1){
			$rating_display_result = mysql_query("SELECT total_value,total_votes FROM ratings where id = '$package->id'", $db);
			$rate_display = mysql_fetch_object($rating_display_result);
			if($rate_display->total_value){
			$current_rating = $rate_display->total_value;
			$vote_count = $rate_display->total_votes;
			if($setting->rate_on == 1 && $setting->member_rate != 1){
			$rate_it = $popular_gal_rating . round($current_rating/$vote_count, 2);
			} else {
			if($setting->rate_on == 1 && $setting->member_rate == 1 && $_SESSION['sub_member'] > 0){
			$rate_it = $popular_gal_rating . round($current_rating/$vote_count, 2);
				}
			}
			} else {
			if($setting->rate_on == 1 && $setting->member_rate != 1){
			$rate_it = $popular_gal_rate_member;
			} else {
			if($setting->rate_on == 1 && $setting->member_rate == 1 && $_SESSION['sub_member'] > 0){
				$rate_it = $popular_gal_rate_member;
				}
			}
				}
			}
			
		# Modified for PS320 to allow changing of hover view sizes
		if(file_exists($stock_photo_path . "s_" . $photo->filename)){
			$display = getimagesize($stock_photo_path . "s_" . $photo->filename);
			$default_size = $setting->hover_size;
			if($display[0] >= $display[1]){
				if($display[0] > $default_size){
					$width = $default_size;
				} else {
					$width = $display[0];
				}
				$ratio = $width/$display[0];
				$height = $display[1] * $ratio;				
			} else {
				if($display[1] > $default_size){
					$height = $default_size;	
				} else {
					$height = $display[1];	
				}		
				$ratio = $height/$display[1];
				$width = $display[0] * $ratio;
			}
		} else {
			$to = $setting->support_email;
			$no_upload_message = "There was an issue with your photostore not being able to display a photo at: \n " . $_SESSION['url'] . " \n";
			$no_upload_message.= "Chances are this is usually caused by a photo that failed to upload correctly. \n";
			$no_upload_message.= "Usually this is caused by low resources on the server or your php settings are not sufficient to upload the photo. \n";
			$no_upload_message.= "To correct and remove this error you will need to log into your store manager and delete the photo that is missing but has a record in the database. \n";
			$no_upload_message.= "You will find it in the photos area of your store manager, select the gallery the error is showing in and look for the thumbnail that doesn't have a preview and delete it. \n";
			$no_upload_message.= "-----------ERROR INFO------------\n";
			$no_upload_message.= "ERROR:20 Failed to display a photo in the most popular photo gallery \n";
			$no_upload_message.= "Photo Info \n";
			$no_upload_message.= "Photo Package ID: " . $package->id . " \n";
			$no_upload_message.= "Photo Package Title: " . $package->title . " \n";
			$no_upload_message.= "Photo Package Description: " . $package->description . " \n";
			$no_upload_message.= "Photo Package Category: " . $package->gallery_id . " \n";
			$no_upload_message.= "---------------------------------\n";
			mail($to, $setting->site_title . " failed to display a photo (NEEDS FIXED)", $no_upload_message, "From: " . $setting->support_email);
		}
	  // End of hover view size changing code
			
		$new_description = $package->description;
			if(strlen($new_description) > $setting->description_length){
				$trim_scription = substr($new_description, 0, $setting->description_length) . "...";
			} else {
				$trim_scription = $new_description;
			}
		$replace_char = array(chr(13).chr(10), "%20", "+", "'", "\"",);
		$trim_scription = str_replace($replace_char, " ", $trim_scription);
		$title2 = str_replace($replace_char, " ", $package->title);
			
	?>
				<?
					if($photocount == 1){
				?>
					</tr><tr>
				<?
					} else {
				?>
				
				<?
					}
				?>
					<td align="center" valign="middle" bgcolor="#F9F9F9" style="border: 1px solid #eeeeee; padding: 5px 0px 5px 0px;" width="<?php echo round(100/$setting->dis_columns); ?>%">
						<?php
								$sample_video_path = "./" . $setting->sample_dir . "/";
                if($photo_rows > 0){
								$filename = strip_ext($photo->filename);
								if(file_exists($sample_video_path . $filename . ".flv")){
									$flvsample=$filename . ".flv";									
								} else {
									$flvsample="";
								}
								
								// FIX FOR MOVING TO SWF FOLDER
								$sample_video_path = ".$sample_video_path";
							
								if($setting->show_watermark_thumb == 1){
									$imagepage = "thumb_mark.php?i=";
								} else {
									$imagepage = "image.php?src=";
								}
						?>
							<?php
                                if(!file_exists("swf/photoloader.swf") or $setting->flashthumbs == 0 or $_SESSION['visitor_flash'] == 1){                            
									mod_photolink($package->id,$package->gallery_id,$title2,"","");
							?>
									<img src="<? echo $imagepage . $photo->id; ?>" <? if($setting->hover_on == 1 && !$_SESSION['visitor_hover']){ ?><? if($setting->show_watermark_hover == 1){ ?> onmouseover="trailOn('hover_mark.php?i=<? echo $photo->id; ?>','<? echo $title2; ?>','<? echo $trim_scription; ?>','','','','1','<? echo $width; ?>','<? echo $height; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } else { ?> onmouseover="trailOn('image_pop.php?src=<? echo $photo->id; ?>','<? echo $title2; ?>','<? echo $trim_scription; ?>','','','','1','<? echo $width; ?>','<? echo $height; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } ?> onmouseout="hidetrail();" <? } ?> class="photos" border="0"></a><br>
                            <?php
								} else {
									// GET WIDTH AND HEIGHT FOR FLASH FILE
								$default_size = $setting->thumb_width;
								$imageInfo = getimagesize($stock_photo_path . "i_" . $photo->filename);			
								if($imageInfo[0] >= $imageInfo[1]){								
									if($imageInfo[0] > $default_size){
										$width2 = $default_size;
									} else {
										$width2 = $imageInfo[0];
									}
									$ratio = $width2/$imageInfo[0];
									$height2 = $imageInfo[1] * $ratio;							
								} else {
									
									if($imageInfo[1] > $default_size){
										$height2 = $default_size;	
									} else {
										$height2 = $imageInfo[1];	
									}											
									$ratio = $height2/$imageInfo[1];
									$width2 = $imageInfo[0] * $ratio;															
								}						
								//echo $width . "x" . $height;
								//watermark.php?i=<?php echo $photo->id;
							?>
								
								<?php mod_photolink($package->id,$package->gallery_id,$title2,"",""); ?>
								<div id="photoloaddiv<?php echo $photo->id; ?>" style="background-color: #e6e6e6; padding: 4px; width: <?php echo $width2; ?>px; background-image: url(images/img_load.gif); background-repeat: no-repeat;" <? if($setting->hover_on == 1 && !$_SESSION['visitor_hover']){ ?><? if($setting->show_watermark_hover == 1){ ?> onmouseover="trailOn('hover_mark.php?i=<? echo $photo->id; ?>','<? echo $title2; ?>','<? echo $trim_scription; ?>','','','','1','<? echo $width; ?>','<? echo $height; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } else { ?> onmouseover="trailOn('image_pop.php?src=<? echo $photo->id; ?>','<? echo $title2; ?>','<? echo $trim_scription; ?>','','','','1','<? echo $width; ?>','<? echo $height; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } ?> onmouseout="hidetrail();" <? } ?>>
								<?PHP echo $no_flashplayer; ?>
								</div>
								<script>
									<!--
										var flashObj<?php echo $photo->id; ?> = new SWFObject ("swf/<?php echo $setting->flashtrans; ?>", "photoloader", "<?php echo $width2; ?>", "<?php echo $height2; ?>", 8, "#eeeeee", true);
										flashObj<?php echo $photo->id; ?>.addVariable ("myphotopath", "<?PHP echo $imagepage; ?><?php echo $photo->id; ?>");
										flashObj<?php echo $photo->id; ?>.addVariable ("linkpath", "<?php mod_photolink_short($package->id,$package->gallery_id,$title2,"",""); ?>");
										flashObj<?php echo $photo->id; ?>.write ("photoloaddiv<?php echo $photo->id; ?>");
									// -->
								</script>
               	</a>
						<?php
								}
							} else {
								echo "<img src=\"images/no_photo.gif\" border=\"0\">";
							}
						?>
							<br>						
						<font color="#A4A4A4">
						<? if($photo_rows > 0){ ?>
                        <?php
							mod_photolink($package->id,$package->gallery_id,$title2,"","photo_links");
						?>
                       	<?PHP echo $popular_gal_details; ?></a><?php if($setting->dis_title_gallery == 1){ ?><? if($setting->dis_filename == 1){ echo "<br>" . $photo->filename; } else { if(trim($package->title) != ""){ echo "<br>" . $package->title; } }?><? } ?><?php if($setting->show_views == "1"){ ?><br><?PHP echo $popular_gal_viewed; ?> <? echo $package->code; ?><? } if($setting->sr_pop == 1){ ?><? echo $rate_it; } } else { ?><a href="#" class="photo_links"><?PHP echo $popular_gal_details; ?></a><? } ?>
						<? 
										if($_SESSION['sub_member']){
												$lightbox1_result = mysql_query("SELECT id FROM lightbox where member_id = '" . $_SESSION['sub_member'] . "' and reference_id = '" . $_SESSION['lightbox_id'] . "' and photo_id = '$package->id'", $db);
												$lightbox1_rows = mysql_num_rows($lightbox1_result);
												$lightbox1 = mysql_fetch_object($lightbox1_result);
												if($lightbox1_rows > 0){ 
										?>
															<br /><a href="public_actions.php?pmode=remove_lightbox&lid=<? echo $lightbox1->id; ?>&cur=<? echo $cur_page; ?>" class="photo_links"><img src="images/sm_rem_lightbox.gif" border="0" alt="<?PHP echo $popular_gal_alt_remlightbox; ?>"></a>
										<? } else { ?>
															<br /><a href="public_actions.php?pmode=add_lightbox&ptype=d&gid=<? echo $package->gallery_id; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $package->id; ?>&cur=<? echo $cur_page; ?>" class="photo_links"><img src="images/sm_add_lightbox.gif" border="0" alt="<?PHP echo $popular_gal_alt_addlightbox; ?>"></a>
										<? } }?>
						<!--
						|
						<?
							if($_SESSION['sub_member']){
						?>
							<a href="download_file.php?pid=<? echo $photo->id; ?>" class="photo_links">Download</a>
						<?
							} else {
						?>
							<a href="public_actions.php?pmode=add_cart&gid=<? echo $package->gallery_id; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $photo->id; ?>" class="photo_links">Add To Cart</a>
						<?
							}
						?>
						-->
					</td>
	<?
					if($photocount == $setting->dis_columns){
						//echo "<br style=\"clear: both;\">";
						$photocount = 1;
					} else {
						$photocount++;
					}
				unset($sample_video_path);
		}
		if($package_rows > 0){
	?>
		</tr>
		
		<tr>
			<td colspan="4" align="right">
	
			<div name="result_details" id="result_details" style="padding-left: 10px;padding-right: 10px;padding-top: 30px; padding-bottom: 10px; width: 100%;clear: both;">
				<?php echo "<b>" . $popular_gal_page . "</b>"; ?> 
                <select style="font-size: 11px" id="page" onChange="location.href=document.getElementById('page').options[document.getElementById('page').selectedIndex].value">
                    <?php
                        for($x=1;$x<=$pages;$x++){
                            $selected = ($x == $page_num) ? "selected" : "";
                            if($setting->modrw){
                                echo "<option value=\"pop_photos-page" . $x . ".html\" $selected>$x</option>";
                            } else {
                                echo "<option value=\"popular_photos.php?page_num=$x\" $selected>$x</option>";
                            }
                        }
                    ?>										
                </select>
                 <?php echo " | "; ?> <?php echo "<b>" . $pages . "</b> " . $popular_gal_pages . " |"; ?> (<strong><?php echo $package_rows; ?></strong> <?php echo $popular_gal_photo; ?>)
                 |
                 <span style="font-weight: bold; color: #CCCCCC">
                <?php
                    if($page_num > 1){
                        if($setting->modrw){
                ?>
                        <a href="pop_photos-page<?php echo ($page_num - 1); ?>.html">
                    <?php
                        } else {
                    ?>
                        <a href="popular_photos.php?page_num=<?php echo ($page_num-1); ?>">
                    <?php
                        }
                    ?>
                        <?php echo $popular_gal_previous; ?></a>
                <?php
                    } else {
                        echo "$popular_gal_previous"; 
                    }
                    echo " : ";
                    if($page_num != $pages){
                        if($setting->modrw){
                ?>
                        <a href="pop_photos-page<?php echo ($page_num + 1); ?>.html">
                    <?php
                        } else {
                    ?>
                        <a href="popular_photos.php?page_num=<?php echo ($page_num+1); ?>">
                    <?php
                        }
                    ?>
                        <?php echo $popular_gal_next; ?></a>
                <?php
                    } else {
                        echo "$popular_gal_next";
                    }
                ?>
                
                </strong>
			</div>
		<?
			}
			if($_GET['sgid'] == ""){
				$sub_gallery_result = mysql_query("SELECT id,title FROM photo_galleries where active = '1' and nest_under = '" . $_GET['gid'] . "' order by title", $db);
				$sub_gallery_rows = mysql_num_rows($sub_gallery_result);
				if($sub_gallery_rows > 0){
					echo "<hr size=\"1\" class=\"hr\"><span style=\"padding-left: 8px;\"><b><font color=\"#333333\">" . $popular_gal_sub_cat . $current_gallery_name . "</b></span>";
				}		
				while($sub_gallery = mysql_fetch_object($sub_gallery_result)){
		?>
		
					<br><span style="padding-left: 8px;"><a href="popular_photos.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $sub_gallery->id; ?>" class="sub_gallery_nav"><? echo $sub_gallery->title; ?></a></span>
		<?
				}		
				if($package_rows < 1 and $sub_gallery_rows < 1){
					echo "<span style=\"padding-left: 8px;\">" . $popular_gal_no_photo . "</span>";	
				}
			}
		?>
        <?php
			if(file_exists('rss.php')){
				include('rss.php');
				if($popular_feed){
		?>
				<div align="right" style="padding: 5px;"><a href="rss.php?show=popular"><img src="images/rss.gif" border="0" /></a></div>
		<?php
				}
			}
		?>
		</td>
	</tr>
</table>