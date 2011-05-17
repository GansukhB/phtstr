<?php
	if(!$incdb){
		include( "database.php" );
	}
	
	$photo_result = mysql_query("SELECT * FROM photo_package where other_galleries != ''", $db);
	$photo_rows = mysql_num_rows($photo_result);
	while($photo = mysql_fetch_object($photo_result)){
		$new = $photo->other_galleries;
		
		if(substr($new,0,1) != ","){
			$new= "," . $new;
		}	
		if(substr($new,strlen($new)-1,strlen($new)) != ","){
			$new= $new . ",";
		}
		
		$sql = "UPDATE photo_package SET other_galleries='$new' WHERE id = '$photo->id'";
		$result = mysql_query($sql);
		
		//echo $new . "<br />";
	}
	
?>