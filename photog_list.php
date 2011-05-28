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
    <?php echo $script1; ?>
		<? print($head); ?>
		<div class="container">
			<? include("header.php"); ?>
				<? //include("search_bar.php"); ?>
      <div id="main">
			<? include("i_gallery_nav.php"); ?>
      <div class="right-main">
							<?php
							$crumb = $photog_list_crumb_link;
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
											<?
												$photogl_result = mysql_query("SELECT id,name FROM photographers where status = '1'", $db);
												$photogl_rows = mysql_num_rows($photogl_result);
												
													if($photogl_rows > 1){													
													while($photogl = mysql_fetch_object($photogl_result)){
												?>
												<span class="gallery_nav" style="padding-left: 5px;padding-right: 0px;" nowrap><img src="images/nav_arrow.gif" align="middle"><b><a href="view_photog.php?photogid=<? echo $photogl->id; ?>"><? echo $photogl->name; ?></a></b> </span><br /><br />
												<?php
														}
													}
												?>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>				
				</td>
			</tr>
			      </div> <!-- end class right main -->
      </div> <!-- end id main -->
    </div> <!-- end container -->
    <? include("footer.php"); ?>	
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	
