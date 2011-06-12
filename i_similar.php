<div class="r-right-content">
  <h1>Similar images</h1>
    <?php 
      $gallery_id = $_GET['gid'];
      $similar_result = array();
      //echo $query;
      function get_img_id($pckg_id){
        $query = "select id from uploaded_images where reference_id='$pckg_id' ";
        $result = mysql_query($query);
        $id = mysql_fetch_object($result);
        return $id->id;
      }
      
      $keyword_text = $package->keywords;
      $keywords = explode(",", $keyword_text);
      //print_r($keywords);
      shuffle($keywords);
      
      $is_added = array();
      $limit = 10;
      
      foreach($keywords as $word)
      {
        $searcher = "SELECT * FROM photo_package where active = '1' and photog_show = '1' and keywords like '%$word%'";
        $result = mysql_query($searcher);
        
        while($photo = mysql_fetch_object($result) )
        {
          if(count($similar_result) == $limit)
          {
            goto end;
          }
          if(!$is_added[$photo->id] && $photo->id != $package->id)
          {
            array_push($similar_result, $photo);
            $is_added[$photo->id] = true;
          }
          
        }
        
      }
      end:
    ?>
    <?php foreach($similar_result as $thmb): ?>
      <div class="image-content">
        <div class="image"><a href="details.php?gid=<?php $gallery_id; ?>&pid=<?php echo $thmb->id; ?>"><img src="image_sq.php?src=<?php echo get_img_id($thmb->id); ?>"></a></div>
        <div align="center" class="title-image"><?php echo $thmb->title; ?></div>
      </div>
    <?php endforeach; ?>
    
    
    
</div>
