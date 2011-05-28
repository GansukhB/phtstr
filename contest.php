<?
	session_start();

	$page_title       = ""; // PAGE TITLE FOR THIS PAGE - IF BLANK USE DEFAULT TITLE	
	$meta_keywords    = ""; // KEYWORD METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	$meta_description = ""; // DESCRIPTION METATAGS FOR THIS PAGE - IF BLANK USE DEFAULT
	
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );
	
	// VISITOR ID
	if(!$_SESSION['visitor_id']){
		session_register("visitor_id");
		$_SESSION['visitor_id'] = random_gen(16,"");
	}
	
	unset($_SESSION['imagenav']);
	
	//UNSET ANY IMAGE VIEWING
	unset($_SESSION['pub_gid']);
	unset($_SESSION['pub_pid']);
	
?>
<html>
	<head>
		<script language=JavaScript src='./js/xns.js'></script>
    <?php echo $script1; ?>
	<? print($head); ?>
  <div class="container">
			<? include("header.php"); ?>
			
      <div id="main">
				
				
				<? //include("search_bar.php"); ?>
			
				<? include("i_gallery_nav.php"); ?>
        
        <div class="right-main">
           <?php
               $date = date("Ymd");
              if(!isset($_GET['id'])):
                
                
                $query = "select * from contest ORDER BY end";
                $result = mysql_query($query);
                
           ?>
						    <h2>Current contest</h2>
                  <ul>
                    <?php 
                      while($cont = mysql_fetch_object($result)):
                        //echo "$date ".$cont->start.' '.$cont->end.'<br />';
                        if($date >= $cont->start && $date <= $cont->end ){
                    ?>
                      <li><a href="contest.php?id=<?php echo $cont->id; ?>"><?php echo $cont->title; ?></a></li>
                    <?php } endwhile; ?>
                  </ul>
                <h2>Incoming contests</h2>
                  <ul>
                    <?php 
                      $result = mysql_query($query);
                      
                      while($cont = mysql_fetch_object($result)):
                      
                        if($cont->start > $date){
                    ?>
                      <li><a href="contest.php?id=<?php echo $cont->id; ?>"><?php echo $cont->title; ?></a></li>
                    <?php } endwhile; ?>
                  </ul>
                <h2>Past contests</h2>
                  <ul>
                    <?php 
                      $result = mysql_query($query);
                      while($cont = mysql_fetch_object($result)):
                        if($cont->end < $date){
                    ?>
                      <li><a href="contest.php?id=<?php echo $cont->id; ?>"><?php echo $cont->title; ?></a></li>
                    <?php } endwhile; ?>
                  </ul>
            <?php else: ?>
            
              <?php 
                $id = $_GET['id'];
                $query = "select * from contest where id='$id'";
                $result = mysql_query($query);
                
                $contest = mysql_fetch_object($result);          
              ?>
              <h2><?php echo $contest->title ?></h2>
              <p><?php echo $contest->description ?></p>
              <?php 
                if($date > $contest->end)
                  echo "Тэмцээний хугацаа дууссан байна.";
                elseif($date < $contest->start) 
                  echo "Тэмцээн хараахан эхлээгүй байна.";
                echo "Үргэлжлэх хугацаа: ".$contest->start." - ".$contest->end;
                
              ?>
              
            <?php endif; ?>
		</div> <!-- end class right main -->
      </div> <!-- end id main -->
    </div> <!-- end container -->
    <? include("footer.php"); ?>	
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	
