
  <h2><?php echo $lightbox_my_lightbox; ?></h2>
    <style>
          .lighbox-main a{
            display:block;
            float:left;
            //text-align: center;
            width:100px;
            height:100px;
            line-height:100px;
            overflow:hidden;
            position:relative;
            z-index:1
          }
          .thumb img{
              float:center;
              position:absolute;
              //top:-20px;
              //left:-50px;
            }
    </style>
    <div class="lighbox-main">
        
        <?php 
          $uid = $_SESSION['sub_member'];
          $query = "select * from lightbox_group where member_id='$uid' ";
          $result = mysql_query($query);
          
          while($lbox=mysql_fetch_object($result)):
        ?>
        <div class="box-content thumb">
          <a href="lightbox.php?lightbox=<?php echo $lbox->id; ?>" title="<?php echo $lbox->name; ?>">
            <?php 
              $query = "select * from lightbox where (member_id='$uid' and reference_id='$lbox->id') order by rand() "; 
              $rslt = mysql_query($query);
              $image = mysql_fetch_object($rslt);
              $image_id = $image->photo_id;
              
              $query = "select id from uploaded_images where reference_id='$image_id' ";
              $rslt = mysql_query($query);
              $thmb = mysql_fetch_object($rslt);
              //echo $query;
            ?>
            <?php if($thmb->id): ?>
              <img src="image.php?src=<?php echo $thmb->id; ?>"  />
            <?php else: ?>
              
            <?php endif; ?>
          </a>
        </div>
        <?php endwhile; ?>
    </div>
