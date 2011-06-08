<?php 
  $nav = $_GET['nav'];
	if($execute_nav == 1){
		$nav_order = 2; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "Contest"; // name of the nav that will appear on the page
	}else {
?>
<div style="text-align: left; margin-left: 20px;">
<table  border="1px solid gray">
  <?
    $banner_dir = "../banner";
    $l_real_dir = realpath($banner_dir);
    
    $query = "SELECT * FROM contest ORDER BY id DESC ";
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
        Title
      </td>
      <td>
        Start date
      </td>
      <td>
        End date
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
          <?php echo $banner->start; ?>
        </td>
        <td>
          <?php echo $banner->end; ?>
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
          <a href="<?php echo $_SERVER['PHP_SELF'] ?>?nav=2&pmode=edit&id=<?php echo $banner->id ?>">Edit</a> | 
          <a href="actions_contest.php?pmode=delete&id=<?php echo $banner->id; ?>" onclick="return confirm('Really delete?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
</div>
  <div style="text-align: left; margin-left: 20px;">
  <form name="form" action="actions_contest.php?pmode=<?php echo $pmode ? 'edit&id='.$pmode : 'create'?>" enctype="multipart/form-data" method="post">
  <?php
    if($pmode)
    {
      
      $query = "SELECT * FROM contest WHERE id='$pmode' ";
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
        <td>Title of contest</td>
        <td><input type="text" name="contest_title" value="<?php echo $pmode ? $obj->title : '' ?>" /></td>
      </tr>
      <tr>
        <td>Description</td>
        <td><textarea name="contest_description"><?php echo $pmode ? $obj->description : ''?></textarea></td>
      </tr>
      <tr>
        <td>
          Start date
        </td>
        <td>
          <input type="text" name="contest_start" value="<?php echo $pmode ? $obj->start : '' ?>" />
          <input type=button value="select" onclick="displayDatePicker('contest_start');">
        </td>
      </tr>
      <tr>
        <td>End date</td>
        <td><input type="text" name="contest_end" value="<?php echo $pmode ? $obj->end : '' ?>" />
        <input type=button value="select" onclick="displayDatePicker('contest_end');">
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
