<?php
	$plugin_name    = "Prints Plugin";
	$plugin_version = "2.2 [4.16.04]";
	
	if($execute_nav == 1){
		$nav_order = 3; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "Prints & Products"; // name of the nav that will appear on the page
	}
	else {
		
		// OPTIONS
			$image_upload     = 10; // number of images that can be uploaded per prints item / 0 for no files
			$image_path       = "../uploaded_images/";
			$image_caption    = 1; // 1 on | 0 off / Allow captions on images
			$image_area_name  = "";
			
			$file_upload      = 10; // number of files that can be uploaded per prints item / 0 for no files
			$file_path        = "../uploaded_files/";
			$file_area_name   = "";
			$file_active      = 1; // allow files to be set active/inactive
			
			$copy_link_option = 1;  // 1 on | 0 off / Allow image/file links to be copied
			$homepage_option  = 0; // 1 on | 0 off	
			$editor           = 1; // 1 on | 0 off
			$reference        = "prints"; // used when saving and pulling images or files from/to the database
			$actions_page     = "actions_prints.php"; // Actions page for processing forms			
		
		// GET GENERAL SETTINGS FROM THE DATABASE
			$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
			$setting = mysql_fetch_object($settings_result);
			
			$item_id = $_GET['item_id'];
			$nav = $_GET['nav'];
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
						if($order_type == "desc"){
							$order_type_next = "";
						}
						else{
							$order_type_next = "desc";
						}
						
						if($order_by == ""){
							$order_by = "porder";
							$order_type = "desc";
						}
						
						$colspan = 7;
						// COLSPAN DEPENDING ON ADMIN LOGIN
						if($_SESSION['access_type'] == "admin"){
							$colspan= $colspan+1;
						}
						
						if($homepage_option == 1){
							$colspan= $colspan + 1;
						}
						
						$prints_result = mysql_query("SELECT * FROM prints order by $order_by " . $order_type, $db);
						$prints_rows = mysql_num_rows($prints_result);
					?>
					<tr>
						<td colspan="<? echo $colspan; ?>" height="4"></td>
					</tr>
					<tr>
						<td colspan="<? echo $colspan; ?>">
							<table width="100%">
								<tr>
									<td style="padding-left: 8px;"><font style="font-size: 13;"><b>Website Prints & Products</b></td>
									<? if($_GET['message'] == "deleted"){ ?>
									<td valign="middle">
										<table>
											<tr>
												<td>&nbsp;</td>
												<td valign="middle"><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"></td>
												<td valign="middle"><font color="#FFE400" style="font-size: 10;">The selected item(s) have been deleted.</td>
											</tr>
										</table>
									</td>
									<? } ?>
									<? if($_GET['message'] == "added"){ ?>
									<td valign="middle">
										<table>
											<tr>
												<td>&nbsp;</td>
												<td valign="middle"><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"></td>
												<td valign="middle"><font color="#FFE400" style="font-size: 10;">Your new listing has been added.</td>
											</tr>
										</table>
									</td>
									<? } ?>
									<?
										if($prints_rows >= 5){
									?>
									<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=new&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>"><img src="images/mgr_button_new.gif" border="0"></a>&nbsp;<a href="javascript:selectAll(document.listings,0);"><img src="images/mgr_button_select_all.gif" border="0"></a>&nbsp;<? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:delete_data();\">"; } ?><img src="images/mgr_button_delete_sel.gif" border="0"></a></td>
									<?
										}
										else if($prints_rows == 0){
									?>
									<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=new&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>"><img src="images/mgr_button_new.gif" border="0"></a></td>
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
					<tr>
						<?
							// SHOW ID IF ADMIN IS LOGGED IN
							if($_SESSION['access_type'] == "admin"){
						?>
							<td bgcolor="<? if($order_by == "id"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894;border-top: 1px solid #5B8BD8;" nowrap>	
								<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;<a href="mgr.php?nav=<? echo $nav; ?>&order_by=id&order_type=<? if($order_by == "id"){ echo $order_type_next; }  ?>" class="title_links">ID</a>&nbsp;&nbsp;</b></font>								
							</td>
						<?
							}
						?>
						<td bgcolor="<? if($order_by == "porder"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;<a href="mgr.php?nav=<? echo $nav; ?>&order_by=porder&order_type=<? if($order_by == "porder"){ echo $order_type_next; }  ?>" class="title_links">DISPLAY ORDER</a>&nbsp;&nbsp;</b></font>								
						</td>
						<td colspan="2" bgcolor="<? if($order_by == "name"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" width="100%" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<? echo $nav; ?>&order_by=name&order_type=<? if($order_by == "name"){ echo $order_type_next; }  ?>" class="title_links">NAME</a></b></font>								
						</td>
						<td bgcolor="<? if($order_by == "price"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;<a href="mgr.php?nav=<? echo $nav; ?>&order_by=price&order_type=<? if($order_by == "price"){ echo $order_type_next; }  ?>" class="title_links">PRICE</a>&nbsp;&nbsp;</b></font>								
						</td>
						<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;EDIT&nbsp;&nbsp;</b></font>								
						</td>
						<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;&nbsp;&nbsp;</b></font>								
						</td>
					</tr>
					<?
						
						//$prints_result = mysql_query("SELECT * FROM prints order by $order_by " . $order_type, $db);
						//$prints_rows = mysql_num_rows($prints_result);
						while($prints = mysql_fetch_object($prints_result)){
							//$posted = round(substr($prints->publish_date, 4, 2)) . "/" . round(substr($prints->publish_date, 6, 2)) . "/" . round(substr($prints->publish_date, 0, 4));
							//$posted_short = round(substr($prints->publish_date, 4, 2)) . "/" . round(substr($prints->publish_date, 6, 2));
							if(strlen($prints->name) > 60){
								$trim_title = substr($prints->name, 0, 60) . "...";
							}
							else {
								$trim_title = $prints->name;
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
								if($_SESSION['access_type'] == "admin"){
							?>
								<td align="center" class="listing_id" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $prints->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
									<? echo $prints->id; ?>
								</td>
							<?
								}
							?>
							<td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $prints->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
								<? echo $prints->porder; ?></a>
							</td>
							<td align="left" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $prints->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $prints->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" class="list_links"><img src="images/mgr_icon_prints.gif" border="0"></a></td>
							<td align="left" width="100%" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $prints->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
								&nbsp;&nbsp;<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $prints->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" class="list_links"><? echo $trim_title; ?></a>
							</td>
							<td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $prints->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
								$<? echo $prints->price; ?></a>
							</td>
							<td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $prints->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
								<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $prints->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" class="edit_links">[edit]</a>
							</td>
							<td align="center" class="listing">
								<input name="<? echo $prints->id; ?>" type="checkbox" value="1">
							</td>
						</tr>
					<?
						}
						if($prints_rows == 0){
					?>
						<tr>
							<td colspan="<? echo $colspan; ?>" bgcolor="#577EC4" align="center" valign="middle" height="60">
								<table>
									<tr>
										<td align="right" valign="bottom">
											<img src="images/mgr_check3_static.gif" valign="absmiddle">
										</td>
										<td align="right" valign="middle" nowrap>
											<font color="#FFE400" style="font-size: 10;">&nbsp;There are no listings at this time
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="<? echo $colspan; ?>" height="4"></td>
						</tr>
						<tr>
							<td colspan="<? echo $colspan; ?>" align="right">
								<table width="100%">
									<tr>
										<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=new&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>"><img src="images/mgr_button_new.gif" border="0"></a></td>
									</tr>
								</table>
							</td>
						</tr>
					<?
						}
						else{
					?>
						<tr>
							<td colspan="<? echo $colspan; ?>" height="4"></td>
						</tr>
						<tr>
							<td colspan="<? echo $colspan; ?>" align="right">
								<table width="100%">
									<tr>
										<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=new&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>"><img src="images/mgr_button_new.gif" border="0"></a>&nbsp;<a href="javascript:selectAll(document.listings,0);"><img src="images/mgr_button_select_all.gif" border="0"></a>&nbsp;<? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:delete_data();\">"; } ?><img src="images/mgr_button_delete_sel.gif" border="0"></a></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="<? echo $colspan; ?>" height="4"></td>
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
								<? if($_GET['message'] == "deleted"){ ?>
								<tr>
									<td valign="top"><img src="images/mgr_check5_ins.gif" valign="absmiddle"></td>
									<td><font color="#FFE400" style="font-size: 10;">The selected item(s) have been deleted.</td>
								</tr>
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								<? } ?>
								<tr>
									<td valign="top"><img src="images/mgr_check5_ins.gif"></td>
									<td><font face="arial" color="#ffffff" style="font-size: 11;">To add a new listing item click on the <b>"New"</b> button or <a href="mgr.php?nav=<? echo $nav; ?>&item_id=new" class="yellow_links">click here</a>.</td>
								</tr>
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								<tr>
									<td valign="top"><img src="images/mgr_check5_ins.gif"></td>
									<td><font face="arial" color="#ffffff" style="font-size: 11;">To edit a listing click on the title of the item or click "[edit]" next to the item you would like to edit.</td>
								</tr>
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								<!--
								<tr>
									<td valign="bottom"><img src="images/mgr_check5_ins.gif"></td>
									<td><font face="arial" color="#ffffff" style="font-size: 11;">To get more information on a listing roll over the <img src="images/mgr_info2.gif"> graphic.</td>
								</tr>
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								-->
								<tr>
									<td valign="top"><img src="images/mgr_check5_ins.gif"></td>
									<td><font face="arial" color="#ffffff" style="font-size: 11;">To delete listings items check the box of the items you would like to delete and hit the <b>"Delete"</b> button.</td>
								</tr>
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								<tr>
									<td valign="top"><img src="images/mgr_check5_ins.gif"></td>
									<td><font face="arial" color="#ffffff" style="font-size: 11;">Click the <b>"Select All"</b> button to select all listings.</td>
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
							$this_day = date("d");
							$this_month = date("m");
							$this_year = date("Y");
							echo "<form name=\"data_form\" action=\"" . $actions_page . "?pmode=save_new\" method=\"post\" ENCTYPE=\"multipart/form-data\">";
							echo "<input type=\"hidden\" value=\"mgr.php?nav=" . $nav . "&message=saved&order_by=" . $order_by . "&order_type=" . $order_type . "&message=added\" name=\"return\">";
						}
						// EDIT ITEM
						else{
							$prints_result = mysql_query("SELECT * FROM prints where id = '$item_id'", $db);
							$prints = mysql_fetch_object($prints_result);
									
							$this_day = substr($prints->publish_date, 6, 2);
							$this_month = substr($prints->publish_date, 4, 2);
							$this_year = substr($prints->publish_date, 0, 4);
							echo "<form name=\"data_form\" action=\"" . $actions_page . "?pmode=save_edit\" method=\"post\" ENCTYPE=\"multipart/form-data\">";
							echo "<input type=\"hidden\" value=\"" . $prints->id . "\" name=\"item_id\">";
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
									<td nowrap><font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;<? if($item_id == "new"){ ?>ADD A NEW LISTING<? } else { ?>EDIT THIS LISTING<? } ?></b></font></td>
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
												<? if($_GET['message'] == "saved"){ ?><td align="left" valign="middle" nowrap><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"><font color="#FFE400" style="font-size: 10;">&nbsp;Your changes have been saved.</td><? } ?>
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
												<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>PRODUCT DETAILS</b></td>
												<td align="right"><img src="images/mgr_section_header_r.gif"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;"><b>Item Title</b> (Displays in a drop down box)<br>(You CAN NOT use these characters in the title: /"?\|][;:=+)(*&^%$#@!<>,)
										<input type="text" name="name" value="<? echo htmlspecialchars($prints->name); ?>" style="width: 300px;">
									</td>
								</tr>
								<? if($setting->editor == 1 and $editor == 1){ ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box"><b>Description:</b><br><? $sContent = $prints->article; ?>
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
										<b>Description:</b><br>
										<textarea name="article" id="text_box" style="width:100%; height:200; border: 1px solid #000000;" rows="1" cols="20"><? echo $ca->article; ?></textarea>
										<p align="right"><a href="#" onclick="window.open('<? echo $setting->help_tips_link; ?>help_tips.php?pmode=html_tags', 'tips_win', ['HEIGHT=500', 'WIDTH=600', 'scrollbars=yes', 'dependent']);" class="edit_links">HTML Tips</a>
									</td>
								</tr>
								<?
									}
								?>
								<!--
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;"><b>Size of Prints</b><br>
										<textarea name="size"><? echo $prints->size; ?></textarea>
									</td>
								</tr>
								-->
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;"><b>Price </b> (Each | Do not include $)</b><br>
										<input type="text" name="price" value="<? echo $prints->price; ?>" style="font-size: 13; font-weight: bold; width: 100px; border: 1px solid #000000;" maxlength="150">
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;"><b>Order </b>(Show in this order on the site)</b><br>
										<input type="text" name="porder" value="<? echo $prints->porder; ?>" style="font-size: 13; font-weight: bold; width: 100px; border: 1px solid #000000;" maxlength="150">
									</td>
								</tr>
								<!-- SHIPPING -->
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;">
										<b>If fixed shipping is set it will override shipping charges per print.</b><br><br>
										<b>Shipping Price:</b><br>
										<b>First item:</b> (Do not include $. Enter 0.00 if there is no shipping charge)</b><br>
										<input type="text" name="ship_price1" value="<? echo $prints->ship_price1; ?>" style="font-size: 13; font-weight: bold; width: 100px; border: 1px solid #000000;" maxlength="150"><br>
										<font face="arial" color="#ffffff" style="font-size: 11;"><b>Each additional item:</b> (Do not include $. Enter 0.00 for no shipping cost for additional items)</b><br>
										<input type="text" name="ship_price2" value="<? echo $prints->ship_price2; ?>" style="font-size: 13; font-weight: bold; width: 100px; border: 1px solid #000000;" maxlength="150">
										<br><input type="checkbox" name="bypass" value="1" <? if($prints->bypass == 1){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Bypass Shipping</b> (This allows this item to bypass all shipping charges for use with in store pickup items, or large items that can't be shipped.)<br>
										<br><input type="checkbox" name="taxable" value="1" <? if($prints->taxable == 1){ echo "checked"; } ?><? if($item_id == "new"){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Taxable</b> (If unchecked the store will not charge tax on this item.)<br>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;"><b>Quantity Available:</b> (Enter 999 for unlimited)</b><br>
										<input type="text" name="quan_avail" value="<? if($item_id == "new"){ echo "999"; } else { echo $prints->quan_avail; } ?>" style="font-size: 13; font-weight: bold; width: 100px; border: 1px solid #000000;" maxlength="150">
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<input type="checkbox" name="visible" value="1" <? if($prints->visible == 1 or $item_id == "new"){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Public List Visibility</b> (Display on the print info page, the print info page is a page that list all print titles and descriptions about it. This does not disable a print, to disable a print set the quantity to 0 "zero")<br>
									</td>
								</tr>
								<tr>
									<td height="10"></td>
								</tr>
								<?
								include("image_upload_area.php");
								include("file_upload_area.php");
								?>
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