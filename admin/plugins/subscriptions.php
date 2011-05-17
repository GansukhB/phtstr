<?php
	$plugin_name    = "Email List Plugin";
	$plugin_version = "1.0 [4.13.04]";
	
	
	if($execute_nav == 1){
		$nav_order = 6; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "Accounts"; // name of the nav that will appear on the page
	}
	else {
		
		// OPTIONS
			$image_upload     = 10; // number of images that can be uploaded per item / 0 for no files
			$image_path       = "../uploaded_images/";
			$image_caption    = 1; // 1 on | 0 off / Allow captions on images
			$image_area_name  = "";
			
			$file_upload      = 10; // number of files that can be uploaded per item / 0 for no files
			$file_path        = "../uploaded_files/";
			$file_area_name   = "";
			$file_active      = 1; // allow files to be set active/inactive
			
			$copy_link_option = 1;  // 1 on | 0 off / Allow image/file links to be copied
			
			$editor           = 1; // 1 on | 0 off
			$homepage_option  = 1; // 1 on | 0 off	
			$reference        = "members"; // used when saving and pulling images or files from/to the database
			$actions_page     = "actions_members.php"; // Actions page for processing forms
		
		// GET GENERAL SETTINGS FROM THE DATABASE
			$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
			$setting = mysql_fetch_object($settings_result);
			
			$item_id = $_GET['item_id'];
			$nav = $_GET['nav'];
			$order_type = $_GET['order_type'];
			$search = $_POST['search'];
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
			var agree=confirm("Save your changes?");
			if (agree) {
				<?php											
					$agent = $_SERVER['HTTP_USER_AGENT'];
					if(!eregi("safari", $agent)){
				?>
				document.getElementById("article").value=oEdit1.getHTMLBody();
				document.getElementById("info").value=oEdit2.getHTMLBody();
				<?php } ?>
				document.data_form.submit();
			}
			else {
				false
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
			<td align="left">
				<table>
					<form action="mgr.php?nav=<? echo $nav; ?>" method="post">
					<tr>
						<td><b>Search members for:<br>
							<input type="textbox" name="search" style="font-size: 11; width: 150;"><input type="submit" value="Search"><br>
					</form>
					<form action="<?php echo $actions_page; ?>?pmode=update_down_limit" method="post">
						<?php echo "<input type=\"hidden\" value=\"mgr.php?nav=" . $nav . "\" name=\"return\">"; ?>
						<b>Default Download Limits:</b><br>
						<input type="text" name="down_limit_m" value="<?php echo $setting->down_limit_m; ?>" style="width: 70px;"></b> <b>Monthly</b><br>
						<input type="text" name="down_limit_y" value="<?php echo $setting->down_limit_y; ?>" style="width: 70px;"></b> <b>Yearly</b><br>
						<input type="submit" value="Update"><br>
						(Enter 99999 to set as unlimited downloads in either monthly, yearly, or both.)<br>
						(These limits are set for all new accounts, they can be changed per member in the members details below.)
					</form>
					</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<?			
				if(!$item_id){
			?>
			<!-- LIST COLUMN -->
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
							$order_by = "l_name";
							$order_type = "";
						}
						
						// COLSPAN DEPENDING ON ADMIN LOGIN
						if($_SESSION['access_type'] == "admin"){
							$colspan="7";
						}
						else{
							$colspan="6";
						}
						
						#############################################
						## PAGING
						#############################################
						
						if($_GET['startat'] == "") {
							$startat = 0;
						} else {
							$startat = $_GET['startat'];
						}
						if($_GET['perpage'] == "") {
							$perpage = 25;
						} else {
							$perpage = $_GET['perpage'];
						}	
						
						$line = $startat;
						$templine = 0;
						$recordnum = 0;
						$total_views = 0;
						$count_total = 0;
						
						$photocount=1;
						$mystart = 1;
						
						#############################################
						
						if($search != ""){
							$members_result = mysql_query("SELECT * FROM members where name like '%$search%' or email like '%$search%' order by '$order_by' " . $order_type, $db);
						} else {
							$members_result = mysql_query("SELECT * FROM members order by '$order_by' " . $order_type, $db);
						}
						$members_rows = mysql_num_rows($members_result);
					?>
					<tr>
						<td colspan="<? echo $colspan; ?>" height="4"></td>
					</tr>
					<form name="listings" method="post">
					<input type="hidden" value="mgr.php?nav=<? echo $nav; ?>&message=deleted&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>" name="return">
					<input type="hidden" value="<? echo $file_path; ?>" name="file_path">
					<input type="hidden" value="<? echo $image_path; ?>" name="image_path">
					<input type="hidden" value="<? echo $reference; ?>" name="reference">
					<tr>
						<td colspan="<? echo $colspan; ?>">
							<table width="100%">
								<tr>
									<td style="padding-left: 8px;"><font style="font-size: 13;"><b>Members</b></td>
									<? if($message == "deleted"){ ?>
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
									<? if($message == "added"){ ?>
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
										if($members_rows >= 5){
									?>
									<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=new&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>"><img src="images/mgr_button_new.gif" border="0"></a>&nbsp;<a href="javascript:selectAll(document.listings,0);"><img src="images/mgr_button_select_all.gif" border="0"></a>&nbsp;<? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:delete_data();\">"; } ?><img src="images/mgr_button_delete_sel.gif" border="0"></a></td>
									<?
										}
										else if($members_rows == 0){
									?>
									<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=new&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>"><img src="images/mgr_button_new.gif" border="0"></a></td>
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
						<td colspan="2" bgcolor="<? if($order_by == "l_name"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" width="100%" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<? echo $nav; ?>&order_by=l_name&order_type=<? if($order_by == "l_name"){ echo $order_type_next; }  ?>&search=<? echo $search; ?>" class="title_links">NAME</a></b></font>								
						</td>
						<td bgcolor="<? if($order_by == "email"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" width="100%" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<? echo $nav; ?>&order_by=email&order_type=<? if($order_by == "email"){ echo $order_type_next; }  ?>&search=<? echo $search; ?>" class="title_links">EMAIL</a></b></font>								
						</td>
						<td bgcolor="<? if($order_by == "active"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<? echo $nav; ?>&order_by=active&order_type=<? if($order_by == "active"){ echo $order_type_next; }  ?>&search=<? echo $search; ?>" class="title_links">ACTIVE</a></b></font>								
						</td>
						<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;EDIT&nbsp;&nbsp;</b></font>								
						</td>
						<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;&nbsp;&nbsp;</b></font>								
						</td>
					</tr>
					<?
						while($members = mysql_fetch_object($members_result)){
							
							if($templine < $perpage and $line < $members_rows) {
					
								if($line == $recordnum) {
									$line++;
									$templine++;
							
									$emp_name = $members->name;
									
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
									<td align="center" class="listing_id" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $members->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>'">
										<? echo $members->id; ?>
									</td>
									<?
										}
									?>
									<td align="left" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $members->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>'"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $members->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>" class="list_links"><img src="images/mgr_icon_members2.gif" border="0"></a></td>
									<td align="left" width="100%" class="listing" style="padding-left: 10;" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $members->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>'">
										<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $members->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>" class="list_links"><? echo $emp_name; ?></a><br><font style="font-weight: normal;"><font color="#ffffff">Logged in <b><? echo $members->visits; ?></b> times
									</td>
									<td align="left" width="100%" style="padding-left: 6px;padding-right: 6px;" nowrap class="listing" style="padding-left: 10;" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $members->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>'">
										<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $members->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>" class="list_links"><? echo $members->email; ?></i>
									</td>
									<td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $members->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>'">
										<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $members->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>" class="edit_links"><b><? if($members->status == 1){ echo "Yes"; } else { echo "No"; } ?></a>
									</td>
									<td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $members->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>'">
										<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $members->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>" class="edit_links">[edit]</a>
									</td>
									<td align="center" class="listing">
										<input name="<? echo $members->id; ?>" type="checkbox" value="1">
									</td>
								</tr>
					<?
								######################################### PAGING
							
								$count_total = $count_total + $fcontents;
			
								if($photocount == $setting->dis_columns){
									//echo "<br style=\"clear: both;\" test=\"" . $photocount . "\">";
									$photocount = 1;
								} else {
									$photocount++;
								}
								
							}
						$recordnum++;
						}
						
					}
						if($members_rows > 0){
						$result_pages = ceil($members_rows/$perpage);
						
						if($_GET['page_num'] == ""){
							$page_num = 1;
						} else {
							$page_num = $_GET['page_num'];
						}
						
						if($result_pages < 1){
							$result_pages = 1;
						}
				?>
					<tr>
						<td colspan="<? echo $colspan; ?>">
								<div name="result_details" id="result_details" style="padding-left: 10px;padding-right: 10px;padding-top: 30px;width: 100%; clear: both;">
								<table width="100%" cellpadding="0" cellspacing="0">
									<tr>
										<td>
											Results: <b><? echo $members_rows; ?> Accounts</b> (<? echo $result_pages; ?> Pages)
										</td>
										<td align="right">
										Page:  
										<b>
										<?
											$page_startat = 0;
											for($page=1; $page <= $result_pages; $page++){
												if($page == $page_num){
													echo "[" . $page . "] ";	
												} else {
													echo "<a href=\"mgr.php?nav=" . $_GET['nav'] . "&gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "&startat=" . $page_startat . "&perpage=" . $perpage . "&page_num=" . $page . "\"><font color=\"#ffffff\">" . $page . "</a> ";	
												}
											$page_startat = $page_startat + $perpage;
											}			
										?>
										: 
										<?
											if($startat == 0){
										?>
											<font color="#B0B0B0">Previous</font>
										<?
											}
											else{
												if(($startat - $perpage) < 1) {
										?>
													<a href="<?php echo "mgr.php?nav=" . $_GET['nav'] . "&gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "&startat=0&perpage=" . $perpage . "&page_num=" . ($page_num-1) . "&sort_by=" . $sort_by; ?>"><font color="#ffffff">Previous</a>
										<?
												} else {
										?>
													<a href="<?php echo "mgr.php?nav=" . $_GET['nav'] . "&gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "&startat=" . ($startat - $perpage); ?>&perpage=<?php print($perpage) . "&page_num=" . ($page_num-1) . "&sort_by=" . $sort_by; ?>"><font color="#ffffff">Previous</a>
										<?
												}
											}
										?>
										 |
 
										<?
										
											if(($startat + $perpage) >= $members_rows){
										?>
											<font color="#B0B0B0">Next</font>
										<?
											}
											else{
												if($line < $members_rows) {
										?>
												<a href="<?php echo "mgr.php?nav=" . $_GET['nav'] . "&gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "&startat=" . ($startat + $perpage); ?>&perpage=<?php print($perpage) . "&page_num=" . ($page_num+1) . "&sort_by=" . $sort_by; ?>"><font color="#ffffff">Next</a>
										<?
												} else {
										?>
												<a href="<?php echo "mgr.php?nav=" . $_GET['nav'] . "&gid=" . $_GET['gid'] . "&sgid=" . $_GET['sgid'] . "&startat=" . ($startat + $perpage); ?>&perpage=<?php print($perpage) . "&page_num=" . ($page_num+1) . "&sort_by=" . $sort_by; ?>"><font color="#ffffff">Next</a>
										<?
												}
											}
										?>
										</b>
										</td>
									</tr>
								</table>
							</div>
							
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<?
						#####################################################						
						}
						
						if($members_rows == 0){
					?>
						<tr>
							<td colspan="<? echo $colspan; ?>" bgcolor="#577EC4" align="center" valign="middle" height="60">
								<table>
									<tr>
										<td align="right" valign="bottom">
											<img src="images/mgr_check3_static.gif" valign="absmiddle">
										</td>
										<td align="right" valign="middle" nowrap>
											<?
												if($search != ""){
											?>
												<font color="#FFE400" style="font-size: 10;">&nbsp;Your search returned no results
											<?		
												}else{
											?>
												<font color="#FFE400" style="font-size: 10;">&nbsp;There are no listings at this time
											<?
												}
											?>
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
										<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=new&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>"><img src="images/mgr_button_new.gif" border="0"></a></td>
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
										<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=new&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>"><img src="images/mgr_button_new.gif" border="0"></a>&nbsp;<a href="javascript:selectAll(document.listings,0);"><img src="images/mgr_button_select_all.gif" border="0"></a>&nbsp;<? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:delete_data();\">"; } ?><img src="images/mgr_button_delete_sel.gif" border="0"></a></td>
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
								<? if($message == "deleted"){ ?>
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
							echo "<input type=\"hidden\" value=\"mgr.php?nav=" . $_GET['nav'] . "&message=saved&order_by=" . $order_by . "&order_type=" . $order_type . "&message=added\" name=\"return\">";
						}
						// EDIT ITEM
						else{
							$members_result = mysql_query("SELECT * FROM members where id = '$item_id'", $db);
							$members = mysql_fetch_object($members_result);
							
							session_register("sub_member");
							$_SESSION['sub_member'] = $members->id;
							
							$this_day = substr($members->added, 6, 2);
							$this_month = substr($members->added, 4, 2);
							$this_year = substr($members->added, 0, 4);
							
														
							if($members->sub_length == "Y"){
								$addmonths = 12;
							} else {
								$addmonths = 1;
							}
							
							$basedate = strtotime($members->added);
							$mydate = strtotime("+$addmonths month", $basedate);							
							$future_month = date("m/d/Y", $mydate);
							
							$end_day = substr($sub_ends, 6, 2);
							$end_month = substr($sub_ends, 4, 2);
							$end_year = substr($sub_ends, 0, 4);
							
							$sub_ends = $end_month . "/" . $end_day . "/" . $end_year;
									
							echo "<form name=\"data_form\" action=\"" . $actions_page . "?pmode=save_edit\" method=\"post\" ENCTYPE=\"multipart/form-data\">";
							echo "<input type=\"hidden\" value=\"" . $members->id . "\" name=\"item_id\">";
							echo "<input type=\"hidden\" value=\"mgr.php?nav=" . $_GET['nav'] . "&message=saved&item_id=" . $_GET['item_id'] . "\" name=\"return\">";
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
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>Name</b><br>
													<input type="text" name="name" value="<? echo $members->name; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>Email</b><br>
													<input type="text" name="email" value="<? echo $members->email; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>Password</b><br>
													<input type="text" name="password" value="<? echo $members->password; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>											
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>Phone</b><br>
													<input type="text" name="phone" value="<? echo $members->phone; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>											
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>Address 1</b><br>
													<input type="text" name="address1" value="<? echo $members->address1; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>											
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>Address 2</b><br>
													<input type="text" name="address2" value="<? echo $members->address2; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>											
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>City</b><br>
													<input type="text" name="city" value="<? echo $members->city; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>											
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>State</b><br>
													<input type="text" name="state" value="<? echo $members->state; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>											
											</tr>
											
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>Zip</b><br>
													<input type="text" name="zip" value="<? echo $members->zip; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>											
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>Country</b><br>
													<input type="text" name="country_display" value="<? echo $members->country; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150"><br>
													<b>Change country to:</b><br>
													<? include("../country.php"); ?>
												</td>											
											</tr>
											<? /*
											*/
											?>
											<? if($item_id == "new"){ ?>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>Download Limit</b> (Monthly limit | Enter 99999 for unlimited downloads)<br>
													<input type="text" name="down_limit_m" value="<? echo $setting->down_limit_m; ?>" style="font-size: 13; font-weight: bold; width: 150px; border: 1px solid #000000;" maxlength="30">
												</td>
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>Download Limit</b> (Yearly limit | Enter 99999 for unlimited downloads)<br>
													<input type="text" name="down_limit_y" value="<? echo $setting->down_limit_y; ?>" style="font-size: 13; font-weight: bold; width: 150px; border: 1px solid #000000;" maxlength="30">
												</td>
											</tr>
											<? } else { ?>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>Download Limit</b> (Monthly limit | Enter 99999 for unlimited downloads)<br>
													<input type="text" name="down_limit_m" value="<? echo $members->down_limit_m; ?>" style="font-size: 13; font-weight: bold; width: 150px; border: 1px solid #000000;" maxlength="30">
												</td>
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>Download Limit</b> (Yearly limit | Enter 99999 for unlimited downloads)<br>
													<input type="text" name="down_limit_y" value="<? echo $members->down_limit_y; ?>" style="font-size: 13; font-weight: bold; width: 150px; border: 1px solid #000000;" maxlength="30">
												</td>
											</tr>
											<? } ?>
										</table>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;"><b>Signup Date</b><br>
										
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
												
												<? if($item_id  != "new"){ ?>
												<td>&nbsp;&nbsp;&nbsp;Subscription Ends: <? echo $future_month; ?></td>
												<? } ?>
											</tr>
										</table>									
									</td>
								</tr>
								<? if($setting->editor == 1 and $editor == 1){ ?>
									<tr>
									<td bgcolor="#5E85CA" class="data_box"><b>NOTES:</b> (For your use only and can not be seen by the member)<br>
									  <? 
									 		$sContent = $members->notes;
											$sContent_info = $members->info;			
											$agent = $_SERVER['HTTP_USER_AGENT'];
											if(eregi("mac", $agent) && $setting->force_mac == 0){
										?>
											<textarea name="article" id="article" rows=8 cols=30 style="width: 100%"><?php echo $sContent; ?></textarea>
										</td>
									</tr>
								  <tr>
								  	<td bgcolor="#5E85CA" class="data_box"><b>INFO:</b> (For My Info page and can be seen only by this member)<br>
										<textarea name="info" id="info" rows=8 cols=30 style="width: 100%"><?php echo $sContent_info; ?></textarea>
										</td>
									</tr>
										<?php
											} else {
										?>										
										<script language=JavaScript src='./scripts/innovaeditor.js'></script>
										<?
										 function encodeHTML($sHTML){
										$sHTML=ereg_replace("&","&amp;",$sHTML);
										$sHTML=ereg_replace("<","&lt;",$sHTML);
										$sHTML=ereg_replace(">","&gt;",$sHTML);
										return $sHTML;
										}
										?>
										<textarea name="article" id="article" rows=4 cols=30>
										<?
										if(isset($sContent)) echo encodeHTML($sContent);
										?>
										</textarea>
										<script>
										var oEdit1 = new InnovaEditor("oEdit1");
										oEdit1.REPLACE("article");
										</script>
									</td>
								</tr>
								<tr>
										<td bgcolor="#5E85CA" class="data_box"><b>INFO:</b> (For My Info page and can be seen only by this member)<br>
										<textarea name="info" id="info" rows=4 cols=30>
										<?
										if(isset($sContent_info)) echo encodeHTML($sContent_info);
										?>
										</textarea>
										<script>
										var oEdit2 = new InnovaEditor("oEdit2");
										oEdit2.REPLACE("info");
										</script>
										<?php
											}
										?>
										</td>
								</tr>
								<? } else { ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<b>Notes</b><br>
										<textarea name="article" id="text_box" style="width:100%; height:200; border: 1px solid #000000;" rows="1" cols="20"><? echo $members->notes; ?></textarea>
										<p align="right"><a href="#" onClick="window.open('<? echo $setting->help_tips_link; ?>help_tips.php?pmode=html_tags', 'tips_win', ['HEIGHT=500', 'WIDTH=600', 'scrollbars=yes', 'dependent']);" class="edit_links">HTML Tips</a>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<b>Info</b><br>
										<textarea name="info" id="text_box" style="width:100%; height:200; border: 1px solid #000000;" rows="1" cols="20"><? echo $members->info; ?></textarea>
										<p align="right"><a href="#" onClick="window.open('<? echo $setting->help_tips_link; ?>help_tips.php?pmode=html_tags', 'tips_win', ['HEIGHT=500', 'WIDTH=600', 'scrollbars=yes', 'dependent']);" class="edit_links">HTML Tips</a>
									</td>
								</tr>
								<? } ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box"><b>Subscription Length:</b> <br />
										<input type="radio" name="sub_length" value="Y" <? if($members->sub_length == "Y" or $item_id == "new"){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Year</b> | <input type="radio" name="sub_length" value="M" <? if($members->sub_length == "M" and $item_id != "new"){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Month</b> | <input type="radio" name="sub_length" value="F" <? if($members->sub_length == "F" and $item_id != "new"){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Free</b> <br>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<input type="checkbox" name="status" value="1" <? if($members->status == 1 or $item_id == "new"){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Active</b> (allow access to the website)<br>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<a href="../lightbox.php" target="_blank"><b>View Lightbox</b></a><br>Click to edit / view this members current lightbox
									</td>
								</tr>
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