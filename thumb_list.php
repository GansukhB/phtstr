<?PHP
session_start();
include("database.php");
include("functions.php");
include("config_public.php");
	
		//Security
		if($_GET['pass'] != md5(2672)){
			echo "You don't have permission to see this page";
			exit;
		}

	//How many photos for the thumbslider?
	$count = $setting->thumb_slide_count;
	//How many photos fro the featured?
    $count_featured = $setting->featured;
  
	//Switch from various parts of the website
  	switch($_GET['pmode']){
  		case "thumbslide":
  		
			//Start of Previous | Next buttons for gallery viewing
			$images = explode(",",$_SESSION['imagenav']);			
			$ptr = $_SESSION['tlist_id'];
			$arraycount = count($images);
			
			for($x=0;$x<=$arraycount;$x++){
				if($images[$x] == $ptr ){
					$currentkey = $x;
				}
			}
			
			$displayperside = round(($count-1)/2);
		
			$newarray = array();
			
			
			$z = 0;
			for($x=($currentkey-$displayperside);$x<$arraycount;$x++){
				if($images[$x] and $z <= $count){
					$newarray[] = $images[$x];					
					$z++;
				}
			}
			
		
			// CHECK FOR LEFTOVERS AND ADD THEM TO THE LEFT
			$loarray = array();
			$leftover = $count - ($z-1);
			if($leftover > 0){
				$loarray = array();
				$start = ($currentkey-$displayperside)-1;
				for($x=0;$x<$leftover;$x++){
					if($images[$x]){
						$loarray[] = $images[$start - $x];
					}
				}
				foreach($loarray as $value){
					//echo "lo: $value<br />";
				}
			}
				
			
			$origarray = array_reverse($newarray);
			foreach($loarray as $value){
				$origarray[] = $value;
			}
			$search_array = array_reverse($origarray);
			
			//Start header of xml file
			header("Content-type: text/xml");
			echo "<?xml version='1.0' encoding='UTF-8'?> \n";
			echo "<galerie> \n";
			
			foreach($search_array as $value){
				$value = trim($value);
				if($value){
			
				//Start loading image IDs
				$photo_result = mysql_query("SELECT id,reference_id,filename FROM uploaded_images where reference = 'photo_package' and reference_id = '$value' order by original", $db);
				$photo = mysql_fetch_object($photo_result);
				
				//Loop to print each line of the image to be shown
				$package_result = mysql_query("SELECT id,gallery_id,title FROM photo_package where id = '$photo->reference_id'", $db);
				$package = mysql_fetch_object($package_result);
				$photo_name = $photo->filename;
				$link = $photo->id;
				$pid = $photo->reference_id;
				$gid = $package->gallery_id;
				if($setting->show_watermark_thumb == 1){
				echo "<img thumbEvents='true' thumbnail='thumb_mark.php?i=$link&ext=.jpg' link='" . mod_photolink_short_noecho($package->id,$package->gallery_id,$package->title,"","") . "' target='_self' /> \n";
				} else {
				echo "<img thumbEvents='true' thumbnail='image.php?src=$link&ext=.jpg' link='" . mod_photolink_short_noecho($package->id,$package->gallery_id,$package->title,"","") . "' target='_self' /> \n";
				}
			}
    }
			echo "</galerie>";
			exit;
			break;
			
			//Start of the flash featured photos area
			case "featured":
			
			//Select all available galleries
			$gallery_result = mysql_query("SELECT id FROM photo_galleries where active = '1' and pub_pri = '0' and free = '0' and monthly = '0' and yearly = '0'", $db);
			while($gallery = mysql_fetch_object($gallery_result)){
				$approved_cats[] = $gallery->id;
			}

			$approved_cats = implode(", ", $approved_cats);
			
			//Select all available images from the available galleries
			$package_result = mysql_query("SELECT id,gallery_id,title FROM photo_package where gallery_id IN ($approved_cats) and active = '1' and featured = '1' and photog_show = '1' order by rand() limit $count_featured", $db);
			
			//Print the header for the xml file
			header("Content-type: text/xml");
			echo "<?xml version='1.0' encoding='UTF-8'?> \n";
			echo "<photos path=\"" . $stock_photo_path . "\"> \n";
			
			//Loop to print each line for the images to be show in the flash featured section
			while($package = mysql_fetch_object($package_result)){
			$photo_result = mysql_query("SELECT id,reference_id,filename FROM uploaded_images where reference = 'photo_package' and reference_id = '$package->id' order by original", $db);
			$photo = mysql_fetch_object($photo_result);
			$photo_name = $photo->filename;
			$id = $photo->id;
			$pid = $photo->reference_id;
			$gid = $package->gallery_id;
			$title = addslashes($package->title);
			$replace = array("\"","/","\\","|","]","[",";",":",")","(","*","^","%","$","#","@","<",">","'",",");
			$title = str_replace($replace, "", $title);
    		echo "<photo name='$title' url='i_$photo_name' link='" . mod_photolink_short_noecho($package->id,$package->gallery_id,$package->title,"","") . "' target='_self' /> \n";
    		}
			echo "</photos>";
			exit;
			break;
			
			case "pageflip":
			//Start of paging for album viewing and max size
			$search_array = explode(",",$_SESSION['imagenav']);
			$photolist = "\n";
			$width = "";
			$height = "";
			foreach($search_array as $value){
				$value = trim($value);
				if($value){
				//Start loading image IDs
				$photo_result = mysql_query("SELECT id,reference_id,filename FROM uploaded_images where reference = 'photo_package' and reference_id = '$value' order by original", $db);
				$photo = mysql_fetch_object($photo_result);
				$size = getimagesize($stock_photo_path. "s_" . $photo->filename);
				if($size[0] > $width){
					$width = $size[0];
				}
				if($size[1] > $height){
					$height = $size[1];
				}
				//Loop to print each line of the image to be shown
				$package_result = mysql_query("SELECT id,gallery_id,title FROM photo_package where id = '$photo->reference_id'", $db);
				$package = mysql_fetch_object($package_result);
				$photo_name = $photo->filename;
				$link = $photo->id;
				$pid = $photo->reference_id;
				$gid = $package->gallery_id;
				//$photolist.= "<page>".$stock_photo_path."s_".$photo_name."</page> \n";
				if($setting->show_watermark_thumb == 1){
				$photolist.= "<page>".$stock_photo_path."s_".$photo_name."</page> \n";
				//$photolist.= "watermark.php?i=$link";
				} else {
				$photolist.= "<page>".$stock_photo_path."s_".$photo_name."</page> \n";
				}
			}
    }
    //GET SIZE AND ADJUST USING A RATIO
    //NEED TO MAKE THIS A SETTING
    $sample_width = 700;
			if($width >= $height){
				$width = $width * 2;
				if($width > $sample_width){
					$new_width = $sample_width;
				} else {
					$new_width = $width;
				}
				$ratio = $new_width/$width;
				$new_height = $height * $ratio;	
			} else {
		$sample_width = 400;
				if($height > $sample_width){
					$new_height = $sample_width;	
				} else {
					$new_height = $height;	
				}		
					$ratio = $new_height/$height;
					$new_width = $width * $ratio * 2;
			}
					
			//Start settings of xml file
			header("Content-type: text/xml");
			echo "<?xml version='1.0' encoding='UTF-8'?> \n";
			echo "<FlippingBook> \n";
			echo "<width>$new_width</width> \n";
			echo "<height>$new_height</height> \n\n";

			echo "<scaleContent>true</scaleContent> \n";
			echo "<firstPage>0</firstPage>	\n";
			echo "<alwaysOpened>true</alwaysOpened> \n";
			echo "<autoFlip>50</autoFlip> \n";
			echo "<flipOnClick>true</flipOnClick> \n\n";

			echo "<staticShadowsDepth>1.5</staticShadowsDepth> \n";
			echo "<dynamicShadowsDepth>2</dynamicShadowsDepth> \n\n";

			echo "<moveSpeed>2</moveSpeed> \n";
			echo "<closeSpeed>2</closeSpeed> \n";
			echo "<gotoSpeed>3</gotoSpeed> \n\n";

			echo "<flipSound> </flipSound> \n";
			echo "<pageBack>0x000000</pageBack> \n\n";

			echo "<loadOnDemand>true</loadOnDemand> \n";
			echo "<cachePages>true</cachePages> \n\n";

   		echo "<cacheSize>20</cacheSize> \n";
			echo "<preloaderType>Round</preloaderType> \n";
			echo "<userPreloaderId> </userPreloaderId> \n\n";

			echo "<pages> \n";
			echo "<page>images/black.jpg</page> \n";
			echo $photolist;
			echo "<page>images/black.jpg</page> \n";
			echo "</pages> \n";
			echo "</FlippingBook> \n";
			exit;
			break;
			
			case "slideshow":
			//Start of paging for album viewing and max size
			$search_array = explode(",",$_SESSION['imagenav']);
			$photolist = "\n";
			$width = "";
			$height = "";
			foreach($search_array as $value){
				$value = trim($value);
				if($value){
				//Start loading image IDs
				$photo_result = mysql_query("SELECT id,reference_id,filename FROM uploaded_images where reference = 'photo_package' and reference_id = '$value' order by original", $db);
				$photo = mysql_fetch_object($photo_result);
				$size = getimagesize($stock_photo_path. "s_" . $photo->filename);
				if($size[0] > $width){
					$width = $size[0];
				}
				if($size[1] > $height){
					$height = $size[1];
				}
				//Loop to print each line of the image to be shown
				$package_result = mysql_query("SELECT id,gallery_id,title,description FROM photo_package where id = '$photo->reference_id'", $db);
				$package = mysql_fetch_object($package_result);
				$gal_result = mysql_query("SELECT title,description FROM photo_galleries where id = '$package->gallery_id'", $db);
				$gal = mysql_fetch_object($gal_result);
				$gal_title = addslashes($gal->title);
				$gal_description = addslashes($gal->description);
				$photo_name = $photo->filename;
				$photo_title = addslashes($package->title);
				$photo_desc = addslashes($package->description);
				$url = $setting->site_url;
				$link = $photo->id;
				$pid = $photo->reference_id;
				$gid = $package->gallery_id;
				$path = substr($stock_photo_path, 1);
				if($setting->show_watermark_thumb == 1){
					$thumb = "tn=\"".$url."/thumb_mark.php?i=$link\"";
					} else {
					$thumb = "tn=\"".$url.$path."i_".$photo_name."\"";
				}
				//$photolist.= "<page>".$stock_photo_path."s_".$photo_name."</page> \n";
				// could add a link like this link=\"".$url."/details.php?pid=".$link."\" target=\"_blank\"
				// relative path = $photolist.= "<img src=\"s_".$photo_name."\" title=\"$photo_title\" caption=\"$photo_desc\" /> \n";
				// absolute path = $photolist.= "<img src=\"".$url.$path."s_".$photo_name."\" tn=\"".$url.$path."i_".$photo_name."\" title=\"$photo_title\" caption=\"$photo_desc\" /> \n";
				
				if($setting->show_watermark == 1){
				$photolist.= "<img src=\"".$url."/watermark.php?i=$link\" ".$thumb." title=\"$photo_title\" caption=\"$photo_desc\" /> \n";
				} else {
				$photolist.= "<img src=\"".$url.$path."s_".$photo_name."\" ".$thumb." title=\"$photo_title\" caption=\"$photo_desc\" /> \n";
				}
				}
    		}
			//Start output of xml file
			header("Content-type: text/xml");
			echo "<?xml version='1.0' encoding='UTF-8'?> \n";
			echo "<gallery> \n";
			// can put this in below  lgPath=\"$stock_photo_path\" tn=\"$stock_photo_path\"
			echo "<album title=\"$gal_title\" description=\"SlideShow\"> \n";
			echo $photolist;
			echo "</album> \n";
			echo "</gallery> \n";
			exit;
			break;
   }
?>