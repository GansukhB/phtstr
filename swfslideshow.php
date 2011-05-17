<?PHP
include("database.php");
include("config_public.php");
?>
<html>
<title>Slide Show</title>
<style type="text/css">
<!--
body {
	background-color: #333333;
	margin-left: 10px;
	margin-top: 10px;
	margin-right: 10px;
	margin-bottom: 10px;
}
-->
</style>
<table align="center" valign="middle">
<div id="flashcontent2"/>
<?PHP echo $no_flashplayer; ?>
</div>
<script type="text/javascript" src="js/swfobject.js"></script>
     <script>
      <!--
    	var flashObj = new SWFObject ("swf/slideshow.swf", "Slideshow", "620", "500", 8, "#333333", true);
      	flashObj.addVariable ("watermark", "images/watermark.png");
	  	flashObj.write ("flashcontent2");
      // -->
     </script>
    </table>