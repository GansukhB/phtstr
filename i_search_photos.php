<?php  if($setting->flashthumbs == 1){ ?><script type="text/javascript" src="js/swfobject.js"></script><?php } ?>
<div class="g-header bg">
                	<div class="left">
                    	<ul class="tabs">
                        	<li <?php if($_GET['sort_by'] == 'date' || !isset($_GET['sort_by'])) echo 'class="active"' ?> >
                            <a href="<?php if($_GET['sort_by'] == 'date' || !isset($_GET['sort_by'])) echo selfURL(); else echo selfURL().'&sort_by=date'?>"><?php echo $homepage_newest; ?></a>
                          </li>
                          <li <?php if($_GET['sort_by'] == 'popular') echo ' class="active"';?> >
                            <a href="<?php if($_GET['sort_by'] == 'popular') echo selfURL(); else echo selfURL().'&sort_by=popular'?>"><?php echo  $homepage_popular; ?></a>
                          </li>
                          <li <?php if($_GET['sort_by'] == 'random') echo ' class="active"';?>>
                            <a href="<?php if($_GET['sort_by'] == 'random') echo selfURL(); else echo selfURL().'&sort_by=random'?>">Random view</a>
                          </li>
                        </ul>
                    </div>
                    <div class="right">
                    	<div class="left">
                        <!--
                        	Pages: <div class="page">1</div>
                        </div>
                        <div class="left">
                        	All Pages 1500:  <a class="next" href="#"></a><a class="prev" href="#"></a>
                          -->
                          
					<?php echo "<b>" . $gallery_page . "</b>"; ?> 
                    <select style="font-size: 11px" id="page" onChange="location.href=document.getElementById('page').options[document.getElementById('page').selectedIndex].value">
						<?php
                            for($x=1;$x<=$pages;$x++){
                                $selected = ($x == $page_num) ? "selected" : "";
                                if($setting->modrw){
									echo "<option value=\"gallery_" . $curgal . "_m" . $_GET['gid'] . "-sb_" . $sort_by . "-so_" . $sort_order . "-page" . $x . ".html\" $selected>$x</option>";
								} else {
									echo "<option value=\"gallery.php?gid=$gid&page_num=$x&sort_by=$sort_by&sort_order=$sort_order\" $selected>$x</option>";
								}
                            }
                        ?>										
                    </select>
                     <?php echo " | "; ?> <?php echo "<b>" . $pages . "</b> " . $gallery_pages . " | "; ?> (<strong><?php echo $package_rows; ?></strong> <?php echo $gallery_photo; ?>)
                     |
                     <span style="font-weight: bold; color: #CCCCCC">
					<?php
                    	if($page_num > 1){
							if($setting->modrw){
                    ?>
                    		<a href="gallery_<?php echo $curgal; ?>_m<?php echo $_GET['gid']; ?>-sb_<?php echo $sort_by; ?>-so_<?php echo $sort_order; ?>-page<?php echo ($page_num - 1); ?>.html">
                    	<?php
							} else {
						?>
                        	<a class="prev" href="gallery.php?gid=<?php echo $gid; ?>&page_num=<?php echo ($page_num-1); ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>">
						<?php
							}
						?>
                    		<?php //echo $gallery_previous; ?></a>
                    <?php
						} else {
							//echo "$gallery_previous"; 
						}
						echo " : ";
						if($page_num != $pages){
							if($setting->modrw){
                    ?>
                        	<a href="gallery_<?php echo $curgal; ?>_m<?php echo $_GET['gid']; ?>-sb_<?php echo $sort_by; ?>-so_<?php echo $sort_order; ?>-page<?php echo ($page_num + 1); ?>.html">
						<?php
							} else {
						?>
                        	<a class="next" href="gallery.php?gid=<?php echo $gid; ?>&page_num=<?php echo ($page_num+1); ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>">
                        <?php
							}
						?>
							<?php //echo $gallery_next; ?></a>
                    <?php
						} else {
							//echo "$gallery_next";
						}
                    ?>
                    
                    </strong>
				
			 <!--end of div naviga tion -->
                        </div>
                    </div>
                </div>
	<?
	if($_GET['startat'] == "") {
		$startat = 0;
	} else {
		$startat = $_GET['startat'];
	}
	if($_GET['perpage'] == "") {
		$perpage = $setting->perpage;
	} else {
		$perpage = $_GET['perpage'];
	}	
	
	$line = $startat;
	$templine = 0;
	$recordnum = 0;
	$total_views = 0;
	$count_total = 0;
	
	$photocount=1;
	if($setting->private_search == 0){
		$extrasql = " AND pub_pri = '0'";
	} else {
		$extrasql = "";
	}
	if($_GET['gid_search'] != ""){
	$id = $_GET['gid_search'];
	$gallery_result = mysql_query("SELECT id FROM photo_galleries WHERE active = '1' AND free = '0' AND monthly = '0' AND yearly = '0'" . $extrasql . " AND id = '$id'", $db);
	$gallery_rows = mysql_num_rows($gallery_result);
	while($gallery = mysql_fetch_object($gallery_result)){
		$approved_cats[] = $gallery->id;
	}
	} else {
	$gallery_result = mysql_query("SELECT id FROM photo_galleries WHERE active = '1' AND free = '0' AND monthly = '0' AND yearly = '0'" . $extrasql . " ", $db);
	$gallery_rows = mysql_num_rows($gallery_result);
	while($gallery = mysql_fetch_object($gallery_result)){
		$approved_cats[] = $gallery->id;
	}
}
	
	$approved_cats = implode(", ", $approved_cats);
	
	# START SEARCH FUNCTIONALITY
	$my_search = strtolower($my_search);
	
	$my_search_words = split(" ",$my_search);
	$words = count($my_search_words);
	
	for($z = 0; $z < $words; $z++){
		if(strlen($my_search_words[$z]) >= 1){
			$my_search_words2 = $my_search_words2 . "," . strtolower($my_search_words[$z]);
		}
	}
	
	$my_search_words2 = split(",",$my_search_words2);
	$words2 = count($my_search_words2);

	if($match_type == "id"){
		$searcher = "SELECT * FROM photo_package where active = '1' and photog_show = '1' and gallery_id IN ($approved_cats) and id = '" . $_GET['search'] . "'";
	}
 if($match_type != "id" && $match_type != "exact"){
	if($words2 < 2){
		$searcher = "SELECT * FROM photo_package where active = '1' and photog_show = '1' and gallery_id IN ($approved_cats) and keywords like '%$my_search%'";
	} else {
		$searcher = "SELECT * FROM photo_package where active = '1' and photog_show = '1' and gallery_id IN ($approved_cats) and (";
		for($z2 = 1; $z2 < $words2; $z2++){
			$searcher.= " keywords like '%" . $my_search_words2[$z2] . "%'";
			if($z2 < ($words2 - 1)){
				if($match_type == "any"){
					$searcher.= " or ";
				}
				if($match_type == "all"){
					$searcher.= " and ";
				}
			}
		}
		if($words2 > 0){
			$searcher.= " or";
		}
		for($z3 = 1; $z3 < $words2; $z3++){
			$searcher.= " title like '%" . $my_search_words2[$z3] . "%'";
			if($z3 < ($words2 - 1)){
				if($match_type == "any"){
					$searcher.= " or ";
				}
				if($match_type == "all"){
					$searcher.= " and ";
				}
			}
		}
		if($words2 > 0){
			$searcher.= " or";
		}
		for($z3 = 1; $z3 < $words2; $z3++){
			$searcher.= " description like '%" . $my_search_words2[$z3] . "%'";
			if($z3 < ($words2 - 1)){
				if($match_type == "any"){
					$searcher.= " or ";
				}
				if($match_type == "all"){
					$searcher.= " and ";
				}
			}
		}
	}
	$search_display_limit = $setting->search;
	$searcher.= ") order by cart_count desc LIMIT $search_display_limit";
}

	if($match_type == "exact"){
	$searcher = "SELECT id,title,keywords,description,gallery_id FROM photo_package WHERE active = '1' AND photog_show = '1' AND gallery_id IN ($approved_cats) AND (keywords like '%$my_search%' OR description like '%$my_search%' OR title like '%$my_search%')";
	$search_display_limit = $setting->search;
	$searcher.= " order by cart_count desc LIMIT $search_display_limit";
	}
  
  
  //echo $_POST['category'];
	if($search_form_method == 'post'){
    $types = $_POST['type'];
    //print_r($types);
    $orient = $_POST['orient'];
    //print_r($orient);
    $category = $_POST['category'];
    $keywords = explode(" ", $_POST['keyword']);  
    $contributor = addslashes($_POST['contributor']);
    
    $g_query = "select * from photographers where name like '%$contributor%'";
    $g_result = mysql_query($g_query);
    $g_list = array();
    
    while($g = mysql_fetch_object($g_result))
    {
      array_push($g_list, $g->id);
    }
    $g_list = implode(",", $g_list);
    if($contributor == '')
      $g_list .= ", 0";
    
    //echo $category.' '.$keywords.' '.$contributor;
    
    $searcher = " SELECT * FROM photo_package WHERE ";
    if($category != "0")
    {
      $searcher .= " gallery_id = '$category' AND ";
    }
    if(count($types) == 1)
    {
      if($types[0] == 'photo')
      {
        $searcher .= " gallery_id != 54 AND ";
      }
      else $searcher .= " gallery_id = 54 AND ";  
    }
    /*
    if(count($orient) == 1)
    {
      if($orient[0] == 'vertical')
      {
        $searcher .= " height >= width AND ";
      }
      else $searcher .= " height >= width AND ";  
    }*/
    foreach($orient as $or)
    {
      if($or == 'vertical')
        $searcher .= " height >= width AND ";
      elseif($or == 'horizontal')
        $searcher .= " height <= width  AND "; 
      else
        $searcher .= " width >= height*2 AND ";
    }
    if(count($keywords))
    foreach($keywords as $word)
    {
      $searcher .= " keywords like '%$word%' AND";
    }
    if(count($g_list))
    {
      $searcher .= " user_uploaded in ($g_list) AND";
    }
    
    $searcher .= " 1=1";
  }
  //echo $searcher;
  
	$package_result = mysql_query($searcher, $db);
	$package_rows = mysql_num_rows($package_result);
	
	if($match_type == "exact"){
	while($package = mysql_fetch_object($package_result)){
//ADDED IN PS350 TO DO PRIVATE GALLERY SEARCHES
	$gal_result = mysql_query("SELECT id,pub_pri,rdmcode,password FROM photo_galleries where id = '$package->gallery_id'", $db);
	$pgal = mysql_fetch_object($gal_result);
	session_register("gal");
	$_SESSION['gal'] = $pgal->rdmcode;	
	if($pgal->pub_pri == 0 or $pgal->password and $_SESSION['galaccess'] == $_SESSION['gal']){
//END OF PRIVATE SEARCH
  	$search_array = explode(",",$package->keywords);
  	$seek = $_GET['search'];
  	foreach($search_array as $key => $value){
  		$seeker = trim($search_array[$key]);
  		if($seeker == $seek){
  			$match++;
  		} else {
  			// Do not match anything
  		}
  	}
  	if($match > 0){
  	$id = $package->id;
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
			$rate_it = $search_gal_rating . round($current_rating/$vote_count, 2);
			} else {
			if($setting->rate_on == 1 && $setting->member_rate == 1 && $_SESSION['sub_member'] > 0){
			$rate_it = $search_gal_rating . round($current_rating/$vote_count, 2);
				}
			}
			} else {
			if($setting->rate_on == 1 && $setting->member_rate != 1){
			$rate_it = $search_gal_rate_member;
			} else {
			if($setting->rate_on == 1 && $setting->member_rate == 1 && $_SESSION['sub_member'] > 0){
				$rate_it = $search_gal_rate_member;
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
			$no_upload_message.= "ERROR:23 Failed to display a photo in the search result photo gallery \n";
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
			
			if($templine < $perpage and $line < $package_rows) {
				if($line == $recordnum) {
					$line++;
					$templine++;
	?>
				<?
					if($photocount == 1){
				?>
				<?
					} else {
				?>
				
				<?
					}
				?>					
        	    
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
									$imagepage = "image.php?gal_size=".$_SESSION['gal_size']."&src=";
								}
								
							if(!file_exists("swf/photoloader.swf") or $setting->flashthumbs == 0 or $_SESSION['visitor_flash'] == 1){                            
							mod_photolink($package->id,$package->gallery_id,$title2,"","");
						?>
							<img src="<? echo $imagepage . $photo->id; ?>" <? if($setting->hover_on == 1 && !$_SESSION['visitor_hover']){ ?><? if($setting->show_watermark_hover == 1){ ?> onmouseover="trailOn('hover_mark.php?i=<? echo $photo->id; ?>','<? echo $title2; ?>','<? echo $trim_scription; ?>','','','','1','<? echo $width; ?>','<? echo $height; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } else { ?> onmouseover="trailOn('image_pop.php?src=<? echo $photo->id; ?>','<? echo $title2; ?>','<? echo $trim_scription; ?>','','','','1','<? echo $width; ?>','<? echo $height; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } ?> onmouseout="hidetrail();" <? } ?> class="photos" border="0"></a>
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
						<? if($photo_rows > 0){ ?>
						<?php
							mod_photolink($package->id,$package->gallery_id,$title2,"","photo_links");
						?>
						<?PHP echo $gallery_details; ?></a><?php if($setting->dis_title_gallery == 1){ ?><? if($setting->dis_filename == 1){ echo  $photo->filename; } else { if(trim($package->title) != ""){ echo  $package->title; } }?><? } ?><?php if($setting->show_views == "1"){ ?><?PHP echo $gallery_viewed; ?><? echo $clicks; ?><? } if($setting->sr_gallery == 1){ ?><? echo $rate_it; } } else { ?><a href="#" class="photo_links"><?PHP echo $gallery_details; ?></a><? } ?>
						<? 
										if($_SESSION['sub_member']){
												$lightbox1_result = mysql_query("SELECT id FROM lightbox where member_id = '" . $_SESSION['sub_member'] . "' and reference_id = '" . $_SESSION['lightbox_id'] . "' and photo_id = '$package->id'", $db);
												$lightbox1_rows = mysql_num_rows($lightbox1_result);
												$lightbox1 = mysql_fetch_object($lightbox1_result);
												if($lightbox1_rows > 0){ 
										?>
															<a href="public_actions.php?pmode=remove_lightbox&lid=<? echo $lightbox1->id; ?>&cur=<? echo $cur_page; ?>" class="photo_links"><img src="images/sm_rem_lightbox.gif" border="0" alt="<?PHP echo $search_gal_alt_remlightbox; ?>"></a>
										<? } else { ?>
															<a href="public_actions.php?pmode=add_lightbox&ptype=d&gid=<? echo $package->gallery_id; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $package->id; ?>&cur=<? echo $cur_page; ?>" class="photo_links"><img src="images/sm_add_lightbox.gif" border="0" alt="<?PHP echo $search_gal_alt_addlightbox; ?>"></a>
										<? } }?>
						<!--|
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
	<?
					if($photocount == $setting->dis_columns){
						$photocount = 1;
					} else {
						$photocount++;
					}
				}
			$count_total = $count_total + $fcontents;	
			}
			unset($search_array);
			unset($id);
			unset($seek);
			unset($match);
			unset($sample_video_path);
			$recordnum++;
			}
			} else {
				if($templine < $perpage and $line < $package_rows) {
				if($line == $recordnum) {
					$line++;
					$templine++;
				if($photocount == 1){
				?>
				<?PHP
					} else {
				?>
				
				<?PHP
					}
				?>
				<?PHP
				echo "<a href=\"pri.php?gid=" . $pgal->id . "&gal=" . $pgal->rdmcode . "&pid=" . $package->id . "\"><img src=\"images/private_photo.gif\" border=\"0\" alt=\"" . $search_gal_alt_private . "\" title=\"" . $search_gal_alt_private . "\"></a>";
				?>
				<?PHP
				if($photocount == $setting->dis_columns){
						$photocount = 1;
					} else {
						$photocount++;
					}
				}
				$count_total = $count_total + $fcontents;
			}
				unset($_SESSION['gal']);
				$recordnum++;
			}
			
		}
	} else {
    
		while($package = mysql_fetch_object($package_result)){
		//ADDED IN PS350 FOR PRIVATE GALLERY SEARCH	
			$gal_result = mysql_query("SELECT id,pub_pri,rdmcode,password FROM photo_galleries where id = '$package->gallery_id'", $db);
			$pgal = mysql_fetch_object($gal_result);
			session_register("gal");
			$_SESSION['gal'] = $pgal->rdmcode;	
			if($pgal->pub_pri == 0 or $pgal->password and $_SESSION['galaccess'] == $_SESSION['gal']){
		//END OF PRIVATE SEARCH
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
			$rate_it = $search_gal_rating . round($current_rating/$vote_count, 2);
			} else {
			if($setting->rate_on == 1 && $setting->member_rate == 1 && $_SESSION['sub_member'] > 0){
			$rate_it = $search_gal_rating . round($current_rating/$vote_count, 2);
				}
			}
			} else {
			if($setting->rate_on == 1 && $setting->member_rate != 1){
			$rate_it = $search_gal_rate_member;
			} else {
			if($setting->rate_on == 1 && $setting->member_rate == 1 && $_SESSION['sub_member'] > 0){
				$rate_it = $search_gal_rate_member;
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
			$no_upload_message.= "ERROR:23 Failed to display a photo in the search result photo gallery \n";
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
			
			if($templine < $perpage and $line < $package_rows) {
				if($line == $recordnum) {
					$line++;
					$templine++;
	?>
				<?
					if($photocount == 1){
				?>
				<?
					} else {
				?>
				
				<?
					}
				?>
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
									$imagepage = "image.php?gal_size=".$_SESSION['gal_size']."&src=";
								}
								
								if(!file_exists("swf/photoloader.swf") or $setting->flashthumbs == 0 or $_SESSION['visitor_flash'] == 1){                            
								//mod_photolink($package->id,$package->gallery_id,$title2,"","");
						?>
            
            <div class="galery-content">
                	<div align="center" class="image">
                    <!--
                    <a href="#">-->
                    <?php
                      mod_photolink($package->id,$package->gallery_id,$title2,"","photo_links"); ?>
                      <img src="<? echo $imagepage . $photo->id; ?>" <? if($setting->hover_on == 1 && !$_SESSION['visitor_hover']){ ?><? if($setting->show_watermark_hover == 1){ ?> onmouseover="trailOn('hover_mark.php?i=<? echo $photo->id; ?>','<? echo $title2; ?>','<? echo $trim_scription; ?>','','','','1','<? echo $width; ?>','<? echo $height; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } else { ?> onmouseover="trailOn('image_pop.php?src=<? echo $photo->id; ?>','<? echo $title2; ?>','<? echo $trim_scription; ?>','','','','1','<? echo $width; ?>','<? echo $height; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } ?> onmouseout="hidetrail();" <? } ?> class="photos" border="0" class="images">
                    </a>
                    
                    </div>
                    <div class="image-hover-main" style="display: none;">
                    	<div align="center" class="image-hover">
                          
                        	<img src="<? echo $imagepage . $photo->id; ?>" <? if($setting->hover_on == 1 && !$_SESSION['visitor_hover']){ ?><? if($setting->show_watermark_hover == 1){ ?> onmouseover="trailOn('hover_mark.php?i=<? echo $photo->id; ?>','<? echo $title2; ?>','<? echo $trim_scription; ?>','','','','1','<? echo $width; ?>','<? echo $height; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } else { ?> onmouseover="trailOn('image_pop.php?src=<? echo $photo->id; ?>','<? echo $title2; ?>','<? echo $trim_scription; ?>','','','','1','<? echo $width; ?>','<? echo $height; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } ?> onmouseout="hidetrail();" <? } ?> class="photos" border="0">
                          
                        </div>
                    </div>
                    <div align="center" class="title"><?php echo $package->title; ?></div>
                    <div class="descreption">
                    	<div align="right" class="left"><img src="images/icon-show1.png"></div>
                        <div align="left" class="right"><img src="images/icon-show2.png"></div>
                    </div>
                </div>
                <!--
							<img src="<? echo $imagepage . $photo->id; ?>" <? if($setting->hover_on == 1 && !$_SESSION['visitor_hover']){ ?><? if($setting->show_watermark_hover == 1){ ?> onmouseover="trailOn('hover_mark.php?i=<? echo $photo->id; ?>','<? echo $title2; ?>','<? echo $trim_scription; ?>','','','','1','<? echo $width; ?>','<? echo $height; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } else { ?> onmouseover="trailOn('image_pop.php?src=<? echo $photo->id; ?>','<? echo $title2; ?>','<? echo $trim_scription; ?>','','','','1','<? echo $width; ?>','<? echo $height; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } ?> onmouseout="hidetrail();" <? } ?> class="photos" border="0"></a>
              -->
              
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
						<? if($photo_rows > 0){ ?>
						<?php
							//mod_photolink($package->id,$package->gallery_id,$title2,"","photo_links");
						?>
            <!--
						<?PHP echo $gallery_details; ?></a><?php if($setting->dis_title_gallery == 1){ ?><? if($setting->dis_filename == 1){ echo  $photo->filename; } else { if(trim($package->title) != ""){ echo  $package->title; } }?><? } ?><?php if($setting->show_views == "1"){ ?><?PHP echo $gallery_viewed; ?><? echo $clicks; ?><? } if($setting->sr_gallery == 1){ ?><? echo $rate_it; } } else { ?><a href="#" class="photo_links"><?PHP echo $gallery_details; ?></a><? } ?>
						
            <? 
										if($_SESSION['sub_member']){
												$lightbox1_result = mysql_query("SELECT id FROM lightbox where member_id = '" . $_SESSION['sub_member'] . "' and reference_id = '" . $_SESSION['lightbox_id'] . "' and photo_id = '$package->id'", $db);
												$lightbox1_rows = mysql_num_rows($lightbox1_result);
												$lightbox1 = mysql_fetch_object($lightbox1_result);
												if($lightbox1_rows > 0){ 
										?>
															<a href="public_actions.php?pmode=remove_lightbox&lid=<? echo $lightbox1->id; ?>&cur=<? echo $cur_page; ?>" class="photo_links"><img src="images/sm_rem_lightbox.gif" border="0" alt="<?PHP echo $search_gal_alt_remlightbox; ?>"></a>
										<? } else { ?>
															<a href="public_actions.php?pmode=add_lightbox&ptype=d&gid=<? echo $package->gallery_id; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $package->id; ?>&cur=<? echo $cur_page; ?>" class="photo_links"><img src="images/sm_add_lightbox.gif" border="0" alt="<?PHP echo $search_gal_alt_addlightbox; ?>"></a>
										<? } }?>-->
						<!--|
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
					
	<?
					if($photocount == $setting->dis_columns){
						$photocount = 1;
					} else {
						$photocount++;
					}
				}
			$count_total = $count_total + $fcontents;
			}
			unset($sample_video_path);
		$recordnum++;
		} else {
				if($templine < $perpage and $line < $package_rows) {
				if($line == $recordnum) {
					$line++;
					$templine++;
				if($photocount == 1){
				?>
				<?PHP
					} else {
				?>
				
				<?PHP
					}
				?>
				<?PHP
				echo "<a href=\"pri.php?gid=" . $pgal->id . "&gal=" . $pgal->rdmcode . "&pid=" . $package->id . "\"><img src=\"images/private_photo.gif\" border=\"0\" alt=\"" . $search_gal_alt_private . "\" title=\"" . $search_gal_alt_private . "\"></a>";
				?>
				<?PHP
				if($photocount == $setting->dis_columns){
						$photocount = 1;
					} else {
						$photocount++;
					}
				}
				$count_total = $count_total + $fcontents;
			}
				unset($_SESSION['gal']);
				$recordnum++;
			}	
		}
	}
		
		// END OF PHOTO SEARCH AND ONTO THE NUMBER OF PHOTO RESULTS AND NUMBER OF PAGES
		if($recordnum > 0){
			$result_pages = ceil($recordnum/$perpage);
			
			if($_GET['page_num'] == ""){
				$page_num = 1;
			} else {
				$page_num = $_GET['page_num'];
			}
			
			if($result_pages < 1){
				$result_pages = 1;
			}
	?>
	
		<div name="result_details" id="result_details" style="padding-left: 10px;padding-right: 10px;padding-bottom: 10px;padding-top: 30px;width: 100%; clear: both;">
			<?php echo "<b>" . $search_gal_page . "</b>"; ?> 
            <select style="font-size: 11px" id="page" onChange="location.href=document.getElementById('page').options[document.getElementById('page').selectedIndex].value">
                <?php
					$page_startat = 0;
                    for($x=1;$x<=$result_pages;$x++){
                        $selected = ($x == $page_num) ? "selected" : "";
							// search.php?search=test&match_type=all
                        echo "<option value=\"search.php?search=" . $my_search . "&match_type=" . $match_type . "&gid_search=" . $_GET['gid_search'] . "&gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "&startat=" . $page_startat . "&perpage=" . $perpage . "&page_num=" . $x . "\" $selected>$x</option>";
						$page_startat = $page_startat + $perpage;
                    }
                ?>										
            </select>
             <?php echo " | "; ?> <?php echo "<b>" . $result_pages . "</b> " . $search_gal_pages. " | "; ?> (<strong><?php echo $package_rows; ?></strong> <?php echo $search_gal_photo; ?>)
             |
             <span style="font-weight: bold; color: #CCCCCC">
           <?
				if($startat == 0){
			?>
				<?PHP echo $search_gal_previous; ?>
			<?
				}
				else{
					if(($startat - $perpage) < 1) {
			?>
						<a href="<?php print($PHP_SELF . "?search=" . $my_search . "&match_type=" . $match_type . "&gid_search=" . $_GET['gid_search'] . "&gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "&startat=0&perpage=" . $perpage . "&page_num=" . ($page_num-1) . "&sort_by=" . $sort_by); ?>"><?PHP echo $search_gal_previous; ?></a>
			<?
					} else {
			?>
						<a href="<?php print($PHP_SELF . "?search=" . $my_search . "&match_type=" . $match_type . "&gid_search=" . $_GET['gid_search'] . "&gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "&startat=" . ($startat - $perpage)); ?>&perpage=<?php print($perpage) . "&page_num=" . ($page_num-1) . "&sort_by=" . $sort_by; ?>"><?PHP echo $search_gal_previous; ?></a>
			<?
					}
				}
			?>
			 |
			<?
			
				if(($startat + $perpage) >= $recordnum){
			?>
				<?PHP echo $search_gal_next; ?>
			<?
				}
				else{
					if($line < $recordnum) {
			?>
					<a href="<?php print($PHP_SELF . "?search=" . $my_search . "&match_type=" . $match_type . "&gid_search=" . $_GET['gid_search'] . "&gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "&startat=" . ($startat + $perpage)); ?>&perpage=<?php print($perpage) . "&page_num=" . ($page_num+1) . "&sort_by=" . $sort_by; ?>"><?PHP echo $search_gal_next; ?></a>
			<?
					} else {
			?>
					<a href="<?php print($PHP_SELF . "?search=" . $my_search . "&match_type=" . $match_type . "&gid_search=" . $_GET['gid_search'] . "&gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "&startat=" . $startat); ?>&perpage=<?php print($perpage) . "&page_num=" . ($page_num+1) . "&sort_by=" . $sort_by; ?>"><?PHP echo $search_gal_next; ?></a>
			<?
					}
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
				echo "<hr size=\"1\" class=\"hr\"><span style=\"padding-left: 8px;\"><b><font color=\"#333333\">" . $search_gal_sub_cat . $current_gallery_name . "</b></span>";
			}		
			while($sub_gallery = mysql_fetch_object($sub_gallery_result)){
	?>
				<span style="padding-left: 8px;"><a href="search.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $sub_gallery->id; ?>" class="sub_gallery_nav"><? echo $sub_gallery->title; ?></a></span>
	<?
			}		
			if($recordnum < 1 and $sub_gallery_rows < 1){
				echo "<span style=\"padding-left: 8px;\">" . $search_gal_no_photo . "</span>";	
			}
		}
	?>
