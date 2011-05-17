<?php
	$plugin_name    = "FTP Plugin";
	$plugin_version = "1.3 [6.29.05]";
	
	if($execute_nav == 1){
		$nav_order = 19; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "Import Photos"; // name of the nav that will appear on the page
	}
	else {
		
		// OPTIONS
			$image_upload     = 0; // number of images that can be uploaded per news item / 0 for no files
			$image_path       = "../uploaded_images/";
			$image_caption    = 0; // 1 on | 0 off / Allow captions on images
			$image_area_name  = "";
			
			$file_upload      = 0; // number of files that can be uploaded per news item / 0 for no files
			$file_path        = "../uploaded_files/";
			$file_area_name   = "";
			$file_active      = 0; // allow files to be set active/inactive
			
			$copy_link_option = 0;  // 1 on | 0 off / Allow image links to be copied
			
			$homepage_option  = 0; // 1 on | 0 off				
			$editor           = 0; // 1 on | 0 off
			$reference        = "copy_area"; // used when saving and pulling images or files from/to the database
			$actions_page     = "actions_copy.php"; // Actions page for processing forms
		
		// GET GENERAL SETTINGS FROM THE DATABASE
			$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
			$setting = mysql_fetch_object($settings_result);
			
			$upload_result = mysql_query("SELECT * FROM status where id = '1'", $db);
			$upload = mysql_fetch_object($upload_result);
	
			$status_reset = "";
			
			$sql = "UPDATE status SET status='$status_reset' WHERE id = '1'";
			$result = mysql_query($sql);
			
			unset($_SESSION['start']);
			
?>
<script language="javascript">
	function save_data() {
		if(document.data_form.title.value == ""){
			alert("Please enter a title");
		}
		else {
			var agree=confirm("Save your changes?");
			if (agree) {
				//window.open('textarea_preview.php', 'preview_window', ['HEIGHT=500', 'WIDTH=600', 'scrollbars=yes', 'dependent']);
				document.data_form.submit();
			}
			else {
				false
			}
		}
	}
</script>
	<table width="750" cellpadding="3" cellspacing="3">
		<tr>
			<td width="100%" valign="top">
				<table cellpadding="0" cellspacing="0" bgcolor="#577EC4" width="100%" style="border: 1px solid #5B8BD8;" background="images/mgr_bg_texture.gif">
					<tr>
						<td height="4"></td>
					</tr>
					<tr>
						<td>
							<table width="100%">
								<tr>
									<td style="padding-left: 8px;"><font style="font-size: 13px;"><b>IMPORT PHOTOS</b></td>
									<?
										if($_GET['message'] == "deleted"){
									?>
									<td valign="middle">
										<table>
											<tr>
												<td>&nbsp;</td>
												<td valign="middle"><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"></td>
												<td valign="middle"><font color="#FFE400" style="font-size: 10;">The selected item(s) have been deleted.</td>
											</tr>
										</table>
									</td>
									<?
										}
									
										if($_GET['message'] == "added"){
									?>
									<td valign="middle">
										<table>
											<tr>
												<td>&nbsp;</td>
												<td valign="middle"><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"></td>
												<td valign="middle"><font color="#FFE400" style="font-size: 10;">Your new listing has been added.</td>
											</tr>
										</table>
									</td>
									<?
										}
									?>
								</tr>
								<tr>
									<td style="padding: 10px;">
									
									<?php
										if(ini_get("max_execution_time") < 60){
									?>
										<div style="background-color:#FFFFFF; color: #333333; padding: 4px; border: 1px solid #0a2963"><b><font color="#FF0000">* NOTICE:</font></b> Your host has a max_execution_time of <?php echo ini_get("max_execution_time"); ?> seconds set. If a single photo takes longer than <?php echo ini_get("max_execution_time"); ?> seconds to process the ftp import will fail and you will get a server error. Contact your host to change this value.</div><br /><br />
									<?php
										}
										if(ini_get("memory_limit") and ini_get("memory_limit") < 128){
									?>
										<div style="background-color:#FFFFFF; color: #333333; padding: 4px; border: 1px solid #0a2963"><b><font color="#FF0000">* NOTICE:</font></b> Your host has a memory_limit of <?php echo ini_get("memory_limit"); ?>. This will restrict your from uploading larger photos. Contact your host to change this value.</div><br /><br />
									<?php
										}
									?>
									</td>
								</tr>
								
								<? if ($_GET['mes'] == "uploaded"){ ?>
								<tr>
									<td class="data_box" bgcolor="#5E85CA">The photos from the FTP folder have been added to your site. <b><?php if($_GET['added']){ echo $_GET['added']; } else { echo "0"; } ?></b> photos have been added.</td>
								</tr>
								<? } else { ?>
								<tr>
									<td class="data_box" bgcolor="#5E85CA">Import photos allows you to add your photos to your site from ftp or the upload photos tab in batches, instead of one photo at a time.<br>
									<br>
									<?php
										$ftp_path = "../ftp/";
										if ($handle = opendir($ftp_path)) {
											while (false !== ($file = readdir($handle))) {
												if(is_file("../ftp/" . $file)){
													$imgtype = getimagesize("../ftp/" . $file);
													if($imgtype[2] == 2){
														$img_files[]  = $file;
														//echo $file . "<br>";
														$mfs[] = figure_memory_needed("../ftp/" . $file);
													} else {
														//echo $file  . " is not a supported image file<br>";
													}
												}
											}
											closedir($handle);
										}
										$x = count($img_files);
										echo "<b>You have $x files waiting to be uploaded.</b>";
									/*
										$s_max_execution_time = ini_get("max_execution_time");
										
										if($s_max_execution_time <= 30){
											$max_num_of_files = 5;
										}
										if($s_max_execution_time > 30 and $s_max_execution_time < 199){
											$max_num_of_files = 25;
										}
										if($s_max_execution_time >= 200){
											$max_num_of_files = 50;
										}
									*/										
										if($mfs){
											$maxfilesize = round(max($mfs),2);
											if(ini_get("memory_limit")){
												$memory_limit = ini_get("memory_limit");
											} else {
												$memory_limit = 256;
											}											
											if($maxfilesize > $memory_limit){
												echo "<div style=\"background-color:#FFFFFF; color: #333333; padding: 4px; border: 1px solid #0a2963\"><b><font color=\"#FF0000\">* NOTICE:</font></b> Some of the photos you are about to add may be larger than your server/hosting can process. A quick test of your server/hosting shows that you may have a PHP memory limit in place that will restrict the size of photos that can be added.<br /><br />The following photos will be skipped during this process:";
												foreach($mfs as $key => $value){
													if($value > $memory_limit){
														echo "<br />" . $img_files[$key];
													}
												}
												echo "</div>";
											}
										}
									
									//echo $maxfilesize . "|" . calc_image_maxsize();
									//echo "<br />";
									//foreach($mfs as $value){
										//echo $value . "<br />";
									//}
									?><br /><br />
									<b>Step 1:</b><br>Upload your photos to the <b>ftp</b> folder on your server. This is the ftp folder in the root of your PhotoStore site.<br><br>
									<b>Step 2:</b><br>Choose the category that you would like to put the photos in. Set to "none" if the photos go into different categories or if you would like to sort them later. <i>Optional: </i> Enter keywords, title, price, etc. for the photos.<br><br>
									
									<b>Step 3:</b><br>Click the "add photos" button below. Your photos in the ftp folder will now be added to your site. This may take up to 15 seconds per photo depending on the speed of your server.<br><br>
									<br><br>
									<form action="actions_ftp_upload.php?pmode=ftp_upload&nav=<? echo $_GET['nav']; ?>" method="post">
									<table>
										<tr>
											<td bgcolor="#5E85CA" class="data_box">
												<font face="arial" color="#ffffff" style="font-size: 11;"><b>Photo Title:</b> (All photos will take the same name. Leaving this field blank will result in no title being entered.)<br>(You CAN NOT use these characters in the title, keywords, or descriptions: /"\|][;:)(*^%$#@<>)<br>
												<input type="text" name="title" style="font-size: 13; font-weight: bold; width: 350; border: 1px solid #000000;"  maxlength="150">
											</td>
										</tr>
										<tr>
											<td bgcolor="#5E85CA" valign="top" class="data_box">
												<b>Main Category:</b> (All photos will be put in this category.)<br>
												<select name="gallery_id" style="font-size: 11; width: 350; border: 1px solid #000000;">
													<option value="0">None</option>
													<?
														function db_tree2($x,$level,$nest_under,$item_id){
															include("../database.php");
															
															$my_row = 1;
															
															$ca_result = mysql_query("SELECT * FROM photo_galleries where nest_under = '$x' order by title", $db);
															$ca_rows = mysql_num_rows($ca_result);
															while($ca = mysql_fetch_object($ca_result)){
																
																echo "<option value=\"";
																
																if($ca->id == $item_id){
																	echo $nest_under . "\"";
																} else {
																	echo $ca->id . "\"";
																}
																
																if($nest_under == $ca->id){
																	echo " selected";
																}
																
																echo ">";
																		
																for($z = 1; $z < $level; $z++){
																	echo "&nbsp;&nbsp;&nbsp;&nbsp;";
																}
																
																echo $ca->title . "</option>";
																
																db_tree2($ca->id,$level + 1,$nest_under,$item_id);
																
															$my_row ++;
															}
														}
														db_tree2(0,1,$ca->nest_under,$item_id);
													?>
												</select>													
												</td>
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
												<font face="arial" color="#ffffff" style="font-size: 11;"><b>Keywords:</b><br>(Optional | Separate with a comma ",". These keywords will be assigned to all photos that are currently being uploaded.)<br>
												<textarea name="keywords" style="height: 50; width: 350; border: 1px solid #000000;"></textarea>
												</td>
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
												<font face="arial" color="#ffffff" style="font-size: 11;"><b>Description:</b> (Optional | Example: Detail about these photos.)<br>
												<textarea name="description" style="height: 50; width: 350; border: 1px solid #000000;"><? echo $pg->description; ?></textarea>
												</td>
											</tr>
											<tr>
												<td bgcolor="#5E85CA" valign="top" class="data_box">
													<b>Other Categories:</b><br> (Optional | To have these photos show in other categories hold Ctrl to select more than one.)<br>
													<select multiple="multiple" size="8" name="other_galleries[]" style="font-size: 11; width: 350; border: 1px solid #000000;">
														<option value="0">None</option>
														<?
															$og = explode(",", $pg->other_galleries);
															function db_tree3($x,$level,$nest_under,$item_id){
																include("../database.php");
																global $og;
																
																$my_row = 1;
																
																$ca_result = mysql_query("SELECT * FROM photo_galleries where nest_under = '$x' order by title", $db);
																$ca_rows = mysql_num_rows($ca_result);
																while($ca = mysql_fetch_object($ca_result)){
																	
																	echo "<option value=\"";
																	
																	if($ca->id == $item_id){
																		echo $nest_under . "\"";
																	} else {
																		echo $ca->id . "\"";
																	}
																	
																	if(in_array($ca->id, $og)){
																		echo " selected";
																	}
																	
																	echo ">";
																			
																	for($z = 1; $z < $level; $z++){
																		echo "&nbsp;&nbsp;&nbsp;&nbsp;";
																	}
																	
																	echo $ca->title . "</option>";
																	
																	db_tree3($ca->id,$level + 1,$nest_under,$item_id);
																	
																$my_row ++;
																}
															}
															db_tree3(0,1,$ca->nest_under,$item_id);
														?>
													</select>													
												</td>
											</tr>
											<tr>
											<td bgcolor="#5E85CA" class="data_box">
												<font face="arial" color="#ffffff" style="font-size: 11;"><b>Quality Description:</b> (Optional | All photos will take the same quality description.)<br>
												<input type="text" name="quality1" style="font-size: 13; font-weight: bold; width: 350; border: 1px solid #000000;"  maxlength="150">
											</td>
											</tr>
											<tr>
											<td bgcolor="#5E85CA" class="data_box">
												<font face="arial" color="#ffffff" style="font-size: 11;"><b>Order:</b> (Optional | Sort order in which this will be listed in comparison to the other sizes. Smaller the number is to the top. Numerical value only! If you don't use the other size option then you can leave this blank.)<br>
												<input type="text" name="quality_order1" style="font-size: 13; font-weight: bold; width: 350; border: 1px solid #000000;"  maxlength="150">
											</td>
											</tr>
												<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<input type="checkbox" name="featured" value="1" <? echo "checked"; ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Featured:</b> (Display this photo on the public website featured photo section located on the homepage.)<br>
												</td>
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<input type="checkbox" name="active" value="1" <? echo "checked"; ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Active:</b> (Display this photo on the public website.)<br>
												</td>
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
												<font face="arial" color="#ffffff" style="font-size: 11;">
												<input type="checkbox" name="act_download" value="1" <? echo "checked"; ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Sell Digital Version:</b> (Allow the digital version of these photos to be purchased & downloaded.)<br>
												</td>
											</tr>
											<!--
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>Price:</b> (Optional / Do not include $ / This price will be assigned to all photos that are currently being uploaded)</b><br>
													<input type="text" name="price" style="font-size: 13; font-weight: bold; width: 100px; border: 1px solid #000000;"  maxlength="150">
												</td>
											</tr>
											-->
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>Price:</b> (Optional | Do not include $ | This price will be assigned to all photos that are currently being uploaded.)
													<table style="border: 1px solid #1A4188; margin: 10px; background-color: #7296D7;">
														<tr>
															<td style="padding: 10px;">
																<b>Price:</b><br /> (If left blank it will take the default price set under settings. Enter 0 to allow the photo to be downloaded for free.)<br>
																<input type="text" name="price" style="font-size: 11; width: 100px; border: 1px solid #000000;" maxlength="200">
															</td>
															<td align="center" valign="middle" style="padding: 10px; background-color: #2F59A3; border: 1px solid #1A4188;"><font style="font-size: 18px; color:#FFFFFF;"><b>OR</b></td>
															<td align="center" style="padding: 10px;" nowrap><b>Contact for Pricing:</b><br />(Overrides any pricing infomation.)<br /><input type="checkbox" name="price_contact" value="1"></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td bgcolor="#5E85CA" valign="top" class="data_box">
													<b>Photographer:</b> (Optional | Leave blank if you are not uploading for a photographer.)<br>
													<select name="photographer" style="font-size: 11; width: 350; border: 1px solid #000000;">
														<option value="0">Leave Blank</option>
														<option value="999999999" <? if($pg->photographer == "999999999"){ echo "selected"; }?>><?php echo $setting->site_title; ?></option>
														<?
															$pgm_result = mysql_query("SELECT * FROM photographers order by name", $db);
															$pgm_rows = mysql_num_rows($pgm_result);
															while($pgm = mysql_fetch_object($pgm_result)){
																
														?>
															<option value="<? echo $pgm->id; ?>" <? if($pg->photographer == $pgm->id){ echo "selected"; }?>><? echo $pgm->name; ?></option>
														<?
															}
														?>
													</select>													
												</td>
											</tr>
											
											<?php
												if($setting->allow_prints){
											?>
											<tr>
												<td colspan="2" height="15"></td>
											</tr>
											<tr>
												<td colspan="2">
													<table cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td align="left"><img src="images/mgr_section_header_l.gif"></td>
															<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>PRINTS & PRODUCTS DETAILS</b></td>
															<td align="right"><img src="images/mgr_section_header_r.gif"></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<table cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td bgcolor="#5E85CA" class="data_box">
																
																	<input type="checkbox" name="all_prints" value="1" checked> <b>Allow ALL Prints & Products to be purchased with this photo:</b><br />(This will assign all prints & products to these images including all future prints & products you may create.)<br />
																	<br />
																	<div align="center" valign="middle" style="padding: 10px; background-color: #2F59A3; border: 1px solid #1A4188;"><font style="font-size: 16px; color:#FFFFFF;"><b>OR</b></font></div>
																	<br />
																	<font face="arial" color="#ffffff" style="font-size: 11;"><b>Allow only selected Prints & Products to be purchased with this photo:</b> (Select any combination below.)<br>
																<?php
																	$myprod = explode(",",$pg->prod);
																
																	$prod_result = mysql_query("SELECT * FROM prints", $db);
																	$prod_rows = mysql_num_rows($prod_result);
																	while($prod = mysql_fetch_object($prod_result)){
																		
																?>
																	<input type="checkbox" name="prod[]" value="<? echo $prod->id; ?>"> <?php echo $prod->name; ?> | <?PHP echo $currency->sign . $prod->price; ?><br />
																<?
																	}
																?>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<?php
												}
											?>
												<?php
												if($setting->allow_digital){
											?>
											<tr>
												<td colspan="2" height="15"></td>
											</tr>
											<tr>
												<td colspan="2">
													<table cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td align="left"><img src="images/mgr_section_header_l.gif"></td>
															<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>ADDITIONAL SIZES FOR DOWNLOAD</b></td>
															<td align="right"><img src="images/mgr_section_header_r.gif"></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<table cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td bgcolor="#5E85CA" class="data_box">
																
																	<input type="checkbox" name="all_sizes" value="1" checked> <b>Allow ALL sizes to be purchased with this photo:</b><br />(This will assign all sizes to these images including all future sizes you may create.)<br />
																	<br />
																	<div align="center" valign="middle" style="padding: 10px; background-color: #2F59A3; border: 1px solid #1A4188;"><font style="font-size: 16px; color:#FFFFFF;"><b>OR</b></font></div>
																	<br />
																	<font face="arial" color="#ffffff" style="font-size: 11;"><b>Allow only selected sizes to be purchased with this photo:</b> (Select any combination below.)<br>
																<?php
																	$mysize = explode(",",$pg->sizes);
																
																	$sizes_result = mysql_query("SELECT * FROM sizes", $db);
																	$sizes_rows = mysql_num_rows($sizes_result);
																	while($sizes = mysql_fetch_object($sizes_result)){
																		
																?>
																	<input type="checkbox" name="sizes[]" value="<? echo $sizes->id; ?>"> <?php echo $sizes->name; ?> | <?php echo $sizes->size; ?>(px) | <?PHP echo $currency->sign . $sizes->price; ?><br />
																<?
																	}
																?>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											
											
											
											<?php
												}
											?>
											<!-- CREATE OTHER VERSIONS -->
											<?php
												if(count($p_name) > 0){
											?>
												<tr>
													<td colspan="2">&nbsp;</td>
												</tr>
												<tr>
													<td colspan="2">
														<table cellpadding="0" cellspacing="0" width="100%">
															<tr>
																<td align="left"><img src="images/mgr_section_header_l.gif"></td>
																<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>ALSO CREATE THESE OTHER VERSIONS OF THESE PHOTOS</b></td>
																<td align="right"><img src="images/mgr_section_header_r.gif"></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td bgcolor="#5E85CA" class="data_box" colspan="2">
													This allows you to automatically generate multiple sizes for each photo you upload. <br /><br />* WARNING - This process uses a lot of server resources. If you are on a shared server and uploading large photos this may or may not work.<br /><br />
													These profiles can be changed in config_mgr.php<br />It is recommended that you now use the sizes tab in the store manager to create other sizes for photos. This is the old way of adding additional sizes and it is left here for compatibility with older versions of photostore. If you had an older version of photostore and upgraded to this version you can still use the new sizes tab to create your additional sizes. Always try to use the new sizes tab and create additional sizes like you would prints then assign the sizes above in the same manner as you would assign prints. (Read manual for more details)<br />
														<?php
															foreach($p_name as $key => $value){
														?>
																
																<input type="checkbox" name="aprofile[]" value="<?php echo $key; ?>" /> <b><?php echo $value; ?></b> at <?php echo $p_size[$key]; ?>px for $<input type="text" name="p_price_<?php echo $key; ?>" value="<?php echo $p_default_price[$key]; ?>" style="width:60px;"><br /><br />
														<?php
															}
														?>
													</td>
												</tr>
											<?php
												}
											?>
											
											
											
										</table>
									
									</td>
								</tr>
								<? } ?>
								<tr>
									<td align="right"><input type="image" src="images/mgr_button_add_photos.gif" border="0"></td>
								</tr>
								</form>
							</table>
						</td>
					</tr>
					<tr>
						<td height="4"></td>
					</tr>
					
					</tr>
				</table>
			</td>
		</tr>
	</table>	
<?
	}
?>