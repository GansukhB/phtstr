<div class="clear"></div>
<!--			<tr>
				<td height="4" colspan="3" class="footer_line"></td>
			</tr>
			<tr><td height="10" colspan="3"></td></tr>
			<tr>
				<td valign="middle" align="center"><span class="cc"><?PHP echo $footer_we_accept; ?><img src="images/cc.gif" align="middle" alt="<?PHP echo $footer_we_accept_alt; ?>"></span></td>
				<td class="footer_div">&nbsp;</td>
				<td valign="middle">
					<table cellpadding="0" cellspacing="0" width="100%">
						<?PHP
   						if($setting->footerbox == 1){
						?>
						<tr>
							<td colspan="3" height="8"></td>
						</tr>
						<tr>
							<td colspan="3" style="padding: 5px 5px 5px 10px;">
						<?PHP 
						copy_area(40,2);
						?>
							</td>
						</tr>
						<?PHP
						}
						?>
						<tr>
							<td class="copyright"><a href="licensing.php" class="footer_links"><?PHP echo $footer_license; ?></a> | <a href="privacy_policy.php" class="footer_links"><?PHP echo $footer_privacy; ?></a> | <a href="terms_of_use.php" class="footer_links"><?PHP echo $footer_terms; ?></a></td>
						</tr>
						<tr>
							<td style="padding: 4px 0px 0px 10px; color: #666666;">
							<? echo $footer_copyright . " " . $setting->site_title . " " . $footer_all_rights; ?>
							</td>
						</tr>	
						<tr>
							<td style="padding: 0px 0px 0px 10px; color: #666666;">
							<? if($setting->author_branding == 1){ ?>
								<? if(!file_exists("nobranding.php")){ ?>
									Powered By <u>PhotoStore  Sell Photos Online</u> by <u>Ktools.net LLC</u>
								<? } ?>
							<? } ?>
							</td>
						</tr>
					</table>			
				</td>
			</tr>
			<tr><td height="10" colspan="3"></td></tr>-->
      
    <div id="footer">
    	<div class="container">
        	<!--top-footer ehlel-->
        	<div class="top-footer">
            	<div class="logo"><a href="#"><img src="images/logo.png"></a></div>
                <div class="footer-menu">
                  
                  <?php 
                    $qry = "SELECT COUNT(id) as cnt FROM photo_package WHERE active=1";
                    $rslt = mysql_query($qry);
                    
                    $allphotos = mysql_fetch_assoc($rslt);
                    $allphotos = $allphotos['cnt'];
                    //print_r($allphotos);
                    $date = date("Ymd");
                    
                    $date -= 7;
                    //echo $date; 
                    
                    $qry = "SELECT count(id) as cnt FROM photo_package WHERE added >= $date  ";
                    $rslt = mysql_query($qry);
                    $week = mysql_fetch_assoc($rslt);
                    $week = $week['cnt'];
                    
                    $qry = "SELECT count(id) as cnt FROM photographers WHERE status=1";
                    $rslt = mysql_query($qry);
                    $phtg = mysql_fetch_assoc($rslt);
                    $phtg = $phtg['cnt'];
                    
                  ?>
                	<a href="#"><?php echo $allphotos.' '.$gallery_photo; ?>
                  
                  </a> / <a href="#"><?php echo $week.' added this week'; ?></a> / 
                  <a href="photog_list.php"><?php echo $phtg; ?> photographers</a> 
                </div>
            </div>
            <!--top-footer tugsgul-->
            <!--contents ehlel-->
            <div class="contents">
            		<!--left contents ehlel-->
                	<div class="left">
                    	<!--content ehlel-->
                    	<div class="content">
                        	<div class="title">MONGOLPHOTo.COM</div>
                            <ul>
                              <li><a href="index.php"><?php echo $top_home; ?></a></li> 
                                <li><a href="faqs.php"><?php echo $top_faq; ?></a></li>  
                                <li><a href="news.php"><?php echo $top_news; ?></a></li>
                                
                                <li><a href="./blog/">Tutorial</a></li>  
                                <!--
                                <li><a href="order_status.php"><?php echo $order_crumb_link; ?></a></li>  
                                -->
                                <?php if(trim($_SESSION['mem_name'])!=""): ?>
                                  <li><a href="lightbox.php"><?php echo $left_lightbox; ?></a></li>
                                <?php endif; ?>
                                <?php if(!$_SESSION['mem_name']): ?>
                                  <li><a href="subscribe.php"><?php echo $left_login; ?></a></li>
                                <?php else: ?>
                                  <li><a href="public_actions.php?pmode=logout"><?php echo $left_logout ?></a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <!--content tugsgul-->
                        <!--content ehlel-->
                        <?php 
                          $query = "SELECT * FROM news WHERE active = '1' AND homepage = '0' ";// AND id != '103' AND id!='104' AND id!='105'";
                          $result = mysql_query($query);
                        ?>
                        <div class="content">
                        	<div class="title">ЭРХЗҮЙН МЭДЭЭЛЭЛ</div>
                            <ul>
                              <?php while($news = mysql_fetch_object($result)): 
                                $title = $news->title;
                                if($_SESSION['lang'] != 'English')
                                {
                                  $title = $news->{ 'title_'.$_SESSION['lang'] };
                                }
                              ?>
                                <li><a href="news_details.php?id=<?php echo $news->id; ?>"><?php echo $title; if(trim($title)=="") echo $news->title; ?></a></li> 
                              <?php endwhile; ?>  
                            </ul>
                        </div>
                        <!--content tugsgul-->
                    </div>
                    <!--left contents tugsgul-->
                    <!--right contents ehlel-->
                    <div class="right">
                    	<!--content ehlel-->
                    	<div class="content">
                        	<div class="title"><?php echo $left_select_language; ?></div>
                            <ul>
                                  <!--
                                    <li><a href="#">English</a></li>
                                    <li><a href="#">English</a></li>
                                    <li><a href="#">English</a></li>
                                  -->
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
                                        $return =  selfurl();
                                        $return = str_replace(array("&"), array("and"), $return);
                                        foreach($lfile as $key => $value){
                                        //IF FILENAME IS . OR .. DO NO SHOW IN THE LIST
                                          $fname = strip_ext($lfile[$key]);
                                          if($fname != ".." && $fname != "."){
                                            if(trim($fname) != '')
                                                echo "<li><a href=\"public_actions.php?pmode=select_lang&lang=$fname&return=$return\">" . $fname . "</a></li>";
                                              
                                          }
                                        }
                                    ?>
                              </ul>
                        </div>
                        <!--content tugsgul-->
                        <!--content ehlel-->
                        <div class="content">
                          <?php 
                            $query = "select * from news where trim(lower(title)) = 'contactus' or id = '107' ";
                            $result = mysql_query($query);
                            $contact = mysql_fetch_object($result);
                            
                            $title = $contact->title;
                            $article = $contact->article;
                            if($_SESSION['lang'] != 'English')
                            {
                              $title = $contact->{ 'title_'.$_SESSION['lang'] };
                              $article = $contact->{ 'article_'.$_SESSION['lang'] };
                            }
                            //echo mysql_error();
                            
                          ?>
                          
                        	<div class="title"><?php echo $title; ?></div>
                            <?php echo $article; ?>
                            <!--
                            <a href="news_details.php?id=<?php echo $news->id; ?>"><?php echo 'more'; ?></a>
                            -->
                        </div>
                        <!--content tugsgul-->
                    </div>
                    <!--right contents tugsgul-->
                </div>
                <!--contents tugsgul-->
        </div>
    </div>
      
