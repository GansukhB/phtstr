<?php
	
  //session_start();
	//include( "check_login_status.php" );
	
	// PHOTO GALLERY ACTIONS - UPDATED 3.22.04
	
	include( "config_mgr.php" );
  
  $settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
	
  
  switch($_GET['pmode']){
    case "create":
      $title = $_POST['contest_title'];
      
      
      $query = "insert into contest (title, order) values('$title', '1')";
      
      $result = mysql_query($query);
      
      if($result)
      {
        header("Location: mgr.php?nav=4");
      }
      else 
      {
        //echo "SOrry".mysql_error();  
      }
     
    break;  
    
    case "edit":
      $id = $_GET['id'];
      $title = $_POST['contest_title'];
      $description = $_POST['contest_description'];
      $start = $_POST['contest_start'];
      $end = $_POST['contest_end'];
      
      $query = "update faq_type set title='$title', description='$description', start='$start', end='$end' where id='$id'";
      
      $result = mysql_query($query);
      if($result)
      {
        header("Location: mgr.php?nav=2");
      }
      else 
      {
        //echo "SOrry".mysql_error();  
      }
    break;
    
    case "delete":
      $id = $_GET['id'];
            
      $query = "DELETE FROM contest WHERE id = '$id'";
      $result = mysql_query($query);
      if($result)
      {
        header("Location: mgr.php?nav=2");
      }
      else 
      {
        //echo "delete error";
      }
    break;
    default:
			header("location: mgr.php?nav=2");
			exit;
		break;
  }
  
  
  
?>
