<div class="most-content">
            <ul class="tab">
              <li class="active">
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
												echo "<li class=\"active\"><a href=\"pri.php?gal=" . $pri_gal->rdmcode . "&gid=" . $key . "\" class=\"crumb_links\">" . $value . "</a></li> ";
											} else {
												mod_gallerylink($value,$key,"crumb_links");
											}
												//echo " <img src=\"images/nav_arrow.gif\" align=\"absmiddle\" /> ";
											} else {
												$pri_gal1_result = mysql_query("SELECT pub_pri,rdmcode FROM photo_galleries where id = '$key'", $db);
												$pri_gal1 = mysql_fetch_object($pri_gal1_result);
												if($pri_gal1->pub_pri != 0){
												echo "<li class=\"active\"><a href=\"pri.php?gal=" . $pri_gal1->rdmcode . "&gid=" . $key . "\" class=\"crumb_links\">" . $value . "</a> </li>";
											} else {
												mod_gallerylink($value,$key,"crumb_links");
												}
											}
											$thru++;
										}
									} else {
										echo "<a>$crumb</a>";
									}
								?>
                </li>
								<!--
							</td>
							<td align="left" class="featured_news_header" valign="top"><img src="images/triangle_1.gif"></td>
							<td class="other_photos_tabs2" nowrap><a href="new_photos.php" class="white_bold_link"><? echo $crumbs_newest; ?></a> &nbsp; <a href="popular_photos.php" class="white_bold_link"><? echo $crumbs_popular; ?></a></td>
						</tr>-->
            <?php
              $currentFile = $_SERVER["PHP_SELF"];
              $parts = Explode('/', $currentFile);
              $current_page =  $parts[count($parts) - 1]; 
            ?>
            <div style="float:right;">
                        <li> <a href="new_photos.php" ><? echo $crumbs_newest; ?></a></li>
                        <li><a href="popular_photos.php"><? echo $crumbs_popular; ?></a></li>
                    </div>
                    </ul>
             </div>
