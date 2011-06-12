<?php
      $qry = "SELECT COUNT(id) as cnt FROM photo_package WHERE active=1";
      $rslt = mysql_query($qry);
      
      $allphotos = mysql_fetch_assoc($rslt);
      $allphotos = $allphotos['cnt'];
      ?>
<div class="header-top">
        	<div class="left"><?php echo $allphotos; ?> Stock photos</div>
            <div class="right">
            	<div class="language">
                  
                        <ul class="nav">
                            <li><a href="#"><?Php echo $_SESSION['lang']; ?></a>
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
                                                echo "<li><a href=\"public_actions.php?pmode=select_lang&lang=$fname&return=".$return."\">" . $fname . "</a></li>";
                                              
                                          }
                                        }
                                    ?>
                              </ul>
                            </li>
                        </ul>
                </div>
                <div class="login">| 
                  <strong>
                    <?php if(!$_SESSION['mem_name']): ?>
                      <a href="subscribe.php"><?php echo $left_login; ?></a>
                    <?php else: ?>
                      <a href="cart.php"><?php echo $cart_my_cart; ?></a> | 
                      <a href="public_actions.php?pmode=logout"><?php echo $left_logout ?></a>
                    <?php endif; ?>
                  </strong>
                </div>
            </div>
        </div>
        
