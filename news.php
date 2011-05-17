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
        <table cellpadding="0" cellspacing="0"><tr><td valign="top">
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
						<?php
							$crumb = $news_crumb_link;
							include("crumbs.php");
						?>
						<tr>
							<td class="index_copy_area" colspan="3" height="4"></td>
						</tr>						
						<tr>
							<td colspan="3" valign="top" height="100%" class="homepage_line">
								<table width="100%" border="0">
									<tr>
										<td height="6"></td>
									</tr>
									<tr>
										<td class="gallery_copy">
											<? copy_area(27,1); ?>
										</td>
									</tr>
									<tr>
										<td style="padding-left: 10px;">
											<table>
											<?
								
												$news_result = mysql_query("SELECT id,title,article,publish_date FROM news where active = '1' order by publish_date desc", $db);
												while($news = mysql_fetch_object($news_result)){
													$posted = round(substr($news->publish_date, 4, 2)) . "/" . round(substr($news->publish_date, 6, 2)) . "/" . round(substr($news->publish_date, 0, 4));
													$posted_short = round(substr($news->publish_date, 4, 2)) . "/" . round(substr($news->publish_date, 6, 2));
													
													$short_article = strip_tags($news->article);
													
													if(strlen($short_article) > 150){
														$trim_article = substr($short_article, 0, 150) . "...";
													}
													else {
														$trim_article = $short_article;
													}
													
													if(strlen($news->title) > 150){
														$trim_title = substr($news->title, 0, 150) . "...";
													}
													else {
														$trim_title = $news->title;
													}
											?>
												<tr>
													<td><b><? echo $posted_short; ?></font></b><br><a href="news_details.php?id=<? echo $news->id; ?>"><? echo $trim_title; ?></a><br><font style="font-size: 11;"><? echo $trim_article; ?></td>
												</tr>
												<tr>
													<td height="8"></td>
												</tr>
											<?
												}
											?>
											</table>
										</td>
									</tr>
                                    <?php
										if(file_exists('rss.php')){
											include('rss.php');
											if($news_feed){
									?>
                                        <tr>
                                            <td align="right"><a href="rss.php?show=news"><img src="images/rss.gif" border="0" /></a></td>
                                        </tr>
                                    <?php
											}
										}
									?>
								</table>
							</td>
						</tr>
					</table>				
				</td>
			</tr>
			<? include("footer.php"); ?>			
		</table>
        </td>
        <td valign="top">
			<?php
				if($pf_feed_status){
					include('pf_feed.php');
				}
			?>
        </td>
        </tr></table>
		</center>
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	