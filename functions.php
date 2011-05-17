<?php
		// ADDED IN PS 3.4
		function mod_photolink($photo_id,$gallery_id,$photo_title,$gallery_title="",$styleclass){
			global $setting;

			//$mod_rewrite = 1;
			if($setting->modrw){
				if(!$gallery_title){
					$gal_result = mysql_query("SELECT title FROM photo_galleries WHERE id = '$gallery_id'");
					$gal = mysql_fetch_object($gal_result);
					$gal_title = $gal->title;
				} else {
					$gal_title = $gallery_title;
				}
				
				$replacethese = array("\"","'","\'","/","_&","&_","&","!",",","+","=","?");
				
				$gal_title = str_replace(" ","_",$gal_title);
				$gal_title = str_replace($replacethese,"",html_entity_decode($gal_title));
				$gal_title = substr($gal_title,0,20);
				
				$p_title = str_replace(" ","_",html_entity_decode($photo_title));
				$p_title = str_replace($replacethese,"",$p_title);


			echo "<a href=\"" . $gal_title . "_g" . $gallery_id . "-" . $p_title . "_p" . $photo_id . ".html\" class=\"$styleclass\">";

			} else {

			echo "<a href=\"details.php?gid=" . $gallery_id . "&pid=" . $photo_id . "\" class=\"$styleclass\">";
			}

		
		}
		
		function mod_photolink_short($photo_id,$gallery_id,$photo_title,$gallery_title="",$styleclass){
			global $setting;

			//$mod_rewrite = 1;
			if($setting->modrw){
				if(!$gallery_title){
					$gal_result = mysql_query("SELECT title FROM photo_galleries WHERE id = '$gallery_id'");
					$gal = mysql_fetch_object($gal_result);
					$gal_title = $gal->title;
				} else {
					$gal_title = $gallery_title;
				}
				
				$replacethese = array("\"","'","\'","/","_&","&_","&","!",",","+","=","?");				
				$gal_title = str_replace(" ","_",$gal_title);
				$gal_title = str_replace($replacethese,"",html_entity_decode($gal_title));
				$gal_title = substr($gal_title,0,20);
				
				$p_title = str_replace(" ","_",html_entity_decode($photo_title));
				$p_title = str_replace($replacethese,"",$p_title);


			echo $gal_title . "_g" . $gallery_id . "-" . $p_title . "_p" . $photo_id . ".html";

			} else {

			echo "details.php?gid=" . $gallery_id . "%26pid=" . $photo_id;
			}

		
		}
		
		function mod_photolink_short_noecho($photo_id,$gallery_id,$photo_title,$gallery_title="",$styleclass){
			global $setting;
			if($setting->modrw){
				if(!$gallery_title){
					$gal_result = mysql_query("SELECT title FROM photo_galleries WHERE id = '$gallery_id'");
					$gal = mysql_fetch_object($gal_result);
					$gal_title = $gal->title;
				} else {
					$gal_title = $gallery_title;
				}
				$replacethese = array("\"","'","\'","/","_&","&_","&","!",",","+","=","?");
				$gal_title = str_replace(" ","_",$gal_title);
				$gal_title = str_replace($replacethese,"",html_entity_decode($gal_title));
				$gal_title = substr($gal_title,0,20);
				$p_title = str_replace(" ","_",html_entity_decode($photo_title));
				$p_title = str_replace($replacethese,"",$p_title);
			return $gal_title . "_g" . $gallery_id . "-" . $p_title . "_p" . $photo_id . ".html";
			} else {
			return "details.php?gid=" . $gallery_id . "&pid=" . $photo_id;
			}
		}
		
		function mod_gallerylink($title,$id,$styleclass){
			global $setting;

			//$mod_rewrite = 1;
			if($setting->modrw){
				$replacethese = array("\"","'","\'","/","_&","&_","&","!",",","+","=","?");
				
				$title2 = str_replace(" ","_",$title);
				$title2 = str_replace($replacethese,"",html_entity_decode($title2));
				$title2 = substr($title2,0,20);

				echo "<a href=\"" . $title2 . "_g$id.html\" class=\"$styleclass\">$title</a></span>";

			} else {
				echo "<a href=\"gallery.php?gid=$id\" class=\"$styleclass\">$title</a></span>";
			}
		}
		
		function mod_clean($input){
			$replacethese = array("\"","'","\'","/","_&","&_","&","!",",","+","=","?");
			$input = str_replace(" ","_",$input);
			$input = str_replace($replacethese,"",html_entity_decode($input));			
			$input = substr($input,0,20);
			return $input;
		}
		
		//Added in PS330 for the email template editor
		function email($email_content_id, $to){
			
			include("database.php");
			$email_result = mysql_query("SELECT subject,article FROM email_copy where id = '$email_content_id'", $db);
			$email = mysql_fetch_object($email_result);
			
			$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
			$setting = mysql_fetch_object($settings_result);
			if($setting->emailchar != ""){
				$charset = "; charset=" . $setting->emailchar;
			} else {
				$charset = "";
			}
		switch($email_content_id){
			
		case "20":
		global $cart_order_id, $amount, $status;
			$link = "<a href=\"" . $setting->site_url . "/download.php?order=" . $cart_order_id . "\">" . $setting->site_url . "/download.php?order=" . $cart_order_id . "</a>";
			$from = $setting->support_email;
			$from_site = $setting->support_email;
			
			$subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
    	$subject = str_replace("{LINK}", $link, $subject);
			$subject = str_replace("{ORDER#}", $cart_order_id, $subject);
			$subject = str_replace("{TOTAL_AMOUNT}", $amount, $subject);
			$subject = str_replace("{PAYMENT_STATUS}", $status, $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
    	$message = str_replace("{LINK}", $link, $message);
			$message = str_replace("{ORDER#}", $cart_order_id, $message);
			$message = str_replace("{TOTAL_AMOUNT}", $amount, $message);
			$message = str_replace("{PAYMENT_STATUS}", $status, $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";

    	mail($to, $subject, $message, $headers);
		break;
		exit;
			
		case "19":
			$link = "<a href=\"" . $setting->site_url ."/login.php\">" . $setting->site_url . "/login.php</a>";
			$contact_us = "<a href=\"" . $setting->site_url . "/support.php\">Contact Us</a>";
			$from = $setting->support_email;
			$from_site = $setting->support_email;
			$subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
			$subject = str_replace("{LINK}", $link, $subject);
			$subject = str_replace("{CONTACT_US}", $contact_us, $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
			$message = str_replace("{LINK}", $link, $message);
			$message = str_replace("{CONTACT_US}", $contact_us, $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
    	
			mail($to, $subject, $message, $headers);
		break;
		exit;
		
		case "18":
		global $cart_order_id, $amount, $status;
			$link = "<a href=\"" . $setting->site_url . "/download.php?order=" . $cart_order_id . "\">" . $setting->site_url . "/download.php?order=" . $cart_order_id . "</a>";
			$from = $setting->support_email;
			$from_site = $setting->support_email;
			$subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
    	$subject = str_replace("{LINK}", $link, $subject);
			$subject = str_replace("{ORDER#}", $cart_order_id, $subject);
			$subject = str_replace("{TOTAL_AMOUNT}", $amount, $subject);
			$subject = str_replace("{PAYMENT_STATUS}", $status, $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
    	$message = str_replace("{LINK}", $link, $message);
			$message = str_replace("{ORDER#}", $cart_order_id, $message);
			$message = str_replace("{TOTAL_AMOUNT}", $amount, $message);
			$message = str_replace("{PAYMENT_STATUS}", $status, $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
    	
    	mail($to, $subject, $message, $headers);
		break;
		exit;
			
		case "17":
		global $grand_total, $order_num;
		  $link = "<a href=\"" . $setting->site_url . "/download.php?order=" . $order_num . "\">" . $setting->site_url . "/download.php?order=" . $order_num . "</a>";
			$contact_us = "<a href=\"" . $setting->site_url . "/support.php?order_info=" . $order_num . "\">contact us</a>";
			$from = $setting->support_email;
			$name = $_POST['name'];
			$from_site = $setting->support_email;
		  $subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
    	$subject = str_replace("{LINK}", $link, $subject);
    	$subject = str_replace("{NAME}", $name, $subject);
			$subject = str_replace("{ORDER#}", $order_num, $subject);
			$subject = str_replace("{CONTACT_US}", $contact_us, $subject);
			$subject = str_replace("{TOTAL_AMOUNT}", $grand_total, $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
    	$message = str_replace("{LINK}", $link, $message);
    	$message = str_replace("{NAME}", $name, $message);
			$message = str_replace("{ORDER#}", $order_num, $message);
			$message = str_replace("{CONTACT_US}", $contact_us, $message);
			$message = str_replace("{TOTAL_AMOUNT}", $grand_total, $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
			mail($to, $subject, $message, $headers);
		break;
		exit;
			
		case "16":
			$link = "<a href=\"" . $setting->site_url ."/login.php\">" . $setting->site_url . "/login.php</a>";
			$contact_us = "<a href=\"" . $setting->site_url . "/support.php\">Contact Us</a>";
			$from = $setting->support_email;
			$from_site = $setting->support_email;
			$subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
			$subject = str_replace("{LINK}", $link, $subject);
			$subject = str_replace("{CONTACT_US}", $contact_us, $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
			$message = str_replace("{LINK}", $link, $message);
			$message = str_replace("{CONTACT_US}", $contact_us, $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
			mail($to, $subject, $message, $headers);
		break;
		exit;
			
		case "15":
		global $_VARIABLE1, $amount, $status;
		  $link = "<a href=\"" . $setting->site_url . "/download.php?order=" . $_VARIABLE1 . "\">" . $setting->site_url . "/download.php?order=" . $_VARIABLE1 . "</a>";
			$from = $setting->support_email;
			$from_site = $setting->support_email;
		  $subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
    	$subject = str_replace("{LINK}", $link, $subject);
			$subject = str_replace("{ORDER#}", $_VARIABLE1, $subject);
			$subject = str_replace("{TOTAL_AMOUNT}", $amount, $subject);
			$subject = str_replace("{PAYMENT_STATUS}", $status, $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
    	$message = str_replace("{LINK}", $link, $message);
			$message = str_replace("{ORDER#}", $_VARIABLE1, $message);
			$message = str_replace("{TOTAL_AMOUNT}", $amount, $message);
			$message = str_replace("{PAYMENT_STATUS}", $status, $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
			mail($to, $subject, $message, $headers);
		break;
		exit;
			
		case "14":
		  $link = "<a href=\"" . $setting->site_url ."/login.php\">" . $setting->site_url . "/login.php</a>";
			$contact_us = "<a href=\"" . $setting->site_url . "/support.php\">Contact Us</a>";
			$from = $setting->support_email;
			$from_site = $setting->support_email;
			$subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
			$subject = str_replace("{LINK}", $link, $subject);
			$subject = str_replace("{CONTACT_US}", $contact_us, $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
			$message = str_replace("{LINK}", $link, $message);
			$message = str_replace("{CONTACT_US}", $contact_us, $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
			mail($to, $subject, $message, $headers);
		break;
		exit;
		
		case "13":
			$from = $setting->support_email;
			$from_site = $setting->support_email;
			$subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
			mail($to, $subject, $message, $headers);
		break;
		exit;
		
		case "12":
			$link = "<a href=\"" . $setting->site_url ."/login.php\">" . $setting->site_url . "/login.php</a>";
			$contact_us = "<a href=\"" . $setting->site_url . "/support.php\">Contact Us</a>";
			$from = $setting->support_email;
			$from_site = $setting->support_email;
			$subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
			$subject = str_replace("{LINK}", $link, $subject);
			$subject = str_replace("{CONTACT_US}", $contact_us, $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
			$message = str_replace("{LINK}", $link, $message);
			$message = str_replace("{CONTACT_US}", $contact_us, $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
			mail($to, $subject, $message, $headers);
		break;
		exit;
		
		case "11":
		global $link, $order_num;
			$link = "<a href=\"" . $setting->site_url . $link . $order_num . "\">" . $setting->site_url . $link . $order_num . "</a>";
			$from = $setting->support_email;
			$from_site = $setting->support_email;
			$subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
			$subject = str_replace("{LINK}", $link, $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
			$message = str_replace("{LINK}", $link, $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
			mail($to, $subject, $message, $headers);
		break;
		exit;
		
		case "10":
			global $name, $number, $text;
			$from = $setting->support_email;
			$from_site = $setting->support_email;
			$subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
			$subject = str_replace("{NUMBER}", $number, $subject);
			$subject = str_replace("{PHOTOGRAPHER}", $name, $subject);
			$subject = str_replace("{MESSAGE}", $text, $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
			$message = str_replace("{NUMBER}", $number, $message);
			$message = str_replace("{PHOTOGRAPHER}", $name, $message);
			$message = str_replace("{MESSAGE}", $text, $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
			mail($to, $subject, $message, $headers);
		break;
		exit;
		
		case "9":
			$pgfers_result = mysql_query("SELECT name,email FROM photographers where id = '" . $_SESSION['photog_id'] . "'", $db);
			$pgfer = mysql_fetch_object($pgfers_result);
			$photographer_name = $pgfer->name;
			$photographer_email = $pgfer->email;
			$from = $photographer_email;
			$from_site = $setting->support_email;
			$number_of = $_SESSION['number_of'];
			
			$subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
			$subject = str_replace("{NUMBER}", $number_of, $subject);
			$subject = str_replace("{PHOTOGRAPHER}", $photographer_name, $subject);
			$subject = str_replace("{EMAIL}", $photographer_email, $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
			$message = str_replace("{NUMBER}", $number_of, $message);
			$message = str_replace("{PHOTOGRAPHER}", $photographer_name, $message);
			$message = str_replace("{EMAIL}", $photographer_email, $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
			mail($to, $subject, $message, $headers);
		break;
		exit;
		
		case "8":
			$from = $setting->support_email;
			$from_site = $setting->support_email;
			$subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
			mail($to, $subject, $message, $headers);
		break;
		exit;
		
		case "7":
			$from = $setting->support_email;
			$from_site = $setting->support_email;
			$subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
			$subject = str_replace("{NAME}", $_POST['name'], $subject);
			$subject = str_replace("{EMAIL}", $_POST['email'], $subject);
			$subject = str_replace("{PHONE}", $_POST['phone'], $subject);
			$subject = str_replace("{ADDRESS1}", $_POST['address1'], $subject);
			$subject = str_replace("{ADDRESS2}", $_POST['address2'], $subject);
			$subject = str_replace("{CITY}", $_POST['city'], $subject);
			$subject = str_replace("{STATE}", $_POST['state'], $subject);
			$subject = str_replace("{ZIP}", $_POST['zip'], $subject);
			$subject = str_replace("{COUNTRY}", $_POST['country'], $subject);
			$subject = str_replace("{BIO}", $_POST['bio'], $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
			$message = str_replace("{NAME}", $_POST['name'], $message);
			$message = str_replace("{EMAIL}", $_POST['email'], $message);
			$message = str_replace("{PHONE}", $_POST['phone'], $message);
			$message = str_replace("{ADDRESS1}", $_POST['address1'], $message);
			$message = str_replace("{ADDRESS2}", $_POST['address2'], $message);
			$message = str_replace("{CITY}", $_POST['city'], $message);
			$message = str_replace("{STATE}", $_POST['state'], $message);
			$message = str_replace("{ZIP}", $_POST['zip'], $message);
			$message = str_replace("{COUNTRY}", $_POST['country'], $message);
			$message = str_replace("{BIO}", $_POST['bio'], $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
			mail($to, $subject, $message, $headers);
		break;
		exit;
		
		case "6":
			$link = "<a href=\"" . $setting->site_url . "/ps_action.php?pmode=activate&email=" . $_POST['email'] . "&password=" . md5($setting->access_id) . "\">" . $setting->site_url . "/ps_action.php?pmode=activate&email=" . $_POST['email'] . "&password=" . md5($setting->access_id) . "</a>";
			$from = $setting->support_email;
			$from_site = $setting->support_email;
			$subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
    	$subject = str_replace("{LINK}", $link, $subject);
			$subject = str_replace("{NAME}", $_POST['name'], $subject);
			$subject = str_replace("{EMAIL}", $_POST['email'], $subject);
			$subject = str_replace("{PHONE}", $_POST['phone'], $subject);
			$subject = str_replace("{ADDRESS1}", $_POST['address1'], $subject);
			$subject = str_replace("{ADDRESS2}", $_POST['address2'], $subject);
			$subject = str_replace("{CITY}", $_POST['city'], $subject);
			$subject = str_replace("{STATE}", $_POST['state'], $subject);
			$subject = str_replace("{ZIP}", $_POST['zip'], $subject);
			$subject = str_replace("{COUNTRY}", $_POST['country'], $subject);
			$subject = str_replace("{BIO}", $_POST['bio'], $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
    	$message = str_replace("{LINK}", $link, $message);
			$message = str_replace("{NAME}", $_POST['name'], $message);
			$message = str_replace("{EMAIL}", $_POST['email'], $message);
			$message = str_replace("{PHONE}", $_POST['phone'], $message);
			$message = str_replace("{ADDRESS1}", $_POST['address1'], $message);
			$message = str_replace("{ADDRESS2}", $_POST['address2'], $message);
			$message = str_replace("{CITY}", $_POST['city'], $message);
			$message = str_replace("{STATE}", $_POST['state'], $message);
			$message = str_replace("{ZIP}", $_POST['zip'], $message);
			$message = str_replace("{COUNTRY}", $_POST['country'], $message);
			$message = str_replace("{BIO}", $_POST['bio'], $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
			mail($to, $subject, $message, $headers);
		break;
		exit;
		
		case "5":

			$link = "<a href=\"" . $setting->site_url . "/download.php?order=" . $_POST['cart_order_id'] . "\">" . $setting->site_url . "/download.php?order=" . $_POST['cart_order_id'] . "</a>";
			if($_POST['credit_card_processed'] == "Y"){
				$payment_status = "Completed";
			} else {
				$payment_status = "Pending";
			}
			$from = $setting->support_email;
			$from_site = $setting->support_email;
			$subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
    	$subject = str_replace("{LINK}", $link, $subject);
			$subject = str_replace("{ORDER#}", $_POST['cart_order_id'], $subject);
			$subject = str_replace("{PAYMENT_STATUS}", $payment_status, $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
    	$message = str_replace("{LINK}", $link, $message);
			$message = str_replace("{ORDER#}", $_POST['cart_order_id'], $message);
			$message = str_replace("{PAYMENT_STATUS}", $payment_status, $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
    	mail($to, $subject, $message, $headers);
		exit;
		break;
		
		case "4":
			
			$link = "<a href=\"" . $setting->site_url . "/download.php?order=" . $_POST['item_number'] . "\">" . $setting->site_url . "/download.php?order=" . $_POST['item_number'] . "</a>";
			$amount = $_POST['mc_gross'];
			$amount = doubleval($amount); 
			$amount = sprintf("%.2f", $amount); 
			$from = $setting->support_email;
			$from_site = $setting->support_email;
			$subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
    	$subject = str_replace("{LINK}", $link, $subject);
			$subject = str_replace("{ORDER#}", $_POST['item_number'], $subject);
			$subject = str_replace("{TOTAL_AMOUNT}", $amount, $subject);
			$subject = str_replace("{PAYMENT_STATUS}", $_POST['payment_status'], $subject);
			
			$message = $email->article;
			$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
    	$message = str_replace("{LINK}", $link, $message);
			$message = str_replace("{ORDER#}", $_POST['item_number'], $message);
			$message = str_replace("{TOTAL_AMOUNT}", $amount, $message);
			$message = str_replace("{PAYMENT_STATUS}", $_POST['payment_status'], $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
    	mail($to, $subject, $message, $headers);
		exit;
		break;
		
		case "3":
		
			$sendfrom = explode(",",$_POST['email']);
			$from = $sendfrom[0];
			$from_site = $setting->support_email;
		  $subject = $email->subject;
			$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
    	$subject = str_replace("{NAME}", sanitize($_POST['name']), $subject);
			$subject = str_replace("{EMAIL}", sanitize($_POST['email']), $subject);
			$subject = str_replace("{COMMENT}", sanitize($_POST['comments']), $subject);
			
			$message = $email->article;
		  $message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
			$message = str_replace("{NAME}", sanitize($_POST['name']), $message);
			$message = str_replace("{EMAIL}", sanitize($_POST['email']), $message);
			$message = str_replace("{COMMENT}", sanitize($_POST['comments']), $message);
			
			$headers  = "From: $from_site\r\n";
			$headers .= "Reply-To: $from\r\n";
    	$headers .= "Content-type: text/html\r\n";
    	if($_POST and $_POST['reg'] == $_SESSION['mail_id'] and $_POST['reg'] != ""){		
    	mail($to, $subject, $message, $headers);
    	} else {
    	echo "Sorry we can't process this email as we don't have record of you being on this site.";
    	}	
			header("location: support.php?message=sent");
		exit;
		break;
			
		case "2":
		global $pid;
		$member_result = mysql_query("SELECT email,name FROM members where id = '" . $_SESSION['sub_member'] . "'", $db);
		$member = mysql_fetch_object($member_result);
    
    $from = $member->email;
    $from_site = $setting->support_email;
    $subject = $email->subject;
		$subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
    $subject = str_replace("{MEMBER_NAME}", $member->name, $subject);
 															
						$package_result = mysql_query("SELECT id,title,description,gallery_id FROM photo_package where id = '$pid'", $db);
						$package = mysql_fetch_object($package_result);
						
						$photo_result = mysql_query("SELECT id FROM uploaded_images where reference = 'photo_package' and reference_id = '$package->id' order by id desc", $db);
						$photo = mysql_fetch_object($photo_result);
						
		  $message = $email->article;
		  $message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
			$message = str_replace("{MEMBER_NAME}", $member->name, $message);
    	$message.= "<table width=\"95%\">";
    	$message.= "<tr>";
    	$message.= $misc_emailmessage;
    	if($setting->show_watermark_thumb == 1){
    	$message.= "<td width=\"300\" align=\"left\" valign=\"middle\" style=\"padding: 0px 0px 0px 20px;\"><a href=\"" . $setting->site_url . "/details.php?gid=" . $package->gallery_id . "&sgid=" . $_GET['sgid'] . "&pid=" . $package->id . "\"><img src=\"" . $setting->site_url . "/thumb_mark.php?i=" . $photo->id . "\" class=\"photos\" border=\"0\"><br>  Click for details</a></td>";
    	} else {
    	$message.= "<td width=\"300\" align=\"left\" valign=\"middle\" style=\"padding: 0px 0px 0px 20px;\"><a href=\"" . $setting->site_url . "/details.php?gid=" . $package->gallery_id . "&sgid=" . $_GET['sgid'] . "&pid=" . $package->id . "\"><img src=\"" . $setting->site_url . "/image.php?src=" . $photo->id . "\" class=\"photos\" border=\"0\"><br>  Click for details</a></td>";
   		}
   		$message.= "<td width=\"250\" align=\"left\" valign=\"top\" style=\"padding: 20px 10px 10px 10px;\">";
   		$message.= "<b>Photo Title:</b><br />" . $package->title . "</br>";
   		$message.= "<b>Description:</b><br />" . $package->description . "</td>";
   		$message.= "<hr width=\"90%\">";
   		$message.= "</tr>";
   		$message.= "</table>";

    		$headers  = "From: $from_site\r\n";
				$headers .= "Reply-To: $from\r\n";
    		$headers .= "Content-type: text/html\r\n";
    		if($_POST and $_POST['reg'] == $_SESSION['mail_id'] and $_POST['reg'] != ""){		
    		mail($to, $subject, $message, $headers);
    		header("location: " . $_POST['return'] . "&message=sent");
    		  exit;
    		} else {
    			echo "Sorry can not be sent, we don't have record of you visiting our site.";
    	    exit;
    	  }
    exit;
    break;
    
		case "1":
		$member_result = mysql_query("SELECT name,email FROM members where id = '" . $_SESSION['sub_member'] . "'", $db);
		$member = mysql_fetch_object($member_result);
		
		$lightbox_result = mysql_query("SELECT photo_id FROM lightbox where reference_id = '" . $_SESSION['lightbox_id'] . "' and member_id = '" . $_SESSION['sub_member'] . "' order by id Desc", $db);
		$lightbox_rows = mysql_num_rows($lightbox_result);
		
		$lightbox_name_result = mysql_query("SELECT name FROM lightbox_group WHERE id = '" . $_SESSION['lightbox_id'] . "'", $db);
		$lightbox_name = mysql_fetch_object($lightbox_name_result);
		
    $from = $member->email;
    $from_site = $setting->support_email;
    $subject = $email->subject;
    $subject = str_replace("{SITE_TITLE}", $setting->site_title, $subject);
    $subject = str_replace("{MEMBER_NAME}", $member->name, $subject);
    $subject = str_replace("{LIGHTBOX_NAME}", $lightbox_name->name, $subject);
 		$subject = str_replace("{NOTES}", $_POST['note'], $subject);
		
		$message = $email->article;
		$message = str_replace("{LIGHTBOX_NAME}", $lightbox_name->name, $message);
		$message = str_replace("{NOTES}", $_POST['note'], $message);
		$message = str_replace("{SITE_TITLE}", $setting->site_title, $message);
		$message = str_replace("{MEMBER_NAME}", $member->name, $message);
		while($lightbox = mysql_fetch_object($lightbox_result)){
															
						$package_result = mysql_query("SELECT id,title,description,gallery_id FROM photo_package where id = '$lightbox->photo_id'", $db);
						$package = mysql_fetch_object($package_result);
						
						$photo_result = mysql_query("SELECT id FROM uploaded_images where reference = 'photo_package' and reference_id = '$package->id' order by id desc", $db);
						$photo = mysql_fetch_object($photo_result);
						    		
    		$message.= "<table width=\"95%\">";
    		$message.= "<tr>";
    		if($setting->show_watermark_thumb == 1){
    		$message.= "<td width=\"300\" align=\"left\" valign=\"middle\" style=\"padding: 0px 0px 0px 20px;\"><a href=\"" . $setting->site_url . "/details.php?gid=" . $package->gallery_id . "&sgid=" . $_GET['sgid'] . "&pid=" . $package->id . "\"><img src=\"" . $setting->site_url . "/thumb_mark.php?i=" . $photo->id . "\" class=\"photos\" border=\"0\"><br>  Click for details</a></td>";
    		} else {
    		$message.= "<td width=\"300\" align=\"left\" valign=\"middle\" style=\"padding: 0px 0px 0px 20px;\"><a href=\"" . $setting->site_url . "/details.php?gid=" . $package->gallery_id . "&sgid=" . $_GET['sgid'] . "&pid=" . $package->id . "\"><img src=\"" . $setting->site_url . "/image.php?src=" . $photo->id . "\" class=\"photos\" border=\"0\"><br>  Click for details</a></td>";
   			}
   			$message.= "<td width=\"250\" align=\"left\" valign=\"top\" style=\"padding: 20px 10px 10px 10px;\">";
   			$message.= "<b>Photo Title:</b><br />" . $package->title . "</br>";
   			$message.= "<b>Description:</b><br />" . $package->description . "</td>";
   			$message.= "<hr width=\"90%\">";
   			$message.= "</tr>";
   			$message.= "</table>";
   		}
    		$headers  = "From: $from_site\r\n";
				$headers .= "Reply-To: $from\r\n";
    		$headers .= "Content-type: text/html\r\n";
    		mail($to, $subject, $message, $headers);
    		
			header("location: lightbox.php?message=lightbox_emailed");
			exit;
		break;
    }
  }
		
		# TRY TO CALCULATE THE MAX IMAGE SIZE THAT CAN BE PROCESSED BY THE SERVER
		function calc_image_maxsize(){
			if(ini_get("memory_limit")){
				$memory_limit = ini_get("memory_limit");
			} else {
				$memory_limit = 256;
			}
			// TAKE AWAY A LITTLE IF THE MAX EXECUTION TIME IS 30 OR LESS
			if(ini_get("max_execution_time") <= 30){
				$mult = .7;
			} else {
				$mult = 1;
			}		
			$calc = round(($memory_limit/20)*$mult,2);		
			return $calc;
		}
		
		function figure_memory_needed( $filename ){
		   $imageInfo = getimagesize($filename);
		   $MB = 1048576;  // number of bytes in 1M
		   $K64 = 65536;    // number of bytes in 64K
		   $TWEAKFACTOR = 2.1;  // Or whatever works for you (1.5)
		   $memoryNeeded = round( ( $imageInfo[0] * $imageInfo[1]
												   * $imageInfo['bits']
												   * $imageInfo['channels'] / 8
									 + $K64
								   ) * $TWEAKFACTOR
								 );
			return round($memoryNeeded/$MB);
			
			// Memory_Get_Usage() or Get_CFG_Var('memory_limit') to find the memory limit
		}


		function upload_image($img_data,$img_name,$img_type,$path,$new_width,$new_height_c,$icon_width,$quality){

			global $image_details,$image_results,$result_code,$setting;
			
			$path = "../uploaded_images/";
			
			// SETTINGS
			$gd_gif_support = "on";
			$thumb_quality = $setting->upload_thumb_quality;
	
			if($img_data['name'] != ""){
				// RANAME THE FILE
				//echo $img_data['name']; exit;
				// REPLACE BAD CHARACTERS				
				$illegal_char = array(1 => "?",",","'","\'","%20");
				$x_count = count($illegal_char);
				$i_count = 1;
				
				$new_image_name = $img_data['name'];
				while($i_count <= $x_count){
					$new_image_name = str_replace($illegal_char[$i_count], "_", $new_image_name);
					$i_count++;
				}
				$new_image_name = str_replace(" ", "_", $new_image_name); // Replace spaces with underscores
				
				// CHECK IF FILE EXISTS
				if(!file_exists($path . $new_image_name)) {
					$new_image_name = $new_image_name;
				}
				else {
					// FILE EXISTS - RENAME (RENAME IN ORDER / myfile_1.jpg, myfile_2.jpg, etc.)
					$filename_array = split("\.", $new_image_name);
					$array_count = count($filename_array);
					
					$x_count2 = 0;
					while($x_count2 < $array_count - 1){ // 5
						if($x_count2 != 0){
							// IF THERE ARE PERIODS ADD THEM ONLY AFTER THE FIRST WORD
							$new_image_name2 = $new_image_name2 . "." . $filename_array[$x_count2];
						}
						else {
							$new_image_name2 = $new_image_name2 . $filename_array[$x_count2];
						}
						$x_count2++;
					}
					$x_count3 = 1;
					while(file_exists($path . $new_image_name)) {
						$new_image_name = $new_image_name2 . "_" . $x_count3 . "." . $filename_array[$x_count2];
						$x_count3++;	
					}						
				}
				
				// PROCEDE ONLY IF THE IMAGE TYPE IS A JPG
				if($img_data['type'] == "image/pjpeg" or $img_data['type'] == "image/jpeg" or $img_data['type'] == "image/jpg"){
			
					// COPY IMAGE TO DIRECTORY
					// move_uploaded_file($_FILES['image']['tmp_name'], $path . $new_image_name);
					copy($img_data['tmp_name'], $path . $new_image_name);
					
					// GET IMAGE WIDTH & HEIGHT
					$the_size = getimagesize($path . $new_image_name);
					
					// RESIZE IMAGE IF NEEDED
					if($new_width != ""){
						if($the_size[0] > $new_width) {
							$src_img = ImageCreateFromJPEG($path . $new_image_name);								
							$ratio = $new_width/$the_size[0];
							// IF A NEW HEIGHT (CROPPED) IS FILLED OUT CROP TO THAT HEIGHT. OTHERWISE SCALE.
							if($new_height_c != ""){
								$new_height = $new_height_c;
							}
							else{
								$new_height = $the_size[1] * $ratio;
							}
							
							$dst_img = ImageCreateTrueColor($new_width, $new_height);
							imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, imagesx($src_img), imagesy($src_img));
							imagejpeg($dst_img, $path . $new_image_name, $quality);
							imagedestroy($src_img); 
							imagedestroy($dst_img);
						}
					}
					// CREATE ICON
					if($icon_width != ""){
						$src_img = ImageCreateFromJPEG($path . $new_image_name);								
						$ratio = $icon_width/$the_size[0];
						$new_height = $the_size[1] * $ratio;						
						$dst_img = ImageCreateTrueColor($icon_width, $new_height);
												
						imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $icon_width, $new_height, imagesx($src_img), imagesy($src_img));
						imagejpeg($dst_img, $path . "i_" . $new_image_name, $thumb_quality);
						
						imagedestroy($src_img); 
						imagedestroy($dst_img);
						
						//watermark("../stock_photos/i_" . $new_image_name,"i_" . $new_image_name,"../images/watermark.png",100);
					}
					// GET IMAGE DETAILS
					$img_src = $path . $new_image_name;
					$image_bytes = filesize($img_src);
					$image_kb = round($image_bytes/1024);
					$the_size = getimagesize($img_src);
					$image_width = $the_size[0];
					$image_height = $the_size[1];
					$current_time = date("YmdHis");
					
					$image_details = array(1 => $new_image_name,$img_type,$path,$image_bytes,$image_kb,$image_width,$image_height,$current_time);
					
					$image_results = "Image Uploaded Successfully";
					$result_code   = 1;
				}
				else{
					// IMAGE TYPE NOT SUPPORTED
					$image_results = "Image Type Not Supported";
					$result_code   = 2;
				}
			}
			else {
				// NO IMAGE ENTERED
				$image_results = "No Image To Upload/Empty";
				$result_code   = 3;
			}
		}
		
		/*
		--------------------------------------------------------------------------------
		JPG IMAGE UPLOAD FUNCTION v1.2 (Photo Gallery Version)
			3-8-04
			To do: Add GIF & PNG support
		--------------------------------------------------------------------------------
		
		Call To Function Looks Like This:
			upload_image($image,$image_name,$image_type,"./test_images/","300","100","150","80");
		or
			// Upload Image ---------------------------------------------------------------------------------------------------------------
			upload_image(
				$image,				// name of form field to upload
				$image_name,		// filename of the image
				$image_type,		// type of image
				"./test_images/",	// directory to upload the image to
				"300",				// new width for your image / if blank doesn't resize
				"100",				// new height for your image (cropped) / if blank resizes with ratio
				"150",				// icon width / if left blank no icon is created
				"80"				// image quality
			);						// returns $image_details[1] - $image_details[8]
									// new image name(1),image type(2), image path(3), image bytes(4), image kb(5), width(6), heigth(7), time(8)
									// $image_results returns results of upload
									// $result_code returns 1,2,3 / results of upload... 1 Success, 2 Type Not Supported , 3 Empty
			
		Form Tag Must Look Like this:
			<form action="your_action_page" method="post" ENCTYPE="multipart/form-data">
		
		*/
		function upload_image_g($img_data,$img_name,$img_type,$path,$new_width,$new_height_c,$icon_width,$quality){

			global $image_details,$image_results,$result_code,$setting,$last;
			
			// SETTINGS
			$gd_gif_support = "on";
			$thumb_quality = $setting->upload_thumb_quality;
			$sample_quality = $setting->upload_sample_quality;
			$large_quality = $setting->upload_large_quality;
			
			if($img_data != ""){
				// RANAME THE FILE
				
				// REPLACE BAD CHARACTERS				
				$illegal_char = array(1 => "?",",","'","\'","%20","%");
				$x_count = count($illegal_char);
				$i_count = 1;
				
				$new_image_name = $img_name;
				while($i_count <= $x_count){
					$new_image_name = str_replace($illegal_char[$i_count], "_", $new_image_name);
					$i_count++;
				}
				$new_image_name = str_replace(" ", "_", $new_image_name); // Replace spaces with underscores
				
				// CHECK IF FILE EXISTS
				if(!file_exists($path . $new_image_name)) {
					$new_image_name = $new_image_name;
				}
				else {
					// FILE EXISTS - RENAME (RENAME IN ORDER / myfile_1.jpg, myfile_2.jpg, etc.)
					$filename_array = split("\.", $new_image_name);
					$array_count = count($filename_array);
					
					$x_count2 = 0;
					while($x_count2 < $array_count - 1){ // 5
						if($x_count2 != 0){
							// IF THERE ARE PERIODS ADD THEM ONLY AFTER THE FIRST WORD
							$new_image_name2 = $new_image_name2 . "." . $filename_array[$x_count2];
						}
						else {
							$new_image_name2 = $new_image_name2 . $filename_array[$x_count2];
						}
						$x_count2++;
					}
					$x_count3 = 1;
					while(file_exists($path . $new_image_name)) {
						$new_image_name = $new_image_name2 . "_" . $x_count3 . "." . $filename_array[$x_count2];
						$x_count3++;	
					}						
				}
				
				// PROCEDE ONLY IF THE IMAGE TYPE IS A JPG
				if($img_type == "image/pjpeg" or $img_type == "image/jpeg" or $img_type == "image/jpg"){
			
					// COPY IMAGE TO DIRECTORY
				
					// move_uploaded_file($_FILES['image']['tmp_name'], $path . $new_image_name);
					copy($_FILES['image']['tmp_name'], $path . $new_image_name);
					
					# GRAB IPTC SUPPORT
					#get_iptc_data($path . $new_image_name);				
									
					// GET IMAGE WIDTH & HEIGHT
					$the_size = getimagesize($path . $new_image_name);
					
					// RESIZE IMAGE IF NEEDED
					if($new_width != ""){
						if($the_size[0] > $new_width) {								
							$ratio = $new_width/$the_size[0];
							// IF A NEW HEIGHT (CROPPED) IS FILLED OUT CROP TO THAT HEIGHT. OTHERWISE SCALE.
							if($new_height_c != ""){
								$new_height = $new_height_c;
							} else {
								$new_height = $the_size[1] * $ratio;
							}
							
						if(@ImageCreateFromJPEG($path . $new_image_name)){
							$src_img = ImageCreateFromJPEG($path . $new_image_name);
         			$dst_img = ImageCreateTrueColor($new_width, $new_height);
							imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, imagesx($src_img), imagesy($src_img));
							imagejpeg($dst_img, $path . $new_image_name, $quality);
							imagedestroy($src_img); 
							imagedestroy($dst_img);
    				} else {
         			$sql="DELETE FROM photo_package WHERE id = '$last->id'";
         			$result2 = mysql_query($sql);
           			if(is_file($path . "/" . $new_image_name)){
               		unlink($path . "/" . $new_image_name);
           			}
           			if(is_file($path . "/s_" . $new_image_name)){
               		unlink($path . "/s_" . $new_image_name);
           			}
           			if(is_file($path . "/i_" . $new_image_name)){
               		unlink($path . "/i_" . $new_image_name);
           			}
           			if(is_file($path . "/m_" . $new_image_name)){
               		unlink($path . "/m_" . $new_image_name);
           			}
           		echo "Sorry the photo is unable to be uploaded";
           		$to = $setting->support_email;
							$no_upload_message = "There was an issue with your photostore not being able to upload a photo \"$new_image_name\". \n";
							$no_upload_message.= "Chances are this is usually caused by low resources on the server your site is hosted with. Either your php settings are low, not enough memory allocated, or something else along those lines. \n";
							$no_upload_message.= "You will need to adjust your php settings to allow you to upload this photo, or you can try again and see if it will upload. This error can also be because the photo your trying to upload is corrupted. \n";
							$no_upload_message.= "-----------ERROR INFO------------\n";
							$no_upload_message.= "ERROR:11 Failed to resize the photo so the process was stopped and all records of this photo were deleted \n";
							$no_upload_message.= "---------------------------------\n";
							mail($to, $setting->site_title . " failed to upload a photo (NEEDS FIXED)", $no_upload_message, "From: " . $setting->support_email);
           		exit;
      			}
      				/*
							$dst_img = ImageCreateTrueColor($new_width, $new_height);
							imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, imagesx($src_img), imagesy($src_img));
							imagejpeg($dst_img, $path . $new_image_name, $quality);
							imagedestroy($src_img); 
							imagedestroy($dst_img);
							*/
						}
					}
					
					
					// CREATE 600px SAMPLE
					if($icon_width != ""){						
						$sample_width = 600;
						if($the_size[0] < $sample_width){
							$sample_width = $the_size[0];
						}
						
						if(@ImageCreateFromJPEG($path . $new_image_name)){
         			$src_img = ImageCreateFromJPEG($path . $new_image_name);
         			$ratio = $sample_width/$the_size[0];
							$new_height = $the_size[1] * $ratio;
							$dst_img = ImageCreateTrueColor($sample_width, $new_height);				
							imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $sample_width, $new_height, imagesx($src_img), imagesy($src_img));
							imagejpeg($dst_img, $path . "s_" . $new_image_name, $sample_quality);
							imagedestroy($src_img); 
							imagedestroy($dst_img);
    				} else {
         			$sql="DELETE FROM photo_package WHERE id = '$last->id'";
         			$result2 = mysql_query($sql);
           			if(is_file($path . "/" . $new_image_name)){
               		unlink($path . "/" . $new_image_name);
           			}
           			if(is_file($path . "/s_" . $new_image_name)){
               		unlink($path . "/s_" . $new_image_name);
           			}
           			if(is_file($path . "/i_" . $new_image_name)){
               		unlink($path . "/i_" . $new_image_name);
           			}
           			if(is_file($path . "/m_" . $new_image_name)){
               		unlink($path . "/m_" . $new_image_name);
           			}
           		echo "Sorry the photo is unable to be uploaded";
           		$to = $setting->support_email;
							$no_upload_message = "There was an issue with your photostore not being able to upload a photo \"$new_image_name\". \n";
							$no_upload_message.= "Chances are this is usually caused by low resources on the server your site is hosted with. Either your php settings are low, not enough memory allocated, or something else along those lines. \n";
							$no_upload_message.= "You will need to adjust your php settings to allow you to upload this photo, or you can try again and see if it will upload. This error can also be because the photo your trying to upload is corrupted. \n";
							$no_upload_message.= "-----------ERROR INFO------------\n";
							$no_upload_message.= "ERROR:12 Failed to create the s_ (sample preview image) so the process was stopped and all records of this photo were deleted \n";
							$no_upload_message.= "---------------------------------\n";
							mail($to, $setting->site_title . " failed to upload a photo (NEEDS FIXED)", $no_upload_message, "From: " . $setting->support_email);
           		exit;
      			}
						/*
						$src_img = ImageCreateFromJPEG($path . $new_image_name);								
						$ratio = $sample_width/$the_size[0];
						$new_height = $the_size[1] * $ratio;						
						$dst_img = ImageCreateTrueColor($sample_width, $new_height);
												
						imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $sample_width, $new_height, imagesx($src_img), imagesy($src_img));
						imagejpeg($dst_img, $path . "s_" . $new_image_name, $sample_quality);
						
						imagedestroy($src_img); 
						imagedestroy($dst_img);
						
						//watermark("../stock_photos/s_" . $new_image_name,"s_" . $new_image_name,"../images/watermark.png",85);
						*/
					}
			
			//NEW LARGE PREVIEW SIZE
			if($setting->large_size == 1){
					if($icon_width != ""){
						$samplel_width = $setting->preview_size;
						
						if($the_size[0] >= $the_size[1]){
							
							if($the_size[0] > $samplel_width){
								$newl_width = $samplel_width;
							} else {
								$newl_width = $the_size[0];
							}
								$ratio = $newl_width/$the_size[0];
								$newl_height = $the_size[1] * $ratio;		
										
						} else {
							
							if($the_size[1] > $samplel_width){
								$newl_height = $samplel_width;	
							} else {
								$newl_height = $the_size[1];	
							}		
								$ratio = $newl_height/$the_size[1];
								$newl_width = $the_size[0] * $ratio;
						}
						
						if(@ImageCreateFromJPEG($path . $new_image_name)){
         			$src_img = ImageCreateFromJPEG($path . $new_image_name);										
							$dst_img = ImageCreateTrueColor($newl_width, $newl_height);					
							imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $newl_width, $newl_height, imagesx($src_img), imagesy($src_img));
							imagejpeg($dst_img, $path . "m_" . $new_image_name, $large_quality);
							imagedestroy($src_img);
							imagedestroy($dst_img);
    				} else {
         			$sql="DELETE FROM photo_package WHERE id = '$last->id'";
         			$result2 = mysql_query($sql);
           			if(is_file($path . "/" . $new_image_name)){
               		unlink($path . "/" . $new_image_name);
           			}
           			if(is_file($path . "/s_" . $new_image_name)){
               		unlink($path . "/s_" . $new_image_name);
           			}
           			if(is_file($path . "/i_" . $new_image_name)){
               		unlink($path . "/i_" . $new_image_name);
           			}
           			if(is_file($path . "/m_" . $new_image_name)){
               		unlink($path . "/m_" . $new_image_name);
           			}
           		echo "Sorry the photo is unable to be uploaded";
           		$to = $setting->support_email;
							$no_upload_message = "There was an issue with your photostore not being able to upload a photo \"$new_image_name\". \n";
							$no_upload_message.= "Chances are this is usually caused by low resources on the server your site is hosted with. Either your php settings are low, not enough memory allocated, or something else along those lines. \n";
							$no_upload_message.= "You will need to adjust your php settings to allow you to upload this photo, or you can try again and see if it will upload. This error can also be because the photo your trying to upload is corrupted. \n";
							$no_upload_message.= "------------ERROR INFO-----------\n";
							$no_upload_message.= "ERROR:13 Failed to create the m_ (click to enlarge sample image) so the process was stopped and all records of this photo were deleted \n";
							$no_upload_message.= "---------------------------------\n";
							mail($to, $setting->site_title . " failed to upload a photo (NEEDS FIXED)", $no_upload_message, "From: " . $setting->support_email);
           		exit;
      			}
						/*
						$src_img = ImageCreateFromJPEG($path . $new_image_name);										
						$dst_img = ImageCreateTrueColor($newl_width, $newl_height);					
						imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $newl_width, $newl_height, imagesx($src_img), imagesy($src_img));
						imagejpeg($dst_img, $path . "m_" . $new_image_name, $large_quality);
						imagedestroy($src_img); 
						imagedestroy($dst_img);
						*/
					}
				}
					
					// CREATE ICON
					$icon_width = 250;
					if($the_size[0] < $icon_width){
						$icon_width = $the_size[0];
					}
					$new_width = $icon_width;
					
					
					if($icon_width != ""){							
						if($the_size[0] >= $the_size[1]){
							$ratio = $new_width/$the_size[0];
							$new_height = $the_size[1] * $ratio;
						} else {
							$new_height = $icon_width;
							$ratio = $new_height/$the_size[1];
							$new_width = $the_size[0] * $ratio;
						}
							
						if(@ImageCreateFromJPEG($path . $new_image_name)){
         			$src_img = ImageCreateFromJPEG($path . $new_image_name);
         			$dst_img = ImageCreateTrueColor($new_width, $new_height);						
							imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, imagesx($src_img), imagesy($src_img));
							imagejpeg($dst_img, $path . "i_" . $new_image_name, $thumb_quality);
							imagedestroy($src_img); 
							imagedestroy($dst_img);
    				} else {
         			$sql="DELETE FROM photo_package WHERE id = '$last->id'";
         			$result2 = mysql_query($sql);
           			if(is_file($path . "/" . $new_image_name)){
               		unlink($path . "/" . $new_image_name);
           			}
           			if(is_file($path . "/s_" . $new_image_name)){
               		unlink($path . "/s_" . $new_image_name);
           			}
           			if(is_file($path . "/i_" . $new_image_name)){
               		unlink($path . "/i_" . $new_image_name);
           			}
           			if(is_file($path . "/m_" . $new_image_name)){
               		unlink($path . "/m_" . $new_image_name);
           			}
           		echo "Sorry the photo is unable to be uploaded";
           		$to = $setting->support_email;
							$no_upload_message = "There was an issue with your photostore not being able to upload a photo \"$new_image_name\". \n";
							$no_upload_message.= "Chances are this is usually caused by low resources on the server your site is hosted with. Either your php settings are low, not enough memory allocated, or something else along those lines. \n";
							$no_upload_message.= "You will need to adjust your php settings to allow you to upload this photo, or you can try again and see if it will upload. This error can also be because the photo your trying to upload is corrupted. \n";
							$no_upload_message.= "------------ERROR INFO-----------\n";
							$no_upload_message.= "ERROR:14 Failed to create the i_ (thumbnail image) so the process was stopped and all records of this photo were deleted \n";
							$no_upload_message.= "---------------------------------\n";
							mail($to, $setting->site_title . " failed to upload a photo (NEEDS FIXED)", $no_upload_message, "From: " . $setting->support_email);
           		exit;
      			}
      			/*		
						$dst_img = ImageCreateTrueColor($new_width, $new_height);						
						imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, imagesx($src_img), imagesy($src_img));
						imagejpeg($dst_img, $path . "i_" . $new_image_name, $thumb_quality);
						imagedestroy($src_img); 
						imagedestroy($dst_img);
						//watermark("../stock_photos/i_" . $new_image_name,"i_" . $new_image_name,"../images/watermark.png",100);
						*/
					}
					
					// GET IMAGE DETAILS
					$img_src = $path . $new_image_name;
					$image_bytes = filesize($img_src);
					$image_kb = round($image_bytes/1024);
					$the_size = getimagesize($img_src);
					$image_width = $the_size[0];
					$image_height = $the_size[1];
					$current_time = date("YmdHis");
					
					$image_details = array(1 => $new_image_name,$img_type,$path,$image_bytes,$image_kb,$image_width,$image_height,$current_time);
					
					$image_results = "Image Uploaded Successfully";
					$result_code   = 1;
				}
				else{
					// IMAGE TYPE NOT SUPPORTED
					$image_results = "Image Type Not Supported";
					$result_code   = 2;
				}
			}
			else {
				// NO IMAGE ENTERED
				$image_results = "No Image To Upload/Empty";
				$result_code   = 3;
			}
		}
		
		function upload_image_p($img_data,$img_name,$img_tmp,$img_type,$path,$new_width,$new_height_c,$icon_width,$quality){
			global $image_details,$image_results,$result_code,$setting;
			
			// SETTINGS
			$gd_gif_support = "on";
			$thumb_quality = $setting->upload_thumb_quality;
			$sample_quality = $setting->upload_sample_quality;
			$large_quality = $setting->upload_large_quality;
			
			if($img_data != ""){
				// RANAME THE FILE
				
				// REPLACE BAD CHARACTERS				
				$illegal_char = array(1 => "?",",","'","\'","%20");
				$x_count = count($illegal_char);
				$i_count = 1;
				
				$new_image_name = $img_name;
				while($i_count <= $x_count){
					$new_image_name = str_replace($illegal_char[$i_count], "_", $new_image_name);
					$i_count++;
				}
				$new_image_name = str_replace(" ", "_", $new_image_name); // Replace spaces with underscores
				
				// CHECK IF FILE EXISTS
				if(!file_exists($path . $new_image_name)) {
					$new_image_name = $new_image_name;
				}
				else {
					// FILE EXISTS - RENAME (RENAME IN ORDER / myfile_1.jpg, myfile_2.jpg, etc.)
					$filename_array = split("\.", $new_image_name);
					$array_count = count($filename_array);
					
					$x_count2 = 0;
					while($x_count2 < $array_count - 1){ // 5
						if($x_count2 != 0){
							// IF THERE ARE PERIODS ADD THEM ONLY AFTER THE FIRST WORD
							$new_image_name2 = $new_image_name2 . "." . $filename_array[$x_count2];
						}
						else {
							$new_image_name2 = $new_image_name2 . $filename_array[$x_count2];
						}
						$x_count2++;
					}
					$x_count3 = 1;
					while(file_exists($path . $new_image_name)) {
						$new_image_name = $new_image_name2 . "_" . $x_count3 . "." . $filename_array[$x_count2];
						$x_count3++;	
					}						
				}
				
				// PROCEDE ONLY IF THE IMAGE TYPE IS A JPG
				if($img_type == "image/pjpeg" or $img_type == "image/jpeg" or $img_type == "image/jpg"){
			
					// COPY IMAGE TO DIRECTORY
				
					// move_uploaded_file($_FILES['image']['tmp_name'], $path . $new_image_name);
				
					copy($img_tmp, $path . $new_image_name);
				
					# GRAB IPTC SUPPORT
					#get_iptc_data($path . $new_image_name);				
									
					// GET IMAGE WIDTH & HEIGHT
					$the_size = getimagesize($path . $new_image_name);
					
					// RESIZE IMAGE IF NEEDED
					if($new_width != ""){
						if($the_size[0] > $new_width) {
							$src_img = ImageCreateFromJPEG($path . $new_image_name);								
							$ratio = $new_width/$the_size[0];
							// IF A NEW HEIGHT (CROPPED) IS FILLED OUT CROP TO THAT HEIGHT. OTHERWISE SCALE.
							if($new_height_c != ""){
								$new_height = $new_height_c;
							}
							else{
								$new_height = $the_size[1] * $ratio;
							}
							
							$dst_img = ImageCreateTrueColor($new_width, $new_height);
							imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, imagesx($src_img), imagesy($src_img));
							imagejpeg($dst_img, $path . $new_image_name, $quality);
							imagedestroy($src_img); 
							imagedestroy($dst_img);
						}
					}
					
					// CREATE 300px SAMPLE
					if($icon_width != ""){						
						$sample_width = 600;
						if($the_size[0] < $sample_width){
							$sample_width = $the_size[0];
						}
						
						
						$src_img = ImageCreateFromJPEG($path . $new_image_name);								
						$ratio = $sample_width/$the_size[0];
						$new_height = $the_size[1] * $ratio;						
						$dst_img = ImageCreateTrueColor($sample_width, $new_height);
												
						imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $sample_width, $new_height, imagesx($src_img), imagesy($src_img));
						imagejpeg($dst_img, $path . "s_" . $new_image_name, $sample_quality);
						
						imagedestroy($src_img); 
						imagedestroy($dst_img);
						
						//watermark("../stock_photos/s_" . $new_image_name,"s_" . $new_image_name,"../images/watermark.png",85);
					}
					
				//NEW LARGE PREVIEW SIZE
			if($setting->large_size == 1){
					if($icon_width != ""){
						$samplel_width = $setting->preview_size;
						
						if($the_size[0] >= $the_size[1]){
							
							if($the_size[0] > $samplel_width){
								$newl_width = $samplel_width;
							} else {
								$newl_width = $the_size[0];
							}
								$ratio = $newl_width/$the_size[0];
								$newl_height = $the_size[1] * $ratio;		
										
						} else {
							
							if($the_size[1] > $samplel_width){
								$newl_height = $samplel_width;	
							} else {
								$newl_height = $the_size[1];	
							}		
								$ratio = $newl_height/$the_size[1];
								$newl_width = $the_size[0] * $ratio;
						}
						
						$src_img = ImageCreateFromJPEG($path . $new_image_name);										
						$dst_img = ImageCreateTrueColor($newl_width, $newl_height);					
						imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $newl_width, $newl_height, imagesx($src_img), imagesy($src_img));
						imagejpeg($dst_img, $path . "m_" . $new_image_name, $large_quality);
						imagedestroy($src_img); 
						imagedestroy($dst_img);
					}
				}
				
					// CREATE ICON
					$icon_width = 250;
					if($the_size[0] < $icon_width){
						$icon_width = $the_size[0];
					}
					$new_width = $icon_width;
					
					
					if($icon_width != ""){
						$src_img = ImageCreateFromJPEG($path . $new_image_name);								
						if($the_size[0] >= $the_size[1]){
						
							$ratio = $new_width/$the_size[0];
							$new_height = $the_size[1] * $ratio;
							
						} else {
							
							$new_height = $icon_width;
						
							$ratio = $new_height/$the_size[1];
							$new_width = $the_size[0] * $ratio;
														
						}
											
						$dst_img = ImageCreateTrueColor($new_width, $new_height);
												
						imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, imagesx($src_img), imagesy($src_img));
						imagejpeg($dst_img, $path . "i_" . $new_image_name, $thumb_quality);
						
						imagedestroy($src_img); 
						imagedestroy($dst_img);
						
						//watermark("../stock_photos/i_" . $new_image_name,"i_" . $new_image_name,"../images/watermark.png",100);
					}
					// GET IMAGE DETAILS
					$img_src = $path . $new_image_name;
					$image_bytes = filesize($img_src);
					$image_kb = round($image_bytes/1024);
					$the_size = getimagesize($img_src);
					$image_width = $the_size[0];
					$image_height = $the_size[1];
					$current_time = date("YmdHis");
					
					$image_details = array(1 => $new_image_name,$img_type,$path,$image_bytes,$image_kb,$image_width,$image_height,$current_time);
					
					$image_results = "Image Uploaded Successfully";
					$result_code   = 1;
				}
				else{
					// IMAGE TYPE NOT SUPPORTED
					$image_results = "Image Type Not Supported";
					$result_code   = 2;
				}
			}
			else {
				// NO IMAGE ENTERED
				$image_results = "No Image To Upload/Empty";
				$result_code   = 3;
			}
		}
		
		/*
		------------------------------------
		COPY AREA FUNCTION
		
			Formats:
				3 = COPY AREA COLUMN ON LEFT AND IMAGES COLUMN ON RIGHT AND FILES AT THE BOTTOM
				2 = COPY AREA ONLY W/O TABLE
				1 = COPY AREA WRAPPING AROUND IMAGES IN A SPAN
				0,default = COPY AREA ONLY W/ TABLE
				
		
			copy_area(12,1);
		------------------------------------
		*/
		
		// ADDED IN PS340 TO GRAB COPY AREA (CONTENT) TITLE
		function copy_title($id){
			include("database.php");
			$copy_area_results = mysql_query("SELECT title FROM copy_areas where id = '$id'", $db);
			$copy_area_num_rows = mysql_num_rows($copy_area_results);
			$copy_area = mysql_fetch_object($copy_area_results);
			if($copy_area_num_rows > 0){
			echo $copy_area->title;
			}
		}
			
		function copy_area($copy_area_id,$format){
			include("database.php");
			$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
			$setting = mysql_fetch_object($settings_result);
		
			$cadisplay_result = mysql_query("SELECT display FROM copy_areas where id = '$copy_area_id'", $db);
			$cadisplay = mysql_fetch_object($cadisplay_result);
			if($cadisplay->display == 1){
				$format = 1;
			}
			if($cadisplay->display == 2){
				$format = 2;
			}
			if($cadisplay->display == 3){
				$format = 3;
			}
			switch($format){
				
				// FORMAT WITH COPY AREA WRAPPING AROUND IMAGES IN A SPAN
				case "1":
					echo "<!-- START : COPY AREA (WITH IMAGES & FILES) -->";
						
						$ca_result = mysql_query("SELECT * FROM copy_areas where id = '$copy_area_id'", $db);
						$ca_rows = mysql_num_rows($ca_result);
						$ca = mysql_fetch_object($ca_result);
					echo "
							<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
								<tr>
									<td valign=\"top\">
						";
								$ci_result = mysql_query("SELECT * FROM uploaded_images where reference = 'copy_area' and reference_id = '$ca->id'", $db);
								$ci_rows = mysql_num_rows($ci_result);
								$new_copy = str_replace("{COMPANY_NAME}", $setting->site_title, $ca->article);
								
								if($ci_rows > 0){		
									echo "<span class=\"copy_photo_area\" align=\"right\">";							
									while($ci = mysql_fetch_object($ci_result)){
										echo "<a href=\"uploaded_images/" . $ci->filename . "\" target=\"_new\"><img src=\"uploaded_images/i_" . $ci->filename . "\" border=\"0\" alt=\"" . $ci->caption . "\"></a>";
										if($ci->caption != ""){
											echo "<br>" . $ci->caption . "";
										}
										echo "<br><br>";
									}
									echo "</span>";
								}
								
								echo $new_copy . "<br><br>";
								
									$cf_result = mysql_query("SELECT * FROM uploaded_files where reference = 'copy_area' and reference_id = '$ca->id'", $db);
									$cf_rows = mysql_num_rows($cf_result);
																								
									while($cf = mysql_fetch_object($cf_result)){
										
										if($cf->file_text != ""){
											$file_text = $cf->file_text;
										}
										else{
											$file_text = $cf->filename;
										}
										echo "<a href=\"uploaded_files/" . $cf->filename . "\" target=\"_new\">" . $file_text . "</a><br><br>";
										
									}
								 
					echo "
									</td>
								</tr>
							</table>
							<!-- END : COPY AREA -->
						";
				break;
				
				// FORMAT - ONLY COPY, NO FILES OR IMAGES | W/O TABLE
				case "2":
						$ca_result = mysql_query("SELECT * FROM copy_areas where id = '$copy_area_id'", $db);
						$ca_rows = mysql_num_rows($ca_result);
						$ca = mysql_fetch_object($ca_result);
						$new_copy = str_replace("{COMPANY_NAME}", $setting->site_title, $ca->article);
						echo $new_copy;
				break;
				
				// FORMAT WITH COPY AREA COLUMN ON LEFT AND IMAGES COLUMN ON RIGHT AND FILES AT THE BOTTOM
				case "3":
					echo "<!-- START : COPY AREA (WITH IMAGES & FILES) -->";
						$ca_result = mysql_query("SELECT * FROM copy_areas where id = '$copy_area_id'", $db);
						$ca_rows = mysql_num_rows($ca_result);
						$ca = mysql_fetch_object($ca_result);
						$new_copy = str_replace("{COMPANY_NAME}", $setting->site_title, $ca->article);
					echo "
							<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
								<tr>
									<td valign=\"top\">
						";
									echo $new_copy; 
									
									$cf_result = mysql_query("SELECT * FROM uploaded_files where reference = 'copy_area' and reference_id = '$ca->id'", $db);
									$cf_rows = mysql_num_rows($cf_result);
																								
									while($cf = mysql_fetch_object($cf_result)){
										
										if($cf->file_text != ""){
											$file_text = $cf->file_text;
										}
										else{
											$file_text = $cf->filename;
										}
										echo "<a href=\"uploaded_files/" . $cf->filename . "\" target=\"_new\">" . $file_text . "</a><br><br>";
										
									}
							echo "
										<br><br>
									</td>
								";
								$ci_result = mysql_query("SELECT * FROM uploaded_images where reference = 'copy_area' and reference_id = '$ca->id'", $db);
								$ci_rows = mysql_num_rows($ci_result);
								
								if($ci_rows > 0){
									echo "<td valign=\"top\" align=\"center\" class=\"copy_photo_area\">";
									
									while($ci = mysql_fetch_object($ci_result)){
										echo "<a href=\"uploaded_images/" . $ci->filename . "\" target=\"_new\"><img src=\"uploaded_images/i_" . $ci->filename . "\" border=\"0\" alt=\"" . $ci->caption . "\"></a>";
										if($ci->caption != ""){
											echo "<br><font style=\"font-size: 11; color: #6F6F6F\">" . $ci->caption . "</font>";
										}
										echo "<br><br>";
									}
								}
					echo "
									</td>
								</tr>
							</table>
							<!-- END : COPY AREA -->
						";
				break;
				
				// FORMAT - ONLY COPY, NO FILES OR IMAGES | W/ TABLE
				default:
						$ca_result = mysql_query("SELECT * FROM copy_areas where id = '$copy_area_id'", $db);
						$ca_rows = mysql_num_rows($ca_result);
						$ca = mysql_fetch_object($ca_result);
						echo "
								<!-- START : COPY AREA (NO IMAGES & FILES) -->
								<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
									<tr>
										<td valign=\"top\">
							";
										echo $ca->article;
						echo "
										</td>
									</tr>
								</table>
								<!-- END : COPY AREA -->
							";
				break;
			}
			
		}
		
		/*
		------------------------------------------------------------------------------
		WATERMARK FUNCTION
			watermark("test.jpg","test.jpg","test.png",100);
		------------------------------------------------------------------------------
		*/
		
		function watermark($srcfilename, $newname, $watermark, $quality, $default_size = 2500) {
			$imageInfo = getimagesize($srcfilename);
			
			if($imageInfo[0] >= $imageInfo[1]){
				
				if($imageInfo[0] > $default_size){
					$width = $default_size;
				} else {
					$width = $imageInfo[0];
				}
				$ratio = $width/$imageInfo[0];
				$height = $imageInfo[1] * $ratio;				
				
			} else {
				
				if($imageInfo[1] > $default_size){
					$height = $default_size;	
				} else {
					$height = $imageInfo[1];	
				}
							
				$ratio = $height/$imageInfo[1];
				$width = $imageInfo[0] * $ratio;
											
			}
      
      if($width < $height)
      {
        $watermark .= "_v";
      }
        $watermark .= ".png"; 
      
			//$width = $imageInfo[0];
			//$height = $imageInfo[1];
			$logoinfo = getimagesize($watermark);
			$logowidth = $logoinfo[0];
			$logoheight = $logoinfo[1];
			
			$horizextra = $width - $logowidth;
			$vertextra = $height - $logoheight;
			
			$horizmargin = round($horizextra / 2);
			$vertmargin = round($vertextra / 2);
			
			$photoImage = ImageCreateFromJPEG($srcfilename);
			//ImageAlphaBlending($photoImage, true);
			
			$dst_img = ImageCreateTrueColor($width, $height + 20);												
			
      imagecopyresampled($dst_img, $photoImage, 0, 0, 0, 0, $width, $height, imagesx($photoImage), imagesy($photoImage));
		
			ImageAlphaBlending($dst_img, true);
			
			$logoImage = ImageCreateFromPNG($watermark);
			
			//$logoW = ImageSX($logoImage);
			//$logoH = ImageSY($logoImage);
			/*
       * Those lines added by GansukhB 
       * Date: 2011.05.09
       * */
       
       $txt_size = 12;
       $txt_angle = 0;
       $txt_x = 10;
       $txt_y = $height + 15;
       $txt_color = $white;
       $txt_font = "./fonts/Ubuntu-L.ttf";
       $txt = "www.photobank.mn ID:".$_GET['i'];
       
       $white = imagecolorallocate($dst_img, 255, 255, 255);
       $black = imagecolorallocate($dst_img, 0, 0, 0);
       
       //$txt_bg_img = ImageCreateFromPNG("./images/watermark_txt_bg.png");
       
      //imagettftext($dist_img, $txt_angle, $txt_x, $txt_y, $txt_color, $txt_font, $txt);
      //imagecopy($dst_img, $txt_bg_img, $txt_x - 10, $txt_y - 15, 0, 0, $logowidth, 20); //backgroung of text
      
      //ImageDestroy($txt_bg_img);
      
      imagettftext($dst_img, $txt_size, $txt_angle, $txt_x, $txt_y, $white, $txt_font, $txt);
      
			//imagecopy($dst_img, $logoImage, $horizmargin, $vertmargin, 0, 0, $logowidth, $logoheight);
			
      //imagecopyresized($dst_img, $logoImage, 0, 0, 0, 0, $width, $height, imagesx($logoImage), imagesy($logoImage));
      imagecopyresampled($dst_img, $logoImage, 0, 0, 0, 0, $width, $height, imagesx($logoImage), imagesy($logoImage));
      //imagecopy($dst_img, $logoImage, 0, 0, 0, 0, $width, $height);
			           
      imagejpeg($dst_img,"",$quality); // output to browser 
			
			//uncomment next line to save the watermarked image to a directory. need write access(changed directory to anything)
			//ImageJPEG($photoImage, "../stock_photos/" . $newname, $quality);
			
			ImageDestroy($photoImage);
			ImageDestroy($dst_img);
			ImageDestroy($logoImage);
		}
		
		/*
		------------------------------------------------------------------------------
		WATERMARK FUNCTION FOR GD < 2
			watermark("test.jpg","test.jpg","test.png",100);
		------------------------------------------------------------------------------
		*/
		
		function watermark_16($srcfilename, $newname, $watermark, $quality) {
			//Header("Content-type: image/jpeg");	
			
			$src = $srcfilename;	
			$img_dim = getimagesize($src);
			
			$imgquality = 90;
				
			$height = $img_dim[1];
			$width = $img_dim[0];
			
			//$new_height = $height + 8;
			//$new_width = $width;
						
			$src_img = imagecreatefromjpeg($src);
			
			$dst_img = imagecreate($width,$height);						
			imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $width, $height, $width, $height); 
			
			// Print Branding Text On Image
			$white = imagecolorallocate($dst_img, 255, 255, 255);
			$black = imagecolorallocate($dst_img, 0, 0, 0);
			//imagestring($src_img, 3, ($img_dim[0] / 2.95), ($img_dim[1] / 6.5), "This is a test", $black);
			// Align Left
			//imagestring($dst_img, 2, 5, ($img_dim[1] - 7), "Image Hosted By ", $white);
			imagestring($dst_img, 2,(($img_dim[0]/2)-64), ($img_dim[1]/2), "Copyright 2004 FlixPix.com", $white);
			
			imagejpeg($dst_img,'', $imgquality);
			imagedestroy($src_img); 
			imagedestroy($dst_img);
			
			/*	
			$imageInfo = getimagesize($srcfilename);
			$width = $imageInfo[0];
			$height = $imageInfo[1];
			$logoinfo = getimagesize($watermark);
			$logowidth = $logoinfo[0];
			$logoheight = $logoinfo[1];
			$horizextra =$width - $logowidth;
			$vertextra =$height - $logoheight;
			$horizmargin = round($horizextra / 2);
			$vertmargin = round($vertextra / 2);
			$photoImage = ImageCreateFromJPEG($srcfilename);
			ImageAlphaBlending($photoImage, true);
			$logoImage = ImageCreateFromPNG($watermark);
			$logoW = ImageSX($logoImage);
			$logoH = ImageSY($logoImage);
			ImageCopy($photoImage, $logoImage, $horizmargin, $vertmargin, 0, 0, $logoW, $logoH);
			ImageJPEG($photoImage,"",$quality); // output to browser 
			//uncomment next line to save the watermarked image to a directory. need write access(changed directory to anything)
			//ImageJPEG($photoImage, "../stock_photos/" . $newname, $quality);
			
			ImageDestroy($photoImage);
			ImageDestroy($logoImage);
			*/
		}
				
		/*
		------------------------------------
		RANDOM CODE GENERATOR WITH EXTENTION
		------------------------------------
		*/
		function random_gen($length, $ext)
			{
			srand(time());
			
			$random .= "abcdefghijklmnopqrstuvwxyz";
			$random .= "1234567890";
			
			for($i = 0; $i < $length; $i++)	{
					$random_num .= substr($random, (rand()%(strlen($random))), 1);
				}
				
			$random_num = $random_num . $ext;
			
			return($random_num);
			}
					
		/*
		------------------
		CURRENCY FORMATTER
		------------------
		*/
		function dollar($amount) 
			{ 
			$amount=doubleval($amount); 
			echo(sprintf("%.2f", $amount)); 
			}
			
		function dollar2($amount) 
			{ 
			$amount=doubleval($amount); 
			return(sprintf("%.2f", $amount)); 
			}
			
		/*
		------------
		CURRENT TIME
		------------
		*/
		$current_time = date("YmdHis");
		
		
		/*
		--------------------------------------------------------------------------------
		FILE UPLOAD FUNCTION v0.1
		--------------------------------------------------------------------------------
		Call To Function Looks Like This:
			upload_file($fileup,$fileup_name,"./uploaded_files/");
		or
			// Upload File ---------------------------------------------------------------------------------------------------------------
			upload_file(
				$fileup,			    // name of form field to upload
				$fileup_name,		    // original filename of the file
				"./uploaded_files/"  	// directory to upload the file to
			);						    // returns $file_details[1];
									    // new file name(1)
										// $file_result_code returns 1 or 0 / results of upload... 1 Success, 2 Empty
			
		Form Tag Must Look Like this:
			<form action="your_action_page" method="post" ENCTYPE="multipart/form-data">
		
		*/
			function upload_file_new($file_data,$file_name,$path){

			global $file_details,$file_results,$file_result_code;
			
			if($file_data != ""){
				
				$new_filename = $file_name;
				$new_filename = str_replace(" ", "_", $new_filename);
				
				if(!file_exists($path . $new_filename)) {
					$new_filename = $new_filename;
				}
				else {
					// FILE EXISTS - RENAME (RENAME IN ORDER / myfile_1.ext, myfile_2.ext, etc.)
					$filename_array = split("\.", $new_filename);
					$array_count = count($filename_array);
					
					$x_count2 = 0;
					while($x_count2 < $array_count - 1){ // 5
						if($x_count2 != 0){
							// IF THERE ARE PERIODS ADD THEM ONLY AFTER THE FIRST WORD
							$new_filename2 = $new_filename2 . "." . $filename_array[$x_count2];
						}
						else {
							$new_filename2 = $new_filename2 . $filename_array[$x_count2];
						}
						$x_count2++;
					}
					$x_count3 = 1;
					while(file_exists($path . $new_filename)) {
						$new_filename = $new_filename2 . "_" . $x_count3 . "." . $filename_array[$x_count2];
						$x_count3++;	
					}						
				}				
				copy($file_data['tmp_name'], $path . $new_filename);
				
				$file_result_code = 1; // SUCCESS
				$file_details = array(1 => $new_filename);		
			}
			else {
				$file_result_code = 0; // FAILED - NO FILE TO UPLOAD
			}
		}
		
		function upload_file($file_data,$file_name,$path){

			global $file_details,$file_results,$file_result_code;
			
			if($file_data != ""){
				
				$new_filename = $file_name;
				$new_filename = str_replace(" ", "_", $new_filename);
				
				if(!file_exists($path . $new_filename)) {
					$new_filename = $new_filename;
				}
				else {
					// FILE EXISTS - RENAME (RENAME IN ORDER / myfile_1.ext, myfile_2.ext, etc.)
					$filename_array = split("\.", $new_filename);
					$array_count = count($filename_array);
					
					$x_count2 = 0;
					while($x_count2 < $array_count - 1){ // 5
						if($x_count2 != 0){
							// IF THERE ARE PERIODS ADD THEM ONLY AFTER THE FIRST WORD
							$new_filename2 = $new_filename2 . "." . $filename_array[$x_count2];
						}
						else {
							$new_filename2 = $new_filename2 . $filename_array[$x_count2];
						}
						$x_count2++;
					}
					$x_count3 = 1;
					while(file_exists($path . $new_filename)) {
						$new_filename = $new_filename2 . "_" . $x_count3 . "." . $filename_array[$x_count2];
						$x_count3++;	
					}						
				}				
				copy($file_data, $path . $new_filename);
				
				$file_result_code = 1; // SUCCESS
				$file_details = array(1 => $new_filename);		
			}
			else {
				$file_result_code = 0; // FAILED - NO FILE TO UPLOAD
			}
		}
		
		# Strip File Extension
		
		function strip_ext($name) 
   { 
       $ext = strrchr($name, '.'); 
       if($ext !== false) 
       { 
           $name = substr($name, 0, -strlen($ext)); 
      } 
       return $name; 
   } 
 // ADDED DURING PS3 TO GRAB FULL URL TO PAGE BEING VISITED
    function selfURL() {
		global $_GET;
						
			$url =  $_SERVER['PHP_SELF'];
	
			if($_GET){
				$url.="?";	
			}
			$x = 1;
			foreach($_GET as $key => $value){
				$url.="$key=$value";
				if($x < count($_GET)){
					$url.= "&";
				}
				$x++;
			}
						
						$s = empty($_SERVER["HTTPS"]) ? ''
						: ($_SERVER["HTTPS"] == "on") ? "s"
						: "";
				$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
				$port = ($_SERVER["SERVER_PORT"] == "80") ? ""
						: (":".$_SERVER["SERVER_PORT"]);
			return $protocol."://".$_SERVER['HTTP_HOST'].$port.$url;
		}
		function strleft($s1, $s2) {
		return substr($s1, 0, strpos($s1, $s2));
}
		$cur_page = selfurl();
//############ END OF URL GRABBER ###########################
    
    //################IMAGE COUNTER FOR MENU#################
    function imgcount($x,$level,$nest_under,$item_id,$photo_rows_final,$sp){
			global $db,$photo_rows_final;
			$my_row = 1;
				if(!$sp){
					$extrasql = " and pub_pri = '0'";
				} else {
					$extrasql = "";
				}
				if($level == 1){
					$gallery_result = mysql_query("SELECT id,nest_under FROM photo_galleries where id = '$x'" . $extrasql, $db);
				} else {
					$gallery_result = mysql_query("SELECT id,nest_under FROM photo_galleries where nest_under = '$x'" . $extrasql . " order by title", $db);	
				}
				while($gallery = mysql_fetch_object($gallery_result)){
					$gid = $gallery->id;
					$photo_rows = mysql_result(mysql_query("SELECT COUNT(id) FROM photo_package where active = '1' and photog_show = '1' and (gallery_id = '$gid' or other_galleries LIKE '%,$gid,%')"),0);
					$photo_rows_final = $photo_rows_final + $photo_rows;
				
				imgcount($gallery->id,$level + 1,$gallery->nest_under,$item_id,$photo_rows_final,$sp);
				$my_row ++;

			}			
		}
		//####################END MENU COUNTER####################

		# READ IPTC INFO
		
		function get_iptc_data($image_path){
			global $iptc_keywords, $iptc_description, $iptc_title;
			
			//$size = getimagesize ($image_path, &$info);
			$size = getimagesize ($image_path, $info);
			$iptc = iptcparse($info["APP13"]);
		
			if(is_array($iptc)){
			
				foreach($iptc as $key => $value){
				   /* 
				   echo "<b>IPTC Key:</b> $key <b>Contents:</b> ";
				   foreach($value as $innerkey => $innervalue)
				   {
					   if( ($innerkey+1) != count($value) )
						   echo "$innervalue, ";
					   else
						   echo "$innervalue";
				   }
				   */	   
				   
					# KEYWORDS
					if($key == "2#025"){
						foreach($value as $innerkey => $innervalue){
							if( ($innerkey+1) != count($value) )
								$iptc_keywords.= "$innervalue, ";
							else
								$iptc_keywords.= "$innervalue";
						}
					}
					//$iptc_description = htmlentities($iptc['2#120'][0]);
					//$iptc_title = htmlentities($iptc['2#005'][0]);
					$iptc_description = $iptc['2#120'][0];
					$iptc_title = $iptc['2#005'][0];
				}
			}
		}

		
?>
