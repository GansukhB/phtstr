<?
	session_start();

	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	if($_COOKIE['lang'])
  {
    header("location:index.php");
  }
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
  </head>
  <link rel="stylesheet" href="styles/css-home.css" />
  <link rel="shortcut icon" type="image/x-icon" href="images/icon1.png" />
  <!--[if IE]>
  <link rel="stylesheet" href="blueprint/ie.css" media="projection,screen"/>
  <![endif]-->
  <script src="js/jquery-1.5.js" type="text/javascript"></script>
  <script type="text/javascript" >
  $(document).ready(function() {
    $(".tab-content").hide();
    $("ul.tab li:first").addClass("active").show(); 
    $(".tab-content:first").show(); 
    $("ul.tab li").click(function() {
      $("ul.tab li").removeClass("active"); 
      $(this).addClass("active"); 
      $(".tab-content").hide(); 
      var activeTab = $(this).find("a").attr("href"); 
      $(activeTab).fadeIn();
      return false;
    });
  });
  </script>
<body class="home-page">

	<div id="home-page" VAlign="CENTER">
    	<div class="center" style="height:100%; width:100%; position:absolute; top:20%;">
            <div class="logo" align="center"><img src="images/home-page-logo.png" /></div>
            <?php
              $qry = "SELECT COUNT(id) as cnt FROM photo_package WHERE active=1";
              $rslt = mysql_query($qry);
              
              $allphotos = mysql_fetch_assoc($rslt);
              $allphotos = $allphotos['cnt'];
            ?>
            <div class="text" align="center"><?php echo $allphotos; ?> StockPhotos</div>
            <div class="language" align="center" style="text-transform: uppercase;">
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
                                        foreach($lfile as $key => $value){
                                        //IF FILENAME IS . OR .. DO NO SHOW IN THE LIST
                                          $fname = strip_ext($lfile[$key]);
                                          if($fname != ".." && $fname != "."){
                                            if(trim($fname) != '')
                                                echo "<a href=\"public_actions.php?pmode=select_lang&lang=$fname&return=index.php\">" . $fname . "</a>";
                                              
                                          }
                                        }
                                    ?>

            </div>
        </div>
    </div>
</body>
</html>
