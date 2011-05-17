<?php
	/*
		Manager Settings Plus	4.7.04
			1 manager user login version
			This version includes metatags and other information
	*/
	  $nav = $_GET['nav'];
	if($execute_nav == 1){
		$nav_order = 2; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "Settings"; // name of the nav that will appear on the page
	}
	else{
		$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
		$setting = mysql_fetch_object($settings_result);
		
		$currency_result = mysql_query("SELECT * FROM currency where active = '1'", $db);
		$currency = mysql_fetch_object($currency_result);
		
		$mgr_result = mysql_query("SELECT * FROM mgr_users where id = '1'", $db);
		$mgr_users = mysql_fetch_object($mgr_result);
?>
	<table width="700" cellpadding="0" cellspacing="0" bgcolor="#577EC4" style="border: 1px solid #5B8BD8;">
		<script language="javascript">
			function save_mgr_login() {
				var agree=confirm("Are you sure you want to update the manager username and password?");
				if (agree) {
					document.mgr_login.action = "mgr_actions.php?pmode=save_mgr_login";
					document.mgr_login.submit();
				}
				else {
					false
				}
			}	
			function save_mgr_settings_mgr() {
				var agree=confirm("Are you sure you want to save these settings?");
				if (agree) {
					document.mgr_settings.action = "mgr_actions.php?pmode=save_mgr_settings_mgr";
					document.mgr_settings.submit();
				}
				else {
					false
				}
			}			
		</script>
		<form name="mgr_settings" method="post" ENCTYPE="multipart/form-data">
		<input type="hidden" name="return" value="mgr.php?nav=<? echo $nav; ?>">
		<?php
			if(file_exists("../nobranding.php")){
				include("../nobranding.php");
			}
		?>
        <tr>
			<td bgcolor="#3C6ABB" align="center" style="padding: 4px; border-bottom: 1px solid #355894;">
				<table cellpadding="0" cellspacing="0" width="95%">
					<tr>	
						<td width="100%" nowrap><font face="arial" color="#ffffff" style="font-size: 11;"><b>WEBSITE/MANAGER SETTINGS</b></font>
						<? if($_GET['message'] == "mgr_settings_saved"){ ?>
						<td align="right" valign="bottom">
							<img src="images/mgr_check2_loop_3.gif" valign="absmiddle">
						</td>
						<td align="right" valign="middle" nowrap>
							<font color="#FFE400" style="font-size: 10;">&nbsp;Your settings have been updated.<br>
						</td>
						<? } ?>
						<?PHP if($_GET['error1'] or $_GET['error2'] or $_GET['error3'] or $_GET['error4']){ ?>
						<td align="right" valign="top">
							<img src="images/mgr_check2_loop_3.gif" valign="absmiddle">
						</td>
						<td align="right" valign="middle" nowrap>
						<?PHP } ?>
						<? if($_GET['error1'] == "photo_dir"){ ?>
							<font color="#FFE400" style="font-size: 10;">&nbsp;You could not rename your photo directory cause it doesn't exist on your server.<br>
						<? } ?>
						<? if($_GET['error2'] == "video_dir"){ ?>
							<font color="#FFE400" style="font-size: 10;">&nbsp;You could not rename your video directory cause it doesn't exist on your server.<br>
						<? } ?>
						<? if($_GET['error3'] == "sample_dir"){ ?>
							<font color="#FFE400" style="font-size: 10;">&nbsp;You could not rename your sample directory cause it doesn't exist on your server.<br>
						<? } ?>
						<? if($_GET['error4'] == "photog_dir"){ ?>
							<font color="#FFE400" style="font-size: 10;">&nbsp;You could not rename your photographers upload directory cause it doesn't exist on your server.<br>
						<? } ?>
						<?PHP if($_GET['error1'] or $_GET['error2'] or $_GET['error3'] or $_GET['error4']){ ?>
							</td>
						<?PHP } ?>
						<? if($_GET['message'] == "backedup"){ ?>
						<td align="right" valign="bottom">
							<img src="images/mgr_check2_loop_3.gif" valign="absmiddle">
						</td>
						<td align="right" valign="middle" nowrap>
							<font color="#FFE400" style="font-size: 10;">&nbsp;Your settings have been backed up.
						</td>
						<? } ?>
						<? if($_GET['message'] == "restored"){ ?>
						<td align="right" valign="bottom">
							<img src="images/mgr_check2_loop_3.gif" valign="absmiddle">
						</td>
						<td align="right" valign="middle" nowrap>
							<font color="#FFE400" style="font-size: 10;">&nbsp;Your settings have been restored.
						</td>
						<? } ?>
						<? if($_GET['message'] == "updated"){ ?>
						<td align="right" valign="bottom">
							<img src="images/mgr_check2_loop_3.gif" valign="absmiddle">
						</td>
						<td align="right" valign="middle" nowrap>
							<font color="#FFE400" style="font-size: 10;">&nbsp;Your previous backup has been updated.
						</td>
						<? } ?>
					</tr>
					<tr>
						<td>
						<b>Backup & Restore Options:</b>
						<?PHP
						$settings2_result = mysql_query("SELECT id FROM settings where id = '2'", $db);
						$setting2_rows = mysql_num_rows($settings2_result);
						if($setting2_rows == 0){
						?>
						<a href="mgr_actions.php?pmode=backup&nav=<?PHP echo $nav; ?>" class="title_links">Backup</a>
						<?PHP
						}
						$settings2_result = mysql_query("SELECT id FROM settings where id = '2'", $db);
						$setting2_rows = mysql_num_rows($settings2_result);
						if($setting2_rows > 0){
						?>
						  <a href="mgr_actions.php?pmode=restore&nav=<?PHP echo $nav; ?>" class="title_links">Restore</a><br><a href="mgr_actions.php?pmode=backup_update&nav=<?PHP echo $nav; ?>" class="title_links">[UPDATE]</a> - Update the backup to the current settings
						<? } else {?>
						 | You need to make a backup of these settings!
						<? } ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<tr>
			<td bgcolor="#577EC4" align="center" style="padding-top: 10px; padding-bottom: 10px;" background="images/mgr_bg_texture.gif">
				<table width="95%" cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td align="left"><img src="images/mgr_section_header_l.gif"></td>
									<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>MAIN SITE SETTINGS</b></td>
									<td align="right"><img src="images/mgr_section_header_r.gif"></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td valign="middle" bgcolor="#5E85CA" class="data_box">
						<div class="tabber">
							<div class="tabbertab" title="Site">
							<font color="#ffffff" style="font-size: 11;">
							<b>Your basic site settings, your site will use this title, description, keywords, throughout the site where needed. All emails sent to you will be sent to the support email you enter. You can not set the install URL that is auto detected and stored.<br>Avoid using '/"?\|][;:=+)(*&^%$#@!<>, in the title, tag line, directories, descriptions and others if possible or otherwise noted!<br><br></b>
							<input name="onoff" type="checkbox" value="1" <? if($setting->onoff == 1){ echo "checked"; } ?>> <b>Turn site on/off</b><br/>
							<b>Your Website's Name:</b> (ex: John's Travel Photos)<br><input type="text" name="site_title" value="<? echo $setting->site_title; ?>" style="font-size: 10; font-weight: bold; width: 450; border: 1px solid #000000;" maxlength="300"><br/>
							<b>Website Tagline:</b> (Optional | ex: The best travel photos on the web.)<br><input type="text" name="site_tagline" value="<? echo $setting->site_tagline; ?>" style="font-size: 10; font-weight: bold; width: 450; border: 1px solid #000000;" maxlength="300"><br/>
							<b>URL to PhotoStore Install:</b> (ex: http://www.myphotos.com or http://www.myphotos.com/store)<br><input type="text" name="site_url" value="<? echo $setting->site_url; ?>" style="font-size: 10; font-weight: bold; width: 450; border: 1px solid #000000;" maxlength="300"><br/>
							<b>Photo Directory Name:</b> (ex: stock_photos or myphotos)<br><input type="text" name="photo_dir" value="<? echo $setting->photo_dir; ?>" style="font-size: 10; font-weight: bold; width: 450; border: 1px solid #000000;" maxlength="300"><br/>
							<b>Video Directory Name:</b> (ex: stock_videos or myvideos)<br><input type="text" name="video_dir" value="<? echo $setting->video_dir; ?>" style="font-size: 10; font-weight: bold; width: 450; border: 1px solid #000000;" maxlength="300"><br/>
							<b>Sample Video Directory Name:</b> (ex: sample_videos or mysamplevideos)<br><input type="text" name="sample_dir" value="<? echo $setting->sample_dir; ?>" style="font-size: 10; font-weight: bold; width: 450; border: 1px solid #000000;" maxlength="300"><br/>
							<input name="modrw" type="checkbox" value="1" <? if($setting->modrw == 1){ echo "checked"; } ?>> <b>Search Engine Optimized</b><br>(SEO - Requires a server with mod_rewrite ability & proper .htaccess file in place || example instead of details.php?pid=xx&gid=xx it would be galleryname_gxx_imagename_pxx.html<br/>
							<b>Support Email:</b> (Used for visitors to contact you)<br/>
							<input type="text" name="support_email" value="<? echo $setting->support_email; ?>" style="font-size: 10; font-weight: bold; width: 220; border: 1px solid #000000;" maxlength="300"><br/>
							<b>Description METATAG:</b> (The Description Metatag provides a brief description of your website that some search engines use to index your site)<br><textarea name="site_description" style="width: 450; height: 40; border: 1px solid #000000;"><? echo $setting->site_description; ?></textarea><br/>
							<b>Keywords METATAG:</b>  (The Keywords Metatag provides keywords that some search engines use to index your site)<br><textarea name="site_keywords" style="width: 450; height: 40; border: 1px solid #000000;"><? echo $setting->site_keywords; ?></textarea><br/>
							<br><b>To upload a logo click on the "browse" button and select a logo from your computer. It is recommened that the logo be at least 765px wide and any height you would like to have it. After you select your logo click the update button to upload it.<br>To delete a logo put a check in the box next to the logo you want to delete and click on the update button. You can check more than one at a time to delete.<br><br></b>
							<b>Upload Logo:</b> <input type="file" name="logoFile" size="40" maxlength="40"><br>
							<b>Currently Uploaded Images:</b> (If more than one logo is uploaded the store will rotate them)<br>
							<?php	
							$real_dir = realpath("../logo/");
							$dir = opendir($real_dir);
							# LOOP THROUGH THE DIRECTORY
							while($file = readdir($dir)){
							// MAKE SURE IT IS A IMAGE FILE
							$isphp = explode(".", $file);
							if($file != ".." && $file != "." && is_file("../logo/" . $file) && @$isphp[count($isphp) - 1] == "jpg" or @$isphp[count($isphp) - 1] == "gif" or @$isphp[count($isphp) - 1] == "png" or @$isphp[count($isphp) - 1] == "swf" or @$isphp[count($isphp) - 1] == "flv"){
							$replace_char = array(".","+","%","(",")","'","_","!","@","#","<",">","$","^","&","*","-");
							$post = str_replace($replace_char, "", $file);
							$post = trim($post);
							$result = mysql_query("SELECT url FROM links WHERE filename = '$file'", $db);
					     	$rs = mysql_fetch_object($result);
					     	if($rs->url == "nolink"){
								$value = "nolink";
							} else {
								if($rs->url == ""){
									$value = "nolink";
								} else {
									$value = $rs->url;
								}
							}
							echo "<b>URL Link: </b><input type=\"text\" name=\"".$post."_url\" value=\"".$value."\" style=\"font-size: 10; font-weight: bold; width: 220; border: 1px solid #000000;\" maxlength=\"300\"> | <b>Delete:</b> <input type=\"checkbox\" name=\"" . $post . "\" value=\"1\"><a href=\"../logo/".$file."\" target=\"_blank\" class=\"edit_links\"><u>".$file."</u></a><br />";
							}
							unset($isphp);
							unset($value);
							unset($rs);
						}
							?>
					<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
					</font>
					</div>
					<div class="tabbertab" title="Display">
					<font color="#ffffff" style="font-size: 11;">
					<b>These are various settings to control the display output on your store. Make sure to specify the amount of photos to display per page and it can't be set at "0" zero or it will cause errors.<br><br></b>
                    <?php if($nb_partners != 1){ ?>
                    	<input name="pf_feed" type="checkbox" value="1" <? if($setting->pf_feed == 1){ echo "checked"; } ?>> Show <font color="#FFFFFF">PhotographyFeed.com</font> photography news on my site.<br />
					<?php } ?>
					<input name="show_news" type="checkbox" value="1" <? if($setting->show_news == 1){ echo "checked"; } ?>> Show news on the homepage.<br/>
					<input name="show_views" type="checkbox" value="1" <? if($setting->show_views == 1){ echo "checked"; } ?>> Show number of views for each photo on the public site. (In gallery area only)<br/>
					<b>Photos Per Page:</b> (Number of photos to show per page on the public website.)<br><input type="text" name="perpage" value="<? echo $setting->perpage; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Featured Photos:</b> (Number of photos to show on the featured photos section)<br><input type="text" name="featured" value="<? echo $setting->featured; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Newest Photos:</b> (Number of photos to show on the newest photos section)<br><input type="text" name="newest" value="<? echo $setting->newest; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Popular Photos:</b> (Number of photos to show on the most popular photos section)<br><input type="text" name="popular" value="<? echo $setting->popular; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Search Result Photos:</b> (Number of photos to show on the search results section)<br><input type="text" name="search" value="<? echo $setting->search; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<input name="search_onoff" type="checkbox" value="1" <? if($setting->search_onoff == 1){ echo "checked"; } ?>> Display search area? (Display the main search box?)<br/>
					<input name="private_search" type="checkbox" value="1" <? if($setting->private_search == 1){ echo "checked"; } ?>> Allow search to look in private galleries? (It will not show any photos, it will show results and let the users know they need to log into the gallery to see the photo)<br/>
					<b>Columns:</b> (This will change how many colums of photos display on the public site) <br><input type="text" name="dis_columns" value="<? echo $setting->dis_columns; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Thumbnail Size (Pixels):</b> (Max 250) <br><input type="text" name="thumb_width" value="<? echo $setting->thumb_width; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">px<br/>
					<b>Thumbnail Creation Quality:</b> (Max 100, this is the quality level at which the store creates the thumbs only) <br><input type="text" name="upload_thumb_quality" value="<? echo $setting->upload_thumb_quality; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">%<br/>
					<b>Thumbnail Display Quality:</b> (Max 100, this is for the display quality of the thumbs only) <br><input type="text" name="thumb_display_quality" value="<? echo $setting->thumb_display_quality; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">%<br/>
					<input name="show_watermark_thumb" type="checkbox" value="1" <? if($setting->show_watermark_thumb == 1){ echo "checked"; } ?>> Watermark the thumbnail previews<br/>
					<b>Sample Photo Size (Pixels):</b> (Max 600) <br><input type="text" name="sample_width" value="<? echo $setting->sample_width; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">px<br/>
					<b>Sample Creation Quality:</b> (Max 100, this is the quality level at which the store creates the samples only) <br><input type="text" name="upload_sample_quality" value="<? echo $setting->upload_sample_quality; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">%<br/>
					<b>Sample Display Quality:</b> (Max 100, this is for the display quality of the sample images) <br><input type="text" name="sample_display_quality" value="<? echo $setting->sample_display_quality; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">%<br/>
					<b>Sample Video Size:</b> (This is the display size of the sample video on the details page) <br><input type="text" name="sample_size" value="<? echo $setting->sample_size; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<input name="show_watermark" type="checkbox" value="1" <? if($setting->show_watermark == 1){ echo "checked"; } ?>> Watermark the samples and large previews<br/>
					<input name="large_size" type="checkbox" value="1" <? if($setting->large_size == 1){ echo "checked"; } ?>> <b>On / Off</b><br> (Check to turn on. **Photostore will use more disk space on your hosting service by creating the larger sizes for the large preview. This turns the creation of the larger image on/off!)<br/>
					<b>Large Preview Creation Quality:</b> (Max 100, this is the quality level at which the store creates the large preview only) <br><input type="text" name="upload_large_quality" value="<? echo $setting->upload_large_quality; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">%<br/>
					<b>Large Display Quality:</b> (Max 100, this is for the display quality of the enlarged images) <br><input type="text" name="large_display_quality" value="<? echo $setting->large_display_quality; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">%<br/>
					<input name="show_preview" type="checkbox" value="1" <? if($setting->show_preview == 1){ echo "checked"; } ?>> <b>Show large preview of sample images:</b><br> (In image details, will allow another button to view an even larger image. This turns the display on/off!)<br/>
					<b>Large Preview Photo Size (Pixels):</b><br>(This size only effects images being uploaded and those images will stay at this size regardless of changes. New images uploaded will take new size.) <br><input type="text" name="preview_size" value="<? echo $setting->preview_size; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">px<br/>
					<input name="dis_title_gallery" type="checkbox" value="1" <? if($setting->dis_title_gallery == 1){ echo "checked"; } ?>> Show image title under thumbnails in gallery.<br/>
					<input name="dis_title_pri" type="checkbox" value="1" <? if($setting->dis_title_pri == 1){ echo "checked"; } ?>> Show image title under thumbnails in private gallery.<br/>
					<input name="dis_title_search" type="checkbox" value="1" <? if($setting->dis_title_search == 1){ echo "checked"; } ?>> Show image title under thumbnails in search results.<br/>
					<input name="dis_title_featured" type="checkbox" value="1" <? if($setting->dis_title_featured == 1){ echo "checked"; } ?>> Show image title under thumbnails in featured photos.<br/>
					<input name="dis_title_new" type="checkbox" value="1" <? if($setting->dis_title_new == 1){ echo "checked"; } ?>> Show image title under thumbnails in new photos gallery.<br/>
					<input name="dis_title_popular" type="checkbox" value="1" <? if($setting->dis_title_popular == 1){ echo "checked"; } ?>> Show image title under thumbnails in popular photos gallery.<br/>
					<input name="hide_id" type="checkbox" value="1" <? if($setting->hide_id == 1){ echo "checked"; } ?>> Hide PhotoStore's photo id.<br/>
					<input name="dis_filename" type="checkbox" value="1" <? if($setting->dis_filename == 1){ echo "checked"; } ?>> Show image filename as title in all galleries. <br><B>(WARNING!!! This is a security issue display filenames at your own risk, this could make it easier for someone to steal your original images)</b><br/>
						<b>Default Gallery Photo Sort:</b><br />
						Sort By: <select name="sort_by" id="sort_by" style="font-size: 9; width:120;">
																<option value="id" <? if($setting->sort_by == id){ echo "selected"; } ?>>Photo ID</option>
																<option value="title" <? if($setting->sort_by == title){ echo "selected"; } ?>>Photo Title</option>
																<option value="date" <? if($setting->sort_by == date){ echo "selected"; } ?>>Date Added</option>
																<option value="popular" <? if($setting->sort_by == popular){ echo "selected"; } ?>>Most Popular</option>
															</select>
					  Sort Order: <select name="sort_order" id="sort_order" style="font-size: 9; width:120;">
																<option value="ascending" <? if($setting->sort_order == ascending){ echo "selected"; } ?>>Ascending</option>
																<option value="descending" <? if($setting->sort_order == descending){ echo "selected"; } ?>>Descending</option>
															</select><br/>
					<input name="dropdown" type="checkbox" value="1" <? if($setting->dropdown == 1){ echo "checked"; } ?>> Display prints & products list as a dropdown box.<br/>
					<input name="print_info" type="checkbox" value="1" <? if($setting->print_info == 1){ echo "checked"; } ?>> Allow the print info page to be displayed on the public site.<br/>
					<input name="size_info" type="checkbox" value="1" <? if($setting->size_info == 1){ echo "checked"; } ?>> Allow the additional size info page to be displayed on the public site.<br/>
					<input name="no_photo_message" type="checkbox" value="1" <? if($setting->no_photo_message == 1){ echo "checked"; } ?>> Show the "No photos in this category" message in empty categories.<br/>
					<input name="multi_lang" type="checkbox" value="1" <? if($setting->multi_lang == 1){ echo "checked"; } ?>> Allow the site to be viewed in multiple languages selectable by the visitor.<br/>
						<b>Default Language:</b><br>(currently only able to translate parts of the site to possibly give the visitor a chance to use your site and make purchases.)<br>
						<select name="lang" style="font-size: 10; font-weight: bold; width: 450; border: 1px solid #000000;">
						<?
							$language_dir = "../language";
							$l_real_dir = realpath($language_dir);
							$l_dir = opendir($l_real_dir);
							$i = 0;
							// LOOP THROUGH THE PLUGINS DIRECTORY
							while(false !== ($file = readdir($l_dir))){
							$lfile[] = $file;
							}
							//SORT THE CSS FILES IN THE ARRAY
							sort($lfile);
							//GO THROUGH THE ARRAY AND GET FILENAMES
							foreach($lfile as $key => $value){
                //IF FILENAME IS . OR .. OR SLIDESHOW.CSS DO NO SHOW IN THE LIST
                $fname = strip_ext($lfile[$key]);
                if($fname != ".." && $fname != "."){
                  if($setting->lang == $fname ){//. ".php"){
                    echo "<option selected>" . $fname . "</option>";
                  } elseif(trim($fname) != "") {
                    echo "<option>" . $fname . "</option>";
                  }
                }
							}
								
						?>
						</select><br/>
					<input name="leftbox1" type="checkbox" value="1" <? if($setting->leftbox1 == 1){ echo "checked"; } ?>> Show Left Box 1 In the left menu?<br/>
					<input name="leftbox2" type="checkbox" value="1" <? if($setting->leftbox2 == 1){ echo "checked"; } ?>> Show Left Box 2 In the left menu?<br/>
					<input name="leftbox3" type="checkbox" value="1" <? if($setting->leftbox3 == 1){ echo "checked"; } ?>> Show Left Box 3 In the left menu?<br/>
					<input name="leftbox4" type="checkbox" value="1" <? if($setting->leftbox4 == 1){ echo "checked"; } ?>> Show Left Box 4 In the left menu?<br/>
					<input name="leftbox5" type="checkbox" value="1" <? if($setting->leftbox5 == 1){ echo "checked"; } ?>> Show Left Box 5 In the left menu?<br/>
					<input name="leftbox6" type="checkbox" value="1" <? if($setting->leftbox6 == 1){ echo "checked"; } ?>> Show Left Box 6 In the left menu?<br/>
					<input name="headerbox" type="checkbox" value="1" <? if($setting->headerbox == 1){ echo "checked"; } ?>> Show Header Box In the Header of the page?<br/>
					<input name="footerbox" type="checkbox" value="1" <? if($setting->footerbox == 1){ echo "checked"; } ?>> Show Footer Box In the Footer of the page?<br/><br/>
					<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
					</font>
					</div>
					<div class="tabbertab" title="Menu">
					<font color="#ffffff" style="font-size: 11;">
					<b>Various settings for the left menu of your public store. You can choose between a classic menu or the new collapsible menu. If you choose to show the collapsible menu make sure to click on the categories tab and click on the red button near the top called "Create Site Menu" to generate a menu. Anytime you make changes to your categories you will need to regenerate that menu again by clicking that button. The collapsible menu is a static menu and it doesn't get updated automatically.<br><br></b>
					<input name="menu_click" type="checkbox" value="1" <? if($setting->menu_click == 1){ echo "checked"; } ?>> Menu expand on click.<br><B>(Check this if you wish for clicking on the categories title expand the tree menu to show all sub categories)</b><br/>
					<input name="show_private" type="checkbox" value="1" <? if($setting->show_private == 1){ echo "checked"; } ?>> Show private categories in the categories list. <br><B>(Still requires login to view photos)</b><br/>
					<input name="show_tree" type="checkbox" value="1" <? if($setting->show_tree == 1){ echo "checked"; } ?>> Show collapsible tree style photo category menu instead of default menu.<br>Make sure to build menu using the "Update Main Site Menu Now" link in the categories tab or <a href="./menu_creator.php" class="title_links" target="_blank"><b>click here</b></a><br>Anytime you make a change to the categories make sure to rebuild it!<br/>
					<input name="show_stats" type="checkbox" value="1" <? if($setting->show_stats == 1){ echo "checked"; } ?>> Show site statistics in the left menu (includes total members, photos, photographers, visitors).<br/>
					<input name="show_num" type="checkbox" value="1" <? if($setting->show_num == 1){ echo "checked"; } ?>> Show the number of images in each category next to the category name on the public site.<br/>
					<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
					</font>
					</div>
					<div class="tabbertab" title="Sell">
					<font color="#ffffff" style="font-size: 11;">
					<b>These settings control the stores selling ability. You can disable or enable the ability to sell prints & products, or digital downloads.<br><br></b>
					<input name="allow_digital" type="checkbox" value="1" <? if($setting->allow_digital == 1){ echo "checked"; } ?>> Allow digital photos to be purchased.<br/>
					<input name="allow_prints" type="checkbox" value="1" <? if($setting->allow_prints == 1){ echo "checked"; } ?>> Allow prints & products to be purchased.<br/>
					<b>Default Photo Price:</b> (If no price for a photo is entered default to this price. Do not include $)<br><input type="text" name="default_price" value="<? echo $setting->default_price; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Minimum Cart Price:</b> (This is the minimun cart price before the visitor will see a checkout button. Enter 0.00 to disable. Do not include $)<br><input type="text" name="cart_price" value="<? echo $setting->cart_price; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<input name="tos_check" type="checkbox" value="1" <? if($setting->tos_check == 1){ echo "checked"; } ?>> Show TOS Agreement: (Terms of Service on Checkout Page, see content editor to edit the TOS.)<br/>
					<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
					</font>
					</div>
					<div class="tabbertab" title="Orders">
					<font color="#ffffff" style="font-size: 11;">
					<b>Settings used to control order options. If you want customers to be able to download right away after using a credit card payment online them make sure to check the option to "force approve" all orders. This force approval does not affect the check/money order payment option.<br><br></b>
					<b>Orders Expire After:</b> (Number of days a customers can download photos from their order)<br><input type="text" name="download_days" value="<? echo $setting->download_days; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">Days<br/>
					<input name="force_approve" type="checkbox" value="1" <? if($setting->force_approve == 1){ echo "checked"; } ?>> Force the approval of all credit card orders so the customer can download right away without you approving the order (on all completed successful transactions)<br/>
					<input name="free_approve" type="checkbox" value="1" <? if($setting->free_approve == 1){ echo "checked"; } ?>> Force the approval of all free orders. Checking this will allow a free order to be downloaded after a sucessful order is placed and the coupon is used to make the order free.<br/>
					<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
					</font>
					</div>
					<div class="tabbertab" title="Browser">
					<font color="#ffffff" style="font-size: 11;">
					<b>Few browser options you can use to tweak your store.<br><br></b>
					<input name="no_cache" type="checkbox" value="1" <? if($setting->no_cache == 1){ echo "checked"; } ?>> Disable page cache (may need it on for compatibility with your server but will use more bandwidth, try running without first).<br/>
					<input name="no_right_click" type="checkbox" value="1" <? if($setting->no_right_click == 1){ echo "checked"; } ?>> Disable right clicking and IE image toolbar.<br/>
					<b>Character set</b> (charset, the default is iso-8859-1):</b><br><input type="text" name="charset" value="<? echo $setting->charset; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Email Template Character set</b> (charset, the default is blank, nothing, and leave blank unless you know what your doing.):</b><br><input type="text" name="emailchar" value="<? echo $setting->emailchar; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<input name="force_mac" type="checkbox" value="1" <? if($setting->force_mac == 1){ echo "checked"; } ?>> Force content editor in MAC. (Only works for IE and FF on Mac, will not work for safari no matter what)<br/>
					<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
					</font>
					</div>
					<div class="tabbertab" title="Debug">
					<font color="#ffffff" style="font-size: 11;">
					<b>You will not need to check this, this is used for development only<br><br></b>
					<input name="debug" type="checkbox" value="1" <? if($setting->debug == 1){ echo "checked"; } ?>> Show debug screen on top of each page.<br/>
					<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
					</font>
					</div>
					</div>
					</td>
				</tr>
				<tr>
						<td height="5"></td>
				</tr>
				<tr>
						<td>
							<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td align="left"><img src="images/mgr_section_header_l.gif"></td>
									<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>FEATURE SETTINGS</b></td>
									<td align="right"><img src="images/mgr_section_header_r.gif"></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
					<td valign="middle" bgcolor="#5E85CA" class="data_box">
				  <div class="tabber">
				  <div class="tabbertab" title="Hover View">
					<font color="#ffffff" style="font-size: 11;">
					<b>These settings control the stores public hover view feature.<br><br></b>
					<input name="hover_on" type="checkbox" value="1" <? if($setting->hover_on == 1){ echo "checked"; } ?>> Turn the mouse over effect (Hover View) on/off for the entire site.<br/>
					<input name="hover_usr" type="checkbox" value="1" <? if($setting->hover_usr == 1){ echo "checked"; } ?>> Allow visitors to turn the hover view on/off.<br/>
					<input name="hover_feature" type="checkbox" value="1" <? if($setting->hover_feature == 1){ echo "checked"; } ?>> Allow hover view on the featured photos page.<br/>
					<input name="hover_new" type="checkbox" value="1" <? if($setting->hover_new == 1){ echo "checked"; } ?>> Allow hover view on the newest photos page.<br/>
					<input name="hover_popular" type="checkbox" value="1" <? if($setting->hover_popular == 1){ echo "checked"; } ?>> Allow hover view on the most popular photos page.<br/>
					<input name="hover_gallery" type="checkbox" value="1" <? if($setting->hover_gallery == 1){ echo "checked"; } ?>> Allow hover view on the gallery pages.<br/>
					<input name="hover_pri" type="checkbox" value="1" <? if($setting->hover_pri == 1){ echo "checked"; } ?>> Allow hover view on the private gallery pages.<br/>
					<input name="hover_search" type="checkbox" value="1" <? if($setting->hover_search == 1){ echo "checked"; } ?>> Allow hover view on the search results page.<br/>
					<b>Hover Display Size:</b> (Max 600px, This is the size of the image being displayed in the hover view) <br><input type="text" name="hover_size" value="<? echo $setting->hover_size; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Hover Display Quality:</b> (Max 100, This is the quality of the image being displayed in the hover view) <br><input type="text" name="hover_display_quality" value="<? echo $setting->hover_display_quality; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">%<br/>
					<input name="show_watermark_hover" type="checkbox" value="1" <? if($setting->show_watermark_hover == 1){ echo "checked"; } ?>> Watermark the hover view previews<br/>
					<b>Description Length:</b> (This is the length of the description displayed on the hover view) <br><input type="text" name="description_length" value="<? echo $setting->description_length; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
					</font>
					</div>
					<div class="tabbertab" title="Ratings">
					<font color="#ffffff" style="font-size: 11;">
					<b>Use these settings to control the photo rating system. You can turn it off/on for the public site, or just allow it for members only.<br><br></b>
					<input name="rate_on" type="checkbox" value="1" <? if($setting->rate_on == 1){ echo "checked"; } ?>> Turn the photo rating system on/off for the entire site.<br/>
					<input name="member_rate" type="checkbox" value="1" <? if($setting->member_rate == 1){ echo "checked"; } ?>> Allow members only (accounts) to use the rating system.<br/>
					<input name="sr_featured" type="checkbox" value="1" <? if($setting->sr_featured == 1){ echo "checked"; } ?>> Check to display rating on the featured photo page under the image.<br/>
					<input name="sr_gallery" type="checkbox" value="1" <? if($setting->sr_gallery == 1){ echo "checked"; } ?>> Check to display rating on the gallery photo page under the image.<br/>
					<input name="sr_pri" type="checkbox" value="1" <? if($setting->sr_pri == 1){ echo "checked"; } ?>> Check to display rating on the private gallery page under the image.<br/>
					<input name="sr_new" type="checkbox" value="1" <? if($setting->sr_new == 1){ echo "checked"; } ?>> Check to display rating on the new photo page under the image.<br/>
					<input name="sr_pop" type="checkbox" value="1" <? if($setting->sr_pop == 1){ echo "checked"; } ?>> Check to display rating on the popular photo page under the image.<br/>
					<input name="sr_search" type="checkbox" value="1" <? if($setting->sr_search == 1){ echo "checked"; } ?>> Check to display rating on the search result page under the image.<br/>
					<input name="sr_photog" type="checkbox" value="1" <? if($setting->sr_photog == 1){ echo "checked"; } ?>> Check to display rating on the photographers photo page under the image.<br/>
					<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
					</font>
					</div>
					<div class="tabbertab" title="SlideShow">
					<font color="#ffffff" style="font-size: 11;">
					<b>Settings to adjust the slide show preview<br><br></b>
					<b>Default Speed:</b> (1000 = 1 second)<br><input type="text" name="slide_speed" value="<? echo $setting->slide_speed; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Default Slide Animation:</b><br><input type="radio" name="slide_type" value="fade" <? if($setting->slide_type == "fade"){ echo "CHECKED"; }?>>Fade | <input type="radio" name="slide_type" value="slide" <? if($setting->slide_type == "slide"){ echo "CHECKED"; }?>>Slide<br/>
					<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
					</font>
					</div>
					<? if(file_exists("../photog_main.php")){ ?>
					<div class="tabbertab" title="Photographers">
					<font color="#ffffff" style="font-size: 11;">
					<b>These settings control the photographers addon and gives you the ability to turn uploads on/off, or allow photographers to sell other sizes.<br><br></b>
					<b>Default Uploading Directory:</b><br>(Example photog_upload <b>DO NOT CHANGE THIS UNLESS YOU RENAME YOUR DIRECTORY MANUALLY</b>)<br><input type="text" name="photog_dir" value="<? echo $setting->photog_dir; ?>" style="font-size: 10; font-weight: bold; width: 300; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Default Commission Level:</b> (This is your commission off of each photographers image being sold)<br><input type="text" name="com_level" value="<? echo $setting->com_level; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">%<br/>
					<b>Default Image Upload Price:</b><br>(This is price that is suggested or used for the original photographer uploaded image.)<br><input type="text" name="photog_price" value="<? echo $setting->photog_price; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"> (Do not include the $ sign)<br/>
					<input name="photog_reg" type="checkbox" value="1" <? if($setting->photog_reg == 1){ echo "checked"; } ?>> Allow photographers to register?<br/>
					<input name="photog_upload" type="checkbox" value="1" <? if($setting->photog_upload == 1){ echo "checked"; } ?>> Allow Photographers to upload photos. (If this is checked the photographer will be allowed to upload using either the flash batch upload if turned on below, or the upload form.)<br/>
					<input name="photog_batch_upload" type="checkbox" value="1" <? if($setting->photog_batch_upload == 1){ echo "checked"; } ?>> Allow Photographers to batch upload photos. (If this is checked the photographer will be allowed to batch upload using a flash based uploader.)<br/>
					<input name="photog_edit" type="checkbox" value="1" <? if($setting->photog_edit == 1){ echo "checked"; } ?>> Allow Photographers to edit photos. (If this is checked the photographer will be allowed to edit their photos.)<br/>
					<input name="appc" type="checkbox" value="1" <? if($setting->appc == 1){ echo "checked"; } ?>> Allow Photographers to change the price (If this is checked the photographer will be allowed to change the default price above.)<br/>
					<input name="photog_old_sizes" type="checkbox" value="1" <? if($setting->photog_old_sizes == 1){ echo "checked"; } ?>> Allow the older other sizes area to be displayed?<br>(This is the old way the store use to make "other sizes" of the same upload. This way uses more of your hosting space.)<br/>
					<input name="photog_new_sizes" type="checkbox" value="1" <? if($setting->photog_new_sizes == 1){ echo "checked"; } ?>> Allow the new other sizes area to be displayed?<br>(If you wish to allow your photographer to create other sizes of the image uploaded for sale then this is the prefered way. Also see the sizes tab in this store manager to specify additional sizes and prices to sell them for.)<br/>
					<input name="photog_sizes_locked" type="checkbox" value="1" <? if($setting->photog_sizes_locked == 1){ echo "checked"; } ?>> Lock the new other sizes area? <br>(If you check this box photographers will not be able to specify which sizes they want to additional create. Any sizes that are able to be created from the original image size will be checked and sold on the store automatically.)<br/>
					<input name="photog_upload_email" type="checkbox" value="1" <? if($setting->photog_upload_email == 1){ echo "checked"; } ?>> Get emails when photographers upload photos?<br/>
					<b>Minimum photo size width allowed:</b> (set to 0 to allow any)<br><input type="text" name="photog_size_width" value="<? echo $setting->photog_size_width; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">(px)<br/>
					<b>Minimum photo size height allowed:</b> (set to 0 to allow any)<br><input type="text" name="photog_size_height" value="<? echo $setting->photog_size_height; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">(px)<br/>
					<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
					</font>
					</div>
					<?PHP } ?>
					<?PHP if(file_exists("../swf/thumbslide.swf")){ ?>
					<input type="hidden" name="thumb_slide_orientation" value="horizontal">
					<input type="hidden" name="thumb_slide_reverserollovereffect" value="">
					<div class="tabbertab" title="Thumbslide">
					<font color="#ffffff" style="font-size: 11;">
					<b>These settings control the flash based thumbnail slider under the photo on the photo details page.<br><br></b>
					<input name="flash_thumb_on" type="checkbox" value="1" <? if($setting->flash_thumb_on == 1){ echo "checked"; } ?>> Turn this feature on/off?<br>
					<b>Number of Photos:</b> (This is the number of photos in the thumbslide to display.)<br><input type="text" name="thumb_slide_count" value="<? echo $setting->thumb_slide_count; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Slide Method:</b><br>
					<SELECT name="thumb_slide_arrowcontrol">
						<option <?PHP if($setting->thumb_slide_arrowcontrol == "click"){ echo "selected"; } ?> value="click">Click</option>
						<option <?PHP if($setting->thumb_slide_arrowcontrol == "rollOver"){ echo "selected"; } ?> value="rollOver">Roll Over</option>
						<option <?PHP if($setting->thumb_slide_arrowcontrol == "mouseMove"){ echo "selected"; } ?> value="mouseMove">Mouse Move</option>
					</SELECT><br>
					<b>Back Ground Color:</b> (enter hex value || example 0x000000 = black and 0xFFFFFF = white)</b><br><input type="text" name="thumb_slide_bgcolor" value="<? echo $setting->thumb_slide_bgcolor; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Photo Preloader:</b><br>
					<SELECT name="thumb_slide_builtinpreloader">
						<option <?PHP if($setting->thumb_slide_builtinpreloader == "none"){ echo "selected"; } ?> value="none">None</option>
						<option <?PHP if($setting->thumb_slide_builtinpreloader == "bar"){ echo "selected"; } ?> value="bar">Bar</option>
						<!--<option <?PHP if($setting->thumb_slide_builtinpreloader == "circular"){ echo "selected"; } ?> value="circular">Circular</option>-->
					</SELECT><br>
					<b>Preloader Color:</b> (enter hex value || example 0x000000 = black and 0xFFFFFF = white)</b><br><input type="text" name="thumb_slide_preloadercolor" value="<? echo $setting->thumb_slide_preloadercolor; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<input name="thumb_slide_border" type="checkbox" value="1" <? if($setting->thumb_slide_border == 1){ echo "checked"; } ?>> Show photo borders? <br>(If you check this box the photos in the slide will have borders.)<br/>
					<b>Border Color:</b> (enter hex value || example 0x000000 = black and 0xFFFFFF = white)</b><br><input type="text" name="thumb_slide_bordercolor" value="<? echo $setting->thumb_slide_bordercolor; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Border Corner Radius:</b> (rounds the corners off)</b><br><input type="text" name="thumb_slide_bordercornerradius" value="<? echo $setting->thumb_slide_bordercornerradius; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Border Size:</b><br><input type="text" name="thumb_slide_bordersize" value="<? echo $setting->thumb_slide_bordersize; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Slide Movements:</b><br>
					<SELECT name="thumb_slide_easetype">
						<option <?PHP if($setting->thumb_slide_easetype == "None"){ echo "selected"; } ?> value="None">None</option>
						<option <?PHP if($setting->thumb_slide_easetype == "Back"){ echo "selected"; } ?> value="Back">Back</option>
						<option <?PHP if($setting->thumb_slide_easetype == "Bounce"){ echo "selected"; } ?> value="Bounce">Bounce</option>
						<option <?PHP if($setting->thumb_slide_easetype == "Elastic"){ echo "selected"; } ?> value="Elastic">Elastic</option>
						<option <?PHP if($setting->thumb_slide_easetype == "Regular"){ echo "selected"; } ?> value="Regular">Regular</option>
						<option <?PHP if($setting->thumb_slide_easetype == "Strong"){ echo "selected"; } ?> value="Strong">Strong</option>
					</SELECT><br>
					<b>Roll Over Effect:</b><br>
					<SELECT name="thumb_slide_rollovereffect">
						<option <?PHP if($setting->thumb_slide_rollovereffect == "none"){ echo "selected"; } ?> value="none">None</option>
						<option <?PHP if($setting->thumb_slide_rollovereffect == "blur"){ echo "selected"; } ?> value="blur">Blur</option>
						<!--<option <?PHP if($setting->thumb_slide_rollovereffect == "black&white"){ echo "selected"; } ?> value="black&white">Grayscale</option> -->
						<option <?PHP if($setting->thumb_slide_rollovereffect == "alpha"){ echo "selected"; } ?> value="alpha">Alpha</option>
						<option <?PHP if($setting->thumb_slide_rollovereffect == "brightness"){ echo "selected"; } ?> value="brightness">Brightness</option>
						<option <?PHP if($setting->thumb_slide_rollovereffect == "distortion"){ echo "selected"; } ?> value="distortion">Distortion</option>
						<option <?PHP if($setting->thumb_slide_rollovereffect == "colorLight"){ echo "selected"; } ?> value="colorLight">Color Light</option>
						<option <?PHP if($setting->thumb_slide_rollovereffect == "scaleBorder"){ echo "selected"; } ?> value="scaleBorder">Scale Border</option>
						<option <?PHP if($setting->thumb_slide_rollovereffect == "scale"){ echo "selected"; } ?> value="scale">Scale Photo</option>
					</SELECT><br>
					<b>Effect Amount:</b><br><input type="text" name="thumb_slide_effectamount" value="<? echo $setting->thumb_slide_effectamount; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Effect Time In:</b><br><input type="text" name="thumb_slide_effecttimein" value="<? echo $setting->thumb_slide_effecttimein; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Effect Time Out:</b><br><input type="text" name="thumb_slide_effecttimeout" value="<? echo $setting->thumb_slide_effecttimeout; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Resize Type:</b><br>
					<SELECT name="thumb_slide_resizetype">
						<option <?PHP if($setting->thumb_slide_resizetype == "noscale"){ echo "selected"; } ?> value="noscale">No Scale</option>
						<option <?PHP if($setting->thumb_slide_resizetype == "resize"){ echo "selected"; } ?> value="resize">Resize</option>
						<option <?PHP if($setting->thumb_slide_resizetype == "scale"){ echo "selected"; } ?> value="scale">Scale</option>
						<!-- <option <?PHP if($setting->thumb_slide_resizetype == "crop"){ echo "selected"; } ?> value="crop">Crop</option> -->
					</SELECT><br>
					<b>Photo Spacing:</b><br><input type="text" name="thumb_slide_spacing" value="<? echo $setting->thumb_slide_spacing; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<!--- <b>Photo Height:</b><br><input type="text" name="thumb_slide_thumbheight" value="<? echo $setting->thumb_slide_thumbheight; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/> --->
					<!--- <b>Photo Width:</b><br><input type="text" name="thumb_slide_thumbwidth" value="<? echo $setting->thumb_slide_thumbwidth; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/> --->
					<b>Scroll Speed:</b><br><input type="text" name="thumb_slide_speed" value="<? echo $setting->thumb_slide_speed; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
				  </font>
					</div>
					<?PHP } ?>
					<?PHP if(file_exists("../swf/featured.swf")){ ?>
					<div class="tabbertab" title="Featured">
					<font color="#ffffff" style="font-size: 11;">
					<b>These settings control the flash based featured photo section on your homepage of your store.<br><br></b>
					<input name="flash_featured_on" type="checkbox" value="1" <? if($setting->flash_featured_on == 1){ echo "checked"; } ?>> Turn this feature on/off?<br>
					<b>Background Color:</b> (This is the color of the background behind the show.)<br><input type="text" name="pf_bgcolor" value="<? echo $setting->pf_bgcolor; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>		
					<input name="pf_mousewheelflip" type="checkbox" value="1" <? if($setting->pf_mousewheelflip == 1){ echo "checked"; } ?>> Allow Mouse Wheel Flipping? <br>(If you check this box the photos in the slide will have borders.)<br/>
					<b>Auto Flip Seconds:</b> (Set to 0 for no auto flipping)<br><input type="text" name="pf_autoflipseconds" value="<? echo $setting->pf_autoflipseconds; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<!-- <b>Flip Sound:</b> (Must upload the sound file || Example mysound.wav OR /sound/mysound.wave)<br><input type="text" name="pf_flipsound" value="<? echo $setting->pf_flipsound; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/> --->
					<b>Flip Speed:</b> (Speed at which the images flip)<br><input type="text" name="pf_flipspeed" value="<? echo $setting->pf_flipspeed; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<input name="pf_namebold" type="checkbox" value="1" <? if($setting->pf_namebold == 1){ echo "checked"; } ?>> Bold Photo Title? <br>(If you check this box the title of the photo will be bold lettering.)<br/>
					<b>Title Text Color:</b><br><input type="text" name="pf_namecolor" value="<? echo $setting->pf_namecolor; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Title Text Distance: (Distance from the center of the show)</b><br><input type="text" name="pf_namedistance" value="<? echo $setting->pf_namedistance; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Title Text Position:</b><br>
					<SELECT name="pf_nameposition">
						<option <?PHP if($setting->pf_nameposition == "top left"){ echo "selected"; } ?> value="top left">Top Left</option>
						<option <?PHP if($setting->pf_nameposition == "top right"){ echo "selected"; } ?> value="top right">Top Right</option>
						<option <?PHP if($setting->pf_nameposition == "top center"){ echo "selected"; } ?> value="top center">Top Center</option>
						<option <?PHP if($setting->pf_nameposition == "bottom left"){ echo "selected"; } ?> value="bottom left">Bottom Left</option>
						<option <?PHP if($setting->pf_nameposition == "bottom right"){ echo "selected"; } ?> value="bottom right">Bottom Right</option>
						<option <?PHP if($setting->pf_nameposition == "bottom center"){ echo "selected"; } ?> value="bottom center">Bottom Center</option>
					</SELECT><br>
					<input name="pf_showname" type="checkbox" value="1" <? if($setting->pf_showname == 1){ echo "checked"; } ?>> Show Photo Title? <br>(If you check this box the photo title will be displayed on selection.)<br/>
					<b>Title Text Size:</b><br><input type="text" name="pf_namesize" value="<? echo $setting->pf_namesize; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Title Text Font:</b><br><input type="text" name="pf_namefont" value="<? echo $setting->pf_namefont; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Preload Amount:</b> (The amount to preload before display starts)<br><input type="text" name="pf_preloadset" value="<? echo $setting->pf_preloadset; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Horizontal Perspective:</b><br><input type="text" name="pf_hpers" value="<? echo $setting->pf_hpers; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Vertical Perspective:</b><br><input type="text" name="pf_vpers" value="<? echo $setting->pf_vpers; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Viewing Perspective:</b><br><input type="text" name="pf_view" value="<? echo $setting->pf_view; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<input name="pf_showreflection" type="checkbox" value="1" <? if($setting->pf_showreflection == 1){ echo "checked"; } ?>> Show Reflections? <br>(If you check this box the photo will cast reflections.)<br/>
					<b>Reflection Alpha:</b> (opacy / alpha || lower the lighter the reflection)<br><input type="text" name="pf_reflectionalpha" value="<? echo $setting->pf_reflectionalpha; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Reflection Depth:</b><br><input type="text" name="pf_reflectiondepth" value="<? echo $setting->pf_reflectiondepth; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Reflection Distance:</b> (Space between photo and reflection)<br><input type="text" name="pf_reflectiondistance" value="<? echo $setting->pf_reflectiondistance; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>	
					<b>Reflection Extended:</b><br><input type="text" name="pf_reflectionextend" value="<? echo $setting->pf_reflectionextend; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Middle Photo Reflection Alpha:</b> (same as reflection alpha || lower the lighter the reflection)<br><input type="text" name="pf_selectedreflectionalpha" value="<? echo $setting->pf_selectedreflectionalpha; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Photo Height:</b> (Max is 250px)<br><input type="text" name="pf_photoheight" value="<? echo $setting->pf_photoheight; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">px<br/>	
					<b>Photo Width:</b> (Max is 250px)<br><input type="text" name="pf_photowidth" value="<? echo $setting->pf_photowidth; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">px<br/>
					<b>Middle Photo Distance:</b> (y position, distance from the top)<br><input type="text" name="pf_selectedy" value="<? echo $setting->pf_selectedy; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Photo Start ID:</b> (The first photo to be shown centered in the show.)<br><input type="text" name="pf_defaultid" value="<? echo $setting->pf_defaultid; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>	
					<b>Photo Holder Alpha:</b> (This is the alpha of the background the photo is on.)<br><input type="text" name="pf_holderalpha" value="<? echo $setting->pf_holderalpha; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Photo Holder Border Alpha:</b> (Controls the alpha of the border on the holder.)<br><input type="text" name="pf_holderborderalpha" value="<? echo $setting->pf_holderborderalpha; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Photo Holder Border Color:</b> (Controls the color of the border on the holder.)<br><input type="text" name="pf_holderbordercolor" value="<? echo $setting->pf_holderbordercolor; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Photo Holder Color:</b> (This is the color of the background the photo is on.)<br><input type="text" name="pf_holdercolor" value="<? echo $setting->pf_holdercolor; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>		
					<b>Photo Scale Mode:</b><br>
					<SELECT name="pf_scalemode">
						<option <?PHP if($setting->pf_scalemode == "noScale"){ echo "selected"; } ?> value="noScale">No Scale</option>
						<option <?PHP if($setting->pf_scalemode == "showAll"){ echo "selected"; } ?> value="showAll">Show All</option>
						<option <?PHP if($setting->pf_scalemode == "scaleToFit"){ echo "selected"; } ?> value="scaleToFit">Scale To Fit</option>
					</SELECT><br>
					<b>Middle Photo Scaling:</b> (How much should the center photo scale.)<br><input type="text" name="pf_selectedscale" value="<? echo $setting->pf_selectedscale; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Photo spacing:</b> (Distance between photos.)<br><input type="text" name="pf_spacing" value="<? echo $setting->pf_spacing; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Photo Zoom:</b> (How much should the photo zoom in when you click on one.)<br><input type="text" name="pf_zoom" value="<? echo $setting->pf_zoom; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					<b>Zoom Which:</b> (Which photos to zoom?)<br>
					<SELECT name="pf_zoomtype">
						<option <?PHP if($setting->pf_zoomtype == "none"){ echo "selected"; } ?> value="none">None</option>
						<option <?PHP if($setting->pf_zoomtype == "all"){ echo "selected"; } ?> value="all">All</option>
						<option <?PHP if($setting->pf_zoomtype == "selected"){ echo "selected"; } ?> value="selected">Selected</option>
						<option <?PHP if($setting->pf_zoomtype == "notSelected"){ echo "selected"; } ?> value="notSelected">Not Selected</option>
					</SELECT><br>
					<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
					</font>
					</div>
					<?PHP } ?>
          <?PHP if(file_exists("../swf/photoloader.swf")){ ?>
            <div class="tabbertab" title="Transitions">
						<font color="#ffffff" style="font-size: 11;">
                        <input name="flashthumbs" type="checkbox" value="1" <? if($setting->flashthumbs == 1){ echo "checked"; } ?>> Flash effect on thumbnails (check to enable) <br/>
                        <input name="flashsamples" type="checkbox" value="1" <? if($setting->flashsamples == 1){ echo "checked"; } ?>> Flash effect on sample photos (check to enable) <br/>
                       	<br />Effect Type<br />
                        <SELECT name="flashtrans">
                            <option <?PHP if($setting->flashtrans == "photoloader1.swf"){ echo "selected"; } ?> value="photoloader1.swf">Fade</option>
                            <option <?PHP if($setting->flashtrans == "photoloader2.swf"){ echo "selected"; } ?> value="photoloader2.swf">Pixelate</option>
                            <option <?PHP if($setting->flashtrans == "photoloader3.swf"){ echo "selected"; } ?> value="photoloader3.swf">Blur</option>
                            <option <?PHP if($setting->flashtrans == "photoloader4.swf"){ echo "selected"; } ?> value="photoloader4.swf">Stretch</option>
                        </SELECT>
                        <p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
						</font>
					</div>
                    <?PHP } ?>
					</div>
                    
						</td>
					</tr>
					<tr>
						<td height="5"></td>
					</tr>
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td align="left"><img src="images/mgr_section_header_l.gif"></td>
									<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>PAYMENT OPTIONS</b></td>
									<td align="right"><img src="images/mgr_section_header_r.gif"></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td valign="middle" bgcolor="#5E85CA" class="data_box">
							<div class="tabber">
							<div class="tabbertab" title="Currency">
							<font color="#ffffff" style="font-size: 11;">
							<b>Select which currency you wish to use.<br><br></b>
							<b>Select Currency:</b> (Current Currency = <? echo $currency->code; ?>)<br>
						<?php
  					$checkValue="$currency->code";
						$chkArr=array("USD"=>"","EUR"=>"","JPY"=>"","CAD"=>"","GBP"=>"","AUD"=>"","NZD"=>"","CHF"=>"","HKD"=>"","SGD"=>"","SEK"=>"","DKK"=>"","PLN"=>"","NOK"=>"","HUF"=>"","CZK"=>"","ZAR"=>"");
						$chkArr[$checkValue]="checked";

						echo "<input type=\"radio\" name=\"code\" value=\"USD\" ".$chkArr["USD"].">USA Dollar<br \>";
						echo "<input type=\"radio\" name=\"code\" value=\"EUR\" ".$chkArr["EUR"].">Europe Euro<br \>";
						echo "<input type=\"radio\" name=\"code\" value=\"JPY\" ".$chkArr["JPY"].">Japan Yen<br \>";
						echo "<input type=\"radio\" name=\"code\" value=\"CAD\" ".$chkArr["CAD"].">Canada Dollar<br \>";
						echo "<input type=\"radio\" name=\"code\" value=\"GBP\" ".$chkArr["GBP"].">Great Britain Pound<br \>";
						echo "<input type=\"radio\" name=\"code\" value=\"AUD\" ".$chkArr["AUD"].">Australia Dollar<br \>";
						echo "<input type=\"radio\" name=\"code\" value=\"NZD\" ".$chkArr["NZD"].">New Zealand Dollar<br \>";
						echo "<input type=\"radio\" name=\"code\" value=\"CHF\" ".$chkArr["CHF"].">Swiss Franc<br \>";
						echo "<input type=\"radio\" name=\"code\" value=\"HKD\" ".$chkArr["HKD"].">Hong Kong Dollar<br \>";
						echo "<input type=\"radio\" name=\"code\" value=\"SGD\" ".$chkArr["SGD"].">Singapore Dollar<br \>";
						echo "<input type=\"radio\" name=\"code\" value=\"SEK\" ".$chkArr["SEK"].">Swedish Krona<br \>";
						echo "<input type=\"radio\" name=\"code\" value=\"DKK\" ".$chkArr["DKK"].">Danish Krone<br \>";
						echo "<input type=\"radio\" name=\"code\" value=\"PLN\" ".$chkArr["PLN"].">Polish Zloty<br \>";
						echo "<input type=\"radio\" name=\"code\" value=\"NOK\" ".$chkArr["NOK"].">Norwegian Krone<br \>";
						echo "<input type=\"radio\" name=\"code\" value=\"HUF\" ".$chkArr["HUF"].">Hungarian Forint<br \>";
						echo "<input type=\"radio\" name=\"code\" value=\"CZK\" ".$chkArr["CZK"].">Czech Koruna<br \>";
						echo "<input type=\"radio\" name=\"code\" value=\"ZAR\" ".$chkArr["ZAR"].">South African Rands (MyGate.co.za Only)<br \>";	
?>
							<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
							</font>
							</div>
							<div class="tabbertab" title="Shipping">
							<font color="#ffffff" style="font-size: 11;">
							<b>Use this to set the fixed shipping rate based on the amount purchased. If you don't want to use this then leave all price fields set at 0.00.<br><br></b>
							<b>Fixed Shipping Rates:</b> (If you have any prices set, it will bypass the prints & products shipping rates)<br>
							<b>Do not enter any "$" dollar signs! </b>(example: $4.30 is wrong, 4.30 is correct)</font><br>
							<b>Any cart total between "From" and "To" = shipping "Price" that is charged for that range</b><br>
							From: <input type="text" name="fix_cart1" value="<? echo $setting->fix_cart1; ?>" style="font-size: 10; font-weight: bold; width: 150; border: 1px solid #000000;" maxlength="300"> To: <input type="text" name="fix_cart2" value="<? echo $setting->fix_cart2; ?>" style="font-size: 10; font-weight: bold; width: 150; border: 1px solid #000000;" maxlength="300"> Price: <input type="text" name="fix_price1" value="<? echo $setting->fix_price1; ?>" style="font-size: 10; font-weight: bold; width: 150; border: 1px solid #000000;" maxlength="300"><br>
  						From: <input type="text" name="fix_cart3" value="<? echo $setting->fix_cart3; ?>" style="font-size: 10; font-weight: bold; width: 150; border: 1px solid #000000;" maxlength="300"> To: <input type="text" name="fix_cart4" value="<? echo $setting->fix_cart4; ?>" style="font-size: 10; font-weight: bold; width: 150; border: 1px solid #000000;" maxlength="300"> Price: <input type="text" name="fix_price2" value="<? echo $setting->fix_price2; ?>" style="font-size: 10; font-weight: bold; width: 150; border: 1px solid #000000;" maxlength="300"><br>
  						From: <input type="text" name="fix_cart5" value="<? echo $setting->fix_cart5; ?>" style="font-size: 10; font-weight: bold; width: 150; border: 1px solid #000000;" maxlength="300"> To: <input type="text" name="fix_cart6" value="<? echo $setting->fix_cart6; ?>" style="font-size: 10; font-weight: bold; width: 150; border: 1px solid #000000;" maxlength="300"> Price: <input type="text" name="fix_price3" value="<? echo $setting->fix_price3; ?>" style="font-size: 10; font-weight: bold; width: 150; border: 1px solid #000000;" maxlength="300"><br>
  						From: <input type="text" name="fix_cart7" value="<? echo $setting->fix_cart7; ?>" style="font-size: 10; font-weight: bold; width: 150; border: 1px solid #000000;" maxlength="300"> To: <input type="text" name="fix_cart8" value="<? echo $setting->fix_cart8; ?>" style="font-size: 10; font-weight: bold; width: 150; border: 1px solid #000000;" maxlength="300"> Price: <input type="text" name="fix_price4" value="<? echo $setting->fix_price4; ?>" style="font-size: 10; font-weight: bold; width: 150; border: 1px solid #000000;" maxlength="300"><br>
							<br>
							<input name="print_ship" type="checkbox" value="1" <? if($setting->print_ship == 1){ echo "checked"; } ?>> If one print item is a store pickup item make the entire order a pickup order. (add no shipping)<br/>
							<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
							</font>
							</div>
							<div class="tabbertab" title="Payment Gateways">
							<font color="#ffffff" style="font-size: 11;">
							<b>Use these settings to use various payment gateways for your store.<br><br></b>
							<hr width="90%"
							<b>Check this to allow payments by check/money order. If you use this, make sure to edit the content called "Store Address" under the content tab. This store address content is display so the user knows where to send the check or money order. All orders placed by this method must be approved manually in order for the customer to receive download links.<br><br></b>
							<input name="use_money" type="checkbox" value="1" <? if($setting->use_money == 1){ echo "checked"; } ?>><b>Allow mail in payments:</b><br> (Example: Check or Money Orders | If you allow this make sure to enter your shipping address in the content tab on top menu, for the content "Store Address".)<br/><br/>
					    <hr width="90%">
					    <b>Use these settings to setup paypal to be used on your site as a payment gateway.<br><br></b>
							<input type="checkbox" name="use_paypal" value="1" <? if($setting->use_paypal == 1){ echo "checked"; } ?>> <b>Allow Paypal Payments:</b> | Need a PayPal account? <a href="https://www.paypal.com/us/mrb/pal=S9MESNJFRGFTC" target="_new">Sign up here</a>.<br/>
							<b>Your Paypal Email Address:</b><br><input type="text" name="paypal_email" value="<? echo $setting->paypal_email; ?>" style="font-size: 10; font-weight: bold; width: 220; border: 1px solid #000000;" maxlength="300"><br/><br/>
              <hr width="90%">
              <b>Use these settings to setup authorize.net to be used on your site as a payment gateway.<br><br></b>
              <input type="checkbox" name="use_authorize_net" value="1" <? if($setting->use_authorize_net == 1){ echo "checked"; } ?> />
              <b>Allow Authorize.net Payments:</b><br/>
              <b>Your <span id="lblLoginId"> API Login ID</span>:</b><br/>
              <input type="text" name="api_login_id" value="<? echo $setting->api_login_id; ?>" style="font-size: 10; font-weight: bold; width: 220; border: 1px solid #000000;" maxlength="300" /><br/>
              <b>Your <span id="lblLoginId"><span id="lblTrandsKey"> Transaction Key</span>:</b><br/>
              <input type="text" name="transaction_key" value="<? echo $setting->transaction_key; ?>" style="font-size: 10; font-weight: bold; width: 220; border: 1px solid #000000;" maxlength="300" /><br/>
							<hr width="90%">
              <b>Use these settings to setup 2checkout.com to be used on your site as a payment gateway.<br><br></b>						
							<input type="checkbox" name="use_2checkout" value="1" <? if($setting->use_2checkout == 1){ echo "checked"; } ?>> <b>Allow 2Checkout Payments:</b> | Need a 2Checkout.com account? <a href="https://www.2checkout.com/2co/signup?affiliate=130242" target="_blank">Sign up here.</a><br/>
							<b>Your 2Checkout.com Account ID:</b><br><input type="text" name="twocheck_account" value="<? echo $setting->twocheck_account; ?>" style="font-size: 10; font-weight: bold; width: 220; border: 1px solid #000000;" maxlength="300"><br><br>In 2Checkout.com set <b>Direct Return to YES</b> and set <b>Approved URL to http://www.YOUR_DOMAIN_NAME.com/2co_ipn.php</b></i><br/>
							<hr width="90%">
              <b>Use these settings to setup Plug n' Pay to be used on your site as a payment gateway.<br><br></b>
							<input type="checkbox" name="pnpstatus" value="1" <? if($setting->pnpstatus == 1){ echo "checked"; } ?>> <b>Allow Plug n' Pay Payments:</b><br />You need a Plug n' Pay account to use this feature. <a href="http://www.plugnpay.com/" target="_new">Find more information here</a>.<br/>
							<?php 
								if(!function_exists('curl_init')){
									echo "<strong>***cURL must be compiled into PHP to use this feature. To have cURL compiled please contact your host.</strong><br /><br />";
								}
							?>
							<b>Your Plug n' Play Account ID:</b><br/><input type="text" name="pnpid" value="<? echo $setting->pnpid; ?>" style="font-size: 10; font-weight: bold; width: 220; border: 1px solid #000000;" maxlength="300"><br/>
							<hr width="90%">
              <b>Use these settings to setup MyGate.co.za to be used on your site as a payment gateway.<br><br></b>
              <input type="checkbox" name="mygatesupport" value="1" <? if($setting->mygatesupport == 1){ echo "checked"; } ?> />
              <b>Allow MyGate.co.za Payments (South African Rands Only):</b><br/>
              <b>Your MyGate.co.za Merchant ID:</b><br/>
              <input type="text" name="mygateid" value="<? echo $setting->mygateid; ?>" style="font-size: 10; font-weight: bold; width: 220; border: 1px solid #000000;" maxlength="300" /><br/>
              <b>Your MyGate.co.za Application ID:</b><br/>
              <input type="text" name="mygateaid" value="<? echo $setting->mygateaid; ?>" style="font-size: 10; font-weight: bold; width: 220; border: 1px solid #000000;" maxlength="300" /><br/>
              <p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
              </font>
              </div>
              <div class="tabbertab" title="Tax">
              <font color="#ffffff" style="font-size: 11;">
              <b>Enter all tax values as a percent:</b> (Example: 6.725)<br>When setting a tax, it will apply to all orders regardless of where the customer lives! If you need specify region taxation, you may want to use paypal's tax module built into your paypal account.<br><br></b>
							<input name="tax_total" type="checkbox" value="1" <? if($setting->tax_total == 1){ echo "checked"; } ?>> <b>Tax Total Amount Plus Shipping & Handling</b><br>(If uncheck it will just tax total sales amount)<br>
							<input name="tax_download" type="checkbox" value="1" <? if($setting->tax_download == 1){ echo "checked"; } ?>> <b>Tax Downloads</b><br>(If uncheck it will not tax downloadable items)<br><br>
							<b>Tax (Main):</b> | (Optional)<br>
							<input type="text" name="tax1_name" value="<? echo $setting->tax1_name; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"> <b>Name</b> {Optional | will be displayed on the cart during checkout}<br>
							<input type="text" name="tax1" value="<? echo $setting->tax1; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">%<br>
							<b>Tax (Secondary):</b> | (Optional)<br>
							<input type="text" name="tax2_name" value="<? echo $setting->tax2_name; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"> <b>Name</b> {Optional | will be displayed on the cart during checkout}<br>
							<input type="text" name="tax2" value="<? echo $setting->tax2; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300">%<br/>
              <p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
              </font>
              </div>
					    </div>
					</td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td align="left"><img src="images/mgr_section_header_l.gif"></td>
									<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>SUBSCRIPTIONS</b></td>
									<td align="right"><img src="images/mgr_section_header_r.gif"></td>
								</tr>
							</table>
						</td>
					</tr>
					
					<tr>
						<td valign="middle" bgcolor="#5E85CA" class="data_box">
						<div class="tabber">
						<div class="tabbertab" title="Yearly">
			      <font color="#ffffff" style="font-size: 11;">
			      <b>These settings control the yearly account feature.<br/><br/></b>
						<input name="allow_subs" type="checkbox" value="1" <? if($setting->allow_subs == 1){ echo "checked"; } ?>><b>Yearly Subscriptions:</b> (Allow visitors to sign up for YEARLY subscriptions.)<br/>
						<b>Yearly Subscription Price:</b> (Do not include $)<br><input type="text" name="sub_price" value="<? echo $setting->sub_price; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
						<b>Yearly Subscription Download Limit:</b> <br><input type="text" name="down_limit_y" value="<? echo $setting->down_limit_y; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
					  <p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
					  </font>
					  </div>
					  <div class="tabbertab" title="Monthly">
					  <font color="#ffffff" style="font-size: 11;">
					  <b>These settings control the monthly account feature.<br/><br/></b>
					  <input name="allow_subs_month" type="checkbox" value="1" <? if($setting->allow_subs_month == 1){ echo "checked"; } ?>><b>Monthly Subscriptions:</b> (Allow visitors to sign up for MONTHLY subscriptions.)<br/>
						<b>Monthly Subscription Price:</b> (Do not include $)<br><input type="text" name="sub_price_month" value="<? echo $setting->sub_price_month; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
						<b>Monthly Subscription Download Limit:</b> <br><input type="text" name="down_limit_m" value="<? echo $setting->down_limit_m; ?>" style="font-size: 10; font-weight: bold; width: 100; border: 1px solid #000000;" maxlength="300"><br/>
						<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
						</font>
						</div>
						<div class="tabbertab" title="Free">
						<font color="#ffffff" style="font-size: 11;">
						<b>These settings control the Free account feature.<br/><br/></b>
						<input name="allow_sub_free" type="checkbox" value="1" <? if($setting->allow_sub_free == 1){ echo "checked"; } ?>><b>Free Accounts:</b> (Allow visitors to sign up for accounts to use special functions in the store. They will not be able to download images unless they upgrade to a paid account.)<br/>
						<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
						</font>
						</div>
						<div class="tabbertab" title="Mandatory">
						<font color="#ffffff" style="font-size: 11;">
						<b>These settings control the need for a mandatory account to checkout.<br/><br/></b>
						<input name="force_members" type="checkbox" value="1" <? if($setting->force_members == 1){ echo "checked"; } ?>><b>Mandatory Accounts:</b> (The Free Accounts option MUST BE ON for this to work! This makes it mandatory that they must create an account to checkout their carts.)<br/>
						<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
						</font>
						</div>
						<div class="tabbertab" title="Downloading">
						<font color="#ffffff" style="font-size: 11;">
						<b>These settings control the account downloading feature.<br/><br/></b>
						<input name="allow_contact_download" type="checkbox" value="1" <? if($setting->allow_contact_download == 1){ echo "checked"; } ?>><b>Allow Downloads of photos set to "contact us for pricing":</b> (Check this to allow a subscriber that has downloads to download any photos that you may have set the pricing to "contact us".)<br/>
						<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
						</font>
						</div>
					  <div class="tabbertab" title="Payments">
					  <font color="#ffffff" style="font-size: 11;">
					  <b>These settings control types of payments that you accept for subscriptions. Some of them are not automatic processes and will require you to verify you got the paid and manually activate the subscription account.<br/><br/></b>
						<input name="sub_paypal" type="checkbox" value="1" <? if($setting->sub_paypal == 1){ echo "checked"; } ?>><b>Allow Paypal:</b> (Automatic)<br/>
						<input name="sub_2co" type="checkbox" value="1" <? if($setting->sub_2co == 1){ echo "checked"; } ?>><b>Allow 2CheckOut:</b> (Automatic)<br/>
						<!----<input name="sub_auth" type="checkbox" value="1" <? if($setting->sub_auth == 1){ echo "checked"; } ?>> <b>Allow Authorize:</b> (???) Coming soon for subscriptions<br/> --->
						<input name="sub_pnp" type="checkbox" value="1" <? if($setting->sub_pnp == 1){ echo "checked"; } ?>><b>Allow Plug n' Pay:</b> (Automatic)<br/>
						<input name="sub_mygate" type="checkbox" value="1" <? if($setting->sub_mygate == 1){ echo "checked"; } ?>><b>Allow MyGate:</b> (Automatic)<br/>
						<input name="sub_cmo" type="checkbox" value="1" <? if($setting->sub_cmo == 1){ echo "checked"; } ?>><b>Allow Check/Money Order:</b> (Manual)<br/>
						<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
					  </font>
					  </div>
					  </div>
					<tr>
						<td height="5"></td>
					</tr>
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td align="left"><img src="images/mgr_section_header_l.gif"></td>
									<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>WEBSITE STYLE</b></td>
									<td align="right"><img src="images/mgr_section_header_r.gif"></td>
								</tr>
							</table>
						</td>
					</tr>
					
					<tr>
						<td valign="middle" bgcolor="#5E85CA" class="data_box">
						<div class="tabber">
						<div class="tabbertab" title="Website Style">
						<font color="#ffffff" style="font-size: 11;">
						<b>Website Style:</b><br>
						<select name="style" style="font-size: 10; font-weight: bold; width: 450; border: 1px solid #000000;">
						<?
							// ADD STYLES
							$styles_dir = "../styles";
							$s_real_dir = realpath($styles_dir);
							$s_dir = opendir($s_real_dir);
							$i = 0;
							// LOOP THROUGH THE PLUGINS DIRECTORY
							while(false !== ($file = readdir($s_dir))){
							$sfile[] = $file;
							}
							//SORT THE CSS FILES IN THE ARRAY
							sort($sfile);
							//GO THROUGH THE ARRAY AND GET FILENAMES
							foreach($sfile as $key => $value){
							//IF FILENAME IS . OR .. OR SLIDESHOW.CSS DO NO SHOW IN THE LIST
							$fname = strip_ext($sfile[$key]);
							if($fname != ".." && $fname != "." && $fname != "slideshow"){
									if($setting->style == $fname . ".css"){
										echo "<option selected>" . $fname . "</option>";
									} else {
										echo "<option>" . $fname . "</option>";
									}
								}
							}
								
						?>
						</select>
						<p align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_mgr();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></p>
					  </font>
					  </div>
					  </div>
					</tr>
					<tr>
						<td height="5"></td>
					</tr>
				</table>
			</td>
		</tr>
		</form>
		<form name="mgr_login" method="post">
		<input type="hidden" name="return" value="mgr.php?nav=<? echo $nav; ?>">
		<tr>
			<td bgcolor="#3C6ABB" align="center" style="padding: 4px; border-bottom: 1px solid #355894;border-top: 1px solid #5B8BD8;">
				<table cellpadding="0" cellspacing="0" width="95%">
					<tr>	
						<td width="100%" nowrap><font face="arial" color="#ffffff" style="font-size: 11;"><b>MANAGER LOGIN SETTINGS</b></font>
						<? if($_GET['message'] == "mgr_login_saved"){ ?>
						<td align="right" valign="bottom">
							<img src="images/mgr_check2_loop_3.gif" valign="absmiddle">
						</td>
						<td align="right" valign="middle" nowrap>
							<font color="#FFE400" style="font-size: 10;">&nbsp;The login information has been updated
						</td>
						<? } ?>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td bgcolor="#577EC4" align="center" style="padding-top: 10px; padding-bottom: 10px;" background="images/mgr_bg_texture.gif">
				<table width="95%" cellpadding="0" cellspacing="0">
					<tr>
						<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;"><b>Username:</b><br><input type="text" name="username" value="<? if($access_type ==  "demo"){ echo "********"; } else { echo $mgr_users->username; } ?>" style="font-size: 10; font-weight: bold; width: 230; border: 1px solid #000000;" maxlength="24"> <font style="font-size: 10;color: #A9C4F6;">(cannot contain spaces)</td>
					</tr>
					<tr>
						<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;"><b>Password:</b><br><input type="text" name="password" value="<? if($access_type ==  "demo"){ echo "********"; } else { echo $mgr_users->password; } ?>" style="font-size: 10; font-weight: bold; width: 230; border: 1px solid #000000;" maxlength="24"> <font style="font-size: 10;color: #A9C4F6;">(cannot contain spaces)</td>
					</tr>
					<tr>
						<td height="5"></td>
					</tr>
					<tr>
						<td align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_login();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></td>
					</tr>
				</table>
			</td>
		</tr>
		</form>
		
		<? if($access_type == "admin"){ ?>
		
		<script language="javascript">

			function save_mgr_settings_admin() {
				var agree=confirm("Are you sure you want to save these settings?");
				if (agree) {
					document.mgr_settings_admin.action = "mgr_actions.php?pmode=save_mgr_settings_admin";
					document.mgr_settings_admin.submit();
				}
				else {
					false
				}
			}				
		</script>
		
		<form name="mgr_settings_admin" method="post">
		<input type="hidden" name="return" value="mgr.php?nav=<? echo $nav; ?>">
		<tr>
			<td bgcolor="#3C6ABB" align="center" style="padding: 4px; border-bottom: 1px solid #355894;border-top: 1px solid #5B8BD8;">
				<table cellpadding="0" cellspacing="0" width="95%">
					<tr>	
						<td width="100%" nowrap><font face="arial" color="#ffffff" style="font-size: 11;"><b>MANAGER SETTINGS</b> (admin only)</font></td>
						<? if($_GET['message'] == "admin_settings_saved"){ ?>
						<td align="right" valign="bottom">
							<img src="images/mgr_check2_loop_3.gif" valign="absmiddle">
						</td>
						<td align="right" valign="middle" nowrap>
							<font color="#FFE400" style="font-size: 10;">&nbsp;Your settings have been saved
						</td>
						<? } ?>
					</tr>
				</table>						
			</td>
		</tr>
		<tr>
			<td bgcolor="#577EC4" style="border-bottom: 1px solid #476DB0;" align="center" style="padding-top: 10px; padding-bottom: 10px;" background="images/mgr_bg_texture.gif">
				<table width="95%" cellpadding="0" cellspacing="0">
					<tr>
						<td valign="middle" bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;"><input name="demo_mode" type="checkbox" value="1" <? if($setting->demo_mode == 1){ echo "checked"; } ?>> Allow Demo Access</td>
					</tr>
					<tr>
						<td valign="middle" bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;"><input name="access_id" type="text" value="<?php echo $setting->access_id; ?>"></td>
					</tr>
					<tr>
						<td valign="middle" bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;"><input name="editor" type="checkbox" value="1" <? if($setting->editor == 1){ echo "checked"; } ?>> Enable Editor On TextArea Fields (IE 5.5+ Only)</td>
					</tr>
					<tr>
						<td valign="middle" bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;"><input name="deactivate" type="checkbox" value="1" <? if($setting->status != MD5(1)){ echo "checked"; } ?>> Deactivate Website</td>
					</tr>
					<tr>
						<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;"><b>Error Message:</b><br><textarea name="error_message" value="" style="width: 380; height: 50; border: 1px solid #000000;"><? echo $setting->error_message; ?></textarea> <font style="font-size: 10;color: #A9C4F6;"></td>
					</tr>
					<tr>
						<td valign="middle" bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;"><input name="author_branding" type="checkbox" value="1" <? if($setting->author_branding == 1){ echo "checked"; } ?>> Author Branding</td>
					</tr>
					<tr>
						<td valign="middle" bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;">
						<b>Link to help tips:</b> (include trailing slash "/")<br>
						<input type="text" name="help_tips_link" value="<? echo $setting->help_tips_link; ?>" style="font-size: 10; width: 380; border: 1px solid #000000;" maxlength="240">
						</td>
					</tr>
					<tr>
						<td valign="middle" bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;">
						<b>Link to text feild editor:</b> (include trailing slash "/")<br>
						<input type="text" name="editor_link" value="<? echo $setting->editor_link; ?>" style="font-size: 10; width: 380; border: 1px solid #000000;" maxlength="240">
						</td>
					</tr>
					<tr>
						<td valign="middle" bgcolor="#5E85CA" class="data_box">
							<font color="#ffffff" style="font-size: 11px;"><a href="server_info.php" target="_blank"><font color="#ffffff">Server Info</a>  |  
							<font color="#ffffff" style="font-size: 11px;"><a href="server_modules.php" target="_blank"><font color="#ffffff">Server Modules</a>  |  
							Server IP: <?php echo $_SERVER['SERVER_ADDR']; ?>
						</td>
					</tr>
					<tr>
						<td height="5"></td>
					</tr>
					<tr>
						<td align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_mgr_settings_admin();\">"; } ?><img src="images/mgr_button_update.gif" border="0"></a></td>
					</tr>
				</table>
			</td>
		</tr>
		</form>
		<? } ?>
		<!--
		<tr>
			<td bgcolor="#5E85CA" style="border-top: 1px solid #6F97DE;border-bottom: 1px solid #476DB0;"><br><br><br><br><br></td>
		</tr>
		<tr>
			<td bgcolor="#577EC4" style="border-bottom: 1px solid #476DB0;border-top: 1px solid #6F97DE;"><br><br><br><br><br></td>
		</tr>
		-->
	</table>
<?
	}
?>
