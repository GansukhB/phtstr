	<?
	// Stacked Details 298 Kmods
	
		$currency_result = mysql_query("SELECT sign FROM currency where active = '1'", $db);
		$currency = mysql_fetch_object($currency_result);
	
		$package_result = mysql_query("SELECT id,gallery_id,title,description,keywords,active,photographer,act_download,all_sizes,sizes,update_29,all_prints,prod,code,photog_show FROM photo_package where id = '" . $_GET['pid'] . "'", $db);
		//$package_rows = mysql_num_rows($package_result);
		$package = mysql_fetch_object($package_result);
    // Check to see if the image is active to be viewed
    if($package->active != 1 or $package->photog_show != 1){
    	echo $detail_inactive;
    	exit;
    }
    
		$gal_result = mysql_query("SELECT pub_pri,rdmcode,title FROM photo_galleries where id = '$package->gallery_id'", $db);
		//$gal_rows = mysql_num_rows($gal_result);
		$gal = mysql_fetch_object($gal_result);
		
		unset($_SESSION['downl']);
		if($_SESSION['sub_member']){
			$member_download_results = mysql_query("SELECT sub_length FROM members where id = '" . $_SESSION['sub_member'] . "'", $db);
			$member_download = mysql_fetch_object($member_download_results);
		}
		
		//IF GALLERY IS PUBLIC RECORD THIS PAGE IF THEY HAVE TO LEAVE TO GO LOGIN
		unset($_SESSION['pub_gid']);
		unset($_SESSION['pub_pid']);
		session_register("pub_gid");
		$_SESSION['pub_gid'] = $_GET['gid'];
		session_register("pub_pid");
		$_SESSION['pub_pid'] = $_GET['pid'];
		
		if($gal->pub_pri){
			 if($gal->rdmcode == $_SESSION['galaccess']){
				//echo "private ok";
			} else {
				echo $detail_private_cat;
			session_register("pri_gid");
			$_SESSION['pri_gid'] = $_GET['gid'];
			session_register("pri_pid");
			$_SESSION['pri_pid'] = $_GET['pid'];
			//echo "<br><b><a href=\"pri.php?gal=" . $gal->rdmcode . "\">". $detail_login . "</a>" . $detail_login2 . "</b>";
				//echo "<br />" . $_SESSION['galaccess'];
				//echo "<br />" . $gal->rdmcode;
				exit;
			}			
		}
		
		
		$photo_result = mysql_query("SELECT id,filename,price,original FROM uploaded_images where reference = 'photo_package' and reference_id = '$package->id' order by original", $db);
		//$photo_rows = mysql_num_rows($photo_result);
		$photo = mysql_fetch_object($photo_result);
		if($photo->price != ""){
			$price = $photo->price;	
			} else {
			$price = $setting->default_price;	
		}
	if(file_exists($stock_photo_path . $photo->filename)){
		$check_size = getimagesize($stock_photo_path . $photo->filename);
	}
	if(file_exists($stock_photo_path . "m_" . $photo->filename)){
		$photo_size1 = getimagesize($stock_photo_path . "m_" . $photo->filename);
		$scroll = "no";
	} else {
		$photo_size1[0] = 800;
		$photo_size1[1] = 800;
		$scroll = "yes";
	}

			//$keywords = str_replace(" ", "", $package->keywords);
			$keywords = $package->keywords;
			$keywords = split(",",$keywords);
			$keyword_length = count($keywords);
			
			if(!file_exists("swf/thumbslide.swf") or $_SESSION['visitor_flash'] == 1 or $setting->flash_thumb_on != 1){
			// Start of Previous | Next buttons for gallery viewing
			$images = $_SESSION['imagenav'];
			$image = split(",",$images);
			$ptr = $package->id;
			
			// Current image id being viewed out of the image array
			reset($image);
			while ($start = current($image)) {
   			if ($start == $ptr) {
      		$ptr1 = current($image);
      		break;
   			}
   			next($image);
  		}
  		
  		// Next image id to be viewed
  		reset($image);
  		while ($nex = current($image)) {
   			if ($nex == $ptr) {
      		$next = next($image);
      		break;
   			}
   			next($image);
  		}
   
      // Previous image id to be viewed
      reset($image);
      while ($pre = current($image)) {
   			if ($pre == $ptr) {
      		$prev = prev($image);
      		break;
   			}
   			next($image);
  		}
  		
  		// End of Previous | Next buttons creation code
  		}
      
      $default_size = $setting->sample_width;
                
                    $srcfilename = "./".$setting->photo_dir."/s_".$photo->filename;
                    
                      $imageInfo = getimagesize($srcfilename);
                    if($imageInfo[0] >= $imageInfo[1]){
				
                      if($imageInfo[0] > $default_size){
                        $width = $default_size;
                      } else {
                        $width = $imageInfo[0];
                      }
                      $ratio = $width/$imageInfo[0];
                      $height = $imageInfo[1] * $ratio;				
                      
                    } else {
                      
                      if($imageInfo[1] > $default_size){
                        $height = $default_size;	
                      } else {
                        $height = $imageInfo[1];	
                      }
                            
                      $ratio = $height/$imageInfo[1];
                      $width = $imageInfo[0] * $ratio;
                                    
                    }
                    
                    $width+=2;
                    $height--;
		?>
  

