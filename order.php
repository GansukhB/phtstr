<?
	session_start();
	
	include( "database.php" );
	include( "functions.php" );
	include( "config_public.php" );
	
	//ADDED IN PS350 FOR EXTRA SECURITY
	if($setting->force_members == 1 && !$_SESSION['sub_member']){
		echo $order_error_no_login;
		exit;
	}
	
if($_SESSION['id']){
		$id = $_SESSION['id'];
	} else {
		$id = $_SESSION['sub_member'];
	}

if($id != ""){
	$member_result = mysql_query("SELECT * FROM members where id = '$id'", $db);
	$member = mysql_fetch_object($member_result);
}

//UNSET ANY IMAGE VIEWING
	unset($_SESSION['pub_gid']);
	unset($_SESSION['pub_pid']);
	
?>
<html>
	<head>
		<? print($head); ?>
		<center>
        <table cellpadding="0" cellspacing="0"><tr><td valign="top">
		<table cellpadding="0" cellspacing="0" width="765" class="main_table" style="border: 5px solid #<? echo $border_color; ?>;">
			<? include("header.php"); ?>
			<tr>
				<td class="left_nav_header"><? echo $misc_photocat; ?></td>
				<td></td>
				<? include("search_bar.php"); ?>
			</tr>
			<tr>
				<td rowspan="1" valign="top"><? include("i_gallery_nav.php"); ?></td>
				<td background="images/col2_shadow.gif" valign="top"><img src="images/col2_white.gif"></td>
				<td valign="top" height="18">
					<center><table cellpadding="0" cellspacing="0" width="560" height="100%">
						<tr>
							<td colspan="3" height="4"></td>
						</tr>
						<tr>
							<?php
							$crumb = $order_crumb_link;
							include("crumbs.php");
						?>
						</tr>
						<tr>
							<td class="index_copy_area" colspan="3" height="4"></td>
						</tr>							
						<tr>
							<td colspan="3" valign="top" height="100%" class="homepage_line">
								<table width="100%" border="0">
									<tr>
										<td height="6"></td>
									</tr>
									<tr>
										<td class="gallery_copy">	
		<script language="javascript">
	
			function validate() {
				if(document.order.email.value == "") {
					alert("Please enter your <? echo $form_email; ?>.");
					return false;
				}
				if(document.order.phone.value == "") {
					alert("Please enter your <? echo $form_phone; ?>.");
					return false;
				}
				if(document.order.name.value == "") {
					alert("Please enter your <? echo $form_name; ?>.");
					return false;
				}
				if(document.order.address_1.value == "") {
					alert("Please enter your <? echo $form_address1; ?>.");
					return false;
				}
				if(document.order.city.value == "") {
					alert("Please enter your <? echo $form_city; ?>.");
					return false;
				}
				if(document.order.state.value == "") {
					alert("Please enter your <? echo $form_State; ?>.");
					return false;
				}
				if(document.order.zip.value == "") {
					alert("Please enter your <? echo $form_zip; ?>.");
					return false;
				}
				document.order.submit();
			}
		</script>
<form name="order" action="public_actions.php?pmode=send_order"
      enctype="application/x-www-form-urlencoded"
      method="post" onSubmit="return validate()">
