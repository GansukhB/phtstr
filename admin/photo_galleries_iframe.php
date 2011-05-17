<html>
	<head>
		<link rel="stylesheet" href="mgr_style.css">
		<script language="javascript">
		function demo_mode(){
			alert("Sorry. You can not use this feature while in DEMO MODE.")
			return
		}
		function copyit(theField) {
			var tempval=eval("document."+theField)
			tempval.select()
			therange=tempval.createTextRange()
			therange.execCommand("Copy")
		}
		</script>
	</head>
	<body bgcolor="#13387E" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
		<table width="100%" cellpadding="0" cellspacing="0">
		<!-- START : IMAGE UPLOAD -->
		<form action="actions_photo_galleries.php?pmode=set_thumbnail" name="gallery_form" method="post">
		<input type="hidden" name="return" value="photo_galleries_iframe.php?item_id=<? echo $item_id; ?>&reference=<? echo $reference; ?>&image_path=<? echo $image_path; ?>&image_upload=<? echo $image_upload; ?>&nav=<? echo $nav; ?>&image_caption=<? echo $image_caption; ?>">
		<input type="hidden" name="reference" value="<? echo $reference; ?>">
		<input type="hidden" name="item_id" value="<? echo $item_id; ?>">
		<?
		include("config_mgr.php");
			if($image_upload != 0){
				$image_result = mysql_query("SELECT id,filename,thumbnail,photo_title FROM uploaded_images where reference = '$reference' and reference_id = '$item_id' order by id", $db);
				$rows = mysql_num_rows($image_result);
					if($rows != 0){
		?>
				<tr>
					<td>
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
		<?
					}
				$x = 1;
				while($image = mysql_fetch_object($image_result)){
					$the_size = getimagesize($image_path . $image->filename);
					
					if($x == 6){
						echo "</tr><tr>";
						$x = 1;
					}
		?>
					<td align="center" valign="top" bgcolor="#5E85CA" class="data_box">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td align="left" class="options_box"><a name="#image_area_<? echo $image->id; ?>">
									<div id="div_<? echo $image->id; ?>" style="position:relative; top:0px; left:0px;display:block;z-index:1">
									<input type="hidden" name="link_<? echo $image->id; ?>" value="../uploaded_gallery/<? echo $image->filename; ?>">
									<table width="100%" cellpadding="1" cellspacing="1">
										<!--
										<tr>
											<td valign="middle"><input type="radio" name="gallery_thumbnail" value="<? echo $image->id; ?>" style="width: 10px;" onclick="this.form.submit();" <? if($image->thumbnail == 1){ echo "checked"; } ?>></td>
											<td valign="middle" width="100%"><font face="arial" color="#ffffff" style="font-size: 9;"> Thumbnail</td>
										</tr>
										-->
										<tr>
											<td>
												<? if($image_caption == 1){ ?>
												<a href="#image_area_<? echo $image->id; ?>" onclick="window.open('image_details_editor.php?id=<? echo $image->id; ?>&image_path=<?PHP echo $stock_photo_path_manager; ?>', 'caption_win', ['HEIGHT=500', 'WIDTH=400', 'scrollbars=yes', 'dependent']);" class="edit_links">&#187; Edit Details</a><br>
												<? } ?>
												<!--<a href="#" onclick="copyit('gallery_form.link_<? echo $image->id; ?>')" class="edit_links">&#187; Copy Image Link</a><br>-->
												<!--<a href="#image_area_<? echo $image->id; ?>" onclick="window.open('image_viewer.php?img=<? echo $image_path . $image->filename; ?>', 'image_win', ['HEIGHT=<? echo $the_size[1]; ?>', 'WIDTH=<? echo $the_size[0]; ?>', 'dependent']);" class="edit_links">&#187; Enlarge</a><br>-->
												<? if($access_type !=  "demo"){ ?><a href="actions_photo_galleries.php?pmode=delete_image&id=<? echo $image->id; ?>&item_id=<? echo $item_id; ?>&nav=<? echo $nav; ?>&order_by=<? echo $order_by; ?>" target="_parent" class="edit_links">&#187; Delete</a><? } ?><br>
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
									<a href="#image_area_<? echo $image->id; ?>" onclick="window.open('image_viewer.php?img=<? echo $image_path . $image->filename; ?>', 'image_win', ['HEIGHT=<? echo $the_size[1]; ?>', 'WIDTH=<? echo $the_size[0]; ?>', 'dependent']);"><img src="<? echo $image_path; ?>i_<? echo $image->filename; ?>" border="0" <? if($rows >= 2){ echo "width=\"75\""; } ?> style="border: 1px solid #476DB0" name="image_<? echo $image->id; ?>" alt="Click To Enlarge"></a>
								</td>
							</tr>
						</table>
					</td>
											
		<?		
				$x++;		
				}
				if($rows > 5 and $x != 6){
					echo "<td colspan=\"" . (6 - $x) . "\" bgcolor=\"#5E85CA\" class=\"data_box\">&nbsp;</td>";
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
		?>
		</form>
		</table>
	</body>
</html>