<!--r-left-main ehlel-->
<div class="r-left-main" style="width: 510px;">
    <!--photo ehlel-->
      <div class="photo" align="center" style="overflow:hidden; align:center;">
        <div style="height: <?php echo $height; ?>; width: <?php echo $width; ?>; overflow: hidden; text-align:center;" >
          <img src="watermark.php?i=<?php echo $photo->id ?>" > </div>
        
        <!--<img src="images/image3.jpg">-->
      </div>
      
      
      <div align=center>
        <?php 
        		 if((file_exists($stock_photo_path . "m_" . $photo->filename) && $setting->show_preview == 1) && !$flvsample){ ?>
          <a href = "javascript:NewWindow('enlarge.php?i=<? echo $photo->id; ?>','LargeView','<? echo $photo_size1[0]; ?>','<? echo $photo_size1[1]; ?>','0','<? echo $scroll; ?>');"><img src="./images/cte.gif" class="photos" alt="<?PHP echo $detail_alt_cte; ?>" title="<?PHP echo $detail_alt_cte; ?>"></a><?php } ?>
					<? 
					if($setting->allow_subs_month == 1 or $setting->allow_subs == 1 or $setting->allow_sub_free == 1){
										if($_SESSION['sub_member']){
												//$lightbox1_result = mysql_query("SELECT id FROM lightbox where member_id = '" . $_SESSION['sub_member'] . "' and reference_id = '" . $_SESSION['lightbox_id'] . "' and photo_id = '$pid'", $db);
                        $lightbox1_result = mysql_query("SELECT id FROM lightbox where member_id = '" . $_SESSION['sub_member'] . "' and photo_id = '$pid'", $db);
												$lightbox1_rows = mysql_num_rows($lightbox1_result);
												$lightbox1 = mysql_fetch_object($lightbox1_result);
												if($lightbox1_rows > 0){ 
										?>
															<a href="public_actions.php?pmode=remove_lightbox&lid=<? echo $lightbox1->id; ?>" class="photo_links"><img src="images/rem_lightbox.gif" class="photos" alt="<? echo $detail_alt_remlightbox; ?>" title="<? echo $detail_alt_remlightbox; ?>"></a>
										<? } else { ?>
                            <!--
															<a href="public_actions.php?pmode=add_lightbox&ptype=d&gid=<? echo $gid; ?>&sgid=<? echo $sgid; ?>&pid=<? echo $pid; ?>" class="photo_links"><img src="images/add_lightbox.gif" class="photos" alt="<? echo $detail_alt_addlightbox; ?>" title="<? echo $detail_alt_addlightbox; ?>"></a>
                            -->
                            <a class="icon" onclick="javascript:show_light();" >
                              <img src="images/add_lightbox.gif" class="photos" alt="<? echo $detail_alt_addlightbox; ?>" title="<? echo $detail_alt_addlightbox; ?>">
                            </a>
                            
										<? } 
										} else { 
											?>
											<a href="login.php" class="photo_links"><img src="images/add_lightbox.gif" class="photos" alt="<? echo $detail_alt_addlightbox; ?>" title="<? echo $detail_alt_addlightbox; ?>"></a>
										<?
									}
									if($setting->allow_subs_month == 1 or $setting->allow_subs == 1){
										if($_SESSION['sub_member'] == ""){
										?>
										<a href="login.php" class="photo_links"><img src="images/download.gif" class="photos" alt="<? echo $detail_alt_download; ?>" title="<? echo $detail_alt_download; ?>"></a>
									<? 
									    }
									    if($_SESSION['sub_type'] == "free" && $price != 0){
										?>
										<a href="renew_full.php" class="photo_links"><img src="images/download.gif" class="photos" alt="<? echo $detail_alt_download; ?>" title="<? echo $detail_alt_download; ?>"></a>
									<? 
									    }
									  }
								if($_SESSION['sub_member']){
								?>
								<a href="javascript:NewWindow('email_photo.php?pid=<? echo $package->id; ?>','Email','500','300','0','0');" class="photo_links"><img src="images/email.gif" class="photos" alt="<? echo $detail_alt_email; ?>" title="<? echo $detail_alt_email; ?>"></a>
					    <? } else { ?>
					      <a href="login.php" class="photo_links"><img src="images/email.gif" class="photos" alt="<? echo $detail_alt_email; ?>" title="<? echo $detail_alt_email; ?>"></a>
					    <? 
					    	}  
					    }
