<?php
	$plugin_name    = "Batch Editor";
	$plugin_version = "1.0 [7.25.06]";
	
	if($execute_nav == 1){
		$nav_order = 21; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "Batch Edit"; // name of the nav that will appear on the page
	} else {
		
		// OPTIONS
			$image_upload    = 5; // number of images that can be uploaded per galley item / 0 for no files
			$image_path      = $stock_photo_path_manager;
			
			$reference       = "photo_package"; // used when saving and pulling images or files from/to the database
			
			$editor          = 1; // 1 on | 0 off
			
			$image_caption   = 1; // 1 on | 0 off / Allow captions on images
			
			$actions_page    = "actions_batch_edit.php"; // Actions page for processing forms
		
		// GET GENERAL SETTINGS FROM THE DATABASE
			$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
			$setting = mysql_fetch_object($settings_result);
?>
<script language="javascript">
	var i_status;
	var c_status;
	var o_status;
	
	function move_data() {
		var agree=confirm("Are you sure you want to move the selected items?");
		if (agree) {
			document.listings.action = "<? echo $actions_page; ?>?pmode=move";
			document.listings.submit();
		}
		else {
			false
		}
	}
	
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
				document.listings.action = "<? echo $actions_page; ?>?pmode=save_edit";
				document.listings.submit();
			}
			else {
				false
			}
	}

