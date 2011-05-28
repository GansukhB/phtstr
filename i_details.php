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
                    $height+=2;
		?>
  

<!--r-left-main ehlel-->
<div class="r-left-main">
    <!--photo ehlel-->
      <div class="photo" align="center" style="width: 475px; overflow:hidden; align:center;">
        <div style="height: <?php echo $height; ?>; width: <?php echo $width; ?>; overflow: hidden; text-align:center;" >
          <img src="watermark.php?i=<?php echo $photo->id ?>" class="photos" > </div>
        
        <!--<img src="images/image3.jpg">-->
      </div>
      <div align=center>
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
      <!--photo tugsgul-->
      <!--price-main ehlel-->
      <div class="price-main">
        <div class="header"><strong>ҮНЭИЙН САНАЛ</strong> (Байгуулгад зориулсан)</div>
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
      <!--price-main ehlel-->
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
      <!--price-main tugsgul-->
  </div>
  <!--r-left-main tugsgul-->
  <!--r-right-main ehlel-->
  <div class="r-right-main">
    <!--r-right-content ehlel-->
    <div class="r-right-content">
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
          
          <div class="id"><?PHP echo $detail_photo_id; ?><strong> <?php echo $package->id; ?></strong></div>
          <div class="id">Release information: <strong>N/A</strong></div>
          <div class="id">Copyright: <strong>
            <?php
              $photog = get_photographer_by_pkg($package->id);
              echo "<a href=\"#\">". $photog[1] ."</a>";
              echo "<br /><a href=\"view_photog.php?photogid=" . $ptgm->id . "\">" . $detail_photog_link . "</a><br>"
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
      <div class="r-right-content">
        <h1>Similler images preweiw</h1>
          <div class="image-content">
            <div class="image"><a href="#"><img src="images/image2.jpg"></a></div>
              <div class="title" align="center">miamore khandaa</div>
          </div>
          
          <div class="image-content">
            <div class="image"><a href="#"><img src="images/image2.jpg"></a></div>
              <div class="title" align="center">miamore khandaa</div>
          </div>
          
          <div class="image-content">
            <div class="image"><a href="#"><img src="images/image2.jpg"></a></div>
              <div class="title" align="center">miamore khandaa</div>
          </div>
          
          <div class="image-content">
            <div class="image"><a href="#"><img src="images/image2.jpg"></a></div>
              <div class="title" align="center">miamore khandaa</div>
          </div>
          
      </div>
      <!--r-right-content tugsgul-->
  </div>
  <!--r-right-main tugsgul-->

