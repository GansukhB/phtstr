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
        <?php 
            $ca_result = mysql_query("SELECT * FROM copy_areas where id = '22'", $db);
						$ca_rows = mysql_num_rows($ca_result);
						$ca = mysql_fetch_object($ca_result);
						$article =  $ca->article;
            
            if($_SESSION['lang'] != 'English')
              $article = $ca->{'article_'.$_SESSION['lang']};
          
            //str_replace('<strong>', '',  $article);
          //echo $article; 
        ?>
          
          <div class="r-left-main">
            <div class="r-news-content">
              <?php 
                $article = str_replace("<span style=\"font-weight: bold;\">", "<div class=\"header1\"><div class=\"icon\" align=\"center\">
<img src=\"images/icon.jpg\">
</div>
<div class=\"text\">", $article);
                $article = str_replace("</span>", "</div></div>", $article);

                echo $article; 
              ?>
            </div>
          </div>
          <div class="r-right-main">
            
          </div>
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
