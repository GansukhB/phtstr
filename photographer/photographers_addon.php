<?PHP

/*
if($_SESSION['access_type'] == "mgr" or $_SESSION['access_type'] == "demo"){
} else {
  echo "You do not have permission to look at this file!"; exit; 
}*/
	$plugin_name    = "Email List Plugin";
	$plugin_version = "1.0 [4.13.04]";
	
	if($execute_nav == 1){
		$nav_order = 6; // order of the nav, cant be 0!
		if($_SESSION['photog_addon'] == 1){
		  $nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		} else {
		  $nav_visible = 0;
		}
		$nav_name = $left_link_photog; // name of the nav that will appear on the page
	} else {
		
		// OPTIONS
			$image_upload_count     = 0; // number of images that can be uploaded per members item / 0 for no files
			$image_path       = "../uploaded_images/";
			$image_caption    = 1; // 1 on | 0 off / Allow captions on images
			$image_area_name  = "";
				
			$file_upload_count      = 0; // number of files that can be uploaded per members item / 0 for no files
			$file_path        = "../uploaded_files/";
			$file_area_name   = "";
			
			$copy_link_option = 0;  // 1 on | 0 off / Allow image links to be copied
			
			$editor           = 1; // 1 on | 0 off
			$editor_type			= $setting->editor_type; //What editor to show on this page
			$homepage_option  = 1; // 1 on | 0 off	
			$reference        = "photographers"; // used when saving and pulling images or files from/to the database
			$actions_page     = "actions_photographers_addon.php"; // Actions page for processing forms
		
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
	
	function checkUncheckAll(theElement) {
     var theForm = theElement.form, z = 0;
	 for(z=0; z<theForm.length;z++){
      if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall'){
	  theForm[z].checked = theElement.checked;
	  }
     }
    }
    
	function delete_data() {
		var agree=confirm("<?PHP echo $photog_are_you_sure_delete; ?>");
		if (agree) {
			document.listings.action = "<?PHP echo $actions_page; ?>?pmode=delete";
			document.listings.submit();
		}
		else {
			false
		}
	}
	
	function delete_download_data() {
		var agree=confirm("<?PHP echo $photog_are_you_sure_delete_download; ?>");
		if (agree) {
			document.downloads.action = "<?PHP echo $actions_page; ?>?pmode=delete_download";
			document.downloads.submit();
		}
		else {
			false
		}
	}
	
	function delete_upload_data() {
		var agree=confirm("<?PHP echo $photog_are_you_sure_delete_upload; ?>");
		if (agree) {
			document.uploads.action = "<?PHP echo $actions_page; ?>?pmode=delete_upload";
			document.uploads.submit();
		}
		else {
			false
		}
	}
	
	function delete_sales_data() {
		var agree=confirm("<?PHP echo $photog_are_you_sure_delete_sales; ?>");
		if (agree) {
			document.photo_sales.action = "<?PHP echo $actions_page; ?>?pmode=delete_sales";
			document.photo_sales.submit();
		}
		else {
			false
		}
	}
	
	function mark_hide() {
		var agree=confirm("<?PHP echo $photog_aus_hide; ?>");
		if (agree) {
			document.photo_sales.action = "<?PHP echo $actions_page; ?>?pmode=mark_hide";
			document.photo_sales.submit();
		}
		else {
			false
		}
	}
	
	function mark_hide_download() {
		var agree=confirm("<?PHP echo $photog_aus_hide_download; ?>");
		if (agree) {
			document.downloads.action = "<?PHP echo $actions_page; ?>?pmode=mark_hide";
			document.downloads.submit();
		}
		else {
			false
		}
	}
	
	function mark_hide_upload() {
		var agree=confirm("<?PHP echo $photog_aus_hide_upload; ?>");
		if (agree) {
			document.uploads.action = "<?PHP echo $actions_page; ?>?pmode=mark_hide_upload";
			document.uploads.submit();
		}
		else {
			false
		}
	}
	
	function mark_paid() {
		var agree=confirm("<?PHP echo $photog_aus_paid; ?>");
		if (agree) {
			document.photo_sales.action = "<?PHP echo $actions_page; ?>?pmode=mark_paid";
			document.photo_sales.submit();
		}
		else {
			false
		}
	}
	
	function mark_paid_download() {
		var agree=confirm("<?PHP echo $photog_aus_paid_download; ?>");
		if (agree) {
			document.downloads.action = "<?PHP echo $actions_page; ?>?pmode=mark_paid";
			document.downloads.submit();
		}
		else {
			false
		}
	}
	
	function mark_paid_upload() {
		var agree=confirm("<?PHP echo $photog_aus_paid_upload; ?>");
		if (agree) {
			document.uploads.action = "<?PHP echo $actions_page; ?>?pmode=mark_paid_upload";
			document.uploads.submit();
		}
		else {
			false
		}
	}
	
	function save_data() {
		if(document.data_form.name.value == "" || document.data_form.email.value == "" || document.data_form.password.value == ""){
			alert("<?PHP echo $photog_enter_name; ?>");
		}
		else {
			var agree=confirm("<?PHP echo $photog_save_changes; ?>");
			if (agree) {
				<?PHP	if($editor_type == "innova"){ ?>
				document.getElementById("article").value=oEdit1.getHTMLBody();
				<?PHP } ?>
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
<body>
	<table width="750" cellpadding="3" cellspacing="3">
		<tr>
			<td align="left">
				<table width="100%">
					
					<tr>
						<form action="mgr.php?nav=<?PHP echo $nav; ?>&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>" method="post">
						<td><?PHP echo $photog_search_for; ?>
							<input type="textbox" name="search" style="font-size: 11; width: 250px;"><input type="submit" value="Search">
						</td>
					</tr>
					<tr>
						</form>
						<form action="<?PHP echo $actions_page; ?>?pmode=update_com" method="post">
						<?PHP echo "<input type=\"hidden\" value=\"mgr.php?nav=".$nav."&order_by=".$order_by."&order_type=".$order_type."&search=".$search."&startat=".$startat."&perpage=".$perpage."&page_num=".$page_num."\" name=\"return\">"; ?>
							<td><hr><b><?PHP echo $photog_default_com; ?><input type="text" name="com_level" value="<?PHP echo $setting->com_level; ?>" style="width: 30px;">%</b><input type="submit" value="Update"><br /><?PHP echo $photog_your_com; ?></td>
						</form>
					</tr>
					<tr>
						<form action="<?PHP echo $actions_page; ?>?pmode=update_download_com" method="post">
						<?PHP echo "<input type=\"hidden\" value=\"mgr.php?nav=".$nav."&order_by=".$order_by."&order_type=".$order_type."&search=".$search."&startat=".$startat."&perpage=".$perpage."&page_num=".$page_num."\" name=\"return\">"; ?>
						<td><hr><b><?PHP echo $photog_default_download; ?> <input type="text" name="com_download" value="<?PHP echo $setting->com_download; ?>" style="width: 60px;">%<?PHP echo $photog_download_com_a; ?><input type="text" name="com_download_default" value="<?PHP echo $setting->com_download_default; ?>" style="width: 60px;"></b><input type="submit" value="Update"><?PHP echo $photog_download_com_b; ?>
						</form>
					</tr>
					<?PHP
					//NOT BEING USED AS OF YET
					/*
					<tr>
						<td>
							<!--<a href="full_details.php?photog_id=<?PHP echo $item_id; ?>" class="title_links"><b>Upload Details</b></a><br>-->
							<a href="total_report2.php" target="_blank" class="title_links"><b>All Photographer Activity Report</b></a><br>
							<a href="total_report2_textonly.php" target="_blank" class="title_links"><b>All Photographer Activity Report (No Photos)</b></a>
						</td>
					</tr>
					*/
					?>
				</table>
			</td>
		</tr>
		<tr>
			<?PHP
				if(!$item_id){
			?>
			<!-- LIST COLUMN -->
			<td width="100%" valign="top">
				<table cellpadding="0" cellspacing="0" bgcolor="#577EC4" width="100%" style="border: 1px solid #5B8BD8;" background="images/mgr_bg_texture.gif">
					<?PHP
						if($order_type == "desc"){
							$order_type_next = "";
						} else {
							$order_type_next = "desc";
						}
						
						if($order_by == ""){
							$order_by = "name";
							$order_type = "";
						}
						
						// COLSPAN DEPENDING ON ADMIN LOGIN
						if($_SESSION['access_type'] == "admin"){
							$colspan="8";
						}
						else{
							$colspan="8";
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
							$search = addslashes($search);
							$members_result = mysql_query("SELECT * FROM photographers where name like '%$search%' or email like '%$search%' order by $order_by " . $order_type, $db);
						} else {
							$members_result = mysql_query("SELECT * FROM photographers order by $order_by " . $order_type, $db);
						}
						$members_rows = mysql_num_rows($members_result);
					?>
					<tr>
						<td colspan="<?PHP echo $colspan; ?>" height="4"></td>
					</tr>
					<form name="listings" method="post">
					<input type="hidden" value="mgr.php?nav=<?PHP echo $nav; ?>&message=deleted&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>" name="return">
					<input type="hidden" value="<?PHP echo $file_path; ?>" name="file_path">
					<input type="hidden" value="<?PHP echo $image_path; ?>" name="image_path">
					<input type="hidden" value="<?PHP echo $reference; ?>" name="reference">
					<tr>
						<td colspan="<?PHP echo $colspan; ?>">
							<table width="100%">
								<tr>
									<td style="padding-left: 8px;"><font style="font-size: 13;"><?PHP echo $photog_photog_title; ?></td>
									<?PHP if($message == "deleted"){ ?>
									<td valign="middle">
										<table>
											<tr>
												<td>&nbsp;</td>
												<td valign="middle"><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"></td>
												<td valign="middle"><font color="#FFE400" style="font-size: 10;"><?PHP echo $photog_items_deleted; ?></td>
											</tr>
										</table>
									</td>
									<?PHP } ?>
									<?PHP if($message == "added"){ ?>
									<td valign="middle">
										<table>
											<tr>
												<td>&nbsp;</td>
												<td valign="middle"><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"></td>
												<td valign="middle"><font color="#FFE400" style="font-size: 10;"><?PHP echo $photog_items_added; ?></td>
											</tr>
										</table>
									</td>
									<?PHP } ?>
									<?PHP
										if($members_rows >= 5){
									?>
									<td align="right"><a href="mgr.php?nav=<?PHP echo $nav; ?>&item_id=new&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>"><img src="images/mgr_button_new.gif" border="0"></a>&nbsp;<a href="javascript:selectAll(document.listings,0);"><img src="images/mgr_button_select_all.gif" border="0"></a>&nbsp;<?PHP if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:delete_data();\">"; } ?><img src="images/mgr_button_delete_sel.gif" border="0"></a></td>
									<?PHP
										}
										else if($members_rows == 0){
									?>
									<td align="right"><a href="mgr.php?nav=<?PHP echo $nav; ?>&item_id=new&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>"><img src="images/mgr_button_new.gif" border="0"></a></td>
									<?PHP
										}
									?>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="<?PHP echo $colspan; ?>" height="4"></td>
					</tr>
					<tr>
						<td bgcolor="<?PHP if($order_by == "id"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;<a href="mgr.php?nav=<?PHP echo $nav; ?>&order_by=id&order_type=<?PHP if($order_by == "id"){ echo $order_type_next; }  ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>" class="title_links"><?PHP echo $photog_list_id; ?></a>&nbsp;&nbsp;</b></font>								
						</td>
						<td colspan="2" bgcolor="<?PHP if($order_by == "name"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" width="100%" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<?PHP echo $nav; ?>&order_by=name&order_type=<?PHP if($order_by == "name"){ echo $order_type_next; }  ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>" class="title_links"><?PHP echo $photog_list_name; ?></a></b></font>								
						</td>
						<td bgcolor="<?PHP if($order_by == "email"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" width="100%" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<?PHP echo $nav; ?>&order_by=email&order_type=<?PHP if($order_by == "email"){ echo $order_type_next; }  ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>" class="title_links"><?PHP echo $photog_list_email; ?></a></b></font>								
						</td>
						<td bgcolor="<?PHP if($order_by == "status"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<?PHP echo $nav; ?>&order_by=status&order_type=<?PHP if($order_by == "status"){ echo $order_type_next; }  ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>" class="title_links"><?PHP echo $photog_list_active; ?></a></b></font>								
						</td>
						<td bgcolor="<?PHP if($order_by == "featured"){ echo "#13387E"; } else { echo "#3C6ABB"; } ?>" align="left" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;">&nbsp;<b><a href="mgr.php?nav=<?PHP echo $nav; ?>&order_by=featured&order_type=<?PHP if($order_by == "featured"){ echo $order_type_next; }  ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>" class="title_links"><?PHP echo $photog_list_featured; ?></a></b></font>								
						</td>
						<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-right: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;<?PHP echo $photog_list_edit; ?>&nbsp;&nbsp;</b></font>								
						</td>
						<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894; border-left: 1px solid #5B8BD8;border-top: 1px solid #5B8BD8;" nowrap>	
							<font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;&nbsp;&nbsp;</b></font>								
						</td>
					</tr>
					<?PHP
						while($members = mysql_fetch_object($members_result)){
						
						//ADDED FOR EXPORT OF ORDERS TO CSV
							$csv_items[]=$members->id;
							$csv_type = "photographers";
							
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
							<td align="center" class="listing_id" onClick="window.location.href='mgr.php?nav=<?PHP echo $nav; ?>&item_id=<?PHP echo $members->id; ?>&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>'">
								<?PHP echo $members->id; ?>
							</td>						
							
							<?PHP
								$result = mysql_query("SELECT * FROM photo_package where photographer = '$members->id'", $db);
								$photo_rows = mysql_num_rows($result);
								
								$photog_result = mysql_query("SELECT * FROM photog_sales where photographer  = '$members->id' and completed = '1' and duplicate = '0'", $db);
								$photog_rows = mysql_num_rows($photog_result);
								
								$photog2_result = mysql_query("SELECT * FROM photog_sales where photographer  = '$members->id' and status = '0' and completed = '1' and duplicate = '0'", $db);
								$photog2_rows = mysql_num_rows($photog2_result);
								
								$photog3_result = mysql_query("SELECT * FROM photog_earning where photog_id  = '$members->id' and status = '0'", $db);
								$photog3_rows = mysql_num_rows($photog3_result);
								
							?>
							<td align="left" class="listing" onClick="window.location.href='mgr.php?nav=<?PHP echo $nav; ?>&item_id=<?PHP echo $members->id; ?>&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>'"><a href="mgr.php?nav=<?PHP echo $nav; ?>&item_id=<?PHP echo $members->id; ?>&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>" class="list_links"><img src="images/mgr_icon_members2.gif" border="0"></a></td>
							<td align="left" width="100%" class="listing" style="padding-left: 10;" onClick="window.location.href='mgr.php?nav=<?PHP echo $nav; ?>&item_id=<?PHP echo $members->id; ?>&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>'">
								<a href="mgr.php?nav=<?PHP echo $nav; ?>&item_id=<?PHP echo $members->id; ?>&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>" class="list_links"><?PHP echo $emp_name; ?></a><br><font style="font-weight: normal;"><font color="#ffffff">Selling <b><?PHP echo $photo_rows; ?></b> Photos | <b><?PHP echo $photog_rows; ?></b> Total Sales |  <b><?PHP echo $photog2_rows; ?></b> Unpaid
							</td>
							<td align="left" width="100%" style="padding-left: 6px;padding-right: 6px;" nowrap class="listing" style="padding-left: 10;" onClick="window.location.href='mgr.php?nav=<?PHP echo $nav; ?>&item_id=<?PHP echo $members->id; ?>&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>'">
								<a href="mgr.php?nav=<?PHP echo $nav; ?>&item_id=<?PHP echo $members->id; ?>&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>" class="list_links"><?PHP echo $members->email; ?></i>
							</td>
							<td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<?PHP echo $nav; ?>&item_id=<?PHP echo $members->id; ?>&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>'">
								<a href="mgr.php?nav=<?PHP echo $nav; ?>&item_id=<?PHP echo $members->id; ?>&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>" class="edit_links"><b><?PHP if($members->status == 1){ echo $photog_yes; } else { echo $photog_no; } ?></a>
							</td>
							<td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<?PHP echo $nav; ?>&item_id=<?PHP echo $members->id; ?>&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>'">
								<a href="mgr.php?nav=<?PHP echo $nav; ?>&item_id=<?PHP echo $members->id; ?>&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>" class="edit_links"><b><?PHP if($members->featured == 1){ echo $photog_yes; } else { echo $photog_no; } ?></a>
							</td>
							<td align="center" class="listing" onClick="window.location.href='mgr.php?nav=<?PHP echo $nav; ?>&item_id=<?PHP echo $members->id; ?>&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>'">
								<a href="mgr.php?nav=<?PHP echo $nav; ?>&item_id=<?PHP echo $members->id; ?>&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>" class="edit_links"><?PHP echo $photog_list_edit_a; ?></a>
							</td>
							<td align="center" class="listing">
								<input name="<?PHP echo $members->id; ?>" type="checkbox" value="1">
							</td>
						</tr>
					<?PHP
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
						<td colspan="<?PHP echo $colspan; ?>">
								<div name="result_details" id="result_details" style="padding-left: 10px;padding-right: 10px;padding-top: 30px;width: 100%; clear: both;">
								<table width="100%" cellpadding="0" cellspacing="0">
									<tr>
										<td>
											<?PHP echo $photog_results; ?><b><?PHP echo $members_rows; ?> <?PHP echo $photog_photog; ?></b> (<?PHP echo $result_pages; ?> <?PHP echo $photog_pages; ?>)
										</td>
										<td align="right">
										<?PHP echo $photog_page; ?>  
										<b>
										<?PHP
											$page_startat = 0;
											for($page=1; $page <= $result_pages; $page++){
												if($page == $page_num){
													echo "[" . $page . "] ";	
												} else {
													echo "<a href=\"mgr.php?nav=".$_GET['nav']."&gid=".$_GET['gid']."&sgid=".$_GET['sgid']."&startat=".$page_startat."&perpage=".$perpage."&page_num=".$page."&search=".$search."&order_by=".$order_by."&order_type=".$order_type."\"><font color=\"#ffffff\">" . $page . "</a> ";	
												}
											$page_startat = $page_startat + $perpage;
											}			
										?>
										: 
										<?PHP
											if($startat == 0){
										?>
											<font color="#B0B0B0"><?PHP echo $photog_previous; ?></font>
										<?PHP
											}
											else{
												if(($startat - $perpage) < 1) {
										?>
													<a href="<?PHP echo "mgr.php?nav=".$_GET['nav']."&gid=".$_GET['gid']."&sgid=".$_GET['sgid']."&startat=0&perpage=".$perpage."&page_num=".($page_num-1)."&search=".$search."&order_by=".$order_by."&order_type=".$order_type; ?>"><font color="#ffffff"><?PHP echo $photog_previous; ?></a>
										<?PHP
												} else {
										?>
													<a href="<?PHP echo "mgr.php?nav=".$_GET['nav']."&gid=".$_GET['gid']."&sgid=".$_GET['sgid']."&startat=".($startat - $perpage); ?>&perpage=<?PHP print($perpage)."&page_num=".($page_num-1)."&search=".$search."&order_by=".$order_by."&order_type=".$order_type; ?>"><font color="#ffffff"><?PHP echo $photog_previous; ?></a>
										<?PHP
												}
											}
										?>
										 |
 
										<?PHP
										
											if(($startat + $perpage) >= $members_rows){
										?>
											<font color="#B0B0B0"><?PHP echo $photog_next; ?></font>
										<?PHP
											}
											else{
												if($line < $members_rows) {
										?>
												<a href="<?PHP echo "mgr.php?nav=".$_GET['nav']."&gid=".$_GET['gid']."&sgid=".$_GET['sgid']."&startat=".($startat + $perpage); ?>&perpage=<?PHP print($perpage)."&page_num=".($page_num+1)."&search=".$search."&order_by=".$order_by."&order_type=".$order_type; ?>"><font color="#ffffff"><?PHP echo $photog_next; ?></a>
										<?PHP
												} else {
										?>
												<a href="<?PHP echo "mgr.php?nav=".$_GET['nav']."&gid=".$_GET['gid']."&sgid=".$_GET['sgid']."&startat=".($startat + $perpage); ?>&perpage=<?PHP print($perpage)."&page_num=".($page_num+1)."&search=".$search."&order_by=".$order_by."&order_type=".$order_type; ?>"><font color="#ffffff"><?PHP echo $photog_next; ?></a>
										<?PHP
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
					<?PHP
						#####################################################						
						}
						if($members_rows == 0){
					?>
						<tr>
							<td colspan="<?PHP echo $colspan; ?>" bgcolor="#577EC4" align="center" valign="middle" height="60">
								<table>
									<tr>
										<td align="right" valign="bottom">
											<img src="images/mgr_check3_static.gif" valign="absmiddle">
										</td>
										<td align="right" valign="middle" nowrap>
											<?PHP
												if($search != ""){
											?>
												<font color="#FFE400" style="font-size: 10;">&nbsp;<?PHP echo $photog_no_results; ?>
											<?PHP		
												}else{
											?>
												<font color="#FFE400" style="font-size: 10;">&nbsp;<?PHP echo $photog_no_listing; ?>
											<?PHP
												}
											?>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="<?PHP echo $colspan; ?>" height="4"></td>
						</tr>
						<tr>
							<td colspan="<?PHP echo $colspan; ?>" align="right">
								<table width="100%">
									<tr>
										<td align="right"><a href="mgr.php?nav=<?PHP echo $nav; ?>&item_id=new&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>"><img src="images/mgr_button_new.gif" border="0"></a></td>
									</tr>
								</table>
							</td>
						</tr>
					<?PHP
						}
						else{
					?>
						<tr>
							<td colspan="<?PHP echo $colspan; ?>" height="4"></td>
						</tr>
						<tr>
							<td colspan="<?PHP echo $colspan; ?>" align="right">
								<table width="100%">
									<tr>
										<td align="right"><input type="checkbox" name="delete_photog_photos" value="1"><?PHP echo $photog_check_delete; ?><br><a href="mgr.php?nav=<?PHP echo $nav; ?>&item_id=new&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>"><img src="images/mgr_button_new.gif" border="0"></a>&nbsp;<a href="javascript:selectAll(document.listings,0);"><img src="images/mgr_button_select_all.gif" border="0"></a>&nbsp;<?PHP if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:delete_data();\">"; } ?><img src="images/mgr_button_delete_sel.gif" border="0"></a>
										</form>
										<form name="export" action="csv_export.php" method="post" style="margin: 0px; padding: 0px;">
											<?PHP
											//ADDED FOR EXPORT OF ORDERS TO CSV
											$csv_items = implode(",",$csv_items);
											?>
											<input type="hidden" name="pass" value="<?PHP echo md5($setting->access_id); ?>">
											<input type="hidden" name="return" value="<?PHP echo "mgr.php?nav=".$nav; ?>">
											<input type="hidden" name="csv_table" value="photographers">
											<input type="hidden" name="csv_items" value="<?PHP echo $csv_items; ?>">
											<input type="submit" value="<?PHP echo $csv_export_form_button; ?>">
										</form>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="<?PHP echo $colspan; ?>" height="8"></td>
						</tr>
					<?PHP
						}
					?>
					<!-- START: INSRUCTIONS -->
					<tr>
						<td colspan="<?PHP echo $colspan; ?>"  bgcolor="#3C6ABB" align="left" style="padding: 3px; border-bottom: 1px solid #355894; border-top: 1px solid #5E85CA;">
							<table cellpadding="0" cellspacing="0">
								<tr>	
									<td width="100%"><font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;&nbsp;<a href="#" onClick="instructions();" class="title_links"><?PHP echo $photog_inst_a; ?></a></b><?PHP echo $photog_inst_b; ?></font>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="<?PHP echo $colspan; ?>"  bgcolor="#426AB3" align="center" style="padding-top: 10px; padding-bottom: 10px;" background="images/mgr_bg_texture.gif">
							<div style="position:relative; top:0px; left:0px;display:none;z-index:1" id="instructions">
							<table width="90%">
								<?PHP if($message == "deleted"){ ?>
								<tr>
									<td valign="top"><img src="images/mgr_check5_ins.gif" valign="absmiddle"></td>
									<td><font color="#FFE400" style="font-size: 10;"><?PHP echo $photog_items_deleted; ?></td>
								</tr>
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								<?PHP } ?>
								<tr>
									<td valign="top"><img src="images/mgr_check5_ins.gif"></td>
									<td><font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_inst_add; ?></td>
								</tr>
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								<tr>
									<td valign="top"><img src="images/mgr_check5_ins.gif"></td>
									<td><font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_inst_edit; ?></td>
								</tr>
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								<tr>
									<td valign="top"><img src="images/mgr_check5_ins.gif"></td>
									<td><font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_inst_delete; ?></td>
								</tr>
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								<tr>
									<td valign="top"><img src="images/mgr_check5_ins.gif"></td>
									<td><font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_inst_all; ?></td>
								</tr>
								<?PHP if($access_type == "admin"){ ?>
								<tr>
									<td colspan="2" height="10"></td>
								</tr>
								<tr>
									<td colspan="2" align="right"><font face="arial" style="font-size: 11;" color="#C8D5ED"><?PHP echo $plugin_name . " " . $plugin_version; ?></td>
								</tr>
								<?PHP } ?>
							</table>
							</div>
						</td>
					</tr>
					<!-- END: INSRUCTIONS -->
				</table>			
			</td>
			<?PHP
				}
				else {
			?>
			<!-- NEW/EDIT COLUMN -->
			<td width="100%" valign="top">
				<table cellpadding="0" cellspacing="0" bgcolor="#577EC4" width="100%" style="border: 1px solid #5B8BD8;">
					<?PHP
						// ADD NEW ITEM
						if($item_id == "new"){
							$this_day = date("d");
							$this_month = date("m");
							$this_year = date("Y");
							
							echo "<form name=\"data_form\" action=\"" . $actions_page . "?pmode=save_new\" method=\"post\" ENCTYPE=\"multipart/form-data\">";
							echo "<input type=\"hidden\" value=\"mgr.php?nav=" . $nav . "&order_by=" . $order_by . "&order_type=" . $order_type . "&startat=".$startat."&perpage=".$perpage."&page_num=".$page_num."&message=added\" name=\"return\">";
						}
						// EDIT ITEM
						else{
							$members_result = mysql_query("SELECT * FROM photographers where id = '$item_id'", $db);
							$members = mysql_fetch_object($members_result);
							
							$this_day = substr($members->added, 6, 2);
							$this_month = substr($members->added, 4, 2);
							$this_year = substr($members->added, 0, 4);
							
							$sub_ends = $members->added + 10000;
							
							
							
							$end_day = substr($sub_ends, 6, 2);
							$end_month = substr($sub_ends, 4, 2);
							$end_year = substr($sub_ends, 0, 4);
							
							$sub_ends = $end_month . "/" . $end_day . "/" . $end_year;
									
							echo "<form name=\"data_form\" action=\"" . $actions_page . "?pmode=save_edit\" method=\"post\" ENCTYPE=\"multipart/form-data\">";
							echo "<input type=\"hidden\" value=\"" . $members->id . "\" name=\"item_id\">";
							echo "<input type=\"hidden\" value=\"mgr.php?nav=".$nav."&message=saved&item_id=".$item_id."&order_by=".$order_by."&order_type=".$order_type."&startat=".$startat."&perpage=".$perpage."&page_num=".$page_num."\" name=\"return\">";
						}
						
						$currency_result = mysql_query("SELECT * FROM currency where active = '1'", $db);
						$currency = mysql_fetch_object($currency_result);
					?>
					<input type="hidden" value="<?PHP echo $reference; ?>" name="reference">
					<input type="hidden" value="<?PHP echo $file_path; ?>" name="file_path">
					<input type="hidden" value="<?PHP echo $image_path; ?>" name="image_path">
					<tr>
						<td bgcolor="#3C6ABB" align="center" style="padding: 3px; border-bottom: 1px solid #355894;">
							<table cellpadding="0" cellspacing="0" width="95%">
								<tr>	
									<td nowrap><font face="arial" color="#ffffff" style="font-size: 11;"><b>&nbsp;<?PHP if($item_id == "new"){ ?><?PHP echo $photog_add_listing; ?><?PHP } else { ?><?PHP echo $photog_edit_listing; ?><?PHP } ?></b></font></td>
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
												<?PHP if($message == "saved"){ ?><td align="left" valign="middle" nowrap><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"><font color="#FFE400" style="font-size: 10;">&nbsp;<?PHP echo $photog_changes_saved; ?></td><?PHP } ?>
												<td align="right"><a href="mgr.php?nav=<?PHP echo $nav; ?>&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>"><img src="images/mgr_button_cancel.gif" border="0"></a>&nbsp;<?PHP if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_data();\">"; } ?><img src="images/mgr_button_save.gif" border="0"></a></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td height="10"></td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<table width="100%" cellpadding="0" cellspacing="0">
												<tr>
												<td width="50%">
													<font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_name_title; ?><br>
													<input type="text" name="name" value="<?PHP echo $members->name; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>
												</tr>
									<?PHP if($error == "display_name"){ ?>
											<tr>
												<td valign="middle"><font color="#AA0000" style="font-size: 11; font-weight: bold;"><?PHP echo $photog_error_display_name; ?></font></td>
											</tr>
									<?PHP } ?>
												<tr>
												<td width="50%">
													<font face="arial" color="#ffffff" style="font-size: 11;"><b><?PHP echo $photog_form_display_name; ?></b><br>
													<input type="text" name="display_name" value="<?PHP echo $members->display_name; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>
												</tr>
									<?PHP if($error == "email"){ ?>
											<tr>
												<td valign="middle"><font color="#AA0000" style="font-size: 11; font-weight: bold;"><?PHP echo $photog_error_email; ?></font></td>
											</tr>
									<?PHP } ?>
												<tr>
												<td width="50%">
													<font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_email_title; ?><br>
													<input type="text" name="email" value="<?PHP echo $members->email; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>
												</tr>
												<tr>
												<td width="50%">
													<font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_phone_title; ?><br>
													<input type="text" name="phone" value="<?PHP echo $members->phone; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>
												</tr>
												<tr>
												<td width="50%">
													<font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_add1_title; ?><br>
													<input type="text" name="address1" value="<?PHP echo $members->address1; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>
												</tr>
												<tr>
												<td width="50%">
													<font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_add2_title; ?><br>
													<input type="text" name="address2" value="<?PHP echo $members->address2; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>
												</tr>
												<tr>
												<td width="50%">
													<font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_city_title; ?><br>
													<input type="text" name="city" value="<?PHP echo $members->city; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>
												</tr>
												<tr>
												<td width="50%">
													<font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_state_title; ?><br>
													<input type="text" name="state" value="<?PHP echo $members->state; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>
												</tr>
												<tr>
												<td width="50%">
													<font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_zip_title; ?><br>
													<input type="text" name="zip" value="<?PHP echo $members->zip; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>
												</tr>
												<tr>
												<td width="50%">
													<font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_country_title; ?><br>
													<input type="text" name="country_display" value="<?PHP echo $members->country; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150"><br>
													<?PHP echo $photog_change_to; ?><br>
													<?PHP include("../country.php"); ?>
												</td>
												</tr>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_password_title; ?><br>
										<input type="text" name="password" value="<?PHP echo $members->password; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_default_com_level; ?><br>
										<input type="text" name="com_percent" value="<?PHP if(!$members->com_percent){ echo  $setting->com_level; } else { echo $members->com_percent; } ?>" style="font-size: 13; font-weight: bold; width: 100px; border: 1px solid #000000;" maxlength="150"><b>%</b>
									</td>
								</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_download_com_a_form; ?><br>
													<input type="text" name="com_download" value="<?PHP if(!$members->com_download){ echo $setting->com_download; } else { echo $members->com_download; } ?>" style="font-size: 13; font-weight: bold; width: 100px; border: 1px solid #000000;" maxlength="150"><b>%</b>
												</td>
											</tr>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_download_com_b_form; ?><br>
													<input type="text" name="com_download_default" value="<?PHP if(!$members->com_download_default){ echo $setting->com_download_default; } else { echo $members->com_download_default; } ?>" style="font-size: 13; font-weight: bold; width: 100px; border: 1px solid #000000;" maxlength="150">
												</td>
											</tr>
											<tr>
													<td bgcolor="#5E85CA" class="data_box"><?PHP echo $photog_payment_type; ?><br>
													<input type="radio" name="payment" value="check" <?PHP if($members->payment_type == "check"){ echo "CHECKED"; }?>><?PHP echo $photog_check_title; ?><br>
													<input type="radio" name="payment" value="paypal" <?PHP if($members->payment_type == "paypal"){ echo "CHECKED"; }?>><?PHP echo $photog_paypal_title; ?>
													</td>
												</tr>
												<tr>
													<td bgcolor="#5E85CA" class="data_box"><?PHP echo $photog_paypal_email_title; ?><br>
													<input type="text" name="paypal" value="<?PHP echo $members->paypal_email; ?>" style="width: 250px">
												</tr>
												<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_url_title; ?><br>
													<input type="text" name="url" value="<?PHP echo $members->url; ?>" style="font-size: 13; font-weight: bold; width: 100%; border: 1px solid #000000;" maxlength="150">
												</td>
												</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;"><b><?PHP echo $photog_ip_title; ?></b><br>
										<?PHP
										if($members->ip > 0){
										?>
											<a href="http://www.domaintools.com/<?PHP echo $members->ip; ?>" target="_blank" class="title_links"><?PHP echo $members->ip; ?></a>
										<?PHP } else { ?>
											<?PHP echo $photog_ip_none; ?>
										<?PHP } ?>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_date_title; ?><br>
										
										<table cellpadding="0" cellspacing="0">
											<tr>
												<td>
												<select name="s_month" style="font-size: 11; font-weight: bold; width: 70;">
													<?PHP
													$month_x = 1;
													while($month_x <= 12){
														if(strlen($month_x) < 2){
															$month_x = "0" . $month_x;
														}
													?>
														<option value="<?PHP echo $month_x; ?>" <?PHP if($month_x == $this_month){ echo "selected"; } ?>><?PHP echo $month_x; ?></option>
													<?PHP
													$month_x++;
													}
													?>
												</select>
												</td>
											
												<td>&nbsp;</td>
												
												<td>
												<select name="s_day" style="font-size: 11; font-weight: bold; width: 70;">
													<?PHP
													$day_x = 1;
													while($day_x <= 31){
														if(strlen($day_x) < 2){
															$day_x = "0" . $day_x;
														}
													?>
														<option value="<?PHP echo $day_x; ?>" <?PHP if($day_x == $this_day){ echo "selected"; } ?>><?PHP echo $day_x; ?></option>
													<?PHP
													$day_x++;
													}
													?>
												</select>
												</td>
											
												<td>&nbsp;</td>
											
												<td>
												<select name="s_year" style="font-size: 11; font-weight: bold; width: 110;">
													<?PHP
													$year_x = 2004;
													while($year_x <= 2020){
													?>
														<option value="<?PHP echo $year_x; ?>" <?PHP if($year_x == $this_year){ echo "selected"; } ?>><?PHP echo $year_x; ?></option>
													<?PHP
													$year_x++;
													}
													?>
												</select>
												</td>
												
												<td>&nbsp;</td>
												
												<td><a name="calendar"><a href="#calendar" onclick="window.open('mgr_calendar.php?field_var=s', 'calendar_win', ['HEIGHT=175', 'WIDTH=180', 'dependent']);"><img src="images/mgr_button_calendar.gif" border="0" alt="View Calendar"></td>
												
												<?PHP if($item_id  != "new"){ ?>
												<td><!--&nbsp;&nbsp;&nbsp;Subscription Ends: <?PHP echo $sub_ends; ?>--></td>
												<?PHP } ?>
											</tr>
										</table>									
									</td>
								</tr>
								<?PHP $sContent = $members->notes; ?>
								<?PHP if($setting->editor == 1 and $editor == 1){ ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<?PHP echo $photog_bio_title; ?><br>
								<?PHP
								/////////////////////////////////////////////////
								///////////////ALL NEW EDITOR////////////////////
								/////////////////////////////////////////////////
								if($editor_type == "tinymce"){
								?>
								<script language="javascript" type="text/javascript" src="../editors/tinymce/tiny_mce.js"></script>
								<script language="javascript" type="text/javascript">
  								tinyMCE.init({
    								theme : "advanced",
    								mode: "exact",
    								//relative_urls : true,
    								//remove_script_host : false,
    								//document_base_url : "<?PHP echo $setting->site_url; ?>",
    								convert_urls : false,
    								elements : "article",
    								
    								plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",
    								theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
    								theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
    								theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
    								theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
    								theme_advanced_toolbar_location : "top",
    								theme_advanced_toolbar_align : "left",
    								theme_advanced_statusbar_location : "bottom",
    								theme_advanced_resizing : true, 
    								force_br_newlines : true,
    								content_css : "../styles/<?PHP echo $setting->style; ?>",
    								
    								// Drop lists for link/image/media/template dialogs
    								template_external_list_url : "js/template_list.js",
    								external_link_list_url : "js/link_list.js",
    								external_image_list_url : "js/image_list.js",
    								media_external_list_url : "js/media_list.js",
    								
    								// Replace values for the template plugin
    								template_replace_values : {
    									username : "Some User",
    									staffid : "991234"
    									} 
  								});
							</script>
										<textarea name="article" id="article" rows=4 cols=30>
										<?PHP
										function encodeHTML($sHTML){
											$sHTML=preg_replace("/&/","&amp;",$sHTML);
											$sHTML=preg_replace("/</","&lt;",$sHTML);
											$sHTML=preg_replace("/>/","&gt;",$sHTML);
											return $sHTML;
										}
										if(isset($sContent)) echo encodeHTML($sContent);
										?>
										</textarea>
									</td>
								</tr>
								<?PHP } else { ?>		
										<script language=JavaScript src='../editors/innova/innovaeditor.js'></script>
										<textarea name="article" id="article" rows=4 cols=30>
										<?PHP
										function encodeHTML($sHTML){
											$sHTML=preg_replace("/&/","&amp;",$sHTML);
											$sHTML=preg_replace("/</","&lt;",$sHTML);
											$sHTML=preg_replace("/>/","&gt;",$sHTML);
											return $sHTML;
										}
										if(isset($sContent)) echo encodeHTML($sContent);
										?>
										</textarea>
										<script>
										var oEdit1 = new InnovaEditor("oEdit1");
										oEdit1.REPLACE("article");
										</script>
										<?PHP
											}
										?>
									</td>
								</tr>
								<?PHP } else { ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<?PHP echo $photog_note_title; ?><br>
										<textarea name="article" id="text_box" style="width:100%; height:200; border: 1px solid #000000;" rows="1" cols="20"><?PHP echo $members->notes; ?></textarea>
										<p align="right"><a href="#" onClick="window.open('<?PHP echo $setting->help_tips_link; ?>help_tips.php?pmode=html_tags', 'tips_win', ['HEIGHT=500', 'WIDTH=600', 'scrollbars=yes', 'dependent']);" class="edit_links"><?PHP echo $photog_html_tips; ?></a>
									</td>
								</tr>
								<?PHP } ?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<input type="checkbox" name="status" value="1" <?PHP if($members->status == 1 or $item_id == "new"){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_active_title; ?><br>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<input type="checkbox" name="featured" value="1" <?PHP if($members->featured == 1 or $item_id == "new"){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_featured_title; ?><br>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<input type="checkbox" name="approved" value="1" <?PHP if($members->approved == 1 ){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_approved_title; ?><br>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<input type="checkbox" name="upload_on" value="1" <?PHP if($members->upload_on == 1 or $item_id == "new"){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_allow_title; ?><br>
									</td>
								</tr>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<input type="checkbox" name="edit_on" value="1" <?PHP if($members->edit_on == 1 or $item_id == "new"){ echo "checked"; } ?>><font face="arial" color="#ffffff" style="font-size: 11;"><?PHP echo $photog_edit_title; ?><br>
									</td>
								</tr>
								<?PHP
									include("image_upload_area.php");
									include("file_upload_area.php");
								?>
								<tr>
									<td height="10"></td>
								</tr>
								</form>
								<?PHP
									if($_REQUEST['item_id'] != "new"){
								?>
								<tr>
									<td bgcolor="#5E85CA" class="data_box">
										<?PHP
											$result = mysql_query("SELECT * FROM photo_package where photographer = '$members->id'", $db);
											$photo_rows = mysql_num_rows($result);
										?>
										<?PHP 
										/*
										echo $photog_selling_title; ?> <b><?PHP echo $photo_rows; ?></b> <?PHP echo $photog_selling_titlea; ?>
										<br />
										*/
										?>
										<?PHP if($photo_rows > 0){ ?>
										<a href="actions_photo_package.php?pmode=search&type=photog_id&search=<?PHP echo $members->id; ?>&return=mgr.php?nav=<?PHP echo $photos_return_nav; ?>" class="title_links"><b><u><?PHP echo $photog_viewall_photos;?></b></u></a>
										<?PHP } ?>
										<form name="photo_sales" method="post">
										<?PHP echo "<input type=\"hidden\" value=\"mgr.php?nav=".$nav."&message=saved&item_id=".$item_id."&order_by=".$order_by."&order_type=".$order_type."&search=".$search."&startat=".$startat."&perpage=".$perpage."&page_num=".$page_num."\" name=\"return\">"; ?>
										<?PHP echo $photog_sales_no_sub_title; ?><br />
										<br /><br />
										<table width="100%" border="1" bordercolordark="#89A6DB" bordercolorlight="#5078BF">
											<tr>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_list_date; ?></td>
												<td align="center" bgcolor="#89A6DB"><?PHP echo $photog_list_photo; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_list_item; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_list_price; ?></td>
												<td align="Center" bgcolor="#89A6DB"><b>%</b></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_pay_photog; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_pay_you; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_list_status; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_list_select; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_list_total; ?></td>
											</tr>
										<?PHP
											$unpaid = 0;
											$photog_result = mysql_query("SELECT * FROM photog_sales where photographer  = '$_GET[item_id]' and completed = '1' and download_by = '' and done = '0' order by odate desc", $db);
											$photog_rows = mysql_num_rows($photog_result); 
											while($photog = mysql_fetch_object($photog_result)){
											  
											  //ADDED IN PS371 TO SEE IF ORDER IS IN THE APPROVED STAGE FIRST BEFORE SHOWING SALES
											  $order_status_result = mysql_query("SELECT * FROM visitors where visitor_id  = '$photog->visitor_id' and done = '1'", $db);
											  $order_status_rows = mysql_num_rows($order_status_result); 
											  
												if($photog->p_type == "d"){
													$photo_result = mysql_query("SELECT * FROM uploaded_images where id = '$photog->photo_id'", $db);
													$photo_rows = mysql_num_rows($photo_result);
													$photo = mysql_fetch_object($photo_result);
													$ptype = $photog_sno_digital."<br>";
													$ptype.= $photog_sno_order_id."<br>".$photog->visitor_id;
												} else {
												if($photog->p_type == "s"){
													$sizes_result = mysql_query("SELECT * FROM sizes where id = '$photog->sid'", $db);
													$sizes_rows = mysql_num_rows($sizes_result);
													$sizes = mysql_fetch_object($sizes_result);
													
													$photo_result = mysql_query("SELECT * FROM uploaded_images where id = '$photog->photo_id'", $db);
													$photo_rows = mysql_num_rows($photo_result);
													$photo = mysql_fetch_object($photo_result);
													$ptype = $photog_sno_other_size."<br>";
													$ptype.= $photog_sno_order_id."<br>".$photog->visitor_id;
												} else {
												  if($photog->prid == 1111111111){
												  $ptype = $photog_sno_photo."<br>";
												  $ptype.= $photog_sno_order_id."<br>".$photog->visitor_id;
												  } else { 
													  $print_result = mysql_query("SELECT * FROM prints where id = '$photog->prid'", $db);
													  $print_rows = mysql_num_rows($print_result);
													  $print = mysql_fetch_object($print_result);
													
													  $cart_result = mysql_query("SELECT * FROM carts where visitor_id = '$photog->visitor_id' AND photo_id = '$photog->photo_id' AND prid = '$photog->prid' AND ptype='p'", $db);
													  $cart_rows = mysql_num_rows($cart_result);
													  $cart = mysql_fetch_object($cart_result);
													
													  $photo_result = mysql_query("SELECT * FROM uploaded_images where id = '$photog->photo_id'", $db);
													  $photo_rows = mysql_num_rows($photo_result);
													  $photo = mysql_fetch_object($photo_result);
													  $ptype = $photog_sno_print."<br>";
													  $ptype.= $photog_sno_order_id."<br>".$photog->visitor_id;
													}
												}
											}
										?>
												<?PHP
												if($photog->p_type == "p"){
												  $pricing = $cart->price * $cart->quantity;

												  $payouta = $pricing * ($photog->com_percent / 100);
													
													$payoutb = $pricing - $payouta;
													
													$payouta = round($payouta, 2);
													
													$price_total = $price_total + $pricing;
													$payouta_total = $payouta_total + $payouta;
													$payoutb_total = $payoutb_total + $payoutb;
												} else {
													$payouta = $photog->price * ($photog->com_percent / 100);
													
													$payoutb = $photog->price - $payouta;
													
													$payouta = round($payouta, 2);
													
													$price_total = $price_total + $photog->price;
													$payouta_total = $payouta_total + $payouta;
													$payoutb_total = $payoutb_total + $payoutb;
												}
													//$odate = round(substr($photog->odate, 4, 2)) . "/" . round(substr($photog->odate, 6, 2));
													$odate = round(substr($photog->odate, 4, 2)) . "/" . round(substr($photog->odate, 6, 2)) . "/" . round(substr($photog->odate, 0, 4));
													
													if($photog->status == 0){
														$unpaid = $unpaid + $payoutb;
													}
													
												?>
												<tr>
													<td align="Center"><?PHP echo $odate; ?></td>
													<td align="Center"><!--<?PHP echo $photog->photo_id; ?><br />--><?PHP if(file_exists($stock_photo_path_manager . "i_" . $photo->filename)){ ?><a href="<?PHP echo $stock_photo_path_manager; ?><?PHP echo $photo->filename; ?>" target="_blank"><img src="<?PHP echo $stock_photo_path_manager; ?>i_<?PHP echo $photo->filename; ?>" width="50" border="0"></a><?PHP } else { ?><img src="../images/no_photo.gif" width="50" border="0"><?PHP } ?></td>
													<td align="Center"><?PHP echo $ptype; ?></td>
													<td align="Center"><?PHP echo $currency->sign; ?><?PHP if($photog->p_type == "p"){ echo dollar($pricing); } else { echo dollar($photog->price); } ?></td>
													<td align="Center"><?PHP echo $photog->com_percent; ?>%</td>
													<td align="Center"><?PHP echo $currency->sign; ?><?PHP echo dollar($payoutb); ?></td>
													<td align="Center"><?PHP echo $currency->sign; ?><?PHP echo dollar($payouta); ?></td>
													<td align="Center" nowrap>
													<?PHP 
													if($order_status_rows < 1){
													  echo $photog_payment_pending;
													} else {
													  if($photog->status == 0){ 
													    echo $photog_not_paid."<br /><a href=\"actions_photographers_addon.php?pmode=paid_status&nav=".$nav."&item_id=".$_GET['item_id']."&comid=".$photog->id."&order_by=".$order_by."&order_type=".$order_type."&search=".$search."&startat=".$startat."&perpage=".$perpage."&page_num=".$page_num."\"><font color=\"#FFFF00\">".$photog_mark_paid."</a>";
													  } else { 
													    echo $photog_paid; 
													  } 
												  }
													?>
													 </td>
													 <td align="Center"><input type="checkbox" name="<?PHP echo $photog->id; ?>" value="1"></td>
												   <td align="Center"><?PHP if($photog->duplicate == 1){ echo $photog_duplicate; } ?></td>
												</tr>
										<?PHP
											}
										?>
											<tr>
												<td align="Center" bgcolor="#89A6DB"><b>&nbsp;</b></td>
												<td align="center" bgcolor="#89A6DB"><b>&nbsp;</b></td>
												<td align="center" bgcolor="#89A6DB"><b>&nbsp;</b></td>
												<td align="Center" bgcolor="#89A6DB"><b><?PHP echo $currency->sign; ?><?PHP echo dollar($price_total); ?></b></td>
												<td align="Center" bgcolor="#89A6DB">&nbsp;</td>
												<td align="Center" bgcolor="#89A6DB"><b><?PHP echo $currency->sign; ?><?PHP echo dollar($payoutb_total); ?></b></td>
												<td align="Center" bgcolor="#89A6DB"><b><?PHP echo $currency->sign; ?><?PHP echo dollar($payouta_total); ?></b></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_tot_unpaid; ?><b><?PHP echo $currency->sign; ?><?PHP echo dollar($unpaid); ?></b></td>
												<td align="Center" bgcolor="#89A6DB"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/></td> 
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_sno_total_sales; ?><br><b><?PHP echo $photog_rows; ?></b></td>
											</tr>
											<tr>
											<td colspan="11" align="right"><a href="javascript:mark_hide();"><img src="images/hide_all.gif" border="0"></a>&nbsp;<a href="javascript:mark_paid();"><img src="images/mgr_button_mark_paid_sel.gif" border="0"></a>&nbsp;<a href="javascript:delete_sales_data();"><img src="images/delete_all.gif" border="0"></a></td>
										</tr>
										</form>
										</table>
										<form name="downloads" method="post">
										<?PHP echo "<input type=\"hidden\" value=\"mgr.php?nav=".$nav."&message=saved&item_id=".$item_id."&order_by=".$order_by."&order_type=".$order_type."&search=".$search."&startat=".$startat."&perpage=".$perpage."&page_num=".$page_num."\" name=\"return\">"; ?>
										<?PHP echo $photog_d_area_title; ?><br />
										<table width="100%" border="1" bordercolordark="#89A6DB" bordercolorlight="#5078BF">
											<tr>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_date_title; ?></td>
												<td align="center" bgcolor="#89A6DB"><?PHP echo $photog_d_photo_title; ?></td>
												<td align="center" bgcolor="#89A6DB"><?PHP echo $photog_d_photo_id_title; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_item_title; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_price_title; ?></td>
												<td align="Center" bgcolor="#89A6DB"><b>%</b></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_rate_title; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_pay_photog_title; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_status_title; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_member_title; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_select_title; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_total_title; ?></td>
											</tr>
										<?PHP
											$unpaid1 = 0;
											$photog1_result = mysql_query("SELECT * FROM photog_sales where photographer  = '$item_id' and completed = '1' and download_by > '0' and done = '0' order by odate desc, duplicate", $db);
											$photog1_rows = mysql_num_rows($photog1_result);
											
											while($photog1 = mysql_fetch_object($photog1_result)){
											//$pt1_result = mysql_query("SELECT * FROM visitors WHERE visitor_id = '$photog1->visitor_id' and payment_method = 'Check/Money Order' and check_approval = '0'", $db);
											//$pt1_rows = mysql_num_rows($pt1_result);
										  
										  $members1_result = mysql_query("SELECT * FROM members where id = '$photog1->download_by'", $db);
											$members1 = mysql_fetch_object($members1_result);
											if($photog1->sid > 0){
													$sizes1_result = mysql_query("SELECT * FROM sizes where id = '$photog1->sid'", $db);
													$sizes1_rows = mysql_num_rows($sizes1_result);
													$sizes1 = mysql_fetch_object($sizes1_result);
													
													$photo1_result = mysql_query("SELECT * FROM uploaded_images where id = '$photog1->photo_id'", $db);
													$photo1_rows = mysql_num_rows($photo1_result);
													$photo1 = mysql_fetch_object($photo1_result);
													$ptype = $photog_sno_other_size.":<br>";
													$ptype.= $sizes1->name;
												} else {
													if($photog1->p_type == "d"){
														$photo1_result = mysql_query("SELECT * FROM uploaded_images where id = '$photog1->photo_id'", $db);
														$photo1_rows = mysql_num_rows($photo1_result);
														$photo1 = mysql_fetch_object($photo1_result);
														$ptype = $photog_sno_digital;
													} else {
														$print1_result = mysql_query("SELECT * FROM prints where id = '$photog1->prid'", $db);
														$print1_rows = mysql_num_rows($print1_result);
														$print1 = mysql_fetch_object($print1_result);
													
														$pg1_result = mysql_query("SELECT * FROM uploaded_images where reference = 'photo_package' and reference_id = '$photog1->photo_id'", $db);
														$pg1_rows = mysql_num_rows($pg1_result);
														$pg1 = mysql_fetch_object($pg1_result);
														$ptype = $photog_sno_print;
													}
												}
										?>
												<?PHP
												if($photog1->duplicate == 0){
												if($photog1->com_download_default == "0"){
													$payouta1 = $photog1->price * ($photog1->com_percent / 100);
												} else {
													$payouta1 = "0.00";
												}
												if($photog1->com_download_default != "0"){
													$payoutc1 = $photog1->com_download_default;
												} else {
													$payoutc1 = "$0.00";
												}
													
													$payoutb1 = $photog1->price - $payouta1;
													
													$payouta1 = round($payouta1, 2);
													
													$price_total1 = $price_total1 + $photog1->price;
													$payouta_total1 = $payouta_total1 + $payouta1 + $payoutc1;
													$payoutb_total1 = $payoutb_total1 + $payoutb1;
													$payoutc_total1 = $payoutc_total1 + $payoutc1;
													
													$odate1 = round(substr($photog1->odate, 4, 2)) . "/" . round(substr($photog1->odate, 6, 2));
													
													if($photog1->status == 0){
														$unpaid1 = $unpaid1 + $payouta1 + $payoutc1;
													}
												}
													
												?>
												<tr>
													<td align="Center"><?PHP echo $odate1; ?></td>
													<td align="Center"><?PHP if(file_exists($stock_photo_path_manager."i_" . $photo1->filename)){ ?><a href="<?PHP echo $stock_photo_path_manager; ?>s_<?PHP echo $photo1->filename; ?>" target="_blank"><img src="<?PHP echo $stock_photo_path_manager; ?>i_<?PHP echo $photo1->filename; ?>" width="50" border="0"></a><?PHP } else { ?><img src="../images/no_photo.gif" width="50" border="0"><?PHP } ?></td>
													<td align="Center"><?PHP if($photog1->photo_id > 0){ echo $photog1->photo_id; } else { echo "--"; } ?></td>
													<td align="Center"><?PHP echo $ptype; ?></td>
													<td align="Center"><?PHP if($photog1->duplicate == 0){ echo $currency->sign; echo dollar($photog1->price); } else { echo "----"; } ?></td>
													<td align="Center"><?PHP if($photog1->com_download_default == "0"){ ?><?PHP echo $photog1->com_percent; ?>%<?PHP } else {?>0%<?PHP } ?></td>
													<td align="Center"><?PHP if($photog1->com_download_default != "0"){ ?><?PHP echo $currency->sign; ?><?PHP echo $photog1->com_download_default; ?><?PHP } else { ?>$0.00<?PHP } ?></td>													
													<td align="Center"><?PHP if($photog1->duplicate == 0){ if($photog1->com_download_default == "0"){ ?><?PHP echo $currency->sign; ?><?PHP echo dollar($payouta1); ?><?PHP } else { ?><?PHP echo $currency->sign; ?><?PHP echo dollar($photog1->com_download_default); ?><?PHP } } else { echo "---"; }?></td>
													<td align="Center" nowrap><?PHP if($photog1->duplicate == 0){ ?><?PHP if($photog1->status == 0){ echo $photog_d_not_paid."<br /><a href=\"actions_photographers_addon.php?pmode=paid_status&nav=".$nav."&item_id=".$item_id."&comid=".$photog1->id."&order_by=".$order_by."&order_type=".$order_type."&search=".$search."&startat=".$startat."&perpage=".$perpage."&page_num=".$page_num."\"><font color=\"#FFFF00\">".$photog_d_mark_paid_title."</a>"; } else { echo $photog_d_already_paid_title; } ?><?PHP if($photog1->status == 0){ ?><?PHP echo "</td>"; } else { ?><?PHP echo "<br />"; ?><?PHP echo "<a href=\"actions_photographers_addon.php?pmode=delete_down&nav=".$nav."&item_id=".$item_id."&comid=".$photog1->id."&order_by=".$order_by."&order_type=".$order_type."&search=".$search."&startat=".$startat."&perpage=".$perpage."&page_num=".$page_num."\"><font color=\"#FFFF00\">".$photog_d_hide_title."</a></td>"; ?><?PHP } } else { echo "---</td>"; } ?>
												  <td align="Center"><?PHP echo $members1->name; ?><br><?PHP echo $photog1->ip; ?></td>
												  <td align="Center"><input type="checkbox" name="<?PHP echo $photog1->id; ?>" value="1"></td>
													<td align="Center"><?PHP if($photog1->duplicate == 1){ echo $photog_duplicate; } ?></td>
												</tr>
										<?PHP
											}
										?>
											<tr>
												<td align="Center" bgcolor="#89A6DB"><b>&nbsp;</b></td>
												<td align="center" bgcolor="#89A6DB"><b>&nbsp;</b></td>
												<td align="center" bgcolor="#89A6DB"><b>&nbsp;</b></td>
												<td align="center" bgcolor="#89A6DB"><b>&nbsp;</b></td>
												<td align="Center" bgcolor="#89A6DB"><b><?PHP echo $currency->sign; ?><?PHP echo dollar($price_total1); ?></b></td>
												<td align="Center" bgcolor="#89A6DB">&nbsp;</td>
												<td align="Center" bgcolor="#89A6DB">&nbsp;</td>
												<td align="Center" bgcolor="#89A6DB"><b><?PHP echo $currency->sign; ?><?PHP echo dollar($payouta_total1); ?></b></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_unpaid_title; ?> <b><?PHP echo $currency->sign; ?><?PHP echo dollar($unpaid1); ?></b></td>
											  <td align="Center" bgcolor="#89A6DB"><td align="Center" bgcolor="#89A6DB"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/></td>
											  <td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_total_download_title; ?><br><b><?PHP echo $photog1_rows; ?></b></td>
											</tr>
											<tr>
											<td colspan="12" align="right"><a href="javascript:mark_hide_download();"><img src="images/hide_all.gif" border="0"></a>&nbsp;<a href="javascript:mark_paid_download();"><img src="images/mgr_button_mark_paid_sel.gif" border="0"></a>&nbsp;<a href="javascript:delete_download_data();"><img src="images/delete_all.gif" border="0"></a></td>
											</tr>
										</form>
										</table>
										<form name="uploads" method="post">
										<?PHP echo "<input type=\"hidden\" value=\"mgr.php?nav=".$nav."&message=saved&item_id=".$item_id."&order_by=".$order_by."&order_type=".$order_type."&search=".$search."&startat=".$startat."&perpage=".$perpage."&page_num=".$page_num."\" name=\"return\">"; ?>
										<?PHP echo $photog_u_main_area_title; ?><br />
										<table width="100%" border="1" bordercolordark="#89A6DB" bordercolorlight="#5078BF">
											<tr>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_date_title; ?></td>
												<td align="center" bgcolor="#89A6DB"><?PHP echo $photog_d_photo_title; ?></td>
												<td align="center" bgcolor="#89A6DB"><?PHP echo $photog_d_photo_id_title; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_pay_photog_title; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_status_title; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_select_title; ?></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_total_title; ?></td>
											</tr>
										<?PHP
											$unpaid2 = 0;
											$photog2_result = mysql_query("SELECT * FROM photog_earning where photog_id  = '$item_id' and done = '0' order by id desc", $db);
											$photog2_rows = mysql_num_rows($photog2_result);
											
											while($photog2 = mysql_fetch_object($photog2_result)){
												
													$photo2_result = mysql_query("SELECT * FROM uploaded_images where reference_id = '$photog2->photo_id'", $db);
													$photo2_rows = mysql_num_rows($photo2_result);
													$photo2 = mysql_fetch_object($photo2_result);
											
											$posted = round(substr($photog2->date, 4, 2)) . "/" . round(substr($photog2->date, 6, 2)) . "/" . round(substr($photog2->date, 0, 4));
											$posted_short = round(substr($photog2->date, 4, 2)) . "/" . round(substr($photog2->date, 6, 2));
											
										?>
												<?PHP
													
													$payouta2 = $photog2->earnings;
													$payout_total2 = $payout_total2 + $payouta2;
													if($photog2->status == 0){
														$unpaid2 = $unpaid2 + $payouta2;
													}
													
												?>
												<tr>
													<td align="Center"><?PHP echo $posted_short; ?></td>
													<td align="Center"><?PHP if(file_exists($stock_photo_path_manager."i_" . $photo2->filename)){ ?><a href="<?PHP echo $stock_photo_path_manager; ?>s_<?PHP echo $photo2->filename; ?>" target="_blank"><img src="<?PHP echo $stock_photo_path_manager; ?>i_<?PHP echo $photo2->filename; ?>" width="50" border="0"></a><?PHP } else { ?><img src="../images/no_photo.gif" width="50" border="0"><?PHP } ?></td>
													<td align="Center"><?PHP if($photog2->photo_id > 0){ echo $photog2->photo_id; } else { echo "--"; } ?></td>
													<td align="Center"><?PHP echo $currency->sign; ?><?PHP echo dollar($payouta2); ?></td>
													<td align="Center" nowrap><?PHP if($photog2->status == 0){ echo $photog_d_not_paid."<br /><a href=\"actions_photographers_addon.php?pmode=mark_paid_upload_single&nav=".$nav."&item_id=".$item_id."&comid=".$photog2->id."&order_by=".$order_by."&order_type=".$order_type."&search=".$search."&startat=".$startat."&perpage=".$perpage."&page_num=".$page_num."\"><font color=\"#FFFF00\">".$photog_d_mark_paid_title."</a>"; } else { echo $photog_d_already_paid_title; } ?><?PHP if($photog2->status == 0){ ?><?PHP echo "</td>"; } else { ?><?PHP echo "<br />"; ?><?PHP echo "<a href=\"actions_photographers_addon.php?pmode=mark_hide_upload_single&nav=".$nav."&item_id=".$item_id."&comid=".$photog2->id."&order_by=".$order_by."&order_type=".$order_type."&search=".$search."&startat=".$startat."&perpage=".$perpage."&page_num=".$page_num."\"><font color=\"#FFFF00\">".$photog_d_hide_title."</a></td>"; } ?>
												  <td align="Center"><input type="checkbox" name="<?PHP echo $photog2->id; ?>" value="1"></td>
													<td></td>
												</tr>
										<?PHP
											}
										?>
											<tr>
												<td align="center" bgcolor="#89A6DB"><b>&nbsp;</b></td>
												<td align="center" bgcolor="#89A6DB"><b>&nbsp;</b></td>
												<td align="Center" bgcolor="#89A6DB"><b>&nbsp;</b></td>
												<td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_d_unpaid_title; ?> <b><?PHP echo $currency->sign; ?><?PHP echo dollar($unpaid2); ?></b></td>
											  <td align="Center" bgcolor="#89A6DB"><td align="Center" bgcolor="#89A6DB"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/></td>
											  <td align="Center" bgcolor="#89A6DB"><?PHP echo $photog_u_upload_title; ?><br><b><?PHP echo $photog2_rows; ?></b></td>
											</tr>
											<tr>
											<td colspan="11" align="right"><a href="javascript:mark_hide_upload();"><img src="images/hide_all.gif" border="0"></a>&nbsp;<a href="javascript:mark_paid_upload();"><img src="images/mgr_button_mark_paid_sel.gif" border="0"></a>&nbsp;<a href="javascript:delete_upload_data();"><img src="images/delete_all.gif" border="0"></a></td>
											</tr>
										</form>
										</table>
									</td>
								</tr>
								<?PHP
									}
								?>
								<tr>
									<td height="10"></td>
								</tr>
								<tr>
									<td align="right"><a href="mgr.php?nav=<?PHP echo $nav; ?>&order_by=<?PHP echo $order_by; ?>&order_type=<?PHP echo $order_type; ?>&search=<?PHP echo $search; ?>&startat=<?PHP echo $startat; ?>&perpage=<?PHP echo $perpage; ?>&page_num=<?PHP echo $page_num; ?>"><img src="images/mgr_button_cancel.gif" border="0"></a>&nbsp;<?PHP if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_data();\">"; } ?><img src="images/mgr_button_save.gif" border="0"></a>
									</form>
									<form name="export" action="csv_export.php" method="post" style="margin: 0px; padding: 0px;">
											<?PHP
											//ADDED FOR EXPORT OF ORDERS TO CSV
											$csv_items = $item_id;
											$csv_type = "photographers";
											?>
											<input type="hidden" name="pass" value="<?PHP echo md5($setting->access_id); ?>">
											<input type="hidden" name="return" value="<?PHP echo "mgr.php?nav=".$nav."&order_by=".$order_by."&order_type=".$order_type."&search=".$search."&startat=".$startat."&perpage=".$perpage."&page_num=".$page_num; ?>">
											<input type="hidden" name="csv_table" value="photographers">
											<input type="hidden" name="csv_items" value="<?PHP echo $csv_items; ?>">
											<input type="submit" value="<?PHP echo $csv_export_form_button; ?>">
										</form>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
			<?PHP
				}
			?>
		</tr>
	</table>	
<?PHP
	}
?>
