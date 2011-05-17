<?php 
//read folder
$names = array();
$folder=opendir("logo/"); 
while ($file = readdir($folder)){ 
	$ext=strtolower(substr($file,-4));
	if($ext==".jpg"||$ext==".gif"||$ext=="jpeg"||$ext==".png"||$ext==".swf"||$ext==".flv"){
		if($file and $file != "." and $file != ".."){
			$names[] = $file;
		}
	}	
} 
closedir($folder);
//sort file names in array
sort($names);


if($names){
srand ((double) microtime() * 10000000);

$rand_keys = array_rand($names,1);

$dimensions = getimagesize("logo/" . $names[$rand_keys]);

//ADDED IN PS350 FOR ADDED URL LINKS
$result = mysql_query("SELECT url FROM links WHERE filename = '$names[$rand_keys]'", $db);
$rs_header_link = mysql_fetch_object($result);
if($rs_header_link->url == "nolink"){
	$rs_hlink = $setting->site_url;
} else {
	$rs_hlink = $rs_header_link->url;
}
$ext=strtolower(substr($names[$rand_keys],-3));
if($ext == "jpg" or $ext == "gif" or $ext == "png"){
echo "<a href=\"".$rs_hlink."\"><img src=\"logo/" . $names[$rand_keys] . "\" $dimensions[3] border=\"0\" /></a>";
}
if($ext == "swf" or $ext == "flv"){
	?>
	<div id="flashcontent"/>
							<script type="text/javascript" src="js/swfobject.js"></script>
                            <script>
                                <!--
                                    var flashObj = new FlashObject ("logo/<?php echo $names[$rand_keys]; ?>", "Logo", "<?PHP echo $dimensions[0]; ?>", "<?PHP echo $dimensions[1]; ?>", "#FFFFFF", true);
                                    flashObj.write ("flashcontent");
                                // -->
                            </script>
    </div>
    
<?PHP
	}
}
?>