<div class="banner-main">
  <?php 
    $currentFile = $_SERVER["SCRIPT_NAME"];
    $parts = Explode('/', $currentFile);
    $currentFile = $parts[count($parts) - 1];

  
    $qry = "SELECT * FROM banner WHERE area='$currentFile' ORDER BY rand() ";
    
    $rslt = mysql_query($qry);
    //echo mysql_num_rows($rslt).'hehe';
  ?>
  <?php $count = 0; while($banner = mysql_fetch_object($rslt)  ): ?>
    <?php if($banner->show == '1' && $count < 3): ?>
      <?php 
        $lst = explode('.', strtolower ($banner->file) );
        //print_r($lst);
        if($lst[ count($lst) - 1 ] != "jpg" || $lst[ count($lst) - 1 ] != "jpeg" || 
          $lst[ count($lst) - 1 ] != "gif" || $lst[ count($lst) - 1 ] != "png"): 
      ?>
          <div class="banner"><a href="<?php echo $banner->link; ?>" target="_blank">
            <img src="banner/<?php echo $banner->file; ?>" /></a></div>
        <?php else: ?>
          <div class="banner">
            <object width="320" height="182">
                <param name="movie" value="banner/<?php echo $banner->file; ?>">
                <embed src="banner/<?php echo $banner->file; ?>" width="320" height="182">
                </embed>
            </object>
          </div>
      <?php endif; ?>
    <?php $count++;  endif; ?>
  <?php endwhile; ?>
  <?php if($count < 3): for($i = $count; $i < 3; $i++): ?>
    <div class="banner"><a href="#" target="_blank">
            <img src="images/banner.jpg" /></a>
    </div>
  <?php endfor; endif; ?>
</div>
