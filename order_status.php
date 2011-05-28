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
		<div id="main">
			<? include("i_gallery_nav.php"); ?>
      <div class="right-main">
					<table cellpadding="0" cellspacing="0" width="560" height="100%">
						<tr>
							<td colspan="3" height="5"></td>
						</tr>
						<?php
							$crumb = $order_status_crumb_link;
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
											<? copy_area(32,3); ?>
											<?
											if($_SESSION['sub_member'] && $_SESSION['sub_member'] != ""){
											$order_list_results = mysql_query("SELECT added,order_num FROM visitors where member_id = '" . $_SESSION['sub_member'] . "'", $db);
											while($order_list = mysql_fetch_object($order_list_results)){
												$added = round(substr($order_list->added, 4, 2)) . "/" . round(substr($order_list->added, 6, 2)) . "/" . round(substr($order_list->added, 0, 4));
												echo "<b>" . $order_status_date . ":</b> " . $added . "<br>";
												echo "<b>" . $order_status_order . ":</b> <a href=\"download.php?order=" . $order_list->order_num . "\">" . $order_list->order_num . "</a><br><br>";
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
			</div> <!-- end class right-main -->
      
      <?php include('i_banner.php'); ?>
      
      </div><!-- end main id-->
      </div> <!-- end container class -->
      <? include("footer.php"); ?>
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	
