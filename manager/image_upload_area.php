								<?
									if($image_upload != 0){
								?>
									<tr>
										<td height="15"></td>
									</tr>
									<tr>
										<td>
											<table cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<td align="left"><img src="images/mgr_section_header_l.gif"></td>
													<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>IMAGES/PHOTOS</b></td>
													<td align="right"><img src="images/mgr_section_header_r.gif"></td>
												</tr>
											</table>
										</td>
									</tr>
								<?
									}
								?>
								<!-- START : IMAGE UPLOAD -->
								<?
								
									$absolute_image_path = $setting->site_url . "/uploaded_images/";
								
									if($image_upload != 0){
										$image_result = mysql_query("SELECT id,filename FROM uploaded_images where reference = '$reference' and reference_id = '$item_id' order by id", $db);
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
											
											if($x == 5){
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
																<tr>
																	<td>
																		<? if($image_caption == 1){ ?>
																		<a href="#image_area_<? echo $image->id; ?>" onclick="window.open('image_caption_editor.php?id=<? echo $image->id; ?>', 'caption_win', ['HEIGHT=300', 'WIDTH=400', 'scrollbars=yes', 'dependent']);" class="edit_links">&#187; Edit Caption</a><br>
																		<? } ?>
																		<? if($_SESSION['access_type'] !=  "demo"){ ?><a href="mgr_actions.php?pmode=delete_image&id=<? echo $image->id; ?>&item_id=<? echo $item_id; ?>&nav=<? echo $nav; ?>&order_by=<? echo $order_by; ?>" target="_parent" class="edit_links">&#187; Delete</a><? } ?><br>
																		Copy thumbnail image link:<br>
																		<input type="text" value="<? echo $absolute_image_path . "i_" .$image->filename; ?>" style="font-size: 13; font-weight: bold; width: 400; border: 1px solid #000000;" maxlength="400"><br>
																		Copy full image link:<br>
																		<input type="text" value="<? echo $absolute_image_path . $image->filename; ?>" style="font-size: 13; font-weight: bold; width: 400; border: 1px solid #000000;" maxlength="400">
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
															<a href="#image_area_<? echo $image->id; ?>" onclick="window.open('<? echo $absolute_image_path . $image->filename; ?>', 'image_win', ['HEIGHT=<? echo $the_size[1]; ?>', 'WIDTH=<? echo $the_size[0]; ?>', 'dependent']);"><img src="<? echo $image_path; ?>i_<? echo $image->filename; ?>" border="0" <? if($rows >= 2){ echo "width=\"75\""; } ?> style="border: 1px solid #476DB0" name="image_<? echo $image->id; ?>" alt="Click To Enlarge"></a>
														</td>
													</tr>
												</table>
											</td>
																	
								<?		
										$x++;		
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
									<td bgcolor="#5E85CA" class="data_box">
										<font face="arial" color="#ffffff" style="font-size: 11;"><b><? echo $image_area_name; ?></b> (jpg files only)<br>
										<input type="file" name="image" style="font-size: 11; width: 287; border: 1px solid #000000;">
										<br>
										<? if($image_caption == 1){ ?>
										<b>Caption</b> (optional)<br>
										<input type="text" name="image_caption" style="font-size: 11; width: 287; border: 1px solid #000000;" maxlength="200">
										<? } ?>
									</td>
								</tr>
								<? } ?>
								<!-- END : IMAGE UPLOAD -->