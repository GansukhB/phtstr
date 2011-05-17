<?php
session_start();

/*
if($_SESSION['access_type'] != "mgr"){ 
	echo "Operation cannot be performed!<BR>YOU NEED TO LOG IN!";
	exit; 
	}
*/

include("../../database.php");
$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
$setting = mysql_fetch_object($settings_result);

if(md5($setting->access_id) != $_GET['pass']){ 
	echo "Operation cannot be performed!<BR>YOU NEED TO LOG IN!";
	exit; 
	}

	header("Expires: mon, 06 jan 1990 00:00:01 gmt");
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	
?><files>
<?php
$imageFolder = "../../ftp/";
$imageFolder2 = "../ftp/";
if ($handle = opendir($imageFolder)) {
	
	$echo = "";
   /* This is the correct way to loop over the directory. */
   while (false !== ($file = readdir($handle))) {
       $echo .= ($file!="." && $file!="..") ? "\t".'<f name="'.$file.'" size="'.filesize($imageFolder.$file).'" path="'.$imageFolder2.$file.'" />'."\n" : "";
   }
	echo $echo;
   closedir($handle);
}


?>
</files>