<?php
	
	include("database.php");
	include("functions.php");
	include( "config_public.php" );
	
	$_RESULT=$_POST['_RESULT'];
	$_ERROR_CODE=$_POST['_ERROR_CODE'];
	$_ERROR_SOURCE=$_POST['_ERROR_SOURCE'];
	$_ERROR_MESSAGE=$_POST['_ERROR_MESSAGE'];
	$_ERROR_DETAIL=$_POST['_ERROR_DETAIL'];
	$_VARIABLE1=$_POST['VARIABLE1'];
	$_EMAIL=$_POST['VARIABLE2'];
	$_TOTAL=$_POST['VARIABLE3'];
	
?>
<? if($_GET['message'] == "failed"){ ?>
<?PHP echo $mygate_ipn_sub_failed_message; ?>
<? } else { ?>
<?PHP echo $mygate_ipn_sub_thank_you; ?>
<?
	 }
	/*
  echo "<br>";
	echo $_RESULT . "=result<br>";
	echo $_ERROR_CODE . "=error code<br>";
	echo $_ERROR_SOURCE . "=error source<br>";
	echo $_ERROR_MESSAGE . "=error message<br>";
	echo $_ERROR_DETAIL . "=error details<br>";
	echo $_VARIABLE1 . "=variable1<br>";
	echo $_EMAIL . "=email known as variable2<br>";
	echo $_TOTAL . "=total known as variable3<br><br>";
	*/
	
	foreach($_GET as $key => $value){				
		if(is_array($value)){
			foreach($value as $key2 => $value2){
				//${$key}[$key2] = addslashes($value2);
				${$key}[$key2] = quote_smart($value2);								
			}	
		} else {
			${$key} = quote_smart($value);
		}
	}
	
	if($_RESULT == 0){
		
		$email = urldecode($_EMAIL);
		
			// GET VISITOR INFORMATION
			$member_result = mysql_query("SELECT id FROM members where order_num = '$_VARIABLE1'", $db);
			$member = mysql_fetch_object($member_result);
			
			$today = date("Ymd");
			
			$down_y_new = $setting->down_limit_y;
			$down_m_new = $setting->down_limit_m;
			
			// UPDATE ORDER INFORMATION
			$sql = "UPDATE members SET status='1',paypal_email='$email',added='$today',down_limit_m='$down_m_new',down_limit_y='$down_y_new' WHERE id = '$member->id'";
			$result = mysql_query($sql);
			
			// SEND EMAIL TO BUYER FOR COMPLETED ORDER
			$to = $email;
			email(16,$to);
	} else {		
		echo $mygate_ipn_sub_pending;
	}	
?>