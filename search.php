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
  if(isset($_POST['category']))
  {
    $search_form_method = "post";
  }
  else $search_form_method = "get";
  
  //echo $search_form_method;
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
    <link rel="stylesheet" href="styles/css-gal.css" />
    <?php echo $script1; ?>
<? print($head); ?>
  <? include("header.php"); ?>
    
		<div class="galery-main">
      <div class="margin">
        			<? include("i_gallery_nav.php"); ?>
      </div>
      <div class="right-main1">
						  <div class="g-header">
          <div style="font-size:19px; " class="left"><strong>
            <style>.g-header strong a{color: black;}</style>
             <?php
									if($_GET['gid']){
										$crumb_array_name = array();
										//$crumb_array_id = array();
										function crumbs($gid){
											global $db, $crumb_array_name, $crumb_array_id;
											
											$ca_result = mysql_query("SELECT id,title,nest_under FROM photo_galleries where id = '$gid'", $db);
											$ca_rows = mysql_num_rows($ca_result);
											$ca = mysql_fetch_object($ca_result);
											
											if($ca_rows){
												$crumb_array_name[$ca->id] = $ca->title;
												$gid = $ca->nest_under;	
												if($ca->nest_under != 0){											
													crumbs($gid);
												}
											}
										}
										crumbs($_GET['gid']);
										$thru = 1;
										$total_crumbs = count($crumb_array_name);
										
										$curgal = mod_clean($crumb_array_name[$_GET['gid']]);
										
										foreach(array_reverse($crumb_array_name,1) as $key => $value){
											
											if($thru < $total_crumbs){
												$pri_gal_result = mysql_query("SELECT pub_pri,rdmcode FROM photo_galleries where id = '$key'", $db);
												$pri_gal = mysql_fetch_object($pri_gal_result);
												if($pri_gal->pub_pri != 0){
												echo "<a href=\"pri.php?gal=" . $pri_gal->rdmcode . "&gid=" . $key . "\" class=\"crumb_links\">" . $value . "</a>";
											} else {
												mod_gallerylink($value,$key,"crumb_links");
											}
												//echo " <img src=\"images/nav_arrow.gif\" align=\"absmiddle\" /> ";
											} else {
												$pri_gal1_result = mysql_query("SELECT pub_pri,rdmcode FROM photo_galleries where id = '$key'", $db);
												$pri_gal1 = mysql_fetch_object($pri_gal1_result);
												if($pri_gal1->pub_pri != 0){
												echo "<a href=\"pri.php?gal=" . $pri_gal1->rdmcode . "&gid=" . $key . "\" class=\"crumb_links\">" . $value . "</a> ";
											} else {
												mod_gallerylink($value,$key,"crumb_links");
												}
											}
											$thru++;
										}
									} else {
										echo "$crumb";
									}
								?>
              </strong></div>
          
         
                
            <div class="right">
              <div align="right">Hover image view:
          <?php 
          if($setting->hover_usr == 1 && $setting->hover_on == 1){
						if(!$_SESSION['visitor_hover']){
							?>
							<a href="public_actions.php?pmode=hover_on&return=<?php echo selfURL(); ?>">OFF</a>
							<?
						} else {
							?>
							<a href="public_actions.php?pmode=hover_off&return=<?php echo selfURL(); ?>">ON</a>
							<?
						}
					} ?>
          </div>
                <div align="right">Show: 
                  <?php 
                    $perpage = $setting->perpage;
                      if(isset($_GET['perpage']))
                      {
                        $_SESSION['perpage'] = $_GET['perpage'];
                      }
											if(isset($_SESSION['perpage']))
                      {
                        $perpage=$_SESSION['perpage'];
                        
                      }
                  ?>
                  <?php if($perpage == 50){ ?>
                    50  
                  <?php } else { ?>
                    <a href="<?php echo selfURL().'&perpage=50'?>"> 50</a>
                  <?php }?>|
                  <?php if($perpage == 100){ ?>
                    100 
                  <?php } else { ?>
                    <a href="<?php echo selfURL().'&perpage=100'?>"> 100</a>
                  <?php }?>|
                   <?php if($perpage == 150){ ?>
                    150 | 
                  <?php } else { ?>
                    <a href="<?php echo selfURL().'&perpage=150'?>"> 150</a>
                  <?php }?>
                     Size:                      
                       <?php 
                        $multi = 1;
                        if(isset($_GET['gal_size']))  
                        {
                          $_SESSION['gal_size'] = $_GET['gal_size'];
                        }
                        if($_SESSION['gal_size'])
                        {
                          if($_SESSION['gal_size'] != 'small')
                            $multi = 2;
                          else $milti = 1;
                        }
                      ?>
                        <?php if($multi == 1){ ?>
                          Small
                        <?php } else { ?>
                          <a href="<?php echo selfURL().'&gal_size=small'?>"> Small</a>
                        <?php }?> | 
                        <?php if($multi == 2){ ?>
                          Large
                        <?php } else { ?>
                          <a href="<?php echo selfURL().'&gal_size=large'?>"> Large</a>
                        <?php }?>
                        
                </div>
            </div>
      <style>

        .galery-content {
          float:left;
          width: <?php echo $multi*150 ?>px;
          margin-left:10px;
          margin-bottom:40px;
          border-bottom:solid 1pz #CCCCCC;
          //height: <?php echo $multi*150 ?>px;
          height: auto;
        }
        .galery-content .image {
          width:100%;
          height:auto;
          float:left;
          overflow:hidden;
        }
        .galery-content .image img{
          max-height: <?php echo $multi* 130?>px;
          max-width:100%;
          border:solid 1px #939598;
        }
      </style>        
            <div class="contents">
              
                  <? copy_area(29,2); ?>
										<?php
												  if( 1){//!empty($my_search)){
                        ?>
                        <? 
                          include("i_search_photos.php"); 
                          
                        ?>
                        <?php
                          }
                        ?>
            </div>
        </div>
                        
</div>
											
									
       </div> <!-- end class right-main -->
       
       
       
       
      
      <? include("footer.php"); ?>
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	