#end of buttons
        ?>
        
<!-- AddThis Button BEGIN -->
<a class="addthis_button_compact"><img src="images/share-icon.png"></a>

<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4de827332cfbe4db"></script>
<!-- AddThis Button END -->        <br />
                            
                              <ul id="popup" style="backround:#e0e0e0; display:none;" onmouseout="hide_light(link);">
                                <li style="display:inline;">Add to lightbox:</li>
                                <?php 
                                  $lightbox_group_result_copy = mysql_query("SELECT id,name FROM lightbox_group WHERE member_id = '" . $_SESSION['sub_member'] . "'", $db);
                                  while($item = mysql_fetch_object($lightbox_group_result_copy) ):
                                ?>
                                  <li style="display:inline;"><a href="public_actions.php?pmode=add_lightbox&ptype=d&gid=<? echo $gid; ?>&sgid=<? echo $sgid; ?>&pid=<? echo $pid; ?>&lb_id=<?php echo $item->id;?>" >
                                    <?php echo $item->name; ?></a></li>
                                <?php endwhile; ?>
                              </ul>
                            <br />
      <?php
          if($_SESSION['imagenav'] && $prev or $next){
					 	if($prev){
							$prev = trim($prev);
							$p_result = mysql_query("SELECT title FROM photo_package WHERE id = '$prev'", $db);
							$prevname = mysql_fetch_object($p_result);
							
							mod_photolink($prev,$_GET['gid'],$prevname->title,"","");
              echo "$detail_previous</a>";
						?>
					<? } else { ?>
						<?PHP echo $detail_previous; ?>
					<? } ?> | 
					<? if($next){
							$next = trim($next);
							$n_result = mysql_query("SELECT title FROM photo_package WHERE id = '$next'", $db);
							$nextname = mysql_fetch_object($n_result);
							mod_photolink($next,$_GET['gid'],$nextname->title,"","");
              echo $detail_next."</a>";
						?>
 
          <? }} ?> 
          
          
          
        </div> 
        <!--
  <div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=106830472742387&amp;xfbml=1"></script><fb:like href="<?php echo selfURL; ?>" send="true" width="450" show_faces="true" font=""></fb:like>      
        -->
      <?
				if(0 && $setting->rate_on == 1 && $setting->member_rate != 1){
					 echo $detail_rating_text;
				   echo rating_bar($package->id,'10','false');
				  } else {
				   if($setting->member_rate == 1 && $setting->rate_on == 1 && $_SESSION['sub_member'] > 0){
				   	echo $detail_rating_text;
				   	echo rating_bar($package->id,'10','false');
				  }
				}
					?>
          <?php
          if($setting->allow_digital == 1 & $package->act_download == 1){
							
							echo "<tr><td class=\"photo_details\" bgcolor=\"#F9F9F9\" style=\"border: 1px solid #eeeeee; padding: 10px;\">";
							echo "<div align=\"center\" style=\"background-color: #eeeeee; margin-bottom: 10px; padding: 3px;\">" . $detail_digital . "</div>";
							
							$x = 1;
							$photo1_result = mysql_query("SELECT * FROM uploaded_images where reference = 'photo_package' and reference_id = '$package->id' order by quality_order", $db);
							$photo1_rows = mysql_num_rows($photo1_result);
							while($photo1 = mysql_fetch_object($photo1_result)){
								
								$filename = strip_ext($photo1->filename);
								$check = "./files/" . $filename . ".zip";
								
								$movie_name = array($filename . ".mov", $filename . ".avi", $filename . ".mpg", $filename . ".flv", $filename . ".wmv");
								foreach($movie_name as $key => $value){
									if(is_file($stock_video_path . $movie_name[$key])){
									$mov_name = $movie_name[$key];
									$check_mov = $stock_video_path . $mov_name;
									$ext_mov = substr($mov_name, -3);
									$ext_mov = strtoupper($ext_mov);
									}
								}
								$sample_name = array($filename . ".mov", $filename . ".avi", $filename . ".mpg", $filename . ".flv", $filename . ".wmv");
								foreach($sample_name as $key => $value){
									if(is_file($sample_video_path . $sample_name[$key])){
									$sam_name = $sample_name[$key];
									$check_sam = $sample_video_path . $sam_name;
									$ext_sam = substr($sam_name, -3);
									$ext_sam = strtoupper($ext_sam);
									}
								}
		
								if(file_exists($check)){
								$file_size = filesize($check);
								$file_size = $file_size/1048576;
								$file_size = round($file_size,2);
								}
								if(file_exists($check_mov)){
								$file_size_mov = filesize($check_mov);
								$file_size_mov = $file_size_mov/1048576;
								$file_size_mov = round($file_size_mov,2);
								}
								if(file_exists($check_sam)){
								$file_size_sam = filesize($check_sam);
								$file_size_sam = $file_size_sam/1048576;
								$file_size_sam = round($file_size_sam,2);
								}
		
								$photo_size = getimagesize($stock_photo_path . $photo1->filename);
								$photo_file_size = filesize($stock_photo_path . $photo1->filename);
								$photo_file_size = $photo_file_size/1048576;
								$photo_file_size = round($photo_file_size,2);
								
								$photo_path = $stock_photo_path . "s_" . $photo1->filename; 
								if(file_exists($check)){
									if($photo1->quality != ""){
									echo $detail_quality . $detail_zip_1 . $photo1->quality . "<br>";
									} else {
									echo $detail_quality . $detail_zip_2;
									}
									echo $detail_filesize . $file_size . $detail_mb;
									if(file_exists($check_sam)){
											if($flvsample){
												echo "<p align=\"right\" style='margin: 0px; padding: 0px;'><b><a href=\"view_flv.php?width=". $setting->sample_size ."&file=" . $flvsample . "\" target=\"blank\"><img src=\"images/viewsample.gif\" border=\"0\" style=\"border: 1px solid #a9a9a9;\" /></a></b></p>";
											} else {
												echo "<p align=\"right\" style='margin: 0px; padding: 0px;'><b><a href=\"download_video.php?id=" . $photo1->id . "\" target=\"blank\"><img src=\"images/viewsample.gif\" border=\"0\" style=\"border: 1px solid #a9a9a9;\" /></a></b></p>";
											}
										}
								} else {
									if(file_exists($check_mov)){
										if($photo1->quality != ""){
										echo $detail_quality . $ext_mov . $detail_file_1 . $photo1->quality . "<br>";
										} else {
										echo $detail_quality . $ext_mov . $detail_file_2;
										}
										echo $detail_filesize . $file_size_mov . $detail_mb;
										if(file_exists($check_sam)){
											if($flvsample){
												echo "<p align=\"right\" style='margin: 0px; padding: 0px;'><b><a href=\"view_flv.php?width=". $setting->sample_size ."&file=" . $flvsample . "\" target=\"blank\"><img src=\"images/viewsample.gif\" border=\"0\" style=\"border: 1px solid #a9a9a9;\" /></a></b></p>";
											} else {
												echo "<p align=\"right\" style='margin: 0px; padding: 0px;'><b><a href=\"download_video.php?id=" . $photo1->id . "\" target=\"blank\"><img src=\"images/viewsample.gif\" border=\"0\" style=\"border: 1px solid #a9a9a9;\" /></a></b></p>";
											}
										}
									} else {
									if($photo1->quality != ""){
										echo $detail_quality . $detail_jpg_1 . $photo1->quality . "<br>";
									} else {
										echo $detail_quality . $detail_jpg_2;
									}
									echo $detail_dimension . $photo_size[0] . $detail_jpg_px . $photo_size[1] . $detail_jpg_px_end; 
									echo $detail_filesize . $photo_file_size . $detail_mb;
									if(file_exists($check_sam)){
											if($flvsample){
												echo "<p align=\"right\" style='margin: 0px; padding: 0px;'><b><a href=\"view_flv.php?width=". $setting->sample_size ."&file=" . $flvsample . "\" target=\"blank\"><img src=\"images/viewsample.gif\" border=\"0\" style=\"border: 1px solid #a9a9a9;\" /></a></b></p>";
											} else {
												echo "<p align=\"right\" style='margin: 0px; padding: 0px;'><b><a href=\"download_video.php?id=" . $photo1->id . "\" target=\"blank\"><img src=\"images/viewsample.gif\" border=\"0\" style=\"border: 1px solid #a9a9a9;\" /></a></b></p>";
											}
										}
									}
								}
									echo "<font color=\"#ff0000\">" . $detail_price;						
									if($photo1->price_contact){
										if($_SESSION['sub_member'] && $package->photographer == "0" && $member_download->sub_length != "F" && $setting->allow_contact_download == 1){
										echo $detail_free_download;
										?>
										<span style="float: right"><a href = "javascript:NewWindow('downl.php?pid=<? echo $photo1->id; ?>','Download','500','100','0','0');"><img src="images/downloadbut.gif" border="0" style="border: 1px solid #a9a9a9;" /></a></span>
										<?php } else {
										echo "<a href=\"support.php?pid=" . $_GET['pid']  . "\">" . $detail_contact . "</a>";
										}
									} else {
										if($photo1->price != ""){
											$price = $photo1->price;	
										} else {
											$price = $setting->default_price;	
										}
										if($price == 0){
											echo $detail_free_download;
							?>
											<span style="float: right"><a href="download_file2.php?pid=<? echo $photo1->id; ?>" class="photo_links"><img src="images/downloadbut.gif" border="0" style="border: 1px solid #a9a9a9;" /></a></span>
							<?
										} else {	
											echo $currency->sign . $price;
										}
									
									echo "</font><br>";
									
									if($_SESSION['sub_member'] && $package->photographer == "0" && $member_download->sub_length != "F" && $price != 0){
						?>
										<span style="float: right"><a href = "javascript:NewWindow('downl.php?pid=<? echo $photo1->id; ?>','Download','500','100','0','0');"><img src="images/downloadbut.gif" border="0" style="border: 1px solid #a9a9a9;" /></a></span>
						<?
									} else if($price != 0) {
						?>
										<span style="float: right"> <a href="public_actions.php?pmode=add_cart&ptype=d&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $photo1->id; ?>" class="photo_links"><img src="images/addtocart.gif" border="0" style="border: 1px solid #a9a9a9;" /></a></span>
						<?
									}
								}
									
									echo "<hr color=\"#eeeeee\" size=\"1\"><br>";
									unset($check);
									unset($check_mov);
									unset($check_sam);
									unset($price);
							}
							echo "</td></tr>";
						} ?>
            <?php 
					$clicks = $package->code;
					$clicks++;
		
					$sql = "UPDATE photo_package SET code='$clicks' where id = '" . $_GET['pid'] . "'";
					$result = mysql_query($sql);
							
							if($setting->show_views == "1"){
							
								echo "<tr><td class=\"photo_details\">";
								echo $detail_viewed;
								echo $clicks;
								echo $detail_times;
								echo "</td></tr>";
								
							}
						
					?>
      <!--photo tugsgul-->
      <!--price-main ehlel-->
      <div class="price-main">
        <div class="header"><strong>ҮНЭИЙН САНАЛ</strong> (Хувь хүнд)</div>
          <div class="p-content">
            <div class="p-main">
                <div class="t1" align="center"></div>
                  <div class="t2"></div>
                  <div class="t3"></div>
                  <div class="t4"></div>
                  <div class="t5"></div>
                  <div class="t6"></div>
                  <div class="t7">нэг удаа хэрэглэх</div>
                  <div class="t8">Royalty free(1 jil)</div>
                  <div class="t9"></div>
              </div>
          </div>
