<?php
session_start();
include("database.php");
include("config_public.php");

if($setting->no_right_click == 1){
		$body = "<body oncontextmenu=\"return false\" bgcolor=\"#$public_bgcolor\" topmargin=\"$topmargin\" leftmargin=\"$leftmargin\" marginheight=\"$marginheight\" marginwidth=\"$marginwidth\">\n";
		} else {
		$body = "<body bgcolor=\"#$public_bgcolor\" topmargin=\"$topmargin\" leftmargin=\"$leftmargin\" marginheight=\"$marginheight\" marginwidth=\"$marginwidth\">\n";
	}
?>
<html>
<head>
	<? echo $body; ?>
<title>Preload</title>
<script language="JavaScript1.1">
	var locationAfterPreload = "slideshow.php"
	var lengthOfPreloadBar = 150
	var heightOfPreloadBar = 15
<?
$images = $_SESSION['imagenav'];
$images = explode(",", $images);
$i = 0;
foreach($images as $value){
$i++;
	$slide_show_images = $slide_show_images . "\"slidemark.php?i=" . trim($value) . "\",";
}
 ?>
var yourImages = new Array(<? echo substr($slide_show_images, 0, -1); ?>)
if (document.images) {
	var dots = new Array() 
	dots[0] = new Image(1,1)
	dots[0].src = "images/black.gif" 
	dots[1] = new Image(1,1)
	dots[1].src = "images/blue.gif" 
	var preImages = new Array(),coverage = Math.floor(lengthOfPreloadBar/yourImages.length),currCount = 0
	var loaded = new Array(),i,covered,timerID
	var leftOverWidth = lengthOfPreloadBar%coverage
}
function loadImages() { 
	for (i = 0; i < yourImages.length; i++) { 
		preImages[i] = new Image()
		preImages[i].src = yourImages[i]
	}
	for (i = 0; i < preImages.length; i++) { 
		loaded[i] = false
	}
	checkLoad()
}
function checkLoad() {
	if (currCount == preImages.length) { 
		location.replace(locationAfterPreload)
		return
	}
	for (i = 0; i <= preImages.length; i++) {
		if (loaded[i] == false && preImages[i].complete) {
			loaded[i] = true
			eval("document.img" + currCount + ".src=dots[1].src")
			currCount++
		}
	}
	timerID = setTimeout("checkLoad()",10) 
}
</script>
</head>
<body bgcolor="#FFFFFF">
<center>
<font size="4"><?PHP echo $slide_preload_patient; ?><? echo $i; ?><?PHP echo $slide_preload_patient2; ?></font><p>
<script language="JavaScript1.1">
if (document.images) {
	var preloadBar = ''
	for (i = 0; i < yourImages.length-1; i++) {
		preloadBar += '<img src="' + dots[0].src + '" width="' + coverage + '" height="' + heightOfPreloadBar + '" name="img' + i + '" align="absmiddle">'
	}
	preloadBar += '<img src="' + dots[0].src + '" width="' + (leftOverWidth+coverage) + '" height="' + heightOfPreloadBar + '" name="img' + (yourImages.length-1) + '" align="absmiddle">'
	document.write(preloadBar)
	loadImages()
}
document.write('<p><small><a href="javascript:window.location=locationAfterPreload"><?PHP echo $slide_preload_not; ?></a></small></p>')
</script>
</center>
</body>
</html>