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
	
	//UNSET ANY IMAGE VIEWING
	unset($_SESSION['pub_gid']);
	unset($_SESSION['pub_pid']);
	
?>
<html>
	<head>
		<script language=JavaScript src='./js/xns.js'></script>
    <?php echo $script1; ?>
	<? print($head); ?>
  <div class="container">
			<? include("header.php"); ?>
			
      <div id="main">
				
				
				<? //include("search_bar.php"); ?>
			
				<? include("i_gallery_nav.php"); ?>
        
        <div class="right-main">
          <?php $crumb = $popular_crumb_link; ?>
            <?php include('crumbs.php'); ?>
					 
          						<? include("i_popular_photos.php"); ?>
						
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
