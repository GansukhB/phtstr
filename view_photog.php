<?
	session_start();

	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );
	
	$photog_result = mysql_query("SELECT * FROM photographers where id = '" . $photogid . "'", $db);
	$photog = mysql_fetch_object($photog_result);
	
	
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
		<script language=JavaScript src='./js/xns.js'></script>
    <?php echo $script1; ?>
				<? print($head); ?>
        
        <? include("header.php"); ?>
      <div class="container">
			
			
      <div id="main">
				
				
				<? //include("search_bar.php"); ?>
			
				<? include("i_gallery_nav.php"); ?>
        
        <div class="right-main">
          
				<td background="images/col2_shadow.gif" valign="top"><img src="images/col2_white.gif"></td>
				<td valign="top" height="18">
					<table cellpadding="0" cellspacing="0" width="560" >
						<tr>
							<td colspan="3" height="5"></td>
						</tr>
						<?php
							$crumb = $photog_gal_crumb_link;
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
											<?PHP if($photog->status == 1){ ?>
											<?PHP echo $photog_gal_photographer; ?><br><b><? echo $photog->name; ?></b><br><br>
											<?PHP echo $photog_gal_bio; ?><br><? echo $photog->notes; ?><br><br>	
											<?PHP } else { ?>
											<?PHP echo $photog_gal_not_active; ?>
											<?PHP } ?>
										</td>
									</tr>
									<tr>
										<td>
											<? include("i_photog_photos.php"); ?>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>				
				</td>
			</tr>
      </div>
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
