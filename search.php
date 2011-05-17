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
	
	//GET SEARCH DATA AND CHECK IT FOR BAD CHARACTERS
	if($_POST['search'] != ""){
		$my_search = $_POST['search'];
	}
	if($_GET['search'] != ""){
		$my_search = $_GET['search'];
	}
	$replace_char = array("%20", "+", "\"", ">", "<", "'", "%22", "%3E");
	$my_search = str_replace($replace_char, "", $my_search);
	$my_search = trim($my_search);
	
	//GET MATCH TYPE DATA FROM SEARCH BAR AND SAVE IT AS A SESSION
	if($_POST['match_type'] != ""){
		$match_type = $_POST['match_type'];
	}
	if($_GET['match_type'] != ""){
		$match_type = $_GET['match_type'];
	}
	if(!$match_type){
		$match_type = "all";
	}
	//UNSET ANY PREVIOUS NEXT BUTTON
	unset($_SESSION['imagenav']);
	//UNSET ANY SEARCH BAR DATA
	unset($_SESSION['search_match_type']);
	//GET ANY NEW CHANGES IN THE SEARCH BAR DATA
	session_register("search_match_type");
	$_SESSION['search_match_type'] = $match_type;
	
	//UNSET ANY IMAGE VIEWING
	unset($_SESSION['pub_gid']);
	unset($_SESSION['pub_pid']);
	
?>
<html>
	<head>
		<script language=JavaScript src='./js/xns.js'></script>
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
						<tr>
							<?php
							$crumb = $search_gal_crumb_link;
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
										<td height="6"></td>
									</tr>
									<tr>
										<td class="gallery_copy">
											<? copy_area(29,2); ?>
										</td>
									</tr>
									<tr>
										<td>
											<?php
												if(!empty($my_search)){
											?>
											<? include("i_search_photos.php"); ?>
											<?php
												}
											?>
										</td>
									</tr>
									<tr>
										<td style="padding: 10px;"></td>
									</tr>
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