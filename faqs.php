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
<? include("header.php"); ?>
<div class="container">
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
          
            $query = "select * from faq_type  ";
            $types = mysql_query($query);
        ?>
          
          <div class="r-left-main">
              <a name="top"></a>
              <?php while($type = mysql_fetch_object($types)): ?>
                <div class="r-news-content">
                    <div class="header1">
                      <div class="icon" align="center">
                      Q
                      </div>
                      <div class="text">
                      <?php
                        $cat_id = $type->id;
                        $category = $type->title;
                        //if($_SESSION['lang'] != 'English')
                        
                        echo $category;
                      ?>
                      
                      </div>
                    </div>
                    <ul class="main">
                      <?php 
                        $query = "select * from faqs where type='$cat_id'";
                        $faq = mysql_query($query);
                        $faqa = $faq;
                        while($q = mysql_fetch_object($faq)):
                      ?>
                          <li><a href="#answer<?php echo $q->id; ?>"><?php echo $q->title; ?></a></li>
                      <?php
                        endwhile;
                      ?>
                    </ul>
                </div>
              <?php endwhile;?>
              
              
              <?php 
                $query = "select * from faqs order by sort ";
                $faq = mysql_query($query);
                while($ans = mysql_fetch_object($faq)):
              ?>
                  <div class="r-news-content">
                    <div class="header2 ">
                      <a name="answer<?php echo $ans->id; ?>"></a>
                      <div class="icon" align="center">
                      A
                      </div>
                      <div class="text">
                        <?php
                          echo $ans->title;
                        ?>
                         
                      </div>
                    </div>
                    <ul class="main">
                      <?php 
                        echo $ans->{'article'};
                      ?>
                      <a href="#top">Go to top</a> 
                    </ul>
                </div>              
              <?php 
                endwhile;
              ?>
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
