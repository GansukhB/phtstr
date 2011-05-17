<?php
	$plugin_name    = "Email Plugin";
	$plugin_version = "3.3.0 6-29-2007";
	
	if($execute_nav == 1){
		$nav_order = 5; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "Emails"; // name of the nav that will appear on the page
	}
	else {
		
		// OPTIONS
			$image_upload     = 20; // number of images that can be uploaded per news item / 0 for no files
			$image_path       = "../uploaded_images/";
			$image_caption    = 1; // 1 on | 0 off / Allow captions on images
			$image_area_name  = "";
			
			$file_upload      = 20; // number of files that can be uploaded per news item / 0 for no files
			$file_path        = "../uploaded_files/";
			$file_area_name   = "";
			$file_active      = 1; // allow files to be set active/inactive
			
			$copy_link_option = 1;  // 1 on | 0 off / Allow image links to be copied
			
			$homepage_option  = 1; // 1 on | 0 off				
			$editor           = 1; // 1 on | 0 off
			$reference        = "email"; // used when saving and pulling images or files from/to the database
			$actions_page     = "actions_email.php"; // Actions page for processing forms
		
		// GET GENERAL SETTINGS FROM THE DATABASE
			$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
			$setting = mysql_fetch_object($settings_result);
			
			$item_id = $_GET['item_id'];
			$nav = $_GET['nav'];
			$gid = $_GET['gid'];
			$order_type = $_GET['order_type'];
?>
<script language="javascript">
	var i_status;
	var c_status;

	function delete_data() {
		var agree=confirm("Are you sure you would like to delete the selected items?");
		if (agree) {
			document.listings.action = "<? echo $actions_page; ?>?pmode=delete";
			document.listings.submit();
		}
		else {
			false
		}
	}
	
	function save_data() {
		if(document.data_form.title.value == ""){
			alert("Please enter a title");
		}
		else {
			var agree=confirm("Save your changes?");
			if (agree) {
				<?php											
					$agent = $_SERVER['HTTP_USER_AGENT'];
					if(!eregi("mac", $agent) or $setting->force_mac == 1){
				?>
				//document.forms.data_form.onsubmit();
				
				document.getElementById("article").value=oEdit1.getHTMLBody();
				
				<?php } ?>
				document.forms.data_form.submit();
			}
			else {
				false
			}
		}
	}

	function selectAll(formObj, isInverse) {
		if(c_status != 1){
			for (var i=0;i < formObj.length;i++){
		      fldObj = formObj.elements[i];
		      if (fldObj.type == 'checkbox')
		      {
			      fldObj.checked = true;			      
		      }
	      }
	      c_status = 1;
		}
		else {
			for (var i=0;i < formObj.length;i++){
		      fldObj = formObj.elements[i];
		      if (fldObj.type == 'checkbox')
		      {
			      fldObj.checked = false;
			      
			  }
		    }
		    c_status = 0;
		}  
	}
		
	function instructions(){
		if(i_status != 1){
			document.getElementById('instructions').style.display='block';
			i_status = 1;
		}
		else{
			document.getElementById('instructions').style.display='none';
			i_status = 0;
		}
	}
</script>
	<table width="750" cellpadding="3" cellspacing="3">
		<tr>
			<?
				if(!$item_id){
			?>
			<!-- LIST COLUMN -->
			<form name="listings" method="post">
			<input type="hidden" value="mgr.php?nav=<? echo $nav; ?>&message=deleted&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" name="return">
			<input type="hidden" value="<? echo $file_path; ?>" name="file_path">
			<input type="hidden" value="<? echo $image_path; ?>" name="image_path">
			<input type="hidden" value="<? echo $reference; ?>" name="reference">
			<td width="100%" valign="top">
				<table cellpadding="0" cellspacing="0" bgcolor="#577EC4" width="100%" style="border: 1px solid #5B8BD8;" background="images/mgr_bg_texture.gif">
					<?
						// CONFIGURE ORDER BY
						if($order_type == "desc"){
							$order_type_next = "";
						}
						else{
							$order_type_next = "desc";
						}
						
						if($order_by == ""){
							$order_by = "title";
							$order_type = "";
						}
						
						
							$colspan="6";
						
						$ca_result = mysql_query("SELECT * FROM email_copy order by $order_by " . $order_type, $db);
						$ca_rows = mysql_num_rows($ca_result);
					?>
					<tr>
						<td colspan="<? echo $colspan; ?>" height="4"></td>
					</tr>
					<tr>
						<td colspan="<? echo $colspan; ?>">
							<table width="100%">
								<tr>
									<td style="padding-left: 8px;"><font style="font-size: 13;"><b>Website Email Areas</b></td>
									<?
										if($message == "deleted"){
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
									
										if($message == "added"){
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
										
										//if($ca_rows >= 5 and $access_type == "admin"){
									?>
									<td align="right"></td>
									<?
										//}
										//else if($ca_rows == 0 and  $access_type == "admin"){
									?>
									<!--<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=new&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>"><img src="images/mgr_button_new.gif" border="0"></a></td>-->
									<?
										//}
									?>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="<? echo $colspan; ?>" height="4"></td>
					</tr>
					<tr>
						<?
							// SHOW ID IF ADMIN IS LOGGED IN
							//if($_SESSION['access_type'] == "admin"){
						?>
							<td bgcolor="<? if($order_by == "id"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894;border-top: 1px solid #5B8BD8;" nowrap>	
								<b>&nbsp;&nbsp;<a href="mgr.php?nav=<? echo $nav; ?>&order_by=id&order_type=<? if($order_by == "id"){ echo $order_type_next; }  ?>" class="title_links">ID</a>&nbsp;&nbsp;</b>								
							</td>
						<?
							//}
						?>
						<td colspan="2" bgcolor="<? if($order_by == "title"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" width="100%" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894;<? if($_SESSION['access_type'] == "admin"){ echo "border-left: 1px solid #5B8BD8"; } ?>;border-top: 1px solid #5B8BD8;" nowrap>	
							&nbsp;<b><a href="mgr.php?nav=<? echo $nav; ?>&order_by=title&order_type=<? if($order_by == "title"){ echo $order_type_next; }  ?>" class="title_links">EMAIL AREA TITLE</a></b>
						</td>
						<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<b>&nbsp;&nbsp;EDIT&nbsp;&nbsp;</b>
						</td>
						<?
							// SHOW ID IF ADMIN IS LOGGED IN
							//if($_SESSION['access_type'] == "admin"){
						?>
						<?
							//}
						?>
					</tr>
					<?
						
						//$ca_result = mysql_query("SELECT * FROM email_copy order by $order_by " . $order_type, $db);
						//$ca_rows = mysql_num_rows($ca_result);
						while($ca = mysql_fetch_object($ca_result)){
							if(strlen($ca->title) > 42){
								$trim_title = substr($ca->title, 0, 42) . "...";
							}
							else {
								$trim_title = $ca->title;
							}
							
							$row_color++;	
							if ($row_color%2 == 0) {
								echo "<tr bgcolor=\"#577EC4\" onmouseover=\"this.style.backgroundColor='#094493';this.style.cursor='hand';\" onmouseout=\"this.style.backgroundColor='#577EC4'\">";
							}
							else {
								echo "<tr bgcolor=\"#5E85CA\" onmouseover=\"this.style.backgroundColor='#094493';this.style.cursor='hand';\" onmouseout=\"this.style.backgroundColor='#5E85CA'\">";
							}
					?>
							<?
								// SHOW ID IF ADMIN IS LOGGED IN
								//if($_SESSION['access_type'] == "admin"){
							?>
							<td align="center" class="listing_id" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $ca->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
								<? echo $ca->id; ?>
							</td>
							<?
								//}
							?>
							<td align="left" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $staff->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $staff->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" class="list_links"><img src="images/mgr_icon_content2.gif" border="0"></a></td>
							<td width="100%" align="left" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $ca->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
								&nbsp;&nbsp;<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $ca->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" class="list_links"><? echo $trim_title; ?></a>
							</td>
							<td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $ca->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
								<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $ca->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" class="edit_links">[edit]</a>
							</td>
							<?
								// SHOW ID IF ADMIN IS LOGGED IN
								//if($_SESSION['access_type'] == "admin"){
							?>
							<?
								//}
							?>
						</tr>
					<?
						}
						if($ca_rows == 0){
					?>
						<tr>
							<td colspan="<? echo $colspan; ?>" bgcolor="#577EC4" align="center" valign="middle" height="60">
								<table>
									<tr>
										<td align="right" valign="bottom">
											<img src="images/mgr_check3_static.gif" valign="absmiddle">
										</td>
										<td align="right" valign="middle" nowrap>
											<font color="#FFE400">&nbsp;There are no listings at this time
										</td>
									</tr>
								</table>
							</td>
						</tr>
					<?
						}
					?>
					<?
						if($access_type == "admin"){
					?>
						<tr>
							<td colspan="<? echo $colspan; ?>" height="4"></td>
						</tr>
						<tr>
							<td colspan="<? echo $colspan; ?>" align="right">
								<table width="100%">
									<tr>
										<?
											if($ca_rows == 0){
										?>
										<td align="right"></td>
										<?
											}
											else{
										?>
										<td align="right"></td>
										<?
											}
										?>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="<? echo $colspan; ?>" height="4"></td>
						</tr>
					<?
						}
						else{
					?>
						<tr>
							<td colspan="<? echo $colspan; ?>" height="40">&nbsp;</td>
						</tr>
					<?
						}
					?>
					<!-- START: INSRUCTIONS -->
					<tr>
						<td colspan="<? echo $colspan; ?>"  bgcolor="#3C6ABB" align="left" style="padding: 3px; border-bottom: 1px solid #355894; border-top: 1px solid #5E85CA;">
							<table cellpadding="0" cellspacing="0">
								<tr>	
									<td width="100%"><font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;<a href="#" onclick="instructions();" class="title_links">INSTRUCTIONS</a></b> (click to expand/collapse)</font>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="<? echo $colspan; ?>"  bgcolor="#426AB3" align="center" style="padding-top: 10px; padding-bottom: 10px;" background="images/mgr_bg_texture.gif">
							<div style="position:relative; top:0px; left:0px;display:none;z-index:1" id="instructions">
							<table width="90%">
								<tr>
									<td valign="top"><img src="images/mgr_check5_ins.gif"></td>
									<td><font face="arial" color="#ffffff" style="font-size: 11;">To edit a listing click on the title of the item or click "[edit]" next to the item you would like to edit.</td>
								</tr>
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								<? if($access_type == "admin"){ ?>
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								<tr>
									<td colspan="2" align="right"><font face="arial" style="font-size: 11;" color="#C8D5ED"><? echo $plugin_name . " " . $plugin_version; ?></td>
								</tr>
								<? } ?>
							</table>
							</div>
						</td>
					</tr>
					<!-- END: INSRUCTIONS -->
				</table>			
			</td>
			</form>
			<?
				}
				else {
			?>
			<!-- NEW/EDIT COLUMN -->
			<td width="100%" valign="top">
				<table cellpadding="0" cellspacing="0" bgcolor="#577EC4" width="100%" style="border: 1px solid #5B8BD8;">
					<?
						// ADD NEW ITEM
						if($item_id == "new"){
							echo "<form name=\"data_form\" action=\"" . $actions_page . "?pmode=save_new\" method=\"post\" ENCTYPE=\"multipart/form-data\">";
							echo "<input type=\"hidden\" value=\"mgr.php?nav=" . $nav . "&message=saved&order_by=" . $order_by . "&order_type=" . $order_type . "&message=added\" name=\"return\">";
						}
						// EDIT ITEM
						else{
							$ca_result = mysql_query("SELECT * FROM email_copy where id = '$item_id'", $db);
							$ca = mysql_fetch_object($ca_result);
							echo "<form name=\"data_form\" action=\"" . $actions_page . "?pmode=save_edit\" method=\"post\" ENCTYPE=\"multipart/form-data\">";
							echo "<input type=\"hidden\" value=\"" . $ca->id . "\" name=\"item_id\">";
							echo "<input type=\"hidden\" value=\"mgr.php?nav=" . $nav . "&message=saved&item_id=" . $item_id . "\" name=\"return\">";
						}
					?>
					<input type="hidden" value="<? echo $reference; ?>" name="reference">
					<input type="hidden" value="<? echo $file_path; ?>" name="file_path">
					<input type="hidden" value="<? echo $image_path; ?>" name="image_path">
					<tr>
						<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894;">
							<table cellpadding="0" cellspacing="0" width="95%">
								<tr>	
									<td><b>&nbsp;<? if($item_id == "new"){ ?>ADD A NEW LISTING<? } else { ?>EDIT THIS LISTING<? } ?></b></td>
									<!--<td align="right"><b><a href="#" onclick="instructions();" class="title_links">INSTRUCTIONS</a></b> (click to expand/collapse)</font></td>-->
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td bgcolor="#577EC4" align="center" style="padding-top: 10px; padding-bottom: 10px;" background="images/mgr_bg_texture.gif">
							<table width="80%" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<? if($message == "saved"){ ?><td align="left" valign="middle" nowrap><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"><font color="#FFE400" style="font-size: 10;">&nbsp;Your changes have been saved.</td><? } ?>
												<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>"><img src="images/mgr_button_cancel.gif" border="0"></a>&nbsp;<? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_data();\">"; } ?><img src="images/mgr_button_save.gif" border="0"></a></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td height="10"></td>
								</tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td align="left"><img src="images/mgr_section_header_l.gif"></td>
												<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>EMAIL AREA DETAILS</b></td>
												<td align="right"><img src="images/mgr_section_header_r.gif"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<b>Email Area Title</b><br>(You CAN NOT use these characters in the title: /"\|][;:)(*^%$#@<>)<br>
										<input type="text" name="title" value="<? echo $ca->title; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<b>Subject:</b><br>(If you wish to use special characters in the the subject or body of the email you must use the html entity of it. Do a search on the web for HTML Entities)<br>
										<input type="text" name="subject" value="<? echo $ca->subject; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
									</td>
								</tr>
								<? if($setting->editor == 1 and $editor == 1){ ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box"><b>Content:</b><br><? $sContent = $ca->article; ?>
										<?php											
											$agent = $_SERVER['HTTP_USER_AGENT'];
											if(eregi("mac", $agent) && $setting->force_mac == 0){
										?>
											<textarea name="article" id="article" rows=8 cols=30 style="width: 100%"><?php echo $sContent; ?></textarea>										
										<?php
											} else {
										?>										
										<script language=JavaScript src='./scripts/innovaeditor.js'></script>
										<textarea name="article" id="article" rows=4 cols=30>
										<?
										function encodeHTML($sHTML)
										{
										$sHTML=ereg_replace("&","&amp;",$sHTML);
										$sHTML=ereg_replace("<","&lt;",$sHTML);
										$sHTML=ereg_replace(">","&gt;",$sHTML);
										return $sHTML;
										}
										if(isset($sContent)) echo encodeHTML($sContent);
										?>
										</textarea>
										<script>
										var oEdit1 = new InnovaEditor("oEdit1");
										oEdit1.initialRefresh=true;
										oEdit1.REPLACE("article");
										</script>
										<?php
											}
										?>
									</td>
								</tr>
								<? } else { ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<b>Copy</b><br>
										<textarea name="article" id="text_box" style="width:100%; height:200; border: 1px solid #000000;" rows="1" cols="20"><? echo $ca->article; ?></textarea>
										<p align="right"><a href="#" onclick="window.open('<? echo $setting->help_tips_link; ?>help_tips.php?pmode=html_tags', 'tips_win', ['HEIGHT=500', 'WIDTH=600', 'scrollbars=yes', 'dependent']);" class="edit_links">HTML Tips</a>
									</td>
								</tr>
								<? } ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<b>Available Variables:</b><br>
										<? echo $ca->variable; ?>
									</td>
								</tr>
								<?
									if($access_type == "admin"){
								?>
								<tr>
									<td height="15"></td>
								</tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td align="left"><img src="images/mgr_section_header_l.gif"></td>
												<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>ADMIN OPTIONS</b></td>
												<td align="right"><img src="images/mgr_section_header_r.gif"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<table width="100%">
											<tr>
												<td valign="top" class="data_box">
													Image Uploads Allowed<br>
													<input type="text" name="set_image_upload" value="<? echo $ca->image_upload; ?>" style="font-size: 11; font-weight: bold; width: 250; border: 1px solid #000000;" maxlength="4"><br>
													Image Width<br>
													<input type="text" name="set_image_width" value="<? echo $ca->image_w; ?>" style="font-size: 11; font-weight: bold; width: 250; border: 1px solid #000000;" maxlength="4"><br>
													Image Area Name<br>
													<input type="text" name="set_image_area_name" value="<? echo $ca->image_area_name; ?>" style="font-size: 11; font-weight: bold; width: 250; border: 1px solid #000000;" maxlength="100">
												</td>
												<td valign="top" class="data_box">
													File Uploads Allowed<br>
													<input type="text" name="set_file_upload" value="<? echo $ca->file_upload; ?>" style="font-size: 11; font-weight: bold; width: 250; border: 1px solid #000000;" maxlength="4"><br>
													File Area Name<br>
													<input type="text" name="set_file_area_name" value="<? echo $ca->file_area_name; ?>" style="font-size: 11; font-weight: bold; width: 250; border: 1px solid #000000;" maxlength="100">
												</td>
											</tr>
										</table>									 
									</td>
								</tr>
								<?
									}
									else{
								?>
								<input type="hidden" name="set_image_upload" value="<? echo $ca->image_upload; ?>">
								<input type="hidden" name="set_image_area_name" value="<? echo $ca->image_area_name; ?>">
								<input type="hidden" name="set_file_upload" value="<? echo $ca->file_upload; ?>">
								<input type="hidden" name="set_file_area_name" value="<? echo $ca->file_area_name; ?>">
								<input type="hidden" name="set_image_width" value="<? echo $ca->image_w; ?>">
								<input type="hidden" name="set_image_height" value="<? echo $ca->image_h; ?>">
								<?
									}
									if($item_id != "new"){
										if($ca->image_upload != ""){
											$image_upload = $ca->image_upload;
										}
										if($ca->image_area_name != ""){
											$image_area_name = $ca->image_area_name;
										}
										if($ca->file_upload != ""){
											$file_upload = $ca->file_upload;
										}
										if($ca->file_area_name != ""){
											$file_area_name = $ca->file_area_name;
										}
									}								
									include("image_upload_area.php");
									include("file_upload_area.php");
								?>
								<tr>
									<td height="10"></td>
								</tr>
								<tr>
									<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>"><img src="images/mgr_button_cancel.gif" border="0"></a>&nbsp;<? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_data();\">"; } ?><img src="images/mgr_button_save.gif" border="0"></a></td>
								</tr>
								</form>
							</table>
						</td>
					</tr>
				</table>
			</td>
			<?
				}
			?>
		</tr>
	</table>	
<?
	}
?>