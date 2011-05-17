<?php
	/*
		Database Plugin
	*/
	
	if($execute_nav == 1){
		$nav_order = 188; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "Data"; // name of the nav that will appear on the page
		$actions_page = "actions_data.php";
	} else {
		$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
		$setting = mysql_fetch_object($settings_result);
		
		$currency_result = mysql_query("SELECT * FROM currency where active = '1'", $db);
		$currency = mysql_fetch_object($currency_result);
		
		$mgr_result = mysql_query("SELECT * FROM mgr_users where id = '1'", $db);
		$mgr_users = mysql_fetch_object($mgr_result);
?>
	<table width="700" cellpadding="0" cellspacing="0" bgcolor="#577EC4" style="border: 1px solid #5B8BD8;">
		<tr>
			<td bgcolor="#3C6ABB" align="center" style="padding: 4px; border-bottom: 1px solid #355894;">
				<table cellpadding="0" cellspacing="0" width="95%">
					<tr>	
						<td width="100%" nowrap><font face="arial" color="#ffffff" style="font-size: 11;"><b>DATABASE</b></font>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td bgcolor="#577EC4" align="center" style="padding-top: 10px; padding-bottom: 10px;" background="images/mgr_bg_texture.gif">
				<table width="95%" cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td align="left"><img src="images/mgr_section_header_l.gif"></td>
									<td width="100%" background="images/mgr_section_header_bg.gif" align="center" valign="middle"><b>DATABASE BACKUP</b></td>
									<td align="right"><img src="images/mgr_section_header_r.gif"></td>
								</tr>
							</table>
						</td>
					</tr>
					<? if($_SESSION['access_type'] != "demo"){ ?>
					<tr>
						<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;"><b>SQL File Backup:</b><br>(Click the backup button below to download an SQL file backup of your database.)<br><hr width="90%">
							<form name="backup" action="actions_data.php?pmode=sql" method="post">
							<input type="hidden" value="mgr.php?nav=<? echo $_GET['nav']; ?>&message=saved&order_by=<? echo $_GET['order_by']; ?>&order_type=<? echo $_GET['order_type']; ?>&search=<? echo $_GET['search']; ?>" name="return">
							<input type="submit" value="SQL Backup"><br>
							</form>
							<!---
							<form name="backup_zip" action="actions_data.php?pmode=zip" method="post">
							<input type="hidden" value="mgr.php?nav=<? echo $_GET['nav']; ?>&message=saved&order_by=<? echo $_GET['order_by']; ?>&order_type=<? echo $_GET['order_type']; ?>&search=<? echo $_GET['search']; ?>" name="return">
							<input type="submit" value=" ZIP Backup "><br>
							</form>
							--->
					</tr>
				<? } else { ?>
					<tr>
						<td bgcolor="#5E85CA" class="data_box"><font color="#ffffff" style="font-size: 11;"><b>SQL File Backup:</b><br>(Click the backup button below to download an SQL file backup of your database.)<br><hr width="90%">
							The site is currently running in demo mode so this option is disabled.<br>
					</tr>
				<? } ?>
				</td>
			</tr>
	</table>
</table>
<?
	}
?>