<h3>Uploading</h3>
<?php
	
  echo "hello";
  //session_start();
	//include( "check_login_status.php" );
	
	// PHOTO GALLERY ACTIONS - UPDATED 3.22.04
	
	include( "config_mgr.php" );
  
  $settings_result = mysql_query("SELECT * FROM settings where id = '1'", $db);
	$setting = mysql_fetch_object($settings_result);
	
  
  switch($_GET['pmode']){
    case "banner_upload":
      
      $banner_name = $_FILES['banner_file']['name'];
      $banner_dir = "../banner/".$_FILES['banner_file']['name'];
      
      if(1)
      {
        $lst = explode('.', strtolower ($banner_name) );
        //print_r($lst);
        if($lst[ count($lst) - 1 ] != "swf" && $lst[ count($lst) - 1 ] != "jpg" && $lst[ count($lst) - 1 ] != "jpeg" && 
          $lst[ count($lst) - 1 ] != "gif" && $lst[ count($lst) - 1 ] != "png")
        {
          echo "SWF флаш, эсвэл JPG, PNG, GIF зураг upload хийнэ үү.";
          exit;
        }
      }
      if(move_uploaded_file($_FILES['banner_file']['tmp_name'],  $banner_dir))
      {
        echo "SUCCESS";
        $banner = explode(".", $banner_name);
        
        $title = $_POST['banner_title'];
        $link = $_POST['banner_link'];
        $area = $_POST['banner_area'];
        $query = "INSERT INTO banner (title, link, area, file) VALUES ('$title', '$link', '$area','$banner_name')";
        $result  = mysql_query($query);
        if($result){
          echo "database changed";
          header("Location: mgr.php?nav=1");
        }else{
          echo "database couldn't changed".mysql_error();
        }
        
      }
      else
      {
        echo "sorry".$_FILES['banner_file']['error'];
        exit;
      }
    
    break;  
    case "change_show":
      $id = $_GET['id'];
      $status = $_GET['status'];
      if($status == 1)
      {
        $status = 0;
      }
      else
      {
         $status = 1;
      }
      $query = "UPDATE `banner` SET `show` = '$status'  WHERE `id` = $id ";
      
      $result = mysql_query($query);
      if($result)
      {
        header("Location: mgr.php?nav=1");
      }
      else
      {
        echo "error ".$query;
        exit;
      }
    break;
    
    case "delete":
      $id = $_GET['id'];
      $file_name = "../banner/";
      $query = "SELECT file FROM banner WHERE id = '$id'";
      $result = mysql_query($query);
      $banner = mysql_fetch_object($result);
      $file_name .= $banner->file;
      
      $query = "DELETE FROM banner WHERE id = '$id'";
      $result = mysql_query($query);
      if($result)
      {
        unlink($file_name);
        header("Location: mgr.php?nav=1");
      }
      else 
      {
        echo "delete error";
      }
    break;
    default:
			//header("location: login.php");
			exit;
		break;
  }
  
  
  
?>
