<?php
	session_start();
		
	# INFOSHARE ON/OFF
	$infoshare = "on";
	include("version.php");
	include("database.php");	
	
	$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
	
	function trimcodes($v){
		$z = substr($v,0,10);
		return $z;
	}
	
	$codes = explode(",",$_GET['act']);
	$codes = array_map("trimcodes",$codes);

	if(in_array(substr($setting->access_id,0,10),$codes) and $infoshare == "on"){
	//if($infoshare == "on"){
	
		//foreach($codes as $value){
		//	echo $value . "<br />";
		//}
	
		# CHECK FOR VERSION
		echo $ktools_product_version;
		
		# CHECK FOR PHOTOGRAPHERS ADDON
		if(file_exists("photog_main.php")){
			# INSTALLED
			echo "|yes";
			if(file_exists("paversion.php")){
				include("paversion.php");
				echo "|$pa_version";
			} else {
				echo "|>3.1.1";
			}
		} else {
			# INSTALLED
			echo "|no";
			# VERSION
			echo "|0";
		}
		
		# MEMBERS
		$mem_rows = mysql_result(mysql_query("SELECT COUNT(id) FROM members WHERE status = '1'"),0);
		echo "|$mem_rows";
		
		# SALES		
		//$cart_sum = mysql_result(mysql_query("SELECT SUM(price) as 'csum',COUNT(id) as 'cid' FROM visitors where status = '1'"),0);
		
		$cart_result = mysql_query("SELECT SUM(price) as 'csum',COUNT(id) as 'cid' FROM visitors where status = '1'", $db);
		$cart = mysql_fetch_object($cart_result);
		echo "|$cart->csum";
		echo "|$cart->cid";
		
		# PHOTOGRAPHERS
		$photog_sum = mysql_result(mysql_query("SELECT COUNT(id) FROM photographers WHERE status = 1"),0);
		echo "|$photog_sum";
	
		# VISITS
		echo "|";
		include("counter.php");
		
		# PHOTOS
		$photos_sum = mysql_result(mysql_query("SELECT COUNT(id) FROM photo_package WHERE active = 1"),0);
		echo "|$photos_sum";
		
		# Currency
		$currency_result = mysql_query("SELECT code FROM currency where active = '1'", $db);
		$currency = mysql_fetch_object($currency_result);
		echo "|$currency->code";
		
		//echo "|$setting->access_id-$_GET[act]";
		
		// UPLOAD MAX FILESIZE
		echo "|".ini_get("upload_max_filesize");
		// MAX INPUT TIME
		echo "|".ini_get("max_input_time");
		// MEMORY LIMIT
		echo "|".ini_get("memory_limit");
		// MAX EXECUTION TIME
		echo "|".ini_get("max_execution_time");
	
	}
		
?>
