<?php
function rating_bar($id,$units='',$static='') { 
require('_config-rating.php'); // get the db connection info

//set some variables
$ip = $_SERVER['REMOTE_ADDR'];
if (!$units) {$units = 10;}
if (!$static) {$static = FALSE;}

// get votes, values, ips for the current rating bar
$query=mysql_query("SELECT total_votes, total_value, used_ips FROM $rating_tableName WHERE id='$id' ")or die(" Error: ".mysql_error());


// insert the id in the DB if it doesn't exist already
if (mysql_num_rows($query) == 0) {
$sql = "INSERT INTO $rating_tableName (`id`,`total_votes`, `total_value`, `used_ips`) VALUES ('$id', '0', '0', '')";
$result = mysql_query($sql);
}

$numbers=mysql_fetch_assoc($query);


if ($numbers['total_votes'] < 1) {
	$count = 0;
} else {
	$count=$numbers['total_votes']; //how many votes total
}
$current_rating=$numbers['total_value']; //total number of rating added together and stored
$tense=($count==1) ? $rating_vote : $rating_votes; //plural form votes/vote

// determine whether the user has voted, so we know how to draw the ul/li
$voted=mysql_num_rows(mysql_query("SELECT used_ips FROM $rating_tableName WHERE used_ips LIKE '%".$ip."%' AND id='".$id."' ")); 

// now draw the rating bar
$rating_width = @number_format($current_rating/$count,2)*$rating_unitwidth;
$rating1 = @number_format($current_rating/$count,1);
$rating2 = @number_format($current_rating/$count,2);


if ($static == 'static') {

		$static_rater = array();
		$static_rater[] .= "\n".'<div class="ratingblock">';
		$static_rater[] .= '<div id="unit_long'.$id.'">';
		$static_rater[] .= '<ul id="unit_ul'.$id.'" class="unit-rating" style="width:'.$rating_unitwidth*$units.'px;">';
		$static_rater[] .= '<li class="current-rating" style="width:'.$rating_width.'px;">'. $rating_currently . $rating2.'/'.$units.'</li>';
		$static_rater[] .= '</ul>';
		$static_rater[] .= '<p class="static">'. $rating_rating .'<strong> '.$rating1.'</strong>/'.$units.' ('.$count.' '.$tense.' '.$rating_cast.') <em>'.$rating_static.'</em></p>';
		$static_rater[] .= '</div>';
		$static_rater[] .= '</div>'."\n\n";

		return join("\n", $static_rater);


} else {

      $rater ='';
      $rater.='<div class="ratingblock">';

      $rater.='<div id="unit_long'.$id.'">';
      $rater.='  <ul id="unit_ul'.$id.'" class="unit-rating" style="width:'.$rating_unitwidth*$units.'px;">';
      $rater.='     <li class="current-rating" style="width:'.$rating_width.'px;">'.$rating_currently . $rating2.'/'.$units.'</li>';

      for ($ncount = 1; $ncount <= $units; $ncount++) { // loop from 1 to the number of units
           if(!$voted) { // if the user hasn't yet voted, draw the voting stars
              $rater.='<li><a href="db.php?j='.$ncount.'&amp;q='.$id.'&amp;t='.$ip.'&amp;c='.$units.'" title="'.$ncount.' out of '.$units.'" class="r'.$ncount.'-unit rater" rel="nofollow">'.$ncount.'</a></li>';
           }
      }
      $ncount=0; // resets the count

      $rater.='  </ul>';
      $rater.='  <p';
      if($voted){ $rater.=' class="voted"'; }
      $rater.='>'. $rating_rating . '<strong> '.$rating1.'</strong>/'.$units.' ('.$count.' '.$tense.' '.$rating_cast.')';
      $rater.='  </p>';
      $rater.='</div>';
      $rater.='</div>';
      return $rater;
 }
}
?>