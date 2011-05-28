	<table border="0" callspacing="3" width="100%">
			<tr>
	<?
	  
		$show_photos = $setting->featured;
		
		$replace_char = array(chr(13).chr(10), "%20", "+", "'", "\"",);
		
		unset($_SESSION['imagenav']);
	
		$gallery_result = mysql_query("SELECT id FROM photo_galleries where active = '1' and pub_pri = '0' and free = '0' and monthly = '0' and yearly = '0'", $db);
		while($gallery = mysql_fetch_object($gallery_result)){
			$approved_cats[] = $gallery->id;
		}
		
		//$id_nums = array(1,6,12,18,24);

		$approved_cats = implode(", ", $approved_cats);
		
		$tallest = 10;
		
		$package_result = mysql_query("SELECT id,title,description,gallery_id,code FROM photo_package where gallery_id IN ($approved_cats) and active = '1' and featured = '1' and photog_show = '1' order by rand() limit $show_photos", $db);
		//$package_rows = mysql_num_rows($package_result);
		while($package = mysql_fetch_object($package_result)){
					
			$photos_result = mysql_query("SELECT id,filename FROM uploaded_images where reference = 'photo_package' and reference_id = '$package->id' order by original", $db);
			$photos_rows = mysql_num_rows($photos_result);
			$photos = mysql_fetch_object($photos_result);			
				
			// Rating system added for PS320
			if($setting->rate_on == 1){
			$rating_display_result = mysql_query("SELECT total_value,total_votes FROM ratings where id = '$package->id'", $db);
			$rate_display = mysql_fetch_object($rating_display_result);
			if($rate_display->total_value){
			$current_rating = $rate_display->total_value;
			$vote_count = $rate_display->total_votes;
			if($setting->rate_on == 1 && $setting->member_rate != 1){
			$rate_it = $featured_rating . round($current_rating/$vote_count, 2);
			} else {
			if($setting->rate_on == 1 && $setting->member_rate == 1 && $_SESSION['sub_member'] > 0){
			$rate_it = $featured_rating . round($current_rating/$vote_count, 2);
				}
			}
			} else {
			if($setting->rate_on == 1 && $setting->member_rate != 1){
			$rate_it = $featured_rate_member;
			} else {
			if($setting->rate_on == 1 && $setting->member_rate == 1 && $_SESSION['sub_member'] > 0){
				$rate_it = $featured_rate_member;
				}
			}
				}
			}
			
			if(file_exists($stock_photo_path . "i_" . $photos->filename)){
				$photo_height = getimagesize($stock_photo_path . "i_" . $photos->filename);
				// Modified for PS320 to allow changing of hover view sizes
					$display = getimagesize($stock_photo_path . "s_" . $photos->filename);
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
	  			// End of hover view size changing code
				
				$photo_height = $photo_height[1];
				$new_description = $package->description;
			}
			
			if($photos_rows > 0){
				$ids[] = $package->id;
				$ids3[] = $package->gallery_id;
				$ids2[] = $photos->id;
				$ids4[] = $photos->filename;
				$ids5[] = $package->title;
				$ids6[] = $package->code;
				$tall[] = $photo_height;
			if(strlen($new_description) > $setting->description_length){
				$trim_scription = substr($new_description, 0, $setting->description_length) . "...";
			} else {
					$trim_scription = $new_description;
			}
			$trim_scription = str_replace($replace_char, " ", $trim_scription);
				$ids41[] = $trim_scription;
				$ids81[] = $height;
				$ids71[] = $width;	
				$ids91[] = $rate_it;
			} else {
				$ids[] = "ns";
				$ids2[] = "ns";
				$tall[] = $photo_height;
			}
		}
