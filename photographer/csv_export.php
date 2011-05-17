<?PHP
include("../database.php");
include("config_mgr.php");

if($pass != md5($setting->access_id)){
	exit;
}


//START CSV STORAGE VARIABLES
$csv_output = "";
$csv_outputa = "";
$csv_outputb = "";
$csv_outputc = "";
$csv_outputd = "";
$member_check = 0;
$prid_check = 0;
$sid_check = 0;
//SET ITEMS AND VARIABLES
$table = $csv_table;
$file = 'export';
$csv_items = "'".$csv_items."'";
$csv_items = str_replace(",","','",$csv_items);

//////////////////////////////////////////
/////////START OF PHOTOGRAPHERS EXPORT /////////
//////////////////////////////////////////
if($table == "photographers"){
	$check = "SELECT id,name,password,email,com_percent,status,featured,notes,added,approved,upload_on,edit_on,country,address1,address2,city,state,zip,phone,com_download,com_download_default,ip,display_name,url,payment_type,paypal_email FROM $table WHERE id IN ($csv_items) order by id";
	$result = mysql_query($check);
	while($results = mysql_fetch_object($result)){
		if($results->status == 1){
			$status = $csv_export_active;
		} else {
			$status = $csv_export_inactive;
		}
		if($results->featured == 1){
			$featured = $csv_export_yes;
		} else {
			$featured = $csv_export_no;
		}
		if($results->approved == 1){
			$approved = $csv_export_yes;
		} else {
		 	$approved = $csv_export_no;
		}
		if($results->upload_on == 1){
			$upload_on = $csv_export_yes;
		} else {
			$upload_on = $csv_export_no;
		}
		if($results->edit_on == 1){
			$edit_on = $csv_export_yes;
		} else {
			$edit_on = $csv_export_no;
		}
		$csv_outputa.= str_replace(",","",$results->id).",\"".str_replace(",","",$results->name)."\",\"".str_replace(",","",$results->display_name)."\",\"".str_replace(",","",$results->email)."\",\"".str_replace(",","",$results->password)."\",".str_replace(",","",$status).",".str_replace(",","",$featured).",\"".str_replace(",","",$results->notes)."\",".str_replace(",","",$results->added).",".str_replace(",","",$approved).",".str_replace(",","",$upload_on).",".str_replace(",","",$edit_on).",\"".str_replace(",","",$results->phone)."\",\"".str_replace(",","",$results->address1)."\",\"".str_replace(",","",$results->address2)."\",\"".str_replace(",","",$results->city)."\",\"".str_replace(",","",$results->state)."\",\"".str_replace(",","",$results->zip)."\",".str_replace(",","",$results->country).",".str_replace(",","",$results->com_percent).",".str_replace(",","",$results->com_download).",".str_replace(",","",$results->com_download_default).",".str_replace(",","",$results->ip).",".str_replace(",","",$results->url).",".str_replace(",","",$results->payment_type).",\"".str_replace(",","",$results->paypal_email)."\"".PHP_EOL;
	}
	$csv_output.= $csv_export_photographers_a;
}
//////////////////////////////////////////
////////////END OF PHOTOGRAPHERS EXPORT/////////
//////////////////////////////////////////


//////////////////////////////////////////
/////////START OF MEMBERS EXPORT /////////
//////////////////////////////////////////
if($table == "members"){
	$check = "SELECT id,name,display_name,email,password,order_num,status,added,visits,notes,info,sub_length,down_limit_m,down_limit_y,phone,address1,address2,city,state,zip,country FROM $table WHERE id IN ($csv_items) order by id";
	$result = mysql_query($check);
	while($results = mysql_fetch_object($result)){
		if($results->sub_length == "F"){
			$sub_type = $csv_export_free;
		} else {
			if($results->sub_length == "M"){
				$sub_type = $csv_export_monthly;
			} else {
				$sub_type = $csv_export_yearly;
			}
		}
		if($results->status == 1){
			$status = $csv_export_active;
		} else {
			$status = $csv_export_inactive;
		}
		$csv_outputa.= str_replace(",","",$results->id).",\"".str_replace(",","",$results->name)."\",\"".str_replace(",","",$results->display_name)."\",\"".str_replace(",","",$results->email)."\",\"".str_replace(",","",$results->password)."\",".str_replace(",","",$results->order_num).",".str_replace(",","",$status).",".str_replace(",","",$results->added).",".str_replace(",","",$results->visits).",\"".str_replace(",","",$results->notes)."\",\"".str_replace(",","",$results->info)."\",".str_replace(",","",$sub_type).",".str_replace(",","",$results->down_limit_m).",".str_replace(",","",$results->down_limit_y).",\"".str_replace(",","",$results->phone)."\",\"".str_replace(",","",$results->address1)."\",\"".str_replace(",","",$results->address2)."\",\"".str_replace(",","",$results->city)."\",\"".str_replace(",","",$results->state)."\",\"".str_replace(",","",$results->zip)."\",".str_replace(",","",$results->country).PHP_EOL;
	}
	$csv_output.= $csv_export_members_a;
}
//////////////////////////////////////////
////////////END OF MEMBERS EXPORT/////////
//////////////////////////////////////////


