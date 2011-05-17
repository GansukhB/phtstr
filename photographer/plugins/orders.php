<?php
	$plugin_name    = "Orders Plugin";
	$plugin_version = "1.0 [4.13.04]";
	
	$currency_result = mysql_query("SELECT * FROM currency where active = '1'", $db);
	$currency = mysql_fetch_object($currency_result);
	
	if($execute_nav == 1){
		$nav_order = 6; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "Orders"; // name of the nav that will appear on the page
	}
	else {
		
		// OPTIONS
			$image_upload     = 0; // number of images that can be uploaded per visitor item / 0 for no files
			$image_path       = "../uploaded_images/";
			$image_caption    = 0; // 1 on | 0 off / Allow captions on images
			$image_area_name  = "";
				
			$file_upload      = 0; // number of files that can be uploaded per visitor item / 0 for no files
			$file_path        = "../uploaded_files/";
			$file_area_name   = "";
			
			$copy_link_option = 0;  // 1 on | 0 off / Allow image links to be copied
			
			$editor           = 1; // 1 on | 0 off
			$homepage_option  = 1; // 1 on | 0 off	
			$reference        = "order"; // used when saving and pulling images or files from/to the database
			$actions_page     = "actions_orders.php"; // Actions page for processing forms
			$check_return     = "mgr.php?nav=" . $_GET['nav'] . "&item_id=" . $_GET['item_id'] . "&message=check_approved&order_by=" . $_GET['order_by'] . "&order_type=" . $_GET['order_type'] . "&search=" . $_GET['search'];
			
		
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
	var o_status;
	
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
				document.data_form.submit();
			}
			else {
				false
			}
		}
	}
	
	function approve_track() {
			var agree=confirm("Save your changes?");
			if (agree) {
				document.track.action = "<? echo $actions_page; ?>?pmode=track_save";
				document.track.submit();
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
						<td><b>Search For Order:<br>
							<input type="textbox" name="search" style="font-size: 11; width: 150;"><br><font size="1">Enter order or item id.
						</td>
						<td valign="bottom" style="padding-bottom: 12px;"><input type="submit" value="Search"></td>
					</tr>
					</form>
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
							$order_by = "added";
							$order_type = "desc";
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
						
						if(!isset($_GET['status'])){
							$status = 1;
						} else {
							$status = $_GET['status'];
						}
						
						if(!empty($search)){
							$visitor_result = mysql_query("SELECT * FROM visitors where status = $status and order_num like '%$search%' or visitor_id like '%$search%' or paypal_email like '%$search%' and hide = '0' order by $order_by " . $order_type, $db);
						} else {
							$visitor_result = mysql_query("SELECT * FROM visitors where status = $status and hide = '0' order by $order_by " . $order_type, $db);
						}
						
						$visitor_rows = mysql_num_rows($visitor_result);
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
									<td style="padding-left: 8px;"><font style="font-size: 13; color: #ffffff"><b>Orders</b> - <a href="mgr.php?nav=<?php echo $_GET['nav']; ?>&status=1"><font color="#FFFFFF">View Completed Orders</font></a>  | <a href="mgr.php?nav=<?php echo $_GET['nav']; ?>&status=0"><font color="#FFFFFF">View Uncompleted Orders</font></a></td>
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
						<td colspan="2" bgcolor="<? if($order_by == "paypal_email"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" width="100%" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<? echo $nav; ?>&order_by=paypal_email&order_type=<? if($order_by == "paypal_email"){ echo $order_type_next; }  ?>&search=<? echo $search; ?>" class="title_links">EMAIL/ORDER ID</a></b></font>								
						</td>
						<td bgcolor="<? if($order_by == "paypal_email"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" width="100%" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<? echo $nav; ?>&order_by=paypal_method&order_type=<? if($order_by == "paypal_method"){ echo $order_type_next; }  ?>&search=<? echo $search; ?>" class="title_links">PAYMENT METHOD</a></b></font>								
						</td>
						<td bgcolor="<? if($order_by == "email"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" width="100%" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<? echo $nav; ?>&order_by=visitor_id&order_type=<? if($order_by == "visitor_id"){ echo $order_type_next; }  ?>&search=<? echo $search; ?>" class="title_links">VISITOR ID</a></b></font>								
						</td>
						<td bgcolor="<? if($order_by == "email"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" width="100%" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<? echo $nav; ?>&order_by=added&order_type=<? if($order_by == "added"){ echo $order_type_next; }  ?>&search=<? echo $search; ?>" class="title_links">ORDER DATE</a></b></font>								
						</td>
						<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;&nbsp;&nbsp;</b></font>								
						</td>
					</tr>
					<?
						while($visitor = mysql_fetch_object($visitor_result)){
						
							if($templine < $perpage and $line < $visitor_rows) {
					
								if($line == $recordnum) {
									$line++;
									$templine++;
							
							$emp_name = $visitor->name;
							
							$added = round(substr($visitor->added, 4, 2)) . "/" . round(substr($visitor->added, 6, 2));
							
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
							<td align="center" class="listing_id" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $visitor->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>'">
								<? echo $visitor->id; ?>
							</td>
							<?
								}
							?>
							<td align="left" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $visitor->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>'"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $visitor->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>" class="list_links"><img src="images/mgr_icon_news2.gif" border="0"></a></td>
							<td align="left" width="100%" class="listing" style="padding-left: 10;" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $visitor->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>'">
								<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $visitor->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>" class="list_links"><? echo $visitor->paypal_email . " (" . $visitor->order_num . ")"; ?></a><br>
							</td>
								<td align="left" width="100%" style="padding-left: 6px;padding-right: 6px;" nowrap class="listing" style="padding-left: 10;" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $visitor->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>'">
								<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $visitor->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>" class="list_links"><? echo $visitor->payment_method; ?></i>
							</td>
							<td align="left" width="100%" style="padding-left: 6px;padding-right: 6px;" nowrap class="listing" style="padding-left: 10;" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $visitor->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>'">
								<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $visitor->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>" class="list_links"><? echo $visitor->visitor_id; ?></i>
							</td>
							<td align="center" width="100%" style="padding-left: 6px;padding-right: 6px;" nowrap class="listing" style="padding-left: 10;" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $visitor->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>'">
								<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $visitor->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&search=<? echo $search; ?>" class="list_links"><? echo $added; ?></i>
							</td>
							<td align="center" class="listing">
								<input name="<? echo $visitor->id; ?>" type="checkbox" value="1">
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
						if($visitor_rows > 0){
						$result_pages = ceil($visitor_rows/$perpage);
						
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
											Results: <b><? echo $visitor_rows; ?> Orders</b> (<? echo $result_pages; ?> Pages)
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
										
											if(($startat + $perpage) >= $visitor_rows){
										?>
											<font color="#B0B0B0">Next</font>
										<?
											}
											else{
												if($line < $visitor_rows) {
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
						if($visitor_rows == 0){
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
										<td></td>
										<td align="right"><? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:delete_data();\">"; } ?><img src="images/mgr_button_delete_sel.gif" border="0"></a></td>
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
				</table>			
			</td>
			</form>
				<tr>
							<td colspan="<? echo $colspan; ?>" height="4"></td>
						</tr>
						<tr>
							<td colspan="<? echo $colspan; ?>" align="right">
								<table width="100%">
									<form name="track" method="post">
									<input type="hidden" name="return" value="<? echo $check_return; ?>">
									<tr>
										<td bgcolor="#5E85CA" class="data_box">
											--Assign Tracking Number--<br>
											<select name="tracking" style="font-size: 11; width: 350; border: 1px solid #000000;">
														<option value="0">Select Order</option>
														<?
															$track_result = mysql_query("SELECT * FROM visitors where status = '1' and hide = '0' order by id desc", $db);
															$track_rows = mysql_num_rows($track_result);
															while($track = mysql_fetch_object($track_result)){
																
														?>
															<option value="<? echo $track->id; ?>"><? echo $track->paypal_email . " (" . $track->order_num . ")"; ?></option>
														<?
															}
														?>
													</select>
													<tr>
														<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;">
														<b>UPS Tracking Number:</b><br>
					    							<input type="text" name="ups" style="font-size: 10; font-weight: bold; width: 300; border: 1px solid #000000;" maxlength="300"><br>
					    							<b>FedEx Tracking Number:</b><br>
					    							<input type="text" name="fedex" style="font-size: 10; font-weight: bold; width: 300; border: 1px solid #000000;" maxlength="300"><br>
					    							<b>DHL Tracking Number:</b><br>
					    							<input type="text" name="dhl" style="font-size: 10; font-weight: bold; width: 300; border: 1px solid #000000;" maxlength="300"><br>
					    							<b>Postal Tracking Number:</b><br>
					    							<input type="text" name="track" style="font-size: 10; font-weight: bold; width: 300; border: 1px solid #000000;" maxlength="300"><br>
					    							</td>
											</tr>
											<tr>
											 <td align="right">
													<a href="javascript:approve_track();"><img src="images/mgr_button_save.gif" border="0"></a>										
											</td>
									</tr>
								</table>
								</form>
							</td>
						</tr>
						<tr>
							<td colspan="<? echo $colspan; ?>" height="4"></td>
						</tr>
				
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
							$visitor_result = mysql_query("SELECT * FROM visitors where id = '$item_id'", $db);
							$visitor = mysql_fetch_object($visitor_result);
							
							$member_info_results = mysql_query("SELECT * FROM members where id = '$visitor->member_id'", $db);
							$member_info = mysql_fetch_object($member_info_results);
							
							$coupon_result = mysql_query("SELECT * FROM coupon where id = '$visitor->coupon_id'", $db);
							$coupon_rows = mysql_num_rows($coupon_result);
							$coupon = mysql_fetch_object($coupon_result);
							$coupon_type = $coupon->type;
			
			if($coupon_type == "1"){
				$type_name = "Free Shipping";
			}
			if($coupon_type == "2"){
				$type_name = "Percent Off";
			}
			if($coupon_type == "3"){
				$type_name = "Dollar Amount Off";
			}
			if($coupon_type == "4"){
				$type_name = "Number of Free Items";
			}
			if($coupon_type == "5"){
				$type_name = "Tax Exempt";
			}
			
							$added = round(substr($visitor->added, 4, 2)) . "/" . round(substr($visitor->added, 6, 2)) . "/" . round(substr($visitor->added, 0, 4));
							
							$date = date("F j, Y", strtotime($added));
							
							echo "<form name=\"data_form\" action=\"" . $actions_page . "?pmode=save_edit\" method=\"post\" ENCTYPE=\"multipart/form-data\">";
							echo "<input type=\"hidden\" value=\"" . $visitor->id . "\" name=\"item_id\">";
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
									<td nowrap><font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;<? if($item_id == "new"){ ?>ADD A NEW LISTING<? } else { ?>VIEW THIS LISTING<? } ?></b></font></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td bgcolor="#577EC4" align="center" style="padding-top: 10px; padding-bottom: 10px;" background="images/mgr_bg_texture.gif">
							<table width="80%" cellpadding="0" cellspacing="0">
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<table width="100%" cellpadding="0" cellspacing="0">
											<tr>
												<td colspan="2"><font face="arial" color="#ffffff" style="font-size: 11;">If this order requires any type of shipping information you will find it in the PayPal or 2checkout.com email that was sent when the order was made.<br><br></td>
											</tr>
									<? if($_GET['message'] == "check_approved"){ ?>
									<tr>
									<td valign="middle">
										<table>
											<tr>
												<td>&nbsp;</td>
												<td valign="middle"><img src="images/mgr_check4_loop_3.gif" valign="absmiddle"></td>
												<td valign="middle"><font color="#FFE400" style="font-size: 10;">The check/money order approval status was updated. Email message was sent to the customer.</td>
											</tr>
										</table>
									</td>
								</tr>
									<? } ?>
											<tr>
												<font face="arial" color="#ffffff" style="font-size: 11;">
												</tr>
												<tr>
														<td><b>Order Date:</b></td><td width="300"><? echo $date; ?></td>
												</tr>
													<tr>
														<td width="150"><b>Email:</b></td><td width="300"><?PHP if($member_info->email != ""){ ?><a href="mailto:<? echo $member_info->email; ?>" class="title_links"><? echo $member_info->email; ?></a><?PHP } else { ?><a href="mailto:<? echo $visitor->paypal_email; ?>" class="title_links"><? echo $visitor->paypal_email; ?></a><?PHP } ?></td>
												</tr>
												<tr>
														<td width="150"><b>Name:</b></td><td width="300"><? echo $member_info->name; ?></td>
												</tr>
												<tr>
														<td width="150"><b>Phone:</b></td><td width="300"><? echo $member_info->phone; ?></td>
												</tr>
												<tr>
														<td width="150"><b>Address 1:</b></td><td width="300"><? echo $member_info->address1; ?></td>
												</tr>
												<tr>
														<td width="150"><b>Address 2:</b></td><td width="300"><? echo $member_info->address2; ?></td>
												</tr>
												<tr>
														<td width="150"><b>City:</b></td><td width="300"><? echo $member_info->city; ?></td>
												</tr>
												<tr>
														<td width="150"><b>State:</b></td><td width="300"><? echo $member_info->state; ?></td>
												</tr>
												<tr>
														<td width="150"><b>Zip:</b></td><td width="300"><? echo $member_info->zip; ?></td>
												</tr>
													<tr>
														<td valign="top" width="150"><b>Payment Method:</b></td><td><?if($visitor->done == 0){ echo "(" . $visitor->payment_method . ") Pending Approval<br><a href=\"actions_orders.php?pmode=check_approval&id=" . $visitor->visitor_id . "&return=" . $check_return . "\" class=\"title_links\"><b>Click To Approve Order</b></a><br>(Once approved you will not be able to edit<br>this will also email a link to the customer)"; } else {  echo $visitor->payment_method; } ?></td>
												</tr>
													<tr>
														<td width="150"><b>Order ID:</b></td><td width="300"><? echo $visitor->order_num; ?></td>
												</tr>
													<tr>
														<td width="150"><b>Item ID:</b></td><td width="300"><? echo $visitor->visitor_id; ?></td>
												</tr>
												<tr>
														<td colspan="2"><br><b>Customer Download link:</b><br><input type="text" value="<? echo  $setting->site_url . "/download.php?order=" . $visitor->order_num; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="400"><br>
															<a href="<? echo  $setting->site_url . "/download.php?order=" . $visitor->order_num; ?>" target="_blank" class="title_links">[Click Here]</a> to view the customers download page.<br>														
												</td>
												<tr>
														<td colspan="2"><br><b>Tracking:</b><br><a href="http://www.ups.com/WebTracking/track?loc=en_US&WT.svl=PriNav" class="title_links" target="_blank">UPS:<? echo $visitor->ups; ?></a><br><a href="http://www.fedex.com/Tracking?link=1&cntry_code=us&lid=//Track//Pack+Track+Corp" class="title_links" target="_blank">FedEx:<? echo $visitor->fedex; ?></a><br><a href="http://track.dhl-usa.com/TrackByNbr.asp?nav=TrackBynumber" class="title_links" target="_blank">DHL:<? echo $visitor->dhl; ?></a><br><a href="http://www.usps.com/shipping/trackandconfirm.htm?from=home&page=0035trackandconfirm" class="title_links" target="_blank">Postal:<? echo $visitor->track; ?></a><br>														
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<table width="100%" cellpadding="0" cellspacing="0">	
											<?
													$total = 0;
													$cart_result = mysql_query("SELECT * FROM carts where visitor_id = '$visitor->visitor_id'", $db);
													$cart_rows = mysql_num_rows($cart_result);
													while($cart = mysql_fetch_object($cart_result)){
														
														if($cart->ptype == "d"){
															$photo_result = mysql_query("SELECT * FROM uploaded_images where id = '$cart->photo_id'", $db);
															$photo_rows = mysql_num_rows($photo_result);
															$photo = mysql_fetch_object($photo_result);
														}
														
														$ppack_result = mysql_query("SELECT * FROM photo_package where id = '$photo->reference_id'", $db);
														$ppack_rows = mysql_num_rows($ppack_result);
														$ppack = mysql_fetch_object($ppack_result);
														
														$photog_result = mysql_query("SELECT * FROM photographers where id = '$ppack->photographer'", $db);
														$photog_rows = mysql_num_rows($photog_result);
														$photog = mysql_fetch_object($photog_result);
														
												?>
												<tr>
													<td align="left" valign="middle" style="border: 1px solid #ffffff; padding: 5px 0px 5px 0px;">
													<span style="padding: 5;">
														<table border="0">
															<!--
															<tr>
																<td colspan="2">Photo Owned By: <?php echo $photog->name; ?> (<?php echo $photog->id; ?>)</td>
															</tr>
															-->
															<tr>
																<?
																	if($cart->ptype == "d"){
																?>
																<? if($setting->show_watermark_thumb == 1){ ?>
																	<td><?php if(file_exists($stock_photo_path_manager . "i_" . $photo->filename)){ ?><img src="../thumb_mark.php?i=<? echo $photo->id; ?>" width="75" class="photos" border="0"><?php } else { echo "<img src=\"images/mgr_nophoto.gif\" width=\"75\" class=\"photos\" border=\"0\">"; } ?></td>
																<? } else { ?>
																  <td><?php if(file_exists($stock_photo_path_manager . "i_" . $photo->filename)){ ?><img src="../image.php?src=<? echo $photo->id; ?>" width="75" class="photos" border="0"><?php } else { echo "<img src=\"images/mgr_nophoto.gif\" width=\"75\" class=\"photos\" border=\"0\">"; } ?></td>
																<? } ?>
																	<td>
																		<?
																			echo "<b>Digital Photo Price:</b> ";						
																			if($photo->price){
																				$price = $photo->price;	
																			} else {
																				$price = $setting->default_price;	
																			}
																			
																			echo $currency->sign;
																			echo $cart->price . "<br>";
																			echo "Photo ID: <a href=\"" . $setting->site_url . "/details.php?pid=" . $photo->reference_id . "\" target=\"_blank\" class=\"title_links\">" . $photo->reference_id . "</a><br>";
																			echo  "Filename: " . $photo->filename . " | <a href=\"../download_file2.php?pid=" . $photo->id . "\"><font color=\"#ffffff\">Download Photo</font></a>";
																			echo  "<br>"
																		?>
																		<!--<font color="#A4A4A4"><a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $photo->reference_id; ?>" class="photo_links">Details</a> | <a href="public_actions.php?pmode=delete_cart&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&cid=<? echo $cart->id; ?>" class="photo_links">Remove From Cart</a>-->
																	</td>
																<?
																	} else {
																	if($cart->ptype == "s"){
																		$sizes_result = mysql_query("SELECT * FROM sizes where id = '$cart->sid'", $db);
																		$sizes_rows = mysql_num_rows($sizes_result);
																		$sizes = mysql_fetch_object($sizes_result);
																		
																		$pg1_result = mysql_query("SELECT * FROM uploaded_images where reference = 'photo_package' and id = '$cart->photo_id'", $db);
																		$pg1_rows = mysql_num_rows($pg1_result);
																		$pg1 = mysql_fetch_object($pg1_result);
																?>
																 <? if($setting->show_watermark_thumb == "1"){ ?>
																	<td><?php if(file_exists($stock_photo_path_manager . "i_" . $pg1->filename)){ ?><img src="../thumb_mark.php?i=<? echo $pg1->id; ?>" width="75" class="photos" border="0"><?php } else { echo "<img src=\"images/mgr_nophoto.gif\" width=\"75\" class=\"photos\" border=\"0\">"; } ?></td>
																<? } else { ?>
																	<td><?php if(file_exists($stock_photo_path_manager . "i_" . $pg1->filename)){ ?><img src="../image.php?src=<? echo $pg1->id; ?>" width="75" class="photos" border="0"><?php } else { echo "<img src=\"images/mgr_nophoto.gif\" width=\"75\" class=\"photos\" border=\"0\">"; } ?></td>
																<? } ?>
																	<td>
																		<table>
																			<tr>
																				<td>
																				<?
																					echo $sizes->name;
																					echo "<br><b>Price:</b> ";						
																					echo $currency->sign;
																					echo $sizes->price;
																					echo  "<br>";
																					echo "Photo ID: <a href=\"" . $setting->site_url . "/details.php?pid=" . $pg1->reference_id . "\" target=\"_blank\" class=\"title_links\">" . $pg1->reference_id . "</a><br>";
																					echo  "Filename: " . $pg1->filename . " | <a href=\"../download_file2.php?pid=" . $pg1->id . "\"><font color=\"#ffffff\">Download Photo</font></a>";
																					echo  "<br>"
																				?>
																				</td>
																				<td valign="top" align="left" style="padding-left: 10px;">
																				</td>
																				</form>
																			</tr>
																		</table>																		
																	</td>
																<?
																} else {
																		$print_result = mysql_query("SELECT * FROM prints where id = '$cart->prid'", $db);
																		$print_rows = mysql_num_rows($print_result);
																		$print = mysql_fetch_object($print_result);
																		
																		$pg_result = mysql_query("SELECT * FROM uploaded_images where reference = 'photo_package' and id = '$cart->photo_id'", $db);
																		$pg_rows = mysql_num_rows($pg_result);
																		$pg = mysql_fetch_object($pg_result);
																		
																?>
																<? if($setting->show_watermark_thumb == "1"){ ?>
																	<td><?php if(file_exists($stock_photo_path_manager . "i_" . $pg->filename)){ ?><img src="../thumb_mark.php?i=<? echo $pg->id; ?>" width="75" class="photos" border="0"><?php } else { echo "<img src=\"images/mgr_nophoto.gif\" width=\"75\" class=\"photos\" border=\"0\">"; } ?></td>
																<? } else { ?>
																	<td><?php if(file_exists($stock_photo_path_manager . "i_" . $pg->filename)){ ?><img src="../image.php?src=<? echo $pg->id; ?>" width="75" class="photos" border="0"><?php } else { echo "<img src=\"images/mgr_nophoto.gif\" width=\"75\" class=\"photos\" border=\"0\">"; } ?></td>
																<? } ?>
																	<td>
																		<table>
																			<tr>
																				<td>
																				<?
																					echo $print->name;
																					echo "<br><b>Print Price:</b> ";						
																					if($print->price){
																						$price1 = $print->price;
																						$price = $print->price * $cart->quantity;
																					} else {
																						$price1 = "5.00";
																						$price = "5.00" * $cart->quantity;
																					}
																					
																					echo $currency->sign;
																					echo $cart->price . " (each)";
																					
																					echo  "<br>";
																					echo "Photo ID: <a href=\"" . $setting->site_url . "/details.php?pid=" . $pg->reference_id . "\" target=\"_blank\" class=\"title_links\">" . $pg->reference_id . "</a><br>";
																					echo  "Filename: " . $pg->filename . " | <a href=\"../download_file2.php?pid=" . $pg->id . "\"><font color=\"#ffffff\">Download Photo</font></a>";
																					echo  "<br>";
																					if($print->bypass == 1){
																						echo "PICKUP ITEM! In store pickup required.<br>";
																					}
																					
																				?>
																				<!--<font color="#A4A4A4"><a href="details.php?gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&pid=<? echo $cart->photo_id; ?>" class="photo_links">Details</a> | <a href="public_actions.php?pmode=delete_cart&gid=<? echo $_GET['gid']; ?>&sgid=<? echo $_GET['sgid']; ?>&cid=<? echo $cart->id; ?>" class="photo_links">Remove From Cart</a>-->
																				</td>
																				<td valign="top" align="left" style="padding-left: 10px;">
																					Quantity: <?php echo $cart->quantity; ?><br>
																					<? $qtotal= $cart->price * $cart->quantity; ?>
																					Total: <? echo $currency->sign; ?><? echo dollar($qtotal); ?>
																				</td>
																				</form>
																			</tr>
																		</table>																		
																	</td>
																<?
																	}
																}
																?>
															</tr>
														</table>
													</span>
													</td>
												</tr>
												<?
													$total = $total + $price;
													}
												?>
											<? if($cart_rows > 0){ 
													$price = dollar2($visitor->price);
													$tax = dollar2($visitor->tax);
													$shipping = dollar2($visitor->shipping);
													$savings = dollar2($visitor->savings);
													$grand = dollar2($price + $tax + $shipping - $savings);
													
													if($currency->code == "JPY"){
														$price = $visitor->price;
														$tax = $visitor->tax;
														$shipping = $visitor->shipping;
														$savings = $visitor->savings;
														$grand = $total + $shipping + $tax - $savings;
														$price = round($price);
														$tax = round($tax);
														$shipping = round($shipping);
														$savings = round($savings);
														$grand = round($grand);
													}	
											?>
												<tr>
													<td align="right">
														<span>Price: <font color="#ffffff"><? echo $currency->sign; ?><? echo $price; ?></font></span><br>
														<span>Tax: <font color="#ffffff"><? echo $currency->sign; ?><? echo $tax; ?></font></span><br>
														<span>Shipping: <font color="#ffffff"><? echo $currency->sign; ?><? echo $shipping; ?></font></span><br>
														<? if($visitor->savings > 0){ ?>
														<span>Coupon Savings: <font color="#ffffff"><? echo $currency->sign; ?><? echo $savings; ?></font></span><br>
														<? } ?>
														<? if($coupon_rows > 0){ ?>
														<span><b>-Coupon Info-</b> <font color="#ffffff"><br>code: <? echo $coupon->code; ?><br>type: <? echo $type_name; ?></font></span><br>
														<? } ?>
														<span><b>Grand Total: <font color="#ffffff"><? echo $currency->sign; ?><? echo $grand; ?></font></b></span>
													</td>
												</tr>
												<? } ?>
										</table>
									</td>
								</tr>
								
								</form>
								<?php
									if($visitor->status == 0){
								?>
									<tr>
										<td>
											<form action="actions_orders.php?pmode=status" method="post">
												<input type="hidden" name="nav" value="<?php echo $_GET['nav']; ?>">
												<input type="hidden" name="id" value="<?php echo $visitor->id; ?>">
												<input type="text" name="email" size="40"><input type="submit" value="Set Order Status To Completed"></font></a>
										
											</form>
										</td>
									</tr>
								<?php
									}
								?>
								<tr>
									<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>"><img src="images/mgr_button_cancel.gif" border="0"></a></td>
								</tr>
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