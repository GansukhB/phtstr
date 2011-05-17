<?
	/*********************************************************************************************************
	
		PUBLIC SETTINGS
			6-18-04
			
	**********************************************************************************************************/
	
		$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
		$setting = mysql_fetch_object($settings_result);
		
		/*if($setting->access_id == "0"){
			echo "<font face=\"verdana\" size=\"2\">This product still requires activation.";
			exit;
		}*/
		
	
		$default_title             = $setting->site_title . " | " . $setting->site_tagline; // DEFAULT TITLE IF PAGE TITLE IS NOT SPECIFIED
		$default_meta_keywords     = $setting->site_keywords;
		$default_meta_description  = $setting->site_description;
		$meta_author               = "";
		$public_bgcolor            = "ffffff";
		$border_color							 = "ffffff";
		$topmargin                 = 0;
		$leftmargin                = 0;
		$marginheight              = 0;
		$marginwidth               = 0;
		$stock_photo_path          = "./" . $setting->photo_dir . "/";
		$stock_video_path					 = "./" . $setting->video_dir . "/";
		$sample_video_path         = "./" . $setting->sample_dir . "/";
		
		//$plugnplay_id = "pnpdemo";
		//$pnp_status = "1";		
    
    //session_destroy();
		if(!isset($_SESSION['lang']))
    {
      $_SESSION['lang'] = "";
    }
    elseif($_SESSION['lang'] == '.php')
    {
      $_SESSION['lang'] = '';
    }
		if($page_title == ""){
			$page_title = $default_title;
		}
		
		// PAGE TITLE
		$page_title = "<title>" . $page_title . "</title>\n";
		
		// METATAGS
		$metatags = "";
		if($meta_keywords == ""){
			$meta_keywords = $default_meta_keywords; // IF NO PAGE SPECIFIC METATAGS ARE DEFINED USE DEFAULT
		}		
		if($meta_description == ""){
			$meta_description = $default_meta_description;  // IF NO PAGE SPECIFIC METATAGS ARE DEFINED USE DEFAULT
		}
		$metatags .= "\t\t<META HTTP-EQUIV=\"Content-Type\" content=\"text/html; charset=" . $setting->charset . "\">" . "\n";
		$metatags .= "\t\t<meta name=\"keywords\" content=\"" . $meta_keywords . "\">\n";
		$metatags .= "\t\t<meta name=\"description\" content=\"" . $meta_description . "\">\n";
		$metatags .= "\t\t<meta name=\"coverage\" content=\"Worldwide\">\n";
		$metatags .= "\t\t<meta name=\"revisit-after\" content=\"10 days\">\n";
		$metatags .= "\t\t<meta name=\"robots\" content=\"index, follow\">" . "\n";
		$metatags .= "\t\t<meta name=\"author\" content=\"" . $meta_author . "\">\n";
		if($setting->no_cache == 1){
		$metatags .= "\t\t<META HTTP-EQUIV=\"pragma\" content=\"no-cache\">" . "\n";
		$metatags .= "\t\t<META HTTP-EQUIV=\"expires\" content=\"-1\">" . "\n";
		}
		if($setting->no_right_click == 1){
		$metatags .= "\t\t<META HTTP-EQUIV=\"imagetoolbar\" CONTENT=\"no\">" . "\n"; 
		}
		
		// STYLE SHEET
		$style = "\t\t<link rel=\"stylesheet\" href=\"./styles/" . $setting->style . "\">\n";
		$style.= "\t\t</head>\n";
		
		// BODY
		if($setting->no_right_click == 1){
		$body = "<body oncontextmenu=\"return false\" bgcolor=\"#$public_bgcolor\" topmargin=\"$topmargin\" leftmargin=\"$leftmargin\" marginheight=\"$marginheight\" marginwidth=\"$marginwidth\" style=\"background-image: url(images/bg.gif);\">\n";
		} else {
		$body = "<body bgcolor=\"#$public_bgcolor\" topmargin=\"$topmargin\" leftmargin=\"$leftmargin\" marginheight=\"$marginheight\" marginwidth=\"$marginwidth\" style=\"background-image: url(images/bg.gif);\">\n";
	}
	
		// HEAD
		$head = $page_title . $metatags . $style . $body;
		
		// PUT CHECKOUT SYSTEM INTO TEST/DEMO MODE BY SETTING $checkout_demo_mode = 1
		$checkout_demo_mode = 0;
		
		// Customers are able to download their orders for X days
		$download_days = $setting->download_days;

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
	// echo $_SESSION['lang'] . " = language file";
	// exit;
  if($_SESSION['mgruser'] == 1){
  	$lang_path = "../language/";
  } else {
  	$lang_path = "language/";
  }
	if($_SESSION['lang'] != ""){
		$bits= explode(".",$_SESSION['lang']);
		$ses_lang = $bits[0];
		$ses_lang = $ses_lang ;//. ".php";
 		include($lang_path . $ses_lang . ".php");
	} else {
		if(file_exists($lang_path . $setting->lang )){
			include($lang_path . $setting->lang );
		} else {
			if(file_exists($lang_path . "English.php")){
  			include($lang_path . "English.php");
  		} else {
				echo "Sorry our system is currently down, please check back later.";
				$to = $setting->support_email;
				$no_lang_message = "There is an issue with your photostore not having any language file installed, the store has automatically told the visitors to check back soon as the system is down. \n";
				$no_lang_message.= "You will need to make sure you have at least the default English.php installed on your site in the language/ folder. \n";
				$no_lang_message.= "If you do not want the English.php as a language choice then you must at least log into your store manager and select the default language you want to use under \"display\" settings. \n";
				$no_lang_message.= "-----------ERROR INFO------------\n";
				$no_lang_message.= "ERROR:25 No language file detected \n";
				$no_lang_message.= "---------------------------------\n";
				mail($to, $setting->site_title . " has an operation issue! (NEEDS FIXED ASAP)", $no_lang_message, "From: " . $setting->support_email);
				exit;
			}
		}
	}

