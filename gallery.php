<?
	session_start();
	
	include( "database.php" );
	include( "functions.php" );
	
	# CHECK TO MAKE SURE THE GALLERY ISN'T PRIVATE : IF IT IS REDIRECT TO THE CORRECT PAGE
	$gal_result = mysql_query("SELECT * FROM photo_galleries where id = '" . $_GET['gid'] . "'", $db);
	$gal_rows = mysql_num_rows($gal_result);
	$gal = mysql_fetch_object($gal_result);

	if($gal->pub_pri == 1){
		//header("location: pri.php?gal=" . $gal->rdmcode . "&gid=" . $gal->id);
		exit;
	}
//ADDED IN PS350 TO CHECK TO SEE IF THE GALLERY REQUIRES MEMBERSHIP
	if($gal->free == 1){
		if($gal->free == 1 && $_SESSION['sub_type'] != "free"){
			$no_view = 1;
			$mes = "galfree";
			$t = "f";
		} else {
		if($gal->monthly == 1 && $_SESSION['sub_type'] == "monthly"){
			$no_view = 0;
			} else {
			if($gal->yearly == 1 && $_SESSION['sub_type'] == "yearly"){
			$no_view = 0;
			} else {
			if($gal->free == 1 && $_SESSION['sub_type'] == "free")
			$no_view = 0;
			}
		}
	}
}
	if($gal->monthly == 1){
		if($gal->monthly == 1 && $_SESSION['sub_type'] != "monthly"){
			$no_view = 1;
			$mes = "galmonthly";
			$t = "m";
		} else {
		if($gal->monthly == 1 && $_SESSION['sub_type'] == "monthly"){
			$no_view = 0;
			} else {
			if($gal->yearly == 1 && $_SESSION['sub_type'] == "yearly"){
			$no_view = 0;
			} else {
			if($gal->free == 1 && $_SESSION['sub_type'] == "free")
			$no_view = 0;
			}
		}
	}
}
	if($gal->yearly == 1){
		if($gal->yearly == 1 && $_SESSION['sub_type'] != "yearly"){
			$no_view = 1;
			$mes = "galyearly";
			$t = "y";
		} else {
		if($gal->monthly == 1 && $_SESSION['sub_type'] == "monthly"){
			$no_view = 0;
			} else {
			if($gal->yearly == 1 && $_SESSION['sub_type'] == "yearly"){
			$no_view = 0;
			} else {
			if($gal->free == 1 && $_SESSION['sub_type'] == "free")
			$no_view = 0;
			}
		}
	}
}
// REDIRECT TO SIGNUP PAGE IF IT DOES REQUIRE MEMBERSHIP
	if($no_view == 1){
	session_register("pub_gid");
	$_SESSION['pub_gid'] = $gal->id;
	header("location: subscribe.php?t=$t&message=$mes");
	exit;
	}
							
	
	if($gal->title == ""){
	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE
	} else {
	$page_title       = $gal->title;
	}
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	//if($gal->description == ""){
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	//} else {
	//$meta_description = $gal->description;
	//}
	
	include( "config_public.php" );
	
	if($_GET['gid']){
		$gid = $_GET['gid'];
	} else {
		$gid = 99999999999999999999999999;
	}
	
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
    <!--
		<script language=JavaScript src='./js/xns.js'></script>
		<script language=javascript type="text/javascript">
    	function NewWindow(page, name, w, h, location, scroll) {
        var winl = (screen.width - w) / 2;
        var wint = (screen.height - h) / 2;
        winprops = 'height='+h+',width='+w+',location='+location+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable'
        win = window.open(page, name, winprops)
        if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
    	}
	</script>
-->
  <link rel="stylesheet" href="styles/css-gal.css" type="text/css" />
  <?php echo $script1; ?>
  <!--
    <script type="text/javascript" >
  /*  function blah(){
      alert('hehe');
      
    }
    function hov(){	
      //blah();
       $(this).parents(".galery-content .image").parents(".galery-content").children(".image-hover-main").show();
    }
    function out(){
      $(this).parents(".image-hover-main").hide();
    }
    $(document).ready(function() {
      //$(".galery-content .image img").hover(hov);
      //$(".image-hover-main .image-hover").mouseout(out);
    }); */
  </script>  -->
<script type="text/javascript">
  $(document).ready(function() {
    $(".nav li a").hover(function(){
      $(".nav li ul").show();
    });
    $(".nav li ul a").mouseout(function(){
      $(".nav li ul").hide();
    });
  });
