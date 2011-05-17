<?php 
error_reporting(0);
if(ini_get("allow_url_fopen")){
    $ref = $_SERVER['HTTP_HOST'];
    $timeout = 3;
    $old = ini_set('default_socket_timeout', $timeout);
    $dataFile = fopen("http://www.photographyfeed.com/feeder3.php?bgcolor=$pf_bgcolor&bordercolor=$pf_$bordercolor&linkcolor=$pf_$linkcolor&readmorecolor=$pf_$readmorecolor&articles=$pf_$articles&titleonly=$pf_$titleonly&ref=$pf_$ref", 'r');
    ini_set('default_socket_timeout', $old);
    stream_set_timeout($dataFile, $timeout);
    stream_set_blocking($dataFile, 0);
    
    if ($dataFile){
        while (!feof($dataFile)){
            $buffer.= fgets($dataFile, 4096);
            //echo $buffer;
        }		
        fclose($dataFile);
    } else {
        die( "fopen failed for $filename" );
    }
	echo $buffer;	
} else {
    echo "<script language='javascript' src='http://www.photographyfeed.com/feeder2.php?bgcolor=$pf_bgcolor&bordercolor=$pf_bordercolor&linkcolor=$pf_linkcolor&readmorecolor=$pf_readmorecolor&articles=$pf_articles&titleonly=$pf_titleonly&ref=$pf_ref'></script>";
}
error_reporting(E_ALL & ~E_NOTICE);	
?>
