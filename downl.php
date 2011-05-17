<?PHP
	session_start();

	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );
	
	echo $download_text1;
	if($sid){
	echo "<a href=\"download_file2.php?sid=" . $sid . "&pid=" . $pid . "\" onClick=\"return targetopener(this,true)\" class=\"gallery_nav\"\">" . $download_text2 . "</a><br />";
	} else {
	echo "<a href=\"download_file2.php?pid=" . $pid . "\" onClick=\"return targetopener(this,true)\" class=\"gallery_nav\"\">" . $download_text2 . "</a><br />";
	}
	echo "<a href=\"#\" onclick=\"self.close();return false;\" class=\"gallery_nav\">" . $download_text3 . "</a><br>";
?>
<head>
<script>
var howLong = 20000;
t = null;
function closeMe(){
t = setTimeout("self.close()",howLong);
}
</script>
</head>
<body onload="closeMe();self.focus()">
</body>