</td>
  <tr cellpadding="10">
    <td class="gallery_copy">
   <input type="hidden" name="business" value="<? echo $setting->site_title; ?>" />
   <input type="hidden" name="total" value="<? echo $_SESSION['ses_total']; ?>" />
   <input type="hidden" name="shipping" value="<? echo $_SESSION['ses_shipping']; ?>" />
   <input type="hidden" name="tax" value="<? echo $_SESSION['ses_tax']; ?>" />
   <input type="hidden" name="coupon_id" value="<? echo $_SESSION['coupon_id']; ?>" />
   <input type="hidden" name="coupon" value="<? echo $_SESSION['ses_coupon']; ?>" />
   <input type="hidden" name="item_name" value="<? echo $_SESSION['visitor_id']; ?>" />
   <input type="hidden" name="type" value="<? echo $_GET['type']; ?>" />
   <input type="hidden" name="key" value="<?PHP echo md5($_SESSION['visitor_id'].$setting->access_id); ?>" />
    <br>
    	<? echo $form_email; ?>:
    <br>
      <input type="text" name="email" <?PHP if($_SESSION['form_email1']){ ?>value="<?PHP echo $_SESSION['form_email1']; ?>"<?PHP } else { ?> <? if($member->email != ""){ ?> value="<? echo $member->email; ?>" <? } } ?> size="30" />
    <br><br>
      <? echo $form_phone; ?>:
    <br>
      <input type="text" name="phone" <?PHP if($_SESSION['form_phone1']){ ?>value="<?PHP echo $_SESSION['form_phone1']; ?>"<?PHP } else { ?> <? if($member->phone != ""){ ?> value="<? echo $member->phone; ?>" <? } } ?> size="30" />
    <br><br>
      <? echo $form_name; ?>:
    <br>
      <input type="text" name="name" <?PHP if($_SESSION['form_name1']){ ?>value="<?PHP echo $_SESSION['form_name1']; ?>"<?PHP } else { ?> <? if($member->name != ""){ ?> value="<? echo $member->name; ?>" <? } } ?> size="30" />
    <br><br>
      <? echo $form_address1; ?>:
    <br>
      <input type="text" name="address1" <?PHP if($_SESSION['form_address11']){ ?>value="<?PHP echo $_SESSION['form_address11']; ?>"<?PHP } else { ?> <? if($member->address1 != ""){ ?> value="<? echo $member->address1; ?>" <? } } ?> size="30" />
    <br><br>
      <? echo $form_address2; ?>:
    <br>
      <input type="text" name="address2" <?PHP if($_SESSION['form_address21']){ ?>value="<?PHP echo $_SESSION['form_address21']; ?>"<?PHP } else { ?> <? if($member->address2 != ""){ ?> value="<? echo $member->address2; ?>" <? } } ?> size="30" />
    <br><br>
      <? echo $form_city; ?>:
    <br>
      <input type="text" name="city" <?PHP if($_SESSION['form_city1']){ ?>value="<?PHP echo $_SESSION['form_city1']; ?>"<?PHP } else { ?> <? if($member->city != ""){ ?> value="<? echo $member->city; ?>" <? } } ?> size="30" />
    <br><br>
      <? echo $form_State; ?>:
    <br>
      <input type="text" name="state" <?PHP if($_SESSION['form_state1']){ ?>value="<?PHP echo $_SESSION['form_state1']; ?>"<?PHP } else { ?> <? if($member->state != ""){ ?> value="<? echo $member->state; ?>" <? } } ?> size="30" />
    <br><br>
      <? echo $form_zip; ?>:
    <br>
      <input type="text" name="zip" <?PHP if($_SESSION['form_zip1']){ ?>value="<?PHP echo $_SESSION['form_zip1']; ?>"<?PHP } else { ?> <? if($member->zip != ""){ ?> value="<? echo $member->zip; ?>" <? } } ?> size="30" />
    <br><br>
      <?PHP echo $order_special_notes; ?>
    <br>
      <textarea name="note" rows="6" cols="30"><?PHP if($_SESSION['form_note1']){ ?><?PHP echo $_SESSION['form_note1']; ?><?PHP } ?></textarea>
    <br>
    </td>
    </tr>
<tr><td class="gallery_copy"><input type="submit" name="Form_Submit" value="<?PHP echo $order_submit_form_button; ?>" /></td></tr>
</form>
			<tr><td colspan="6" class="gallery_copy"><?PHP echo $order_form_notice; ?></td></tr>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>				
				</td>
			</tr>
			<? include("footer.php"); ?>			
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
		</center>
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
?>	