<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>View Sample</title>
</head>

<?php
	$vidheight = $_GET['width']*.75;
?>

<body>

<div align="center">
<object width="<?php echo $_GET['width']; ?>" height="<?php echo $vidheight; ?>" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0">
<param name="movie" value="swf/flashsample.swf">
<param name="quality" value="best">
<param name="loop" value="true">
<param name="FlashVars" value="myfile=<?php echo $_GET['file']; ?>">
<EMBED SRC="swf/flashsample.swf" LOOP="true" QUALITY="best" FlashVars="myfile=<?php echo $_GET['file']; ?>" WIDTH="<?php echo $_GET['width']; ?>" HEIGHT="<?php echo $vidheight; ?>">
</object>
</div>

</body>
</html>
