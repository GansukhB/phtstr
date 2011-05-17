<?php
	$plugin_name    = "News Plugin";
	$plugin_version = "2.2 [4.16.04]";
	
	if($execute_nav == 1){
		$nav_order = 3; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "News"; // name of the nav that will appear on the page
	}
	else {
		
		// OPTIONS
			$image_upload     = 3; // number of images that can be uploaded per news item / 0 for no files
			$image_path       = "../uploaded_images/";
			$image_caption    = 1; // 1 on | 0 off / Allow captions on images
			$image_area_name  = "";
			
			$file_upload      = 2; // number of files that can be uploaded per news item / 0 for no files
			$file_path        = "../uploaded_files/";
			$file_area_name   = "";
			$file_active      = 1; // allow files to be set active/inactive
			
			$copy_link_option = 1;  // 1 on | 0 off / Allow image/file links to be copied
			$homepage_option  = 1; // 1 on | 0 off	
			$editor           = 1; // 1 on | 0 off
			$reference        = "news"; // used when saving and pulling images or files from/to the database
			$actions_page     = "actions_news.php"; // Actions page for processing forms			
		
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
				document.getElementById("article").value=oEdit1.getHTMLBody();
				<?php } ?>
				document.data_form.submit();
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
							$order_by = "publish_date";
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
						
						$news_result = mysql_query("SELECT * FROM news order by $order_by " . $order_type, $db);
						$news_rows = mysql_num_rows($news_result);
					?>
					<tr>
						<td colspan="<? echo $colspan; ?>" height="4"></td>
					</tr>
					<tr>
						<td colspan="<? echo $colspan; ?>">
							<table width="100%">
								<tr>
									<td style="padding-left: 8px;"><font style="font-size: 13;"><b>Website News</b></td>
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
										if($news_rows >= 5){
									?>
									<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=new&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>"><img src="images/mgr_button_new.gif" border="0"></a>&nbsp;<a href="javascript:selectAll(document.listings,0);"><img src="images/mgr_button_select_all.gif" border="0"></a>&nbsp;<? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:delete_data();\">"; } ?><img src="images/mgr_button_delete_sel.gif" border="0"></a></td>
									<?
										}
										else if($news_rows == 0){
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
						<td colspan="2" bgcolor="<? if($order_by == "title"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" width="100%" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<? echo $nav; ?>&order_by=title&order_type=<? if($order_by == "title"){ echo $order_type_next; }  ?>" class="title_links">NEWS TITLE</a></b></font>								
						</td>
						<td bgcolor="<? if($order_by == "publish_date"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;<a href="mgr.php?nav=<? echo $nav; ?>&order_by=publish_date&order_type=<? if($order_by == "publish_date"){ echo $order_type_next; }  ?>" class="title_links">DATE</a>&nbsp;&nbsp;</b></font>								
						</td>
						<td bgcolor="<? if($order_by == "active"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<? echo $nav; ?>&order_by=active&order_type=<? if($order_by == "active"){ echo $order_type_next; }  ?>" class="title_links">ACTIVE</a></b></font>								
						</td>
						<?
							if($homepage_option == 1){
						?>
							<td bgcolor="<? if($order_by == "homepage"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
								<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<? echo $nav; ?>&order_by=homepage&order_type=<? if($order_by == "homepage"){ echo $order_type_next; }  ?>" class="title_links">HOMEPAGE</a></b></font>								
							</td>
						<?
							}
						?>
						<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;EDIT&nbsp;&nbsp;</b></font>								
						</td>
						<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;&nbsp;&nbsp;</b></font>								
						</td>
					</tr>
					<?
						
						//$news_result = mysql_query("SELECT * FROM news order by $order_by " . $order_type, $db);
						//$news_rows = mysql_num_rows($news_result);
						while($news = mysql_fetch_object($news_result)){
							$posted = round(substr($news->publish_date, 4, 2)) . "/" . round(substr($news->publish_date, 6, 2)) . "/" . round(substr($news->publish_date, 0, 4));
							$posted_short = round(substr($news->publish_date, 4, 2)) . "/" . round(substr($news->publish_date, 6, 2));
							if(strlen($news->title) > 60){
								$trim_title = substr($news->title, 0, 60) . "...";
							}
							else {
								$trim_title = $news->title;
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
								<td align="center" class="listing_id" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $news->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
									<? echo $news->id; ?>
								</td>
							<?
								}
							?>
							<td align="left" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $staff->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $staff->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" class="list_links"><img src="images/mgr_icon_news2.gif" border="0"></a></td>
							<td align="left" width="100%" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $news->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
								&nbsp;&nbsp;<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $news->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" class="list_links"><? echo $trim_title; ?></a>
							</td>
							<td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $news->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
								<? echo $posted_short; ?></a>
							</td>
							<td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $news->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
								<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $news->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" class="edit_links"><b><? if($news->active == 1){ echo "Yes"; } else { echo "No"; } ?></a>
							</td>
							<?
								if($homepage_option == 1){
							?>
								<td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $news->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
									<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $news->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" class="edit_links"><b><? if($news->homepage == 1){ echo "Yes"; } else { echo "No"; } ?></a>
								</td>
							<?
								}
							?>
							<td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $news->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
								<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $news->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" class="edit_links">[edit]</a>
							</td>
							<td align="center" class="listing">
								<input name="<? echo $news->id; ?>" type="checkbox" value="1">
							</td>
						</tr>
					<?
						}
						if($news_rows == 0){
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
									<td width="100%"><font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;<a href="#" onClick="instructions();" class="title_links">INSTRUCTIONS</a></b> (click to expand/collapse)</font>
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
							$news_result = mysql_query("SELECT * FROM news where id = '$item_id'", $db);
							$news = mysql_fetch_object($news_result);
									
							$this_day = substr($news->publish_date, 6, 2);
							$this_month = substr($news->publish_date, 4, 2);
							$this_year = substr($news->publish_date, 0, 4);
							echo "<form name=\"data_form\" action=\"" . $actions_page . "?pmode=save_edit\" method=\"post\" ENCTYPE=\"multipart/form-data\">";
							echo "<input type=\"hidden\" value=\"" . $news->id . "\" name=\"item_id\">";
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
												<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>NEWS DETAILS</b></td>
												<td align="right"><img src="images/mgr_section_header_r.gif"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;"><b>Title</b><br>(Can NOT use: /"\|][;:)(*^%$#@<> in the title.)<br>
										<input type="text" name="title" value="<? echo $news->title; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
									</td>
								</tr>
								<? if($setting->editor == 1 and $editor == 1){ ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<? $sContent = $news->article; ?>
										<font face="arial" color="#ffffff" style="font-size: 11;"><b>Article:</b><br>
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
										<font face="arial" color="#ffffff" style="font-size: 11;"><b>Article</b><br>
										<textarea name="article" id="text_box" style="width:100%; height:200; border: 1px solid #000000;" rows="1" cols="20"><? echo $news->article; ?></textarea>
										<p align="right"><a href="#" onClick="window.open('<? echo $setting->help_tips_link; ?>help_tips.php?pmode=html_tags', 'tips_win', ['HEIGHT=500', 'WIDTH=600', 'scrollbars=yes', 'dependent']);" class="edit_links">HTML Tips</a>
									</td>
								</tr>
								<? } ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;"><b>Date</b><br>
										
										<table cellpadding="0" cellspacing="0">
											<tr>
												<td>
												<select name="s_month" style="font-size: 11; font-weight: bold; width: 70;">
													<?
													$month_x = 1;
													while($month_x <= 12){
														if(strlen($month_x) < 2){
															$month_x = "0" . $month_x;
														}
													?>
														<option value="<? echo $month_x; ?>" <? if($month_x == $this_month){ echo "selected"; } ?>><? echo $month_x; ?></option>
													<?
													$month_x++;
													}
													?>
												</select>
												</td>
											
												<td>&nbsp;</td>
												
												<td>
												<select name="s_day" style="font-size: 11; font-weight: bold; width: 70;">
													<?
													$day_x = 1;
													while($day_x <= 31){
														if(strlen($day_x) < 2){
															$day_x = "0" . $day_x;
														}
													?>
														<option value="<? echo $day_x; ?>" <? if($day_x == $this_day){ echo "selected"; } ?>><? echo $day_x; ?></option>
													<?
													$day_x++;
													}
													?>
												</select>
												</td>
											
												<td>&nbsp;</td>
											
												<td>
												<select name="s_year" style="font-size: 11; font-weight: bold; width: 110;">
													<?
													$year_x = 2004;
													while($year_x <= 2010){
													?>
														<option value="<? echo $year_x; ?>" <? if($year_x == $this_year){ echo "selected"; } ?>><? echo $year_x; ?></option>
													<?
													$year_x++;
													}
													?>
												</select>
												</td>
												
												<td>&nbsp;</td>
												
												<td><a name="calendar"><a href="#calendar" onclick="window.open('mgr_calendar.php?field_var=s', 'calendar_win', ['HEIGHT=175', 'WIDTH=180', 'dependent']);"><img src="images/mgr_button_calendar.gif" border="0" alt="View Calendar"></td>
											</tr>
										</table>									
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<input type="checkbox" name="active" value="1" <? if($news->active == 1 or $item_id == "new"){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Active</b> (Display on the public website)<br>
									</td>
								</tr>
								<? if($homepage_option == 1){ ?>
									<tr>
										<td bgcolor="#5E85CA" class="data_box">
											<input type="checkbox" name="homepage" value="1" <? if($news->homepage == 1){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Home Page</b> (Display on the home page)<br>
										</td>
									</tr>
								<? } ?>
								<?
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