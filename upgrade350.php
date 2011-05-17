<?php
	## UPGRADE SHOW LARGE PREVIEW

	include( "database.php" );
	$settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
		
	$sql = 
		"CREATE TABLE lightbox (
		  id int(10) NOT NULL auto_increment,
		  member_id int(4) NOT NULL,
		  photo_id int(7) NOT NULL,		  
		  ptype char(1) NOT NULL,
		  prid int(10) NOT NULL default '0',
		  PRIMARY KEY  (id)
		) 
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `allow_sub_free` TINYINT(1) NOT NULL DEFAULT '0' 
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `force_approve` TINYINT(1) NOT NULL DEFAULT '1' 
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `force_mac` TINYINT(1) NOT NULL DEFAULT '0' 
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `debug` TINYINT(1) NOT NULL DEFAULT '0' 
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `force_members` TINYINT(1) NOT NULL DEFAULT '1' 
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"CREATE TABLE lightbox_group (
		  id int(10) NOT NULL auto_increment,
		  member_id int(4) NOT NULL,
		  name varchar(50) NOT NULL,
		  PRIMARY KEY  (id)
		) 
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `lightbox`
		ADD `reference_id` int(10) NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"CREATE TABLE counter (
		  id int(10) NOT NULL auto_increment,
		  ip varchar(20) NOT NULL,
		  date varchar(20) NOT NULL,
		  PRIMARY KEY  (id)
		) 
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `photo_galleries`
		ADD `link` TEXT NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `photo_galleries`
		ADD `slideshow` TINYINT(1) default '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `sort_by` varchar(250) default 'id' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `show_stats` TINYINT(1) default '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `visitors`
		ADD `member_id` INT(10) NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `show_tree` TINYINT(1) default '1' NOT NULL
		";
	$results = mysql_query($sql,$db);	
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `sort_order` varchar(250) DEFAULT 'descending' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings` 
		ADD `down_limit_y` int(5) DEFAULT '99999' NOT NULL,
		ADD `down_limit_m` int(5) DEFAULT '99999' NOT NULL
		"; 
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings` 
		ADD `no_cache` tinyint(1) DEFAULT '0' NOT NULL,
		ADD `no_right_click` tinyint(1) DEFAULT '0' NOT NULL
		"; 
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings` 
		ADD `slide_type` varchar(30) DEFAULT 'fade' NOT NULL,
		ADD `slide_speed` int(10) DEFAULT '5000' NOT NULL
		"; 
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `visitors` 
		ADD `done` tinyint(1) DEFAULT '0' NOT NULL,
		ADD `hide` tinyint(1) DEFAULT '0' NOT NULL
		"; 
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings` 
		ADD `hover_feature` tinyint(1) DEFAULT '1' NOT NULL,
		ADD `hover_new` tinyint(1) DEFAULT '1' NOT NULL,
		ADD `hover_popular` tinyint(1) DEFAULT '1' NOT NULL,
		ADD `hover_gallery` tinyint(1) DEFAULT '1' NOT NULL,
		ADD `hover_pri` tinyint(1) DEFAULT '1' NOT NULL,
		ADD `hover_search` tinyint(1) DEFAULT '1' NOT NULL,
		ADD `hover_user` tinyint(1) DEFAULT '1' NOT NULL
		"; 
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings` 
		ADD `hover_usr` tinyint(1) DEFAULT '1' NOT NULL,
		ADD `hover_on` tinyint(1) DEFAULT '1' NOT NULL
		"; 
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `members` 
		ADD `down_limit_m` int(5) DEFAULT '99999' NOT NULL,
		ADD `down_limit_y` int(5) DEFAULT '99999' NOT NULL
		"; 
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `members` 
		ADD `phone` varchar(100) NOT NULL,
		ADD `address1` varchar(250) NOT NULL,
		ADD `address2` varchar(250) NOT NULL,
		ADD `city` varchar(250) NOT NULL,
		ADD `state` varchar(250) NOT NULL,
		ADD `zip` varchar(25) NOT NULL
		"; 
	$results = mysql_query($sql,$db);
	
	$sql = 
		"CREATE TABLE status (
		  id int(10) NOT NULL auto_increment,
		  status TEXT NOT NULL default '', 
		  PRIMARY KEY  (id)
		) 
		";
	$results = mysql_query($sql,$db);
	
	$sql = "INSERT INTO status (id,status) VALUES ('1','')";
	$result = mysql_query($sql);

	$sql = "INSERT INTO copy_areas (id,title,article) VALUES ('32','Order Status','<DIV>Click on an order below to view details, if you have any questions please&nbsp;<A href=\"contact.php\">contact us</A>. </DIV>')";
	$result = mysql_query($sql);

	$sql = 
		"ALTER TABLE `settings`
		ADD `show_preview` TINYINT(1) DEFAULT '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `large_size` TINYINT(1) DEFAULT '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `preview_size` int(6) default '800' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `show_watermark` TINYINT(1) DEFAULT '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `description_length` INT(4) DEFAULT '21' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `featured` INT(3) DEFAULT '12' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `thumb_display_quality` INT(3) DEFAULT '95' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `sample_display_quality` INT(3) DEFAULT '95' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `large_display_quality` INT(3) DEFAULT '95' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `hover_display_quality` INT(3) DEFAULT '95' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `show_watermark_thumb` TINYINT(1) DEFAULT '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `show_watermark_hover` TINYINT(1) DEFAULT '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "INSERT INTO currency (code,sign,active,name) VALUES ('NZD','$','0','New Zealand Dollar ($)')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO currency (code,sign,active,name) VALUES ('CHF','CHF ','0','Swiss Franc')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO currency (code,sign,active,name) VALUES ('HKD','$','0','Hong Kong Dollar ($)')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO currency (code,sign,active,name) VALUES ('SGD','$','0','Singapore Dollar ($)')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO currency (code,sign,active,name) VALUES ('SEK','SEK ','0','Swedish Krona')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO currency (code,sign,active,name) VALUES ('DKK','DKK ','0','Danish Krone')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO currency (code,sign,active,name) VALUES ('PLN','PLN ','0','Polish Zloty')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO currency (code,sign,active,name) VALUES ('NOK','NOK ','0','Norwegian Krone')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO currency (code,sign,active,name) VALUES ('HUF','HUF ','0','Hungarian Forint')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO currency (code,sign,active,name) VALUES ('CZK','CZK ','0','Czech Koruna')";
	$result = mysql_query($sql);
	
	
	// 3.1.1
	$sql = 
		"ALTER TABLE `uploaded_images`
		ADD `original` INT(3) DEFAULT '999' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `members`
		ADD `info` TEXT NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `prints`
		ADD `article` TEXT NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `prints`
		ADD `visible` TINYINT(1) DEFAULT '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `photo_package`
		ADD `featured` TINYINT(1) DEFAULT '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	//Updates for Photostore version 320
	$sql = 
		"CREATE TABLE sizes (
		  id int(5) NOT NULL auto_increment,
		  name TEXT NOT NULL,
		  article TEXT NOT NULL,		  
		  size int(10) NOT NULL,
		  price varchar(100) NOT NULL,
		  sorder int(3) NOT NULL,
		  visible TINYINT(1) NOT NULL default '0',
		  photog_sizes TINYINT(1) NOT NULL default '1',
		  PRIMARY KEY  (id)
		) 
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"CREATE TABLE ratings (
		  id varchar(11) NOT NULL,  
		  total_votes int(11) NOT NULL default '0',
		  total_value int(11) NOT NULL default '0',
		  used_ips longtext NOT NULL,
		  PRIMARY KEY  (id)
		) 
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `photo_package`
		ADD `sizes` varchar(100) NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `photo_package`
		ADD `all_sizes` TINYINT(1) NOT NULL default '0'
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `carts`
		ADD `sid` INT(5) NOT NULL default '0'
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `hover_size` INT(5) NOT NULL default '250'
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `photog_price` varchar(10) default '25.00' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `appc` TINYINT(1) default '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `photog_old_sizes` TINYINT(1) default '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `photog_new_sizes` TINYINT(1) default '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `photog_sizes_locked` TINYINT(1) default '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `photog_upload` TINYINT(1) default '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `photog_edit` TINYINT(1) default '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `uploaded_images`
		MODIFY `original` INT(3) DEFAULT '999' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "UPDATE uploaded_images SET original = replace(original,'0','999')";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `rate_on` TINYINT(1) default '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `member_rate` TINYINT(1) default '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `use_authorize_net` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `use_paypal` ,
		ADD `api_login_id` MEDIUMTEXT NULL AFTER `use_authorize_net` ,
		ADD `transaction_key` MEDIUMTEXT NULL AFTER `api_login_id`
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `photographers`
		ADD `featured` TINYINT(1) default '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings` 
		ADD `sr_featured` tinyint(1) DEFAULT '0' NOT NULL,
		ADD `sr_new` tinyint(1) DEFAULT '0' NOT NULL,
		ADD `sr_pop` tinyint(1) DEFAULT '0' NOT NULL,
		ADD `sr_gallery` tinyint(1) DEFAULT '0' NOT NULL,
		ADD `sr_pri` tinyint(1) DEFAULT '0' NOT NULL,
		ADD `sr_search` tinyint(1) DEFAULT '0' NOT NULL,
		ADD `sr_photog` tinyint(1) DEFAULT '0' NOT NULL
		"; 
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `members`
		ADD `country` TEXT NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `print_info` tinyint(1) DEFAULT '1' NOT NULL,
		ADD `size_info` tinyint(1) DEFAULT '1' NOT NULL,
		ADD `popular` INT(3) DEFAULT '50' NOT NULL,
		ADD `search` INT(3) DEFAULT '300' NOT NULL,
		ADD `newest` INT(3) DEFAULT '50' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `charset` varchar(100) default 'iso-8859-1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `com_level` INT(3) default '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	//ADDED FOR PS321 UPDATE
	$sql = 
		"ALTER TABLE `settings`
		ADD `no_photo_message` TINYINT(1) default '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "INSERT INTO copy_areas (id,title,article) VALUES ('33','Privacy Policy','<DIV>Privacy Policy Here</DIV>')";
	$result = mysql_query($sql);
	
	// FIX FOR OTHER CATEGORIES
	include('upddb.php');
	
	// Version 3.3
	$sql = "INSERT INTO currency (code,sign,active,name) VALUES ('ZAR','R ','0','South African Rands (MyGate.co.za Only)')";
	$result = mysql_query($sql);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `mygatesupport` tinyint(1) DEFAULT '0' NOT NULL,
		ADD `mygateid` varchar(100) default '' NOT NULL,
		ADD `mygateaid` varchar(100) default '' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `allow_contact_download` TINYINT(1) default '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
		
	$sql = "ALTER TABLE `settings`
		ADD `sub_paypal` tinyint(1) DEFAULT '1' NOT NULL,
		ADD `sub_2co` tinyint(1) DEFAULT '1' NOT NULL,
		ADD `sub_auth` tinyint(1) DEFAULT '1' NOT NULL,
		ADD `sub_pnp` tinyint(1) DEFAULT '1' NOT NULL,
		ADD `sub_mygate` tinyint(1) DEFAULT '1' NOT NULL,
		ADD `sub_cmo` tinyint(1) DEFAULT '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `upload_thumb_quality` int(3) DEFAULT '100' NOT NULL,
		ADD `upload_sample_quality` int(3) DEFAULT '100' NOT NULL,
		ADD `upload_large_quality` int(3) DEFAULT '100' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `onoff` tinyint(1) DEFAULT '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `multi_lang` tinyint(1) DEFAULT '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `lang` varchar(100) DEFAULT 'English.php' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `photo_dir` varchar(100) DEFAULT 'stock_photos' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `video_dir` varchar(100) DEFAULT 'stock_videos' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `sample_dir` varchar(100) DEFAULT 'sample_videos' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `sample_size` int(3) DEFAULT '320' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `dropdown` tinyint(1) DEFAULT '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `photog_upload_email` tinyint(1) DEFAULT '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `photog_size_width` int(10) DEFAULT '800' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `photog_size_height` int(10) DEFAULT '600' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `photog_reg` tinyint(1) DEFAULT '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
	 "CREATE TABLE email_copy (
  	id int(4) NOT NULL auto_increment,
  	subject text NOT NULL,
  	title varchar(200) NOT NULL default '',
  	article text NOT NULL,
  	variable text NOT NULL,
  	image_upload varchar(4) NOT NULL default '',
  	image_area_name varchar(100) NOT NULL default '',
  	image_w varchar(5) NOT NULL default '',
  	image_h varchar(5) NOT NULL default '',
  	file_upload varchar(4) NOT NULL default '',
  	file_area_name varchar(100) NOT NULL default '',
  	PRIMARY KEY (id)
	)
	";
	$results = mysql_query($sql,$db);
	
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('1','Lightbox Email','A lightbox from {SITE_TITLE} has been emailed to you by {MEMBER_NAME}!','<p align=\"center\"><b>Lightbox: {LIGHTBOX_NAME}</b></p><br /><b>Notes:</b><br />{NOTES}<br />','{SITE_TITLE}<br>{MEMBER_NAME}<br>{LIGHTBOX_NAME}<br>{NOTES}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('2','Send Photo To Friend Email','A photo from {SITE_TITLE} has been emailed to you by {MEMBER_NAME}!','<p>This photo was sent to you by {MEMBER_NAME} from {SITE_TITLE}, click on the photo to visit our site and view more details on it.</p><br>','{SITE_TITLE}<br>{MEMBER_NAME}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('3','Store Contact Us Email','A question from {SITE_TITLE} has been sent to you by {NAME}','<div>This question was submitted by:</div><div>Email: {EMAIL}</div><div>Name: {NAME}</div><div>Comments:</div><div>{COMMENT}</div>','{SITE_TITLE}<br>{EMAIL}<br>{NAME}<br>{COMMENT}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('4','Paypal Customer Order Email','Thank you for your purchase from {SITE_TITLE}','<div>Thank you for your purchase from {SITE_TITLE}.<br /></div><div>Order Number: {ORDER#}<br />Payment Total:  \${TOTAL_AMOUNT}<br />Payment Status: {PAYMENT_STATUS}</div><div>(This status does not reflect the order status, it only reflects the payment status of the order.)<br /><br />Please click on the following link to view your order. If you purchased digital photos you will be able to download them by visiting the link.<br />**Can only download if the order status is approved, usually this is instant**<br />{LINK}<br />If you have any questions please contact us.<br /><br />Thanks<br />{SITE_TITLE} administration </div>','{SITE_TITLE}<br>{ORDER#}<br>{LINK}<br>{TOTAL_AMOUNT}<br>{PAYMENT_STATUS}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('5','2CheckOut Customer Order Email','Thank you for your purchase from {SITE_TITLE}','<div>Thank you for your purchase from {SITE_TITLE}.<br /></div><div>Order Number: {ORDER#}<br />Payment Status: {PAYMENT_STATUS}</div><div>(This status does not reflect the order status, it only reflects the payment status of the order.)<br /><br />Please click on the following link to view your order. If you purchased digital photos you will be able to download them by visiting the link.<br />**Can only download if the order status is approved, usually this is instant**<br />{LINK}<br />If you have any questions please contact us.<br /><br />Thanks<br />{SITE_TITLE} administration </div>','{SITE_TITLE}<br>{ORDER#}<br>{LINK}<br>{PAYMENT_STATUS}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('6','Photographer Signup Email to Store Owner','New photographer signup at {SITE_TITLE}','<div>Photographer Info:<br>Name: {NAME}<br>Email: {EMAIL}<br>Phone: {PHONE}<br>Address1: {ADDRESS1}<br>Address2: {ADDRESS2}<br>City: {CITY}<br>State: {STATE}<br>Zip: {ZIP}<br>Country: {COUNTRY}<br>Bio: {BIO}<br>If you wish to approve them click this link:<br>{LINK}','{SITE_TITLE}<br>{LINK}<br>{NAME}<br>{EMAIL}<br>{PHONE}<br>{ADDRESS1}<br>{ADDRESS2}<br>{CITY}<br>{STATE}<br>{ZIP}<br>{COUNTRY}<br>{BIO}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('7','Photographer Signup Email to Photographer','Thank you for signing up at {SITE_TITLE}','<div>Thank you for signing up at {SITE_TITLE}. Your application will be reviewed and if approved it will be activated so you can start selling<br><br>Photographer Info:<br>Name: {NAME}<br>Email: {EMAIL}<br>Phone: {PHONE}<br>Address1: {ADDRESS1}<br>Address2: {ADDRESS2}<br>City: {CITY}<br>State: {STATE}<br>Zip: {ZIP}<br>Country: {COUNTRY}<br>Bio: {BIO}','{SITE_TITLE}<br>{NAME}<br>{EMAIL}<br>{PHONE}<br>{ADDRESS1}<br>{ADDRESS2}<br>{CITY}<br>{STATE}<br>{ZIP}<br>{COUNTRY}<br>{BIO}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('8','Photographer Activated Email','Your account is now active at {SITE_TITLE}','<div>Your photographer account is now active.<br>You may visit our site to login and begin uploading and selling your photos!</div>','{SITE_TITLE}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('9','Photographer Photos Uploaded','{PHOTOGRAPHER} has just uploaded photos to {SITE_TITLE}','<div>Photographer:<br>{PHOTOGRAPHER}<br>{EMAIL}<br><br>You will need to log into your store manager and look in the photo queue to approve or reject these photos.<br>There were {NUMBER} photos uploaded by {PHOTOGRAPHER}</div>','{SITE_TITLE}<br>{PHOTOGRAPHER}<br>{EMAIL}<br>{NUMBER}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('10','Photographer photo Rejection & Approve Emails','Info about your recent photos uploaded to {SITE_TITLE}','<div>We have just processed {NUMBER} photo/s<br><br>----------------------------<br>{MESSAGE}<BR>----------------------------<BR>{PHOTOGRAPHER} if you have any questions about our photo uploading policy just contact us!</div>','{SITE_TITLE}<br>{PHOTOGRAPHER}<br>{MESSAGE} (this is the message you create in the photo queue)<br>{NUMBER}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('11','Check/money Order Approval Email','Info about your order from {SITE_TITLE}','<div>Your check/money order has cleared our billing department, your order is now completed. If you ordered downloads you can download then here:<br>{LINK}<br>If you ordered prints, the order will be filled and mailed out very soon.<br>You can review your order details here:<br>{LINK}<br><br>Thank you</div>','{SITE_TITLE}<br>{LINK}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('12','Paypal Customer Subscription Order Email','Info about your subscription from {SITE_TITLE}','<div>Thank you for your subscription to {SITE_TITLE}.<br>You may now visit our site and login here:<br>{LINK}<br><br>If you have any questions please feel free to {CONTACT_US}<br>Thank you!</div>','{SITE_TITLE}<br>{LINK}<br>{CONTACT_US}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('13','Subscription Created Email to Store Owner','A new subscription at {SITE_TITLE}','<div>A new subscription was created at {SITE_TITLE}.<br>This email is to let you know a new one was created\, but this doesn\'t mean that the account is active. This email can be sent for various reasons\, free account signup\, checkout signup\, actual paid subscription\, etc..<br><br>If it was a paid subscription you should get another email notice from the payment gateway\, or one from your store if it was by check or money order.</div>','{SITE_TITLE}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('14','2CheckOut Customer Subscription Order Email','Info about your subscription from {SITE_TITLE}','<div>Thank you for your subscription to {SITE_TITLE}.<br>You may now visit our site and login here:<br>{LINK}<br><br>If you have any questions please feel free to {CONTACT_US}<br>Thank you!</div>','{SITE_TITLE}<br>{LINK}<br>{CONTACT_US}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('15','MyGate Customer Order Email','Thanks you for your purchase from {SITE_TITLE}','<div>Thank you for your purchase from {SITE_TITLE}.<br /></div><div>Order Number: {ORDER#}<br />Payment Total:  \${TOTAL_AMOUNT}<br />Payment Status: {PAYMENT_STATUS}</div><div>(This status does not reflect the order status, it only reflects the payment status of the order.)<br /><br />Please click on the following link to view your order. If you purchased digital photos you will be able to download them by visiting the link.<br />**Can only download if the order status is approved, usually this is instant**<br />{LINK}<br />If you have any questions please contact us.<br /><br />Thanks<br />{SITE_TITLE} administration </div>','{SITE_TITLE}<br>{ORDER#}<br>{LINK}<br>{TOTAL_AMOUNT}<br>{PAYMENT_STATUS}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('16','MyGate Customer Subscription Order Email','Info about your subscription from {SITE_TITLE}','<div>Thank you for your subscription to {SITE_TITLE}.<br>You may now visit our site and login here:<br>{LINK}<br><br>If you have any questions please feel free to {CONTACT_US}<br>Thank you!</div>','{SITE_TITLE}<br>{LINK}<br>{CONTACT_US}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('17','Check/money order Customer Order Email','{NAME} thank you for your purchase from {SITE_TITLE}','<div>{NAME} thank you for your purchase from {SITE_TITLE}.<br /></div><div>Order Number: {ORDER#}<br />Payment Total:  \${TOTAL_AMOUNT}<br />Payment Status: Pending</div><div>(This status reflect the order status it will not change tell the check/money order is sent in and cleared)<br /><br />Please click on the following link to view your order. If you purchased digital photos you will not be able to download them tell the order clears.<br />{LINK}<br /><br />If you have any questions about this order please {CONTACT_US}.<br /><br />Thanks<br />{SITE_TITLE} administration </div>','{SITE_TITLE}<br>{NAME}<br>{ORDER#}<br>{LINK}<br>{TOTAL_AMOUNT}<br>{CONTACT_US}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('18','PlugNPay Customer Order Email','Thank you for your purchase from {SITE_TITLE}','<div>Thank you for your purchase from {SITE_TITLE}.<br /></div><div>Order Number: {ORDER#}<br />Payment Total:  \${TOTAL_AMOUNT}<br />Payment Status: {PAYMENT_STATUS}</div><div>(This status does not reflect the order status, it only reflects the payment status of the order.)<br /><br />Please click on the following link to view your order. If you purchased digital photos you will be able to download them by visiting the link.<br />**Can only download if the order status is approved, usually this is instant**<br />{LINK}<br />If you have any questions please contact us.<br /><br />Thanks<br />{SITE_TITLE} administration </div>','{SITE_TITLE}<br>{ORDER#}<br>{LINK}<br>{TOTAL_AMOUNT}<br>{PAYMENT_STATUS}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('19','PlugNpay Customer Subscription Order Email','Info about your subscription from {SITE_TITLE}','<div>Thank you for your subscription to {SITE_TITLE}.<br>You may now visit our site and login here:<br>{LINK}<br><br>If you have any questions please feel free to {CONTACT_US}<br>Thank you!</div>','{SITE_TITLE}<br>{LINK}<br>{CONTACT_US}')";
	$result = mysql_query($sql);
	$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('20','Authorize.net Customer Order Email','Thank you for your purchase from {SITE_TITLE}','<div>Thank you for your purchase from {SITE_TITLE}.<br /></div><div>Order Number: {ORDER#}<br />Payment Total:  \${TOTAL_AMOUNT}<br />Payment Status: {PAYMENT_STATUS}</div><div>(This status does not reflect the order status, it only reflects the payment status of the order.)<br /><br />Please click on the following link to view your order. If you purchased digital photos you will be able to download them by visiting the link.<br />**Can only download if the order status is approved, usually this is instant**<br />{LINK}<br />If you have any questions please contact us.<br /><br />Thanks<br />{SITE_TITLE} administration </div>','{SITE_TITLE}<br>{ORDER#}<br>{LINK}<br>{TOTAL_AMOUNT}')";
	$result = mysql_query($sql);
	//$sql = "INSERT INTO email_copy (id,title,subject,article,variable) VALUES ('21','Authorize.net Customer Subscription Order Email','Info about your subscription from {SITE_TITLE}','<div>Thank you for your subscription to {SITE_TITLE}.<br>You may now visit our site and login here:<br>{LINK}<br><br>If you have any questions please feel free to {CONTACT_US}<br>Thank you!</div>','{SITE_TITLE}<br>{LINK}<br>{CONTACT_US}')";
	//$result = mysql_query($sql);
	
	$sql = 
		"CREATE TABLE photog_response (
		  id int(10) NOT NULL auto_increment,
		  title VARCHAR(100) NOT NULL,
		  message text NOT NULL,
		  PRIMARY KEY  (id)
		) 
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `prints`
		ADD `bypass` tinyint(1) DEFAULT '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `print_ship` tinyint(1) DEFAULT '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	//ADDED FOR PS340
	
	$sql = "ALTER TABLE `coupon`
		ADD `article` text DEFAULT '' NOT NULL,
		ADD `display` tinyint(1) DEFAULT '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `modrw` tinyint(1) DEFAULT '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `thumb_slide_count` int(3) DEFAULT '10' NOT NULL,
		ADD `thumb_slide_arrowcontrol` varchar(20) DEFAULT 'mouseMove' NOT NULL,
		ADD `thumb_slide_border` varchar(20) DEFAULT 'true' NOT NULL,
		ADD `thumb_slide_bordercolor` varchar(20) DEFAULT '0x000000' NOT NULL,
		ADD `thumb_slide_bordercornerradius` int(3) DEFAULT '5' NOT NULL,
		ADD `thumb_slide_bordersize` int(3) DEFAULT '5' NOT NULL,
		ADD `thumb_slide_builtinpreloader` varchar(20) DEFAULT 'bar' NOT NULL,
		ADD `thumb_slide_preloadercolor` varchar(20) DEFAULT '0xC0C0C0' NOT NULL,
		ADD `thumb_slide_easetype` varchar(20) DEFAULT 'Strong' NOT NULL,
		ADD `thumb_slide_effectamount` int(3) DEFAULT '50' NOT NULL,
		ADD `thumb_slide_effecttimein` int(3) DEFAULT '10' NOT NULL,
		ADD `thumb_slide_effecttimeout` int(3) DEFAULT '10' NOT NULL,
		ADD `thumb_slide_rollovereffect` varchar(20) DEFAULT 'colorLight' NOT NULL,
		ADD `thumb_slide_reverserollovereffect` varchar(20) DEFAULT 'false' NOT NULL,
		ADD `thumb_slide_orientation` varchar(20) DEFAULT 'horizontal' NOT NULL,
		ADD `thumb_slide_resizetype` varchar(20) DEFAULT 'scale' NOT NULL,
		ADD `thumb_slide_spacing` int(3) DEFAULT '5' NOT NULL,
		ADD `thumb_slide_thumbheight` int(3) DEFAULT '80' NOT NULL,
		ADD `thumb_slide_thumbwidth` int(3) DEFAULT '100' NOT NULL,
		ADD `thumb_slide_speed` int(3) DEFAULT '6' NOT NULL,
		ADD `thumb_slide_bgcolor` varchar(20) DEFAULT '0xFFFFFF' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "ALTER TABLE `settings`
		ADD `pf_mousewheelflip` varchar(20) DEFAULT '1' NOT NULL,
		ADD `pf_autoflipseconds` int(3) DEFAULT '0' NOT NULL,
		ADD `pf_flipsound` text DEFAULT '' NOT NULL,
		ADD `pf_flipspeed` int(3) DEFAULT '10' NOT NULL,
		ADD `pf_namebold` varchar(20) DEFAULT '1' NOT NULL,
		ADD `pf_namecolor` varchar(20) DEFAULT '0x000000' NOT NULL,
		ADD `pf_namedistance` int(3) DEFAULT '15' NOT NULL,
		ADD `pf_nameposition` varchar(50) DEFAULT 'top center' NOT NULL,
		ADD `pf_namesize` int(3) DEFAULT '12' NOT NULL,
		ADD `pf_namefont` varchar(20) DEFAULT 'Arial' NOT NULL,
		ADD `pf_preloadset` int(3) DEFAULT '7' NOT NULL,
		ADD `pf_hpers` decimal(3,1) DEFAULT '0.2' NOT NULL,
		ADD `pf_vpers` decimal(3,1) DEFAULT '0.2' NOT NULL,
		ADD `pf_view` int(3) DEFAULT '50' NOT NULL,
		ADD `pf_reflectionalpha` int(3) DEFAULT '10' NOT NULL,
		ADD `pf_reflectiondepth` int(3) DEFAULT '60' NOT NULL,
		ADD `pf_reflectiondistance` int(3) DEFAULT '20' NOT NULL,
		ADD `pf_reflectionextend` int(3) DEFAULT '250' NOT NULL,
		ADD `pf_selectedreflectionalpha` int(3) DEFAULT '50' NOT NULL,
		ADD `pf_showname` varchar(20) DEFAULT '1' NOT NULL,
		ADD `pf_showreflection` varchar(20) DEFAULT '1' NOT NULL,
		ADD `pf_photoheight` int(3) DEFAULT '250' NOT NULL,
		ADD `pf_photowidth` int(3) DEFAULT '250' NOT NULL,
		ADD `pf_selectedy` int(3) DEFAULT '-10' NOT NULL,
		ADD `pf_defaultid` int(3) DEFAULT '3' NOT NULL,
		ADD `pf_holderalpha` int(3) DEFAULT '100' NOT NULL,
		ADD `pf_holderborderalpha` int(3) DEFAULT '100' NOT NULL,
		ADD `pf_holderbordercolor` varchar(20) DEFAULT '0xE4E4E4' NOT NULL,
		ADD `pf_holdercolor` varchar(20) DEFAULT '0xFFFFFF' NOT NULL,
		ADD `pf_scalemode` varchar(30) DEFAULT 'noScale' NOT NULL,
		ADD `pf_selectedscale` int(3) DEFAULT '100' NOT NULL,
		ADD `pf_spacing` int(3) DEFAULT '50' NOT NULL,
		ADD `pf_zoom` int(3) DEFAULT '0' NOT NULL,
		ADD `pf_zoomtype` varchar(30) DEFAULT 'none' NOT NULL,
		ADD `pf_bgcolor` varchar(20) DEFAULT '0xFFFFFF' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "INSERT INTO copy_areas (id,title,article) VALUES ('3','Left Box 1','<DIV>To make this box visible in the left menu you must turn it on in the store manager settings under display. Make sure to give this a real title as it will be display as the title for the box area.</DIV>')";
	$result = mysql_query($sql);
	
	$sql = "INSERT INTO copy_areas (id,title,article) VALUES ('4','Left Box 2','<DIV>To make this box visible in the left menu you must turn it on in the store manager settings under display. Make sure to give this a real title as it will be display as the title for the box area.</DIV>')";
	$result = mysql_query($sql);
	
	$sql = "INSERT INTO copy_areas (id,title,article) VALUES ('5','Left Box 3','<DIV>To make this box visible in the left menu you must turn it on in the store manager settings under display. Make sure to give this a real title as it will be display as the title for the box area.</DIV>')";
	$result = mysql_query($sql);
	
	$sql = "INSERT INTO copy_areas (id,title,article) VALUES ('6','Left Box 4','<DIV>To make this box visible in the left menu you must turn it on in the store manager settings under display. Make sure to give this a real title as it will be display as the title for the box area.</DIV>')";
	$result = mysql_query($sql);
	
	$sql = "INSERT INTO copy_areas (id,title,article) VALUES ('7','Left Box 5','<DIV>To make this box visible in the left menu you must turn it on in the store manager settings under display. Make sure to give this a real title as it will be display as the title for the box area.</DIV>')";
	$result = mysql_query($sql);
	
	$sql = "INSERT INTO copy_areas (id,title,article) VALUES ('8','Left Box 6','<DIV>To make this box visible in the left menu you must turn it on in the store manager settings under display. Make sure to give this a real title as it will be display as the title for the box area.</DIV>')";
	$result = mysql_query($sql);
	
	$sql = "INSERT INTO copy_areas (id,title,article) VALUES ('9','Header Box','<DIV>This is display between the logo and the top menu, does not need a legit title, it will not get displayed.</DIV>')";
	$result = mysql_query($sql);
	
	$sql = "INSERT INTO copy_areas (id,title,article) VALUES ('40','Footer Box','<DIV>This is displayed in the footer of the site, above the powered by line, copyright logo. Does not need a legit title as it will not be displayed.</DIV>')";
	$result = mysql_query($sql);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `leftbox1` TINYINT(1) default '0' NOT NULL,
		ADD `leftbox2` TINYINT(1) default '0' NOT NULL,
		ADD `leftbox3` TINYINT(1) default '0' NOT NULL,
		ADD `leftbox4` TINYINT(1) default '0' NOT NULL,
		ADD `leftbox5` TINYINT(1) default '0' NOT NULL,
		ADD `leftbox6` TINYINT(1) default '0' NOT NULL,
		ADD `headerbox` TINYINT(1) default '0' NOT NULL,
		ADD `footerbox` TINYINT(1) default '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `emailchar` varchar(100) default '' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `free_approve` TINYINT(1) default '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `flashthumbs` TINYINT(1) default '0' NOT NULL,
		ADD `flashsamples` TINYINT(1) default '0' NOT NULL,
		ADD `flashtrans` varchar(30) default 'photoloader2.swf' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `flash_featured_on` TINYINT(1) NOT NULL DEFAULT '1' 
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `flash_thumb_on` TINYINT(1) NOT NULL DEFAULT '1' 
		";
	$results = mysql_query($sql,$db);
	
	// FOR 3.4.1 - MAKE SURE OLD PHOTOS other_galleries IS CORRECT - IF NOT THEN FIX IT
	
	$pg_result = mysql_query("SELECT other_galleries,id FROM photo_package WHERE other_galleries != ''");
	while($pg = mysql_fetch_object($pg_result)){
		$first_char = substr($pg->other_galleries,0,1);
		if($first_char != "," and $pg->other_galleries != ""){
			$new = "," . $pg->other_galleries;
			// UPDATE
			$sql = "UPDATE photo_package SET other_galleries='$new' where id = '$pg->id'";
			$result = mysql_query($sql);			
		}
	}
	
	// ADDED IN PS343			
	$sql = "ALTER TABLE `visitors`
		ADD `ups` varchar(50) DEFAULT '' NOT NULL,
		ADD `fedex` varchar(50) DEFAULT '' NOT NULL,
		ADD `dhl` varchar(50) DEFAULT '' NOT NULL,
		ADD `track` varchar(50) DEFAULT '' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	//ADDED IN PS350
	$sql = 
		"ALTER TABLE `photo_galleries`
		ADD `pageflip` TINYINT(1) default '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
   	$sql = 
		"ALTER TABLE `settings`
		ADD `photog_dir` varchar(50) default 'photog_upload' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `photog_batch_upload` TINYINT(1) default '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	$sql = 
		"ALTER TABLE `settings`
		ADD `search_onoff` TINYINT(1) default '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	$sql = 
		"ALTER TABLE `photo_galleries`
		ADD `sort_on` TINYINT(1) default '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	$sql = 
		"ALTER TABLE `photo_galleries`
		ADD `free` TINYINT(1) default '0' NOT NULL,
		ADD `monthly` TINYINT(1) default '0' NOT NULL,
		ADD `yearly` TINYINT(1) default '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	$sql = 
		"ALTER TABLE `photo_galleries`
		ADD `gallery_search_on` TINYINT(1) default '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	$sql = 
		"ALTER TABLE `photo_galleries`
		ADD `sort_by` varchar(250) default '$setting->sort_by' NOT NULL
		";
	$results = mysql_query($sql,$db);
	$sql = 
		"ALTER TABLE `photo_galleries`
		ADD `sort_order` varchar(250) DEFAULT '$setting->sort_order' NOT NULL
		";
	$results = mysql_query($sql,$db);
	$sql = 
		"ALTER TABLE `photo_galleries`
		ADD `photog_use` tinyint(1) DEFAULT '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	$sql = 
		"ALTER TABLE `settings`
		ADD `private_search` TINYINT(1) default '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	$sql = 
		"ALTER TABLE `settings`
		ADD `tos_check` TINYINT(1) default '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	$sql = 
		"ALTER TABLE `settings`
		ADD `tax_download` TINYINT(1) default '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	$sql = 
		"CREATE TABLE links (
		  id int(10) NOT NULL auto_increment,
		  filename varchar(100) NOT NULL,
		  url varchar(250) NOT NULL,
		  PRIMARY KEY  (id)
		) 
		";
	$results = mysql_query($sql,$db);
	$sql = 
		"ALTER TABLE `settings`
		ADD `cart_price` varchar(10) default '0.00' NOT NULL
		";
	$results = mysql_query($sql,$db);
	$sql = 
		"ALTER TABLE `copy_areas`
		ADD `display` tinyint(1) default '2' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `copy_areas`
		ADD `allowdel` tinyint(1) default '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	// UPDATE THE DEFAULT CONTENT AREA SO THEY CAN'T BE DELETED, ONLY USER CREATED CONTENT CAN BE
	$id = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,40);
	foreach($id as $value => $key){
	$sql = "UPDATE copy_areas SET allowdel='0' WHERE id='$key'";
	$result = mysql_query($sql);
	}
	
	$sql = 
		"ALTER TABLE `photo_package`
		ADD `photog_show` TINYINT(1) DEFAULT '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = "INSERT INTO copy_areas (id,title,article,allowdel) VALUES ('101','Checkout Terms Of Service','<DIV>Enter your checkout terms of service here, you can do so by using your store manager content editor.</DIV>','0')";
	$result = mysql_query($sql);
	
	$sql = 
		"ALTER TABLE `prints`
		ADD `taxable` TINYINT(1) DEFAULT '1' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `pf_feed` TINYINT(1) DEFAULT '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `show_abanner` TINYINT(1) DEFAULT '0' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `abanner_name` varchar(20) DEFAULT '' NOT NULL
		";
	$results = mysql_query($sql,$db);
	
	$sql = 
		"ALTER TABLE `settings`
		ADD `kaffiliate` varchar(10) DEFAULT '' NOT NULL
		";
	$results = mysql_query($sql,$db);
	$sql = 
		"ALTER TABLE `settings`
		ADD `menu_click` TINYINT(1) DEFAULT '1' NOT NULL
		";
	$results = mysql_query($sql,$db);

	echo "<font color=#ff0000 face=Verdana>Database Upgrade Complete<br /><br />";
	echo "Now upload the new or changed files based on the change log.<br /><br />";
	
?>