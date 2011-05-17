<?
	session_start();
	include( "config_mgr.php" );
	
	if(!$_GET['image_path']){
		$image_path = "../uploaded_images/";
	}
	else{
		$image_path = $_GET['image_path'];
	}
?>
<html>
	<head>
		<title>Caption Editor</title>
		<link rel="stylesheet" href="mgr_style.css">
		<script language="javascript">
		function demo_mode(){
			alert("Sorry. You can not use this feature while in DEMO MODE.")
			return
		}
		function save_data() {
			var agree=confirm("Save your changes?");
			if (agree) {
				document.data_form.action = "mgr_actions.php?pmode=update_image_caption";
				document.data_form.submit();
			}
			else {
				false
			}
		}
		</script>
	</head>
	<body background="images/mgr_bg_texture.gif" bgcolor="#13387E" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
		<center>
			<table cellpadding="0" cellspacing="0" width="90%">
				<tr>
					<td height="15"></td>
				</tr>
				<form name="data_form" method="post">
				<input type="hidden" name="id" value="<? echo $_GET['id']; ?>">
				<input type="hidden" name="return" value="image_caption_editor.php?id=<? echo $_GET['id']; ?>&message=updated">
				<input type="hidden" name="image_path" value="<? echo $image_path; ?>">
				<? if($_GET['message'] == "updated"){ ?>
				<tr>
					<td valign="middle">
						<table>
							<tr>
								<td valign="middle"><img src="images/mgr_check6_loop_3.gif" valign="absmiddle"></td>
								<td valign="middle"><font color="#FFE400" style="font-size: 10;">Your caption has been updated.</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<? } ?>
				<tr>
					<?
						$image_result = mysql_query("SELECT id,filename,caption FROM uploaded_images where id =" . $_GET['id'], $db);
						$rows = mysql_num_rows($image_result);
						$image = mysql_fetch_object($image_result);
						
					?>
					<td bgcolor="#5E85CA" class="data_box">
						<table width="100%">
							<tr>
								<td colspan="2">
								<font face="arial" color="#ffffff" style="font-size: 11;"><b>Caption</b><br>
								<input type="text" value="<? echo $image->caption; ?>" name="image_caption" style="font-size: 11; width: 300; border: 1px solid #000000;">
								</td>
							</tr>
							<tr>
								<td><img src="<? echo $image_path . "i_" . $image->filename; ?>" style="border: 1px solid #476DB0"></td>
								<td align="right" valign="bottom"><a href="javascript:window.close();"><img src="images/mgr_button_close.gif" border="0"></a>&nbsp;<? if($access_type ==  "demo"){ echo "<a href=\"javascript:demo_mode();\">"; } else { echo "<a href=\"javascript:save_data();\">"; } ?><img src="images/mgr_button_save.gif" border="0"></a></td>								
							</tr>
						</table>						
					</td>
				</tr>
				</form>
			</table>
		</center>
	</body>
</html>