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
			echo "<br><b><a href=\"pri.php?gal=" . $gal->rdmcode . "\">". $detail_login . "</a>" . $detail_login2 . "</b>";
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
		?>
	<span>
		<table border="0" width="95%">
			<tr>
				<td valign="top" align="center">	
            
				<span style="padding-right: 5px;  ">
                	<?php
						$filename = strip_ext($photo->filename);
						if(file_exists($sample_video_path . $filename . ".flv")){
							$flvsample="." . $sample_video_path . $filename . ".flv";
							$vidheight = $setting->sample_size*.75;
							
					?>
						<div align="center">
						<object width="<?php echo $setting->sample_size; ?>" height="<?php echo $vidheight; ?>" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0">
						<param name="movie" value="swf/flashsample.swf">
						<param name="quality" value="best">
						<param name="loop" value="true">
						<param name="FlashVars" value="myfile=<?php echo $flvsample; ?>">
						<EMBED SRC="swf/flashsample.swf" LOOP="true" QUALITY="best" FlashVars="myfile=<?php echo $flvsample; ?>" WIDTH="<?php echo $setting->sample_size; ?>" HEIGHT="<?php echo $vidheight; ?>">
						</object>
                        </div>
                    <?php									
						} else {				
							if(!file_exists("swf/photoloader.swf") or $setting->flashsamples == 0 or $_SESSION['visitor_flash'] == 1){
                
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
                    $height+=2;
								echo "<div style=\"height: $height; width: $width; overflow: hidden; \"><img src=\"watermark.php?i=" . $photo->id . "\" class=\"photos\"> </div>";
							} else {
								// GET WIDTH AND HEIGHT FOR FLASH FILE
								$default_size = $setting->sample_width;
								if(file_exists($stock_photo_path . "s_" . $photo->filename)){
								$imageInfo = getimagesize($stock_photo_path . "s_" . $photo->filename);			
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
							} else {
									$to = $setting->support_email;
									$no_upload_message = "There was an issue with your photostore not being able to display a photo at: \n " . $_SESSION['url'] . " \n";
									$no_upload_message.= "Chances are this is usually caused by a photo that failed to upload correctly. \n";
									$no_upload_message.= "Usually this is caused by low resources on the server or your php settings are not sufficient to upload the photo. \n";
									$no_upload_message.= "To correct and remove this error you will need to log into your store manager and delete the photo that is missing but has a record in the database. \n";
									$no_upload_message.= "You will find it in the photos area of your store manager, select the gallery the error is showing in and look for the thumbnail that doesn't have a preview and delete it. \n";
									$no_upload_message.= "-----------ERROR INFO------------\n";
									$no_upload_message.= "ERROR:24 Failed to display a photo in the photo details view \n";
									$no_upload_message.= "Photo Info \n";
									$no_upload_message.= "Photo Package ID: " . $package->id . " \n";
									$no_upload_message.= "Photo Package Title: " . $package->title . " \n";
									$no_upload_message.= "Photo Package Description: " . $package->description . " \n";
									$no_upload_message.= "Photo Package Category: " . $package->gallery_id . " \n";
									$no_upload_message.= "---------------------------------\n";
									mail($to, $setting->site_title . " failed to display a photo (NEEDS FIXED)", $no_upload_message, "From: " . $setting->support_email);
							}
					?>
                <div id="photoloaddiv" style="background-color: #e6e6e6; padding: 4px; width: <?php echo $width; ?>px; background-image: url(images/img_load.gif); background-repeat: no-repeat;">
								<?PHP echo $no_flashplayer; ?>
								</div>
								<script type="text/javascript" src="js/swfobject.js"></script>
								<script>
									<!--
										var flashObj<?php echo $photo->id; ?> = new SWFObject ("swf/<?php echo $setting->flashtrans; ?>", "photoloader", "<?php echo $width; ?>", "<?php echo $height; ?>", 8, "#eeeeee", true);
										flashObj<?php echo $photo->id; ?>.addVariable ("myphotopath", "watermark.php?i=<?php echo $photo->id; ?>");
										<?php /* flashObj.addVariable ("myphotopath", "<?php echo $stock_photo_path . "s_" . $photo->filename; ?>"); */ ?>
										flashObj<?php echo $photo->id; ?>.write ("photoloaddiv");
									// -->
								</script>
							<?php
								}
						}	
					?>
                    </span><br>
					<?php if((file_exists($stock_photo_path . "m_" . $photo->filename) && $setting->show_preview == 1) && !$flvsample){ ?><a href = "javascript:NewWindow('enlarge.php?i=<? echo $photo->id; ?>','LargeView','<? echo $photo_size1[0]; ?>','<? echo $photo_size1[1]; ?>','0','<? echo $scroll; ?>');"><img src="./images/cte.gif" class="photos" alt="<?PHP echo $detail_alt_cte; ?>" title="<?PHP echo $detail_alt_cte; ?>"></a><?php } ?>
					<? 
					if($setting->allow_subs_month == 1 or $setting->allow_subs == 1 or $setting->allow_sub_free == 1){
										if($_SESSION['sub_member']){
												$lightbox1_result = mysql_query("SELECT id FROM lightbox where member_id = '" . $_SESSION['sub_member'] . "' and reference_id = '" . $_SESSION['lightbox_id'] . "' and photo_id = '$pid'", $db);
												$lightbox1_rows = mysql_num_rows($lightbox1_result);
												$lightbox1 = mysql_fetch_object($lightbox1_result);
												if($lightbox1_rows > 0){ 
										?>
															<a href="public_actions.php?pmode=remove_lightbox&lid=<? echo $lightbox1->id; ?>" class="photo_links"><img src="images/rem_lightbox.gif" class="photos" alt="<? echo $detail_alt_remlightbox; ?>" title="<? echo $detail_alt_remlightbox; ?>"></a>
										<? } else { ?>
															<a href="public_actions.php?pmode=add_lightbox&ptype=d&gid=<? echo $gid; ?>&sgid=<? echo $sgid; ?>&pid=<? echo $pid; ?>" class="photo_links"><img src="images/add_lightbox.gif" class="photos" alt="<? echo $detail_alt_addlightbox; ?>" title="<? echo $detail_alt_addlightbox; ?>"></a>
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
					     if($package->photographer > 0 && $package->photographer < 999999999){
									?>
									  <a href="view_photog.php?photogid=<? echo $package->photographer; ?>" class="photo_links"><img src="images/photographer.gif" class="photos" alt="<? echo $detail_alt_photographer; ?>" title="<? echo $detail_alt_photographer; ?>"></a>
									<?
								}
					    if(file_exists("swf/thumbslide.swf") && $_SESSION['visitor_flash'] != 1 && $setting->flash_thumb_on == 1){
					    unset($_SESSION['tlist_id']);
							session_register("tlist_id");
							$_SESSION['tlist_id'] = $package->id;
							if($_SESSION['imagenav']){
					    ?>
					<br>
					<div id="flashcontent2"/>
					<?PHP echo $no_flashplayer; ?>
					</div>
						<script type="text/javascript" src="js/swfobject.js"></script>
                            <script>
                                <!--
                                    var flashObjthumbs = new SWFObject ("swf/thumbslide.swf", "thumbslide", "600", "100", 8, "<?PHP echo $setting->thumb_slide_bgcolor; ?>", true);
                                    flashObjthumbs.addVariable ("arrowcontrol", "<?PHP echo $setting->thumb_slide_arrowcontrol; ?>");
                                    flashObjthumbs.addVariable ("border", "<?PHP echo $setting->thumb_slide_border; ?>");
                                    flashObjthumbs.addVariable ("bordercolor", "<?PHP echo $setting->thumb_slide_bordercolor; ?>");
                                    flashObjthumbs.addVariable ("bordercornerradius", "<?PHP echo $setting->thumb_slide_bordercornerradius; ?>");
                                    flashObjthumbs.addVariable ("bordersize", "<?PHP echo $setting->thumb_slide_bordersize; ?>");
                                    flashObjthumbs.addVariable ("builtinpreloader", "<?PHP echo $setting->thumb_slide_builtinpreloader; ?>");
                                    flashObjthumbs.addVariable ("preloadercolor", "<?PHP echo $setting->thumb_slide_preloadercolor; ?>");
                                    flashObjthumbs.addVariable ("easetype", "<?PHP echo $setting->thumb_slide_easetype; ?>");
                                    flashObjthumbs.addVariable ("effectamount", "<?PHP echo $setting->thumb_slide_effectamount; ?>");
                                    flashObjthumbs.addVariable ("effecttimein", "<?PHP echo $setting->thumb_slide_effecttimein; ?>");
                                    flashObjthumbs.addVariable ("effecttimeout", "<?PHP echo $setting->thumb_slide_effecttimeout; ?>");
                                    flashObjthumbs.addVariable ("rollovereffect", "<?PHP echo $setting->thumb_slide_rollovereffect; ?>");
                                    flashObjthumbs.addVariable ("reverserollovereffect", "<?PHP echo $setting->thumb_slide_reverserollovereffect; ?>");
                                    flashObjthumbs.addVariable ("orientation", "<?PHP echo $setting->thumb_slide_orientation; ?>");
                                    flashObjthumbs.addVariable ("resizetype", "<?PHP echo $setting->thumb_slide_resizetype; ?>");
                                    flashObjthumbs.addVariable ("spacing", "<?PHP echo $setting->thumb_slide_spacing; ?>");
                                    flashObjthumbs.addVariable ("thumbheight", "<?PHP echo $setting->thumb_slide_thumbheight; ?>");
                                    flashObjthumbs.addVariable ("thumbwidth", "<?PHP echo $setting->thumb_slide_thumbwidth; ?>");
                                    flashObjthumbs.addVariable ("tweenyspeed", "<?PHP echo $setting->thumb_slide_speed; ?>");
                                    flashObjthumbs.write ("flashcontent2");
                                // -->
                            </script>
					<? 
						}
					} else {
					?>
					<br>
					<? 
					if($_SESSION['imagenav'] && $prev or $next){
					 	if($prev){
							$prev = trim($prev);
							$p_result = mysql_query("SELECT title FROM photo_package WHERE id = '$prev'", $db);
							$prevname = mysql_fetch_object($p_result);
							
							mod_photolink($prev,$_GET['gid'],$prevname->title,"","");
						?>
                        <!--<a href="details.php?gal=<? echo $_GET['gal']; ?>&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo trim($prev); ?>">--><?PHP echo $detail_previous; ?></a>
						
					<? } else { ?>
						<?PHP echo $detail_previous; ?>
					<? } ?> | 
					<? if($next){
							$next = trim($next);
							$n_result = mysql_query("SELECT title FROM photo_package WHERE id = '$next'", $db);
							$nextname = mysql_fetch_object($n_result);
							mod_photolink($next,$_GET['gid'],$nextname->title,"","");
						?>
                    	<!--<a href="details.php?gal=<? echo $_GET['gal']; ?>&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo trim($next); ?>">--><?PHP echo $detail_next; ?></a><? } else { ?><?PHP echo $detail_next; ?><? } } ?></center>
					<? } ?>
					<table width="100%">
					<?
							if($setting->hide_id == 0){
					?>
					<?PHP echo $detail_photo_id; ?><?php echo $package->id; ?>
					<br />
					<?PHP echo $detail_gallery_id; ?><?php echo $package->gallery_id; ?> - <a href="gallery.php?gid=<?php echo $package->gallery_id; ?>"><?php echo $gal->title; ?></a>
					<?
					 } 
							if($setting->dis_filename == 1){
								echo "<tr><td class=\"photo_title\">";
								echo $detail_title . $photo->filename;
								echo "</td></tr>";
								} else {
								echo "<tr><td class=\"photo_title\">";
								echo $detail_title . $package->title;
								echo "</td></tr>";
							}
							
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
						}
						// ADDITIONAL SIZES AREA #########################################################
						if($setting->allow_digital == 1 && $photo->original == 1){
							if($package->all_sizes == 1 or $package->sizes != ""){
								$sizes_result = mysql_query("SELECT id,name,size,price FROM sizes order by sorder", $db);
								$sizes_num_rows = mysql_num_rows($sizes_result);
							if($sizes_num_rows > 0){
								echo "<tr><td class=\"photo_details\" bgcolor=\"#F9F9F9\" style=\"border: 1px solid #eeeeee; padding: 10px;\">";
								echo "<div align=\"center\" style=\"background-color: #eeeeee; margin-bottom: 10px; padding: 3px;\">" . $detail_additional_sizes . "</div>";
							if($package->all_sizes != 1){
							$size_num = explode(",", $package->sizes);
							while($sizes = mysql_fetch_object($sizes_result)){
								if($check_size[0] >= $sizes->size or $check_size[1] >= $sizes->size){
								if(in_array($sizes->id,$size_num)){
						//Get the resized info for the photo (added in PS321)
						$samplel_width = $sizes->size;
						if($check_size[0] >= $check_size[1]){
							if($check_size[0] > $samplel_width){
								$newl_width = $samplel_width;
							} else {
								$newl_width = $check_size[0];
							}
								$ratio = $newl_width/$check_size[0];
								$newl_height = $check_size[1] * $ratio;
						} else {
							if($check_size[1] > $samplel_width){
								$newl_height = $samplel_width;	
							} else {
								$newl_height = $check_size[1];	
							}		
								$ratio = $newl_height/$check_size[1];
								$newl_width = $check_size[0] * $ratio;
						}
						//End of resizing info
									echo $detail_quality . $sizes->name . "<br>";
									echo $detail_dimension . round($newl_width) . "x" . round($newl_height) . $detail_size_px;
									echo $detail_price . $currency->sign . $sizes->price . "<br>";
									if($_SESSION['sub_member'] && $package->photographer == "0" && $member_download->sub_length != "F" && $sizes->price != 0){
						?>
										<span style="float: right"><a href = "javascript:NewWindow('downl.php?pid=<? echo $photo->id; ?>&sid=<? echo $sizes->id; ?>','Download','500','100','0','0');"><img src="images/downloadbut.gif" border="0" style="border: 1px solid #a9a9a9;" /></a></span>
						<?
									} else {
									if($sizes->price == 0 or $sizes->price == "0.00"){
									echo $detail_free_download . "<br>";
									?>
										<span style="float: right"><a href="download_file2.php?pid=<? echo $photo->id; ?>&sid=<? echo $sizes->id; ?>" class="photo_links"><img src="images/downloadbut.gif" border="0" style="border: 1px solid #a9a9a9;" /></a></span>
									<?
								  } else {
								  ?>
										<span style="float: right"><a href="public_actions.php?pmode=add_cart&ptype=s&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&sid=<? echo $sizes->id; ?>&pid=<? echo $photo->id; ?>" class="photo_links"><img src="images/addtocart.gif" border="0" style="border: 1px solid #a9a9a9;" /></a></span>
								  <?
								}
							}
								  echo "<hr color=\"#eeeeee\" size=\"1\"><br>";
									}
								}
							}
						} else {
							while($sizes = mysql_fetch_object($sizes_result)){
								if($check_size[0] >= $sizes->size or $check_size[1] >= $sizes->size){
						//Get the resized info for the photo (added in PS321)
						$samplel_width = $sizes->size;
						if($check_size[0] >= $check_size[1]){
							if($check_size[0] > $samplel_width){
								$newl_width = $samplel_width;
							} else {
								$newl_width = $check_size[0];
							}
								$ratio = $newl_width/$check_size[0];
								$newl_height = $check_size[1] * $ratio;
						} else {
							if($check_size[1] > $samplel_width){
								$newl_height = $samplel_width;	
							} else {
								$newl_height = $check_size[1];	
							}		
								$ratio = $newl_height/$check_size[1];
								$newl_width = $check_size[0] * $ratio;
						}
						//End of resizing info
									echo $detail_quality . $sizes->name . "<br>";
									echo $detail_dimension . round($newl_width) . "x" . round($newl_height) . $detail_size_px;
									echo $detail_price . $currency->sign . $sizes->price . "<br>";
									if($_SESSION['sub_member'] && $package->photographer == "0" && $member_download->sub_length != "F" && $sizes->price != 0){
						?>
										<span style="float: right"><a href = "javascript:NewWindow('downl.php?pid=<? echo $photo->id; ?>&sid=<? echo $sizes->id; ?>','Download','500','100','0','0');"><img src="images/downloadbut.gif" border="0" style="border: 1px solid #a9a9a9;" /></a></span>
						<?
									} else {
									if($sizes->price == 0 or $sizes->price == "0.00"){
										echo $detail_free_download . "<br>";
									?>
										<span style="float: right"><a href="download_file2.php?pid=<? echo $photo->id; ?>&sid=<? echo $sizes->id; ?>" class="photo_links"><img src="images/downloadbut.gif" border="0" style="border: 1px solid #a9a9a9;" /></a></span>
									<?
								  } else {
								  ?>
										<span style="float: right"><a href="public_actions.php?pmode=add_cart&ptype=s&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&sid=<? echo $sizes->id; ?>&pid=<? echo $photo->id; ?>" class="photo_links"> <img src="images/addtocart.gif" border="0" style="border: 1px solid #a9a9a9;" /></a></span>
								  <?
									}
								}
								  echo "<hr color=\"#eeeeee\" size=\"1\"><br>";
								}
							}
						}
							if($setting->size_info == 1){
								echo "<p align=\"center\"><a href=\"size_info.php\">" . $detail_addtional_size_info . "</a> <a href=\"size_info.php\"><img src=\"images/size_info.gif\" border=\"0\" alt=\"" . $detail_addtional_size_info . "\" title=\"" . $detail_addtional_size_info . "\" align='absmiddle'></a></p>";
							}
							echo "</td></tr>";
								}
							}
						}
						##########################################
						
						# PRINTS #########################################################
						if($setting->dropdown != 1){
							if($setting->allow_prints == 1){
							if($package->update_29 == 0){
							 if($package->all_prints == 1 or $package->prod != ""){
								echo "<tr><td class=\"photo_details\" bgcolor=\"#F9F9F9\" style=\"border: 1px solid #eeeeee; padding: 10px;\">";
								echo "<div align=\"center\" style=\"background-color: #eeeeee; margin-bottom: 10px; padding: 3px;\">" . $detail_prints . "</div>";
								
								$x = 1;
								$print_result = mysql_query("SELECT id,name,price,quan_avail,bypass FROM prints where quan_avail > 0 order by porder", $db);
								$print_rows = mysql_num_rows($print_result);
								while($print = mysql_fetch_object($print_result)){
										
										echo "<b>" . $print->name . "</b><br>";
										//echo "<b>Size:</b> " . $print->size . "<br>";
										
										echo "<font color=\"#ff0000\">" . $detail_print_price . $currency->sign . $print->price . $detail_print_each;
										if($print->quan_avail != "999"){
											echo $detail_print_available . $print->quan_avail;
										}
										if($print->bypass == 1){
										 echo "<br>" . $misc_pickup;
										}
										echo "</font><br>";
										echo "<span style=\"float: right\"><a href=\"public_actions.php?pmode=add_cart&ptype=p&gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "&pid=" . $photo->id . "&prid=" . $print->id . "\" class=\"photo_links\"><img src=\"images/cart.gif\" valign=\"middle\" border=\"0\"></a> <a href=\"public_actions.php?pmode=add_cart&ptype=p&gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "&pid=" . $photo->id . "&prid=" . $print->id . "\" class=\"photo_links\">" . $detail_add_to_cart . "</a></span>";
										echo "<hr color=\"#eeeeee\" size=\"1\"><br>";
								}
								$print2_result = mysql_query("SELECT * FROM prints where quan_avail > 0 and visible = 1 order by porder", $db);
								$print2_rows = mysql_num_rows($print2_result);
								if($setting->print_info == 1 && $print2_rows > 0){
									echo "<br><div align=\"center\"><a href=\"print_info.php\">" . $detail_info . "</a> <a href=\"print_info.php\"><img src=\"images/size_info.gif\" border=\"0\" alt=\"" . $detail_alt_info . "\" title=\"" . $detail_alt_info . "\" align='absmiddle'></a></div>";
								}
								 echo "</td></tr>";
							}
							} else {
								if($package->all_prints == 1 or $package->prod != ""){
								$myprod = explode(",",$package->prod);
								//$myprod = $package->prod;
								
								foreach($myprod as $value){
									if($value != ""){
										$newarray[] = $value;
									}
								}
								
								if(is_array($newarray)){
									$id_nums = implode(", ", $newarray);
									echo "<tr><td class=\"photo_details\" bgcolor=\"#F9F9F9\" style=\"border: 1px solid #eeeeee; padding: 10px;\">";
									echo "<div align=\"center\" style=\"background-color: #eeeeee; margin-bottom: 10px; padding: 3px;\">" . $detail_prints . "</div>";
								} else {
									$id_nums = "999999999999,99999112299999,999991323129";
									echo "<tr><td class=\"photo_details\" bgcolor=\"#F9F9F9\" style=\"border: 1px solid #eeeeee; padding: 10px;\">";
								  echo "<div align=\"center\" style=\"background-color: #eeeeee; margin-bottom: 10px; padding: 3px;\">" . $detail_prints . "</div>";
								}
								
								if($package->all_prints){
									$print_result = mysql_query("SELECT id,name,price,quan_avail,bypass FROM prints where quan_avail > 0  order by porder", $db);
								} else {
									$print_result = mysql_query("SELECT id,name,price,quan_avail,bypass FROM prints where quan_avail > 0 and id IN ($id_nums) order by porder", $db);
								}
								$print_rows = mysql_num_rows($print_result);
								while($print = mysql_fetch_object($print_result)){
										
										echo "<b>" . $print->name . "</b><br>";
										//echo "<b>Size:</b> " . $print->size . "<br>";
										
										echo "<font color=\"#ff0000\">" . $detail_print_price . $currency->sign . $print->price . $detail_print_each;
										if($print->quan_avail != "999"){
											echo $detail_print_available . $print->quan_avail;
										}
										if($print->bypass == 1){
										 echo "<br>" . $misc_pickup;
										}
										echo "</font><br>";
										echo "<span style=\"float: right\"><a href=\"public_actions.php?pmode=add_cart&ptype=p&gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "&pid=" . $photo->id . "&prid=" . $print->id . "\" class=\"photo_links\"><img src=\"images/cart.gif\" valign=\"middle\" border=\"0\"></a> <a href=\"public_actions.php?pmode=add_cart&ptype=p&gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "&pid=" . $photo->id . "&prid=" . $print->id . "\" class=\"photo_links\">" . $detail_add_to_cart . "</a></span>";
										echo "<hr color=\"#eeeeee\" size=\"1\"><br>";
								}
								$print2_result = mysql_query("SELECT * FROM prints where quan_avail > 0 and visible = 1 order by porder", $db);
								$print2_rows = mysql_num_rows($print2_result);
								if($setting->print_info == 1 && $print2_rows > 0){
									echo "<br><div align=\"center\"><a href=\"print_info.php\">" . $detail_info . "</a> <a href=\"print_info.php\"><img src=\"images/size_info.gif\" border=\"0\" alt=\"" . $detail_alt_info . "\" title=\"" . $detail_alt_info . "\" align='absmiddle'></a></div>";
								}
							echo "</td></tr>";
								}
							}
						}
					} else {
						if($setting->allow_prints == 1){
							if($package->update_29 == 0){
								if($package->all_prints == 1 or $package->prod != ""){
								$x = 1;
								$print_result = mysql_query("SELECT id,name,price,quan_avail,bypass FROM prints where quan_avail > 0 order by porder", $db);
								$print_rows = mysql_num_rows($print_result);
								if($print_rows > 0){
									
								echo "<tr><td class=\"photo_details\" bgcolor=\"#F9F9F9\" style=\"border: 1px solid #eeeeee; padding: 10px;\">";
								echo "<div align=\"center\" style=\"background-color: #eeeeee; margin-bottom: 10px; padding: 3px;\">" . $detail_prints . "</div>";
								
								
								while($print = mysql_fetch_object($print_result)){
										
										echo "<b>" . $print->name . "</b><br>";
										//echo "<b>Size:</b> " . $print->size . "<br>";
										
										echo "<font color=\"#ff0000\">". $detail_print_price . $currency->sign . $print->price . $detail_print_each;
										if($print->quan_avail != "999"){
											echo $detail_print_available . $print->quan_avail;
										}
										if($print->bypass == 1){
										 echo "<br>" . $misc_pickup;
										}
										echo "</font><br>";
										echo "<span style=\"float: right\"><a href=\"public_actions.php?pmode=add_cart&ptype=p&gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "&pid=" . $photo->id . "&prid=" . $print->id . "\" class=\"photo_links\"><img src=\"images/cart.gif\" valign=\"middle\" border=\"0\"></a> <a href=\"public_actions.php?pmode=add_cart&ptype=p&gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "&pid=" . $photo->id . "&prid=" . $print->id . "\" class=\"photo_links\"><img src=\"images/addtocart.gif\" border=\"0\" style=\"border: 1px solid #a9a9a9;\" /></a></span>";
										echo "<hr color=\"#eeeeee\" size=\"1\"><br>";
								}
							}
										echo "</td></tr>";
							  }
							} else {
								if($package->all_prints == 1 or $package->prod != ""){
								$myprod = explode(",",$package->prod);
								//$myprod = $package->prod;
								
								foreach($myprod as $value){
									if($value != ""){
										$newarray[] = $value;
									}
								}
								
								if(is_array($newarray)){
									$id_nums = implode(", ", $newarray);
								} else {
									$id_nums = "999999999999,99999112299999,999991323129";
								}
								
								if($package->all_prints){
									$print_result = mysql_query("SELECT id,name,price,quan_avail,bypass FROM prints where quan_avail > 0  order by porder", $db);
								} else {
									$print_result = mysql_query("SELECT id,name,price,quan_avail,bypass FROM prints where quan_avail > 0 and id IN ($id_nums) order by porder", $db);
								}
								$print_rows = mysql_num_rows($print_result);
								if($print_rows > 0){
								echo "<tr><td align=\"center\" class=\"photo_details\" bgcolor=\"#F9F9F9\" style=\"border: 1px solid #eeeeee; padding: 10px;\">";
								echo "<div align=\"center\" style=\"background-color: #eeeeee; margin-bottom: 10px; padding: 3px;\">" . $detail_prints . "</div>";
								?>
								<form name="print_drop" method="post" onSubmit="return dropdown(this.prints)" style="margin: 0px; padding: 0px; font-size: 11;">
								<select name="prints" id="prints" style="font-size: 9; width:500;">
								<option value=""><?PHP echo $detail_select; ?></option>
								<?
								while($print = mysql_fetch_object($print_result)){
								?>	
							  <option value="public_actions.php?pmode=add_cart&ptype=p&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $photo->id; ?>&prid=<? echo $print->id; ?>"><? echo $print->name . " - (" . $currency->sign . $print->price; ?>) <?PHP if($print->quan_avail != "999"){ echo " (" . $print->quan_avail . $detail_quantity_avail . ") "; } ?><?PHP if($print->bypass == 1){ echo $misc_pickup; } ?></option>
								<?
								}
								?>					
								</select>
								<br>
							  <?PHP
							  	echo "<span><input type=\"submit\" value=\"Add Selected To Cart\" class=\"go_button\">";
							  	if($setting->print_info == 1){
							      $print2_rows = mysql_result(mysql_query("SELECT COUNT(id) FROM prints where quan_avail > 0 and visible = 1 order by porder"),0);
										echo "</font><br>";
										if($print2_rows > 0){
										echo "<br><a href=\"print_info.php\">" . $detail_info . "</a> <a href=\"print_info.php\"><img src=\"images/size_info.gif\" border=\"0\" alt=\"" . $detail_alt_info . "\" title=\"" . $detail_alt_info . "\" align='absmiddle'></a></span>";
									  }
									} else {
										echo "</span>";
									}
								    echo "</form>";
								    echo "</td></tr>";
								}
							}
						}
					}
				}
						
						###########################################################

						
					?>
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
					</table>
					
					<table width="100%">
					<?php
						if($package->keywords && trim($package->keywords) != ""){
							echo "<tr><td class=\"photo_details\">";
							echo $detail_keyword;
							for($keyx = 0; $keyx < $keyword_length; $keyx++){
								if($keyx != 0){
									echo ", ";
								}
								echo "<a href=\"search.php?search=" . $keywords[$keyx] . "\">" . $keywords[$keyx] . "</a>";
							}
							echo "</td></tr>";
						}
					?>
					</table>
					
					<table width="100%"> 
					<?php  
						if($package->description && trim($package->description) != ""){
							echo "<tr><td class=\"photo_details\">";
							echo $detail_description;
							echo $package->description;
							echo "</td></tr>";
						}
					?>
					</table>
					<table width="100%"> 
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
					</table>
					<table width="100%">
					<tr>
					 <td valign="middle">
					 <?
				if($setting->rate_on == 1 && $setting->member_rate != 1){
					 echo $detail_rating_text;
				   echo rating_bar($package->id,'10','false');
				  } else {
				   if($setting->member_rate == 1 && $setting->rate_on == 1 && $_SESSION['sub_member'] > 0){
				   	echo $detail_rating_text;
				   	echo rating_bar($package->id,'10','false');
				  }
				}
					?>
								</td>
							</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="right"><a href="javascript:history.go(-1)">[Back]</a></td>
			</tr>
		</table>
	</span>