<div class="p-content">
            <div class="p-main">
                <div class="t1" align="center">A</div>
                  <div class="t2">SMALL</div>
                  <div class="t3">480x320</div>
                  <div class="t4">JPEG</div>
                  <div class="t5">72dpi</div>
                  <div class="t6">1</div>
                  <div class="t7">15.000 MNT</div>
                  <div class="t8">75.000 MNT</div>
                  <div class="t9"><a href="#">DOWNLOAD</a></div>
              </div>
              <div class="p-main">
                <div class="t1" align="center">B</div>
                  <div class="t2">MEDIUM</div>
                  <div class="t3">480x320</div>
                  <div class="t4">JPEG</div>
                  <div class="t5">72dpi</div>
                  <div class="t6">1</div>
                  <div class="t7">15.000 MNT</div>
                  <div class="t8">75.000 MNT</div>
                  <div class="t9"><a href="#">DOWNLOAD</a></div>
              </div>
              <div class="p-main">
                <div class="t1" align="center">C</div>
                  <div class="t2">LARGE</div>
                  <div class="t3">480x320</div>
                  <div class="t4">JPEG</div>
                  <div class="t5">72dpi</div>
                  <div class="t6">1</div>
                  <div class="t7">15.000 MNT</div>
                  <div class="t8">75.000 MNT</div>
                  <div class="t9"><a href="#">DOWNLOAD</a></div>
              </div>
              <div class="p-main">
                <div class="t1" align="center">D</div>
                  <div class="t2">SUPER</div>
                  <div class="t3">480x320</div>
                  <div class="t4">JPEG</div>
                  <div class="t5">72dpi</div>
                  <div class="t6">1</div>
                  <div class="t7">15.000 MNT</div>
                  <div class="t8">75.000 MNT</div>
                  <div class="t9"><a href="#">DOWNLOAD</a></div>
              </div>
          </div>
          <div class="p-content">
            <div class="p-main">
                <div class="t1" align="center">A</div>
                  <div class="t2">SMALL</div>
                  <div class="t3">480x320</div>
                  <div class="t4">JPEG</div>
                  <div class="t5">72dpi</div>
                  <div class="t6">1</div>
                  <div class="t7 yellow">тохиролцох</div>
                  <div class="t8">NA</div>
                  <div class="t9"><a href="#">DOWNLOAD</a></div>
              </div>
           </div>
      </div>
      <!--price-main tugsgul-->
      <!--
      <div class="price-main">
        <div class="header"><strong>ҮНЭИЙН САНАЛ</strong> (Вектор)</div>
          <div class="p-content">
            <div class="p-main">
                <div class="t1" align="center"></div>
                  <div class="t2"></div>
                  <div class="t3"></div>
                  <div class="t4"></div>
                  <div class="t5"></div>
                  <div class="t6"></div>
                  <div class="t7">нэг удаа хэрэглэх</div>
                  <div class="t8">Royalty free(1 jil)</div>
                  <div class="t9"></div>
              </div>
          </div>