var checkflag = "false";
function check(field) {
if (checkflag == "false") {
  for (i = 0; i < field.length; i++) {
  field[i].checked = true;}
  checkflag = "true";
  return "Uncheck all"; }
else {
  for (i = 0; i < field.length; i++) {
  field[i].checked = false; }
  checkflag = "false";
  return "Check all"; }
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
					if(!$gid){
						$gid = 0;
					}
			?>
			<!-- LIST COLUMN -->
			<form name="listings" action="<? echo $actions_page . "?pmode=save_new\" method=\"post\" ENCTYPE=\"multipart/form-data\""; ?> method="post">
			<input type="hidden" value="mgr.php?nav=<? echo $nav; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>" name="return">
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
							$order_by = "title";
							$order_type = "";
						}
						
						// COLSPAN DEPENDING ON ADMIN LOGIN
						$colspan="6";
						
						$pg_result = mysql_query("SELECT * FROM photo_package order by id desc", $db);
						//$pg_rows = mysql_num_rows($pg_result);
						
						$pg_rows = 0;
						
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
						
						
						
						//$pg_result = mysql_query("SELECT * FROM photo_package where gallery_id = '" . $_GET['gid'] . "' order by '$order_by' " . $order_type, $db);
						//$pg_rows = mysql_num_rows($pg_result);
					?>
					<tr>
						<td colspan="<? echo $colspan; ?>" height="4"></td>
					</tr>
					<tr>
						<td colspan="<? echo $colspan; ?>">
							<table width="100%">
								<tr>
									<td style="padding-left: 8px;"><font style="font-size: 13;"><b>Select A Category</b> &nbsp;
									<select name="gallery_id" style="font-size: 11; width: 325; border: 1px solid #000000;" onChange="location=document.listings.gallery_id.options[document.listings.gallery_id.selectedIndex].value;">
										<option value="mgr.php?nav=<? echo $_GET['nav']; ?>&gid=0">None (Photos not yet in a category)</option>
										<?
											function db_tree2($x,$level,$nest_under,$item_id){
												include("../database.php");
												global $nav, $gid; 
												$my_row = 1;
												
												$ca_result = mysql_query("SELECT * FROM photo_galleries where nest_under = '$x' order by 'title'", $db);
												$ca_rows = mysql_num_rows($ca_result);
												while($ca = mysql_fetch_object($ca_result)){
												
													echo "<option value=\"mgr.php?nav=" . $_GET['nav'] . "&gid=" . $pgm->id;
													
													if($ca->id == $gid){
														echo $ca->id . "\" selected";
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
													
													echo $ca->title . "</option>\n";
													
													db_tree2($ca->id,$level + 1,$nest_under,$item_id);
													
												$my_row ++;
												}
											}
											db_tree2(0,1,$ca->nest_under,$item_id);
										?>
									<?php
									//ADDED IN PS350 FOR AMOUNT TO SHOW PER PAGE
									if($_GET['perpage'] == ""){
										$perpage = 50;
									} else {
										$perpage = $_GET['perpage'];
									}
									echo $perpage . "=perpage<br>";
									?>
									</select>
									<b>Per Page:</b><select name="show_perpage" id="show_perpage" style="font-size: 9; width:50;" onChange="location=document.listings.show_perpage.options[document.listings.show_perpage.selectedIndex].value;" value="GO">
										<option value="mgr.php?nav=<?PHP echo $_GET['nav']; ?>&gid=<?PHP echo $_GET['gid']; ?>&perpage=5" <?PHP if($perpage == 5){ echo "selected"; }?>>5</option>
										<option value="mgr.php?nav=<?PHP echo $_GET['nav']; ?>&gid=<?PHP echo $_GET['gid']; ?>&perpage=10" <?PHP if($perpage == 10){ echo "selected"; }?>>10</option>
										<option value="mgr.php?nav=<?PHP echo $_GET['nav']; ?>&gid=<?PHP echo $_GET['gid']; ?>&perpage=20" <?PHP if($perpage == 20){ echo "selected"; }?>>20</option>
										<option value="mgr.php?nav=<?PHP echo $_GET['nav']; ?>&gid=<?PHP echo $_GET['gid']; ?>&perpage=30" <?PHP if($perpage == 30){ echo "selected"; }?>>30</option>
										<option value="mgr.php?nav=<?PHP echo $_GET['nav']; ?>&gid=<?PHP echo $_GET['gid']; ?>&perpage=40" <?PHP if($perpage == 40){ echo "selected"; }?>>40</option>
										<option value="mgr.php?nav=<?PHP echo $_GET['nav']; ?>&gid=<?PHP echo $_GET['gid']; ?>&perpage=50" <?PHP if($perpage == 50){ echo "selected"; } ?>>50</option>
										<option value="mgr.php?nav=<?PHP echo $_GET['nav']; ?>&gid=<?PHP echo $_GET['gid']; ?>&perpage=100" <?PHP if($perpage == 100){ echo "selected"; }?>>100</option>
										<option value="mgr.php?nav=<?PHP echo $_GET['nav']; ?>&gid=<?PHP echo $_GET['gid']; ?>&perpage=250" <?PHP if($perpage == 250){ echo "selected"; }?>>250</option>
										<option value="mgr.php?nav=<?PHP echo $_GET['nav']; ?>&gid=<?PHP echo $_GET['gid']; ?>&perpage=450" <?PHP if($perpage == 450){ echo "selected"; }?>>450</option>
										<option value="mgr.php?nav=<?PHP echo $_GET['nav']; ?>&gid=<?PHP echo $_GET['gid']; ?>&perpage=700" <?PHP if($perpage == 700){ echo "selected"; }?>>700</option>
										<option value="mgr.php?nav=<?PHP echo $_GET['nav']; ?>&gid=<?PHP echo $_GET['gid']; ?>&perpage=1000" <?PHP if($perpage == 1000){ echo "selected"; }?>>1000</option>
									</select>
									</td>
									
									<? if($message == "deleted"){ ?>
									<td valign="middle">
										<table>
											<tr>
												<td>&nbsp;</td>
												<td valign="middle"><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"></td>
												<td valign="middle"><font color="#FFE400" style="font-size: 10;">The items have been deleted.</td>
											</tr>
										</table>
									</td>
									<? } ?>
									<? if($message == "moved"){ ?>
									<td valign="middle">
										<table>
											<tr>
												<td>&nbsp;</td>
												<td valign="middle"><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"></td>
												<td valign="middle"><font color="#FFE400" style="font-size: 10;">The items have been moved to new category.</td>
											</tr>
										</table>
									</td>
									<? } ?>
									<? if($_GET['message'] == "main_id"){ ?>
									<td valign="middle">
										<table>
											<tr>
												<td>&nbsp;</td>
												<td valign="middle"><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"></td>
												<td valign="middle"><font color="#FFE400" style="font-size: 10;">You Can not delete a main photo when there is photos attached to it.</td>
											</tr>
										</table>
									</td>
									<? } ?>
									<? if($message == "saved"){ ?>
									<td valign="middle">
										<table>
											<tr>
												<td>&nbsp;</td>
												<td valign="middle"><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"></td>
												<td valign="middle"><font color="#FFE400" style="font-size: 10;">Saving process completed.</td>
											</tr>
										</table>
									</td>
									<? } ?>
									<td align="right"></td>
								</tr>									
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="<? echo $colspan; ?>" height="4"></td>
					</tr>
					<tr>
						<td bgcolor="<? if($order_by == "id"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;<a href="#" class="title_links">ID</a>&nbsp;&nbsp;</b></font>								
						</td>
						<td colspan="2" bgcolor="<? if($order_by == "title"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" width="100%" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="#" class="title_links">PHOTO TITLE</a></b></font>								
						</td>
						<td bgcolor="<? if($order_by == "active"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="#" class="title_links">ACTIVE</a></b></font>								
						</td>
							<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>MOVE/DELETE</b></font>								
						</td>
					</tr>
					<?
						
						//$pg_result = mysql_query("SELECT * FROM photo_galleries order by '$order_by' " . $order_type, $db);
						//$pg_rows = mysql_num_rows($pg_result);
					
						while($pg = mysql_fetch_object($pg_result)){
						
							if($gid == 0){
								$myarray = array(99999999);
							} else {
								$myarray = explode(",", $pg->other_galleries);
							}
							if($pg->gallery_id == $gid or in_array($gid,$myarray)){
								$pg_rows++;
						
							#################################### PAGING
						
							if($templine < $perpage and $line < $pg_rows) {
					
								if($line == $recordnum) {
									$line++;
									$templine++;
								
							############################################
						
								
						
									$photo_result = mysql_query("SELECT * FROM uploaded_images where reference = 'photo_package' and reference_id = '$pg->id' and original > '0' order by original", $db);
									$photo_rows = mysql_num_rows($photo_result);
									$photo = mysql_fetch_object($photo_result);
									if($photo->id > 0){
									if(file_exists($stock_photo_path_manager . $photo->filename)){
										$psize = getimagesize($stock_photo_path_manager . $photo->filename);
										$fsize = filesize($stock_photo_path_manager . $photo->filename);
									}
								}
									
									$row_color++;	
									if ($row_color%2 == 0) {
										echo "<tr bgcolor=\"#577EC4\" onmouseover=\"this.style.backgroundColor='#094493';\" onmouseout=\"this.style.backgroundColor='#577EC4'\">";
									}
									else {
										echo "<tr bgcolor=\"#5E85CA\" onmouseover=\"this.style.backgroundColor='#094493';\" onmouseout=\"this.style.backgroundColor='#5E85CA'\">";
									}
									// START OF ORIGINAL PHOTO LISTING
								
								 if($photo->id > 0){
					?>
									<td align="center" class="listing_id">
										<b>Original ID</b><br>
										<? echo $pg->id; ?><br>
									</td>
									<td align="left" class="listing"><? if($photo_rows > 0){ ?><img src="<?PHP echo $stock_photo_path_manager; ?>i_<?php echo $photo->filename; ?>" width="75" border="0"><? }else{ ?><img src="./images/no_photo.gif" border="0"><? } ?><br><? echo $psize[0] . "X" . $psize[1]; ?></td>
									<td width="100%" align="left" class="listing">
										<br />
										
										<table cellpadding="4" width="100%" style="border: 1px solid #436AAF;">
											<tr>
												<td  width="90%"><b>Filename:</b><br><?php echo $photo->filename; ?><br>
												<b>Title:</b><br><input type="text" name="title_<? echo $pg->id; ?>" width="175" value="<? echo $pg->title; ?>"><br>
											  <b>Description:</b><br><textarea name="description_<? echo $pg->id; ?>" style="height: 50; width: 250; border: 1px solid #000000;"><? echo $pg->description; ?></textarea><br>
											   <b>keywords:</b><br><textarea name="keywords_<? echo $pg->id; ?>" style="height: 50; width: 250; border: 1px solid #000000;"><? echo $pg->keywords; ?></textarea><br>
													</td>
													<td valign="center"><b>Photographer:</b><br>
													<select name="photographer_<? echo $pg->id; ?>" style="font-size: 11; width: 175; border: 1px solid #000000;">
														<option value="0">Leave Blank</option>
														<option value="999999999" <? if($pg->photographer == "999999999"){ echo "selected"; }?>><?php echo $setting->site_title; ?></option>
														<?
															$pgm_result = mysql_query("SELECT * FROM photographers order by 'name'", $db);
															$pgm_rows = mysql_num_rows($pgm_result);
															while($pgm = mysql_fetch_object($pgm_result)){
																
														?>
															<option value="<? echo $pgm->id; ?>" <? if($pg->photographer == $pgm->id){ echo "selected"; }?>><? echo $pgm->name; ?></option>
														<?
															}
														?>
													</select><br>
															<font face="arial" color="#ffffff" style="font-size: 11;">								
															<input type="checkbox" name="featured_<? echo $pg->id; ?>" value="1" <?php if($pg->featured){ echo "checked"; } ?>><b>: Featured</b><br>
															<input type="checkbox" name="<? echo "b_" . $pg->id; ?>" value="1" <? if($pg->act_download == 1){ echo "checked"; }?>><b>: Sell Digital Version</b><br>						
															<input type="checkbox" name="<? echo "a_" . $pg->id; ?>" value="1" <?php if($pg->all_prints){ echo "checked"; } ?>><b>: Allow ALL Prints</b><br>
															<input type="checkbox" name="sizes_<? echo $pg->id; ?>" value="1" <?php if($pg->all_sizes){ echo "checked"; } ?>><b>: Allow ALL Sizes</b><br>
															<b>Price</b><br>
															<input type="text" name="price_<? echo $pg->id; ?>" value="<? echo $photo->price; ?>"><br>
															<b>Quality Description</b><br>
															<input type="text" name="quality_<? echo $pg->id; ?>" value="<? echo $photo->quality; ?>">
															<b>Quality Order</b><br>
															<input type="text" name="quality_order_<? echo $pg->id; ?>" value="<? echo $photo->quality_order; ?>">
												</td>
						
											</tr>
										</table>
									</td>
									<td align="center" class="listing">
											 <input type="checkbox" name="<? echo "c_" . $pg->id; ?>" value="1" <? if($pg->active == 1){ echo "checked"; } ?>> 
									</td>
									  <td align="center" class="listing">
									  <b>MOVE/DELETE</b><br>
										<input name="<? echo $pg->id; ?>" type="checkbox" value="1">
										<input type="hidden" name="<? echo $pg->id . "_main_id"; ?>" value="1">
										<input type="hidden" name="main_id" value="1">
									</td>
								</tr>					
					<?
					 // START OF OTHER SIZE IMAGE lISTING
					 
							while($photo1 = mysql_fetch_object($photo_result)){
									$photo_size_result = mysql_query("SELECT * FROM uploaded_images where id = '$photo1->id' order by original", $db);
									$photo_size = mysql_fetch_object($photo_size_result);
			
									if(file_exists($stock_photo_path_manager . $photo_size->filename)){
										$psize1 = getimagesize($stock_photo_path_manager . $photo_size->filename);
										$fsize1 = filesize($stock_photo_path_manager . $photo_size->filename);
									}
	
							$row_color++;	
									if ($row_color%2 == 0) {
										echo "<tr bgcolor=\"#4ba55b\" onmouseover=\"this.style.backgroundColor='#5d8765';\" onmouseout=\"this.style.backgroundColor='#4ba55b'\">";
									}
									else {
										echo "<tr bgcolor=\"#4ba55b\" onmouseover=\"this.style.backgroundColor='#5d8765';\" onmouseout=\"this.style.backgroundColor='#4ba55b'\">";
									}
					?>
									<td align="center" class="listing_id">
										<b>Sub ID</b><br>
										<? echo $photo1->id; ?>
									</td>
									<td align="left" class="listing"><? if($photo_rows > 0){ ?><img src="<?PHP echo $stock_photo_path_manager; ?>i_<?php echo $photo->filename; ?>" width="75" border="0"><? }else{ ?><img src="./images/no_photo.gif" border="0"><? } ?><br><? echo $psize1[0] . "X" . $psize1[1]; ?></td>
									<td width="100%" align="left" class="listing">
										<br />
										
										<table cellpadding="4" width="100%" style="border: 1px solid #436AAF;">
											<tr>
												<td  width="90%">
															<b>Price</b><br>
															<input type="text" name="price_<? echo $photo1->id; ?>" value="<? echo $photo1->price; ?>"><br>
															<b>Quality Description</b><br>
															<input type="text" name="quality_<? echo $photo1->id; ?>" value="<? echo $photo1->quality; ?>"><br>
															<b>Quality Order</b><br>
															<input type="text" name="quality_order_<? echo $photo1->id; ?>" value="<? echo $photo1->quality_order; ?>">
												</td>
											</tr>
										</table>
									</td>
									<td>
									</td>	
									  <td align="center" class="listing">
									  	<b>DELETE</b><br>
									  	<input name="<? echo $photo1->id; ?>" type="checkbox" value="1">
									   	<input type="hidden" name="sub_id" value="1">
									  	<input type="hidden" name="<? echo $photo1->id . "_sub_id"; ?>" value="1">
									</td>
								</tr>
						<?
				}
		 }
	}
	if($photo->id <= 0){
	 echo "<tr><td colspan=\"5\" align=\"center\"><b>---Image " . $pg->id . " can not be edited because it was uploaded in an older version of photostore---</b></td></tr>";
	}
							unset($myarray);
							
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
					if($pg_rows > 0){
						$result_pages = ceil($pg_rows/$perpage);
						
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
											Results: <b><? echo $pg_rows; ?> Photos</b> (<? echo $result_pages; ?> Pages)
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
										
											if(($startat + $perpage) >= $pg_rows){
										?>
											<font color="#B0B0B0">Next</font>
										<?
											}
											else{
												if($line < $package_rows) {
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
									<tr>
										<td height="8"></td>
									</tr>
									<tr>
								<td width="550" style="padding-left: 8px;"><font style="font-size: 13;"><b>Select a category to move selected images to</b><br><b>-OR-</b><br><input type="checkbox" name="move3" value="1"><b>Move All</b> (Check this to move all images above to the category selected)<br>
									<select name="gallery_id_to" style="font-size: 11; width: 200; border: 1px solid #000000;">
										<option value="0">None (Do Not Move)</option>
										<?
											function db_tree24($x,$level,$nest_under,$item_id){
												include("../database.php");
												global $nav, $gid; 
												$my_row = 1;
												
												$ca_result1 = mysql_query("SELECT * FROM photo_galleries where nest_under = '$x' order by 'title'", $db);
												$ca_rows1 = mysql_num_rows($ca_result1);
												while($ca1 = mysql_fetch_object($ca_result1)){
													
													if($ca1->id == $gid){
														echo "<option value=\"" . $ca1->id . "\" selected";
													} else {
														echo "<option value=\"" . $ca1->id . "\"";
													}
													
													if($nest_under == $ca1->id){
														echo " selected";
													}
													
													echo ">";
															
													for($z = 1; $z < $level; $z++){
														echo "&nbsp;&nbsp;&nbsp;&nbsp;";
													}
													
													echo $ca1->title . "</option>\n";
													
													db_tree24($ca1->id,$level + 1,$nest_under,$item_id);
													
												$my_row ++;
												}
											}
											db_tree24(0,1,$ca1->nest_under,$item_id);
										?>
									</select>
									<br>
									<a href="javascript:move_data();"><img src="images/mgr_button_move.gif" border="0"></a>
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
						if($pg_rows == 0){
					?>
						<tr>
							<td colspan="<? echo $colspan; ?>" bgcolor="#577EC4" align="center" valign="middle" height="60">
								<table>
									<tr>
										<td align="right" valign="bottom">
											<img src="images/mgr_check3_static.gif" valign="absmiddle">
										</td>
										<td align="right" valign="middle" nowrap>
											<font color="#FFE400" style="font-size: 10;">&nbsp;There are no listings in this category
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
										<td style="padding: 8px;"><!--<? if($access_type != "demo"){ ?><font style="font-size: 11;">(<a href="http://www.ktools.net/stock_photo_extras/stock_photo_packages.php" target="_blank"><font color="#ffffff">Download Photos From Ktools.net</font></a>)<? } ?>--></td>
										<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=new&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>"><img src="images/mgr_button_new.gif" border="0"></a></td>
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
										<td style="padding: 0px 0px 5px 30px;">
										<b>Batch Edit Options:</b><br>(This section is for all main photos above, and NOT for the sub photos. If you wish to edit them individually then leave this section empty and edit them above. Make all changes and hit save. If you wish to make a global change to all photos above then enter a value below and hit save. To edit more photos in this category click on a the next page above.)<br>(You CAN NOT use these characters in the title, keywords, or description: /"\|][;:)(*^%$#@<>)<br><br>
															<b>Title:</b>(All main photos above will take this title)<br>
															<input type="text" name="title3" width="175" value=""><br>
											  			<b>Description:</b>(All main photos above will take this description)<br>
											  			<textarea name="description3" style="height: 50; width: 250; border: 1px solid #000000;"></textarea><br>
											   			<b>keywords:</b>(All main photos above will take this keywords, separate by comma ",")<br>
											   			<textarea name="keywords3" style="height: 50; width: 250; border: 1px solid #000000;"></textarea><br>
															<b>Photographer:</b>(All main photos will be assigned to the selected photographer)<br>
															<select name="photographer3" style="font-size: 11; width: 250; border: 1px solid #000000;">
															<option value="">Select to change</option>
															<option value="0">leave blank (no photog)</option>
															<option value="999999999" <? if($pg->photographer == "999999999"){ echo "selected"; }?>><?php echo $setting->site_title; ?></option>
														<?
															$pgm1_result = mysql_query("SELECT * FROM photographers order by 'name'", $db);
															$pgm1_rows = mysql_num_rows($pgm1_result);
															while($pgm1 = mysql_fetch_object($pgm1_result)){
																
														?>
															<option value="<? echo $pgm1->id; ?>" <? if($pg->photographer == $pgm1->id){ echo "selected"; }?>><? echo $pgm1->name; ?></option>
														<?
															}
														?>
															</select><br>
															<b>Featured</b>(Checking this will set all main photos above to show on the featured photos section)<br>
															<input type="checkbox" name="featured3" value="1"><br>
															<b>Sell Digital Version:</b>(Checking this will set all main photos above to sell digital version)<br>
															<input type="checkbox" name="act_download3" value="1">
															<font face="arial" color="#ffffff" style="font-size: 11;"><br>								
															<b>Allow ALL Prints</b>(Checking this will set all main photos above to sell all prints created)<br>
															<input type="checkbox" name="all_prints3" value="1"><br>
															<b>Allow ALL Sizes</b>(Checking this will set all main photos above to sell all sizes created)<br>
															<input type="checkbox" name="sizes3" value="1"><br>
															<b>Active</b>(Checking this will set all main photos above to active)<br>
															<input type="checkbox" name="active3" value="1"><br>
															<b>Price</b>(Setting this to a value will assign this value to all main photos above, Do not include "$")<br>
															<input type="text" name="price3" value=""><br>
															<b>Quality Description</b>(All main photos above will get this quality description if you enter one)<br>
															<input type="text" name="quality3" value=""><br>
															<b>Quality Order</b>(All main photos above will get this value if you enter one)<br>
															<input type="text" name="quality_order3" value="">
										</td>
									<tr>
											<td style="padding: 0px 0px 5px 30px;">
       								<? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:delete_data();\">"; } ?><img src="images/mgr_button_delete_sel.gif" border="0"></a>&nbsp;<a href="javascript:save_data();"><img src="images/mgr_button_save.gif" border="0"></a>
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
								<tr>
									<td valign="top"><img src="images/mgr_check5_ins.gif"></td>
									<td><font face="arial" color="#ffffff" style="font-size: 11;">To check or uncheck all use the <b>check all</b> buttons.</td>
								</tr>
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								<tr>
									<td valign="top"><img src="images/mgr_check5_ins.gif"></td>
									<td><font face="arial" color="#ffffff" style="font-size: 11;">To move photos to another category check the listing checkbox and select a category at the bottom of page you want to move the photos to and hit the <b>move</b> button.</td>
								</tr>
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								<tr>
									<td valign="top"><img src="images/mgr_check5_ins.gif"></td>
									<td><font face="arial" color="#ffffff" style="font-size: 11;">To delete listings items check the box of the items you would like to delete and hit the <b>"Delete"</b> button.</td>
								</tr>
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								<tr>
									<td valign="top"><img src="images/mgr_check5_ins.gif"></td>
									<td><font face="arial" color="#ffffff" style="font-size: 11;">To edit items make all the changes you would like and hit the <b>save</b> button.</td>
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