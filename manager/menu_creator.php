<?PHP
  session_start();
  session_register("mgruser");
  $_SESSION['mgruser'] = 1;
  
	include( "../database.php" );
	include( "../config_public.php" );
	include( "../functions.php" );
	
	unset($_SESSION['mgruser']);

		 $cr = "\n";
		 
											if($setting->show_private != 1){
												$extrasql = " and pub_pri = '0'";
											} else {
												$extrasql = "";
											}
											
											if($setting->show_num == 1){
												$show = 1;
											} else {
												$show = 0;
											}
											
											$output = "var TREE_ITEMS = [";
											$output.= $cr . "['". $left_select_cat . "', '', {'tt' : '" . $left_select_cat_hover . "', 'sb':'Select A Category','hte':{'oncontextmenu':'return h_context_menu_root(o_tree_item)','style':'color:" . $left_select_cat_color . "'}},";
						// level one output (main category)----------------------------------------
												$ca_result = mysql_query("SELECT title,id,pub_pri,rdmcode FROM photo_galleries where active = '1' and nest_under = '0'" . $extrasql . " order by galorder", $db);
												$ca_rows = mysql_num_rows($ca_result);
												while($ca = mysql_fetch_object($ca_result)){
													$title1 = $ca->title;
													//addslashes($title1);
													htmlspecialchars($title1);
												$output.= $cr . "['" . addslashes($title1);
											if($setting->show_num == 1){ 
												$output.= imgcount($ca->id,1,0,$item_id,0,$setting->show_private);
												$output.= " (" .$photo_rows_final . ")', ";
												$photo_rows_final = 0;
											} else {
												$output.= "', ";
											}
												if($ca->pub_pri == 1){
													$output.= "'" . $setting->site_url . "/pri.php?gid=" . $ca->id . "&gal=" . $ca->rdmcode . "',,";
												} else {
													
													if($setting->modrw){
														$output.= "'" . $setting->site_url . "/" . mod_clean($ca->title) . "_g" . $ca->id . ".html',,";
													} else {
														$output.= "'" . $setting->site_url . "/gallery.php?gid=" . $ca->id . "',,";
													}
												}
            // level two output (sub category)------------------------------------------
                        $ca2_result = mysql_query("SELECT title,id,pub_pri,rdmcode FROM photo_galleries where active = '1' and nest_under = '$ca->id'" . $extrasql . " order by galorder", $db);
												$ca2_rows = mysql_num_rows($ca2_result);
												if($ca2_rows == 0){
												 	$output.= "],";
												} else {
												while($ca2 = mysql_fetch_object($ca2_result)){
													$title2 = $ca2->title;
													//addslashes($title2);
													htmlspecialchars($title2);
												$output.= $cr . "['" . addslashes($title2);
											if($setting->show_num == 1){ 
												$output.= imgcount($ca2->id,1,0,$item_id,0,$setting->show_private);
												$output.= " (" .$photo_rows_final . ")', ";
												$photo_rows_final = 0;
											} else {
												$output.= "', ";
											}
												if($ca2->pub_pri == 1){
													$output.= "'" . $setting->site_url . "/pri.php?gid=" . $ca2->id . "&gal=" . $ca2->rdmcode . "',,";
												} else {
													//$output.= "'" . $setting->site_url . "/gallery.php?gid=" . $ca2->id . "',,";
													
													if($setting->modrw){
														$output.= "'" . $setting->site_url . "/" . mod_clean($ca2->title) . "_g" . $ca2->id . ".html',,";
													} else {
														$output.= "'" . $setting->site_url . "/gallery.php?gid=" . $ca2->id . "',,";
													}
												} 
						// level three output (sub sub category)-----------------------------------
						            $ca3_result = mysql_query("SELECT title,id,pub_pri,rdmcode FROM photo_galleries where active = '1' and nest_under = '$ca2->id'" . $extrasql . " order by galorder", $db);
												$ca3_rows = mysql_num_rows($ca3_result);
												if($ca3_rows == 0){
												 	$output.= "],";
												} else {
												while($ca3 = mysql_fetch_object($ca3_result)){
													$title3 = $ca3->title;
													//addslashes($title3);
													htmlspecialchars($title3);
												$output.= $cr . "['" . addslashes($title3);
											if($setting->show_num == 1){
												$output.= imgcount($ca3->id,1,0,$item_id,0,$setting->show_private);
												$output.= " (" .$photo_rows_final . ")', ";
												$photo_rows_final = 0;
											} else {
												$output.= "', ";
											}
												if($ca3->pub_pri == 1){
													$output.= "'" . $setting->site_url . "/pri.php?gid=" . $ca3->id . "&gal=" . $ca3->rdmcode . "',,";
												} else {
													//$output.= "'" . $setting->site_url . "/gallery.php?gid=" . $ca3->id . "',,";
													
													if($setting->modrw){
														$output.= "'" . $setting->site_url . "/" . mod_clean($ca3->title) . "_g" . $ca3->id . ".html',,";
													} else {
														$output.= "'" . $setting->site_url . "/gallery.php?gid=" . $ca3->id . "',,";
													}
												}
						// level four output (sub sub sub category)--------------------------------
						            $ca4_result = mysql_query("SELECT title,id,pub_pri,rdmcode FROM photo_galleries where active = '1' and nest_under = '$ca3->id'" . $extrasql . " order by galorder", $db);
												$ca4_rows = mysql_num_rows($ca4_result);
												if($ca4_rows == 0){
												 	$output.= "],";
												} else {
												while($ca4 = mysql_fetch_object($ca4_result)){
													$title4 = $ca4->title;
													//addslashes($title4);
													htmlspecialchars($title4);
												$output.= $cr . "['" . addslashes($title4);
											if($setting->show_num == 1){
												$output.= imgcount($ca4->id,1,0,$item_id,0,$setting->show_private);
												$output.= " (" .$photo_rows_final . ")', ";
												$photo_rows_final = 0;
											} else {
												$output.= "', ";
											}
												if($ca4->pub_pri == 1){
													$output.= "'" . $setting->site_url . "/pri.php?gid=" . $ca4->id . "&gal=" . $ca4->rdmcode . "',,";
												} else {
													//$output.= "'" . $setting->site_url . "/gallery.php?gid=" . $ca4->id . "',,";
													
													if($setting->modrw){
														$output.= "'" . $setting->site_url . "/" . mod_clean($ca4->title) . "_g" . $ca4->id . ".html',,";
													} else {
														$output.= "'" . $setting->site_url . "/gallery.php?gid=" . $ca4->id . "',,";
													}
												}
					 // level five output (sub sub sub sub category)------------------------------
					 			        $ca5_result = mysql_query("SELECT title,id,pub_pri,rdmcode FROM photo_galleries where active = '1' and nest_under = '$ca4->id'" . $extrasql . " order by galorder", $db);
												$ca5_rows = mysql_num_rows($ca5_result);
												if($ca5_rows == 0){
												 	$output.= "],";
												} else {
												while($ca5 = mysql_fetch_object($ca5_result)){
													$title5 = $ca5->title;
													//addslashes($title5);
													htmlspecialchars($title5);
												$output.= $cr . "['" . addslashes($title5);
											if($setting->show_num == 1){
												$output.= imgcount($ca5->id,1,0,$item_id,0,$setting->show_private);
												$output.= " (" .$photo_rows_final . ")', ";
												$photo_rows_final = 0;
											} else {
												$output.= "', ";
											}
												if($ca5->pub_pri == 1){
													$output.= "'" . $setting->site_url . "/pri.php?gid=" . $ca5->id . "&gal=" . $ca5->rdmcode . "'],";
												} else {
													//$output.= "'" . $setting->site_url . "/gallery.php?gid=" . $ca5->id . "'],";
													
													if($setting->modrw){
														$output.= "'" . $setting->site_url . "/" . mod_clean($ca5->title) . "_g" . $ca5->id . ".html'],";
													} else {
														$output.= "'" . $setting->site_url . "/gallery.php?gid=" . $ca5->id . "'],";
													}
												}
											}
											$output.= $cr . "],";
										}
											}
											$output.= $cr . "],";
										}
										
											}
											$output.= $cr . "],";
										}
											}
											$output.= $cr . "],";
										}
										  }
							$output.= $cr . "]";
							$output.= $cr . "];";
							     
										$filename = '../js/tree_items.js';
										$content = $output;
										if (is_writable($filename)) {
										if (!$handle = fopen($filename, 'w')) {
         							echo "Cannot open file ($filename)";
         						exit;
   									}
										if (fwrite($handle, $content) === FALSE) {
       								echo "<html>";
											echo "<body>";
       								echo "Cannot write to file ($filename) check the folder permission<br>";
       								echo "<font face=\"arial\" size=\"2\"><a href=\"javascript:window.close()\">There was an issue writing to file, do you wish to close this window?</a></font>";
											echo "</body>";
											echo "</html>";		
       							exit;
   									}
   									echo "<html>";
										echo "<body>";
       							echo "Success, wrote menu to file ($filename)<br>";  
       							echo "<font face=\"arial\" size=\"2\"><a href=\"javascript:window.close()\">The menu was created successfully, do you wish to close this window?</a></font>";
										echo "</body>";
										echo "</html>";		 
   									fclose($handle);
									} else {
   									echo "The file $filename is not writable check the file permission";
								}
?>								