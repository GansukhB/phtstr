			<tr>
				<td height="4" colspan="3" class="footer_line"></td>
			</tr>
			<tr><td height="10" colspan="3"></td></tr>
			<tr>
				<td valign="middle" align="center"><span class="cc"><?PHP echo $footer_we_accept; ?><img src="images/cc.gif" align="middle" alt="<?PHP echo $footer_we_accept_alt; ?>"></span></td>
				<td class="footer_div">&nbsp;</td>
				<td valign="middle">
					<table cellpadding="0" cellspacing="0" width="100%">
						<?PHP
   						if($setting->footerbox == 1){
						?>
						<tr>
							<td colspan="3" height="8"></td>
						</tr>
						<tr>
							<td colspan="3" style="padding: 5px 5px 5px 10px;">
						<?PHP 
						copy_area(40,2);
						?>
							</td>
						</tr>
						<?PHP
						}
						?>
						<tr>
							<td class="copyright"><a href="licensing.php" class="footer_links"><?PHP echo $footer_license; ?></a> | <a href="privacy_policy.php" class="footer_links"><?PHP echo $footer_privacy; ?></a> | <a href="terms_of_use.php" class="footer_links"><?PHP echo $footer_terms; ?></a></td>
						</tr>
						<tr>
							<td style="padding: 4px 0px 0px 10px; color: #666666;">
							<? echo $footer_copyright . " " . $setting->site_title . " " . $footer_all_rights; ?>
							</td>
						</tr>	
						<tr>
							<td style="padding: 0px 0px 0px 10px; color: #666666;">
							<? if($setting->author_branding == 1){ ?>
								<? if(!file_exists("nobranding.php")){ ?>
									Powered By <u>PhotoStore – Sell Photos Online</u> by <u>Ktools.net LLC</u>
								<? } ?>
							<? } ?>
							</td>
						</tr>
					</table>			
				</td>
			</tr>
			<tr><td height="10" colspan="3"></td></tr>