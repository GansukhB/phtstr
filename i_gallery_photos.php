<?php
	if($setting->flashthumbs == 1){
?>
	<script type="text/javascript" src="js/swfobjectS.js"></script>
<?php
	}
	
	$pages = ceil($package_rows/$perpage);
?>

	<?php
		$photocount=1;
    
  ?>
  
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
              <div class="left" style="margin: 0 0 0 0;">
            
                  
  <?php echo "<b>" . $gallery_page . "</b>"; ?> 
            <select style="margin: 0 0 5px 0 ; font-size: 11px" id="page" onChange="location.href=document.getElementById('page').options[document.getElementById('page').selectedIndex].value">
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
            

                </div>
            </div>
        </div>  
      
               
  <?php
		while($package = mysql_fetch_object($package_result)){
	
			$photo_result = mysql_query("SELECT id,filename FROM uploaded_images where reference = 'photo_package' and reference_id = '$package->id' order by original", $db);
			$photo_rows = mysql_num_rows($photo_result);
			$photo = mysql_fetch_object($photo_result);
			
			# Rating system added for PS320
			if($setting->rate_on == 1){
				$rating_display_result = mysql_query("SELECT total_value,total_votes FROM ratings where id = '$package->id'", $db);
				$rate_display = mysql_fetch_object($rating_display_result);
				if($rate_display->total_value){
					$current_rating = $rate_display->total_value;
					$vote_count = $rate_display->total_votes;
					if($setting->rate_on == 1 && $setting->member_rate != 1){
						$rate_it = $gallery_rating . round($current_rating/$vote_count, 2);
					} else {
						if($setting->rate_on == 1 && $setting->member_rate == 1 && $_SESSION['sub_member'] > 0){
							$rate_it = $gallery_rating . round($current_rating/$vote_count, 2);
						}
					}
				} else {
					if($setting->rate_on == 1 && $setting->member_rate != 1){
						$rate_it = $gallery_rate_member;
					} else {
						if($setting->rate_on == 1 && $setting->member_rate == 1 && $_SESSION['sub_member'] > 0){
							$rate_it = $gallery_rate_member;
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
			$no_upload_message = "There was an issue with your photostore not being able to display a photo at: \n " . $_SESSION['url'] . ". \n";
			$no_upload_message.= "Chances are this is usually caused by a photo that failed to upload correctly. \n";
			$no_upload_message.= "Usually this is caused by low resources on the server or your php settings are not sufficient to upload the photo. \n";
			$no_upload_message.= "To correct and remove this error you will need to log into your store manager and delete the photo that is missing but has a record in the database. \n";
			$no_upload_message.= "You will find it in the photos area of your store manager, select the gallery the error is showing in and look for the thumbnail that doesn't have a preview and delete it. \n";
			$no_upload_message.= "-----------ERROR INFO------------\n";
			$no_upload_message.= "ERROR:10 Failed to display a photo in the public gallery view \n";
			$no_upload_message.= "Photo Info \n";
			$no_upload_message.= "Photo Package ID: " . $package->id . " \n";
			$no_upload_message.= "Photo Package Title: " . $package->title . " \n";
			$no_upload_message.= "Photo Package Description: " . $package->description . " \n";
			$no_upload_message.= "Photo Package Category: " . $gid . " \n";
			$no_upload_message.= "---------------------------------\n";
			mail($to, $setting->site_title . " failed to display a photo (NEEDS FIXED)", $no_upload_message, "From: " . $setting->support_email);
		}
			# End of hover view size changing code
			
			$new_description = $package->description;
			if(strlen($new_description) > $setting->description_length){
				$trim_scription = substr($new_description, 0, $setting->description_length) . "...";
			} else {
				$trim_scription = $new_description;
			}
			$replace_char = array(chr(13).chr(10), "%20", "+", "'", "\"",);
			$trim_scription = str_replace($replace_char, " ", $trim_scription);
			$title2 = str_replace($replace_char, " ", $package->title);
			$clicks = $package->code;
			

		
			?>
      
      
      
		<?php
     $imagepage = "image.php?gal_size=".$_SESSION['gal_size']."&src=";
    ?>
                <div class="galery-content">
                	<div align="center" class="image">
                    
                    <?php
                      mod_photolink($package->id,$package->gallery_id,$title2,"","photo_links"); ?>
                      <img src="<? echo $imagepage . $photo->id; ?>" <? if($setting->hover_on == 1 && !$_SESSION['visitor_hover']){ ?>
                        <? if($setting->show_watermark_hover == 1){ ?> 
                          onmouseover="trailOn('hover_mark.php?i=<? echo $photo->id; ?>','<? echo $title2; ?>','<? echo $trim_scription; ?>','','','','1','<? echo $width; ?>','<? echo $height; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } 
                          else { ?> 
                          onmouseover="trailOn('image_pop.php?src=<? echo $photo->id; ?>','<? echo $title2; ?>','<? echo $trim_scription; ?>','','','','1','<? echo $width; ?>','<? echo $height; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } ?> 
                          onmouseout="hidetrail();" 
                          <? } ?> class="photos" border="0" >
                    </a>
                  </div>
                     
                      <div align="center" class="title">
                        <?php echo $package->title; ?>
                      </div>
                      <div class="descreption">
                        <div align="right" class="left">
                          
                          <a href="public_actions.php?pmode=add_lightbox&ptype=d&gid=<? echo $package->gallery_id; ?>&sgid=<? echo $sgid; ?>&pid=<? echo $package ->id; ?>" class="photo_links">
                          <!--
                          <a onclick="javascript:show_light();" onmouseout="hide_light();">-->
                          <img src="images/icon-show1.png"></a>
                          
                        </div>
                        <div align="left" class="right"><a href="similar.php?gid=<?php echo $package->gallery_id;  ?>&pid=<?php echo $package->id; ?>" ><img src="images/icon-show2.png"></a></div>
                        
                        
                      </div>
                    
                </div>
                            
                          
              
								

                
    
            
    <?
		}
	?>