<div class="p-content">
            <div class="p-main">
                <div class="t1" align="center">A</div>
                  <div class="t2">SMALL</div>
                  <div class="t3">480x320</div>
                  <div class="t4">JPEG</div>
                  <div class="t5">72dpi</div>
                  <div class="t6">1</div>
                  <div class="t7">15.000 MNT</div>
                  <div class="t8">75.000 MNT</div>
                  <div class="t9"><a href="#">DOWNLOAD</a></div>
              </div>
              <div class="p-main">
                <div class="t1" align="center">B</div>
                  <div class="t2">MEDIUM</div>
                  <div class="t3">480x320</div>
                  <div class="t4">JPEG</div>
                  <div class="t5">72dpi</div>
                  <div class="t6">1</div>
                  <div class="t7">15.000 MNT</div>
                  <div class="t8">75.000 MNT</div>
                  <div class="t9"><a href="#">DOWNLOAD</a></div>
              </div>
              <div class="p-main">
                <div class="t1" align="center">C</div>
                  <div class="t2">LARGE</div>
                  <div class="t3">480x320</div>
                  <div class="t4">JPEG</div>
                  <div class="t5">72dpi</div>
                  <div class="t6">1</div>
                  <div class="t7">15.000 MNT</div>
                  <div class="t8">75.000 MNT</div>
                  <div class="t9"><a href="#">DOWNLOAD</a></div>
              </div>
              <div class="p-main">
                <div class="t1" align="center">D</div>
                  <div class="t2">SUPER</div>
                  <div class="t3">480x320</div>
                  <div class="t4">JPEG</div>
                  <div class="t5">72dpi</div>
                  <div class="t6">1</div>
                  <div class="t7">15.000 MNT</div>
                  <div class="t8">75.000 MNT</div>
                  <div class="t9"><a href="#">DOWNLOAD</a></div>
              </div>
          </div>
          <div class="p-content">
            <div class="p-main">
                <div class="t1" align="center">A</div>
                  <div class="t2">SMALL</div>
                  <div class="t3">480x320</div>
                  <div class="t4">JPEG</div>
                  <div class="t5">72dpi</div>
                  <div class="t6">1</div>
                  <div class="t7 yellow">тохиролцох</div>
                  <div class="t8">NA</div>
                  <div class="t9"><a href="#">DOWNLOAD</a></div>
              </div>
           </div>
      </div>
      -->
  
      <div class="price-main">
        <div class="header"><strong>Similar named photos</strong></div>
        <?php 
          $photoname = strtolower($package->title);
          $photowords = explode(" ", $photoname);
          
          
          $similar_named = array();
          
          $is_added = array();
          foreach($photowords as $word)
          {
            $searcher = "SELECT * FROM photo_package where active = '1' and photog_show = '1' and  title like '%$word%'";
            $result = mysql_query($searcher);
            
            while($photo = mysql_fetch_object($result) )
            {
              if(!$is_added[$photo->id])
              {
                array_push($similar_named, $photo);
                $is_added[$photo->id] = true;
              }
            }
            
          }
          
        ?>
        <ul style="float: left;">
          <?php 
            $limit = 10;
            $count = 0;
            foreach($similar_named as $photo):
              if($count == $limit)
                break;
              if($photo->id != $package->id): 
          ?>
              <li><a href="details.php?gid=<?php echo $photo->gallery_id; ?>&pid=<?php echo $photo->id; ?>"><?php 
                $title = $photo->title;
                
                echo $title;
              ?></a></li>
          <?php 
                $count++;
              endif; 
            endforeach; 
          ?>
        </ul>
      </div>
  
  </div>
  <!--r-left-main tugsgul-->
  <!--r-right-main ehlel-->
  <div class="r-right-main">
    <!--r-right-content ehlel-->
    <div class="r-right-content">
       <div class="id">Stock photo:<br />
         <div class="title">
          <?php 
            
              if($setting->dis_filename == 1){
                echo $photo->filename;
                
              } 
              else {
                echo $package->title;
              }
          ?>
          </div>
        </div><br />
          
          <div class="id"><?PHP echo $detail_photo_id; ?><strong> <?php echo $package->id; ?></strong></div>
          <div class="id">Release information: <strong>N/A</strong></div>
          <div class="id">Copyright: <strong>
            <?php
              $photog = get_photographer_by_pkg($package->id);
              echo "<a href=\"#\">". $photog[1] ."</a>";
              echo "<br /><a href=\"view_photog.php?photogid=" . $photog[0] . "\">" . $detail_photog_link . "</a><br>"
            ?>
          </strong></div>
          
         
      
					<div class="id"><?PHP echo $detail_gallery_id; ?><?php echo $package->gallery_id; ?> <strong><a href="gallery.php?gid=<?php echo $package->gallery_id; ?>"><?php echo $gal->title; ?></a></strong></div>
          
          
          <?php
          
          
          
           
						if($package->photographer && trim($package->photographer) != ""){
							echo "<tr><td class=\"photo_details\">";
							echo $detail_photographer;
							if($package->photographer == "999999999"){
								echo $setting->site_title;
							} else {
								$ptgm_result = mysql_query("SELECT name,notes,id FROM photographers where id = '$package->photographer'", $db);
								$ptgm = mysql_fetch_object($ptgm_result);
								
								echo $ptgm->name;
								echo " | <a href=\"view_photog.php?photogid=" . $ptgm->id . "\">" . $detail_photog_link . "</a><br>";
								echo $ptgm->notes;
							}
							
							echo "</td></tr>";
						}
					?>
          <?php  
						if($package->description && trim($package->description) != ""){
							
							echo $detail_description;
							echo $package->description;
							
						}
					?>
      </div>
      <!--r-right-content tugsgul-->
      <!--r-right-content ehlel-->
      <div class="r-right-content">
        <p><?php echo $detail_keyword; ?></p>
          <?php
            if($package->keywords && trim($package->keywords) != ""){
							
							for($keyx = 0; $keyx < $keyword_length; $keyx++){
								if($keyx != 0){
									echo ", ";
								}
								echo "<a href=\"search.php?search=" . $keywords[$keyx] . "\">" . $keywords[$keyx] . "</a>";
							}
						}
					?>
      </div>
      <!--r-right-content tugsgul-->
      <!--r-right-content ehlel-->
      <?php include("i_similar.php");?>
      <!--r-right-content tugsgul-->
  </div>
  <!--r-right-main tugsgul-->