?>
			</tr>
			
				<?php
					
					$tall_count = count($tall);
					$i = 0;
					$x = 0;
					$z = 0;
					$total = $setting->dis_columns;
					while($i < $tall_count){
						while($x < $total){
							$taller[] = $tall[$x];
							$x++;
						}
						
						$mymax = max($taller);
						
						while($z < $total){
							$tall[$z] = $mymax;
							$z++;
						}
						
						unset($taller);
						
						$x = $total;
						$z = $total;
						$total = $total + 4;							
						$i++;
						$tallest = 0;
						//echo $x . "<br />";
					}
								
					$mycount = 1;
					if($photos_rows > 0){
						
						foreach($ids as $key => $value){
							if($mycount == 1){
								echo "<tr>";
							}
							$title2 = str_replace($replace_char, " ", $ids5[$key]);
				?>
								<td valign="top" width="<?php echo round(100/$setting->dis_columns); ?>%">
									<table width="100px">
										<tr>
											<td align="center" valign="middle" height="<? echo $setting->thumb_width + 15; ?>" bgcolor="#F9F9F9" style="border: 1px solid #eeeeee; padding: 5px 0px 5px 0px;">
												
                    <?php
                    			$sample_video_path = "./" . $setting->sample_dir . "/";
                    			
													if($ids2[$key] != "ns"){
														$filename = strip_ext($ids4[$key]);
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
														mod_photolink($value,$ids3[$key],$title2,"","photo_links");
													?>
													<img src="<? echo $imagepage . $ids2[$key]; ?>" <? if($setting->hover_on == 1 && !$_SESSION['visitor_hover']){ ?><? if($setting->show_watermark_hover == 1){ ?> onmouseover="trailOn('hover_mark.php?i=<? echo $ids2[$key]; ?>','<? echo $title2; ?>','<? echo $ids41[$key]; ?>','','','','1','<? echo $ids71[$key]; ?>','<? echo $ids81[$key]; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } else { ?> onmouseover="trailOn('image_pop.php?src=<? echo $ids2[$key]; ?>','<? echo $title2; ?>','<? echo $ids41[$key]; ?>','','','','1','<? echo $ids71[$key]; ?>','<? echo $ids81[$key]; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } ?> onmouseout="hidetrail();" <? } ?> class="photos" border="0"></a><br>
												<?php
													} else {
														echo "<img src=\"images/no_photo.gif\" border=\"0\">";
													}
												?>
												<br>
											</td>
										</tr>
										<tr>
											<td align="center" valign="middle" bgcolor="#F9F9F9" style="border: 1px solid #eeeeee; padding:4px;">
											<? if($value != "ns"){ ?>

												
                                                <?php
													mod_photolink($value,$ids3[$key],$title2,"","photo_links");
												?>
                                                <?PHP echo $featured_details; ?></a><font color="#A4A4A4"><?php if($setting->dis_title_featured == 1){ ?><? if($setting->dis_filename == 1){ echo "<br>" . $ids4[$key]; } else { if(trim($ids5[$key]) != ""){ echo "<br>" . $ids5[$key]; } }?><? } ?><? if($setting->show_views == 1){ ?><br><? echo $featured_viewed . $ids6[$key]; } if($setting->sr_featured == 1){ ?><? echo $ids91[$key]; } ?>

										<? 
										if($_SESSION['sub_member']){
												$lightbox1_result = mysql_query("SELECT id FROM lightbox where member_id = '" . $_SESSION['sub_member'] . "' and reference_id = '" . $_SESSION['lightbox_id'] . "' and photo_id = '$ids[$key]'", $db);
												$lightbox1_rows = mysql_num_rows($lightbox1_result);
												$lightbox1 = mysql_fetch_object($lightbox1_result);
												if($lightbox1_rows > 0){ 
										?>
															<br /><a href="public_actions.php?pmode=remove_lightbox&lid=<? echo $lightbox1->id; ?>&cur=<? echo $cur_page; ?>" class="photo_links"><img src="images/sm_rem_lightbox.gif" border="0" alt="<?PHP echo $featured_alt_remlightbox; ?>"></a>
										<? } else { ?>
															<br /><a href="public_actions.php?pmode=add_lightbox&ptype=d&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $value; ?>&cur=<? echo $cur_page; ?>" class="photo_links"><img src="images/sm_add_lightbox.gif" border="0" alt="<?PHP echo $featured_alt_addlightbox; ?>"></a>
										<? } }?>
											<? } else { ?>
												<a href="#" class="photo_links"><?PHP echo $featured_details; ?></a><?php if($setting->dis_title_featured == 1){ ?><? if($setting->dis_filename == 1){ echo "<br>" . $ids4[$key]; } else { if($id5[$key] != ""){ echo "<br>" . $ids5[$key]; } }?><? } ?><? if($setting->show_views == 1){ ?><br><? echo $featured_viewed . $ids6[$key]; } ?>
											<? 
										if($_SESSION['sub_member']){
												$lightbox1_result = mysql_query("SELECT id FROM lightbox where member_id = '" . $_SESSION['sub_member'] . "' and reference_id = '" . $_SESSION['lightbox_id'] . "' and photo_id = '$ids[$key]'", $db);
												$lightbox1_rows = mysql_num_rows($lightbox1_result);
												$lightbox1 = mysql_fetch_object($lightbox1_result);
												if($lightbox1_rows > 0){ 
										?>
															<br /><a href="public_actions.php?pmode=remove_lightbox&lid=<? echo $lightbox1->id; ?>&cur=<? echo $cur_page; ?>" class="photo_links"><img src="images/sm_rem_lightbox.gif" border="0" alt="<?PHP echo $featured_alt_remlightbox; ?>"></a>
										<? } else { ?>
															<br /><a href="public_actions.php?pmode=add_lightbox&ptype=d&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $value; ?>&cur=<? echo $cur_page; ?>" class="photo_links"><img src="images/sm_add_lightbox.gif" border="0" alt="<?PHP echo $featured_alt_addlightbox; ?>"></a>
										<? } }?>
											<? } ?>
											<!-- | -->
											<?
												if($_SESSION['sub_member']){
											?>
												<!--<a href="download_file.php?pid=<? echo $package->id; ?>" class="photo_links">Download</a>-->
											<?
												} else {
											?>
												<!--<a href="public_actions.php?pmode=add_cart&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $package->id; ?>" class="photo_links">Add To Cart</a>-->
											<?
												}
											?>
											</td>
										</tr>
									</table>
								</td>
				<?php
							if($mycount >= $setting->dis_columns){
								$mycount = 1;
								echo "</tr>";
							} else {							
								$mycount++;
							}
							unset($sample_video_path);
						}
					}
				?>
				
			</tr>
		</table>
        <?php
            if(file_exists('rss.php')){
                include('rss.php');
                if($featured_feed){
        ?>
            	<div align="right" style="padding: 5px;"><a href="rss.php?show=featured"><img src="images/rss.gif" border="0" /></a></div>
        <?php
                }
            }
        ?>