//////////////////////////////////////////
////////START OF ORDER EXPORT/////////////
//////////////////////////////////////////
//MASTER TABLE CHECK AND OVERALL VALUE OUTPUTS
if($table == "visitors"){
	$check = "SELECT id,visitor_id,added,order_num,paypal_email,payment_method,shipping,price,tax,coupon_id,savings,member_id FROM $table WHERE visitor_id IN ($csv_items) order by id desc";
	$result = mysql_query($check);
	while($results = mysql_fetch_object($result)){
		$member_check = "SELECT name,email,phone,address1,address2,city,state,zip,country FROM members WHERE id = '$results->member_id'";
		$members = mysql_query($member_check);
		$member = mysql_fetch_object($members);
		$cart_check = "SELECT photo_id,ptype,prid,sid,quantity,mtitle,stitle FROM carts WHERE visitor_id = '$results->visitor_id'";
		$carts = mysql_query($cart_check);
		while($cart = mysql_fetch_object($carts)){
		$csv_outputa.= str_replace(",","",$results->id).",".str_replace(",","",$results->visitor_id).",".str_replace(",","",$results->added).",".str_replace(",","",$results->order_num).",\"".str_replace(",","",$results->paypal_email)."\",".str_replace(",","",$results->payment_method).",".str_replace(",","",$results->shipping).",".str_replace(",","",$results->price).",".str_replace(",","",$results->tax).",".str_replace(",","",$results->coupon_id).",".str_replace(",","",$results->savings).",".str_replace(",","",$results->member_id);
		$csv_outputa.= ",\"".str_replace(",","",$member->name)."\",\"".str_replace(",","",$member->email)."\",\"".str_replace(",","",$member->phone)."\",\"".str_replace(",","",$member->address1)."\",\"".str_replace(",","",$member->address2)."\",\"".str_replace(",","",$member->city)."\",\"".str_replace(",","",$member->state)."\",\"".str_replace(",","",$member->zip)."\",".str_replace(",","",$member->country);
		$photo_check = "SELECT filename,reference_id FROM uploaded_images WHERE id = '$cart->photo_id' order by original desc";
		$photos = mysql_query($photo_check);
		$photo = mysql_fetch_object($photos);
		if($photo->filename != ""){
			$photo_name = $photo->filename;
		} else {
			$photo_name = $csv_export_unknown;
		}
		if($cart->ptype == d){
			$cart_type = $csv_export_digital;
		} else {
			if($cart->ptype == s){
				$cart_type = $csv_export_size;
			} else {
			 $cart_type = $csv_export_print;
			}
		}
		$csv_outputa.= ",".str_replace(",","",$photo->reference_id).",".str_replace(",","",$photo_name).",".str_replace(",","",$cart_type).",".str_replace(",","",$cart->prid).",".str_replace(",","",$cart->sid).",".str_replace(",","",$cart->quantity).",".str_replace(",","",$cart->mtitle).",".str_replace(",","",$cart->stitle);
		if($cart->prid > 0 && $cart->prid < 1111111111){
			$prids_check = "SELECT name FROM prints WHERE id = '$cart->prid' LIMIT 1";
			$prids = mysql_query($prids_check);
			$prid = mysql_fetch_object($prids);
			$csv_outputa.= ",\"".str_replace(",","",$prid->name)."\"";
		} else {
			$csv_outputa.=",";
		}
		if($cart->sid > 0){
			$sids_check = "SELECT name,size FROM sizes WHERE id = '$cart->sid' LIMIT 1";
			$sids = mysql_query($sids_check);
			$sid = mysql_fetch_object($sids);
			$csv_outputa.= ",\"".str_replace(",","",$sid->name)."\",".str_replace(",","",$sid->size)."(px)".PHP_EOL;
		} else {
			$csv_outputa.= PHP_EOL;
		}
	}
}
	//CREATE FIELD TITLES
	$csv_output.= $csv_export_orders_a;
	$csv_output.= $csv_export_orders_b;
	$csv_output.= $csv_export_orders_c;
	$csv_output.= $csv_export_orders_d;
	$csv_output.= $csv_export_orders_e;
}
//////////////////////////////////////
/////////END OF ORDER EXPORT//////////
//////////////////////////////////////


//COMBINE IT ALL TOGETHER FOR FINAL OUTPUT
$csv_output_final = $csv_output.PHP_EOL.$csv_outputa.$csv_outputb.$csv_outputc.$csv_outputd;
//STRIP ALL HTML
$csv_output_final = strip_tags($csv_output_final);


$filename = $file."_".date("Y-m-d_H-i",time());
header("Pragma: public"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("Cache-Control: private",false);
header("Content-type: application/vnd.ms-excel; charset=$setting->charset");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header("Content-disposition: filename=".$filename.".csv");
header("Content-Transfer-Encoding: binary");
print $csv_output_final;
?>