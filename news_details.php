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
   <?php include("head_navbar.php");?>
	<div class="container">
    <?php include('header.php');?>
      <div id="main">
			<? include("i_gallery_nav.php"); ?>
      <div class="right-main">
											<? //copy_area(27,1); ?>
										


            	<!--news main ehlel-->
            	<div class="news-main">
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
                    <div class="news-content-main">
                      <?
                        $news_result = mysql_query("SELECT * FROM news where id = '$id'", $db);
                        $news = mysql_fetch_object($news_result);
                          $posted = round(substr($news->publish_date, 4, 2)) . "/" . round(substr($news->publish_date, 6, 2)) . "/" . round(substr($news->publish_date, 0, 4));
                          $posted_short = round(substr($news->publish_date, 4, 2)) . "/" . round(substr($news->publish_date, 6, 2));
                      ?>
                      <div class="left"><? echo $posted; ?></div>
                      <div class="right">
												
                          <h1>
											      <? 
                              if($_SESSION['lang'] != "English") 
                                $title = $news->{'title_'.$_SESSION['lang']}; 
                              else $title = $news->title; 
                              echo stripslashes($title);
                            ?>
                            
                          </h1>
																<?
																	$ci_result = mysql_query("SELECT filename,caption FROM uploaded_images where reference = 'news' and reference_id = '$news->id'", $db);
																	$ci_rows = mysql_num_rows($ci_result);
																	
																	if($ci_rows > 0){		
																		echo "<span class=\"copy_photo_area\" style=\"float: right;padding: 5;text-align: center;\">";							
																		while($ci = mysql_fetch_object($ci_result)){
																			echo "<a href=\"uploaded_images/" . $ci->filename . "\" target=\"_new\"><img src=\"uploaded_images/i_" . $ci->filename . "\" border=\"0\" alt=\"" . $ci->caption . "\"></a>";
																			if($ci->caption != ""){
																				echo "<br>" . $ci->caption . "";
																			}
																			echo "<br><br>";
																		}
																		echo "</span>";
																	}
																	$article = $news->article;
                                  if($_SESSION['lang'] != 'English')
                                    $article = $news->{'article_'.$_SESSION['lang']};
																	echo $news->article;
																	
																	
																	$cf_result = mysql_query("SELECT file_text,filename FROM uploaded_files where reference = 'news' and reference_id = '$news->id'", $db);															
																	while($cf = mysql_fetch_object($cf_result)){
																		
																		if($cf->file_text != ""){
																			$file_text = $cf->file_text;
																		}
																		else{
																			$file_text = $cf->filename;
																		}
																		echo "<a href=\"uploaded_files/" . $cf->filename . "\" target=\"_new\">" . $file_text . "</a><br><br>";
																		
																	}
																?>
                      </div>
                  </div>
              </div>
          </div>
                <!--news main tugsgul-->
        </div> <!-- end class right main -->
        <?php include('i_banner.php');?>
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
