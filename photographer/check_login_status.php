<?
	// CHECK AND SEE IF THE PERSON IS LOGGED IN BEFORE PROCEEDING
	if($_SESSION['access_status']  != "dfjfhkallkdfdmsa1"){
		header("location: login.php?error=1");
		exit;
	}
?>
