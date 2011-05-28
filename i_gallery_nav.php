<?PHP if($setting->menu_click == 1){ ?>
<script language="javascript">
function onItemSelectHandler (o_item) {
	if (o_item.a_config[3]) {
		o_item.o_root.toggle(o_item.n_id);
		window.location = o_item.a_config[1];
		return false;
	}
	return true;
}
</script>
<?PHP } else { ?>
<script language="javascript">
function onItemSelectHandler (o_item) {
	return true;
}
</script>
<?PHP } ?>

<!--
<div name="gallery_nav" id="gallery_nav" nowrap>
	<table cellpadding="0" cellspacing="0" width="200" style="border: 1px solid #eeeeee;">
	<?
	  if($_SESSION['lang'] == ""){
	  session_register("lang");
		$_SESSION['lang'] = $setting->lang;
		}
		$return_url = selfurl();
		$replace_char = array(">", "<", "\"");
		$return_url = str_replace($replace_char, "", $return_url);
		
		$currency_result = mysql_query("SELECT sign FROM currency where active = '1'", $db);
		$currency = mysql_fetch_object($currency_result);
		
		if(file_exists("photog_main.php")){
			$pmphp = 1;
		} else {
			$pmphp = 0;
		}
		
		$photo_result = mysql_query("SELECT gallery_id FROM photo_package where active = '1'", $db);
		while($photo = mysql_fetch_object($photo_result)){
			if(!${"photo_" . $photo->gallery_id}){
				${"photo_" . $photo->gallery_id} = 1;
			} else {
				${"photo_" . $photo->gallery_id} = ${"photo_" . $photo->gallery_id} + 1;
			}
		}
		
		
		if(!$setting->show_private){
			$extrasql = " and pub_pri = '0'";
		} else {
			$extrasql = "";
		}
	  
	  if($setting->show_tree == 1){
	  	?>
	  	<tr>
			<td style="padding-left: 0px;" class="gallery_nav">
			<?
			//include("tree.php");
			?>
			</td>
		</tr>
	<? 
	} else {
		$gallery_result = mysql_query("SELECT id,title,pub_pri,rdmcode FROM photo_galleries where active = '1' and nest_under = '0'" . $extrasql . " order by galorder", $db);
		while($gallery = mysql_fetch_object($gallery_result)){
			if($_GET['gid'] == $gallery->id){
				$current_gallery_name = $gallery->title;
			}
			
			if(!${"photo_" . $gallery->id}){
				${"photo_" . $gallery->id} = 0;
			}
	?>
		<tr>
			<td class="nav_div" style="padding-left: 0px;">
			<span class="gallery_nav" style="padding-left: 5px;padding-right: 0px;" nowrap><img src="images/nav_arrow.gif" align="middle">
				<?php
					if($gallery->pub_pri == 1){
						echo "<a href=\"pri.php?gid=$gallery->id&gal=$gallery->rdmcode\" class=\"gallery_nav\">$gallery->title</a></span>";
					} else {
						//echo "<a href=\"gallery.php?gid=$gallery->id\" class=\"gallery_nav\">$gallery->title</a></span>";
						
						mod_gallerylink($gallery->title,$gallery->id,"gallery_nav");
					}
				?>
			</span>
			<span style="font-size: 9px; color: #939393;">
			<?php
				if($setting->show_num == 1){ 
					echo "(";
					
					imgcount($gallery->id,1,0,$item_id,0,$setting->show_private);
					//$pf = $photo_rows_final + ${"photo_" . $gallery->id};
					//echo $pf;
					echo $photo_rows_final;
					
					echo ")";
				}
				$photo_rows_final = 0;
			?>
			</span>
			</td>
		</tr>
	<?
		}
	}
	if($setting->leftbox1 == 1){
		?>
		<tr>
			<td height="8"></td>
		</tr>
		<tr>
			<td class="sub_banner">
				<?PHP copy_title(3); ?>
			</td>
		</tr>
		<tr>
			<td align="left" style="padding: 5px 5px 5px 10px;">
		<?PHP 
		copy_area(3,2);
		?>
			</td>
		</tr>
	<?	
	}
	if($_SESSION['sub_member']){
							$member_end_result = mysql_query("SELECT added,sub_length,info FROM members where id = '" . $_SESSION['sub_member'] . "'", $db);
							$member_end = mysql_fetch_object($member_end_result);
							
							$this_day = substr($member_end->added, 6, 2);
							$this_month = substr($member_end->added, 4, 2);
							$this_year = substr($member_end->added, 0, 4);
							
														
							if($member_end->sub_length == "Y"){
								$addmonths = 12;
							} else {
								$addmonths = 1;
							}
							
							$basedate = strtotime($member_end->added);
							$mydate = strtotime("+$addmonths month", $basedate);							
							$future_month = date("m/d/Y", $mydate);	
						}		
	?>
	<tr>
			<td height="8"></td>
		</tr>
		<tr>
			<td class="sub_banner">
				<?PHP echo $left_options; ?>
        
			</td>
		</tr>
		<tr>
							<td align="left" nowrap style="padding: 5px 5px 5px 10px;"><a href="cart.php" class="search_bar_links"><?PHP echo $left_view_cart; ?></a> <? if($_SESSION['photog_id']){ ?> <br> <a href="photog_main.php" class="search_bar_links"><?PHP echo $left_photog_area; ?></a><? } else if($pmphp == 1){ ?> <br> <a href="photog_login.php" class="search_bar_links"><?PHP echo $left_photog_login; ?></a> <? } ?><?php if($setting->allow_subs_month != 0 or $setting->allow_subs != 0 or $setting->allow_sub_free != 0){ ?><? if($_SESSION['sub_member']){ ?> <br> <a href="public_actions.php?pmode=logout" class="search_bar_links"><?PHP echo $left_logout; ?></a><? } else { ?> <br> <a href="login.php" class="search_bar_links"><?PHP echo $left_login; ?></a><? } }?>
              
              
              
              <br>
              
              <a href="./photographer/mgr.php" class="search_bar_links">Зурагчин</a><br />
							<hr width="95%">
							<?
							if($_SESSION['sub_member']){
							?>
							<?PHP echo $left_logged; ?><? echo $_SESSION['mem_name']; ?><br>
							<?
						}
						 if($_SESSION['sub_member']){
							if($_SESSION['mem_down_limit'] > 0 && $member_end->sub_length != "F"){
							?>
							<?PHP echo $left_download; ?>
							<?
							if($_SESSION['mem_down_limit'] == 99999){
							?>
							<?PHP echo $left_unlimited; ?>
							<? 
							} else { 
							echo $_SESSION['mem_down_limit'];
							}
							?>
							<br>
							<?PHP echo $left_expired; ?>
							<? 
							echo $future_month; 
							?>
							<br>
							<hr width="95%">
							<?
							} else {
								if($member_end->sub_length != "F"){
							?>
							<b><a href="renew_full.php" class="search_bar_links"><?PHP echo $left_renew; ?></a></b><br>
							<hr width="95%">
							<?
						} 
					}
							if($member_end->sub_length == "F"){ 
							?>
							<?PHP echo $left_account_free; ?><? if($setting->allow_subs == 1 or $setting->allow_subs_month == 1){ ?><br /><a href="renew_full.php"><?PHP echo $left_upgrade; ?></a><? } ?><br>
							<hr width="95%">
							<? 
						}
						if(file_exists("lightbox.php")){
							?>
							<a href="lightbox.php" class="search_bar_links"><?PHP echo $left_lightbox; ?></a>
							<? 
						}
						$order_status_results = mysql_query("SELECT id FROM visitors where member_id = '" . $_SESSION['sub_member'] . "'", $db);
						$order_status_rows = mysql_num_rows($order_status_results);
						if($order_status_rows > 0){
							?>
							<br><a href="order_status.php" class="search_bar_links"><?PHP echo $left_orders; ?></a>
							<? 
						}
							?>
					    <br><a href="my_details.php" class="search_bar_links"><?PHP echo $left_details; ?></a>
							<?
							 if($member_end->info != ""){
							?>
							<br><a href="my_info.php" class="search_bar_links"><?PHP echo $left_info; ?></a>
							<?
						}
					}
					?>
				</td>
			</tr>
			<tr>
				<td>
				<table>
					<tr>
						<td>
							<?PHP
					if($setting->hover_usr == 1 && $setting->hover_on == 1){
						if(!$_SESSION['visitor_hover']){
							?>
							<form style="margin: 0px; padding: 0px;" method="post" action="public_actions.php?pmode=hover_on">
							<input type="hidden" name="return" value="<? echo $return_url; ?>">
							<?PHP echo $left_hover; ?></td><td><input type="submit" value="<?PHP echo $left_on; ?>" class="go_button2"></td>
							<?
						} else {
							?>
							<form style="margin: 0px; padding: 0px;" method="post" action="public_actions.php?pmode=hover_off">
							<input type="hidden" name="return" value="<? echo $return_url; ?>">
							<?PHP echo $left_hover; ?></td><td><input type="submit" value="<?PHP echo $left_off; ?>" class="go_button2"></td>
							<?
						}
					}
				if(file_exists("swf/featured.swf")){
					if($setting->flash_thumb_on == 1 or $setting->flash_featured_on == 1 or $setting->flashthumbs == 1 or $setting->flashsamples == 1){
						echo "</form>";
						echo "</tr>";
						echo "<tr><td>";
						if(!$_SESSION['visitor_flash']){
							?>
							<form style="margin: 0px; padding: 0px;" method="post" action="public_actions.php?pmode=flash_on">
							<input type="hidden" name="return" value="<? echo $return_url; ?>">
							<?PHP echo $left_flash; ?></td><td><input type="submit" value="<?PHP echo $left_on; ?>" class="go_button2"></td>
							<?
						} else {
							?>
							<form style="margin: 0px; padding: 0px;" method="post" action="public_actions.php?pmode=flash_off">
							<input type="hidden" name="return" value="<? echo $return_url; ?>">
							<?PHP echo $left_flash; ?></td><td><input type="submit" value="<?PHP echo $left_off; ?>" class="go_button2"></td>
							<?
						}
					}
				}
					?>
					    </form>
					  </td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap style="padding: 5px 5px 5px 10px;">
							<br>
					  <?PHP if($setting->multi_lang == 1){ ?>
					  <form name="select_lang" method="post" action="public_actions.php?pmode=select_lang">
					  <input type="hidden" name="return" value="<? echo $return_url; ?>">
					  <?PHP echo $left_select_language; ?><br>
						<select name="lang" style="font-size: 10; font-weight: bold; width: 150; border: 1px solid #000000;">
						<?
							$language_dir = "language";
							$l_real_dir = realpath($language_dir);
							$l_dir = opendir($l_real_dir);
							// LOOP THROUGH THE PLUGINS DIRECTORY
							while(false !== ($file = readdir($l_dir))){
							$lfile[] = $file;
							}
							//SORT THE CSS FILES IN THE ARRAY
							sort($lfile);
							//GO THROUGH THE ARRAY AND GET FILENAMES
							foreach($lfile as $key => $value){
							//IF FILENAME IS . OR .. DO NO SHOW IN THE LIST
							$fname = strip_ext($lfile[$key]);
							if($fname != ".." && $fname != "."){
								if($_SESSION['lang'] == $fname){
									echo "<option selected>" . $fname . "</option>";
								} elseif(trim($fname) != "") {
			            echo "<option>" . $fname . "</option>";
								}
							}
						}
						?>
						</select>
					  <input type="submit" value="<?PHP echo $left_go_button; ?>" class="go_button2">
						</form>
					<? } ?>
				</td>
			</tr>
            <?php
				if($setting->show_abanner and $setting->abanner_name){
					$top_banner= explode("-",$setting->abanner_name);
					if($top_banner[1] >= 100){
			?>
			<tr>
			   <td align="center">
				<hr />
				</td>
			</tr>
			<?php
					}
				}
			?>
		<?PHP
				if($setting->leftbox2 == 1){
		?>
		<tr>
			<td height="8"></td>
		</tr>
		<tr>
			<td class="sub_banner">
				<?PHP copy_title(4); ?>
			</td>
		</tr>
		<tr>
			<td align="left" style="padding: 5px 5px 5px 10px;">
		<?PHP 
		copy_area(4,2);
		?>
			</td>
		</tr>
	<?PHP
	}
		include("counter.php") ?>
				<?PHP
				 if($setting->show_stats == 1){ 
				 ?>
		<tr>
			<td height="8"></td>
		</tr>
		<tr>
			<td class="sub_banner">
				<?PHP echo $left_statistics; ?>
        
			</td>
		</tr>
		<td>
								<table border="0" width="100%" style="border-style:solid; border-width:1px; border-color:#E8E8E8;">
								<?
								if($setting->allow_subs_month == 1 or $setting->allow_subs == 1 or $setting->allow_sub_free == 1){
							  ?>
								<tr>
								  <td style="border-style:solid; border-width:1px; border-color:#E8E8E8;">
							<?PHP echo $left_members; ?></td><td style="border-style:solid; border-width:1px; border-color:#E8E8E8;"><? echo $memberstat; ?>
									</td>
								</tr>
							<? } ?>
							<? if($pmphp == 1){ ?>
							  <tr>
							    <td style="border-style:solid; border-width:1px; border-color:#E8E8E8;">
						<?PHP echo $left_photographers; ?></td><td style="border-style:solid; border-width:1px; border-color:#E8E8E8;"><? echo $photogstat; ?>
							    </td>
							   </tr>
							<? } ?>
							   <tr>
							   	<td style="border-style:solid; border-width:1px; border-color:#E8E8E8;">
							<?PHP echo $left_photos; ?></td><td style="border-style:solid; border-width:1px; border-color:#E8E8E8;"><? echo $photostat; ?>
								  </td>
								</tr>
								<tr>
									<td style="border-style:solid; border-width:1px; border-color:#E8E8E8;">
							<?PHP echo $left_visitors; ?></td><td style="border-style:solid; border-width:1px; border-color:#E8E8E8;"><?PHP echo $count_display; ?>
								  </td>
								</tr>
							</table>
						</td>
					</tr>
		<?PHP
        }
     if($setting->leftbox3 == 1){
		?>
		<tr>
			<td height="8"></td>
		</tr>
		<tr>
			<td class="sub_banner">
				<?PHP copy_title(5); ?>
			</td>
		</tr>
		<tr>
			<td align="left" style="padding: 5px 5px 5px 10px;">
		<?PHP 
		copy_area(5,2);
		?>
			</td>
		</tr>
	<?	
	}
		$photogl_result = mysql_query("SELECT id,name FROM photographers where status = '1' and featured = '1' order by rand() limit 10", $db);
		$photogl_rows = mysql_num_rows($photogl_result);
		
		if($photogl_rows > 0){
	?>
		<tr>
			<td height="8"></td>
		</tr>
		<tr>
			<td class="sub_banner">
				<?PHP echo $left_featured_photogs; ?>
			</td>
		</tr>
		<?php
			
			while($photogl = mysql_fetch_object($photogl_result)){
		?>
			<tr>
				<td class="nav_div" style="padding-left: 0px;"><span class="gallery_nav" style="padding-left: 5px;padding-right: 0px;" nowrap><img src="images/nav_arrow.gif" align="middle"><a href="view_photog.php?photogid=<? echo $photogl->id; ?>" class="gallery_nav"><? echo $photogl->name; ?></a> </span><span style="font-size: 9px; color: #939393;"></td>
			</tr>
		<?php
				}
		?>
		<tr>
			<td class="nav_div" style="padding-left: 0px;"><span class="gallery_nav" style="padding-left: 5px;padding-right: 0px;" nowrap><img src="images/nav_arrow.gif" align="middle"><a href="photog_list.php" class="gallery_nav"><?PHP echo $left_more_photographers; ?></a> </span><span style="font-size: 9px; color: #939393;"></td>
		</tr>		
		<?PHP
			}
		if($setting->leftbox4 == 1){
		?>
		<tr>
			<td height="8"></td>
		</tr>
		<tr>
			<td class="sub_banner">
				<?PHP copy_title(6); ?>
			</td>
		</tr>
		<tr>
			<td align="left" style="padding: 5px 5px 5px 10px;">
		<?PHP 
		copy_area(6,2);
		?>
			</td>
		</tr>
	<?PHP	
	}
		if($setting->allow_subs_month == 1){
	?>
		<tr>
			<td height="10"></td>
		</tr>
		<tr>
			<td class="sub_banner">
				<?PHP echo $left_month1; ?><? if($setting->down_limit_m == 99999){ echo $left_unlimited; ?><? } else { ?><? echo $setting->down_limit_m; } ?><?PHP echo $left_month2; ?><? echo $currency->sign; ?><? echo $setting->sub_price_month; ?><?PHP echo $left_month3; ?><a href="subscribe.php?t=m" class="white_link"><?PHP echo $left_signup; ?></a> 
			</td>
		</tr>
	<?
		}
		if($setting->allow_subs == 1){
	?>
		<tr>
			<td height="10"></td>
		</tr>
		<tr>
			<td class="sub_banner">
				<?PHP echo $left_year1; ?><? if($setting->down_limit_y == 99999){ echo $left_unlimited; ?><? } else { ?><? echo $setting->down_limit_y; } ?><?PHP echo $left_year2; ?><? echo $currency->sign; ?><? echo $setting->sub_price; ?><?PHP echo $left_year3; ?><a href="subscribe.php?t=y" class="white_link"><?PHP echo $left_signup; ?></a> 
			</td>
		</tr>
	<?
	}
		if($setting->allow_sub_free == 1){
	?>
		<tr>
			<td height="10"></td>
		</tr>
		<tr>
			<td class="sub_banner">
				<? echo $left_signup1; ?><br><a href="subscribe.php?t=f" class="white_link"><?PHP echo $left_signup; ?></a> 
			</td>
		</tr>
	<?PHP
		}
		 if($setting->leftbox5 == 1){
		?>
		<tr>
			<td height="8"></td>
		</tr>
		<tr>
			<td class="sub_banner">
				<?PHP copy_title(7); ?>
			</td>
		</tr>
		<tr>
			<td align="left" style="padding: 5px 5px 5px 10px;">
		<?PHP 
		copy_area(7,2);
		?>
			</td>
		</tr>
	<?PHP
	}
		if($pmphp == 1){
	?>
		
		<tr>
			<td height="10"></td>
		</tr>
		<tr>
			<td class="sub_banner">
				<? echo $left_photogsignup; ?><br><a href="photographer_signup.php" class="white_link"><?PHP echo $left_signup; ?></a>
			</td>
		</tr>
	<?PHP
		}
		 if($setting->leftbox6 == 1){
		?>
		<tr>
			<td height="8"></td>
		</tr>
		<tr>
			<td class="sub_banner">
				<?PHP copy_title(8); ?>
			</td>
		</tr>
		<tr>
			<td align="left" style="padding: 5px 5px 5px 10px;">
		<?PHP 
		copy_area(8,2);
		?>
			</td>
		</tr>
	<?PHP
	}
	?>
	</table>
