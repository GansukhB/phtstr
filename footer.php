
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
                        	<div class="title">MONGOLPHOT.COM</div>
                            <ul>
                                <li><a href="#">Нүүр хуудас</a></li> 
                                <li><a href="#">Тусламж</a></li>  
                                <li><a href="#">Блог</a></li>  
                                <li><a href="#">Уралдаан</a></li>  
                                <li><a href="#">Захиалга</a></li>  
                                <li><a href="#">Нэвтрэх</a></li> 
                            </ul>
                        </div>
                        <!--content tugsgul-->
                        <!--content ehlel-->
                        <div class="content">
                        	<div class="title">ЭРХЗҮЙН МЭДЭЭЛЭЛ</div>
                            <ul>
                                <li><a href="#">Нүүр хуудас</a></li> 
                                <li><a href="#">Тусламж</a></li>  
                                <li><a href="#">Блог</a></li>  
                                <li><a href="#">Уралдаан</a></li>  
                                <li><a href="#">Захиалга</a></li>  
                                <li><a href="#">Нэвтрэх</a></li> 
                            </ul>
                        </div>
                        <!--content tugsgul-->
                    </div>
                    <!--left contents tugsgul-->
                    <!--right contents ehlel-->
                    <div class="right">
                    	<!--content ehlel-->
                    	<div class="content">
                        	<div class="title">ХЭЛНИЙ СОНГОЛТ</div>
                            <ul>
                                <li><a href="#">English</a></li> 
                                <li><a href="#">Russia</a></li>  
                                <li><a href="#">Mongolia</a></li>  
                            </ul>
                        </div>
                        <!--content tugsgul-->
                        <!--content ehlel-->
                        <div class="content">
                        	<div class="title">БИДЭНТЭЙ ХОЛБОГДОХ</div>
                            Acronym, Definition. QWE, Quality Week Europe. 
                            <div class="link"><a href="#">LIVE CHAT</a></div>
                        </div>
                        <!--content tugsgul-->
                    </div>
                    <!--right contents tugsgul-->
                </div>
                <!--contents tugsgul-->
        </div>
    </div>
      
