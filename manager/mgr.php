<?
	session_start();
	include( "check_login_status.php" );
	include( "config_mgr.php" );
	
	$welcome_page = 1; // 1 = ON / 0 = OFF
	if(file_exists("../nobranding.php")){
	$welcome_page = 0; // automatically turn off ktools welcome page is the nobranding option is installed
	}

	if($_GET['nav'] == ""){
		$nav = 200;
	}
	
	$metatags .= "\t\t<META HTTP-EQUIV=\"Content-Type\" content=\"text/html; charset=" . $setting->charset . "\">" . "\n";
?>
<html>
	<head>
		<?PHP print $metatags; ?>
		<title><? echo $manager_title; ?></title>
		<link rel="stylesheet" href="mgr_style.css">
		<script language="javascript">
		function demo_mode(){
			alert("Sorry. You can not use this feature while in DEMO MODE.")
			return
		}
		</script>
		<?php // added for PS330 and support for tabs in the store manager settings
		?>
		<script type="text/javascript" src="../js/tab.js"></script>
		<script type="text/javascript">
			document.write('<style type="text/css">.tabber{display:none;}<\/style>');
		</script>
		<?php // END
		?>
	</head>
	<body bgcolor="#13387E" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
		<center>
			<table>
            	<tr>
                	<td valign="top" width="150" style="padding-top: 20px;">                    	
                        <table cellpadding="0" cellspacing="0" width="150">
                            <tr>
                                <td align="left" background="images/mgr_nav_off_bg_dark.gif"><img src="images/mgr_int_header_left.gif"><!--<img src="images/mgr_nav_left2.gif">--></td>
                                <td align="center" background="images/mgr_nav_off_bg_dark.gif">
                                <td align="right" background="images/mgr_nav_off_bg_dark.gif"><img src="images/mgr_int_header_right.gif"></td>
                            </tr>
                            <tr>
                                <td colspan="3" background="images/mgr_ln_bg.gif">
									<?php
										echo "<div style=\"padding: 5px 4px 5px 4px; border: 1px solid #6192dc; margin: 2px 7px 3px 7px; background-color: #234d8e;\"><a href=\"mgr.php\" class=\"manager_nav_on\">Home</a></div>";
                                        sort($nav_array);
                                        $total_plugins = count($nav_array);
                                        
                                        for($i = 0; $i < $total_plugins; $i++) {
                                            if($_GET['nav'] == $i and $_GET['nav'] != null){
                                                // NAV ON
                                                //echo "<td><img src=\"images/mgr_nav_on_left.gif\"></td>";
                                                //echo "<td background=\"images/mgr_nav_on_bg.gif\" style=\"padding-left: 2px;padding-right: 2px; line-height: 1;\" align=\"center\"><font color=\"#C8D6ED\" style=\"font-size: 10;\"><a href=\"mgr.php?nav=" . $i . "\" class=\"manager_nav_on\">" . $nav_array[$i][2] . "</a></td>";
                                                //echo "<td><img src=\"images/mgr_nav_on_right.gif\"></td>";
                                                echo "<div style=\"padding: 5px 4px 5px 4px; border: 1px solid #6192dc; margin: 2px 7px 3px 7px; background-color: #03204a;\"><a href=\"mgr.php?nav=" . $i . "\" class=\"manager_nav_on\">" . $nav_array[$i][2] . "</a></div>";
                                            }
                                            else{
                                                // PUT OFF LEFT GRAPHIC IF IT ISN'T NEXT TO ON NAV
                                                if(($nav + 1) != $i){
                                                    //echo "<td><img src=\"images/mgr_nav_off_left.gif\"></td>";
                                                }
                                                // NAV OFF
                                                //echo "<td background=\"images/mgr_nav_off_bg.gif\" style=\"padding-left: 2px;padding-right: 2px;padding-top: 2px; line-height: 1;\" align=\"center\"><font color=\"#C8D6ED\" style=\"font-size: 10;\"><a href=\"mgr.php?nav=" . $i . "\" class=\"manager_nav_off\">" . $nav_array[$i][2] . "</a></td>";
                                                //echo "<td><img src=\"images/mgr_nav_off_right.gif\"></td>";
                                                echo "<div style=\"padding: 5px 4px 5px 4px; border: 1px solid #6192dc; margin: 2px 7px 3px 7px; background-color: #234d8e;\"><a href=\"mgr.php?nav=" . $i . "\" class=\"manager_nav_on\">" . $nav_array[$i][2] . "</a></div>";
                                            }
                                        //}
                                    }
                                    ?>
                       			</td>
                        	</tr>
                            <tr>
                                <td colspan="3"><img src="images/mgr_ln_footer.gif"></td>
                            </tr>
                    	</table>
                    </td>
                	<td valign="top">
                        <table cellpadding="0" cellspacing="0" width="765">
                            <tr>
                                <td height="20"></td>
                            </tr>
                            <tr>
                                <td>
                                    <table cellpadding="0" cellspacing="0" width="765">
                                        <tr>
                                            <td align="left" background="images/mgr_nav_off_bg_dark.gif"><img src="images/mgr_int_header_left.gif"><!--<img src="images/mgr_nav_left2.gif">--></td>
                                            <td align="center" background="images/mgr_nav_off_bg_dark.gif">
                                                
                                                
                                                
                                                
                                            </td>
                                            <td align="right" background="images/mgr_nav_off_bg_dark.gif"><img src="images/mgr_int_header_right.gif"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td background="images/mgr_int_bg.gif" align="center" valign="top" style="padding-top: 10px; padding-bottom: 10px;">
                                    <?
                                        if($nav == 200){
                                            // SCRIPT VALIDATION CHECK
                                    ?>
                                        <table>
                                            <tr>
                                                <td><font face="arial" color="#ffffff" style="font-size: 11;"><b>Welcome to the <? echo $manager_title; ?> Website Manager</b></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                <?
                                                    error_reporting(0);
													$mgrnews = 1;
                                                    if($_SESSION['access_type'] != "demo" and $mgrnews == 1){
                                                        if(ini_get("allow_url_fopen") and $welcome_page){
                                                            $welcome_page = "";
                                                            //echo $welcome_page;
                                                            
                                                            $timeout = 3;
                                                            $old = ini_set('default_socket_timeout', $timeout);
                                                            $dataFile = fopen($welcome_page, 'r');
                                                            ini_set('default_socket_timeout', $old);
                                                            stream_set_timeout($dataFile, $timeout);
                                                            stream_set_blocking($dataFile, 0);
                                                            
                                                            if ($dataFile){
                                                                while (!feof($dataFile)){
                                                                    $buffer = fgets($dataFile, 4096);
                                                                    echo $buffer;
                                                                }		
                                                                fclose($dataFile);
                                                            } 
                                                            //else {
                                                            //	die( "fopen failed for $filename" ) ;
                                                            //}
                                                            
                                                        }
                                                    } else {
                                                        echo "<br><br><br><br><br><br><br><br><br><br><br><br><br>";
                                                    }
													error_reporting(E_ALL & ~E_NOTICE);
                                                    
                                                ?>
                                                </td>
                                            </tr>
                                        </table>
                                    <?
                                        }
                                        else{
                                            $execute_nav = 0;
                                            include("plugins/" . $nav_array[$_GET['nav']][3]);
                                        }
                                    ?>					
                                </td>
                            </tr>
                            <tr>
                                <td align="right" background="images/mgr_int_bg2.gif" style="padding-right: 10px;">
                                <?PHP if(!file_exists("../nobranding.php")){ ?>
                                &nbsp;
                                <?PHP } ?>
                                 <a href="mgr_actions.php?pmode=logout"><img src="images/mgr_button_logout.gif" border="0" alt="Logout Of The Manager"></a></td>
                            </tr>
                            <tr>
                                <td><img src="images/mgr_int_footer.gif"></td>
                            </tr>
                            <tr>
                                <td height="5"></td>
                            </tr>
                            <? if(!file_exists("../nobranding.php") && $_GET['show'] != 1){ ?>
                                <tr>
                                    <td align="center" class="footer">PhotoStore Version <b><? echo $ktools_product_version; ?></b> Installed</td>
                                </tr>
                                <tr>
                                    <td align="center" class="footer"><b><? echo $manager_title; ?></b>  |  Powered By <? if($author_website != ""){ ?><a href="<? echo $author_website; ?>" target="new" class="footer_link"><? } ?><img src="images/mgr_ktools_logo.gif" border="0" align="absmiddle"></a></td>
                                </tr>
                            <? } ?>
                            <tr>
                                <td height="5"></td>
                            </tr>
                        </table>
            		</td>
                </tr>
            </table>
		</center>
	</body>
</html>
<?
	if($db != ""){
		mysql_close($db);
	}
	$ip = $_SERVER['SERVER_ADDR'];
	
?>	