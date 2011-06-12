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
    
    <?php include('head_navbar.php'); ?>
	<div class="container">
<?php include('header.php');?>
				<? //include("search_bar.php"); ?>
      <div id="main">
			<? include("i_gallery_nav.php"); ?>
      <div class="right-main">
           	<!--news main ehlel-->
            	<div class="news-main">
                	<!--news header ehlel-->
                  <?php 
                    $query = "SELECT * FROM news where active = '1' and homepage='1' order by publish_date desc";
                    
                    $years = array();
                    $news_result = mysql_query($query, $db);
                    $news_result1 = mysql_query($query, $db);
                    $isadded; 
                    $news_list = array();
                    while($news = mysql_fetch_object($news_result1)){
                      array_push($news_list, $news);
                      $posted = round(substr($news->publish_date, 4, 2)) . "/" . round(substr($news->publish_date, 6, 2)) . "/" . round(substr($news->publish_date, 0, 4));
                      $posted_short = round(substr($news->publish_date, 4, 2)) . "/" . round(substr($news->publish_date, 6, 2));
                      
                      list($day, $month, $year) = explode("/", $posted);
                      
                      if(!$isadded[$year]){
                        $isadded[$year] = true;
                        array_push($years, $year);
                      }
                      
                    }
                    if(isset($_GET['year']))
                    {
                      $show_year = $_GET['year'];                        
                    }
                    else $show_year = $years[0];
                    
                    
                    $month_list = array();
                    $isaddedmonth;
                    
                    foreach($news_list as $news):
                      $posted = round(substr($news->publish_date, 4, 2)) . " " . round(substr($news->publish_date, 6, 2)) . " " . round(substr($news->publish_date, 0, 4));
                      $posted_short = round(substr($news->publish_date, 4, 2)) . "/" . round(substr($news->publish_date, 6, 2));
                      list($month, $day, $year) = explode(" ", $posted);
                      if($year == $show_year && !$isaddedmonth[$month])
                      {
                        $isaddedmonth[$month] = true;
                        array_push($month_list, $month);
                      }
                    endforeach;
                  ?>
                	  <div class="news-header">
                    	<div class="left"><?php echo $top_news; ?></div>
                        <div class="left tab-menu">
                        	<ul>
                            <?php foreach($years as $y): ?>
                              <li><a href="news.php?year=<?php echo $y?>"><?php echo $y; ?></a></li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <?php 
                      foreach($month_list as $i):
                      
                    ?>
                        <div class="news-content-main">
                          <div class="left"><?php echo "$i / $show_year"?><br /></div>
                            <div class="right">
                    <?php 
                              foreach($news_list as $news):
                                $posted = round(substr($news->publish_date, 4, 2)) . " " . round(substr($news->publish_date, 6, 2)) . " " . round(substr($news->publish_date, 0, 4));
                                $posted_short = round(substr($news->publish_date, 4, 2)) . "/" . round(substr($news->publish_date, 6, 2));
                                list($month, $day, $year) = explode(" ", $posted);
                                
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
                                if($show_year == $year && $i == $month):
                    ?>
                                  <div class="news-content">
                                    <h1>
                                      <?php 
                                        $title = $news->title; 
                                        if($_SESSION['lang'] != 'English')
                                        {
                                          $title = $news->{'title_'.$_SESSION['lang']};
                                        }
                                        if($title) echo stripslashes($title);
                                        else echo stripslashes($news->title);
                                      ?>
                                    </h1>
                                      <?php 
                                        $article = $news->article; 
                                        if($_SESSION['lang'] != 'English')
                                        {
                                          $article = $news->{'article_'.$_SESSION['lang']};
                                        }
                                        if($article) echo stripslashes($article);
                                        else echo stripslashes($news->article);
                                      ?>
                                      <a class="news-more" href="news_details.php?id=<?php echo $news->id; ?>"> <?php echo $news_details_crumb_link ; ?>&gt;&gt;</a>
                                  </div>
                    <?php 
                                endif; 
                              endforeach;
                    ?>
                          </div>
                        </div>
                    <?php 
                      endforeach;
                    ?>
                    <?
                        
                      
                      while($news = mysql_fetch_object($news_result)){
                        $posted = round(substr($news->publish_date, 4, 2)) . " " . round(substr($news->publish_date, 6, 2)) . " " . round(substr($news->publish_date, 0, 4));
                        $posted_short = round(substr($news->publish_date, 4, 2)) . "/" . round(substr($news->publish_date, 6, 2));
                        list($day, $month, $year) = explode($posted, " ");
                        
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
                        //echo $posted. ' '.$posted_short; 
                    ?>
                    <!--
                        <div class="news-content-main">
                          <div class="left">DECEMBER 2010 <br> NEWSLETER</div>
                            <div class="right">
                              <div class="news-content">
                                  <h1><?php echo $news->title; ?></h1>
                                    <?php echo $news->article; ?><a class="news-more" href="#"> <?php echo $news_details_crumb_link ; ?>&gt;&gt;</a>
                                </div>
                                <div class="news-content">
                                  <h1>news title</h1>
                                    Premium Templates, Dynamic Swish, Last added, Full site, Flash Site, Dynamic Flash, Full package, Low budget, Zero Downloads, Most Popular, osCommerce Templates, 3 colors, 3D style, Adult, 			Agriculture, Alternative Power, Animals &amp; Pets, Architecture, Art &amp; Photography
                                    <a class="news-more" href="#"> more... &gt;&gt;</a>
                                </div>
                                <div class="news-content">
                                  <h1>news title</h1>
                                    Premium Templates, Dynamic Swish, Last added, Full site, Flash Site, Dynamic Flash, Full package, Low budget, Zero Downloads, Most Popular, osCommerce Templates, 3 colors, 3D style, Adult, 			Agriculture, Alternative Power, Animals &amp; Pets, Architecture, Art &amp; Photography
                                    <a class="news-more" href="#"> more... &gt;&gt;</a>
                                </div>
                            </div>
                        </div>-->
                    <?php 
                      }
                    ?>
                    
                </div>
                <!--news main tugsgul-->
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
