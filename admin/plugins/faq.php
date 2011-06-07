<?php 
  $nav = $_GET['nav'];
	if($execute_nav == 1){
		$nav_order = 2; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "FAQ"; // name of the nav that will appear on the page
	}else {
?>
<h3>Contest</h3>
<div style="text-align: left; margin-left: 20px;">
<table  border="1px solid gray">
  <?
    $banner_dir = "../banner";
    $l_real_dir = realpath($banner_dir);
    
    $query = "SELECT * FROM faqs ORDER BY sort ";
    $result = mysql_query($query);
    
    $pmode = 0;
    
    if(isset($_GET['pmode']))
    {
      $pmode = $_GET['id'];
      
    }
  ?>
  <thead>
    <tr>
      <td>
        Question
      </td>
      <td>
        Sort
      </td>
      
      <td></td>
    </tr>
  </thead>
  <tbody>
    <?php while($banner = mysql_fetch_object($result)): ?>
      <tr>
        <td>
          <?php echo $banner->title; ?>
        </td>
        <td>
          <?php echo $banner->sort; ?>
        </td>
        
        
        <!--
        <td>
          <?php if ( $banner->show ): ?>
            Shown
          <?php else: ?>
            Hidden
          <?php endif; ?>:
          <a href="actions_banner.php?pmode=change_show&id=<?php echo $banner->id; ?>&status=<?php echo $banner->show; ?>">
          <?php if ( $banner->show ): ?>
            Hide
          <?php else: ?>
            Show
          <?php endif; ?>
          </a>
        </td>-->
        <td>
          <a href="<?php echo $_SERVER['PHP_SELF'] ?>?nav=3&pmode=edit&id=<?php echo $banner->id ?>">Edit</a> | 
          <a href="actions_contest.php?pmode=delete&id=<?php echo $banner->id; ?>" onclick="return confirm('Really delete?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
</div>
  <div style="text-align: left; margin-left: 20px;">
  <form name="form" action="actions_faq.php?pmode=<?php echo $pmode ? 'edit&id='.$pmode : 'create'?>" enctype="multipart/form-data" method="post">
  <?php
    if($pmode)
    {
      
      $query = "SELECT * FROM faqs WHERE id='$pmode' ";
      $result = mysql_query($query);
      $obj = mysql_fetch_object($result);
      echo $pmode;
    }
  ?>
    <table>
      <thead>
        <tr>
          <td>
            <h2>New Contest</h2>
          </td>
        </tr>
      </thead>
      <tr>
        <td>Questions</td>
        <td><input type="text" name="contest_title" value="<?php echo $pmode ? $obj->title : '' ?>" /></td>
      </tr>
      <tr>
        <td>Answer</td>
        <td><textarea name="contest_article"><?php echo $pmode ? $obj->article : ''?></textarea></td>
      </tr>
      
      <tr>
        <td>Sort</td>
        <td><input type="text" name="contest_sort" value="<?php echo $pmode ? $obj->sort : ''?>" /></td>
      </tr>
      <tr>
        <td>Type</td>
        <td>
          <select name="contest_type">
            <?php 
              $query = "SELECT * FROM faq_type ORDER BY sort ";
              $result = mysql_query($query);
              while($type = mysql_fetch_object($result)):
                //echo $type->title;
            ?>
                <option value="<?php echo $type->id; ?>" <?php if($obj->type == $type->id) echo 'selected'?> ><?php echo $type->title;?></option>
            <?php 
              endwhile;
            ?>
          </select>
        </td>
      </tr>
      
      <tr>
        <td></td> 
        <td><input type="submit" value="Submit" /></td>
      </tr>
    </table>
  </form>
  </div>
  
<?php } ?>
