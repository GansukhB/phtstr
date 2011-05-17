<?
	session_start();

	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );
	
	// VISITOR ID
	if(!$_SESSION['visitor_id']){
		session_register("visitor_id");
		$_SESSION['visitor_id'] = random_gen(16,"");
	}
	
	//UNSET ANY IMAGE VIEWING
	unset($_SESSION['pub_gid']);
	unset($_SESSION['pub_pid']);
	
	
?>
<html>
	<head>
		<? print($head); ?>
		<center>
		<table cellpadding="0" cellspacing="0" width="765" class="main_table" style="border: 5px solid #<? echo $border_color; ?>;">
			<? include("header.php"); ?>
			<tr>
				<td class="left_nav_header"><? echo $misc_photocat; ?></td>
				<td></td>
				<? include("search_bar.php"); ?>
			</tr>
			<tr>
				<td rowspan="1" valign="top"><? include("i_gallery_nav.php"); ?></td>
				<td background="images/col2_shadow.gif" valign="top"><img src="images/col2_white.gif"></td>
				<td valign="top" height="18">
					<table cellpadding="0" cellspacing="0" width="560" height="100%">
						<tr>
							<td colspan="3" height="5"></td>
						</tr>
						<tr>
							<?php
							$crumb = $news_details_crumb_link;
							include("crumbs.php");
						?>
						</tr>
						<tr>
							<td class="index_copy_area" colspan="3" height="4"></td>
						</tr>						
						<tr>
							<td colspan="3" valign="top" height="100%" class="homepage_line">
								<table width="100%" border="0">
									<tr>
										<td style="padding-left: 10px;padding-right: 10px;">
											<!-- START: Body Area -->
											<table cellpadding="0" cellspacing="0" width="100%">
												<?
													$news_result = mysql_query("SELECT id,title,article,publish_date FROM news where id = '$id'", $db);
													$news = mysql_fetch_object($news_result);
														$posted = round(substr($news->publish_date, 4, 2)) . "/" . round(substr($news->publish_date, 6, 2)) . "/" . round(substr($news->publish_date, 0, 4));
														$posted_short = round(substr($news->publish_date, 4, 2)) . "/" . round(substr($news->publish_date, 6, 2));
												?>
												<tr>
													<td>
														<table width="100%">
															<tr>
																<td height="15" colspan="2"></td>
															</tr>
															<tr>
																<td><font style="font-size: 12px;"><b><? echo $news->title; ?></b></td>
																<td align="right"><b><? echo $posted_short; ?></b></td>
															</tr>
															<tr>
																<td height="8" colspan="2"></td>
															</tr>
															<tr>
																<td colspan="2">
																<?
																	$ci_result = mysql_query("SELECT filename,caption FROM uploaded_images where reference = 'news' and reference_id = '$news->id'", $db);
																	$ci_rows = mysql_num_rows($ci_result);
																	
																	if($ci_rows > 0){		
																		echo "<span class=\"copy_photo_area\" style=\"float: right;padding: 5;text-align: center;\">";							
																		while($ci = mysql_fetch_object($ci_result)){
																			echo "<a href=\"uploaded_images/" . $ci->filename . "\" target=\"_new\"><img src=\"uploaded_images/i_" . $ci->filename . "\" border=\"0\" alt=\"" . $ci->caption . "\"></a>";
																			if($ci->caption != ""){
																				echo "<br>" . $ci->caption . "";
																			}
																			echo "<br><br>";
																		}
																		echo "</span>";
																	}
																	
																	echo $news->article;
																	
																	echo "<br><br>";
																	
																	$cf_result = mysql_query("SELECT file_text,filename FROM uploaded_files where reference = 'news' and reference_id = '$news->id'", $db);															
																	while($cf = mysql_fetch_object($cf_result)){
																		
																		if($cf->file_text != ""){
																			$file_text = $cf->file_text;
																		}
																		else{
																			$file_text = $cf->filename;
																		}
																		echo "<a href=\"uploaded_files/" . $cf->filename . "\" target=\"_new\">" . $file_text . "</a><br><br>";
																		
																	}
																?>
																</td>
															</tr>
															<tr>
																<td height="25" colspan="2"></td>
															</tr>
															<tr>
																<td align="right" colspan="2"><a href="news.php"><?PHP echo $news_details_back_button; ?></a></td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
											<!-- END: Body Area -->
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>				
				</td>
			</tr>
			<? include("footer.php"); ?>			
		</table>
		</center>
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	