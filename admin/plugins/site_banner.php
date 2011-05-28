<?php 
  $nav = $_GET['nav'];
	if($execute_nav == 1){
		$nav_order = 2; // order of the nav, cant be 0!
		$nav_visible = 1; // 1 if you want to see the nav, 0 if its hidden
		$nav_name = "Banner Manage"; // name of the nav that will appear on the page
	}else {
?>
  <h3>Banners</h3>
<div style="text-align: left; margin-left: 20px;">
<table  border="1px solid gray">
  <?
    $banner_dir = "../banner";
    $l_real_dir = realpath($banner_dir);
    
    $query = "SELECT * FROM banner ORDER BY id DESC ";
    $result = mysql_query($query);
    
  ?>
  <thead>
    <tr>
      <td>
        Title
      </td>
      <td>
        Link
      </td>
      <td>
        File Name
      </td>
      <td>
        Show on site
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
          <?php echo $banner->link; ?>
        </td>
        <td>
          <?php echo $banner->file; ?>
        </td>
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
        </td>
        <td>
          <a href="actions_banner.php?pmode=delete&id=<?php echo $banner->id; ?>">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
</div>
  <div style="text-align: left; margin-left: 20px;">
  <form name="form" action="actions_banner.php?pmode=banner_upload" enctype="multipart/form-data" method="post">
    <table>
      <tr>
        <td>Title of banner</td>
        <td><input type="text" name="banner_title" /></td>
      </tr>
      <tr>
        <td>Link url banner</td>
        <td><input type="text" name="banner_link" /></td>
      </tr>
      <tr>
        <td>Choose</td>
        <td><input type="file" name="banner_file" /></td>
      </tr>
      <tr>
        <td></td> 
        <td><input type="submit" value="Upload" /></td>
      </tr>
    </table>
  </form>
  </div>
  
<?php } ?>
