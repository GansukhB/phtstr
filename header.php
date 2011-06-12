<?
	include( "database.php" );
	
	if(!$_SESSION['url']){
    session_register("url");
		$_SESSION['url'] = selfurl();
	} else {
	  $_SESSION['url'] = selfurl();
	}
	
	if($_SESSION['memberstat'] == ""){
	$memberstats_rows = mysql_result(mysql_query("SELECT COUNT(id) FROM members"),0);
	session_register("memberstat");
	$_SESSION['memberstat'] = $memberstats_rows;
	$memberstat = $_SESSION['memberstat'];
	} else {
	$memberstat = $_SESSION['memberstat'];
	}
	
	if($_SESSION['photostat'] == ""){
	$photostats_rows = mysql_result(mysql_query("SELECT COUNT(id) FROM photo_package WHERE active = '1'"),0);	
	session_register("photostat");
	$_SESSION['photostat'] = $photostats_rows;
	$photostat = $_SESSION['photostat'];
	} else {
	$photostat = $_SESSION['photostat'];
	}
	
	if($_SESSION['photogstat'] == ""){
	$photogstats_rows = mysql_result(mysql_query("SELECT COUNT(id) FROM photographers"),0);	
  session_register("photogstat");
	$_SESSION['photogstat'] = $photogstats_rows;
	$photogstat = $_SESSION['photogstat'];
	} else {
	$photogstat = $_SESSION['photogstat'];
  }
  
?>
<!--
<?php
	if($setting->show_abanner and $setting->abanner_name){
		$top_banner= explode("-",$setting->abanner_name);
		if($top_banner[1] < 99){
?>


<tr>
   <td colspan="3" align="right">
    </td>
</tr>
<?php
		}
	}
?>
<tr>
   <td colspan="3">
   	<?PHP 
   	if($_SESSION['visitor_flash'] != 1){
   	if(file_exists("swf/thumbslide.swf") or file_exists("swf/featured.swf")){ ?>
  	<center><?php include('random.php'); ?></center>
  	<?PHP } else { ?>
  	<center><?php include('random1.php'); ?></center>
  	<?PHP 
  		}
  	} else {
  		?>
  		<center><?php include('random1.php'); ?></center>
  	<?PHP } ?>
   </td>
  </tr>
  <?PHP
   if($setting->headerbox == 1){
		?>
		<tr>
			<td colspan="3" height="8"></td>
		</tr>
		<tr>
			<td colspan="3" style="padding: 5px 5px 5px 10px;">
		<?PHP 
		copy_area(9,2);
		?>
			</td>
		</tr>
	<?PHP
	}
	?>
  <tr>
   <td colspan="3" valign="middle">
					<table cellpadding="0" cellspacing="0" width="100%" border="0">
						<tr>
							<td align="center" class="top_nav" style="border-left: 0px;"><a href="index.php" class="top_nav"><? echo $top_home; ?></a></td>
							<td align="center" class="top_nav"><a href="about_us.php" class="top_nav"><? echo $top_aboutus; ?></a></td>
							<?php
								if($setting->allow_subs == 1 or $setting->allow_subs_month == 1 or $setting->allow_sub_free == 1){
							?>
								<td align="center" class="top_nav"><a href="subscribe.php" class="top_nav"><? echo $top_subscribe; ?></a></td>
							<?php
								}
							?>
							<td align="center" class="top_nav"><a href="faqs.php" class="top_nav"><? echo $top_faq; ?></a></td>
							<td align="center" class="top_nav"><a href="news.php" class="top_nav"><? echo $top_news; ?></a></td>
							<td align="center" class="top_nav"><a href="support.php" class="top_nav"><? echo $top_support; ?></a></td>
						</tr>
			<?
			if($setting->debug == 1){
			?>
			     <tr>
			       <td align="center" class="top_nav">
			<?
			       echo "Lightbox Data<br>";
			       echo "ID=" . $_SESSION['lightbox_id'];
		  ?>
		        </td>
			      <td align="center" class="top_nav">
			<?
			       echo "Member Data<br>";
			       echo "ID=" . $_SESSION['sub_member'] . "<br>";
			       echo "NAME=" . $_SESSION['mem_name'] . "<br>";
			       echo "LIMIT=" . $_SESSION['mem_down_limit'] . "<br>";
		  ?>
		        </td>
		             <td colspan="4" align="center" class="top_nav">
			<?
			       echo "URL Data<br>";
			       echo "URL=" . $_SESSION['url'] . "<br>";
		  ?>
		        </td>
		      </tr>
		  <?
		    }
		  ?>
					</table>
				</td>
			</tr>-->
      
    <div id="header">
      
        <div class="header">
        	<div class="top-header">
            	<div class="logo"><a href="index.php"><img src="images/logo.png"></a></div>
                <div class="text"><?php echo $allphotos; ?> stock photos</div>
                <div class="search-main">
                	<form action="search.php" id="SearchForm">
                        <div>
                            <label class="input-bg" >
                            <input type="text" value="Search..." onblur="clearText(this)" onfocus="clearText(this)" name="search" class="input">
                            </label>
                            <input type="image" src="images/input-img.gif" class="button">
                        </div>
                    </form>
                </div>
            </div>
            <!--top-header tugsgul-->
            <!--menu ehlel-->
            <div class="menu" style="text-transform:uppercase;">
            	<ul>
                	<li><a href="index.php"><?php echo $top_home; ?></a></li> 
                    <li><a href="faqs.php"><?php echo $top_faq; ?></a></li>  
                    <li><a href="news.php"><?php echo $top_news; ?></a></li>
                    
                    <li><a href="contest.php">Уралдаан</a></li> 
                    <li><a href="./blog">Блог</a></li>  
                    <!--
                    <li><a href="order_status.php"><?php echo $order_crumb_link; ?></a></li>  
                    -->
                    <?php if(trim($_SESSION['mem_name'])!=""): ?>
                      <li><a href="lightbox.php"><?php echo $left_lightbox; ?></a></li>
                    <?php endif; ?>
                    <?php if(!$_SESSION['mem_name']): ?>
                      <li><a href="subscribe.php"><?php echo $left_login; ?></a></li>
                    <?php else: ?>
                      <li><a href="public_actions.php?pmode=logout"><?php echo $left_logout ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <!--menu tugsgul-->
        </div>
    </div>
    
   
