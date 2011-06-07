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

  <link rel="stylesheet" href="styles/css-gal.css" />
  <?php echo $script1; ?>
    <script type="text/javascript" >
 /*   function blah(){
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
  </script>
<script type="text/javascript" >
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

  <? include("header.php"); ?>
  
    
		<div class="galery-main">
      <div class="margin">
        			<? include("i_gallery_nav.php"); ?>
      </div>
      <div class="right-main1">
        
        <div class="contents">
          <?php 
            $gallery_id = $_GET['gid'];
            $pckg_id =$_GET['pid'];
            
            $qry = "select * from photo_package where id='$pckg_id'";
            $rslt = mysql_query($qry);
            $package = mysql_fetch_object($rslt);
            
            $similar_result = array();
            //echo $query;
            function get_img_id($pckg_id){
              $query = "select id from uploaded_images where reference_id='$pckg_id' ";
              $result = mysql_query($query);
              $id = mysql_fetch_object($result);
              return $id->id;
            }
            
            $keyword_text = $package->keywords;
            $keywords = explode(",", $keyword_text);
            //print_r($keywords);
            shuffle($keywords);
            
            $is_added = array();
            $limit = 10;
            
            foreach($keywords as $word)
            {
              $searcher = "SELECT * FROM photo_package where active = '1' and photog_show = '1' and keywords like '%$word%'";
              $result = mysql_query($searcher);
              
              while($photo = mysql_fetch_object($result) )
              {
                if(count($similar_result) == $limit)
                {
                  goto end;
                }
                if(!$is_added[$photo->id] && $photo->id != $package->id)
                {
                  array_push($similar_result, $photo);
                  $is_added[$photo->id] = true;
                }
                
              }
              
            }
            end:
          ?>
          
          <?php $imagepage = "image_sq.php?gal_size=".$_SESSION['gal_size']."&src="; foreach($similar_result as $thmb): ?>
          <!--
            <div class="image-content">
              <div class="image"><a href="details.php?gid=<?php $gallery_id; ?>&pid=<?php echo $thmb->id; ?>"><img src="image_sq.php?src=<?php echo get_img_id($thmb->id); ?>"></a></div>
              <div align="center" class="title"><?php echo $thmb->title; ?></div>
            </div>
            -->
            <div class="galery-content">
                	<div align="center" class="image">
                    
                    <?php
                      mod_photolink($thmb->id,$thmb->gallery_id, $thmb->title,"","photo_links"); ?>
                      <img src="<? echo $imagepage . get_img_id($thmb->id); ?>" class="photos" border="0" class="images" />
                    </a>
                  </div>
                     
                      <div align="center" class="title">
                        <?php echo $thmb->title; ?>
                      </div>
                      <div class="descreption">
                        <div align="right" class="left">
                          
                          <a href="public_actions.php?pmode=add_lightbox&ptype=d&gid=<? echo $thmb->gallery_id; ?>&sgid=<? echo $sgid; ?>&pid=<? echo $thmb->id; ?>" class="photo_links">
                          <!--
                          <a onclick="javascript:show_light();" onmouseout="hide_light();">-->
                          <img src="images/icon-show1.png"></a>
                          
                        </div>
                        <div align="left" class="right"><a href="similar.php?gid=<?php echo $thmb->gallery_id;  ?>&pid=<?php echo $thmb->id; ?>" ><img src="images/icon-show2.png"></a></div>
                        
                        
                      </div>
                    
                </div>
          <?php endforeach; ?>
          
          
        </div>
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
