<?
if(!$_SESSION['ip']){
  $ip = $_SERVER['REMOTE_ADDR'];
  
  $usr_rows = mysql_result(mysql_query("SELECT COUNT(id) FROM counter WHERE ip = '$ip'"),0);
				
	session_register("ip");
	$_SESSION['ip'] = $ip;
	
	$added = date("Ymd");

	if($usr_rows < 1){
			$sql = "INSERT INTO counter (ip,date) VALUES ('$ip','$added')";
			$result = mysql_query($sql);
		}
	}
	
	if($_SESSION['counter_display'] == ""){
	$usr_count_rows = mysql_result(mysql_query("SELECT COUNT(id) FROM counter"),0);
	
	$count_display = $usr_count_rows;
	session_register("counter_display");
	$_SESSION['counter_display'] = $usr_count_rows;
	} else {
  $count_display = $_SESSION['counter_display'];
	}
?>