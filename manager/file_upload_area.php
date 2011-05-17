								<?
									if($file_upload != 0){
								?>
									<tr>
										<td height="15"></td>
									</tr>
									<tr>
										<td>
											<table cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<td align="left"><img src="images/mgr_section_header_l.gif"></td>
													<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>FILES</b></td>
													<td align="right"><img src="images/mgr_section_header_r.gif"></td>
												</tr>
											</table>
										</td>
									</tr>
								<?
									}
								?>
								<!-- START : FILE UPLOAD -->
								<?
									$absolute_file_path = $setting->site_url . "/uploaded_files/";
								  
									if($file_upload != 0){
										$file_result = mysql_query("SELECT id,filename,file_text FROM uploaded_files where reference = '$reference' and reference_id = '$item_id' order by id", $db);
										$rows = mysql_num_rows($file_result);
											if($rows != 0){
								?>
											<tr>
												<td bgcolor="#5E85CA" class="data_box">
													<table width="100%" cellpadding="0" cellspacing="0">
													<?
													
														while($file = mysql_fetch_object($file_result)){
															if($file->file_text != ""){
																if(strlen($file->file_text) > 30){
																	$filename = substr($file->file_text, 0, 30) . "...";
																}
																else {
																	$filename = $file->file_text;
																}
															}
															else {
																if(strlen($file->filename) > 30){
																	$filename = substr($file->filename, 0, 30) . "...";
																}
																else {
																	$filename = $file->filename;
																}
															}
															    $new_filename = str_replace(" ", "_", $file->filename);
													?>
														<tr>
															<td class="data_box_files" nowrap><img src="images/mgr_file_icon.gif"></td>
															<td class="data_box_files" width="100%" nowrap><a href="<? echo $file_path . $new_filename; ?>" target="_new" class="edit_links"><? echo $filename; ?></a> </td>
															<!--<td class="data_box_files" nowrap>&nbsp;&nbsp;</td>
															<td class="data_box_files" nowrap><a href="#"  onclick="window.open('file_details_editor.php?id=<? echo $file->id; ?>&file_active=<? echo $file_active; ?>', 'caption_win', ['HEIGHT=300', 'WIDTH=400', 'scrollbars=yes', 'dependent']);" class="edit_links">Edit Details</a> </td>-->
															<td class="data_box_files" nowrap></td>
															<td class="data_box_files" nowrap><a href="<? echo $file_path . $new_filename; ?>" target="_new" class="edit_links">View</a> </td>
															<td class="data_box_files" nowrap>&nbsp;|&nbsp;</td>
															<td class="data_box_files" nowrap><? if($access_type !=  "demo"){ ?><a href="mgr_actions.php?pmode=delete_file&id=<? echo $file->id; ?>&item_id=<? echo $_GET['item_id']; ?>&nav=<? echo $_GET['nav']; ?>&order_by=<? echo $order_by; ?>&order_type=<? echo $order_type; ?>" class="edit_links">Delete</a> <? } ?></td>
														</tr>
														<tr>
															<td colspan="6" class="data_box_files" width="100%" nowrap>Copy Link Location:<br>
																<input type="text" value="<? echo $absolute_file_path . $new_filename; ?>" style="font-size: 13; font-weight: bold; width: 400; border: 1px solid #000000;" maxlength="400">
															</td>
														</tr>
													<?
														}
													?>
													</table>
												</td>
											</tr>							
								<?			
											}
										if($rows < $file_upload){
											if($file_area_name == ""){
												$file_area_name = "Add File";
											}
								?>
									<tr>
										<td bgcolor="#5E85CA" class="data_box">
											<font face="arial" color="#ffffff" style="font-size: 11;"><b><? echo $file_area_name; ?></b><br>
											<input type="file" name="fileup" style="font-size: 11; width: 287; border: 1px solid #000000;"><br>
											<font face="arial" color="#ffffff" style="font-size: 11;"><b>File Text</b> (optional)<br>
											<input type="text" name="file_text" style="font-size: 11; width: 287; border: 1px solid #000000;" maxlength="100">
										</td>
									</tr>
								<?
										}
									}
								?>
								<!-- END : FILE UPLOAD -->