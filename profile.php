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
	
	//UNSET ANY IMAGE VIEWING
	unset($_SESSION['pub_gid']);
	unset($_SESSION['pub_pid']);
	
  $pg = 0;
  $user_id = 0;
  $photos = array();
  
  if(isset($_GET['id']))
  {
    $user_id = $_GET['id'];
  }
  if(isset($_GET['pg']))
  {
    $pg = 1;
  }
  $query = "SELECT * FROM ".($pg ? 'photographers' : 'members')." WHERE id = $user_id";
  $result = mysql_query($query);
  $user = mysql_fetch_object($result);
  
  if($pg)
  {
    $query = "SELECT * FROM uploaded_images WHERE user_uploaded = '$user_id'";
    $photos = mysql_query($query);
    
  }
?>
<html>
	<head>
    <?php echo $script1; ?>
		<? print($head); ?>

    <div class="container">
    <? include("header.php"); ?>
		<div id="main">
			<? include("i_gallery_nav.php"); ?>
      <div class="right-main">
        
        <table cellpadding="0" cellspacing="0"><tr><td valign="top">
		<table cellpadding="0" cellspacing="0" width="765" class="main_table" style="border: 5px solid #<? echo $border_color; ?>;">
			
			<tr>
				<? include("search_bar.php"); ?>
			</tr>
			<tr>
				<td valign="top" height="18">
					<table cellpadding="0" cellspacing="0" width="560" height="100%">
						<tr>
							<td colspan="3" height="5"></td>
						</tr>

						<tr>
							<td class="index_copy_area" colspan="3" height="4"></td>
						</tr>						
						<tr>
							<td colspan="3" valign="top" height="100%" class="homepage_line">
								<table width="100%" border="0">
									<tr>
										<td height="6">Гэрэл зурагчин:</td>
                    <td><?php echo $user->name; ?></td>
									</tr>
									 <tr>
										<td height="6">Имэйл:</td>
                    <td><?php echo $user->email; ?></td>
									</tr> 
								</table>
                <h3>Зургууд</h3>
                <?php while($photo = mysql_fetch_object($photos)): 
                  
                  $qry = "SELECT * FROM photo_package WHERE id = '$photo->reference_id'";
                  $result = mysql_query($qry);
                  $photoref = mysql_fetch_object($result);
                ?>
                  <a href="details.php?gid=<?php echo $photoref->gallery_id ?>&pid=<?php echo $photo->reference_id; ?>">
                    <img src="image.php?src=<?php echo $photo->id; ?>" /></a>
                <?php endwhile; ?>
							</td>
						</tr>
					</table>				
				</td>
			</tr>		
		</table>
        </td>
        <td valign="top">
			<?php
				if($pf_feed_status){
					include('pf_feed.php');
				}
			?>
        </td>
        </tr></table>
		</div> <!-- end class right-main -->
      </div><!-- end main id-->
      </div> <!-- end container class -->
      <? include("footer.php"); ?>
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	
