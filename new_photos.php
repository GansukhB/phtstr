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
	
	unset($_SESSION['imagenav']);
?>
<html>
	<head>
		<script language=JavaScript src='./js/xns.js'></script>
    <?php echo $script1; ?>
	<? print($head); ?>
		<div class="container">
			<? include("header.php"); ?>
				<? //include("search_bar.php"); ?>
      <div id="main">
			<? include("i_gallery_nav.php"); ?>
      <div class="right-main">
        
				<td valign="top" height="18">
					<table cellpadding="0" cellspacing="0" width="560" height="100%">
						<?php
							$crumb = $newest_crumb_link;
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
											<? copy_area(17,2); ?>
										</td>
									</tr>
									<tr>
										<td>
											<? include("i_new_photos.php"); ?>
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
