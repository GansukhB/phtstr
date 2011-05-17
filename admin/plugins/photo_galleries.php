<?php
	$plugin_name    = "Photo Galleries Plugin (Nesting)";
	$plugin_version = "1.1 [4.22.04]";
	
	
	if($execute_nav == 1){
		$nav_order = 10; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "Categories"; // name of the nav that will appear on the page
	}
	else {
		
		// OPTIONS
			$image_upload    = 500; // number of images that can be uploaded per galley item / 0 for no files
			$image_path      = $stock_photo_path_manager;
			
			$reference       = "stock_photos"; // used when saving and pulling images or files from/to the database
			
			$editor          = 1; // 1 on | 0 off
			
			$image_caption   = 1; // 1 on | 0 off / Allow captions on images
			
			$actions_page    = "actions_photo_galleries.php"; // Actions page for processing forms
		
		// GET GENERAL SETTINGS FROM THE DATABASE
			$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
			$setting = mysql_fetch_object($settings_result);
			
			$order_type = $_GET['order_type'];
			$nav = $_GET['nav'];
			$item_id = $_GET['item_id'];
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
				if(!$_GET['item_id']){
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
							$order_by = "galorder";
							$order_type = "";
						}
						
						// COLSPAN DEPENDING ON ADMIN LOGIN
						if($_SESSION['access_type'] == "admin"){
							$colspan="6";
						}
						else{
							$colspan="5";
						}
						
						$pg_result = mysql_query("SELECT id,galorder,pub_pri,active,title,nest_under FROM photo_galleries order by $order_by " . $order_type, $db);
						$pg_rows = mysql_num_rows($pg_result);
							
						function db_tree($x,$level,$order_by,$order_type,$access_type,$nav){
							global $db;
							
							$current_row = 1;
							
							$pg_result = mysql_query("SELECT id,galorder,pub_pri,active,title,nest_under FROM photo_galleries where nest_under = '$x' order by $order_by " . $order_type, $db);
							$pg_rows = mysql_num_rows($pg_result);
							while($pg = mysql_fetch_object($pg_result)){
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
								<td align="center" class="listing_id" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
									<? echo $pg->id; ?>
								</td>
								<?
									}
								?>
								
								<td align="center" class="listing_id" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
									<?php if($pg->pub_pri == 1){ echo "--"; } else { echo $pg->galorder; } ?>
								</td>
								
								<td width="100%">
									<table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="left" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $staff->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'"><?	for($z = 1; $z < $level; $z++){	echo "<img src=\"images/mgr_listing_spacer.gif\" border=\"0\">"; } ?><a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $staff->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" class="list_links"><?php if($pg->pub_pri == 1){ echo "<img src=\"images/mgr_cat_lock.gif\" border=\"0\">"; } else { ?><img src="images/mgr_icon_gallery2.gif" border="0"><?php } ?></a></td>
											<td width="100%" align="left" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
												&nbsp;&nbsp;<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" class="list_links"><? echo $trim_title; ?></a>
											</td>										
										</tr>
									</table>
								</td>					
								
								
								<td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
									<a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" class="edit_links"><b><? if($pg->active == 1){ echo "Yes"; } else { echo "No"; } ?></a>
								</td>
								<td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>'">
									
                  <!--
                  <a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" class="edit_links">
                  -->    
                      <?php
                        $language_dir = "../language";
                        $l_real_dir = realpath($language_dir);
                        $l_dir = opendir($l_real_dir);
                        $i = 0;
                        // LOOP THROUGH THE PLUGINS DIRECTORY
                        $lfile = array();
                        while(false !== ($file = readdir($l_dir))){
                        $lfile[] = $file;
                        }
                        //SORT THE CSS FILES IN THE ARRAY
                        sort($lfile);
                      ?>

                      
                      <?php
                        //GO THROUGH THE ARRAY AND GET FILENAMES
                        foreach($lfile as $key => $value){
                          //IF FILENAME IS . OR .. OR SLIDESHOW.CSS DO NO SHOW IN THE LIST
                          $fname = strip_ext($lfile[$key]);
                          if($fname != ".." && $fname != "." && trim($fname) != ""){
                              
                       ?>
                       <a href="mgr.php?nav=<? echo $nav; ?>&item_id=<? echo $pg->id; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>&lang=<?php echo $fname; ?>" class="edit_links">
                        [edit]<?php echo $fname ;?>
                       </a>
                       <?php       
                          }
                        }
                          
                      ?>
                  <!--
                  </a>
                  -->
								</td>
								<td align="center" class="listing">
									<input name="<? echo $pg->id; ?>" type="checkbox" value="1">
								</td>
							</tr>					
						<?
							
								db_tree($pg->id,$level + 1,$order_by,$order_type,$access_type,$nav);	
							$current_row ++;						
							}
						}						
						
					?>
					<tr>
						<td colspan="<? echo $colspan; ?>" height="4"></td>
					</tr>
					<tr>
						<td colspan="<? echo $colspan; ?>">
							<table width="100%">
								<tr>
									<td style="padding-left: 8px;">
                    
                    <font style="font-size: 13;"><b>Photo Galleries</b></font>
                    <br />
                    <!--
                    <b>
                      <a href="./menu_creator.php" class="title_links" target="_blank">
                      <img src="images/mgr_button_create.gif" border="0" alt="Update Main Site Menu">
                      </a>&nbsp;
                    </b>-->
                    
                    <?php
                        $language_dir = "../language";
                        $l_real_dir = realpath($language_dir);
                        $l_dir = opendir($l_real_dir);
                        $i = 0;
                        // LOOP THROUGH THE PLUGINS DIRECTORY
                        $lfile = array();
                        while(false !== ($file = readdir($l_dir))){
                        $lfile[] = $file;
                        }
                        //SORT THE CSS FILES IN THE ARRAY
                        sort($lfile);
                      ?>

                      
                      <?php
                        //GO THROUGH THE ARRAY AND GET FILENAMES
                        foreach($lfile as $key => $value){
                          //IF FILENAME IS . OR .. OR SLIDESHOW.CSS DO NO SHOW IN THE LIST
                          $fname = strip_ext($lfile[$key]);
                          if($fname != ".." && $fname != "." && trim($fname) != ""){
                              
                       ?>
                        <a href="./menu_creator.php?lang=<?php echo $fname; ?>" class="title_links" target="_blank">
                          Update Site Menu in <?php echo $fname; ?><br />
                        </a>
                       <?php       
                          }
                        }
                          
                      ?>
                  </td>
									
                  <? if($_GET['message'] == "deleted"){ ?>
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
									<? if($_GET['message'] == "added"){ ?>
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
									
									<td align="right"><a href="mgr.php?nav=<? echo $nav; ?>&item_id=new&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>"><img src="images/mgr_button_new.gif" border="0"></a>&nbsp;<a href="javascript:selectAll(document.listings,0);"><img src="images/mgr_button_select_all.gif" border="0"></a>&nbsp;<? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:delete_data();\">"; } ?><img src="images/mgr_button_delete_sel.gif" border="0"></a></td>
									
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
						<td bgcolor="<? if($order_by == "galorder"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;<a href="mgr.php?nav=<? echo $nav; ?>&order_by=galorder&order_type=<? if($order_by == "galorder"){ echo $order_type_next; }  ?>" class="title_links">ORDER</a>&nbsp;&nbsp;</b></font>								
						</td>
						<td bgcolor="<? if($order_by == "title"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" width="100%" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<? echo $nav; ?>&order_by=title&order_type=<? if($order_by == "title"){ echo $order_type_next; }  ?>" class="title_links">CATEGORY TITLE</a></b></font>								
						</td>
						<td bgcolor="<? if($order_by == "active"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<? echo $nav; ?>&order_by=active&order_type=<? if($order_by == "active"){ echo $order_type_next; }  ?>" class="title_links">ACTIVE</a></b></font>								
						</td>
						<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;EDIT&nbsp;&nbsp;</b></font>								
						</td>
						<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;&nbsp;&nbsp;</b></font>								
						</td>
					</tr>
					<?
						db_tree(0,1,$order_by,$order_type,$_SESSION['access_type'],$nav);
						
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
							if($_GET['item_id'] == "new"){
								echo "<form name=\"data_form\" action=\"" . $actions_page . "?pmode=save_new\" method=\"post\" ENCTYPE=\"multipart/form-data\">";
								echo "<input type=\"hidden\" value=\"mgr.php?nav=" . $nav . "&message=saved&order_by=" . $order_by . "&order_type=" . $order_type . "&message=added\" name=\"return\">";
							}
							// EDIT ITEM
							else{
								$pg_result = mysql_query("SELECT * FROM photo_galleries where id = '" . $_GET['item_id'] . "'", $db);
								$pg = mysql_fetch_object($pg_result);
								echo "<form name=\"data_form\" action=\"" . $actions_page . "?pmode=save_edit\" method=\"post\" ENCTYPE=\"multipart/form-data\">";
								echo "<input type=\"hidden\" value=\"" . $pg->id . "\" name=\"item_id\">";
								echo "<input type=\"hidden\" value=\"mgr.php?nav=" . $_GET['nav'] . "&message=saved&item_id=" . $_GET['item_id'] . "\" name=\"return\">";
							}
					?>
					<input type="hidden" value="<? echo $reference; ?>" name="reference" readonly>
					<input type="hidden" value="<? echo $file_path; ?>" name="file_path" readonly>
					<input type="hidden" value="<? echo $image_path; ?>" name="image_path" readonly>
					<input type="hidden" value="<? echo $_GET['lang']; ?>" name="language" readonly>
					<?
						if($pg->rdmcode){
							$rdmcode = $pg->rdmcode;
						} else {
							$rdmcode = random_gen(8, "");
						}
					?>
						
					
					<input type="hidden" value="<? echo $rdmcode; ?>" name="rdmcode">
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
												<? if($_GET['message'] == "saved"){ ?><td align="left" valign="middle" nowrap><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"><font color="#FFE400" style="font-size: 10;">&nbsp;Your changes have been saved.<?PHP if($setting->show_tree == 1){ ?><br>It is recommended that you rebuild your site menu<br><a href="menu_creator.php" target="_blank"><font color="FFFFF">[Click Here To Rebuild Menu]</font></a><?PHP } ?></td><? } ?>
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
												<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>CATEGORY DETAILS</b></td>
												<td align="right"><img src="images/mgr_section_header_r.gif"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;"><b>Photo Category Title</b><br>(You CAN NOT use these characters in the title: /"\|][;:)(*^%$#@<>)<br>
										<input type="text" name="title" value="<? if($_GET['lang'] == 'English') echo $pg->title; else echo $pg->{'title_'.$_GET['lang'] }; ?>" style="font-size: 13; font-weight: bold; width: 575; border: 1px solid #000000;"  maxlength="150">
									</td>
								</tr>
							<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<b>Category Avatar</b><br />
										<?php
										if(is_file("../gal_images/$pg->link"))
											echo "<img alt=\"avatar\" src=\"../gal_images/$pg->link\">&nbsp;<input type=\"checkbox\" name=\"deleteAv\" value=\"1\"> Delete ";
										?>
										<br />
										
										<p align="left">Upload/Update: <input type="file" name="avatarFile" size="40" maxlength="40"></p>
									</td>
								</tr>
								<tr>
								<td bgcolor="#5E85CA" class="data_box">
								<input type="checkbox" name="gallery_search_on" value="1" <? if($pg->gallery_search_on == 1){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Gallery Search:</b><br>(Allow visitors to do gallery searches in this gallery, for public galleries only)<br>
								</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<input type="checkbox" name="slideshow" value="1" <? if($pg->slideshow == 1){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Slide Show:</b><br>(Allow slide shows to be generated from this category.<br>Not recommend for categories with large amounts of photos!)<br>
									</td>
								</tr>
								<?PHP if(file_exists("../swf/pageflip.swf")){ ?>
								<tr>
								<td bgcolor="#5E85CA" class="data_box">
								<input type="checkbox" name="pageflip" value="1" <? if($pg->pageflip == 1){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Album Viewer:</b><br>(Allow an album view of this gallery).<br><b>(ALL PHOTOS MUST BE THE EXACT SAME RESOLUTION SIZE FOR THIS TO WORK! JUST LIKE PAGES IN A BOOK)</b><br>(If the pages are different sizes the smaller ones will be stretched)
								</td>
								</tr>
								<?PHP } ?>
								<?PHP if($item_id == "new"){ ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
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
									</td>
								</tr>
								<?PHP } else { ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<b>Default Gallery Photo Sort:</b><br />
										Sort By: <select name="sort_by" id="sort_by" style="font-size: 9; width:120;">
										<option value="id" <? if($pg->sort_by == id){ echo "selected"; } ?>>Photo ID</option>
										<option value="title" <? if($pg->sort_by == title){ echo "selected"; } ?>>Photo Title</option>
										<option value="date" <? if($pg->sort_by == date){ echo "selected"; } ?>>Date Added</option>
										<option value="popular" <? if($pg->sort_by == popular){ echo "selected"; } ?>>Most Popular</option>
										</select>
					  					Sort Order: <select name="sort_order" id="sort_order" style="font-size: 9; width:120;">
										<option value="ascending" <? if($pg->sort_order == ascending){ echo "selected"; } ?>>Ascending</option>
										<option value="descending" <? if($pg->sort_order == descending){ echo "selected"; } ?>>Descending</option>
										</select><br/>
									</td>
								</tr>
								<?PHP } ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<input type="checkbox" name="sort_on" value="1" <? if($pg->sort_on == 1){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Display Sorting Options:</b><br>(Allow visitors to change the sort order of this gallery, check this box to turn it on.)
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
									<b>Accounts Required:</b> (Checking any of these in any combination will limit the gallery to only the subscriptions you check below. Leave all three unchecked if you want the gallery open to the public and subscribers.)<br><br>
										<input type="checkbox" name="free" value="1" <? if($pg->free == 1){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Free subscribers:</b><br>(Allow free subscribers / members to view this gallery. Checking this box will make it mandatory to have at least a free account to see this gallery)
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<input type="checkbox" name="monthly" value="1" <? if($pg->monthly == 1){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Monthly subscribers:</b><br>(Allow monthly subscribers / members to view this gallery. Checking this box will make it mandatory to have at least a monthly account to see this gallery)
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<input type="checkbox" name="yearly" value="1" <? if($pg->yearly == 1){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Yearly subscribers:</b><br>(Allow yearly subscribers / members to view this gallery. Checking this box will make it mandatory to have at least a yearly account to see this gallery)
									</td>
								</tr>
								<? if(file_exists("../photog_main.php")){ ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<input type="checkbox" name="photog_use" value="1" <? if($pg->photog_use == 1 or $item_id == "new"){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Allow photographers to upload to this category?</b> (Check this box if you wish to allow photographers to use this as an option during uploading.)<br>
									</td>
								</tr>
								<? } ?>
								<tr>
								<td bgcolor="#5E85CA" class="data_box">
										<b>Category Content:</b> (Displayed above the images, <b>Example:</b> Brief description of category.)<br>
										
										<? $sContent = $pg->description; ?>
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
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;"><b>Public or private category?</b><br>(Public categories can be seen by all visitors to the site. Private categories can only be seen by people who you send the link and password to. The link to this category will show below when you save this listing as a private category.)<br>
										<input type="radio" name="pub_pri" value="0" <?php if($pg->pub_pri == 0){ echo "checked";  }?>> Public <input type="radio" name="pub_pri" value="1" <?php if($pg->pub_pri == 1){ echo "checked"; } ?>> Private										
									
										<br><br>
										<b>Password:</b><br>(If this category is private, visitors will only be able to view the category if you send them the password & the link below. If you don't want a password on this category please leave it blank.)<br>
										<input type="text" name="password" value="<? echo $pg->password; ?>" style="font-size: 13; font-weight: bold; width: 250; border: 1px solid #000000;"  maxlength="150">
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" valign="top" class="data_box">
										<b>Place under:</b> (Can place this category in another category to make it a sub-category.)<br>
										
										<?php
											$gid = $pg->nest_under;
												
											//echo "GID: " . $gid;
											//exit;
										?>
										<select name="nest" style="font-size: 11; width: 350; border: 1px solid #000000;">
											<option value="0">None</option>
											
											
											<?
												
												function db_tree2($x,$level,$nest_under,$item_id){
													include("../database.php");
													global $gid;
													$my_row = 1;
													
													$ca_result = mysql_query("SELECT * FROM photo_galleries where nest_under = '$x' order by title", $db);
													$ca_rows = mysql_num_rows($ca_result);
													while($ca = mysql_fetch_object($ca_result)){
														if($ca->id != $item_id){
														echo "<option value=\"";
														
														//if($ca->id == $item_id){
															//echo $nest_under . "\"";
														//} else {
															echo $ca->id . "\"";
														//}
														
														if($gid == $ca->id){
															echo " selected";
														}
														
														echo ">";
																
														for($z = 1; $z < $level; $z++){
															echo "&nbsp;&nbsp;&nbsp;&nbsp;";
														}
														
														echo $ca->title . "</option>\n";
														
														db_tree2($ca->id,$level + 1,$nest_under,$_GET['item_id']);
														
													$my_row ++;
														}
													}
												}
												db_tree2(0,1,$ca->nest_under,$_GET['item_id']);
											?>
										</select>													
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;"><b>Order:</b> (Optional | Order to display categories in on the public site.)<br>
										<input type="text" name="galorder" value="<?php echo $pg->galorder; ?>" style="font-size: 13; font-weight: bold; width: 150px; border: 1px solid #000000;"  maxlength="150">
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<input type="checkbox" name="active" value="1" <? if($pg->active == 1 or $item_id == "new"){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><b>Active:</b> (Display on the public website.)<br>
									</td>
								</tr>
								<?
									if($item_id != "new" and $pg->pub_pri == 1){
								?>
									<tr>
										<td bgcolor="#5E85CA" class="data_box">
											<font face="arial" color="#ffffff" style="font-size: 11;"><b>Link to this category:</b> (Use this link for private categories. Send the link (along with the password if there is one) to the person you would like to be able to view this category.)<br>
											<input type="text" name="prilink" value="<?php echo $setting->site_url; ?>/pri.php?gid=<?php echo $item_id; ?>&gal=<?php echo $pg->rdmcode; ?>" style="font-size: 13; font-weight: bold; width: 575px; border: 1px solid #000000;"  maxlength="150">
										</td>
									</tr>
								<?
									}
								?>
								<!-- END : IMAGE UPLOAD -->
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
