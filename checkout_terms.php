<?
	session_start();
	$content_id = "101";
	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );
?>
<table align="center" valign="top" width="490px" >
	<tr>
		<td width="450px" STYLE="word-wrap: break-word">
	<?
  copy_area($content_id,2); 
 ?>
    </td>
  </tr>
</table>