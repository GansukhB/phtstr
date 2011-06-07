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
	
	if($lightbox){
	unset($_SESSION['lightbox_id']);
	session_register("lightbox_id");
	$_SESSION['lightbox_id'] = $lightbox;
	}
	
	//Unset any image storage details
	unset($_SESSION['imagenav']);
	
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
        <!--r-left-main ehlel-->
            	<div class="r-left-main">
                  <div class="r-content-main">
                    	<div align="center"><h2><?php echo $lightbox_my_lightbox; ?></h2></div>
                      <?									
                        $lightbox_group_result = mysql_query("SELECT id,name FROM lightbox_group WHERE member_id = '" . $_SESSION['sub_member'] . "'", $db);
                        $lightbox_group_result_copy = $lightbox_group_result;
                        $lightbox_group_result_move = $lightbox_group_result;
                      ?>
                        <ul class="menu1">
                          <li>
                          <? 	
                            echo "<form method=\"post\" name=\"select\" style=\"height: 20px; padding-top: 0;\" action=''>";
                            //echo $lightbox_select_one;
                            echo "<select name='cat' onchange=\"location=document.select.cat.options[document.select.cat.selectedIndex].value;\" style=\"width:70px; margin-top: 0; height: 20px;;\">
                              <option value=\"lightbox.php\">" . $lightbox_select_lightbox . "</option>";
                            while($lightbox_group = mysql_fetch_array($lightbox_group_result)) {
                              if($lightbox_group[id] == $_SESSION['lightbox_id']){
                                echo "<option selected value=\"lightbox.php?lightbox=" . $lightbox_group[id] . "\">" . $lightbox_group[name] . "</option><BR>";
                              } else {
                                echo "<option value=\"lightbox.php?lightbox=" . $lightbox_group[id] . "\">" . $lightbox_group[name] . "</option><BR>";
                              }
                            }
                              echo "</select></form>";
                            ?>
                            
                            
                          </li>
                        	  
                            
                            <li><a href="#" class="addthis_button">Share</a>
                              <script type="text/javascript">//var addthis_config = {"data_track_clickback":true};</script>
                              <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4de827332cfbe4db"></script>
                            </li>
                            <li><a href="<?php echo selfURL(); ?>&action=rename">Rename</a></li>
                            <li><a href="public_actions.php?pmode=delete_lightbox_group">Delete</a> |</li>
                              
                            <li><a href="#" onclick="javascript: document.getElementById('point').value= '<?php echo $item->reference_id; ?>'; document.getElementById('action').value= 'remove'; document.lightbox.submit();">Remove</a></li>
                            <li style="padding: 0 8px 0 0px; ">
                              <div class="share-main">
                                <div class="share icon">
                                  <a href="#" class="icon" style="padding: 0 8px 0 8px;">Copy</a>
                                  
                                  <div class="pop-up" style="display: none;">
                                    <div class="pop-up-header"></div>
                                    <ul class="pop-up-main">
                                      <?php 
                                        $lightbox_group_result_copy = mysql_query("SELECT id,name FROM lightbox_group WHERE member_id = '" . $_SESSION['sub_member'] . "'", $db);
                                        while($item = mysql_fetch_object($lightbox_group_result_copy) ):
                                      ?>
                                        <li><a href="#" onclick="javascript: document.getElementById('point').value= '<?php echo $item->id; ?>'; document.getElementById('action').value= 'copy'; document.lightbox.submit();">
                                          <?php echo $item->name; ?></a></li>
                                      <?php endwhile; ?>
                                    </ul>
                                    <div class="pop-up-bottom"></div>
                                  </div>
                                </div>
                              </div>
                            </li>
                            <li style="padding: 0 0px 0 8px;">
                              <div class="share-main1">
                                <div class="share1">
                                  <a href="#" class="icon" style="padding: 0 8px 0 8px;">Move</a>
                                  
                                  <div class="pop-up" style="display: none;">
                                    <div class="pop-up-header"></div>
                                    <ul class="pop-up-main">
                                      <?php 
                                        $lightbox_group_result_copy = mysql_query("SELECT id,name FROM lightbox_group WHERE member_id = '" . $_SESSION['sub_member'] . "'", $db);
                                        while($item = mysql_fetch_object($lightbox_group_result_copy) ):
                                      ?>
                                        <li><a href="#" onclick="javascript: document.getElementById('point').value= '<?php echo $item->id; ?>'; document.getElementById('action').value= 'move'; document.lightbox.submit();">
                                          <?php echo $item->name; ?></a></li>
                                      <?php endwhile; ?>
                                    </ul>
                                    <div class="pop-up-bottom"></div>
                                  </div>
                                </div>
                              </div>
                            </li>
                        </ul>
                     <div class="lighbox-main"> 
                     
                      <form name="lightbox" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <?php
                                echo $_SESSION['mem_sub'];
                                  $list = $_POST['photos'];
                                  $action = $_POST['action'];
                                  
                                  if($action == 'remove')
                                  {
                                    $point = $_SESSION['lightbox_id'];
                                    foreach($list as $item){
                                      $query = "DELETE FROM lightbox WHERE photo_id='$item' and reference_id='$point'";
                                      $result = mysql_query($query);
                                      
                                    }
                                  }
                                  if($action == 'move')
                                  {
                                    $point = $_POST['point'];
                                    foreach($list as $item){
                                      $query = "UPDATE lightbox SET reference_id='$point' WHERE photo_id='$item'";
                                      $result = mysql_query($query);
                                      
                                    }
                                  }
                                  if($action == 'copy')
                                  {
                                    $point = $_POST['point'];
                                    $user_id = $_SESSION['sub_member'];
                                    
                                    foreach($list as $item){
                                      $query = "INSERT INTO lightbox (member_id, photo_id, ptype, reference_id) values ('$user_id', '$item', 'd', '$point');";
                                      $result = mysql_query($query);
                                      
                                    }
                                  }
                                  $lightbox_result = mysql_query("SELECT id,photo_id FROM lightbox where reference_id = '" . $_SESSION['lightbox_id'] . "' and member_id = '" . $_SESSION['sub_member'] . "' order by id desc", $db);
                                  $lightbox_rows = mysql_num_rows($lightbox_result);
                                  
                                  $lightbox_name_result = mysql_query("SELECT name FROM lightbox_group WHERE id = '" . $_SESSION['lightbox_id'] . "'", $db);
                                  $lightbox_name = mysql_fetch_object($lightbox_name_result);
                                  if($_SESSION['lightbox_id'] AND $lightbox_rows <= 0){
                                  
                                  if($lightbox_name->name){
                                  echo "<tr><td style=\"padding: 0px 0px 0px 20px\"><b>" . $lightbox_page_name . $lightbox_name->name . "</b></td></tr>";
                                      }
                                  echo "<tr><td style=\"padding: 0px 0px 0px 20px\">" . $lightbox_no_image . "</td></tr>";
                                  if($_SESSION['lightbox_id']){
                                ?>
                                      <tr>
                                      <td style="padding: 0px 0px 0px 20px"><br /><b><a href="public_actions.php?pmode=delete_lightbox_group"><?PHP echo $lightbox_delete_it_now; ?>(<? echo $lightbox_name->name; ?>)</a></b></td>
                                      </tr>
                                <? } ?>
              
                                <?
                                } else {
                                        if($lightbox_name->name){
                                          //echo  $lightbox_lightbox_name . $lightbox_name->name ;
                                        }
                                        while($lightbox = mysql_fetch_object($lightbox_result)){
                                                          
                                        $package_result = mysql_query("SELECT id,title,description,gallery_id FROM photo_package where id = '$lightbox->photo_id'", $db);
                                        $package = mysql_fetch_object($package_result);
                                        
                                        $photo_result = mysql_query("SELECT id,filename FROM uploaded_images where reference = 'photo_package' and reference_id = '$package->id' order by id desc", $db);
                                        $photo = mysql_fetch_object($photo_result);
                                
                                ?>
                                <? if($lightbox_rows > 0 && $photo->filename){ ?>
                                  <div class="box-content" style="height:116px;">
                                      <? if($setting->show_watermark_thumb == 1){ ?>
                                      <a href="details.php?gid=<? echo $package->gallery_id; ?>&sgid=<? echo $sgid; ?>&pid=<? echo $package->id; ?>">
                                        <img src="thumb_mark.php?i=<? echo $photo->id; ?>" ><?PHP //echo $lightbox_click_for_details; ?></a>
                                        <!--<? if($setting->hover_on == 1 && !$_SESSION['visitor_hover']){ ?><? if($setting->show_watermark_hover == 1){ ?> onmouseover="trailOn('hover_mark.php?i=<? echo $ids2[$key]; ?>','<? echo $title2; ?>','<? echo $ids41[$key]; ?>','','','','1','<? echo $ids71[$key]; ?>','<? echo $ids81[$key]; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } else { ?> onmouseover="trailOn('image_pop.php?src=<? echo $photo->id; ?>','<? echo $package->title; ?>','<? echo $package->description; ?>','','','','1','<? echo $ids71[$key]; ?>','<? echo $ids81[$key]; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } ?> onmouseout="hidetrail();" <? } ?> -->
                                      <? } else { ?>
                                      <a href="details.php?gid=<? echo $package->gallery_id; ?>&sgid=<? echo $sgid; ?>&pid=<? echo $package->id; ?>">
                                        <img width="91" src="image_sq.php?src=<? echo $photo->id; ?>" />
                                        <!-- <? if($setting->hover_on == 1 && !$_SESSION['visitor_hover']){ ?><? if($setting->show_watermark_hover == 1){ ?> onmouseover="trailOn('hover_mark.php?i=<? echo $ids2[$key]; ?>','<? echo $title2; ?>','<? echo $ids41[$key]; ?>','','','','1','<? echo $ids71[$key]; ?>','<? echo $ids81[$key]; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } else { ?> onmouseover="trailOn('image_pop.php?src=<? echo $photo->id; ?>','<? echo $package->title; ?>','<? echo $package->description; ?>','','','','1','<? echo $ids71[$key]; ?>','<? echo $ids81[$key]; ?>','<?php echo $flvsample; ?>','<?php echo $sample_video_path; ?>');"<? } ?> onmouseout="hidetrail();" <? } ?>--> 
                                        <?PHP //echo $lightbox_click_for_details; ?></a>
                                      <? } ?>
                                      <a href="public_actions.php?pmode=remove_lightbox&lid=<? //echo $lightbox->id; ?>"><?PHP //echo $lightbox_remove_photo; ?></a>
                                      
                                      <? //if($package->title){ ?><?PHP //echo $lightbox_photo_title; ?><? //echo $package->title; ?><? //} ?>
                                      <? //if($package->description){ ?><?PHP //echo $lightbox_photo_description; ?><? //echo $package->description; } ?>
                                      <center><input type="checkbox" name="photos[]" value="<?php echo $package->id; ?>"/></center>
                                  </div> 
                                    
                                    <? } ?>
                                      <? }
                                      if($lightbox_rows > 0){ ?>	
                                      
                                      <a href="public_actions.php?pmode=delete_lightbox"><?PHP //echo $lightbox_delete_all_photos; ?></a>
                                      <a href="public_actions.php?pmode=delete_lightbox_group"><?PHP //echo $lightbox_delete_this_lightbox; ?><? //echo $lightbox_name->name; ?></a>
                                    
                                    <? } 
                                  } ?>
                        
                        <input type="hidden" id="action" name="action" >
                        <input type="hidden" id="point" name="point" >
                      </form>
                      <div style="clear:both;"></div>
                      <?php if($_GET['action'] == 'rename'): ?>
                        <form name="create" action="public_actions.php?pmode=rename_lightbox&id=<?php echo $_GET['lightbox']; ?>" method="post">
                          <td width="95%" align="left"><font face="arial" style="font-size: 11;"><?PHP echo 'Rename'; ?><br />
                          <input type="text" name="name" value="" style="font-size: 13; font-weight: bold; width: 100; border: 1px solid #000000;"  maxlength="50"> <input type="submit" value="<?PHP echo $lightbox_go_button; ?>" class="go_button">										
                          
                        </form>
                      <?php else: ?>
                        <form name="create" action="public_actions.php?pmode=create_lightbox" method="post">
                          <td width="95%" align="left"><font face="arial" style="font-size: 11;"><?PHP echo $lightbox_create_new; ?><br />
                          <input type="text" name="name" value="" style="font-size: 13; font-weight: bold; width: 100; border: 1px solid #000000;"  maxlength="50"> <input type="submit" value="<?PHP echo $lightbox_go_button; ?>" class="go_button">										
                          
                        </form>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <!--r-left-main tugsgul-->
                <!--r-right-main ehlel-->
                <div class="r-right-main">
                	
                </div>
                <!--r-right-main tugsgul-->
                
                <!--most content ehlel-->
                <?php include('i_nav_photo.php'); ?>
        
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
