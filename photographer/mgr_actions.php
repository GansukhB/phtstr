<?php
	session_start();
	
	if($_GET['pmode'] != "login" and $_GET['pmode'] != "logout" ){
		include( "check_login_status.php" );
	}
	include( "config_mgr.php" );
	
	
	
	$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
	
	$currency_result = mysql_query("SELECT * FROM currency", $db);
	$currecny = mysql_fetch_object($currency_result);
	
	$default_file_path = "../uploaded_files/";
	$default_image_path = "../uploaded_images/";
	$default_sp_path = $stock_photo_path_manager;
	
	switch($_GET['pmode']){
/*-----------------------------------------------------------------------------------------------------------------------*/
/*                                               MANAGER LOGIN & LOGOUT                                                  */	
/*-----------------------------------------------------------------------------------------------------------------------*/		
	/* LOGIN */
		case "backup":
		foreach($setting as $key => $value){
		if($fields != ""){
			$fields = $fields . "," . $key;
		} else {
			$fields = $key;
		}
		if($values != ""){
			$values = $values . "','" . addslashes($value);
		} else {
			$values = addslashes($value);
		}
		}
		$sql = "INSERT INTO settings (";
		$sql.= $fields;
		$sql.= ") VALUES (";
		$sql.= "'2'," . substr($values, 3);;
		$sql.= "')";
		mysql_query($sql, $db);
		header("location: mgr.php?nav=$nav&message=backedup");
		exit;
		break;
		
		case "restore":
		$settings2_result = mysql_query("SELECT * FROM settings where id = '2'", $db);
		$setting2 = mysql_fetch_object($settings2_result);
		if($setting2->id == 2){
		$sql="DELETE FROM settings WHERE id = '1'";
		$result2 = mysql_query($sql);
		} else {
		echo "There is no backups found that can be used to restore?";
		}
		foreach($setting2 as $key => $value){
		if($fields != ""){
			$fields = $fields . "," . $key;
		} else {
			$fields = $key;
		}
		if($values != ""){
			$values = $values . "','" . addslashes($value);
		} else {
			$values = addslashes($value);
		}
		}
		$sql = "INSERT INTO settings (";
		$sql.= $fields;
		$sql.= ") VALUES (";
		$sql.= "'1'," . substr($values, 3);;
		$sql.= "')";
		mysql_query($sql, $db);
		header("location: mgr.php?nav=$nav&message=restored");
		exit;
		break;
		
		case "backup_update":
		$settings2_result = mysql_query("SELECT * FROM settings where id = '2'", $db);
		$setting2 = mysql_fetch_object($settings2_result);
		if($setting2->id == 2){
		$sql="DELETE FROM settings WHERE id = '2'";
		$result2 = mysql_query($sql);
		} else {
		echo "There is no backups found that can be updated?";
		}
		foreach($setting as $key => $value){
		if($fields != ""){
			$fields = $fields . "," . $key;
		} else {
			$fields = $key;
		}
		if($values != ""){
			$values = $values . "','" . addslashes($value);
		} else {
			$values = addslashes($value);
		}
		}
		$sql = "INSERT INTO settings (";
		$sql.= $fields;
		$sql.= ") VALUES (";
		$sql.= "'2'," . substr($values, 3);;
		$sql.= "')";
		mysql_query($sql, $db);
		header("location: mgr.php?nav=$nav&message=updated");
		exit;
		break;
		
		case "login":		
			session_register("access_status");
			session_register("access_type");
			
			// CHECK MGR_USERS DB FOR ACCESS
			$mgr_users_result = mysql_query("SELECT * FROM photographers", $db);
			while($mgr_users = mysql_fetch_object($mgr_users_result)){
				if(strtolower($_POST['username']) == strtolower($mgr_users->email) && strtolower($_POST['password']) == strtolower($mgr_users->password) && $setting->status == MD5(1)){
					$_SESSION['access_status'] = "dfjfhkallkdfdmsa";
					$_SESSION['access_type']  = "mgr";
          $_SESSION['user_id'] = $mgr_users->id;
					header("location: mgr.php");
					exit;
				}
			}
			// ALL LOGINS FAILED - GO BACK TO THE LOGIN PAGE
			header("location: login.php?error=1");
			exit;		
		break;
		
	/* LOGOUT */	
		case "logout":
			session_unregister("access_status");
			session_unregister("access_type");
			header("location: login.php?logout=1");
			exit;
		break;
		
/*-----------------------------------------------------------------------------------------------------------------------*/	
/*                                           SAVE WEBSITE/MANGER SETTINGS                                                */	
/*-----------------------------------------------------------------------------------------------------------------------*/

	/* SAVE MANAGER USERNAME AND PASSWORD */	
		case "save_mgr_login":
			if($_SESSION['access_type'] != "mgr"){ echo "Operation cannot be performed in demo mode"; exit; }
		
		$username = $_POST['username'];
		$password = $_POST['password'];
		$return = $_POST['return'];
		
			$sql = "UPDATE mgr_users SET username='$username',password='$password' WHERE id = '1'";
			$result = mysql_query($sql);
			header("location: mgr.php?nav=0&message=mgr_settings_saved");
			
		break;
	
	/* SAVE MANAGER SETTINGS WHEN LOGGED IN AS ADMIN */	
		case "save_mgr_settings_admin":
			if($_POST['deactivate'] == 1){
				$status = 0;
			} else {
				$status = MD5(1);
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
			
			$sql = "UPDATE settings SET demo_mode='$demo_mode',editor='$editor',editor_link='$editor_link',help_tips_link='$help_tips_link',status='$status',error_message='$error_message',author_branding='$author_branding' WHERE id = '1'";
			$result = mysql_query($sql);
			header("location:" . $return . "&message=admin_settings_saved");
		break;
	
	/* SAVE MANAGER SETTINGS WHEN LOGGED IN AS MGR */	
		case "save_mgr_settings_mgr":
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
		
		// UPLOAD logo
			if($_FILES['logoFile']['name'] != ""){
				$ext = substr($_FILES['logoFile']['name'],-3);
				$logoFileName = $_FILES['logoFile']['name'];
				upload_file_new($_FILES['logoFile'],$logoFileName,"../logo/");
			}
			
							$real_dir = realpath("../logo/");
							$dir = opendir($real_dir);
							# LOOP THROUGH THE DIRECTORY
							while($file1 = readdir($dir)){
							// MAKE SURE IT IS A IMAGE FILE
							$isphp = explode(".", $file1);
							if($file1 != ".." && $file1 != "." && is_file("../logo/" . $file1) && @$isphp[count($isphp) - 1] == "jpg" or @$isphp[count($isphp) - 1] == "gif" or @$isphp[count($isphp) - 1] == "png" or @$isphp[count($isphp) - 1] == "swf" or @$isphp[count($isphp) - 1] == "flv"){
							$replace_char = array(".","+","%","(",")","'","_","!","@","#","<",">","$","^","&","*","-");
							$post = str_replace($replace_char, "", $file1);
							$post = trim($post);
							if($_POST[$post] == 1){
								if(file_exists("../logo/" . $file1)){
									unlink("../logo/" . $file1);
								}
					     		$sql="DELETE FROM links WHERE filename = '$file1'";
								$result2 = mysql_query($sql);
					     	}
					     	unset($result2);
					     	
					     		$result = mysql_query("SELECT id FROM links WHERE filename = '$file1'", $db);
					     		$rs_rows = mysql_num_rows($result);
					     		$url_link = $_POST[$post."_url"];
					     		if($url_link == ""){
										$url_link = "nolink";
								}
								if($rs_rows == 1){
									$sql = "UPDATE links SET url='$url_link' WHERE filename = '$file1'";
									$results = mysql_query($sql,$db);
								} else {
									$sql = "INSERT INTO links (filename,url) VALUES ('$file1','$url_link')";
									$result = mysql_query($sql);
								}
							}
						unset($isphp);
						unset($rs_rows);
						unset($result);
						unset($url_link);
					}
					
					 //ADDED FOR PS320- ADD .css TO THE END OF THE STYLE NAME THAT IS SELECTED
					 $style = $style . ".css";
					 //ADDED FOR PS330- ADD .php TO THE END OF THE LANGUAGE FILE THAT IS SELECTED
					 $lang = $lang ;//. ".php";
					 
					 //ADDED FOR PS320- ADDED TO REMEMBER THE SETTINGS FOR THE PHOTOGRAPHERS SETTING AREA IF THE PHOTOG_MAIN.PHP DOES NOT EXIST
					 if(!file_exists("../photog_main.php")){
					 	$com_level = $setting->com_level;
					 	$photog_price = $setting->photog_price;
					 	$appc = $setting->appc;
					 	$photog_old_sizes = $setting->photog_old_sizes;
					 	$photog_new_sizes = $setting->photog_new_sizes;
					 	$photog_sizes_locked = $setting->photog_sizes_locked;
					 	$photog_upload = $setting->photog_upload;
					 	$photog_edit = $setting->photog_edit;
					 	$photog_upload_email = $setting->photog_upload_email;
					 	$photog_size_width = $setting->photog_size_width;
					 	$photog_size_height = $setting->photog_size_height;
					 	$photog_reg = $setting->photog_reg;
					 	$photog_dir = $setting->photog_dir;
					 	$photog_batch_upload = $setting->photog_batch_upload;
						}
					  
						//ADDED FOR PS340- ADDED TO REMEMBER THE SETTINGS FOR THE THUMBSLIDE IF THE thumbslide.swf DOES NOT EXIST
					 if(!file_exists("../swf/thumbslide.swf")){
					 	$flash_thumb_on = $setting->flash_thumb_on;
					 	$thumb_slide_count = $setting->thumb_slide_count;
					 	$thumb_slide_arrowcontrol = $setting->thumb_slide_arrowcontrol;
					 	$thumb_slide_border = $setting->thumb_slide_border;
					 	$thumb_slide_bordercolor = $setting->thumb_slide_bordercolor;
					 	$thumb_slide_bordercornerradius = $setting->thumb_slide_bordercornerradius;
					 	$thumb_slide_bordersize = $setting->thumb_slide_bordersize;
					 	$thumb_slide_builtinpreloader = $setting->thumb_slide_builtinpreloader;
					 	$thumb_slide_preloadercolor = $setting->thumb_slide_preloadercolor;
					 	$thumb_slide_easetype = $setting->thumb_slide_easetype;
					 	$thumb_slide_effectamount = $setting->thumb_slide_effectamount;
					 	$thumb_slide_effecttimein = $setting->thumb_slide_effecttimein;
					 	$thumb_slide_effecttimeout = $setting->thumb_slide_effecttimeout;
					 	$thumb_slide_rollovereffect = $setting->thumb_slide_rollovereffect;
					 	$thumb_slide_reverserollovereffect = $setting->thumb_slide_reverserollovereffect;
					 	$thumb_slide_orientation = $setting->thumb_slide_orientation;
					 	$thumb_slide_resizetype = $setting->thumb_slide_resizetype;
					 	$thumb_slide_spacing = $setting->thumb_slide_spacing;
					 	$thumb_slide_thumbheight = $setting->thumb_slide_thumbheight;
					 	$thumb_slide_thumbwidth = $setting->thumb_slide_thumbwidth;
					 	$thumb_slide_speed = $setting->thumb_slide_speed;
					 	$thumb_slide_bgcolor = $setting->thumb_slide_bgcolor;
						}
						
						//ADDED FOR PS340- ADDED TO REMEMBER THE SETTINGS FOR THE FEATURED IF THE featured.swf DOES NOT EXIST
					 if(!file_exists("../swf/featured.swf")){
					 	$flash_featured_on = $setting->flash_featured_on;
					 	$pf_mousewheelflip = $setting->pf_mousewheelflip;
					 	$pf_autoflipseconds = $setting->pf_autoflipseconds;
					 	$pf_flipsound = $setting->pf_flipsound;
					 	$pf_flipspeed = $setting->pf_flipspeed;
					 	$pf_namebold = $setting->pf_namebold;
					 	$pf_namecolor = $setting->pf_namecolor;
					 	$pf_namedistance = $setting->pf_namedistance;
					 	$pf_nameposition = $setting->pf_nameposition;
					 	$pf_namesize = $setting->pf_namesize;
					 	$pf_namefont = $setting->pf_namefont;
					 	$pf_preloadset = $setting->pf_preloadset;
					 	$pf_hpers = $setting->pf_hpers;
					 	$pf_vpers = $setting->pf_vpers;
					 	$pf_view = $setting->pf_view;
					 	$pf_reflectionalpha = $setting->pf_reflectionalpha;
					 	$pf_reflectiondepth = $setting->pf_reflectiondepth;
					 	$pf_reflectiondistance = $setting->pf_reflectiondistance;
					 	$pf_reflectionextend = $setting->pf_reflectionextend;
					 	$pf_selectedreflectionalpha = $setting->pf_selectedreflectionalpha;
					 	$pf_showname = $setting->pf_showname;
					 	$pf_showreflection = $setting->pf_showreflection;
					 	$pf_photoheight = $setting->pf_photoheight;
					 	$pf_photowidth = $setting->pf_photowidth;
					 	$pf_selectedy = $setting->pf_selectedy;
					 	$pf_defaultid = $setting->pf_defaultid;
					 	$pf_holderalpha = $setting->pf_holderalpha;
					 	$pf_holderborderalpha = $setting->pf_holderborderalpha;
					 	$pf_holderbordercolor = $setting->pf_holderbordercolor;
					 	$pf_holdercolor = $setting->pf_holdercolor;
					 	$pf_scalemode = $setting->pf_scalemode;
					 	$pf_selectedscale = $setting->pf_selectedscale;
					 	$pf_spacing = $setting->pf_spacing;
					 	$pf_zoom = $setting->pf_zoom;
					 	$pf_zoomtype = $setting->pf_zoomtype;
					 	$pf_bgcolor = $setting->pf_bgcolor;
						}
						
						//ADDED FOR PS340- ADDED TO REMEMBER THE SETTINGS FOR THE PHOTOLOADERS IF THE photoloader1.swf DOES NOT EXIST
						if(!file_exists("../swf/photoloader1.swf")){
							$flashthumbs = $setting->flashthumbs;
							$flashsamples = $setting->flashsamples;
							$flashtrans = $setting->flashtrans;
						}
						//ADDED IN PS350 TO CHECK TO MAKE SURE THE DIR EXIST BEFORE TRYING TO RENAME IT
						if(!is_dir("../".$photo_dir)){
							$photo_dir = $setting->photo_dir;
							$error1 = "&error1=photo_dir";
						}
						if(!is_dir("../".$video_dir)){
							$video_dir = $setting->video_dir;
							$error2 = "&error2=video_dir";
						}
						if(!is_dir("../".$sample_dir)){
							$sample_dir = $setting->sample_dir;
							$error3 = "&error3=sample_dir";
						}
						if(is_file("../photog_main.php")){
							if(!is_dir("../".$photog_dir)){
								$photog_dir = $setting->photog_dir;
								$error4 = "&error4=photog_dir";
							}
						}
						
						//ADDED IN PS350 TO CLEANUP SOME OF THE FIELD INPUTS TO AVOID THESE CHARACTERS '/"?\|][;:=+)(*&^%$#@!<>,
						$site_title = cleanup($site_title);
						$site_tagline = cleanup($site_tagline);
						$site_description = cleanup($site_description);
						$photo_dir = cleanup($photo_dir);
						$video_dir = cleanup($video_dir);
						$sample_dir = cleanup($sample_dir);
						$site_keywords = cleanup($site_keywords);
						$default_price = price_cleanup($default_price);
						$cart_price = price_cleanup($cart_price);
						$photog_price = price_cleanup($photog_price);
						$fix_cart1 = price_cleanup($fix_cart1);
						$fix_cart2 = price_cleanup($fix_cart2);
						$fix_cart3 = price_cleanup($fix_cart3);
						$fix_cart4 = price_cleanup($fix_cart4);
						$fix_cart5 = price_cleanup($fix_cart5);
						$fix_cart6 = price_cleanup($fix_cart6);
						$fix_cart7 = price_cleanup($fix_cart7);
						$fix_cart8 = price_cleanup($fix_cart8);
						$fix_price1 = price_cleanup($fix_price1);
						$fix_price2 = price_cleanup($fix_price2);
						$fix_price3 = price_cleanup($fix_price3);
						$fix_price4 = price_cleanup($fix_price4);
						$tax1_name = cleanup($tax1_name);
						$tax1 = price_cleanup($tax1);
						$tax2_name = cleanup($tax2_name);
						$tax2 = price_cleanup($tax2);
						$sub_price = price_cleanup($sub_price);
						$sub_price_month = price_cleanup($sub_price_month);
						
			$sql = "UPDATE settings SET site_title='$site_title',site_tagline='$site_tagline',site_url='$site_url',support_email='$support_email',personal_email='$personal_email',site_description='$site_description',site_keywords='$site_keywords',paypal_email='$paypal_email',use_paypal='$use_paypal',use_2checkout='$use_2checkout',twocheck_account='$twocheck_account',default_price='$default_price',allow_subs='$allow_subs',sub_price='$sub_price',allow_subs_month='$allow_subs_month',sub_price_month='$sub_price_month',style='$style',show_num='$show_num',show_news='$show_news',pf_feed='$pf_feed',show_abanner='$show_abanner',abanner_name='$abanner_name',kaffiliate='$kaffiliate',allow_digital='$allow_digital',allow_prints='$allow_prints',perpage='$perpage',show_views='$show_views',thumb_width='$thumb_width',sample_width='$sample_width',dis_columns='$dis_columns',allow_ktools='$allow_ktools',use_money='$use_money',download_days='$download_days',show_preview='$show_preview',preview_size='$preview_size',fix_cart1='$fix_cart1',fix_cart2='$fix_cart2',fix_cart3='$fix_cart3',fix_cart4='$fix_cart4',fix_cart5='$fix_cart5',fix_cart6='$fix_cart6',fix_cart7='$fix_cart7',fix_cart8='$fix_cart8',fix_price1='$fix_price1',fix_price2='$fix_price2',fix_price3='$fix_price3',fix_price4='$fix_price4',tax1='$tax1',tax2='$tax2',tax_total='$tax_total',tax1_name='$tax1_name',tax2_name='$tax2_name',dis_title_gallery='$dis_title_gallery',dis_title_pri='$dis_title_pri',dis_title_search='$dis_title_search',dis_title_new='$dis_title_new',dis_title_popular='$dis_title_popular',dis_title_featured='$dis_title_featured',dis_filename='$dis_filename',hide_id='$hide_id',pnpid='$pnpid',pnpstatus='$pnpstatus',show_private='$show_private',show_watermark='$show_watermark',large_size='$large_size',hover_on='$hover_on',hover_usr='$hover_usr',hover_feature='$hover_feature',hover_new='$hover_new',hover_popular='$hover_popular',hover_gallery='$hover_gallery',hover_pri='$hover_pri',hover_search='$hover_search',sort_by='$sort_by',sort_order='$sort_order',show_tree='$show_tree',show_stats='$show_stats',allow_sub_free='$allow_sub_free',no_cache='$no_cache',no_right_click='$no_right_click',debug='$debug',slide_type='$slide_type',slide_speed='$slide_speed',force_members='$force_members',force_mac='$force_mac',force_approve='$force_approve',description_length='$description_length',featured='$featured',thumb_display_quality='$thumb_display_quality',hover_display_quality='$hover_display_quality',sample_display_quality='$sample_display_quality',large_display_quality='$large_display_quality',show_watermark_thumb='$show_watermark_thumb',show_watermark_hover='$show_watermark_hover',hover_size='$hover_size',com_level='$com_level',photog_price='$photog_price',appc='$appc',photog_old_sizes='$photog_old_sizes',photog_new_sizes='$photog_new_sizes',photog_sizes_locked='$photog_sizes_locked',photog_upload='$photog_upload',photog_edit='$photog_edit',rate_on='$rate_on',member_rate='$member_rate',use_authorize_net='$use_authorize_net',api_login_id='$api_login_id',transaction_key='$transaction_key',sr_featured='$sr_featured',sr_gallery='$sr_gallery',sr_pri='$sr_pri',sr_new='$sr_new',sr_pop='$sr_pop',sr_search='$sr_search',sr_photog='$sr_photog',down_limit_y='$down_limit_y',down_limit_m='$down_limit_m',print_info='$print_info',size_info='$size_info',popular='$popular',newest='$newest',search='$search',charset='$charset',no_photo_message='$no_photo_message',mygatesupport='$mygatesupport',mygateid='$mygateid',mygateaid='$mygateaid',allow_contact_download='$allow_contact_download',sub_paypal='$sub_paypal',sub_2co='$sub_2co',sub_auth='$sub_auth',sub_pnp='$sub_pnp',sub_mygate='$sub_mygate',sub_cmo='$sub_cmo',upload_thumb_quality='$upload_thumb_quality',upload_sample_quality='$upload_sample_quality',upload_large_quality='$upload_large_quality',onoff='$onoff',dropdown='$dropdown',multi_lang='$multi_lang',lang='$lang',photo_dir='$photo_dir',video_dir='$video_dir',sample_dir='$sample_dir',sample_size='$sample_size',photog_upload_email='$photog_upload_email',photog_size_width='$photog_size_width',photog_size_height='$photog_size_height',photog_reg='$photog_reg',print_ship='$print_ship',thumb_slide_count='$thumb_slide_count',modrw='$modrw',thumb_slide_arrowcontrol='$thumb_slide_arrowcontrol',thumb_slide_border='$thumb_slide_border',thumb_slide_bordercolor='$thumb_slide_bordercolor',thumb_slide_bordercornerradius='$thumb_slide_bordercornerradius',thumb_slide_bordersize='$thumb_slide_bordersize',thumb_slide_builtinpreloader='$thumb_slide_builtinpreloader',thumb_slide_preloadercolor='$thumb_slide_preloadercolor',thumb_slide_easetype='$thumb_slide_easetype',thumb_slide_effectamount='$thumb_slide_effectamount',thumb_slide_effecttimein='$thumb_slide_effecttimein',thumb_slide_effecttimeout='$thumb_slide_effecttimeout',thumb_slide_rollovereffect='$thumb_slide_rollovereffect',thumb_slide_reverserollovereffect='$thumb_slide_reverserollovereffect',thumb_slide_orientation='$thumb_slide_orientation',thumb_slide_resizetype='$thumb_slide_resizetype',thumb_slide_spacing='$thumb_slide_spacing',thumb_slide_thumbheight='$thumb_slide_thumbheight',thumb_slide_thumbwidth='$thumb_slide_thumbwidth',thumb_slide_speed='$thumb_slide_speed',thumb_slide_bgcolor='$thumb_slide_bgcolor',pf_mousewheelflip='$pf_mousewheelflip',pf_autoflipseconds='$pf_autoflipseconds',pf_flipsound='$pf_flipsound',pf_flipspeed='$pf_flipspeed',pf_namebold='$pf_namebold',pf_namecolor='$pf_namecolor',pf_namedistance='$pf_namedistance',pf_nameposition='$pf_nameposition',pf_namesize='$pf_namesize',pf_namefont='$pf_namefont',pf_preloadset='$pf_preloadset',pf_hpers='$pf_hpers',pf_vpers='$pf_vpers',pf_view='$pf_view',pf_reflectionalpha='$pf_reflectionalpha',pf_reflectiondepth='$pf_reflectiondepth',pf_reflectiondistance='$pf_reflectiondistance',pf_reflectionextend='$pf_reflectionextend',pf_selectedreflectionalpha='$pf_selectedreflectionalpha',pf_showname='$pf_showname',pf_showreflection='$pf_showreflection',pf_photoheight='$pf_photoheight',pf_photowidth='$pf_photowidth',pf_selectedy='$pf_selectedy',pf_defaultid='$pf_defaultid',pf_holderalpha='$pf_holderalpha',pf_holderborderalpha='$pf_holderborderalpha',pf_holderbordercolor='$pf_holderbordercolor',pf_holdercolor='$pf_holdercolor',pf_scalemode='$pf_scalemode',pf_selectedscale='$pf_selectedscale',pf_spacing='$pf_spacing',pf_zoom='$pf_zoom',pf_zoomtype='$pf_zoomtype',pf_bgcolor='$pf_bgcolor',leftbox1='$leftbox1',leftbox2='$leftbox2',leftbox3='$leftbox3',leftbox4='$leftbox4',leftbox5='$leftbox5',leftbox6='$leftbox6',headerbox='$headerbox',footerbox='$footerbox',emailchar='$emailchar',flashtrans='$flashtrans',flashthumbs='$flashthumbs',flashsamples='$flashsamples',free_approve='$free_approve',flash_featured_on='$flash_featured_on',flash_thumb_on='$flash_thumb_on',photog_dir='$photog_dir',photog_batch_upload='$photog_batch_upload',private_search='$private_search',search_onoff='$search_onoff',cart_price='$cart_price',tos_check='$tos_check',tax_download='$tax_download',menu_click='$menu_click' WHERE id = '1'";
			$result = mysql_query($sql);
			
			$sql = "UPDATE currency SET active=0";
			$results = mysql_query($sql,$db);
			
			$sql = "UPDATE currency SET active=1 WHERE code='$code'";
			$results = mysql_query($sql,$db);
			if($error1 or $error2 or $error3 or $error4){
				header("location: mgr.php?nav=0".$error1.$error2.$error3.$error4);
			} else {
			header("location: mgr.php?nav=0&message=mgr_settings_saved");
		}
		break;
		
		
		
/*-----------------------------------------------------------------------------------------------------------------------*/	
/*                                                DELETE FILE OR IMAGE                                                   */	
/*-----------------------------------------------------------------------------------------------------------------------*/

	/* DELETE IMAGE */	
		case "delete_image":
			if($_SESSION['access_type'] != "mgr"){ echo "Operation cannot be performed in demo mode"; exit; }
		$result = mysql_query("SELECT id,filename FROM uploaded_images where id = '" . $_GET['id'] . "'", $db);
		$rs = mysql_fetch_object($result);
			unlink($default_image_path . $rs->filename);	
			unlink($default_image_path . "i_" . $rs->filename);
			
			$sql="DELETE FROM uploaded_images WHERE id = '$rs->id'";
			$result2 = mysql_query($sql);
			
		header("location: mgr.php?nav=" . $_GET['nav'] . "&item_id=" . $_GET['item_id']);
		exit;
		
	/* DELETE IMAGE */	
		case "delete_image2":
			if($_SESSION['access_type'] != "mgr"){ echo "Operation cannot be performed in demo mode"; exit; }
		$result = mysql_query("SELECT id,filename FROM uploaded_images where id = '" . $_GET['id'] . "'", $db);
		$rs = mysql_fetch_object($result);
			unlink($default_sp_path . $rs->filename);	
			unlink($default_sp_path . "i_" . $rs->filename);
			
			$sql="DELETE FROM uploaded_images WHERE id = '$rs->id'";
			$result2 = mysql_query($sql);
			
		header("location: mgr.php?nav=" . $_GET['nav'] . "&item_id=" . $_GET['item_id']);
		exit;
		
	/* DELETE FILE */	
		case "delete_file":
			if($_SESSION['access_type'] != "mgr"){ echo "Operation cannot be performed in demo mode"; exit; }
		$result = mysql_query("SELECT id,filename FROM uploaded_files where id = '$_GET[id]'", $db);
		$rs = mysql_fetch_object($result);
			unlink($default_file_path . $rs->filename);
			
			$sql="DELETE FROM uploaded_files WHERE id = '$rs->id'";
			$result2 = mysql_query($sql);
			
		header("location: mgr.php?nav=" . $_GET['nav'] . "&item_id=" . $_GET['item_id']);
		exit;

/*-----------------------------------------------------------------------------------------------------------------------*/	
/*                                                UPDATE FILE DETAILS                                                    */	
/*-----------------------------------------------------------------------------------------------------------------------*/

	/* UPDATE FILE DETAILS */	
		case "update_file_details":
			if($_SESSION['access_type'] != "mgr"){ echo "Operation cannot be performed in demo mode"; exit; }
			$sql = "UPDATE uploaded_files SET file_text='" . $_POST['file_text'] . "',active='" . $_POST['active'] . "' WHERE id = " . $_POST['id'];
			$result = mysql_query($sql);					
		header("location: " . $_POST['return']);
		exit;		
	
/*-----------------------------------------------------------------------------------------------------------------------*/	
/*                                                UPDATE IMAGE CAPTION                                                   */	
/*-----------------------------------------------------------------------------------------------------------------------*/

	/* UPDATE IMAGE CAPTION */	
		case "update_image_caption":
			if($_SESSION['access_type'] != "mgr"){ echo "Operation cannot be performed in demo mode"; exit; }
			$sql = "UPDATE uploaded_images SET caption='$image_caption' WHERE id = " . $_POST['id'];
			$result = mysql_query($sql);					
		header("location: " . $_POST['return'] . "&image_path=" . $_POST['image_path']);
		exit;

/*-----------------------------------------------------------------------------------------------------------------------*/
/*                                                      DEFAULT                                                          */
/*-----------------------------------------------------------------------------------------------------------------------*/	
		default:
			header("location: login.php");
			exit;
		break;
	}
?>
