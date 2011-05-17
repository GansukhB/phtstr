<?php
	session_start();
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );

	$pg_result = mysql_query("SELECT password FROM photo_galleries where rdmcode = '" . $gal . "'", $db);
	$pg = mysql_fetch_object($pg_result);
	
	if($_POST['password'] == $pg->password){
		session_register("galaccess");
		session_register("gal");
		$_SESSION['galaccess'] = $_POST['gal'];
		$_SESSION['gal'] = $_POST['gal'];
	if($_SESSION['pri_pid'] == "" && $_SESSION['pri_gid'] == ""){
		header("location: pri.php?gid=" . $_POST['gid'] . "&gal=" . $_POST['gal']);
	} else {
		$pid = $_SESSION['pri_pid'];
		$gid = $_SESSION['pri_gid'];
		unset($_SESSION['pri_pid']);
		unset($_SESSION['pri_gid']);
		if($pid && $gid){
		header("location: details.php?gid=" . $gid . "&gal=" . $_POST['gal'] . "&pid=" . $pid);
		} else {
		header("location: pri.php?gid=" . $_POST['gid'] . "&gal=" . $_POST['gal']);
		}
	}
		exit;
	} else {
		header("location: pri.php?gid=" . $_POST['gid'] . "&gal=" . $_POST['gal'] . "&mes=failed");
		exit;
	
		/*
		echo "gal" . $_POST['gal'];
		echo "<br>";
		echo "post " . $_POST['password'] . "<br>";
		echo "db" . $pg->password;
		*/
	}


?>