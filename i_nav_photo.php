<div class="most-content">
   <!--most content tab ehlel-->
    <ul class="tab">
        <li class="active"><a href="#tab1"><?php echo $homepage_featured; ?></a></li>
        <li><a href="#tab2"><?php echo $homepage_newest; ?></a></li>
        <li><a href="#tab3"><?php echo $homepage_popular; ?> </a></li>
    </ul>
    <!--most content tab tugsgul-->
    <?php 
      $query = " select id, title, gallery_id from photo_package where featured='1' order by id desc limit 0, 6";
      $featured_result = mysql_query($query);
      
      $query = " select id, title, gallery_id from photo_package order by id desc limit 0, 6";
      $newest_result = mysql_query($query);
      
      $query = " select id, title, gallery_id from photo_package order by code desc limit 0, 6";
      $popular_result = mysql_query($query);
      function get_img_id($pckg_id){
        $query = "select id from uploaded_images where reference_id='$pckg_id' ";
        $result = mysql_query($query);
        $id = mysql_fetch_object($result);
        return $id->id;
      }
    ?>
    <!--tab container ehlel-->
    <div class="tab-container">
      <!--tab1 ehlel-->
        <div class="tab-content" style="display: block;" id="tab1">
            
            <?php while($thmb = mysql_fetch_object($featured_result) ): ?>
              <div align="center" class="most">
                <a href="details.php?gid=<?php echo $thmb->gallery_id; ?>&pid=<?php echo $thmb->id;  ?>">
                  <img src="image_sq.php?src=<?php echo get_img_id($thmb->id); ?>" class="image" >
                  
                  <div class="title"><?php echo $thmb->title; ?></div>
                </a>
              </div>
            <?php endwhile; ?>
        </div>
        <!--tab1 tugsgul-->
        <!--tab2 ehlel-->
        <div class="tab-content" style="display:none;" id="tab2">
            <?php while($thmb = mysql_fetch_object($newest_result) ): ?>
              <div align="center" class="most">
                <a href="details.php?gid=<?php echo $thmb->gallery_id; ?>&pid=<?php echo $thmb->id;  ?>">
                  <img src="image_sq.php?src=<?php echo get_img_id($thmb->id); ?>" class="image">
                  
                  <div class="title"><?php echo $thmb->title; ?></div>
                </a>
              </div>
            <?php endwhile; ?>
        </div>
        <!--tab2 tugsgul-->
        <!--tab3 ehlel-->
        <div class="tab-content" style="display:none;" id="tab3">
          <?php while($thmb = mysql_fetch_object($popular_result) ): ?>
              <div align="center" class="most">
                <a href="details.php?gid=<?php echo $thmb->gallery_id; ?>&pid=<?php echo $thmb->id;  ?>">
                  <img src="image_sq.php?src=<?php echo get_img_id($thmb->id); ?>" class="image">
                  <div class="title"><?php echo $thmb->title; ?></div>
                </a>
              </div>
            <?php endwhile; ?>
        </div>
        <!--tab3 tugsgul-->
    </div>
    <!--tab container tugsgul-->
</div>
