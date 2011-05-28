<?
	session_start();

	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );
	
  //session_destroy();
	// VISITOR ID
	if(!$_SESSION['visitor_id']){
		session_register("visitor_id");
		$_SESSION['visitor_id'] = random_gen(16,"");
	}
	
	//UNSET ANY IMAGE VIEWING
	unset($_SESSION['pub_gid']);
	unset($_SESSION['pub_pid']);
	unset($_SESSION['imagenav']);
  
  
  //echo $_SESSION['lang'];  
?>
<html>
	<head>
		<script language=JavaScript src='./js/xns.js'></script>
    <?php echo $script1; ?>
    
	<? print($head); ?>

<body>
  <div class="container">
			<? include("header.php"); ?>
			
      <div id="main">
				
				
				<? //include("search_bar.php"); ?>
			
				<? include("i_gallery_nav.php"); ?>
        
        <div class="right-main">
          <?php if(!$_SESSION['mem_name']): ?>
            	<div class="slider">
                	<img src="images/image1.jpg">
                </div>
                <!--content ehlel-->
                <div class="content">
                	<!--left content ehlel-->
                	<div class="left-content">
                    	<h1>20000 ГАРУЙ ЗУРГИЙН САНГААС ХУДАЛДАЖ АВАХЫГХҮСВЭЛ</h1>
                        Template Monster website templates, Flash templates and other web design products are famous for being top quality solution for a quick, easy and affordable website production. The best part about our templates is the simplicity - you purchase the template package, customize it a little bit and upload it to your hosting. And there you have it - your website is up and running within as little as several hours from the moment you've been choosing a website template! So when
                        <div class="more" align="center">
                        	<a href="#">Захиалах</a>
                        </div>
                    </div>
                    <!--left content tugsgul-->
                    <!--right content ehlel-->
                    <div class="right-content">
                      <?php if(!$_SESSION['mem_name']): ?>
                        <h1><? echo $login_member_login; ?></h1><!--
                          <form>
                            <label>
                              <input value="хэрэглэгчийн нэр" type="text">
                              </label>
                              <label>
                                <input value="password" type="password">
                                  <input class="button" src="images/button2.jpg" type="image">
                              </label>
                          </form>-->
                          
                          <script>
                            function clearText(field){
                                if (field.defaultValue == field.value) field.value = '';
                                else if (field.value == '') field.value = field.defaultValue;
                            }

                          </script>
                          <form action="public_actions.php?pmode=login" method="post">
                            
                                <label><input type="text" name="email" value="<?php echo $form_email; ?>" onFocus="clearText(this)" onBlur="clearText(this)"></label>
                              
                                <label><input type="password" name="password" value="password" onFocus="clearText(this)" onBlur="clearText(this)"></label>
                              
                               <!--<input type="submit" value="<?PHP echo $login_form_submit_button; ?>"> -->
                               <input class="button" src="images/button2.jpg" type="image">
                          </form>
                          
                          <div>
                            <a href="subscribe.php"><?php echo $subscribe_crumb_link ; ?></a>
                          </div>
                          <div>
                            <a href="recover_password.php"><?php echo 'Forgotten you password!'; ?></a>
                          </div>
                          <div>
                            <a href="./photographer/"><?php echo 'Registered Photographer?'; ?></a>
                          </div>
                        <?php else: ?>
                            <?php echo $login_login; ?>                     
                        <?php endif; ?>
                    </div>
                    <!--right content tugsgul-->
                </div>
                <!--content tugsgul-->
                <!--content ehlel-->
                <div class="content">
                	<img class="image" src="images/image2.jpg">
                	Template Monster website templates, Flash templates and other web design products are famous for being top quality solution for a quick, easy and affordable website production. The best part about our templates is the simplicity - you purchase the template package, customize it a little bit and upload it to your hosting. And there you have it - your website is up and running within as little as several hours from the moment you've been choosing a website template! So when
                </div>
                <!--content tugsgul-->
          <?php else: ?>   
                <div class="r-left-main">
               	  <div class="r-content-main">
                    	<h2><?php echo $homepage_welcome_to_message; ?></h2>
                      <div>
                        <?php 
                          $query = "select * from copy_areas where id='102' ";
                          $result = mysql_query($query);
                          
                          $obj = mysql_fetch_object($result);
                          
                          $text = $obj->article;
                          
                          if($_SESSION['lang'] != 'English')
                          {
                            $text = $obj->{ 'article_'.$_SESSION['lang'] };
                          }
                          
                          echo $text;
                        ?>
                      </div>
                  </div>
                  <div class="r-content-main">
                  		<h2><?php echo $left_details; ?></h2>
                      
                      
                  <?php if($_SESSION['sub_member']){
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
<?php
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
                      
                  		<div class="left">
                        	<div><strong class="yellow">Subscrib today?</strong></div>
                            we have packages starting at 49$.
                        </div>
                        <div class="right">
                        	<div class="s-price-main">
                            	<div class="left1">
                                	<h1>On demeand</h1>
                                    Download images when you need them
                                </div>
                            	<div align="center" ;="" class="right">
                                	<div class="title1">start at</div>
                                    <div class="title2">49$</div>
                                    <div class="title1">5 downloads</div>
                                </div>
                            </div>
                            <div class="s-price-main">
                            	<div class="left1">
                                	<h1>On demeand</h1>
                                    Download images when you need them
                                </div>
                            	<div align="center" ;="" class="right">
                                	<div class="title1">start at</div>
                                    <div class="title2">49$</div>
                                    <div class="title1">5 downloads</div>
                                </div>
                            </div>
                        </div>
                  </div>
                  <div class="r-content-main">
                    	<h2>Your lighboxes</h2>
                        <div class="lighbox-main">
                        	<div class="box-content"></div>
                            <div class="box-content"></div>
                            <div class="box-content"></div>
                            <div class="box-content"></div>
                            <div class="box-content"></div>
                            <div class="box-content"></div>
                            <div class="box-content"></div>
                            <div class="box-content"></div>
                            <div class="box-content"></div>
                            <div class="box-content"></div>
                        </div>
                  </div>
                </div>
          <?php endif; ?>
                <!--content ehlel-->
                <div class="content">
                	<!-- video-content ehlel-->
                	<div class="video-content line">
                    	<h1>7 ХОНОГИЙН ҮНЭГҮЙ ЗУРАГ</h1>
                        <img class="image" src="images/image2.jpg">
                        <div class="copyright">by <a href="#">miamore</a></div>
                        <div><span>web design colection</span></div>
                        <div class="download">
                        	<a href="#">татах</a>
                        </div>
                    </div>
                    <!-- video-content tugsgul-->
                    <!-- video-content ehlel-->
                    <div class="video-content line">
                    	<h1>7 ХОНОГИЙН ҮНЭГҮЙ ЗУРАГ</h1>
                        <img class="image" src="images/image2.jpg">
                        <div class="copyright">by <a href="#">miamore</a></div>
                        <div><span>web design colection</span></div>
                        <div class="download">
                        	<a href="#">татах</a>
                        </div>
                    </div>
                    <!-- video-content tugsgul-->
                    <!-- video-content ehlel-->
                    <div class="video-content">
                    	<h1>7 ХОНОГИЙН ҮНЭГҮЙ ЗУРАГ</h1>
                        <img class="image" src="images/image2.jpg">
                        <div class="copyright">by <a href="#">miamore</a></div>
                        <div><span>web design colection</span></div>
                        <div class="download">
                        	<a href="#">татах</a>
                        </div>
                    </div>
                    <!-- video-content tugsgul-->
                </div>
                <!--content tugsgul-->
                <!--most content ehlel-->
                <div class="most-content">
                	 <!--most content tab ehlel-->
                	<ul class="tab">
                    	<li class="active"><a href="#tab1">FEUTURED PHOTOS</a></li>
                        <li><a href="#tab2">NEWEST PHOTOS</a></li>
                        <li><a href="#tab3">MOST POPULAR PHOTOS</a></li>
                    </ul>
                    <!--most content tab tugsgul-->
                    <!--tab container ehlel-->
                    <div class="tab-container">
                    	<!--tab1 ehlel-->
                    	<div id="tab1" style="" class="tab-content">
                        	<div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand</a></div>
                            </div>
                            <div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand</a></div>
                            </div>
                            <div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand</a></div>
                            </div>
                            <div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand</a></div>
                            </div>
                            <div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand</a></div>
                            </div>
                            <div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand</a></div>
                            </div>
                        </div>
                        <!--tab1 tugsgul-->
                        <!--tab2 ehlel-->
                        <div id="tab2" style="display:none;" class="tab-content">
                        	<div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand6</a></div>
                            </div>
                            <div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand5</a></div>
                            </div>
                            <div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand4</a></div>
                            </div>
                            <div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand3</a></div>
                            </div>
                            <div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand2</a></div>
                            </div>
                            <div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand1</a></div>
                            </div>
                        </div>
                        <!--tab2 tugsgul-->
                        <!--tab3 ehlel-->
                        <div id="tab3" style="display:none;" class="tab-content">
                        	<div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand1</a></div>
                            </div>
                            <div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand2</a></div>
                            </div>
                            <div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand3</a></div>
                            </div>
                            <div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand4</a></div>
                            </div>
                            <div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand5</a></div>
                            </div>
                            <div class="most" align="center">
                            	<img class="image" src="images/image2.jpg">
                                <div class="title"><a href="#">miamore khand6</a></div>
                            </div>
                        </div>
                        <!--tab3 tugsgul-->
                    </div>
                    <!--tab container tugsgul-->
                </div>
                 <!--most content tugsgul-->
            
            
          <!--
												<span class="body_header_text"><?PHP echo $homepage_welcome_to_message; ?><? echo $setting->site_title; ?></span><br><br>
												<?
													$copy_area_id = 15;
													$ca_result = mysql_query("SELECT article FROM copy_areas where id = '$copy_area_id'", $db);
													$ca = mysql_fetch_object($ca_result);
													
													$hp_copy = str_replace("<P>", "", $ca->article);
													$hp_copy = str_replace("</P>", "<br><br>", $hp_copy);
													$hp_copy = str_replace("{COMPANY_NAME}", $setting->site_title, $hp_copy);
													
													echo $hp_copy;
												?>
										<? if($setting->show_news == 1){ ?>
											<? //include("i_news.php"); ?>
										<? } else { ?>
											<td class="index_copy_area"></td>
											<td class="index_copy_area"></td>
										<? } ?>-->
                    <!--
									</tr>
									<tr>
										<td colspan="3" valign="top">
											<table cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<td class="featured_photos_tab" nowrap><?PHP echo $homepage_featured; ?></td>
													<td align="left" class="other_photos_tabs"><img src="images/triangle_2.gif"></td>
													<td class="other_photos_tabs" valign="top"><span class="other_photos_tabs"><a href="new_photos.php" class="white_bold_link"><?PHP echo $homepage_newest; ?></a></span><span class="other_photos_tabs"><a href="popular_photos.php" class="white_bold_link"><?PHP echo $homepage_popular; ?></a></span></td>
												</tr>
												<tr>
													<td colspan="3" align="center" valign="top" style="padding: 5px;">
														<?
														if(!file_exists("swf/featured.swf")){
														  //include("i_featured_photos.php"); 
														} else {
														if($_SESSION['visitor_flash'] != 1 && $setting->flash_featured_on == 1){
														?>
														<div id="flashcontent2"/>
														<?PHP echo $no_flashplayer; ?>
														</div>
														<script type="text/javascript" src="js/swfobject.js"></script>
                            <script>
                                <!--
                                    var flashObj = new SWFObject ("swf/featured.swf", "featured photos", "550", "325", 8, "<?PHP echo $setting->pf_bgcolor; ?>", true);
                                    flashObj.addVariable ("mousewheelflip", "<?PHP echo $setting->pf_mousewheelflip; ?>");
                                    flashObj.addVariable ("autoflipseconds", "<?PHP echo $setting->pf_autoflipseconds; ?>");
                                    flashObj.addVariable ("flipsound", "<?PHP echo $setting->pf_flipsound; ?>");
                                    flashObj.addVariable ("flipspeed", "<?PHP echo $setting->pf_flipspeed; ?>");
                                    flashObj.addVariable ("namebold", "<?PHP echo $setting->pf_namebold; ?>");
                                    flashObj.addVariable ("namecolor", "<?PHP echo $setting->pf_namecolor; ?>");
                                    flashObj.addVariable ("namedistance", "<?PHP echo $setting->pf_namedistance; ?>");
                                    flashObj.addVariable ("nameposition", "<?PHP echo $setting->pf_nameposition; ?>");
                                    flashObj.addVariable ("namesize", "<?PHP echo $setting->pf_namesize; ?>");
                                    flashObj.addVariable ("namefont", "<?PHP echo $setting->pf_namefont; ?>");
                                    flashObj.addVariable ("preloadset", "<?PHP echo $setting->pf_preloadset; ?>");
                                    flashObj.addVariable ("hpers", "<?PHP echo $setting->pf_hpers; ?>");
                                    flashObj.addVariable ("vpers", "<?PHP echo $setting->pf_vpers; ?>");
                                    flashObj.addVariable ("view", "<?PHP echo $setting->pf_view; ?>");
                                    flashObj.addVariable ("reflectionalpha", "<?PHP echo $setting->pf_reflectionalpha; ?>");
                                    flashObj.addVariable ("reflectiondepth", "<?PHP echo $setting->pf_reflectiondepth; ?>");
                                    flashObj.addVariable ("reflectiondistance", "<?PHP echo $setting->pf_reflectiondistance; ?>");
                                    flashObj.addVariable ("reflectionextend", "<?PHP echo $setting->pf_reflectionextend; ?>");
                                    flashObj.addVariable ("selectedreflectionalpha", "<?PHP echo $setting->pf_selectedreflectionalpha; ?>");
                                    flashObj.addVariable ("showname", "<?PHP echo $setting->pf_showname; ?>");
                                    flashObj.addVariable ("showreflection", "<?PHP echo $setting->pf_showreflection; ?>");
                                    flashObj.addVariable ("photoheight", "<?PHP echo $setting->pf_photoheight; ?>");
                                    flashObj.addVariable ("photowidth", "<?PHP echo $setting->pf_photowidth; ?>");
                                    flashObj.addVariable ("selectedy", "<?PHP echo $setting->pf_selectedy; ?>");
                                    flashObj.addVariable ("defaultid", "<?PHP echo $setting->pf_defaultid; ?>");
                                    flashObj.addVariable ("holderalpha", "<?PHP echo $setting->pf_holderalpha; ?>");
                                    flashObj.addVariable ("holderborderalpha", "<?PHP echo $setting->pf_holderborderalpha; ?>");
                                    flashObj.addVariable ("holderbordercolor", "<?PHP echo $setting->pf_holderbordercolor; ?>");
                                    flashObj.addVariable ("holdercolor", "<?PHP echo $setting->pf_holdercolor; ?>");
                                    flashObj.addVariable ("scalemode", "<?PHP echo $setting->pf_scalemode; ?>");
                                    flashObj.addVariable ("selectedscale", "<?PHP echo $setting->pf_selectedscale; ?>");
                                    flashObj.addVariable ("spacing", "<?PHP echo $setting->pf_spacing; ?>");
                                    flashObj.addVariable ("zoom", "<?PHP echo $setting->pf_zoom; ?>");
                                    flashObj.addVariable ("zoomtype", "<?PHP echo $setting->pf_zoomtype; ?>");                                    
                                    flashObj.write ("flashcontent2");
                                // 
                            </script>
            							<?PHP
            						} else {
            							//include("i_featured_photos.php"); 
            						}
            					} 
            							?>-->
                  	
              
                  <?php
                    if($pf_feed_status){
                      include('pf_feed.php');
                    }
                  ?>
      
      </div><!-- end right main-->
      <?php include('i_banner.php'); ?>
      
      
      </div> <!-- end id main-->
      </div><!-- end container-->
      <? include("footer.php"); ?>		
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	
