<td class="search_bar">
<?PHP if($setting->search_onoff == 1){ ?>
<script language="javascript" type="text/javascript">
function validateForm(search)
{

if(""==document.forms.search.search.value)
{
alert("<? echo $search_alert; ?>");
return false;
}

}
</script>
					<table width="100%">
						<tr>
							<td width="400" nowrap>
								<div style="background-color: #E8E8E8; border: 1px solid #AFAFAF; padding: 3px;">
								<form name="search" action="search.php" method="link" onSubmit="return validateForm(search);" style="margin: 0px; padding: 0px;">
									<? echo $search_search; ?> <input type="textbox" name="search" class="search_box"> <input type="submit" value="<? echo $search_button; ?>" class="go_button">
									<br />
								<input type="radio" name="match_type" value="all" <? if($_SESSION['search_match_type'] == "all"){ echo "checked"; } ?>> <? echo $search_match_all; ?> | <input type="radio" name="match_type" value="any" <? if($_SESSION['search_match_type'] == "any"){ echo "checked"; } ?>> <? echo $search_match_any; ?><br><? if($setting->hide_id != 1){ ?><input type="radio" name="match_type" value="id" <? if($_SESSION['search_match_type'] == "id"){ echo "checked"; } ?>> <? echo $search_match_id; ?>   &nbsp;| <? } ?><input type="radio" name="match_type" value="exact" <? if($_SESSION['search_match_type'] == "exact"){ echo "checked"; } ?>> <? echo $search_match_exact; ?>
								</form>
								</div>
							</td>
						</tr>
					</table>
<?PHP } ?>
			  </td>