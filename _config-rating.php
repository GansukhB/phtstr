<?php
	include("database.php");
	require_once("config_public.php");
	$rating_path_db       = ''; // the path to your db.php file (not used yet!)
	$rating_path_rpc      = ''; // the path to your rpc.php file (not used yet!)
	$rating_tableName 		= "ratings";
	
	$rating_unitwidth     = 20; // the width (in pixels) of each rating unit (star, etc.)
	global $rating_votes, $rating_vote, $rating_currently, $rating_rating, $rating_static, $rating_thanks, $rating_cast;
?>