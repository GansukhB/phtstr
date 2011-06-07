<?php
	$plugin_name    = "Photos Manager";
	$plugin_version = "1.0 [8.25.04]";
	
	
	if($execute_nav == 1){
		$nav_order = 10; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "Photos"; // name of the nav that will appear on the page
	}
	else {
		
		// OPTIONS
			$image_upload    = 5; // number of images that can be uploaded per galley item / 0 for no files
			$image_path      = $stock_photo_path_manager;
			
			$reference       = "photo_package"; // used when saving and pulling images or files from/to the database
			
			$editor          = 1; // 1 on | 0 off
			
			$image_caption   = 1; // 1 on | 0 off / Allow captions on images
			
			$actions_page    = "actions_photo_package.php"; // Actions page for processing forms
		
		// GET GENERAL SETTINGS FROM THE DATABASE
			$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
			$setting = mysql_fetch_object($settings_result);
			
			$item_id = $_GET['item_id'];
			$nav = $_GET['nav'];
		if(!$_GET['gid']){
			$gid = "0";
		} else {
			$gid = $_GET['gid'];
		}
			$order_type = $_GET['order_type'];
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
		
			var agree=confirm("Save your changes?");
			if (agree) {
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
		<form action="<?php echo $actions_page; ?>?pmode=search" method="post">
					<tr>
						<td><b>Search for photo:</b> (enter photo id)<br>
							<input type="hidden" name="return" value="mgr.php?nav=<?php echo $nav; ?>">
							<input type="textbox" name="search" style="font-size: 11; width: 150;"><input type="submit" value="Search"><br>
					</form>
					</td>
				</tr>
		<tr>
			<?
				if(!$item_id){
			?>
			<!-- LIST COLUMN -->
			<form name="listings" method="post">
			<input type="hidden" value="mgr.php?nav=<? echo $nav; ?>&message=deleted&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>" name="return">
			<input type="hidden" value="<? echo $file_path; ?>" name="file_path">
			<input type="hidden" value="<? echo $image_path; ?>" name="image_path">
			<input type="hidden" value="<? echo $reference; ?>" name="reference">
			<td width="100%" valign="top">
				<table cellpadding="0" cellspacing="0" bgcolor="#577EC4" width="100%" style="border: 1px solid #5B8BD8;" background="images/mgr_bg_texture.gif">
					<?
						$order_type = $_GET['order_type'];
						$order_by = $_GET['order_by'];
						
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
						if($_SESSION['access_type'] == "admin"){
							$colspan="6";
						}
						else{
							$colspan="5";
						}
						
						
						$perpage = 40;
						if($_GET['page_num']){												
							$page_num = $_GET['page_num'];
						} else {
							$page_num = 1;
						}
						# CALCULATE THE STARTING RECORD						
						$startrecord = ($page_num == 1) ? 0 : (($page_num - 1) * $perpage);	
						$photocount=1;
						
					if($gid == 0){
						$pg_rows = mysql_result(mysql_query("SELECT COUNT(id) FROM photo_package where active = '1' and (gallery_id = '$gid' or gallery_id = '')"),0);
						$pg_result = mysql_query("SELECT * FROM photo_package WHERE active = '1' and (gallery_id = '$gid' or gallery_id = '') order by $order_by " . $order_type . "  LIMIT $startrecord,$perpage", $db);
					} else {
						$pg_rows = mysql_result(mysql_query("SELECT COUNT(id) FROM photo_package where active = '1' and (gallery_id = '$gid' or other_galleries LIKE '%,$gid,%')"),0);
						$pg_result = mysql_query("SELECT * FROM photo_package WHERE active = '1' and (gallery_id = '$gid' or other_galleries LIKE '%,$gid,%') order by $order_by " . $order_type . "  LIMIT $startrecord,$perpage", $db);
					}
						
						$pages = ceil($pg_rows/$perpage);
						
						//$pg_result = mysql_query("SELECT * FROM photo_package order by $order_by " . $order_type, $db);
						//$pg_rows = mysql_num_rows($pg_result);
						//$pg_rows = 0;

					?>
					<tr>
						<td colspan="<? echo $colspan; ?>" height="4"></td>
					</tr>
					<tr>
						<td colspan="<? echo $colspan; ?>">
							<table width="100%">
								<tr>
									<td style="padding-left: 8px;" colspan="<?PHP echo $colspan; ?>"><font style="font-size: 13;"><b>Select A Category</b><br>
									<select name="gallery_id" style="font-size: 11; width: 400; border: 1px solid #000000;" onChange="location=document.listings.gallery_id.options[document.listings.gallery_id.selectedIndex].value;">
										<option value="mgr.php?nav=<? echo $_GET['nav']; ?>&gid=0">None (Photos not yet in a category)</option>
										<?
											function db_tree2($x,$level,$nest_under,$item_id){
												include("../database.php");
												global $nav, $gid; 
												$my_row = 1;
												
												$ca_result = mysql_query("SELECT * FROM photo_galleries where nest_under = '$x' order by title", $db);
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
									</select>
									</td>
									</tr>
										<tr>
											<td>
											</td>	
									<? if($message == "deleted"){ ?>
									<td valign="middle">
										<table>
											<tr>
												<td>&nbsp;</td>
												<td valign="middle"><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"></td>
											<td valign="middle"><font color="#FFE400" style="font-size: 10;">The selected item(s) have been deleted.<?PHP if($setting->show_tree == 1){ ?><br>It is recommended that you rebuild your site menu<br><a href="menu_creator.php" target="_blank"><font color="FFFFF">[Click Here To Rebuild Menu]</font></a><?PHP } ?></td>
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
												<td valign="middle"><font color="#FFE400" style="font-size: 10;">Your new listing has been added.<?PHP if($setting->show_tree == 1){ ?><br>It is recommended that you rebuild your site menu<br><a href="menu_creator.php" target="_blank"><font color="FFFFF">[Click Here To Rebuild Menu]</font></a><?PHP } ?></td>
											</tr>
										</table>
									</td>
									<? } ?>
									<?
										if($pg_rows >= 5){
									?>
									<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=new&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>"><img src="images/mgr_button_new.gif" border="0"></a>&nbsp;<a href="javascript:selectAll(document.listings,0);"><img src="images/mgr_button_select_all.gif" border="0"></a>&nbsp;<? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:delete_data();\">"; } ?><img src="images/mgr_button_delete_sel.gif" border="0"></a></td>
									<?
										}
										else if($pg_rows == 0){
									?>
									<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=new&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>"><img src="images/mgr_button_new.gif" border="0"></a></td>
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
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;<a href="mgr.php?nav=<? echo $nav; ?>&order_by=id&order_type=<? if($order_by == "id"){ echo $order_type_next; }  ?>&gid=<? echo $_GET['gid']; ?>" class="title_links">ID</a>&nbsp;&nbsp;</b></font>								
						</td>
						<?
							}
						?>
						<td colspan="2" bgcolor="<? if($order_by == "title"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" width="100%" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<? echo $nav; ?>&order_by=title&order_type=<? if($order_by == "title"){ echo $order_type_next; }  ?>&gid=<? echo $_GET['gid']; ?>" class="title_links">PHOTO TITLE</a></b></font>								
						</td>
						<td bgcolor="<? if($order_by == "active"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<? echo $nav; ?>&order_by=active&order_type=<? if($order_by == "active"){ echo $order_type_next; }  ?>&gid=<? echo $_GET['gid']; ?>" class="title_links">ACTIVE</a></b></font>								
						</td>
						<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;EDIT&nbsp;&nbsp;</b></font>								
						</td>
						<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;&nbsp;&nbsp;</b></font>								
						</td>
					</tr>
					<?
						
						//$pg_result = mysql_query("SELECT * FROM photo_galleries order by $order_by " . $order_type, $db);
						//$pg_rows = mysql_num_rows($pg_result);
						while($pg = mysql_fetch_object($pg_result)){

						$photo_result = mysql_query("SELECT * FROM uploaded_images where reference = 'photo_package' and reference_id = '$pg->id' order by original LIMIT 1", $db);
						$photo_rows = mysql_num_rows($photo_result);
						$photo = mysql_fetch_object($photo_result);
						if($photo->original > 0){
						if(file_exists($stock_photo_path_manager . $photo->filename)){
							$psize = getimagesize($stock_photo_path_manager . $photo->filename);
							$fsize = filesize($stock_photo_path_manager . $photo->filename);
						}
					}

									
						if(strlen($pg->title) > 100){
							$trim_title = substr($pg->title, 0, 100) . "...";
						}
						else {
							$trim_title = $pg->title;
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
                            <td align="center" class="listing_id" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>'">
                                <? echo $pg->id; ?>
                            </td>
                            <?
                                }
                            ?>
                            <td align="left" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $staff->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>'"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>" class="list_links"><? if($photo_rows > 0){ ?><img src="<?PHP echo $stock_photo_path_manager; ?>i_<?php echo $photo->filename; ?>" width="75" border="0"><? }else{ ?><img src="./images/no_photo.gif" border="0"><? } ?></a></td>
                            <td width="750" align="left" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>'">
                                &nbsp;&nbsp;<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>" class="list_links">Title: <? echo $trim_title; ?></a><br />
                                
                                <table cellpadding="4" width="100%" style="border: 1px solid #436AAF;">
                                    <tr>
                                        <? if($photo->original > 0){ ?>
                                        <td>&nbsp;</td>
                                        <td width="40%"><b>Filename:</b><br><?php echo $photo->filename; ?><!-- | Added To Cart: <?php echo $photo->cart_count; ?>--></td>
                                        <td width="20%"><b>Views:</b><br><?php echo $pg->code; ?><!-- | Added To Cart: <?php echo $photo->cart_count; ?>--></td>
                                        <td width="20%"><b>Pixels:</b><br><?php echo $psize[0] . "x" . $psize[1]; ?></td>
                                        <td width="20%"><b>Filesize:</b><br><?php echo round($fsize/1048576, 2); ?>mb</td>
                                        <? } else { ?>
                                        <? echo "<td colspan=\"5\"><br><br></td>"; ?>
                                        <? } ?>
                                    </tr>
                                </table>
                            </td>
                            <td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>'">
                                <a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>" class="edit_links"><b><? if($pg->active == 1){ echo "Yes"; } else { echo "No"; } ?></a>
                            </td>
                            <td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>'">
                                <a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>" class="edit_links">[edit]</a>
                            </td>
                            <td align="center" class="listing">
                                <input name="delete[]" type="checkbox" value="<? echo $pg->id; ?>">
                            </td>
                        </tr>					
					<?
							if($photocount == $setting->dis_columns){
								//echo "<br style=\"clear: both;\" test=\"" . $photocount . "\">";
								$photocount = 1;
							} else {
								$photocount++;
							}
						
				}
				if($pg_rows > 0){

				?>
					<tr>
						<td colspan="<? echo $colspan; ?>" align="right">
							<div name="result_details" id="result_details" style="padding-left: 10px;padding-bottom: 10px;padding-right: 10px;padding-top: 30px;width: 100%; clear: both;">
								Page  
                                <select style="font-size: 11px" id="page" onChange="location.href=document.getElementById('page').options[document.getElementById('page').selectedIndex].value">
                                    <?php
                                        for($x=1;$x<=$pages;$x++){
                                            $selected = ($x == $page_num) ? "selected" : "";
                                            echo "<option value=\"mgr.php?nav=$_GET[nav]&gid=$gid&order_by=$order_by&order_type=$order_type&page_num=$x\" $selected>$x</option>";
                                        }
                                    ?>										
                                </select>
                                 of <?php echo $pages; ?> (<strong><?php echo $pg_rows; ?></strong> items total)
                                 <strong> | </strong>
                                 <span style="font-weight: bold; color: #CCCCCC">
                                <?php
                                    if($page_num > 1){
                                ?>
                                        <a href="<?php echo "mgr.php?nav=$_GET[nav]&gid=$gid&order_by=$order_by&order_type=$order_type&page_num=" . ($page_num-1); ?>"><font color="#ffffff">Previous</font></a>
                                <?php
                                    } else {
                                        echo "Previous"; 
                                    }
                                    echo " : ";
                                    if($page_num != $pages){
                                ?>
                                		<a href="<?php echo "mgr.php?nav=$_GET[nav]&gid=$gid&order_by=$order_by&order_type=$order_type&page_num=" . ($page_num+1); ?>"><font color="#ffffff">Next</font></a>
                                <?php
                                    } else {
                                        echo "Next";
                                    }
                                ?>
                                
                                </strong>
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
											<?php if($_GET['message'] == "no_match"){ ?>
											<font color="#FFE400" style="font-size: 10;">&nbsp;Sorry the search for <?php echo $_GET['search']; ?> didn't return any results
											<?php } else { ?>
											<font color="#FFE400" style="font-size: 10;">&nbsp;There are no listings in this category
											<?php } ?>
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
										<!--
										<td width="150">
											<select style="font-size: 11; width: 100;">
												<option>ID</option>
												<option>Title</option>
												<option>Date</option>
												<option>Active</option>
												<option>Homepage</option>
											</select>
										</td>
										-->
										<td style="padding: 8px;"><? if(!file_exists("../nobranding.php")){ ?><font style="font-size: 11;">(<font color="#ffffff">Download Photos From Ktools.net</font>)<? } ?></td>
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
										<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=new&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>"><img src="images/mgr_button_new.gif" border="0"></a>&nbsp;<a href="javascript:selectAll(document.listings,0);"><img src="images/mgr_button_select_all.gif" border="0"></a>&nbsp;<? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:delete_data();\">"; } ?><img src="images/mgr_button_delete_sel.gif" border="0"></a></td>
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
								echo "<form name=\"data_form\" action=\"" . $actions_page . "?pmode=save_new\" method=\"post\" ENCTYPE=\"multipart/form-data\">";
								echo "<input type=\"hidden\" value=\"mgr.php?nav=" . $nav . "&message=saved&order_by=" . $order_by . "&order_type=" . $order_type . "&message=added&gid=" . $_GET['gid'] . "\" name=\"return\">";
							}
							// EDIT ITEM
							else{
								$pg_result = mysql_query("SELECT * FROM photo_package where id = '$item_id' order by id desc", $db);
								$pg = mysql_fetch_object($pg_result);
								echo "<form name=\"data_form\" action=\"" . $actions_page . "?pmode=save_edit\" method=\"post\" ENCTYPE=\"multipart/form-data\">";
								echo "<input type=\"hidden\" value=\"" . $pg->id . "\" name=\"item_id\">";
								echo "<input type=\"hidden\" value=\"mgr.php?nav=" . $nav . "&message=saved&item_id=" . $item_id . "&gid=" . $_GET['gid'] . "\" name=\"return\">";
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
									<td colspan="2">
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<? if($message == "saved"){ ?><td align="left" valign="middle" nowrap><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"><font color="#FFE400" style="font-size: 10;">&nbsp;Your changes have been saved.</td><? } ?>
												<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>&gid=<? echo $_GET['gid']; ?>"><img src="images/mgr_button_cancel.gif" border="0"></a>&nbsp;<? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_data();\">"; } ?><img src="images/mgr_button_save.gif" border="0"></a></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								<tr>
									<td colspan="2">
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td align="left"><img src="images/mgr_section_header_l.gif"></td>
												<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>PHOTO DETAILS</b></td>
												<td align="right"><img src="images/mgr_section_header_r.gif"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b>Photo Title:</b><br>(You CAN NOT use these characters in the title, description, or keywords: /"\|][;:)(*^%$#@<>)<br>
													<input type="text" name="title" value="<? echo $pg->title; ?>" style="font-size: 13; font-weight: bold; width: 350; border: 1px solid #000000;"  maxlength="150">
												</td>
											</tr>
											<tr>
												<td bgcolor="#5E85CA" valign="top" class="data_box">
													<b>Main Category:</b><br>
													<select name="gallery_id" style="font-size: 11; width: 350; border: 1px solid #000000;">
														<option value="0">None</option>
																											
														<?
															if($item_id != "new"){														
																$gid = $pg->gallery_id;
															}
														
															function db_tree2($x,$level,$nest_under,$item_id){
																include("../database.php");
																global $gid;
																
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
																	
																	if($gid == $ca->id){
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
												<font face="arial" color="#ffffff" style="font-size: 11;"><b>Keywords:</b> (Optional | Separate with a comma ",".)<br>
												<textarea name="keywords" style="height: 50; width: 350; border: 1px solid #000000;"><? echo $pg->keywords; ?></textarea>
												</td>
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
												<font face="arial" color="#ffffff" style="font-size: 11;"><b>Description:</b> (Optional | Details of the images.)<br>
												<textarea name="description" style="height: 50; width: 350; border: 1px solid #000000;"><? echo $pg->description; ?></textarea>
												</td>
											</tr>
											<tr>
												<td bgcolor="#5E85CA" valign="top" class="data_box">
													<b>Other Categories:</b><br>(Optional | Also show this photo in these categories: (hold Ctrl to select more than one.)<br>
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
												<td bgcolor="#5E85CA" valign="top" class="data_box">
													<b>Photographer:</b><br>(Optional | Leave blank if not uploading for a photographer or you want subscribers to download this photo.)<br>
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
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<input type="checkbox" name="featured" value="1" <? if($pg->featured == 1 or $item_id == "new"){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Featured:</b> (Display this photo on the public website featured photo section located on the homepage.)<br>
												</td>
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<input type="checkbox" name="active" value="1" <? if($pg->active == 1 or $item_id == "new"){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Active:</b> (Display this photo on the public website.)<br>
												</td>
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<input type="checkbox" name="act_download" value="1" <? if(($pg->act_download == 1) or ($setting->allow_digital == 1 and $item_id == "new")) { echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Sell Digital Version:</b> (Allow the digital version of this file to be purchased & downloaded.)<br>
												</td>
											</tr>
										</table>
									</td>
									<td bgcolor="#5E85CA" class="data_box">
										<table cellapdding="0" cellspacing="0" width="100%">
										<?
												if($item_id != "new"){
													$photo_result = mysql_query("SELECT * FROM uploaded_images where reference = 'photo_package' and reference_id = '$pg->id' order by original LIMIT 1", $db);
													$photo_rows = mysql_num_rows($photo_result);
													$photo = mysql_fetch_object($photo_result);
									if($photo->original > 0){
									if(file_exists($stock_photo_path_manager . $photo->filename)){
										$psize = getimagesize($stock_photo_path_manager . $photo->filename);
										$fsize = filesize($stock_photo_path_manager . $photo->filename);
									}
								}				
										?>
												<tr>
													<td align="center"><? if($photo_rows > 0){ ?><a href="<?PHP echo $stock_photo_path_manager; ?><?PHP echo $photo->filename; ?>" target="_blank"><img src="<?PHP echo $stock_photo_path_manager; ?>i_<?php echo $photo->filename; ?>" border="0"></a><? } else { ?><img src="images/no_photo.gif"><? } ?><br>Click image to view larger size<br><td>
												</tr>
										<? if($photo->original > 0){ ?>
												<tr>
												<td align="left"><br><b>Original Photo Info:</b><br><b>Filename:</b><br><? echo $photo->filename; ?><br><b>Views:</b><br><?php echo $pg->code; ?><br><b>Pixels:</b><br><?php echo $psize[0] . "x" . $psize[1]; ?><br><b>Filesize:</b><br><?php echo round($fsize/1048576, 2); ?>mb<br><b>Added to Cart (for download):</b><br><?php echo $pg->cart_count; ?> Times
												</tr>
										<?
													}
												}
										?>
										</table>
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
													
														<input type="checkbox" name="all_prints" value="1" <?php if($item_id == "new" or $pg->all_prints){ echo "checked"; } ?>> <b>Allow ALL Prints & Products to be purchased with this photo:</b><br />(This will assign all prints & products to this image including all future prints & products you may create.)<br />
														<br />
														<div align="center" valign="middle" style="padding: 10px; background-color: #2F59A3; border: 1px solid #1A4188;"><font style="font-size: 16px; color:#FFFFFF;"><b>OR</b></font></div>
														<br />
														<font face="arial" color="#ffffff" style="font-size: 11;"><b>Allow only selected Prints & Products to be purchased with this photo:</b><br />(Select any combination below.)<br>
													<?php
														$myprod = explode(",",$pg->prod);
													
														$prod_result = mysql_query("SELECT * FROM prints order by porder", $db);
														$prod_rows = mysql_num_rows($prod_result);
														while($prod = mysql_fetch_object($prod_result)){
															
													?>
													<input type="checkbox" name="prod[]" value="<? echo $prod->id; ?>" <?php if($item_id == "new" or in_array($prod->id,$myprod) or $pg->all_prints){ echo "checked"; } ?>> <?php echo $prod->name; ?> | <?PHP echo $currency->sign . $prod->price; ?><br />
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
									if($photo->original > 0 or $item_id == "new"){
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
													
														<input type="checkbox" name="all_sizes" value="1" <?php if($item_id == "new" or $pg->all_sizes){ echo "checked"; } ?>> <b>Allow ALL sizes to be purchased with this photo:</b><br />(This will assign all sizes to this image including all future sizes you may create.)<br />
														<br />
														<div align="center" valign="middle" style="padding: 10px; background-color: #2F59A3; border: 1px solid #1A4188;"><font style="font-size: 16px; color:#FFFFFF;"><b>OR</b></font></div>
														<br />
														<font face="arial" color="#ffffff" style="font-size: 11;"><b>Allow only selected sizes to be purchased with this photo:</b><br />(Select any combination below.)<br>
													<?php
														$mysize = explode(",",$pg->sizes);
													
														$size_result = mysql_query("SELECT * FROM sizes order by sorder", $db);
														$size_rows = mysql_num_rows($size_result);
														while($size = mysql_fetch_object($size_result)){
													?>
														<input type="checkbox" name="size[]" value="<? echo $size->id; ?>" <?php if($item_id == "new" or in_array($size->id,$mysize) or $pg->all_sizes){ echo "checked"; $checked = 1; } else { $checked = 0; } ?>> <?php echo $size->name; ?> | <?php echo $size->size; ?>(px) | <?PHP echo $currency->sign . $size->price; ?><?PHP if($item_id != "new"){ ?>| <? if($psize[0] >= $size->size or $psize[1] >= $size->size){ echo "<b>Ok for this image</b>"; } else { ?><? if($checked == 1){ ?><b>DO NOT CHECK THIS ONE!</b> (It is too large for the photo)<? } else { ?>This is to large for the photo do not check it!<?PHP } } }?><br />
													<?
														}
													?>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<? 
								}
							}
									if($image_upload != 0){
								?>
									<tr>
										<td colspan="2" height="15"></td>
									</tr>
									<tr>
										<td colspan="2">
											<table cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<td align="left"><img src="images/mgr_section_header_l.gif"></td>
													<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>CURRENT IMAGES/PHOTOS</b></td>
													<td align="right"><img src="images/mgr_section_header_r.gif"></td>
												</tr>
											</table>
										</td>
									</tr>
								<?
									}
								?>
								<SCRIPT LANGUAGE="JavaScript">
									function copy_image_link(mylink) 
									{
										window.clipboardData.setData("Text", mylink);
										alert("A link to this image has been copied to the clipboard");
									}
								</SCRIPT> 
								<!-- START : IMAGE UPLOAD -->
								<?
								
									$absolute_image_path = str_replace("../", "/", $image_path);
									$absolute_image_path = $setting->site_url . $absolute_image_path;
								
									if($image_upload != 0){
										$image_result = mysql_query("SELECT * FROM uploaded_images where reference = '$reference' and reference_id = '$item_id' order by quality_order", $db);
										$rows = mysql_num_rows($image_result);
										
										
											if($rows != 0){
								?>
										<tr>
											<td colspan="2">
												<table width="100%" cellpadding="0" cellspacing="0">
													<tr>
								<?
											}
										$x = 1;
										while($image = mysql_fetch_object($image_result)){
											if(file_exists($image_path . $image->filename)){
												$the_size = getimagesize($image_path . $image->filename);
												$fsize = filesize($stock_photo_path_manager . $image->filename);
											}
											
											if($x == 4){
												echo "</tr><tr>";
												$x = 1;
											}
											
											if($rows > 4){
												$rowWidth = "33";
											} else {
												$rowWidth = round(100/$rows);
											}
											
								?>
											<td align="center" valign="top" width="<? echo $rowWidth; ?>%" bgcolor="#5E85CA" class="data_box">
												<table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td align="left" class="options_box"><a name="#image_area_<? echo $image->id; ?>">
															<div id="div_<? echo $image->id; ?>" style="position:relative; top:0px; left:0px;display:block;z-index:1">
															<input type="hidden" name="link_<? echo $image->id; ?>" value="../uploaded_gallery/<? echo $image->filename; ?>">
															<table width="100%" cellpadding="1" cellspacing="1">
																<tr>
																	<td>
																		<b>INFO:</b><br>
																		<?PHP if($image_caption == 1){ ?>
																		<a href="#image_area_<? echo $image->id; ?>" onClick="window.open('image_details_editor.php?id=<? echo $image->id; ?>', 'caption_win', ['HEIGHT=400', 'WIDTH=400', 'scrollbars=yes', 'dependent']);" class="edit_links">&#187; Edit Details</a><br>
																		<?PHP } ?>
																		
																		<?PHP if($copy_link_option == 1){ ?>
																		<a name="file_link"><a href="#image_link" onClick="copy_image_link('<? echo $absolute_image_path . $image->filename; ?>')" class="edit_links">&#187; Copy Link</a><br>
																		<?PHP } ?>
																		
																		<?PHP if($x > 1){ ?>
																		<?PHP if($access_type !=  "demo"){ ?><a href="mgr_actions.php?pmode=delete_image2&id=<? echo $image->id; ?>&item_id=<? echo $item_id; ?>&nav=<? echo $nav; ?>&order_by=<? echo $order_by; ?>" target="_parent" class="edit_links">&#187; Delete</a><? } ?><br>
																		<?PHP } ?>
																	</td>
																</tr>
																<tr>
																	<td nowrap>
																		<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
																		<b>Filename:</b> <a href="<?PHP echo $stock_photo_path_manager . $image->filename; ?>" target="_blank" class="edit_links"><u><?php echo $image->filename; ?></u></a><br>
																		<b>Thumbnail name:</b> <a href="<?PHP echo $stock_photo_path_manager . "i_" . $image->filename; ?>" target="_blank" class="edit_links"><u><?php echo "i_" . $image->filename; ?></u></a><br>
																		<b>Sample name:</b> <a href="<?PHP echo $stock_photo_path_manager . "s_" . $image->filename; ?>" target="_blank" class="edit_links"><u><?php echo "s_" . $image->filename; ?></u></a><br>
																		<?PHP if(file_exists($stock_photo_path_manager . "m_" . $image->filename)){ ?>
																		<b>Large name:</b> <a href="<?PHP echo $stock_photo_path_manager . "m_" . $image->filename; ?>" target="_blank" class="edit_links"><u><?php echo "m_" . $image->filename; ?></u></a><br>
																		<?PHP } else { ?>
																		<b>Large name:</b> There is no click to enlarge sample (m_ file).
																		<?PHP } ?>
																		<b>Price:</b> <?PHP if($image->price_contact == 1){ echo "Contact For Price"; } else { echo $currency->sign . $image->price; } ?><br>
																		<b>Quality:</b> <?php echo $image->quality; ?><br>
																		<b>Order:</b> <?php echo $image->quality_order; ?><br>
																		<b>Pixels:</b> <?php echo $the_size[0] . "x" . $the_size[1]; ?><br>
																		<b>Image Filesize:</b> <?php echo round($fsize/1048576, 2); ?>mb<br>
											<?php
											//Added for zip file uploading ability in version 320
											$name = strip_ext($image->filename);
											$name = $name . ".zip";
											$name_check = "../files/" . $name;
											if(is_file("../files/" . $name)){
												$file_size = filesize($name_check);
												$file_size = $file_size/1048576;
												$file_size = round($file_size,2);
											echo "<br><b>Zip File:</b> Yes&nbsp;<input type=\"checkbox\" name=\"delete_zip_" . $image->id . "\" value=\"1\"> Delete <br>";
											echo $name . "<br>";
											echo "File Size: " . $file_size . "(MB)<br>";
											} else {
											echo "<br><b>Zip File:</b> No&nbsp;";
											?>
										<br><b>Upload/Update:</b><br><input type="file" name="zip_file_<? echo $image->id; ?>" size="20" maxlength="40">
										<? 
										} //End of zip upload
										?>
										
										<?php
										//Added for movie file uploading ability in version 320
										$name1 = strip_ext($image->filename);
										$name1 = array($name1 . ".mov", $name1 . ".avi", $name1 . ".mpg", $name1 . ".flv", $name1 . ".wmv");
										foreach($name1 as $key => $value){
										if(is_file($stock_video_path_manager . $name1[$key])){
											$file_exist = 1;
											$mov_name = $name1[$key];
											$check_mov = $stock_video_path_manager . $name1[$key];
											}
										}
										if($file_exist == 1){
											$file_size1 = filesize($check_mov);
											$file_size1 = $file_size1/1048576;
											$file_size1 = round($file_size1,2);
										echo "<br><b>Movie File:</b> Yes&nbsp;<input type=\"checkbox\" name=\"delete_movie_" . $image->id . "\" value=\"1\"> Delete <br>";
										echo $mov_name . "<br>";
										echo "File Size: " . $file_size1 . "(MB)<br>";
										} else {
										echo "<br><b>Movie File:</b> No&nbsp;";
										?>
										<br><b>Upload/Update:</b><br><input type="file" name="movie_file_<? echo $image->id; ?>" size="20" maxlength="40">
										<?
										} //End of movie upload
										?>
										
										<?php
										//Added for movie sample file uploading ability in version 320
										$name2 = strip_ext($image->filename);
										$name2 = array($name2 . ".mov", $name2 . ".avi", $name2 . ".mpg", $name2 . ".flv", $name2 . ".wmv");
										foreach($name2 as $key => $value){
										if(is_file($sample_video_path_manager . $name2[$key])){
											$file_exist2 = 1;
											$sample_name = $name2[$key];
											$check_sam = $sample_video_path_manager . $name2[$key];
											}
										}
										if($file_exist2 == 1){
											$file_size2 = filesize($check_sam);
											$file_size2 = $file_size2/1048576;
											$file_size2 = round($file_size2,2);
										echo "<br><b>Sample Movie File:</b> Yes&nbsp;<input type=\"checkbox\" name=\"delete_sample_" . $image->id . "\" value=\"1\"> Delete <br>";
										echo $sample_name . "<br>";
										echo "File Size: " . $file_size2 . "(MB)<br>";
										} else {
										echo "<br><b>Sample Movie File:</b> No&nbsp;";
										?>
										<br><b>Upload/Update:</b><br><input type="file" name="sample_file_<? echo $image->id; ?>" size="20" maxlength="40">
										<? 
										} //End of movie sample upload
										?>
										<input type="hidden" name="file_name_<? echo $image->id; ?>" value="<? echo $image->filename; ?>">
																	</td>
																</tr>
															</table>
															</div>
														</td>
													</tr>
												  <tr>
												    <td height="5"></td>
												  </tr>
													<tr>
														<td align="center">
															<img src="<? echo $image_path; ?>i_<? echo $image->filename; ?>" border="0" <? if($rows >= 2){ echo "width=\"75\""; } ?> style="border: 1px solid #476DB0" name="image_<? echo $image->id; ?>"><br><a href="<?PHP echo $actions_page."?pmode=rotate&rotate=90&photo_name=$image->filename&return="; ?>mgr.php?nav=<? echo $nav; ?>&message=deleted&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>&item_id=<? echo $_GET['item_id']; ?>"><img src="images/ccw.gif" border="0" title="Rotate CCW" alt="Rotate CCW"></a>&nbsp;<a href="<?PHP echo $actions_page."?pmode=rotate&rotate=-90&photo_name=$image->filename&return="; ?>mgr.php?nav=<? echo $nav; ?>&message=deleted&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&gid=<? echo $_GET['gid']; ?>&item_id=<? echo $_GET['item_id']; ?>"><img src="images/cw.gif" border="0" title="Rotate CW" alt="Rotate CW"></a>
														</td>
													</tr>
												</table>
											</td>
																	
								<?		
										$x++;
										unset($mov_name);
										unset($sample_name);
										unset($name);
										unset($name1);
										unset($name2);
										unset($file_exist2);
										unset($file_exist);
										
										}
										if($rows > 4){
											echo "<td colspan=\"" . (5 - $x) . "\" bgcolor=\"#5E85CA\" class=\"data_box\">&nbsp;</td>";
										}
										echo "</tr>";
										if($rows != 0){
								?>
												</table>
											</td>
										</tr>
								<?
										}
									}
								
									if($rows < $image_upload and $image_upload != 0){
										if($image_area_name == ""){
											$image_area_name = "Add Image";
										}
								?>
								
								<tr>
									<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="2">
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td align="left"><img src="images/mgr_section_header_l.gif"></td>
												<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>ADD NEW PHOTO</b></td>
												<td align="right"><img src="images/mgr_section_header_r.gif"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box" colspan="2">
										<?php if($_SESSION['access_type'] != "demo"){ ?>
											<?php
                                                if(ini_get("upload_max_filesize") < 15){
                                            ?>
                                            <div style="background-color:#FFFFFF; color: #333333; padding: 4px; border: 1px solid #0a2963"><b><font color="#FF0000">* NOTICE:</font></b> Your host has a maximum upload size of <?php echo strtolower(ini_get("upload_max_filesize")); ?>b set. You will not be able to upload photos larger than <?php echo strtolower(ini_get("upload_max_filesize")); ?>b until the upload_max_filesize setting in PHP is changed. Contact your host to change this value. This setting does not apply to FTP uploads.</div>
                                            <br /><br />
                                            <?php
                                                }
                                            ?>
											<?php
												if(ini_get("max_input_time") > 0){
											?>
												<div style="background-color:#FFFFFF; color: #333333; padding: 4px; border: 1px solid #0a2963"><b><font color="#FF0000">* NOTICE:</font></b> Your host has a maximum upload time of <?php echo ini_get("max_input_time"); ?> seconds set. Uploads that take longer than <?php echo ini_get("max_input_time"); ?> seconds will be cancelled until the max_input_time setting in PHP is changed. Contact your host to change this value.</div>
											<?php
												}
												if(ini_get("memory_limit") and ini_get("memory_limit") < 65){
											?>
												<br /><br /><div style="background-color:#FFFFFF; color: #333333; padding: 4px; border: 1px solid #0a2963"><b><font color="#FF0000">* NOTICE:</font></b> Your host has a memory limit of <?php echo ini_get("memory_limit"); ?>. This may restrict you from uploading larger photos. Contact your host to change this value.</div>
											<?php
												}
											?>
										<? } ?>
										<br /><br />
										<font face="arial" color="#ffffff" style="font-size: 11;"><b><? echo $image_area_name; ?>:</b> (JPG files only.)<br>
										<input type="file" name="image" style="font-size: 11; width: 287; border: 1px solid #000000;">
										<br>
                    <font face="arial" color="#ffffff" style="font-size: 11;"><b><? echo $image_area_name; ?>:</b> (EPS files only. If vector image)<br>
										<input type="file" name="image_vector" style="font-size: 11; width: 287; border: 1px solid #000000;">
										<br>
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
										<br>
										<b>Quality:</b> (Optional | Example: High Quality or 300 DPI.)<br>
										<input type="text" name="quality" style="font-size: 11; width: 287; border: 1px solid #000000;" maxlength="200">
										<br>
										<b>Order:</b> (Optional | Order of the quality displayed on image details page.)<br>
										<input type="text" name="quality_order" style="font-size: 11; width: 287; border: 1px solid #000000;" maxlength="200">
									</td>
								</tr>
								
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
													<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>ALSO CREATE THESE OTHER VERSIONS OF THIS PHOTO</b></td>
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
								
								<? } ?>
								<!-- END : IMAGE UPLOAD -->
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								<tr>
									<td colspan="2" align="right"><a href="mgr.php?nav=<? echo $nav; ?>&gid=<? echo $_GET['gid']; ?>"><img src="images/mgr_button_cancel.gif" border="0"></a>&nbsp;<? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_data();\">"; } ?><img src="images/mgr_button_save.gif" border="0"></a></td>
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
