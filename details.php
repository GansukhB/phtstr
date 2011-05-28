<?
	session_start();
	
	include( "database.php" );
	include( "functions.php" );
	require('_drawrating.php');
		
	$package1_result = mysql_query("SELECT title,keywords,description FROM photo_package where id = '" . $_GET['pid'] . "'", $db);
	$package1 = mysql_fetch_object($package1_result);
	
if($package1->title != ""){
	$page_title       = $package1->title; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE
} else {
	$page_title       = "";
}
if($package1->keywords != ""){
	$meta_keywords    = $package1->keywords; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
} else {
	$meta_keywords    = "";
}
if($package1->description != ""){
	$meta_description = $package1->description; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
} else {
	$meta_description = "";
}
	
	include( "config_public.php" );

	// VISITOR ID
	if(!$_SESSION['visitor_id']){
		session_register("visitor_id");
		$_SESSION['visitor_id'] = random_gen(16,"");
	}
?>
<html>
	<head>
		<script language=javascript type="text/javascript">
		function dropdown(mySel){
			var myWin, myVal;
			myVal = mySel.options[mySel.selectedIndex].value;
			if(myVal){
   		if(mySel.form.target)myWin = parent[mySel.form.target];
   				else myWin = window;
   		if (! myWin) return true;
   				myWin.location = myVal;
   			}
			return false;
			}

    	function NewWindow(page, name, w, h, location, scroll) {
        var winl = (screen.width - w) / 2;
        var wint = (screen.height - h) / 2;
        winprops = 'height='+h+',width='+w+',location='+location+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable'
        win = window.open(page, name, winprops)
        if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
    	}
	</script>
	<script type="text/javascript" language="javascript" src="js/behavior.js"></script>
	<script type="text/javascript" language="javascript" src="js/rating.js"></script>
		<?php echo $script1; ?>
    <? print($head); ?>
    
  </head>
<body>    
<div class="container">
			<? include("header.php"); ?>
				<? //include("search_bar.php"); ?>
      <div id="main">
			<? include("i_gallery_nav.php"); ?>
      <div class="right-main">
        
        <? include("i_details.php"); ?>
      
      </div> <!-- end class right main -->
      </div> <!-- end id main -->
    </div> <!-- end container -->
    <? include("footer.php"); ?>	
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	
