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
    case "lang_upload":
      
      $language_name = $_FILES['lang_source']['name'];
      $language_dir = "../language/".$_FILES['lang_source']['name'];
      
      if(1)
      {
        $lst = explode('.', $language_name);
        print_r($lst);
        if($lst[ count($lst) - 1 ] != "php")
        {
          echo "Зөвхөн PHP файл upload хийнэ үү.";
          exit;
        }
      }
      if(move_uploaded_file($_FILES['lang_source']['tmp_name'],  $language_dir))
      {
        echo "SUCCESS";
        $lang = explode(".", $language_name);
        $lang = $lang[0];
        
        $query = "ALTER TABLE `photo_galleries` ADD `title_$lang` VARCHAR(255) NOT NULL AFTER `title`";
        $result  = mysql_query($query);
        if($result){
          echo "database changed";
          //header("Location: mgr.php?nav=0");
        }else{
          echo "database couldn't changed".mysql_error();
        }
        
        $query = "ALTER TABLE `news` ADD `article_$lang` TEXT NOT NULL AFTER `id`, ADD `title_$lang` VARCHAR(255) NOT NULL AFTER `id`";
        $result  = mysql_query($query);
        if($result){
          echo "database changed";
          //header("Location: mgr.php?nav=0");
        }else{
          echo "database couldn't changed".mysql_error();
        }
       
        $query = "ALTER TABLE `copy_areas` ADD `article_$lang` TEXT NOT NULL AFTER `id`, ADD `title_$lang` VARCHAR(255) NOT NULL AFTER `id`";
        $result  = mysql_query($query);
        if($result){
          echo "database changed";
          header("Location: mgr.php?nav=0");
        }else{
          echo "database couldn't changed".mysql_error();
        } 
      }
      else
      {
        echo "sorry".$_FILES['lang_source']['error'];
        exit;
      }
    
    break;  
    
    default:
			//header("location: login.php");
			exit;
		break;
  }
  
  
  
?>
