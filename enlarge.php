<?php
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );

	if($setting->no_right_click == 1){
		$body = "<body oncontextmenu=\"return false\" bgcolor=\"#$public_bgcolor\" topmargin=\"$topmargin\" leftmargin=\"$leftmargin\" marginheight=\"$marginheight\" marginwidth=\"$marginwidth\" style=\"background-image: url(images/bg.gif);\">\n";
		} else {
		$body = "<body bgcolor=\"#$public_bgcolor\" topmargin=\"$topmargin\" leftmargin=\"$leftmargin\" marginheight=\"$marginheight\" marginwidth=\"$marginwidth\" style=\"background-image: url(images/bg.gif);\">\n";
	}
?>
<html>
<?php echo $body; ?>
<img src="watermark2.php?i=<?php echo $_GET['i']; ?>" />
</body>
</html>
