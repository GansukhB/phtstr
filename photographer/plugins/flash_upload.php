<?php
	/*
		Manager Settings Plus	4.7.04
			1 manager user login version
			This version includes metatags and other information
	*/
	
	if($execute_nav == 1){
		$nav_order = 18; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "Upload Photos"; // name of the nav that will appear on the page
		//$actions_page = "actions_coupon.php";
	}
	else{
		$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
		$setting = mysql_fetch_object($settings_result);
		
		$currency_result = mysql_query("SELECT * FROM currency where active = '1'", $db);
		$currency = mysql_fetch_object($currency_result);
		
		$mgr_result = mysql_query("SELECT * FROM mgr_users where id = '1'", $db);
		$mgr_users = mysql_fetch_object($mgr_result);
		
		if(ini_get("upload_max_filesize")){
				$upload_limit = ini_get("upload_max_filesize") - .05;
				$upload_limit = $upload_limit * 1024;
			} else {
				$upload_limit = 2000;
			}
?>
	<table width="700" cellpadding="0" cellspacing="0" bgcolor="#577EC4" style="border: 1px solid #5B8BD8;">
		<tr>
			<td bgcolor="#577EC4" align="center" style="padding-top: 10px; padding-bottom: 10px;" background="images/mgr_bg_texture.gif">
				<table width="95%" cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td align="left"><img src="images/mgr_section_header_l.gif"></td>
									<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>Upload</b></td>
									<td align="right"><img src="images/mgr_section_header_r.gif"></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
					<td bgcolor="#5E85CA" class="data_box">
					 <b>Info on uploading and importing</b>
                            <br><br>
                            <b>Uploading:</b><br>
                            Make sure that you upload photos in batches you want to work with (similar photos that you want to have the same titles, keywords, descriptions, categories, etc..).<br>
                            Example: If you have a bunch of dog photos upload those first and process them using the import photo page, and then upload another batch of photos related to cats and process those.
                            <br><br>
                            <b>Import:</b><br>
                            After Uploading the photos click on the "Import Photos" tab in the left menu to import the photos into your store and assign titles, descriptions, keywords, and many more options to the photos.<br>
            </td>
          </tr>	
					<tr>
						<td align="center" bgcolor="#5E85CA" class="data_box">
                            <div id="flashcontent"/>
							<script type="text/javascript" src="flash/FlashObject.js"></script>
                            <script>
                                <!--
                                    var flashObj = new FlashObject ("uploadDownload.swf", "uploadDownload", "550", "600", 8, "#5E85CA", true);			
                                    flashObj.addVariable ("myextensions", "*.jpg;*.gif;*.png;*.php");
                                    flashObj.addVariable ("uploadUrl", "flash/uploadFile.php?pass=<?php echo md5($setting->access_id); ?>");
                                    flashObj.addVariable ("downloadListUrl", "flash/viewFiles.php?pass=<?php echo md5($setting->access_id); ?>");
                                    flashObj.addVariable ("maxFileSize", "<?PHP echo $upload_limit; ?>"); // in kb
                                    flashObj.write ("flashcontent");
                                // -->
                            </script>
                            </div>
						</td>
					</tr>
					<table width="95%">
				</table>
				</td>
			</tr>
	</table>
<?
	}
?>