</div>
-->
<div class="left-main">
            	<h1><?php echo $misc_photocat; ?></h1>
                <!--left menu ehlel-->
                <div class="left-menu">
                	<ul>
                    <?php 
                      $query = "SELECT * FROM photo_galleries ORDER BY title";
                      if($_SESSION['lang'])
                      {
                        //$query .= '_'.$_SESSION['lang'];
                      }
                      $result = mysql_query($query);
                      
                    ?>
                    <?php while($item = mysql_fetch_object($result)): ?>
                      <li><a href="gallery.php?gid=<?php echo $item->id; ?>"><?php 
                        if($_SESSION['lang'] != 'English'){
                          if(trim($item->{ 'title_'.$_SESSION['lang']}) != "")
                            echo $item->{ 'title_'.$_SESSION['lang']};
                          else echo $item->title;
                        }
                        else echo $item->title;
                        ?>
                      </a></li>
                    <?php endwhile; ?>
                  </ul>
                </div>
                <div class="left-search-main">
                  <div class="top-bg"></div>
                    <div class="main">
                      <form name="select_lang" method="post" action="public_actions.php?pmode=select_lang">
                      <input type="hidden" name="return" value="<? echo $return_url; ?>">
                      <?PHP echo $left_select_language; ?><br>
                      <select name="lang" style="font-size: 10; font-weight: bold; width: 150; border: 1px solid #000000;" onchange="document.select_lang.submit()">
                      <?
                        $language_dir = "language";
                        $l_real_dir = realpath($language_dir);
                        $l_dir = opendir($l_real_dir);
                        // LOOP THROUGH THE PLUGINS DIRECTORY
                        $lfile = array();
                        while(false !== ($file = readdir($l_dir))){
                          $lfile[] = $file;
                        }
                        //SORT THE CSS FILES IN THE ARRAY
                        sort($lfile);
                        //GO THROUGH THE ARRAY AND GET FILENAMES
                        foreach($lfile as $key => $value){
                        //IF FILENAME IS . OR .. DO NO SHOW IN THE LIST
                        $fname = strip_ext($lfile[$key]);
                        if($fname != ".." && $fname != "."){
                          if($_SESSION['lang'] == $fname){
                            echo "<option selected>" . $fname . "</option>";
                          } elseif(trim($fname) != "") {
                            echo "<option>" . $fname . "</option>";
                          }
                        }
                      }
                      ?>
                      </select>
                      <!--<input type="submit" value="<?PHP echo $left_go_button; ?>" class="go_button2">-->
                      </form>
                    </div>
                  <div class="bottom-bg"></div>
                </div>
                <!--left menu tugsgul-->
                <div style="clear:both; height: 10px;"></div>
                <!--left search ehlel-->
                <?php include('search_nav.php');?>
            </div>
