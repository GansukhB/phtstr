<?php
	session_start();
	include( "check_login_status.php" );
	// Database backup
	include( "../database.php" );
	
	if($_SESSION['access_type'] != "mgr"){ echo "Operation cannot be performed in demo mode"; exit; }
	
	$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
	$db = $db_name;
	
	switch($_REQUEST['pmode']){
	case "sql":
	function data($db){
	mysql_select_db($db); 
	$tables = mysql_list_tables($db); 
		while ($td = mysql_fetch_array($tables)){
		$table = $td[0]; 
		$r = mysql_query("SHOW CREATE TABLE `$table`"); 
	if ($r){ 
		$insert_sql = ""; 
		$d = mysql_fetch_array($r); 
		$d[1] .= ";"; 
		$sql[] = str_replace("\n", "", $d[1]); 
		$table_query = mysql_query("SELECT * FROM `$table`"); 
		$num_fields = mysql_num_fields($table_query); 
	while ($fetch_row = mysql_fetch_array($table_query)){ 
		$insert_sql .= "INSERT INTO $table VALUES("; 
		for ($n=1;$n<=$num_fields;$n++){ 
			$m = $n - 1; 
			$insert_sql .= "'".mysql_real_escape_string($fetch_row[$m])."', "; 
		} 
			$insert_sql = substr($insert_sql,0,-2); 
			$insert_sql .= ");\n"; 
		} 
	if ($insert_sql!= ""){ 
		$sql[] = $insert_sql; 
		} 
	} 
} 
return implode("\r", $sql);
}

//print_r(data($db));
 	 $string = data($db);
 	 $date = date(Y_m_d);
 	 $ext = "sql";
 	 $filename = "data/" . $db . "_" . $date . ".sql";
 	 $filenamehandle = fopen($filename, 'w') or die("can't open file for writing");
 	 fwrite($filenamehandle, $string);
 	 fclose($filenamehandle);
 	 
   $file_extension = "sql";

            switch ($file_extension) {
                case "pdf": $ctype="application/pdf"; break;
                case "exe": $ctype="application/octet-stream"; break;
                case "zip": $ctype="application/zip"; break;
                case "doc": $ctype="application/msword"; break;
                case "xls": $ctype="application/vnd.ms-excel"; break;
                case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
                case "gif": $ctype="image/gif"; break;
                case "png": $ctype="image/png"; break;
                case "jpe": case "jpeg":
                case "jpg": $ctype="image/jpg"; break;
                case "sql": $ctype="applicatoin/txt"; break;
                default: $ctype="application/force-download";
            }

            if (!file_exists($filename)) {
                die("NO FILE HERE");
            }

            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private",false);
            header("Content-Type: $ctype");
            header("Content-Disposition: attachment; filename=\"".basename($filename)."\";");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: ".@filesize($filename));
            set_time_limit(0);
            @readfile("$filename") or die("File not found."); 
   					unlink($filename);
 	 					break;
 	 					exit;
 	 					
 	case "zip":
 	//NOT BEING USED SO EXIT IF SOMEONE VISITS THIS
 	echo "This is not functional";
 	exit;
 	function data($db){
	mysql_select_db($db); 
	$tables = mysql_list_tables($db); 
		while ($td = mysql_fetch_array($tables)){
		$table = $td[0]; 
		$r = mysql_query("SHOW CREATE TABLE `$table`"); 
	if ($r){ 
		$insert_sql = ""; 
		$d = mysql_fetch_array($r); 
		$d[1] .= ";"; 
		$sql[] = str_replace("\n", "", $d[1]); 
		$table_query = mysql_query("SELECT * FROM `$table`"); 
		$num_fields = mysql_num_fields($table_query); 
	while ($fetch_row = mysql_fetch_array($table_query)){ 
		$insert_sql .= "INSERT INTO $table VALUES("; 
		for ($n=1;$n<=$num_fields;$n++){ 
			$m = $n - 1; 
			$insert_sql .= "'".mysql_real_escape_string($fetch_row[$m])."', "; 
		} 
			$insert_sql = substr($insert_sql,0,-2); 
			$insert_sql .= ");\n"; 
		} 
	if ($insert_sql!= ""){ 
		$sql[] = $insert_sql; 
		} 
	} 
} 
return implode("\r", $sql);
}

//print_r(data($db));
 	 $string = data($db);
 	 $date = date(Y_m_d);
 	 $ext = "sql";
 	 $filename = "data/" . $db . "_" . $date . ".zip";
 	 include("zip.php");
 	 if(file_exists("data/".$filename)){
   	unlink("data/".$filename);
  	}
   $archive = new PclZip("data/".$filename);
   $v_list = $archive->create("data", PCLZIP_OPT_REMOVE_PATH, 'data');
 	 $filenamehandle = fopen($filename, 'w') or die("can't open file for writing");
 	 fwrite($filenamehandle, $string);
 	 fclose($filenamehandle);
 	 
   $file_extension = "sql";

            switch ($file_extension) {
                case "pdf": $ctype="application/pdf"; break;
                case "exe": $ctype="application/octet-stream"; break;
                case "zip": $ctype="application/zip"; break;
                case "doc": $ctype="application/msword"; break;
                case "xls": $ctype="application/vnd.ms-excel"; break;
                case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
                case "gif": $ctype="image/gif"; break;
                case "png": $ctype="image/png"; break;
                case "jpe": case "jpeg":
                case "jpg": $ctype="image/jpg"; break;
                case "sql": $ctype="applicatoin/txt"; break;
                default: $ctype="application/force-download";
            }

            if (!file_exists($filename)) {
                die("NO FILE HERE");
            }

            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private",false);
            header("Content-Type: $ctype");
            header("Content-Disposition: attachment; filename=\"".basename($filename)."\";");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: ".@filesize($filename));
            set_time_limit(0);
            @readfile("$filename") or die("File not found."); 
   					unlink($filename);
 	 					break;
 	 					exit;
 	 				}
?>