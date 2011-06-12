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
			<!--
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
        -->
<div class="left-search-main">
                	<div class="top-bg"></div>
                    <!--left search main ehlel-->
                    <div class="main">
                      <!--
                    	<form name="search" action="search.php" method="link" onSubmit="return validateForm(search);" style="margin: 0px; padding: 0px;">
                        	  
                            
                            <label>
                            	<div class="title">Зурагны төрөл</div>
                                <div>
                                	<select>
                                      <option>Нийт төрөл</option>
                                      <option>Most Popular</option>
                                      <option>osCommerce Templates</option>
                                      <option>Premium Templates</option>
                                      <option>Most Popular</option>
                                      <option>osCommerce Templates</option>
                                      <option>Premium Templates</option>
                                    </select>
                                </div>
                            </label>
                            
                            
                            
                            <label>
                            	<div class="title"><? echo $search_search; ?></div>
                                <div>
                                  <input type="textbox" class="text" name="search" class="search_box">
                                </div>
                            </label>
                            
                            <input type="radio" name="match_type" value="all" <? if($_SESSION['search_match_type'] == "all"){ echo "checked"; } ?>> 
                              <? echo $search_match_all; ?> <br />
                            <input type="radio" name="match_type" value="any" <? if($_SESSION['search_match_type'] == "any"){ echo "checked"; } ?>> 
                              <? echo $search_match_any; ?><br><? if($setting->hide_id != 1){ ?>
                            <input type="radio" name="match_type" value="id" <? if($_SESSION['search_match_type'] == "id"){ echo "checked"; } ?>> 
                              <? echo $search_match_id; ?>   <br /> <? } ?>
                            <input type="radio" name="match_type" value="exact" <? if($_SESSION['search_match_type'] == "exact"){ echo "checked"; } ?>> 
                              <? echo $search_match_exact; ?>
								
                            <label>
                            	<button>хайх</button>
                                <button class="clear">арилгах</button>
                            </label>
                        </form>onSubmit="return validateForm(search);" 
                        -->
                        
                        <form name="search" method="post" action="search.php" style="margin: 0px; padding: 0px;">
                        	  
                            <label>	<div class="title">Type of pictures</div></label>
                            <label>
                              <div class="label"><input type="checkbox" name="type[]" value="photo"><div class="type">Photo</div></div>
                            </label>
                            <label>
                              <div class="label"><input type="checkbox" name="type[]" value="vector"><div class="type">Vector</div></div>
                            </label>
                            
                            <label>	<div class="title">Orientation</div></label>
                            <label>
                                <div class="label"><input type="checkbox" name="orient[]" value="vertical"><div class="type">Vertical</div></div>
                            </label>
                            <label>
                                <div class="label"><input type="checkbox" name="orient[]" value="horizontal"><div class="type">Horizontal</div></div>
                            </label>
                            <label>
                                <div class="label"><input type="checkbox" name="orient[]" value="panaroma"><div class="type">Panaroma</div></div>
                            </label>
                            <label>
                            	<div class="title">Category</div>
                                <div>
                                	<select name="category">
                                    <option value="0">Any category</option>
                                    <?php
                                      $result1 = mysql_query($query); 
                                      while($item = mysql_fetch_object($result1)):
                                    ?>
                                      <option value="<?php echo $item->id; ?>">
                                        <?php 
                                          if($_SESSION['lang'] != 'English'){
                                            if(trim($item->{ 'title_'.$_SESSION['lang']}) != "")
                                              echo $item->{ 'title_'.$_SESSION['lang']};
                                            else echo $item->title;
                                          }
                                          else echo $item->title;
                                        ?>
                                      </option>
                                    <?php 
                                      endwhile; 
                                    ?>     
                                  </select>
                                </div>
                            </label>
                            <label>
                            	<div class="title">Keywords</div>
                                <div>
                                	<input class="text" type="text" name="keyword">
                                </div>
                            </label>
                            <label>
                            	<div class="title">Contributor</div>
                                <div>
                                	<input class="text" type="text" name="contributor">
                                </div>
                            </label>
                            <label>
                            	<button>Search</button>
                                <button class="clear" onclick="this.form.reset();">Clear</button>
                            </label>
                        </form>
                    </div>
                    <!--left search main tugsgul-->
                    <div class="bottom-bg"></div>
                </div>          
          
          
<?PHP } ?>
