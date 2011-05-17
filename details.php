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
		<? print($head); ?>
		<center>
        <table cellpadding="0" cellspacing="0"><tr><td valign="top">
		<table cellpadding="0" cellspacing="0" width="765" class="main_table" style="border: 5px solid #<? echo $border_color; ?>;">
			<? include("header.php"); ?>
			<tr>
				<td class="left_nav_header"><? echo $misc_photocat; ?></td>
				<td></td>
				<? include("search_bar.php"); ?>
			</tr>
			<tr>
				<td rowspan="1" valign="top"><? include("i_gallery_nav.php"); ?></td>
				<td background="images/col2_shadow.gif" valign="top"><img src="images/col2_white.gif"></td>
				<td valign="top" height="18" width="581">
					<table cellpadding="0" cellspacing="0" border="0" width="560" height="100%">
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" width="100%">
									<tr>
									<?php
							include("crumbs.php");
						?>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class="index_copy_area" height="4"></td>
						</tr>						
						<tr>
							<td valign="top" height="100%" class="homepage_line">
								<table width="100%" border="0">
									<tr>
										<td height="6"></td>
									</tr>
									<tr>
										<td class="default_copy">
											<? copy_area(16,2); ?>
										</td>
									</tr>
									<tr>
										<td style="padding-left: 10;">
											<? include("i_details.php"); ?>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>				
				</td>
			</tr>
			<? include("footer.php"); ?>			
		</table>
        </td>
        <td valign="top">
			<?php
				if($pf_feed_status){
					include('pf_feed.php');
				}
			?>
        </td>
        </tr></table>
		</center>
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	