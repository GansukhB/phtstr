<?
	/************************************************************************************************************************************

		MANAGER SETTINGS
			4-7-04
			
		// DO NOT CHANGE ANYTHING BELOW THIS LINE / CHANGING THIS INFORMATION MAY RESULT IN THIS PRODUCT NOT FUNCTIONING CORRECTLY
		
	************************************************************************************************************************************/
		
		$manager_title  = "Site Manager";
		$website_url    = "http://www.yoursite.com"; // URL to the root of the site - *NOT CURRENTLY USED
	
		include( "../database.php" ); // ADD DATABASE CONNECTION FILE
		include( "../functions.php" ); // ADD FUNCTIONS FILE
		
		$infoshare = "on";
		include("../version.php");
		
		$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
		$setting = mysql_fetch_object($settings_result);
		// OVERRIDE MANAGER/SITE TITLE IF ONE EXISTS IN THE DATABASE
		if($setting->site_title != ""){
			$manager_title = $setting->site_title;
		}
		$install_url = selfURL();
		$array_url = explode("/",$install_url);
		$strip = $array_url[count($array_url) - 1];
		$install_url = str_replace($strip, "", $install_url);
		$array2_url = explode("/",$install_url);
		$strip2 = $array2_url[count($array2_url) - 2];
		$install_url = str_replace($strip2, "", $install_url);
		$install_url = substr($install_url, 0, -2);
		
		if($setting->site_url != $install_url){
			$sql = "UPDATE settings SET site_url='$install_url' WHERE id = '1'";
			$result = mysql_query($sql);
		}
		$stock_photo_path_manager = "../" . $setting->photo_dir . "/";
		$stock_video_path_manager = "../" . $setting->video_dir . "/";
		$sample_video_path_manager = "../" . $setting->sample_dir . "/";
		
		$author         = "";
		$author_email   = "info@domain.com";
		$author_website = "http://www.domain.com"; // CAN ALSO ENTER EMAIL ADDRESS AS mailto:me@here.com
		$support_url    = "http://www.domain.com/support";
				
		// ADD PLUGINS
		$execute_nav = 1;
		$plugins_dir = "plugins";
		$real_dir = realpath($plugins_dir);
		$dir = opendir($real_dir);
		$i = 0;
		// LOOP THROUGH THE PLUGINS DIRECTORY
		while($file = readdir($dir)){
			// MAKE SURE IT IS A VALID FILE
			if($file != ".." && $file != "." && stristr($file,".php")){
				$i++;
				include($plugins_dir . "/" . $file);
				// PUT NAV ITEMS IN AN ARRAY
				if($nav_visible == 1){ // MAKE SURE THE NAV ITEM IS TURNED ON
					$nav_array[$i] = array($nav_order, $nav_visible, $nav_name, $file);
				}
			}
		}
		
		# ADDITIONAL PHOTO UPLOAD PROFILES
		# The p_number array is for the batch editor so it knows which image belongs to which size, make sure to put a number as a value!
		# Do not use the same number twice!
		# DO NOT USE THE NUMBER 1 THAT IS RESERVED FOR THE ORIGINAL IMAGE!
		# Start with 2 and number up for each other size you make or just edit the three below already made
		# p_order is the order in which the images are displayed for sale on your site smaller number being near the top of list
		
		$p_number     		= array();
		$p_order      		= array();
		$p_name						= array();
		$p_size 					= array();
		$p_default_price 	= array();
		
		$p_number[]     		= "2"; 			// Number associated with this entry
		$p_order[]      		= "2"; 			// Order in which they are listed for sale
		$p_name[] 					= "Large"; 	// Name of profile
		$p_size[] 					= "800"; 		// Scale to this size
		$p_default_price[]	= "15.00"; 	// Default price
		
		$p_number[]     		= "3"; 			// Number associated with this entry
		$p_order[]      		= "3"; 			// Order in which they are listed for sale
		$p_name[] 					= "Medium"; // Name of profile
		$p_size[] 					= "600"; 		// Scale to this size
		$p_default_price[]	= "10.00"; 	// Default price
		
		$p_number[]     		= "4"; 			// Number associated with this entry
		$p_order[]      		= "4"; 			// Order in which they are listed for sale
		$p_name[] 					= "Small"; 	// Name of profile
		$p_size[] 					= "400"; 		// Scale to this size
		$p_default_price[]	= "5.00"; 	// Default price
		
		
		# FIX FOR MAGIC QUOTES #####################
		function quote_smart($value){
		   // Stripslashes if magic quotes is on
		   if(get_magic_quotes_gpc()) {
			   $value = stripslashes($value);
		   }
		   // Quote if not a number or a numeric string
		   if (!is_numeric($value)) {
			   $value = mysql_real_escape_string($value);
		   }
		   return $value;
		}
		foreach($_POST as $key => $value){				
				if(is_array($value)){
					foreach($value as $key2 => $value2){
						//${$key}[$key2] = addslashes($value2);
						${$key}[$key2] = quote_smart($value2);								
					}	
				} else {
					${$key} = quote_smart($value);
				}
			}
			
		foreach($_GET as $key => $value){				
				if(is_array($value)){
					foreach($value as $key2 => $value2){
						//${$key}[$key2] = addslashes($value2);
						${$key}[$key2] = quote_smart($value2);								
					}	
				} else {
					${$key} = quote_smart($value);
				}
			}
			
		#############################################
		//ADDED IN PS350 FOR CLEAN UP OF SPECIAL CHARACTERS THAT CAN'T BE USED /"\|][;:)(*^%$#@<>
		function cleanup($string){
			//$cleanup_chars = array("\"","/","\\","|","]","[",";",":",")","(","*","^","%","$","#","@","<",">");
			//$string = str_replace($cleanup_chars, "", $string);
			$string = addslashes($string);
			return $string;
		}
		function price_cleanup($string){
			$cleanup_chars = array("$","£","¥","€");
			$cleanup_decimal = array(",");
			$string = str_replace($cleanup_chars, "", $string);
			$string = str_replace($cleanup_decimal, ".", $string);
			return $string;
		}
?>
