<?php 
  $nav = $_GET['nav'];
	if($execute_nav == 1){
		$nav_order = 2; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "Add languages"; // name of the nav that will appear on the page
	}else {
?>
  <h3>Currently added Languages</h3>
<ul>  
  <?
    $language_dir = "../language";
    $l_real_dir = realpath($language_dir);
    $l_dir = opendir($l_real_dir);
    $i = 0;
    // LOOP THROUGH THE PLUGINS DIRECTORY
    while(false !== ($file = readdir($l_dir))){
    $lfile[] = $file;
    }
    //SORT THE CSS FILES IN THE ARRAY
    sort($lfile);
    //GO THROUGH THE ARRAY AND GET FILENAMES
    foreach($lfile as $key => $value){
      //IF FILENAME IS . OR .. OR SLIDESHOW.CSS DO NO SHOW IN THE LIST
      $fname = strip_ext($lfile[$key]);
      if($fname != ".." && $fname != "." && trim($fname) != ''){
        echo '<li>'.$fname.'</li>';
      }
    }
    
  ?>
</ul>
  
  <form name="form" action="actions_language.php?pmode=lang_upload" enctype="multipart/form-data" method="post">
    <input type="file" name="lang_source" />
    <input type="submit" value="Upload" />
  </form>
  
  
<?php } ?>