//ADDED TO TURN THE SITE ON / OFF
		if($setting->onoff == 0){
			echo $misc_site_on_off;
			exit;
		}

		# ADDITIONAL PHOTO UPLOAD PROFILES
		# The p_number array is for the batch editor so it knows which image belongs to which size, make sure to put a number as a value!
		# Do not use the same number twice!
		# DO NOT USE THE NUMBER 1 THAT IS RESERVED FOR THE ORIGINAL IMAGE!
		# Start with 2 and number up for each other size you make (example below already has 2, 3, and 4)
		# p_order is the order in which the images are displayed for sale on your site (The smaller number being near the top of list)
		
		$p_number     		= array();
		$p_order      		= array();
		$p_name						= array();
		$p_size 					= array();
		$p_default_price 	= array();
		
		$p_number[]     		= "2"; 			// Number associated with this entry
		$p_order[]      		= "2"; 			// Order in which they are listed for sale
		$p_name[] 				= "Large"; 	// Name of profile
		$p_size[] 				= "800"; 		// Scale to this size
		$p_default_price[]		= "15.00"; 	// Default price
		
		$p_number[]     		= "3"; 			// Number associated with this entry
		$p_order[]      		= "3"; 			// Order in which they are listed for sale
		$p_name[] 				= "Medium"; // Name of profile
		$p_size[] 				= "600"; 		// Scale to this size
		$p_default_price[]		= "10.00"; 	// Default price
		
		$p_number[]     		= "4"; 			// Number associated with this entry
		$p_order[]      		= "4"; 			// Order in which they are listed for sale
		$p_name[] 				= "Small"; 	// Name of profile
		$p_size[] 				= "400"; 		// Scale to this size
		$p_default_price[]		= "5.00"; 	// Default price
		
		
		// PHOTOGRAPHY FEED NEWS FEED
		$pf_feed_status = $setting->pf_feed; // 1 = ON & 0 = OFF
		$pf_bgcolor = "ffffff";// BACKGROUND HEX COLOR FOR THE NEWS DISPLY
		$pf_bordercolor = "258adc";// BORDER HEX COLOR FOR THE NEWS DISPLAY
		$pf_linkcolor = "0d7fb4";// LINK COLOR FOR THE NEWS DISPLAY
		$pf_readmorecolor = "80c65a";// READ MORE LINK COLOR
		$pf_articles = "";// NUMBER OF ARICLES TO DISPLAY
		$pf_titleonly = 0;// ONLY SHOW NEWS TITLES AND NOT DESCRIPTIONS 0 = OFF / 1 = ON
		$pf_ref = $_SERVER['HTTP_HOST'];
?>
