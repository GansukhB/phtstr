<?PHP
	session_start();

	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );
	
	$photo_result = mysql_query("SELECT id FROM uploaded_images where reference = 'photo_package' and reference_id = '$pid' order by id desc", $db);
	$photo = mysql_fetch_object($photo_result);
	
	session_register("mail_id");
	$_SESSION['mail_id'] = random_gen(16,"");
	
	if($_GET['message'] == "sent"){
		echo $email_photo_successfully;
		exit;
	} else {
		echo $email_emailphoto;
?>
<form name="emailphoto" method="post" action="public_actions.php?pmode=email_photo&pid=<? echo $pid; ?>">
<? if($setting->show_watermark_thumb == 1){ ?>
<img src="thumb_mark.php?i=<? echo $photo->id; ?>" class="photo"><br>
<? } else { ?>
<img src="image.php?src=<? echo $photo->id; ?>" class="photo"><br>
<? } ?>
<input type="hidden" value="<?php echo $_SESSION['mail_id']; ?>" name="reg" />
<input type="hidden" value="<?php echo selfurl(); ?>" name="return" />
<? echo $email_emailto; ?><br>
<input name="email" type="textbox" style="width: 350px;">
<input type="submit" value="go" class="go_button">
</form>
<head>
<script>
var howLong = 3600000;
t = null;
function closeMe(){
t = setTimeout("self.close()",howLong);
}
</script>
</head>
<body onload="closeMe();self.focus()">
</body>
<?
}