</script>
	<? print($head); ?>
  
  <?php include("head_navbar.php"); ?>
  <? include("header.php"); ?>
  
    
		<div class="galery-main">
      <div class="margin">
        			<? include("i_gallery_nav.php"); ?>
      </div>
      <div class="right-main1">
			  <div class="g-header">
          
          <!--
          <div style="font-size:19px; " class="left" >
            <style>.g-header strong a{color: black;}</style>
            
            <strong>
            
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
                        echo "</a>";
											}
												//echo " <img src=\"images/nav_arrow.gif\" align=\"absmiddle\" /> ";
											} else {
												$pri_gal1_result = mysql_query("SELECT pub_pri,rdmcode FROM photo_galleries where id = '$key'", $db);
												$pri_gal1 = mysql_fetch_object($pri_gal1_result);
												if($pri_gal1->pub_pri != 0){
												echo "<a href=\"pri.php?gal=" . $pri_gal1->rdmcode . "&gid=" . $key . "\" class=\"crumb_links\">" . $value . "</a> ";
											} else {
												mod_gallerylink($value,$key,"crumb_links"); echo "</a>";
												}
											}
											$thru++;
										}
									} 
								?>
              </strong>
          </div>-->
          
         
                
            <div class="right" style=" ">
              <style>a{ color: yellow;}</style>
              
              <div align="right">Hover image view:
              <?php 
                $return = selfURL();
                $return = str_replace(array("&"), array("and"), $return);
                  if($setting->hover_usr == 1 && $setting->hover_on == 1){
                    if(!$_SESSION['visitor_hover']){
                      ?>
                      <a href="public_actions.php?pmode=hover_on&return=<?php echo $return; ?>" style="color: #ffd400;">OFF</a>
                      <?
                    } else {
                      ?>
                      <a href="public_actions.php?pmode=hover_off&return=<?php echo $return; ?>" style="color: #ffd400;">ON</a>
                      <?
                    }
                  } ?>
              </div>
                <div align="right" >Show: 
                  
                  <?php 
                    $perpage = $setting->perpage;
                      if(isset($_GET['perpage']))
                      {
                        $_SESSION['perpage'] = $_GET['perpage'];
                        $perpage = $_SESSION['perpage'];
                      }
											if(isset($_SESSION['perpage']))
                      {
                        $perpage=$_SESSION['perpage'];
                        
                      }
                  ?>
                  <?php if($perpage == 50){ ?>
                    50  
                  <?php } else { ?>
                    <a href="<?php echo selfURL().'&perpage=50'?>" style="color: #ffd400;"> 50</a>
                  <?php }?>|
                  <?php if($perpage == 100){ ?>
                    100 
                  <?php } else { ?>
                    <a href="<?php echo selfURL().'&perpage=100'?>" style="color: #ffd400;"> 100</a>
                  <?php }?>|
                   <?php if($perpage == 150){ ?>
                    150 | 
                  <?php } else { ?>
                    <a href="<?php echo selfURL().'&perpage=150'?>" style="color: #ffd400;"> 150</a>
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
                          <a href="<?php echo selfURL().'&gal_size=small'?>" style="color: #ffd400;"> Small</a>
                        <?php }?> | 
                        <?php if($multi == 2){ ?>
                          Large
                        <?php } else { ?>
                          <a href="<?php echo selfURL().'&gal_size=large'?>" style="color: #ffd400;"> Large</a>
                        <?php }?>
                        
                </div>
            </div>
        </div>
          
          <style>

            .galery-content {
              float:left;
              width: <?php echo $multi*150 ?>px;
              margin-left:10px;
              margin-bottom:40px;
              border-bottom:solid 1pz #CCCCCC;
              height: <?php echo $multi*150 ?>px;
              //
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
            	
              <?PHP
							if($_GET['sort_by'] != ""){
								   $sort_by = $_GET['sort_by'];
								  } else {
								    //$sort_by = $gal->sort_by;
                   $sort_by = "date";
								  }
								 
							if($_GET['sort_order'] != ""){
								   $sort_order = $_GET['sort_order'];
								  } else {
								   $sort_order = $gal->sort_order;
								  }
								  
								  if($sort_by == "id"){
								  	$order_by = "id";
								  }
								  if($sort_by == "title"){
								  	$order_by = "title";
								  }
								  if($sort_by == "date"){
								  	$order_by = "id";
								  }
								  if($sort_by == "popular"){
								  	$order_by = "code";
								  }
								  if($sort_by == "random"){
								  	$order_by = "rand()";
								  }
								  if($sort_order == "ascending"){
								  	$order = "";
								  } else {
								  	$order = "desc";
								  }
							?>
             
								
										<?PHP
											$perpage = $setting->perpage;
                      if(isset($_GET['perpage']))
                      {
                        $_SESSION['perpage'] = $_GET['perpage'];
                      }
											if(isset($_SESSION['perpage']))
                      {
                        $perpage=$_SESSION['perpage'];
                        
                      }
											# CHECK TO SEE IF THE CURRENT PAGE IS SET
											if($_GET['page_num']){											
												$page_num = $_GET['page_num'];
											} else {
												$page_num = 1;
											}
											# CALCULATE THE STARTING RECORD						
											$startrecord = ($page_num == 1) ? 0 : (($page_num - 1) * $perpage);
											
												$pg_result = mysql_query("SELECT id FROM photo_package where active = '1' and photog_show = '1' and (gallery_id = '$gid' or other_galleries LIKE '%,$gid,%') order by $order_by " . $order);
												$package_rows = mysql_num_rows($pg_result);
												while($pg = mysql_fetch_object($pg_result)){
													$search_array2.="$pg->id,";
												}
												
												$package_result = mysql_query("SELECT id,title,description,code,gallery_id FROM photo_package WHERE active = '1' and photog_show = '1' and (gallery_id = '$gid' or other_galleries LIKE '%,$gid,%') order by $order_by ". ( $order_by == 'rand()' ? ' limit 0,'.$perpage : $order."  LIMIT $startrecord,$perpage" ), $db);
												session_register("imagenav");
												$_SESSION['imagenav'] = $search_array2;
												$_SESSION['imagenav'] = substr($_SESSION['imagenav'], 0, -1);		
											
											if($package_rows > 0){
											?>
												<?php
													if($gal->description){
														//print ($gal->description);
													} else {
														//copy_area(10,2);
													}													
												?>	
											<?
											} else {
												if($gal->description){
												print ($gal->description);
												}
												if($setting->no_photo_message == 1){											
												echo $gallery_no_photo_message;
												}
											}
										?>
										
											<?PHP if($package_rows > 0){ ?>
											<?PHP include("i_gallery_photos.php"); ?>
											<?PHP } ?>
										

               
      </div>
        
      </div> <!-- end class right-main -->
    </div>
    
      
      <? include("footer.php"); ?>
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	
