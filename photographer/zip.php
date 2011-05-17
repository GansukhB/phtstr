<?php

  // ----- Constants
  if (!defined('PCLZIP_READ_BLOCK_SIZE')) {
    define( 'PCLZIP_READ_BLOCK_SIZE', 2048 );
  }
  if (!defined('PCLZIP_SEPARATOR')) {
    define( 'PCLZIP_SEPARATOR', ',' );
  }
  if (!defined('PCLZIP_ERROR_EXTERNAL')) {
    define( 'PCLZIP_ERROR_EXTERNAL', 0 );
  }
  if (!defined('PCLZIP_TEMPORARY_DIR')) {
    define( 'PCLZIP_TEMPORARY_DIR', '' );
  }

// --------------------------------------------------------------------------------
// ***** UNDER THIS LINE NOTHING NEEDS TO BE MODIFIED *****
// --------------------------------------------------------------------------------

  // ----- Global variables
  $g_pclzip_version = "2.6";

  // ----- Error codes
  //   -1 : Unable to open file in binary write mode
  //   -2 : Unable to open file in binary read mode
  //   -3 : Invalid parameters
  //   -4 : File does not exist
  //   -5 : Filename is too long (max. 255)
  //   -6 : Not a valid zip file
  //   -7 : Invalid extracted file size
  //   -8 : Unable to create directory
  //   -9 : Invalid archive extension
  //  -10 : Invalid archive format
  //  -11 : Unable to delete file (unlink)
  //  -12 : Unable to rename file (rename)
  //  -13 : Invalid header checksum
  //  -14 : Invalid archive size
  define( 'PCLZIP_ERR_USER_ABORTED', 2 );
  define( 'PCLZIP_ERR_NO_ERROR', 0 );
  define( 'PCLZIP_ERR_WRITE_OPEN_FAIL', -1 );
  define( 'PCLZIP_ERR_READ_OPEN_FAIL', -2 );
  define( 'PCLZIP_ERR_INVALID_PARAMETER', -3 );
  define( 'PCLZIP_ERR_MISSING_FILE', -4 );
  define( 'PCLZIP_ERR_FILENAME_TOO_LONG', -5 );
  define( 'PCLZIP_ERR_INVALID_ZIP', -6 );
  define( 'PCLZIP_ERR_BAD_EXTRACTED_FILE', -7 );
  define( 'PCLZIP_ERR_DIR_CREATE_FAIL', -8 );
  define( 'PCLZIP_ERR_BAD_EXTENSION', -9 );
  define( 'PCLZIP_ERR_BAD_FORMAT', -10 );
  define( 'PCLZIP_ERR_DELETE_FILE_FAIL', -11 );
  define( 'PCLZIP_ERR_RENAME_FILE_FAIL', -12 );
  define( 'PCLZIP_ERR_BAD_CHECKSUM', -13 );
  define( 'PCLZIP_ERR_INVALID_ARCHIVE_ZIP', -14 );
  define( 'PCLZIP_ERR_MISSING_OPTION_VALUE', -15 );
  define( 'PCLZIP_ERR_INVALID_OPTION_VALUE', -16 );
  define( 'PCLZIP_ERR_ALREADY_A_DIRECTORY', -17 );
  define( 'PCLZIP_ERR_UNSUPPORTED_COMPRESSION', -18 );
  define( 'PCLZIP_ERR_UNSUPPORTED_ENCRYPTION', -19 );
  define( 'PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE', -20 );
  define( 'PCLZIP_ERR_DIRECTORY_RESTRICTION', -21 );

  // ----- Options values
  define( 'PCLZIP_OPT_PATH', 77001 );
  define( 'PCLZIP_OPT_ADD_PATH', 77002 );
  define( 'PCLZIP_OPT_REMOVE_PATH', 77003 );
  define( 'PCLZIP_OPT_REMOVE_ALL_PATH', 77004 );
  define( 'PCLZIP_OPT_SET_CHMOD', 77005 );
  define( 'PCLZIP_OPT_EXTRACT_AS_STRING', 77006 );
  define( 'PCLZIP_OPT_NO_COMPRESSION', 77007 );
  define( 'PCLZIP_OPT_BY_NAME', 77008 );
  define( 'PCLZIP_OPT_BY_INDEX', 77009 );
  define( 'PCLZIP_OPT_BY_EREG', 77010 );
  define( 'PCLZIP_OPT_BY_PREG', 77011 );
  define( 'PCLZIP_OPT_COMMENT', 77012 );
  define( 'PCLZIP_OPT_ADD_COMMENT', 77013 );
  define( 'PCLZIP_OPT_PREPEND_COMMENT', 77014 );
  define( 'PCLZIP_OPT_EXTRACT_IN_OUTPUT', 77015 );
  define( 'PCLZIP_OPT_REPLACE_NEWER', 77016 );
  define( 'PCLZIP_OPT_STOP_ON_ERROR', 77017 );
  // Having big trouble with crypt. Need to multiply 2 long int
  // which is not correctly supported by PHP ...
  //define( 'PCLZIP_OPT_CRYPT', 77018 );
  define( 'PCLZIP_OPT_EXTRACT_DIR_RESTRICTION', 77019 );
  
  // ----- File description attributes
  define( 'PCLZIP_ATT_FILE_NAME', 79001 );
  define( 'PCLZIP_ATT_FILE_NEW_SHORT_NAME', 79002 );
  define( 'PCLZIP_ATT_FILE_NEW_FULL_NAME', 79003 );
  define( 'PCLZIP_ATT_FILE_MTIME', 79004 );
  define( 'PCLZIP_ATT_FILE_CONTENT', 79005 );
  define( 'PCLZIP_ATT_FILE_COMMENT', 79006 );

  // ----- Call backs values
  define( 'PCLZIP_CB_PRE_EXTRACT', 78001 );
  define( 'PCLZIP_CB_POST_EXTRACT', 78002 );
  define( 'PCLZIP_CB_PRE_ADD', 78003 );
  define( 'PCLZIP_CB_POST_ADD', 78004 );
  class PclZip
  {
    // ----- Filename of the zip file
    var $zipname = '';

    // ----- File descriptor of the zip file
    var $zip_fd = 0;

    // ----- Internal error handling
    var $error_code = 1;
    var $error_string = '';
    
    // ----- Current status of the magic_quotes_runtime
    // This value store the php configuration for magic_quotes
    // The class can then disable the magic_quotes and reset it after
    var $magic_quotes_status;

  function PclZip($p_zipname)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::PclZip', "zipname=$p_zipname");

    // ----- Tests the zlib
    if (!function_exists('gzopen'))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 1, "zlib extension seems to be missing");
      die('Abort '.basename(__FILE__).' : Missing zlib extensions');
    }

    // ----- Set the attributes
    $this->zipname = $p_zipname;
    $this->zip_fd = 0;
    $this->magic_quotes_status = -1;

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 1);
    return;
  }
  // --------------------------------------------------------------------------------

  function create($p_filelist)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::create', "filelist='$p_filelist', ...");
    $v_result=1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Set default values
    $v_options = array();
    $v_options[PCLZIP_OPT_NO_COMPRESSION] = FALSE;

    // ----- Look for variable options arguments
    $v_size = func_num_args();
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "$v_size arguments passed to the method");

    // ----- Look for arguments
    if ($v_size > 1) {
      // ----- Get the arguments
      $v_arg_list = func_get_args();

      // ----- Remove from the options list the first argument
      array_shift($v_arg_list);
      $v_size--;

      // ----- Look for first arg
      if ((is_integer($v_arg_list[0])) && ($v_arg_list[0] > 77000)) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Variable list of options detected");

        // ----- Parse the options
        $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options,
                                            array (PCLZIP_OPT_REMOVE_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_ALL_PATH => 'optional',
                                                   PCLZIP_OPT_ADD_PATH => 'optional',
                                                   PCLZIP_CB_PRE_ADD => 'optional',
                                                   PCLZIP_CB_POST_ADD => 'optional',
                                                   PCLZIP_OPT_NO_COMPRESSION => 'optional',
                                                   PCLZIP_OPT_COMMENT => 'optional'
                                                   //, PCLZIP_OPT_CRYPT => 'optional'
                                             ));
        if ($v_result != 1) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
          return 0;
        }
      }

      // ----- Look for 2 args
      // Here we need to support the first historic synopsis of the
      // method.
      else {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Static synopsis");

        // ----- Get the first argument
        $v_options[PCLZIP_OPT_ADD_PATH] = $v_arg_list[0];

        // ----- Look for the optional second argument
        if ($v_size == 2) {
          $v_options[PCLZIP_OPT_REMOVE_PATH] = $v_arg_list[1];
        }
        else if ($v_size > 2) {
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER,
		                       "Invalid number / type of arguments");
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
          return 0;
        }
      }
    }

    // ----- Init
    $v_string_list = array();
    $v_att_list = array();
    $v_filedescr_list = array();
    $p_result_list = array();
    
    // ----- Look if the $p_filelist is really an array
    if (is_array($p_filelist)) {
    
      // ----- Look if the first element is also an array
      //       This will mean that this is a file description entry
      if (isset($p_filelist[0]) && is_array($p_filelist[0])) {
        $v_att_list = $p_filelist;
      }
      
      // ----- The list is a list of string names
      else {
        $v_string_list = $p_filelist;
      }
    }

    // ----- Look if the $p_filelist is a string
    else if (is_string($p_filelist)) {
      // ----- Create a list from the string
      $v_string_list = explode(PCLZIP_SEPARATOR, $p_filelist);
    }

    // ----- Invalid variable type for $p_filelist
    else {
      PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid variable type p_filelist");
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return 0;
    }
    
    // ----- Reformat the string list
    if (sizeof($v_string_list) != 0) {
      foreach ($v_string_list as $v_string) {
        if ($v_string != '') {
          $v_att_list[][PCLZIP_ATT_FILE_NAME] = $v_string;
        }
        else {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Ignore an empty filename");
        }
      }
    }
    
    // ----- For each file in the list check the attributes
    $v_supported_attributes
    = array ( PCLZIP_ATT_FILE_NAME => 'mandatory'
             ,PCLZIP_ATT_FILE_NEW_SHORT_NAME => 'optional'
             ,PCLZIP_ATT_FILE_NEW_FULL_NAME => 'optional'
             ,PCLZIP_ATT_FILE_MTIME => 'optional'
             ,PCLZIP_ATT_FILE_CONTENT => 'optional'
             ,PCLZIP_ATT_FILE_COMMENT => 'optional'
						);
    foreach ($v_att_list as $v_entry) {
      $v_result = $this->privFileDescrParseAtt($v_entry,
                                               $v_filedescr_list[],
                                               $v_options,
                                               $v_supported_attributes);
      if ($v_result != 1) {
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
        return 0;
      }
    }

    // ----- Expand the filelist (expand directories)
    $v_result = $this->privFileDescrExpand($v_filedescr_list, $v_options);
    if ($v_result != 1) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return 0;
    }

    // ----- Call the create fct
    $v_result = $this->privCreate($v_filedescr_list, $p_result_list, $v_options);
    if ($v_result != 1) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return 0;
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $p_result_list);
    return $p_result_list;
  }
  // --------------------------------------------------------------------------------

  function add($p_filelist)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::add', "filelist='$p_filelist', ...");
    $v_result=1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Set default values
    $v_options = array();
    $v_options[PCLZIP_OPT_NO_COMPRESSION] = FALSE;

    // ----- Look for variable options arguments
    $v_size = func_num_args();
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "$v_size arguments passed to the method");

    // ----- Look for arguments
    if ($v_size > 1) {
      // ----- Get the arguments
      $v_arg_list = func_get_args();

      // ----- Remove form the options list the first argument
      array_shift($v_arg_list);
      $v_size--;

      // ----- Look for first arg
      if ((is_integer($v_arg_list[0])) && ($v_arg_list[0] > 77000)) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Variable list of options detected");

        // ----- Parse the options
        $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options,
                                            array (PCLZIP_OPT_REMOVE_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_ALL_PATH => 'optional',
                                                   PCLZIP_OPT_ADD_PATH => 'optional',
                                                   PCLZIP_CB_PRE_ADD => 'optional',
                                                   PCLZIP_CB_POST_ADD => 'optional',
                                                   PCLZIP_OPT_NO_COMPRESSION => 'optional',
                                                   PCLZIP_OPT_COMMENT => 'optional',
                                                   PCLZIP_OPT_ADD_COMMENT => 'optional',
                                                   PCLZIP_OPT_PREPEND_COMMENT => 'optional'
                                                   //, PCLZIP_OPT_CRYPT => 'optional'
												   ));
        if ($v_result != 1) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
          return 0;
        }
      }

      // ----- Look for 2 args
      // Here we need to support the first historic synopsis of the
      // method.
      else {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Static synopsis");

        // ----- Get the first argument
        $v_options[PCLZIP_OPT_ADD_PATH] = $v_add_path = $v_arg_list[0];

        // ----- Look for the optional second argument
        if ($v_size == 2) {
          $v_options[PCLZIP_OPT_REMOVE_PATH] = $v_arg_list[1];
        }
        else if ($v_size > 2) {
          // ----- Error log
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid number / type of arguments");

          // ----- Return
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
          return 0;
        }
      }
    }

    // ----- Init
    $v_string_list = array();
    $v_att_list = array();
    $v_filedescr_list = array();
    $p_result_list = array();
    
    // ----- Look if the $p_filelist is really an array
    if (is_array($p_filelist)) {
    
      // ----- Look if the first element is also an array
      //       This will mean that this is a file description entry
      if (isset($p_filelist[0]) && is_array($p_filelist[0])) {
        $v_att_list = $p_filelist;
      }
      
      // ----- The list is a list of string names
      else {
        $v_string_list = $p_filelist;
      }
    }

    // ----- Look if the $p_filelist is a string
    else if (is_string($p_filelist)) {
      // ----- Create a list from the string
      $v_string_list = explode(PCLZIP_SEPARATOR, $p_filelist);
    }

    // ----- Invalid variable type for $p_filelist
    else {
      PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid variable type '".gettype($p_filelist)."' for p_filelist");
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return 0;
    }
    
    // ----- Reformat the string list
    if (sizeof($v_string_list) != 0) {
      foreach ($v_string_list as $v_string) {
        $v_att_list[][PCLZIP_ATT_FILE_NAME] = $v_string;
      }
    }
    
    // ----- For each file in the list check the attributes
    $v_supported_attributes
    = array ( PCLZIP_ATT_FILE_NAME => 'mandatory'
             ,PCLZIP_ATT_FILE_NEW_SHORT_NAME => 'optional'
             ,PCLZIP_ATT_FILE_NEW_FULL_NAME => 'optional'
             ,PCLZIP_ATT_FILE_MTIME => 'optional'
             ,PCLZIP_ATT_FILE_CONTENT => 'optional'
             ,PCLZIP_ATT_FILE_COMMENT => 'optional'
						);
    foreach ($v_att_list as $v_entry) {
      $v_result = $this->privFileDescrParseAtt($v_entry,
                                               $v_filedescr_list[],
                                               $v_options,
                                               $v_supported_attributes);
      if ($v_result != 1) {
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
        return 0;
      }
    }

    // ----- Expand the filelist (expand directories)
    $v_result = $this->privFileDescrExpand($v_filedescr_list, $v_options);
    if ($v_result != 1) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return 0;
    }

    // ----- Call the create fct
    $v_result = $this->privAdd($v_filedescr_list, $p_result_list, $v_options);
    if ($v_result != 1) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return 0;
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $p_result_list);
    return $p_result_list;
  }
  // --------------------------------------------------------------------------------

  function listContent()
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::listContent', "");
    $v_result=1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Check archive
    if (!$this->privCheckFormat()) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return(0);
    }

    // ----- Call the extracting fct
    $p_list = array();
    if (($v_result = $this->privList($p_list)) != 1)
    {
      unset($p_list);
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0, PclZip::errorInfo());
      return(0);
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $p_list);
    return $p_list;
  }
  // --------------------------------------------------------------------------------

  function extract()
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::extract", "");
    $v_result=1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Check archive
    if (!$this->privCheckFormat()) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return(0);
    }

    // ----- Set default values
    $v_options = array();
//    $v_path = "./";
    $v_path = '';
    $v_remove_path = "";
    $v_remove_all_path = false;

    // ----- Look for variable options arguments
    $v_size = func_num_args();
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "$v_size arguments passed to the method");

    // ----- Default values for option
    $v_options[PCLZIP_OPT_EXTRACT_AS_STRING] = FALSE;

    // ----- Look for arguments
    if ($v_size > 0) {
      // ----- Get the arguments
      $v_arg_list = func_get_args();

      // ----- Look for first arg
      if ((is_integer($v_arg_list[0])) && ($v_arg_list[0] > 77000)) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Variable list of options");

        // ----- Parse the options
        $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options,
                                            array (PCLZIP_OPT_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_ALL_PATH => 'optional',
                                                   PCLZIP_OPT_ADD_PATH => 'optional',
                                                   PCLZIP_CB_PRE_EXTRACT => 'optional',
                                                   PCLZIP_CB_POST_EXTRACT => 'optional',
                                                   PCLZIP_OPT_SET_CHMOD => 'optional',
                                                   PCLZIP_OPT_BY_NAME => 'optional',
                                                   PCLZIP_OPT_BY_EREG => 'optional',
                                                   PCLZIP_OPT_BY_PREG => 'optional',
                                                   PCLZIP_OPT_BY_INDEX => 'optional',
                                                   PCLZIP_OPT_EXTRACT_AS_STRING => 'optional',
                                                   PCLZIP_OPT_EXTRACT_IN_OUTPUT => 'optional',
                                                   PCLZIP_OPT_REPLACE_NEWER => 'optional'
                                                   ,PCLZIP_OPT_STOP_ON_ERROR => 'optional'
                                                   ,PCLZIP_OPT_EXTRACT_DIR_RESTRICTION => 'optional'
												    ));
        if ($v_result != 1) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
          return 0;
        }

        // ----- Set the arguments
        if (isset($v_options[PCLZIP_OPT_PATH])) {
          $v_path = $v_options[PCLZIP_OPT_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_REMOVE_PATH])) {
          $v_remove_path = $v_options[PCLZIP_OPT_REMOVE_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_REMOVE_ALL_PATH])) {
          $v_remove_all_path = $v_options[PCLZIP_OPT_REMOVE_ALL_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_ADD_PATH])) {
          // ----- Check for '/' in last path char
          if ((strlen($v_path) > 0) && (substr($v_path, -1) != '/')) {
            $v_path .= '/';
          }
          $v_path .= $v_options[PCLZIP_OPT_ADD_PATH];
        }
      }

      // ----- Look for 2 args
      // Here we need to support the first historic synopsis of the
      // method.
      else {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Static synopsis");

        // ----- Get the first argument
        $v_path = $v_arg_list[0];

        // ----- Look for the optional second argument
        if ($v_size == 2) {
          $v_remove_path = $v_arg_list[1];
        }
        else if ($v_size > 2) {
          // ----- Error log
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid number / type of arguments");

          // ----- Return
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0, PclZip::errorInfo());
          return 0;
        }
      }
    }

    // ----- Trace
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "path='$v_path', remove_path='$v_remove_path', remove_all_path='".($v_remove_path?'true':'false')."'");

    // ----- Call the extracting fct
    $p_list = array();
    $v_result = $this->privExtractByRule($p_list, $v_path, $v_remove_path,
	                                     $v_remove_all_path, $v_options);
    if ($v_result < 1) {
      unset($p_list);
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0, PclZip::errorInfo());
      return(0);
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $p_list);
    return $p_list;
  }
  // --------------------------------------------------------------------------------
  //function extractByIndex($p_index, options...)
  function extractByIndex($p_index)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::extractByIndex", "index='$p_index', ...");
    $v_result=1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Check archive
    if (!$this->privCheckFormat()) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return(0);
    }

    // ----- Set default values
    $v_options = array();
//    $v_path = "./";
    $v_path = '';
    $v_remove_path = "";
    $v_remove_all_path = false;

    // ----- Look for variable options arguments
    $v_size = func_num_args();
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "$v_size arguments passed to the method");

    // ----- Default values for option
    $v_options[PCLZIP_OPT_EXTRACT_AS_STRING] = FALSE;

    // ----- Look for arguments
    if ($v_size > 1) {
      // ----- Get the arguments
      $v_arg_list = func_get_args();

      // ----- Remove form the options list the first argument
      array_shift($v_arg_list);
      $v_size--;

      // ----- Look for first arg
      if ((is_integer($v_arg_list[0])) && ($v_arg_list[0] > 77000)) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Variable list of options");

        // ----- Parse the options
        $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options,
                                            array (PCLZIP_OPT_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_ALL_PATH => 'optional',
                                                   PCLZIP_OPT_EXTRACT_AS_STRING => 'optional',
                                                   PCLZIP_OPT_ADD_PATH => 'optional',
                                                   PCLZIP_CB_PRE_EXTRACT => 'optional',
                                                   PCLZIP_CB_POST_EXTRACT => 'optional',
                                                   PCLZIP_OPT_SET_CHMOD => 'optional',
                                                   PCLZIP_OPT_REPLACE_NEWER => 'optional'
                                                   ,PCLZIP_OPT_STOP_ON_ERROR => 'optional'
                                                   ,PCLZIP_OPT_EXTRACT_DIR_RESTRICTION => 'optional'
												   ));
        if ($v_result != 1) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
          return 0;
        }

        // ----- Set the arguments
        if (isset($v_options[PCLZIP_OPT_PATH])) {
          $v_path = $v_options[PCLZIP_OPT_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_REMOVE_PATH])) {
          $v_remove_path = $v_options[PCLZIP_OPT_REMOVE_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_REMOVE_ALL_PATH])) {
          $v_remove_all_path = $v_options[PCLZIP_OPT_REMOVE_ALL_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_ADD_PATH])) {
          // ----- Check for '/' in last path char
          if ((strlen($v_path) > 0) && (substr($v_path, -1) != '/')) {
            $v_path .= '/';
          }
          $v_path .= $v_options[PCLZIP_OPT_ADD_PATH];
        }
        if (!isset($v_options[PCLZIP_OPT_EXTRACT_AS_STRING])) {
          $v_options[PCLZIP_OPT_EXTRACT_AS_STRING] = FALSE;
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Option PCLZIP_OPT_EXTRACT_AS_STRING not set.");
        }
        else {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Option PCLZIP_OPT_EXTRACT_AS_STRING set.");
        }
      }

      // ----- Look for 2 args
      // Here we need to support the first historic synopsis of the
      // method.
      else {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Static synopsis");

        // ----- Get the first argument
        $v_path = $v_arg_list[0];

        // ----- Look for the optional second argument
        if ($v_size == 2) {
          $v_remove_path = $v_arg_list[1];
        }
        else if ($v_size > 2) {
          // ----- Error log
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid number / type of arguments");

          // ----- Return
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
          return 0;
        }
      }
    }

    // ----- Trace
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "index='$p_index', path='$v_path', remove_path='$v_remove_path', remove_all_path='".($v_remove_path?'true':'false')."'");

    // ----- Trick
    // Here I want to reuse extractByRule(), so I need to parse the $p_index
    // with privParseOptions()
    $v_arg_trick = array (PCLZIP_OPT_BY_INDEX, $p_index);
    $v_options_trick = array();
    $v_result = $this->privParseOptions($v_arg_trick, sizeof($v_arg_trick), $v_options_trick,
                                        array (PCLZIP_OPT_BY_INDEX => 'optional' ));
    if ($v_result != 1) {
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
        return 0;
    }
    $v_options[PCLZIP_OPT_BY_INDEX] = $v_options_trick[PCLZIP_OPT_BY_INDEX];

    // ----- Call the extracting fct
    if (($v_result = $this->privExtractByRule($p_list, $v_path, $v_remove_path, $v_remove_all_path, $v_options)) < 1) {
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0, PclZip::errorInfo());
        return(0);
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $p_list);
    return $p_list;
  }
  // --------------------------------------------------------------------------------

  function delete()
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::delete", "");
    $v_result=1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Check archive
    if (!$this->privCheckFormat()) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return(0);
    }

    // ----- Set default values
    $v_options = array();

    // ----- Look for variable options arguments
    $v_size = func_num_args();
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "$v_size arguments passed to the method");

    // ----- Look for arguments
    if ($v_size > 0) {
      // ----- Get the arguments
      $v_arg_list = func_get_args();

      // ----- Parse the options
      $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options,
                                        array (PCLZIP_OPT_BY_NAME => 'optional',
                                               PCLZIP_OPT_BY_EREG => 'optional',
                                               PCLZIP_OPT_BY_PREG => 'optional',
                                               PCLZIP_OPT_BY_INDEX => 'optional' ));
      if ($v_result != 1) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
          return 0;
      }
    }

    // ----- Magic quotes trick
    $this->privDisableMagicQuotes();

    // ----- Call the delete fct
    $v_list = array();
    if (($v_result = $this->privDeleteByRule($v_list, $v_options)) != 1) {
      $this->privSwapBackMagicQuotes();
      unset($v_list);
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0, PclZip::errorInfo());
      return(0);
    }

    // ----- Magic quotes trick
    $this->privSwapBackMagicQuotes();

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_list);
    return $v_list;
  }
  // --------------------------------------------------------------------------------

  function deleteByIndex($p_index)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::deleteByIndex", "index='$p_index'");
    
    $p_list = $this->delete(PCLZIP_OPT_BY_INDEX, $p_index);

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $p_list);
    return $p_list;
  }
  // --------------------------------------------------------------------------------
  function properties()
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::properties", "");

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Magic quotes trick
    $this->privDisableMagicQuotes();

    // ----- Check archive
    if (!$this->privCheckFormat()) {
      $this->privSwapBackMagicQuotes();
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return(0);
    }

    // ----- Default properties
    $v_prop = array();
    $v_prop['comment'] = '';
    $v_prop['nb'] = 0;
    $v_prop['status'] = 'not_exist';

    // ----- Look if file exists
    if (@is_file($this->zipname))
    {
      // ----- Open the zip file
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
      if (($this->zip_fd = @fopen($this->zipname, 'rb')) == 0)
      {
        $this->privSwapBackMagicQuotes();
        
        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open archive \''.$this->zipname.'\' in binary read mode');

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), 0);
        return 0;
      }

      // ----- Read the central directory informations
      $v_central_dir = array();
      if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)
      {
        $this->privSwapBackMagicQuotes();
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
        return 0;
      }

      // ----- Close the zip file
      $this->privCloseFd();

      // ----- Set the user attributes
      $v_prop['comment'] = $v_central_dir['comment'];
      $v_prop['nb'] = $v_central_dir['entries'];
      $v_prop['status'] = 'ok';
    }

    // ----- Magic quotes trick
    $this->privSwapBackMagicQuotes();

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_prop);
    return $v_prop;
  }
  // --------------------------------------------------------------------------------
  
  function duplicate($p_archive)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::duplicate", "");
    $v_result = 1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Look if the $p_archive is a PclZip object
    if ((is_object($p_archive)) && (get_class($p_archive) == 'pclzip'))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "The parameter is valid PclZip object '".$p_archive->zipname."'");

      // ----- Duplicate the archive
      $v_result = $this->privDuplicate($p_archive->zipname);
    }

    // ----- Look if the $p_archive is a string (so a filename)
    else if (is_string($p_archive))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "The parameter is a filename '$p_archive'");

      // ----- Check that $p_archive is a valid zip file
      // TBC : Should also check the archive format
      if (!is_file($p_archive)) {
        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "No file with filename '".$p_archive."'");
        $v_result = PCLZIP_ERR_MISSING_FILE;
      }
      else {
        // ----- Duplicate the archive
        $v_result = $this->privDuplicate($p_archive);
      }
    }

    // ----- Invalid variable
    else
    {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid variable type p_archive_to_add");
      $v_result = PCLZIP_ERR_INVALID_PARAMETER;
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function merge($p_archive_to_add)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::merge", "");
    $v_result = 1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Check archive
    if (!$this->privCheckFormat()) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, 0);
      return(0);
    }

    // ----- Look if the $p_archive_to_add is a PclZip object
    if ((is_object($p_archive_to_add)) && (get_class($p_archive_to_add) == 'pclzip'))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The parameter is valid PclZip object");

      // ----- Merge the archive
      $v_result = $this->privMerge($p_archive_to_add);
    }

    // ----- Look if the $p_archive_to_add is a string (so a filename)
    else if (is_string($p_archive_to_add))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The parameter is a filename");

      // ----- Create a temporary archive
      $v_object_archive = new PclZip($p_archive_to_add);

      // ----- Merge the archive
      $v_result = $this->privMerge($v_object_archive);
    }

    // ----- Invalid variable
    else
    {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid variable type p_archive_to_add");
      $v_result = PCLZIP_ERR_INVALID_PARAMETER;
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function errorCode()
  {
    if (PCLZIP_ERROR_EXTERNAL == 1) {
      return(PclErrorCode());
    }
    else {
      return($this->error_code);
    }
  }
  // --------------------------------------------------------------------------------
  
  function errorName($p_with_code=false)
  {
    $v_name = array ( PCLZIP_ERR_NO_ERROR => 'PCLZIP_ERR_NO_ERROR',
                      PCLZIP_ERR_WRITE_OPEN_FAIL => 'PCLZIP_ERR_WRITE_OPEN_FAIL',
                      PCLZIP_ERR_READ_OPEN_FAIL => 'PCLZIP_ERR_READ_OPEN_FAIL',
                      PCLZIP_ERR_INVALID_PARAMETER => 'PCLZIP_ERR_INVALID_PARAMETER',
                      PCLZIP_ERR_MISSING_FILE => 'PCLZIP_ERR_MISSING_FILE',
                      PCLZIP_ERR_FILENAME_TOO_LONG => 'PCLZIP_ERR_FILENAME_TOO_LONG',
                      PCLZIP_ERR_INVALID_ZIP => 'PCLZIP_ERR_INVALID_ZIP',
                      PCLZIP_ERR_BAD_EXTRACTED_FILE => 'PCLZIP_ERR_BAD_EXTRACTED_FILE',
                      PCLZIP_ERR_DIR_CREATE_FAIL => 'PCLZIP_ERR_DIR_CREATE_FAIL',
                      PCLZIP_ERR_BAD_EXTENSION => 'PCLZIP_ERR_BAD_EXTENSION',
                      PCLZIP_ERR_BAD_FORMAT => 'PCLZIP_ERR_BAD_FORMAT',
                      PCLZIP_ERR_DELETE_FILE_FAIL => 'PCLZIP_ERR_DELETE_FILE_FAIL',
                      PCLZIP_ERR_RENAME_FILE_FAIL => 'PCLZIP_ERR_RENAME_FILE_FAIL',
                      PCLZIP_ERR_BAD_CHECKSUM => 'PCLZIP_ERR_BAD_CHECKSUM',
                      PCLZIP_ERR_INVALID_ARCHIVE_ZIP => 'PCLZIP_ERR_INVALID_ARCHIVE_ZIP',
                      PCLZIP_ERR_MISSING_OPTION_VALUE => 'PCLZIP_ERR_MISSING_OPTION_VALUE',
                      PCLZIP_ERR_INVALID_OPTION_VALUE => 'PCLZIP_ERR_INVALID_OPTION_VALUE',
                      PCLZIP_ERR_UNSUPPORTED_COMPRESSION => 'PCLZIP_ERR_UNSUPPORTED_COMPRESSION',
                      PCLZIP_ERR_UNSUPPORTED_ENCRYPTION => 'PCLZIP_ERR_UNSUPPORTED_ENCRYPTION'
                      ,PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE => 'PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE'
                      ,PCLZIP_ERR_DIRECTORY_RESTRICTION => 'PCLZIP_ERR_DIRECTORY_RESTRICTION'
                    );

    if (isset($v_name[$this->error_code])) {
      $v_value = $v_name[$this->error_code];
    }
    else {
      $v_value = 'NoName';
    }

    if ($p_with_code) {
      return($v_value.' ('.$this->error_code.')');
    }
    else {
      return($v_value);
    }
  }
  // --------------------------------------------------------------------------------

  function errorInfo($p_full=false)
  {
    if (PCLZIP_ERROR_EXTERNAL == 1) {
      return(PclErrorString());
    }
    else {
      if ($p_full) {
        return($this->errorName(true)." : ".$this->error_string);
      }
      else {
        return($this->error_string." [code ".$this->error_code."]");
      }
    }
  }
  // --------------------------------------------------------------------------------

  function privCheckFormat($p_level=0)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privCheckFormat", "");
    $v_result = true;

	// ----- Reset the file system cache
    clearstatcache();

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Look if the file exits
    if (!is_file($this->zipname)) {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "Missing archive file '".$this->zipname."'");
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, false, PclZip::errorInfo());
      return(false);
    }

    // ----- Check that the file is readeable
    if (!is_readable($this->zipname)) {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "Unable to read archive '".$this->zipname."'");
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, false, PclZip::errorInfo());
      return(false);
    }
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privParseOptions(&$p_options_list, $p_size, &$v_result_list, $v_requested_options=false)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privParseOptions", "");
    $v_result=1;
    
    // ----- Read the options
    $i=0;
    while ($i<$p_size) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Looking for table index $i, option = '".PclZipUtilOptionText($p_options_list[$i])."(".$p_options_list[$i].")'");

      // ----- Check if the option is supported
      if (!isset($v_requested_options[$p_options_list[$i]])) {
        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid optional parameter '".$p_options_list[$i]."' for this method");

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }

      // ----- Look for next option
      switch ($p_options_list[$i]) {
        // ----- Look for options that request a path value
        case PCLZIP_OPT_PATH :
        case PCLZIP_OPT_REMOVE_PATH :
        case PCLZIP_OPT_ADD_PATH :
          // ----- Check the number of parameters
          if (($i+1) >= $p_size) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Get the value
          $v_result_list[$p_options_list[$i]] = PclZipUtilTranslateWinPath($p_options_list[$i+1], FALSE);
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($p_options_list[$i])." = '".$v_result_list[$p_options_list[$i]]."'");
          $i++;
        break;

        case PCLZIP_OPT_EXTRACT_DIR_RESTRICTION :
          // ----- Check the number of parameters
          if (($i+1) >= $p_size) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Get the value
          if (   is_string($p_options_list[$i+1])
              && ($p_options_list[$i+1] != '')) {
            $v_result_list[$p_options_list[$i]] = PclZipUtilTranslateWinPath($p_options_list[$i+1], FALSE);
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($p_options_list[$i])." = '".$v_result_list[$p_options_list[$i]]."'");
            $i++;
          }
          else {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($p_options_list[$i])." set with an empty value is ignored.");
          }
        break;

        // ----- Look for options that request an array of string for value
        case PCLZIP_OPT_BY_NAME :
          // ----- Check the number of parameters
          if (($i+1) >= $p_size) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Get the value
          if (is_string($p_options_list[$i+1])) {
              $v_result_list[$p_options_list[$i]][0] = $p_options_list[$i+1];
          }
          else if (is_array($p_options_list[$i+1])) {
              $v_result_list[$p_options_list[$i]] = $p_options_list[$i+1];
          }
          else {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "Wrong parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }
          ////--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($p_options_list[$i])." = '".$v_result_list[$p_options_list[$i]]."'");
          $i++;
        break;

        // ----- Look for options that request an EREG or PREG expression
        case PCLZIP_OPT_BY_EREG :
        case PCLZIP_OPT_BY_PREG :
        //case PCLZIP_OPT_CRYPT :
          // ----- Check the number of parameters
          if (($i+1) >= $p_size) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Get the value
          if (is_string($p_options_list[$i+1])) {
              $v_result_list[$p_options_list[$i]] = $p_options_list[$i+1];
          }
          else {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "Wrong parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($p_options_list[$i])." = '".$v_result_list[$p_options_list[$i]]."'");
          $i++;
        break;

        // ----- Look for options that takes a string
        case PCLZIP_OPT_COMMENT :
        case PCLZIP_OPT_ADD_COMMENT :
        case PCLZIP_OPT_PREPEND_COMMENT :
          // ----- Check the number of parameters
          if (($i+1) >= $p_size) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE,
			                     "Missing parameter value for option '"
								 .PclZipUtilOptionText($p_options_list[$i])
								 ."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Get the value
          if (is_string($p_options_list[$i+1])) {
              $v_result_list[$p_options_list[$i]] = $p_options_list[$i+1];
          }
          else {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE,
			                     "Wrong parameter value for option '"
								 .PclZipUtilOptionText($p_options_list[$i])
								 ."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($p_options_list[$i])." = '".$v_result_list[$p_options_list[$i]]."'");
          $i++;
        break;

        // ----- Look for options that request an array of index
        case PCLZIP_OPT_BY_INDEX :
          // ----- Check the number of parameters
          if (($i+1) >= $p_size) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Get the value
          $v_work_list = array();
          if (is_string($p_options_list[$i+1])) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Index value is a string '".$p_options_list[$i+1]."'");

              // ----- Remove spaces
              $p_options_list[$i+1] = strtr($p_options_list[$i+1], ' ', '');

              // ----- Parse items
              $v_work_list = explode(",", $p_options_list[$i+1]);
          }
          else if (is_integer($p_options_list[$i+1])) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Index value is an integer '".$p_options_list[$i+1]."'");
              $v_work_list[0] = $p_options_list[$i+1].'-'.$p_options_list[$i+1];
          }
          else if (is_array($p_options_list[$i+1])) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Index value is an array");
              $v_work_list = $p_options_list[$i+1];
          }
          else {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "Value must be integer, string or array for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }
          
          // ----- Reduce the index list
          // each index item in the list must be a couple with a start and
          // an end value : [0,3], [5-5], [8-10], ...
          // ----- Check the format of each item
          $v_sort_flag=false;
          $v_sort_value=0;
          for ($j=0; $j<sizeof($v_work_list); $j++) {
              // ----- Explode the item
              $v_item_list = explode("-", $v_work_list[$j]);
              $v_size_item_list = sizeof($v_item_list);
              
              // ----- TBC : Here we might check that each item is a
              // real integer ...
              
              // ----- Look for single value
              if ($v_size_item_list == 1) {
                  // ----- Set the option value
                  $v_result_list[$p_options_list[$i]][$j]['start'] = $v_item_list[0];
                  $v_result_list[$p_options_list[$i]][$j]['end'] = $v_item_list[0];
              }
              elseif ($v_size_item_list == 2) {
                  // ----- Set the option value
                  $v_result_list[$p_options_list[$i]][$j]['start'] = $v_item_list[0];
                  $v_result_list[$p_options_list[$i]][$j]['end'] = $v_item_list[1];
              }
              else {
                  // ----- Error log
                  PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "Too many values in index range for option '".PclZipUtilOptionText($p_options_list[$i])."'");

                  // ----- Return
                  //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
                  return PclZip::errorCode();
              }

              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extracted index item = [".$v_result_list[$p_options_list[$i]][$j]['start'].",".$v_result_list[$p_options_list[$i]][$j]['end']."]");

              // ----- Look for list sort
              if ($v_result_list[$p_options_list[$i]][$j]['start'] < $v_sort_value) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The list should be sorted ...");
                  $v_sort_flag=true;

                  // ----- TBC : An automatic sort should be writen ...
                  // ----- Error log
                  PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "Invalid order of index range for option '".PclZipUtilOptionText($p_options_list[$i])."'");

                  // ----- Return
                  //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
                  return PclZip::errorCode();
              }
              $v_sort_value = $v_result_list[$p_options_list[$i]][$j]['start'];
          }
          
          // ----- Sort the items
          if ($v_sort_flag) {
              // TBC : To Be Completed
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "List sorting is not yet write ...");
          }

          // ----- Next option
          $i++;
        break;

        // ----- Look for options that request no value
        case PCLZIP_OPT_REMOVE_ALL_PATH :
        case PCLZIP_OPT_EXTRACT_AS_STRING :
        case PCLZIP_OPT_NO_COMPRESSION :
        case PCLZIP_OPT_EXTRACT_IN_OUTPUT :
        case PCLZIP_OPT_REPLACE_NEWER :
        case PCLZIP_OPT_STOP_ON_ERROR :
          $v_result_list[$p_options_list[$i]] = true;
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($p_options_list[$i])." = '".$v_result_list[$p_options_list[$i]]."'");
        break;

        // ----- Look for options that request an octal value
        case PCLZIP_OPT_SET_CHMOD :
          // ----- Check the number of parameters
          if (($i+1) >= $p_size) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Get the value
          $v_result_list[$p_options_list[$i]] = $p_options_list[$i+1];
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($p_options_list[$i])." = '".$v_result_list[$p_options_list[$i]]."'");
          $i++;
        break;

        // ----- Look for options that request a call-back
        case PCLZIP_CB_PRE_EXTRACT :
        case PCLZIP_CB_POST_EXTRACT :
        case PCLZIP_CB_PRE_ADD :
        case PCLZIP_CB_POST_ADD :
        /* for futur use
        case PCLZIP_CB_PRE_DELETE :
        case PCLZIP_CB_POST_DELETE :
        case PCLZIP_CB_PRE_LIST :
        case PCLZIP_CB_POST_LIST :
        */
          // ----- Check the number of parameters
          if (($i+1) >= $p_size) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Get the value
          $v_function_name = $p_options_list[$i+1];
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "call-back ".PclZipUtilOptionText($p_options_list[$i])." = '".$v_function_name."'");

          // ----- Check that the value is a valid existing function
          if (!function_exists($v_function_name)) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "Function '".$v_function_name."()' is not an existing function for option '".PclZipUtilOptionText($p_options_list[$i])."'");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Set the attribute
          $v_result_list[$p_options_list[$i]] = $v_function_name;
          $i++;
        break;

        default :
          // ----- Error log
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER,
		                       "Unknown parameter '"
							   .$p_options_list[$i]."'");

          // ----- Return
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
          return PclZip::errorCode();
      }

      // ----- Next options
      $i++;
    }

    // ----- Look for mandatory options
    if ($v_requested_options !== false) {
      for ($key=reset($v_requested_options); $key=key($v_requested_options); $key=next($v_requested_options)) {
        // ----- Look for mandatory option
        if ($v_requested_options[$key] == 'mandatory') {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Detect a mandatory option : ".PclZipUtilOptionText($key)."(".$key.")");
          // ----- Look if present
          if (!isset($v_result_list[$key])) {
            // ----- Error log
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Missing mandatory parameter ".PclZipUtilOptionText($key)."(".$key.")");

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }
        }
      }
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privFileDescrParseAtt(&$p_file_list, &$p_filedescr, $v_options, $v_requested_options=false)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privFileDescrParseAtt", "");
    $v_result=1;
    
    // ----- For each file in the list check the attributes
    foreach ($p_file_list as $v_key => $v_value) {
    
      // ----- Check if the option is supported
      if (!isset($v_requested_options[$v_key])) {
        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid file attribute '".$v_key."' for this file");

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }

      // ----- Look for attribute
      switch ($v_key) {
        case PCLZIP_ATT_FILE_NAME :
          if (!is_string($v_value)) {
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "Invalid type ".gettype($v_value).". String expected for attribute '".PclZipUtilOptionText($v_key)."'");
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          $p_filedescr['filename'] = PclZipUtilPathReduction($v_value);
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($v_key)." = '".$v_value."'");
          
          if ($p_filedescr['filename'] == '') {
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "Invalid empty filename for attribute '".PclZipUtilOptionText($v_key)."'");
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

        break;

        case PCLZIP_ATT_FILE_NEW_SHORT_NAME :
          if (!is_string($v_value)) {
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "Invalid type ".gettype($v_value).". String expected for attribute '".PclZipUtilOptionText($v_key)."'");
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          $p_filedescr['new_short_name'] = PclZipUtilPathReduction($v_value);
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($v_key)." = '".$v_value."'");

          if ($p_filedescr['new_short_name'] == '') {
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "Invalid empty short filename for attribute '".PclZipUtilOptionText($v_key)."'");
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }
        break;

        case PCLZIP_ATT_FILE_NEW_FULL_NAME :
          if (!is_string($v_value)) {
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "Invalid type ".gettype($v_value).". String expected for attribute '".PclZipUtilOptionText($v_key)."'");
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          $p_filedescr['new_full_name'] = PclZipUtilPathReduction($v_value);
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($v_key)." = '".$v_value."'");

          if ($p_filedescr['new_full_name'] == '') {
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "Invalid empty full filename for attribute '".PclZipUtilOptionText($v_key)."'");
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }
        break;

        // ----- Look for options that takes a string
        case PCLZIP_ATT_FILE_COMMENT :
          if (!is_string($v_value)) {
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "Invalid type ".gettype($v_value).". String expected for attribute '".PclZipUtilOptionText($v_key)."'");
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          $p_filedescr['comment'] = $v_value;
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($v_key)." = '".$v_value."'");
        break;

        case PCLZIP_ATT_FILE_MTIME :
          if (!is_integer($v_value)) {
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "Invalid type ".gettype($v_value).". Integer expected for attribute '".PclZipUtilOptionText($v_key)."'");
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          $p_filedescr['mtime'] = $v_value;
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($v_key)." = '".$v_value."'");
        break;

        case PCLZIP_ATT_FILE_CONTENT :
          $p_filedescr['content'] = $v_value;
          ////--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "".PclZipUtilOptionText($v_key)." = '".$v_value."'");
        break;

        default :
          // ----- Error log
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER,
		                           "Unknown parameter '".$v_key."'");

          // ----- Return
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
          return PclZip::errorCode();
      }

      // ----- Look for mandatory options
      if ($v_requested_options !== false) {
        for ($key=reset($v_requested_options); $key=key($v_requested_options); $key=next($v_requested_options)) {
          // ----- Look for mandatory option
          if ($v_requested_options[$key] == 'mandatory') {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Detect a mandatory option : ".PclZipUtilOptionText($key)."(".$key.")");
            // ----- Look if present
            if (!isset($p_file_list[$key])) {
              PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Missing mandatory parameter ".PclZipUtilOptionText($key)."(".$key.")");
              //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
              return PclZip::errorCode();
            }
          }
        }
      }
    
    // end foreach
    }
    
    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privFileDescrExpand(&$p_filedescr_list, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privFileDescrExpand", "");
    $v_result=1;
    
    // ----- Create a result list
    $v_result_list = array();
    
    // ----- Look each entry
    for ($i=0; $i<sizeof($p_filedescr_list); $i++) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Looking for file ".$i.".");
      
      // ----- Get filedescr
      $v_descr = $p_filedescr_list[$i];
      
      // ----- Reduce the filename
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Filedescr before reduction :'".$v_descr['filename']."'");
      $v_descr['filename'] = PclZipUtilTranslateWinPath($v_descr['filename']);
      $v_descr['filename'] = PclZipUtilPathReduction($v_descr['filename']);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Filedescr after reduction :'".$v_descr['filename']."'");
      
      // ----- Look for real file or folder
      if (file_exists($v_descr['filename'])) {
        if (@is_file($v_descr['filename'])) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "This is a file");
          $v_descr['type'] = 'file';
        }
        else if (@is_dir($v_descr['filename'])) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "This is a folder");
          $v_descr['type'] = 'folder';
        }
        else if (@is_link($v_descr['filename'])) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Unsupported file type : link");
          // skip
          continue;
        }
        else {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Unsupported file type : unknown type");
          // skip
          continue;
        }
      }
      
      // ----- Look for string added as file
      else if (isset($v_descr['content'])) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "This is a string added as a file");
        $v_descr['type'] = 'virtual_file';
      }
      
      // ----- Missing file
      else {
        // ----- Error log
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File '".$v_descr['filename']."' does not exists");
        PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "File '".$v_descr['filename']."' does not exists");

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }
      
      // ----- Calculate the stored filename
      $this->privCalculateStoredFilename($v_descr, $p_options);
      
      // ----- Add the descriptor in result list
      $v_result_list[sizeof($v_result_list)] = $v_descr;
      
      // ----- Look for folder
      if ($v_descr['type'] == 'folder') {
        // ----- List of items in folder
        $v_dirlist_descr = array();
        $v_dirlist_nb = 0;
        if ($v_folder_handler = @opendir($v_descr['filename'])) {
          while (($v_item_handler = @readdir($v_folder_handler)) !== false) {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Looking for '".$v_item_handler."' in the directory");

            // ----- Skip '.' and '..'
            if (($v_item_handler == '.') || ($v_item_handler == '..')) {
                continue;
            }
            
            // ----- Compose the full filename
            $v_dirlist_descr[$v_dirlist_nb]['filename'] = $v_descr['filename'].'/'.$v_item_handler;
            
            // ----- Look for different stored filename
            // Because the name of the folder was changed, the name of the
            // files/sub-folders also change
            if ($v_descr['stored_filename'] != $v_descr['filename']) {
              if ($v_descr['stored_filename'] != '') {
                $v_dirlist_descr[$v_dirlist_nb]['new_full_name'] = $v_descr['stored_filename'].'/'.$v_item_handler;
              }
              else {
                $v_dirlist_descr[$v_dirlist_nb]['new_full_name'] = $v_item_handler;
              }
            }
      
            $v_dirlist_nb++;
          }
          
          @closedir($v_folder_handler);
        }
        else {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Unable to open dir '".$v_descr['filename']."' in read mode. Skipped.");
          // TBC : unable to open folder in read mode
        }
        
        // ----- Expand each element of the list
        if ($v_dirlist_nb != 0) {
          // ----- Expand
          if (($v_result = $this->privFileDescrExpand($v_dirlist_descr, $p_options)) != 1) {
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
          }
          
          // ----- Concat the resulting list
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Merging result list (size '".sizeof($v_result_list)."') with dirlist (size '".sizeof($v_dirlist_descr)."')");
          $v_result_list = array_merge($v_result_list, $v_dirlist_descr);
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "merged result list is size '".sizeof($v_result_list)."'");
        }
        else {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Nothing in this folder to expand.");
        }
          
        // ----- Free local array
        unset($v_dirlist_descr);
      }
    }
    
    // ----- Get the result list
    $p_filedescr_list = $v_result_list;

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privCreate($p_filedescr_list, &$p_result_list, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privCreate", "list");
    $v_result=1;
    $v_list_detail = array();
    
    // ----- Magic quotes trick
    $this->privDisableMagicQuotes();

    // ----- Open the file in write mode
    if (($v_result = $this->privOpenFd('wb')) != 1)
    {
      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Add the list of files
    $v_result = $this->privAddList($p_filedescr_list, $p_result_list, $p_options);

    // ----- Close
    $this->privCloseFd();

    // ----- Magic quotes trick
    $this->privSwapBackMagicQuotes();

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privAdd($p_filedescr_list, &$p_result_list, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privAdd", "list");
    $v_result=1;
    $v_list_detail = array();

    // ----- Look if the archive exists or is empty
    if ((!is_file($this->zipname)) || (filesize($this->zipname) == 0))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Archive does not exist, or is empty, create it.");

      // ----- Do a create
      $v_result = $this->privCreate($p_filedescr_list, $p_result_list, $p_options);

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }
    // ----- Magic quotes trick
    $this->privDisableMagicQuotes();

    // ----- Open the zip file
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($v_result=$this->privOpenFd('rb')) != 1)
    {
      // ----- Magic quotes trick
      $this->privSwapBackMagicQuotes();

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Read the central directory informations
    $v_central_dir = array();
    if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)
    {
      $this->privCloseFd();
      $this->privSwapBackMagicQuotes();
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Go to beginning of File
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position in file : ".ftell($this->zip_fd)."'");
    @rewind($this->zip_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position in file : ".ftell($this->zip_fd)."'");

    // ----- Creates a temporay file
    $v_zip_temp_name = PCLZIP_TEMPORARY_DIR.uniqid('pclzip-').'.tmp';

    // ----- Open the temporary file in write mode
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($v_zip_temp_fd = @fopen($v_zip_temp_name, 'wb')) == 0)
    {
      $this->privCloseFd();
      $this->privSwapBackMagicQuotes();

      PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open temporary file \''.$v_zip_temp_name.'\' in binary write mode');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Copy the files from the archive to the temporary file
    // TBC : Here I should better append the file and go back to erase the central dir
    $v_size = $v_central_dir['offset'];
    while ($v_size != 0)
    {
      $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
      $v_buffer = fread($this->zip_fd, $v_read_size);
      @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
      $v_size -= $v_read_size;
    }

    // ----- Swap the file descriptor
    // Here is a trick : I swap the temporary fd with the zip fd, in order to use
    // the following methods on the temporary fil and not the real archive
    $v_swap = $this->zip_fd;
    $this->zip_fd = $v_zip_temp_fd;
    $v_zip_temp_fd = $v_swap;

    // ----- Add the files
    $v_header_list = array();
    if (($v_result = $this->privAddFileList($p_filedescr_list, $v_header_list, $p_options)) != 1)
    {
      fclose($v_zip_temp_fd);
      $this->privCloseFd();
      @unlink($v_zip_temp_name);
      $this->privSwapBackMagicQuotes();

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Store the offset of the central dir
    $v_offset = @ftell($this->zip_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "New offset of central dir : $v_offset");

    // ----- Copy the block of file headers from the old archive
    $v_size = $v_central_dir['size'];
    while ($v_size != 0)
    {
      $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
      $v_buffer = @fread($v_zip_temp_fd, $v_read_size);
      @fwrite($this->zip_fd, $v_buffer, $v_read_size);
      $v_size -= $v_read_size;
    }

    // ----- Create the Central Dir files header
    for ($i=0, $v_count=0; $i<sizeof($v_header_list); $i++)
    {
      // ----- Create the file header
      if ($v_header_list[$i]['status'] == 'ok') {
        if (($v_result = $this->privWriteCentralFileHeader($v_header_list[$i])) != 1) {
          fclose($v_zip_temp_fd);
          $this->privCloseFd();
          @unlink($v_zip_temp_name);
          $this->privSwapBackMagicQuotes();

          // ----- Return
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
          return $v_result;
        }
        $v_count++;
      }

      // ----- Transform the header to a 'usable' info
      $this->privConvertHeader2FileInfo($v_header_list[$i], $p_result_list[$i]);
    }

    // ----- Zip file comment
    $v_comment = $v_central_dir['comment'];
    if (isset($p_options[PCLZIP_OPT_COMMENT])) {
      $v_comment = $p_options[PCLZIP_OPT_COMMENT];
    }
    if (isset($p_options[PCLZIP_OPT_ADD_COMMENT])) {
      $v_comment = $v_comment.$p_options[PCLZIP_OPT_ADD_COMMENT];
    }
    if (isset($p_options[PCLZIP_OPT_PREPEND_COMMENT])) {
      $v_comment = $p_options[PCLZIP_OPT_PREPEND_COMMENT].$v_comment;
    }

    // ----- Calculate the size of the central header
    $v_size = @ftell($this->zip_fd)-$v_offset;

    // ----- Create the central dir footer
    if (($v_result = $this->privWriteCentralHeader($v_count+$v_central_dir['entries'], $v_size, $v_offset, $v_comment)) != 1)
    {
      // ----- Reset the file list
      unset($v_header_list);
      $this->privSwapBackMagicQuotes();

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Swap back the file descriptor
    $v_swap = $this->zip_fd;
    $this->zip_fd = $v_zip_temp_fd;
    $v_zip_temp_fd = $v_swap;

    // ----- Close
    $this->privCloseFd();

    // ----- Close the temporary file
    @fclose($v_zip_temp_fd);

    // ----- Magic quotes trick
    $this->privSwapBackMagicQuotes();

    // ----- Delete the zip file
    // TBC : I should test the result ...
    @unlink($this->zipname);

    // ----- Rename the temporary file
    // TBC : I should test the result ...
    //@rename($v_zip_temp_name, $this->zipname);
    PclZipUtilRename($v_zip_temp_name, $this->zipname);

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privOpenFd($p_mode)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privOpenFd", 'mode='.$p_mode);
    $v_result=1;

    // ----- Look if already open
    if ($this->zip_fd != 0)
    {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Zip file \''.$this->zipname.'\' already open');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Open the zip file
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Open file in '.$p_mode.' mode');
    if (($this->zip_fd = @fopen($this->zipname, $p_mode)) == 0)
    {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open archive \''.$this->zipname.'\' in '.$p_mode.' mode');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privCloseFd()
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privCloseFd", "");
    $v_result=1;

    if ($this->zip_fd != 0)
      @fclose($this->zip_fd);
    $this->zip_fd = 0;

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------
  
//  function privAddList($p_list, &$p_result_list, $p_add_dir, $p_remove_dir, $p_remove_all_dir, &$p_options)
  function privAddList($p_filedescr_list, &$p_result_list, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privAddList", "list");
    $v_result=1;

    // ----- Add the files
    $v_header_list = array();
    if (($v_result = $this->privAddFileList($p_filedescr_list, $v_header_list, $p_options)) != 1)
    {
      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Store the offset of the central dir
    $v_offset = @ftell($this->zip_fd);

    // ----- Create the Central Dir files header
    for ($i=0,$v_count=0; $i<sizeof($v_header_list); $i++)
    {
      // ----- Create the file header
      if ($v_header_list[$i]['status'] == 'ok') {
        if (($v_result = $this->privWriteCentralFileHeader($v_header_list[$i])) != 1) {
          // ----- Return
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
          return $v_result;
        }
        $v_count++;
      }

      // ----- Transform the header to a 'usable' info
      $this->privConvertHeader2FileInfo($v_header_list[$i], $p_result_list[$i]);
    }

    // ----- Zip file comment
    $v_comment = '';
    if (isset($p_options[PCLZIP_OPT_COMMENT])) {
      $v_comment = $p_options[PCLZIP_OPT_COMMENT];
    }

    // ----- Calculate the size of the central header
    $v_size = @ftell($this->zip_fd)-$v_offset;

    // ----- Create the central dir footer
    if (($v_result = $this->privWriteCentralHeader($v_count, $v_size, $v_offset, $v_comment)) != 1)
    {
      // ----- Reset the file list
      unset($v_header_list);

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privAddFileList($p_filedescr_list, &$p_result_list, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privAddFileList", "filedescr_list");
    $v_result=1;
    $v_header = array();

    // ----- Recuperate the current number of elt in list
    $v_nb = sizeof($p_result_list);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Before add, list have ".$v_nb." elements");

    // ----- Loop on the files
    for ($j=0; ($j<sizeof($p_filedescr_list)) && ($v_result==1); $j++) {
      // ----- Format the filename
      $p_filedescr_list[$j]['filename']
      = PclZipUtilTranslateWinPath($p_filedescr_list[$j]['filename'], false);
      
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Looking for file '".$p_filedescr_list[$j]['filename']."'");

      // ----- Skip empty file names
      // TBC : Can this be possible ? not checked in DescrParseAtt ?
      if ($p_filedescr_list[$j]['filename'] == "") {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Skip empty filename");
        continue;
      }

      // ----- Check the filename
      if (   ($p_filedescr_list[$j]['type'] != 'virtual_file')
          && (!file_exists($p_filedescr_list[$j]['filename']))) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File '".$p_filedescr_list[$j]['filename']."' does not exists");
        PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "File '".$p_filedescr_list[$j]['filename']."' does not exists");
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }

      // ----- Look if it is a file or a dir with no all path remove option
      // or a dir with all its path removed
//      if (   (is_file($p_filedescr_list[$j]['filename']))
//          || (   is_dir($p_filedescr_list[$j]['filename'])
      if (   ($p_filedescr_list[$j]['type'] == 'file')
          || ($p_filedescr_list[$j]['type'] == 'virtual_file')
          || (   ($p_filedescr_list[$j]['type'] == 'folder')
              && (   !isset($p_options[PCLZIP_OPT_REMOVE_ALL_PATH])
                  || !$p_options[PCLZIP_OPT_REMOVE_ALL_PATH]))
          ) {

        // ----- Add the file
        $v_result = $this->privAddFile($p_filedescr_list[$j], $v_header,
                                       $p_options);
        if ($v_result != 1) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
          return $v_result;
        }

        // ----- Store the file infos
        $p_result_list[$v_nb++] = $v_header;
      }
    }
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "After add, list have ".$v_nb." elements");

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privAddFile($p_filedescr, &$p_header, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privAddFile", "filename='".$p_filedescr['filename']."'");
    $v_result=1;
    
    // ----- Working variable
    $p_filename = $p_filedescr['filename'];

    // TBC : Already done in the fileAtt check ... ?
    if ($p_filename == "") {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid file list parameter (invalid or empty list)");

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Set the file properties
    clearstatcache();
    $p_header['version'] = 20;
    $p_header['version_extracted'] = 10;
    $p_header['flag'] = 0;
    $p_header['compression'] = 0;
    $p_header['crc'] = 0;
    $p_header['compressed_size'] = 0;
    $p_header['filename_len'] = strlen($p_filename);
    $p_header['extra_len'] = 0;
    $p_header['disk'] = 0;
    $p_header['internal'] = 0;
    $p_header['offset'] = 0;
    $p_header['filename'] = $p_filename;
// TBC : Removed    $p_header['stored_filename'] = $v_stored_filename;
    $p_header['stored_filename'] = $p_filedescr['stored_filename'];
    $p_header['extra'] = '';
    $p_header['status'] = 'ok';
    $p_header['index'] = -1;

    // ----- Look for regular file
    if ($p_filedescr['type']=='file') {
      $p_header['external'] = 0x00000000;
      $p_header['size'] = filesize($p_filename);
    }
    
    // ----- Look for regular folder
    else if ($p_filedescr['type']=='folder') {
      $p_header['external'] = 0x00000010;
      $p_header['mtime'] = filemtime($p_filename);
      $p_header['size'] = filesize($p_filename);
    }
    
    // ----- Look for virtual file
    else if ($p_filedescr['type'] == 'virtual_file') {
      $p_header['external'] = 0x00000000;
      $p_header['size'] = strlen($p_filedescr['content']);
    }
    
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Header external extension '".sprintf("0x%X",$p_header['external'])."'");

    // ----- Look for filetime
    if (isset($p_filedescr['mtime'])) {
      $p_header['mtime'] = $p_filedescr['mtime'];
    }
    else if ($p_filedescr['type'] == 'virtual_file') {
      $p_header['mtime'] = mktime();
    }
    else {
      $p_header['mtime'] = filemtime($p_filename);
    }

    // ------ Look for file comment
    if (isset($p_filedescr['comment'])) {
      $p_header['comment_len'] = strlen($p_filedescr['comment']);
      $p_header['comment'] = $p_filedescr['comment'];
    }
    else {
      $p_header['comment_len'] = 0;
      $p_header['comment'] = '';
    }

    // ----- Look for pre-add callback
    if (isset($p_options[PCLZIP_CB_PRE_ADD])) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "A pre-callback '".$p_options[PCLZIP_CB_PRE_ADD]."()') is defined for the extraction");

      // ----- Generate a local information
      $v_local_header = array();
      $this->privConvertHeader2FileInfo($p_header, $v_local_header);

      // ----- Call the callback
      // Here I do not use call_user_func() because I need to send a reference to the
      // header.
      eval('$v_result = '.$p_options[PCLZIP_CB_PRE_ADD].'(PCLZIP_CB_PRE_ADD, $v_local_header);');
      if ($v_result == 0) {
        // ----- Change the file status
        $p_header['status'] = "skipped";
        $v_result = 1;
      }

      // ----- Update the informations
      // Only some fields can be modified
      if ($p_header['stored_filename'] != $v_local_header['stored_filename']) {
        $p_header['stored_filename'] = PclZipUtilPathReduction($v_local_header['stored_filename']);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "New stored filename is '".$p_header['stored_filename']."'");
      }
    }

    // ----- Look for empty stored filename
    if ($p_header['stored_filename'] == "") {
      $p_header['status'] = "filtered";
    }
    
    // ----- Check the path length
    if (strlen($p_header['stored_filename']) > 0xFF) {
      $p_header['status'] = 'filename_too_long';
    }

    // ----- Look if no error, or file not skipped
    if ($p_header['status'] == 'ok') {

      // ----- Look for a file
//      if (is_file($p_filename))
      if (   ($p_filedescr['type'] == 'file')
          || ($p_filedescr['type'] == 'virtual_file')) {
          
        // ----- Get content from real file
        if ($p_filedescr['type'] == 'file') {        
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "'".$p_filename."' is a file");

          // ----- Open the source file
          if (($v_file = @fopen($p_filename, "rb")) == 0) {
            PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "Unable to open file '$p_filename' in binary read mode");
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
          }

          // ----- Read the file content
          $v_content = @fread($v_file, $p_header['size']);

          // ----- Close the file
          @fclose($v_file);
        }
        else if ($p_filedescr['type'] == 'virtual_file') {        
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Add by string");
          $v_content = $p_filedescr['content'];
        }

        // ----- Calculate the CRC
        $p_header['crc'] = @crc32($v_content);
        
        // ----- Look for no compression
        if ($p_options[PCLZIP_OPT_NO_COMPRESSION]) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File will not be compressed");
          // ----- Set header parameters
          $p_header['compressed_size'] = $p_header['size'];
          $p_header['compression'] = 0;
        }
        
        // ----- Look for normal compression
        else {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File will be compressed");
          // ----- Compress the content
          $v_content = @gzdeflate($v_content);

          // ----- Set header parameters
          $p_header['compressed_size'] = strlen($v_content);
          $p_header['compression'] = 8;
        }

        // ----- Call the header generation
        if (($v_result = $this->privWriteFileHeader($p_header)) != 1) {
          @fclose($v_file);
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
          return $v_result;
        }

        // ----- Write the compressed (or not) content
        @fwrite($this->zip_fd, $v_content, $p_header['compressed_size']);
      }

      // ----- Look for a directory
      else if ($p_filedescr['type'] == 'folder') {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "'".$p_filename."' is a folder");
        // ----- Look for directory last '/'
        if (@substr($p_header['stored_filename'], -1) != '/') {
          $p_header['stored_filename'] .= '/';
        }

        // ----- Set the file properties
        $p_header['size'] = 0;
        //$p_header['external'] = 0x41FF0010;   // Value for a folder : to be checked
        $p_header['external'] = 0x00000010;   // Value for a folder : to be checked

        // ----- Call the header generation
        if (($v_result = $this->privWriteFileHeader($p_header)) != 1)
        {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
          return $v_result;
        }
      }
    }

    // ----- Look for post-add callback
    if (isset($p_options[PCLZIP_CB_POST_ADD])) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "A post-callback '".$p_options[PCLZIP_CB_POST_ADD]."()') is defined for the extraction");

      // ----- Generate a local information
      $v_local_header = array();
      $this->privConvertHeader2FileInfo($p_header, $v_local_header);

      // ----- Call the callback
      // Here I do not use call_user_func() because I need to send a reference to the
      // header.
      eval('$v_result = '.$p_options[PCLZIP_CB_POST_ADD].'(PCLZIP_CB_POST_ADD, $v_local_header);');
      if ($v_result == 0) {
        // ----- Ignored
        $v_result = 1;
      }

      // ----- Update the informations
      // Nothing can be modified
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------
  
  function privCalculateStoredFilename(&$p_filedescr, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privCalculateStoredFilename", "filename='".$p_filedescr['filename']."'");
    $v_result=1;
    
    // ----- Working variables
    $p_filename = $p_filedescr['filename'];
    if (isset($p_options[PCLZIP_OPT_ADD_PATH])) {
      $p_add_dir = $p_options[PCLZIP_OPT_ADD_PATH];
    }
    else {
      $p_add_dir = '';
    }
    if (isset($p_options[PCLZIP_OPT_REMOVE_PATH])) {
      $p_remove_dir = $p_options[PCLZIP_OPT_REMOVE_PATH];
    }
    else {
      $p_remove_dir = '';
    }
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Remove path ='".$p_remove_dir."'");
    if (isset($p_options[PCLZIP_OPT_REMOVE_ALL_PATH])) {
      $p_remove_all_dir = $p_options[PCLZIP_OPT_REMOVE_ALL_PATH];
    }
    else {
      $p_remove_all_dir = 0;
    }

    // ----- Look for full name change
    if (isset($p_filedescr['new_full_name'])) {
      $v_stored_filename = $p_filedescr['new_full_name'];
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Changing full name of '".$p_filename."' for '".$v_stored_filename."'");
    }
    
    // ----- Look for path and/or short name change
    else {

      // ----- Look for short name change
      if (isset($p_filedescr['new_short_name'])) {
        $v_path_info = pathinfo($p_filename);
        $v_dir = '';
        if ($v_path_info['dirname'] != '') {
          $v_dir = $v_path_info['dirname'].'/';
        }
        $v_stored_filename = $v_dir.$p_filedescr['new_short_name'];
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Changing short name of '".$p_filename."' for '".$v_stored_filename."'");
      }
      else {
        // ----- Calculate the stored filename
        $v_stored_filename = $p_filename;
      }

      // ----- Look for all path to remove
      if ($p_remove_all_dir) {
        $v_stored_filename = basename($p_filename);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Remove all path selected change '".$p_filename."' for '".$v_stored_filename."'");
      }
      // ----- Look for partial path remove
      else if ($p_remove_dir != "") {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Partial path to remove");
        if (substr($p_remove_dir, -1) != '/')
          $p_remove_dir .= "/";

        if (   (substr($p_filename, 0, 2) == "./")
            || (substr($p_remove_dir, 0, 2) == "./")) {
            
          if (   (substr($p_filename, 0, 2) == "./")
              && (substr($p_remove_dir, 0, 2) != "./")) {
            $p_remove_dir = "./".$p_remove_dir;
          }
          if (   (substr($p_filename, 0, 2) != "./")
              && (substr($p_remove_dir, 0, 2) == "./")) {
            $p_remove_dir = substr($p_remove_dir, 2);
          }
        }

        $v_compare = PclZipUtilPathInclusion($p_remove_dir,
                                             $v_stored_filename);
        if ($v_compare > 0) {
          if ($v_compare == 2) {
            $v_stored_filename = "";
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Path to remove is the current folder");
          }
          else {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Remove path '$p_remove_dir' in file '$v_stored_filename'");
            $v_stored_filename = substr($v_stored_filename,
                                        strlen($p_remove_dir));
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Result is '$v_stored_filename'");
          }
        }
      }
      // ----- Look for path to add
      if ($p_add_dir != "") {
        if (substr($p_add_dir, -1) == "/")
          $v_stored_filename = $p_add_dir.$v_stored_filename;
        else
          $v_stored_filename = $p_add_dir."/".$v_stored_filename;
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Add path '$p_add_dir' in file '$p_filename' = '$v_stored_filename'");
      }
    }

    // ----- Filename (reduce the path of stored name)
    $v_stored_filename = PclZipUtilPathReduction($v_stored_filename);
    $p_filedescr['stored_filename'] = $v_stored_filename;
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Stored filename will be '".$p_filedescr['stored_filename']."', strlen ".strlen($p_filedescr['stored_filename']));
    
    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privWriteFileHeader(&$p_header)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privWriteFileHeader", 'file="'.$p_header['filename'].'", stored as "'.$p_header['stored_filename'].'"');
    $v_result=1;

    // ----- Store the offset position of the file
    $p_header['offset'] = ftell($this->zip_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, 'File offset of the header :'.$p_header['offset']);

    // ----- Transform UNIX mtime to DOS format mdate/mtime
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Date : \''.date("d/m/y H:i:s", $p_header['mtime']).'\'');
    $v_date = getdate($p_header['mtime']);
    $v_mtime = ($v_date['hours']<<11) + ($v_date['minutes']<<5) + $v_date['seconds']/2;
    $v_mdate = (($v_date['year']-1980)<<9) + ($v_date['mon']<<5) + $v_date['mday'];

    // ----- Packed data
    $v_binary_data = pack("VvvvvvVVVvv", 0x04034b50,
	                      $p_header['version_extracted'], $p_header['flag'],
                          $p_header['compression'], $v_mtime, $v_mdate,
                          $p_header['crc'], $p_header['compressed_size'],
						  $p_header['size'],
                          strlen($p_header['stored_filename']),
						  $p_header['extra_len']);

    // ----- Write the first 148 bytes of the header in the archive
    fputs($this->zip_fd, $v_binary_data, 30);

    // ----- Write the variable fields
    if (strlen($p_header['stored_filename']) != 0)
    {
      fputs($this->zip_fd, $p_header['stored_filename'], strlen($p_header['stored_filename']));
    }
    if ($p_header['extra_len'] != 0)
    {
      fputs($this->zip_fd, $p_header['extra'], $p_header['extra_len']);
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privWriteCentralFileHeader(&$p_header)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privWriteCentralFileHeader", 'file="'.$p_header['filename'].'", stored as "'.$p_header['stored_filename'].'"');
    $v_result=1;

    // TBC
    //for(reset($p_header); $key = key($p_header); next($p_header)) {
    //  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "header[$key] = ".$p_header[$key]);
    //}

    // ----- Transform UNIX mtime to DOS format mdate/mtime
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Date : \''.date("d/m/y H:i:s", $p_header['mtime']).'\'');
    $v_date = getdate($p_header['mtime']);
    $v_mtime = ($v_date['hours']<<11) + ($v_date['minutes']<<5) + $v_date['seconds']/2;
    $v_mdate = (($v_date['year']-1980)<<9) + ($v_date['mon']<<5) + $v_date['mday'];

    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Comment size : \''.$p_header['comment_len'].'\'');

    // ----- Packed data
    $v_binary_data = pack("VvvvvvvVVVvvvvvVV", 0x02014b50,
	                      $p_header['version'], $p_header['version_extracted'],
                          $p_header['flag'], $p_header['compression'],
						  $v_mtime, $v_mdate, $p_header['crc'],
                          $p_header['compressed_size'], $p_header['size'],
                          strlen($p_header['stored_filename']),
						  $p_header['extra_len'], $p_header['comment_len'],
                          $p_header['disk'], $p_header['internal'],
						  $p_header['external'], $p_header['offset']);

    // ----- Write the 42 bytes of the header in the zip file
    fputs($this->zip_fd, $v_binary_data, 46);

    // ----- Write the variable fields
    if (strlen($p_header['stored_filename']) != 0)
    {
      fputs($this->zip_fd, $p_header['stored_filename'], strlen($p_header['stored_filename']));
    }
    if ($p_header['extra_len'] != 0)
    {
      fputs($this->zip_fd, $p_header['extra'], $p_header['extra_len']);
    }
    if ($p_header['comment_len'] != 0)
    {
      fputs($this->zip_fd, $p_header['comment'], $p_header['comment_len']);
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privWriteCentralHeader($p_nb_entries, $p_size, $p_offset, $p_comment)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privWriteCentralHeader", 'nb_entries='.$p_nb_entries.', size='.$p_size.', offset='.$p_offset.', comment="'.$p_comment.'"');
    $v_result=1;

    // ----- Packed data
    $v_binary_data = pack("VvvvvVVv", 0x06054b50, 0, 0, $p_nb_entries,
	                      $p_nb_entries, $p_size,
						  $p_offset, strlen($p_comment));

    // ----- Write the 22 bytes of the header in the zip file
    fputs($this->zip_fd, $v_binary_data, 22);

    // ----- Write the variable fields
    if (strlen($p_comment) != 0)
    {
      fputs($this->zip_fd, $p_comment, strlen($p_comment));
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privList(&$p_list)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privList", "list");
    $v_result=1;

    // ----- Magic quotes trick
    $this->privDisableMagicQuotes();

    // ----- Open the zip file
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($this->zip_fd = @fopen($this->zipname, 'rb')) == 0)
    {
      // ----- Magic quotes trick
      $this->privSwapBackMagicQuotes();
      
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open archive \''.$this->zipname.'\' in binary read mode');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Read the central directory informations
    $v_central_dir = array();
    if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)
    {
      $this->privSwapBackMagicQuotes();
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Go to beginning of Central Dir
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Offset : ".$v_central_dir['offset']."'");
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Position in file : ".ftell($this->zip_fd)."'");
    @rewind($this->zip_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Position in file : ".ftell($this->zip_fd)."'");
    if (@fseek($this->zip_fd, $v_central_dir['offset']))
    {
      $this->privSwapBackMagicQuotes();

      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, 'Invalid archive size');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Position in file : ".ftell($this->zip_fd)."'");

    // ----- Read each entry
    for ($i=0; $i<$v_central_dir['entries']; $i++)
    {
      // ----- Read the file header
      if (($v_result = $this->privReadCentralFileHeader($v_header)) != 1)
      {
        $this->privSwapBackMagicQuotes();
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
        return $v_result;
      }
      $v_header['index'] = $i;

      // ----- Get the only interesting attributes
      $this->privConvertHeader2FileInfo($v_header, $p_list[$i]);
      unset($v_header);
    }

    // ----- Close the zip file
    $this->privCloseFd();

    // ----- Magic quotes trick
    $this->privSwapBackMagicQuotes();

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privConvertHeader2FileInfo($p_header, &$p_info)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privConvertHeader2FileInfo", "Filename='".$p_header['filename']."'");
    $v_result=1;

    // ----- Get the interesting attributes
    $p_info['filename'] = $p_header['filename'];
    $p_info['stored_filename'] = $p_header['stored_filename'];
    $p_info['size'] = $p_header['size'];
    $p_info['compressed_size'] = $p_header['compressed_size'];
    $p_info['mtime'] = $p_header['mtime'];
    $p_info['comment'] = $p_header['comment'];
    $p_info['folder'] = (($p_header['external']&0x00000010)==0x00000010);
    $p_info['index'] = $p_header['index'];
    $p_info['status'] = $p_header['status'];
    $p_info['crc'] = $p_header['crc'];

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privExtractByRule(&$p_file_list, $p_path, $p_remove_path, $p_remove_all_path, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privExtractByRule", "path='$p_path', remove_path='$p_remove_path', remove_all_path='".($p_remove_all_path?'true':'false')."'");
    $v_result=1;

    // ----- Magic quotes trick
    $this->privDisableMagicQuotes();

    // ----- Check the path
    if (   ($p_path == "")
	    || (   (substr($p_path, 0, 1) != "/")
		    && (substr($p_path, 0, 3) != "../")
			&& (substr($p_path,1,2)!=":/")))
      $p_path = "./".$p_path;

    // ----- Reduce the path last (and duplicated) '/'
    if (($p_path != "./") && ($p_path != "/"))
    {
      // ----- Look for the path end '/'
      while (substr($p_path, -1) == "/")
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Destination path [$p_path] ends by '/'");
        $p_path = substr($p_path, 0, strlen($p_path)-1);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Modified to [$p_path]");
      }
    }

    // ----- Look for path to remove format (should end by /)
    if (($p_remove_path != "") && (substr($p_remove_path, -1) != '/'))
    {
      $p_remove_path .= '/';
    }
    $p_remove_path_size = strlen($p_remove_path);

    // ----- Open the zip file
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($v_result = $this->privOpenFd('rb')) != 1)
    {
      $this->privSwapBackMagicQuotes();
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Read the central directory informations
    $v_central_dir = array();
    if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)
    {
      // ----- Close the zip file
      $this->privCloseFd();
      $this->privSwapBackMagicQuotes();

      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Start at beginning of Central Dir
    $v_pos_entry = $v_central_dir['offset'];

    // ----- Read each entry
    $j_start = 0;
    for ($i=0, $v_nb_extracted=0; $i<$v_central_dir['entries']; $i++)
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Read next file header entry : '$i'");

      // ----- Read next Central dir entry
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Position before rewind : ".ftell($this->zip_fd)."'");
      @rewind($this->zip_fd);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Position after rewind : ".ftell($this->zip_fd)."'");
      if (@fseek($this->zip_fd, $v_pos_entry))
      {
        // ----- Close the zip file
        $this->privCloseFd();
        $this->privSwapBackMagicQuotes();

        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, 'Invalid archive size');

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Position after fseek : ".ftell($this->zip_fd)."'");

      // ----- Read the file header
      $v_header = array();
      if (($v_result = $this->privReadCentralFileHeader($v_header)) != 1)
      {
        // ----- Close the zip file
        $this->privCloseFd();
        $this->privSwapBackMagicQuotes();

        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
        return $v_result;
      }

      // ----- Store the index
      $v_header['index'] = $i;

      // ----- Store the file position
      $v_pos_entry = ftell($this->zip_fd);

      // ----- Look for the specific extract rules
      $v_extract = false;

      // ----- Look for extract by name rule
      if (   (isset($p_options[PCLZIP_OPT_BY_NAME]))
          && ($p_options[PCLZIP_OPT_BY_NAME] != 0)) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract with rule 'ByName'");

          // ----- Look if the filename is in the list
          for ($j=0; ($j<sizeof($p_options[PCLZIP_OPT_BY_NAME])) && (!$v_extract); $j++) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Compare with file '".$p_options[PCLZIP_OPT_BY_NAME][$j]."'");

              // ----- Look for a directory
              if (substr($p_options[PCLZIP_OPT_BY_NAME][$j], -1) == "/") {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The searched item is a directory");

                  // ----- Look if the directory is in the filename path
                  if (   (strlen($v_header['stored_filename']) > strlen($p_options[PCLZIP_OPT_BY_NAME][$j]))
                      && (substr($v_header['stored_filename'], 0, strlen($p_options[PCLZIP_OPT_BY_NAME][$j])) == $p_options[PCLZIP_OPT_BY_NAME][$j])) {
                      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The directory is in the file path");
                      $v_extract = true;
                  }
              }
              // ----- Look for a filename
              elseif ($v_header['stored_filename'] == $p_options[PCLZIP_OPT_BY_NAME][$j]) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The file is the right one.");
                  $v_extract = true;
              }
          }
      }

      // ----- Look for extract by ereg rule
      else if (   (isset($p_options[PCLZIP_OPT_BY_EREG]))
               && ($p_options[PCLZIP_OPT_BY_EREG] != "")) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract by ereg '".$p_options[PCLZIP_OPT_BY_EREG]."'");

          if (ereg($p_options[PCLZIP_OPT_BY_EREG], $v_header['stored_filename'])) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Filename match the regular expression");
              $v_extract = true;
          }
      }

      // ----- Look for extract by preg rule
      else if (   (isset($p_options[PCLZIP_OPT_BY_PREG]))
               && ($p_options[PCLZIP_OPT_BY_PREG] != "")) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract with rule 'ByEreg'");

          if (preg_match($p_options[PCLZIP_OPT_BY_PREG], $v_header['stored_filename'])) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Filename match the regular expression");
              $v_extract = true;
          }
      }

      // ----- Look for extract by index rule
      else if (   (isset($p_options[PCLZIP_OPT_BY_INDEX]))
               && ($p_options[PCLZIP_OPT_BY_INDEX] != 0)) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract with rule 'ByIndex'");
          
          // ----- Look if the index is in the list
          for ($j=$j_start; ($j<sizeof($p_options[PCLZIP_OPT_BY_INDEX])) && (!$v_extract); $j++) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Look if index '$i' is in [".$p_options[PCLZIP_OPT_BY_INDEX][$j]['start'].",".$p_options[PCLZIP_OPT_BY_INDEX][$j]['end']."]");

              if (($i>=$p_options[PCLZIP_OPT_BY_INDEX][$j]['start']) && ($i<=$p_options[PCLZIP_OPT_BY_INDEX][$j]['end'])) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Found as part of an index range");
                  $v_extract = true;
              }
              if ($i>=$p_options[PCLZIP_OPT_BY_INDEX][$j]['end']) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Do not look this index range for next loop");
                  $j_start = $j+1;
              }

              if ($p_options[PCLZIP_OPT_BY_INDEX][$j]['start']>$i) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Index range is greater than index, stop loop");
                  break;
              }
          }
      }

      // ----- Look for no rule, which means extract all the archive
      else {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract with no rule (extract all)");
          $v_extract = true;
      }

	  // ----- Check compression method
	  if (   ($v_extract)
	      && (   ($v_header['compression'] != 8)
		      && ($v_header['compression'] != 0))) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Unsupported compression method (".$v_header['compression'].")");
          $v_header['status'] = 'unsupported_compression';

          // ----- Look for PCLZIP_OPT_STOP_ON_ERROR
          if (   (isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]))
		      && ($p_options[PCLZIP_OPT_STOP_ON_ERROR]===true)) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "PCLZIP_OPT_STOP_ON_ERROR is selected, extraction will be stopped");

              $this->privSwapBackMagicQuotes();
              
              PclZip::privErrorLog(PCLZIP_ERR_UNSUPPORTED_COMPRESSION,
			                       "Filename '".$v_header['stored_filename']."' is "
				  	    	  	   ."compressed by an unsupported compression "
				  	    	  	   ."method (".$v_header['compression'].") ");

              //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
              return PclZip::errorCode();
		  }
	  }
	  
	  // ----- Check encrypted files
	  if (($v_extract) && (($v_header['flag'] & 1) == 1)) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Unsupported file encryption");
          $v_header['status'] = 'unsupported_encryption';

          // ----- Look for PCLZIP_OPT_STOP_ON_ERROR
          if (   (isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]))
		      && ($p_options[PCLZIP_OPT_STOP_ON_ERROR]===true)) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "PCLZIP_OPT_STOP_ON_ERROR is selected, extraction will be stopped");

              $this->privSwapBackMagicQuotes();

              PclZip::privErrorLog(PCLZIP_ERR_UNSUPPORTED_ENCRYPTION,
			                       "Unsupported encryption for "
				  	    	  	   ." filename '".$v_header['stored_filename']
								   ."'");

              //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
              return PclZip::errorCode();
		  }
    }

      // ----- Look for real extraction
      if (($v_extract) && ($v_header['status'] != 'ok')) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "No need for extract");
          $v_result = $this->privConvertHeader2FileInfo($v_header,
		                                        $p_file_list[$v_nb_extracted++]);
          if ($v_result != 1) {
              $this->privCloseFd();
              $this->privSwapBackMagicQuotes();
              //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
              return $v_result;
          }

          $v_extract = false;
      }
      
      // ----- Look for real extraction
      if ($v_extract)
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting file '".$v_header['filename']."', index '$i'");

        // ----- Go to the file position
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position before rewind : ".ftell($this->zip_fd)."'");
        @rewind($this->zip_fd);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position after rewind : ".ftell($this->zip_fd)."'");
        if (@fseek($this->zip_fd, $v_header['offset']))
        {
          // ----- Close the zip file
          $this->privCloseFd();

          $this->privSwapBackMagicQuotes();

          // ----- Error log
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, 'Invalid archive size');

          // ----- Return
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
          return PclZip::errorCode();
        }
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position after fseek : ".ftell($this->zip_fd)."'");

        // ----- Look for extraction as string
        if ($p_options[PCLZIP_OPT_EXTRACT_AS_STRING]) {

          // ----- Extracting the file
          $v_result1 = $this->privExtractFileAsString($v_header, $v_string);
          if ($v_result1 < 1) {
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result1);
            return $v_result1;
          }

          // ----- Get the only interesting attributes
          if (($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted])) != 1)
          {
            // ----- Close the zip file
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();

            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
          }

          // ----- Set the file content
          $p_file_list[$v_nb_extracted]['content'] = $v_string;

          // ----- Next extracted file
          $v_nb_extracted++;
          
          // ----- Look for user callback abort
          if ($v_result1 == 2) {
          	break;
          }
        }
        // ----- Look for extraction in standard output
        elseif (   (isset($p_options[PCLZIP_OPT_EXTRACT_IN_OUTPUT]))
		        && ($p_options[PCLZIP_OPT_EXTRACT_IN_OUTPUT])) {
          // ----- Extracting the file in standard output
          $v_result1 = $this->privExtractFileInOutput($v_header, $p_options);
          if ($v_result1 < 1) {
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result1);
            return $v_result1;
          }

          // ----- Get the only interesting attributes
          if (($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted++])) != 1) {
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
          }

          // ----- Look for user callback abort
          if ($v_result1 == 2) {
          	break;
          }
        }
        // ----- Look for normal extraction
        else {
          // ----- Extracting the file
          $v_result1 = $this->privExtractFile($v_header,
		                                      $p_path, $p_remove_path,
											  $p_remove_all_path,
											  $p_options);
          if ($v_result1 < 1) {
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result1);
            return $v_result1;
          }

          // ----- Get the only interesting attributes
          if (($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted++])) != 1)
          {
            // ----- Close the zip file
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();

            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
          }

          // ----- Look for user callback abort
          if ($v_result1 == 2) {
          	break;
          }
        }
      }
    }

    // ----- Close the zip file
    $this->privCloseFd();
    $this->privSwapBackMagicQuotes();

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privExtractFile(&$p_entry, $p_path, $p_remove_path, $p_remove_all_path, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::privExtractFile', "path='$p_path', remove_path='$p_remove_path', remove_all_path='".($p_remove_all_path?'true':'false')."'");
    $v_result=1;

    // ----- Read the file header
    if (($v_result = $this->privReadFileHeader($v_header)) != 1)
    {
      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Found file '".$v_header['filename']."', size '".$v_header['size']."'");

    // ----- Check that the file header is coherent with $p_entry info
    if ($this->privCheckFileHeaders($v_header, $p_entry) != 1) {
        // TBC
    }

    // ----- Look for all path to remove
    if ($p_remove_all_path == true) {
        // ----- Look for folder entry that not need to be extracted
        if (($p_entry['external']&0x00000010)==0x00000010) {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "The entry is a folder : need to be filtered");

            $p_entry['status'] = "filtered";

            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
        }

        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "All path is removed");
        // ----- Get the basename of the path
        $p_entry['filename'] = basename($p_entry['filename']);
    }

    // ----- Look for path to remove
    else if ($p_remove_path != "")
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Look for some path to remove");
      if (PclZipUtilPathInclusion($p_remove_path, $p_entry['filename']) == 2)
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "The folder is the same as the removed path '".$p_entry['filename']."'");

        // ----- Change the file status
        $p_entry['status'] = "filtered";

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
        return $v_result;
      }

      $p_remove_path_size = strlen($p_remove_path);
      if (substr($p_entry['filename'], 0, $p_remove_path_size) == $p_remove_path)
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Found path '$p_remove_path' to remove in file '".$p_entry['filename']."'");

        // ----- Remove the path
        $p_entry['filename'] = substr($p_entry['filename'], $p_remove_path_size);

        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Resulting file is '".$p_entry['filename']."'");
      }
    }

    // ----- Add the path
    if ($p_path != '') {
      $p_entry['filename'] = $p_path."/".$p_entry['filename'];
    }
    
    // ----- Check a base_dir_restriction
    if (isset($p_options[PCLZIP_OPT_EXTRACT_DIR_RESTRICTION])) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Check the extract directory restriction");
      $v_inclusion
      = PclZipUtilPathInclusion($p_options[PCLZIP_OPT_EXTRACT_DIR_RESTRICTION],
                                $p_entry['filename']); 
      if ($v_inclusion == 0) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "PCLZIP_OPT_EXTRACT_DIR_RESTRICTION is selected, file is outside restriction");

        PclZip::privErrorLog(PCLZIP_ERR_DIRECTORY_RESTRICTION,
			                     "Filename '".$p_entry['filename']."' is "
								 ."outside PCLZIP_OPT_EXTRACT_DIR_RESTRICTION");

        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }
    }

    // ----- Look for pre-extract callback
    if (isset($p_options[PCLZIP_CB_PRE_EXTRACT])) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "A pre-callback '".$p_options[PCLZIP_CB_PRE_EXTRACT]."()') is defined for the extraction");

      // ----- Generate a local information
      $v_local_header = array();
      $this->privConvertHeader2FileInfo($p_entry, $v_local_header);

      // ----- Call the callback
      // Here I do not use call_user_func() because I need to send a reference to the
      // header.
      eval('$v_result = '.$p_options[PCLZIP_CB_PRE_EXTRACT].'(PCLZIP_CB_PRE_EXTRACT, $v_local_header);');
      if ($v_result == 0) {
        // ----- Change the file status
        $p_entry['status'] = "skipped";
        $v_result = 1;
      }
      
      // ----- Look for abort result
      if ($v_result == 2) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "User callback abort the extraction");
        // ----- This status is internal and will be changed in 'skipped'
        $p_entry['status'] = "aborted";
      	$v_result = PCLZIP_ERR_USER_ABORTED;
      }

      // ----- Update the informations
      // Only some fields can be modified
      $p_entry['filename'] = $v_local_header['filename'];
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "New filename is '".$p_entry['filename']."'");
    }

    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting file (with path) '".$p_entry['filename']."', size '$v_header[size]'");

    // ----- Look if extraction should be done
    if ($p_entry['status'] == 'ok') {

    // ----- Look for specific actions while the file exist
    if (file_exists($p_entry['filename']))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File '".$p_entry['filename']."' already exists");

      // ----- Look if file is a directory
      if (is_dir($p_entry['filename']))
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Existing file '".$p_entry['filename']."' is a directory");

        // ----- Change the file status
        $p_entry['status'] = "already_a_directory";
        
        // ----- Look for PCLZIP_OPT_STOP_ON_ERROR
        // For historical reason first PclZip implementation does not stop
        // when this kind of error occurs.
        if (   (isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]))
		    && ($p_options[PCLZIP_OPT_STOP_ON_ERROR]===true)) {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "PCLZIP_OPT_STOP_ON_ERROR is selected, extraction will be stopped");

            PclZip::privErrorLog(PCLZIP_ERR_ALREADY_A_DIRECTORY,
			                     "Filename '".$p_entry['filename']."' is "
								 ."already used by an existing directory");

            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
		}
      }
      // ----- Look if file is write protected
      else if (!is_writeable($p_entry['filename']))
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Existing file '".$p_entry['filename']."' is write protected");

        // ----- Change the file status
        $p_entry['status'] = "write_protected";

        // ----- Look for PCLZIP_OPT_STOP_ON_ERROR
        // For historical reason first PclZip implementation does not stop
        // when this kind of error occurs.
        if (   (isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]))
		    && ($p_options[PCLZIP_OPT_STOP_ON_ERROR]===true)) {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "PCLZIP_OPT_STOP_ON_ERROR is selected, extraction will be stopped");

            PclZip::privErrorLog(PCLZIP_ERR_WRITE_OPEN_FAIL,
			                     "Filename '".$p_entry['filename']."' exists "
								 ."and is write protected");

            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
            return PclZip::errorCode();
		}
      }

      // ----- Look if the extracted file is older
      else if (filemtime($p_entry['filename']) > $p_entry['mtime'])
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Existing file '".$p_entry['filename']."' is newer (".date("l dS of F Y h:i:s A", filemtime($p_entry['filename'])).") than the extracted file (".date("l dS of F Y h:i:s A", $p_entry['mtime']).")");
        // ----- Change the file status
        if (   (isset($p_options[PCLZIP_OPT_REPLACE_NEWER]))
		    && ($p_options[PCLZIP_OPT_REPLACE_NEWER]===true)) {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "PCLZIP_OPT_REPLACE_NEWER is selected, file will be replaced");
		}
		else {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File will not be replaced");
            $p_entry['status'] = "newer_exist";

            // ----- Look for PCLZIP_OPT_STOP_ON_ERROR
            // For historical reason first PclZip implementation does not stop
            // when this kind of error occurs.
            if (   (isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]))
		        && ($p_options[PCLZIP_OPT_STOP_ON_ERROR]===true)) {
                //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "PCLZIP_OPT_STOP_ON_ERROR is selected, extraction will be stopped");

                PclZip::privErrorLog(PCLZIP_ERR_WRITE_OPEN_FAIL,
			             "Newer version of '".$p_entry['filename']."' exists "
					    ."and option PCLZIP_OPT_REPLACE_NEWER is not selected");

                //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
                return PclZip::errorCode();
		    }
		}
      }
      else {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Existing file '".$p_entry['filename']."' is older than the extrated one - will be replaced by the extracted one (".date("l dS of F Y h:i:s A", filemtime($p_entry['filename'])).") than the extracted file (".date("l dS of F Y h:i:s A", $p_entry['mtime']).")");
      }
    }

    // ----- Check the directory availability and create it if necessary
    else {
      if ((($p_entry['external']&0x00000010)==0x00000010) || (substr($p_entry['filename'], -1) == '/'))
        $v_dir_to_check = $p_entry['filename'];
      else if (!strstr($p_entry['filename'], "/"))
        $v_dir_to_check = "";
      else
        $v_dir_to_check = dirname($p_entry['filename']);

      if (($v_result = $this->privDirCheck($v_dir_to_check, (($p_entry['external']&0x00000010)==0x00000010))) != 1) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Unable to create path for '".$p_entry['filename']."'");

        // ----- Change the file status
        $p_entry['status'] = "path_creation_fail";

        // ----- Return
        ////--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
        //return $v_result;
        $v_result = 1;
      }
    }
    }

    // ----- Look if extraction should be done
    if ($p_entry['status'] == 'ok') {

      // ----- Do the extraction (if not a folder)
      if (!(($p_entry['external']&0x00000010)==0x00000010))
      {
        // ----- Look for not compressed file
        if ($p_entry['compression'] == 0) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting an un-compressed file");

		  // ----- Opening destination file
          if (($v_dest_file = @fopen($p_entry['filename'], 'wb')) == 0)
          {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Error while opening '".$p_entry['filename']."' in write binary mode");

            // ----- Change the file status
            $p_entry['status'] = "write_error";

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
          }

          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Read '".$p_entry['size']."' bytes");

          // ----- Read the file by PCLZIP_READ_BLOCK_SIZE octets blocks
          $v_size = $p_entry['compressed_size'];
          while ($v_size != 0)
          {
            $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Read $v_read_size bytes");
            $v_buffer = @fread($this->zip_fd, $v_read_size);
            /* Try to speed up the code
            $v_binary_data = pack('a'.$v_read_size, $v_buffer);
            @fwrite($v_dest_file, $v_binary_data, $v_read_size);
            */
            @fwrite($v_dest_file, $v_buffer, $v_read_size);            
            $v_size -= $v_read_size;
          }

          // ----- Closing the destination file
          fclose($v_dest_file);

          // ----- Change the file mtime
          touch($p_entry['filename'], $p_entry['mtime']);
          

        }
        else {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting a compressed file (Compression method ".$p_entry['compression'].")");
          // ----- TBC
          // Need to be finished
          if (($p_entry['flag'] & 1) == 1) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File is encrypted");
          }
          else {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Read '".$p_entry['compressed_size']."' compressed bytes");
              // ----- Read the compressed file in a buffer (one shot)
              $v_buffer = @fread($this->zip_fd, $p_entry['compressed_size']);
          }
          
          // ----- Decompress the file
          $v_file_content = @gzinflate($v_buffer);
          unset($v_buffer);
          if ($v_file_content === FALSE) {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Unable to inflate compressed file");

            // ----- Change the file status
            // TBC
            $p_entry['status'] = "error";
            
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
          }
          
          // ----- Opening destination file
          if (($v_dest_file = @fopen($p_entry['filename'], 'wb')) == 0) {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Error while opening '".$p_entry['filename']."' in write binary mode");

            // ----- Change the file status
            $p_entry['status'] = "write_error";

            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
          }

          // ----- Write the uncompressed data
          @fwrite($v_dest_file, $v_file_content, $p_entry['size']);
          unset($v_file_content);

          // ----- Closing the destination file
          @fclose($v_dest_file);

          // ----- Change the file mtime
          @touch($p_entry['filename'], $p_entry['mtime']);
        }

        // ----- Look for chmod option
        if (isset($p_options[PCLZIP_OPT_SET_CHMOD])) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "chmod option activated '".$p_options[PCLZIP_OPT_SET_CHMOD]."'");

          // ----- Change the mode of the file
          @chmod($p_entry['filename'], $p_options[PCLZIP_OPT_SET_CHMOD]);
        }

        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extraction done");
      }
    }

	// ----- Change abort status
	if ($p_entry['status'] == "aborted") {
      $p_entry['status'] = "skipped";
	}
	
    // ----- Look for post-extract callback
    elseif (isset($p_options[PCLZIP_CB_POST_EXTRACT])) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "A post-callback '".$p_options[PCLZIP_CB_POST_EXTRACT]."()') is defined for the extraction");

      // ----- Generate a local information
      $v_local_header = array();
      $this->privConvertHeader2FileInfo($p_entry, $v_local_header);

      // ----- Call the callback
      // Here I do not use call_user_func() because I need to send a reference to the
      // header.
      eval('$v_result = '.$p_options[PCLZIP_CB_POST_EXTRACT].'(PCLZIP_CB_POST_EXTRACT, $v_local_header);');

      // ----- Look for abort result
      if ($v_result == 2) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "User callback abort the extraction");
      	$v_result = PCLZIP_ERR_USER_ABORTED;
      }
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privExtractFileInOutput(&$p_entry, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::privExtractFileInOutput', "");
    $v_result=1;

    // ----- Read the file header
    if (($v_result = $this->privReadFileHeader($v_header)) != 1) {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Found file '".$v_header['filename']."', size '".$v_header['size']."'");

    // ----- Check that the file header is coherent with $p_entry info
    if ($this->privCheckFileHeaders($v_header, $p_entry) != 1) {
        // TBC
    }

    // ----- Look for pre-extract callback
    if (isset($p_options[PCLZIP_CB_PRE_EXTRACT])) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "A pre-callback '".$p_options[PCLZIP_CB_PRE_EXTRACT]."()') is defined for the extraction");

      // ----- Generate a local information
      $v_local_header = array();
      $this->privConvertHeader2FileInfo($p_entry, $v_local_header);

      // ----- Call the callback
      // Here I do not use call_user_func() because I need to send a reference to the
      // header.
      eval('$v_result = '.$p_options[PCLZIP_CB_PRE_EXTRACT].'(PCLZIP_CB_PRE_EXTRACT, $v_local_header);');
      if ($v_result == 0) {
        // ----- Change the file status
        $p_entry['status'] = "skipped";
        $v_result = 1;
      }

      // ----- Look for abort result
      if ($v_result == 2) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "User callback abort the extraction");
        // ----- This status is internal and will be changed in 'skipped'
        $p_entry['status'] = "aborted";
      	$v_result = PCLZIP_ERR_USER_ABORTED;
      }

      // ----- Update the informations
      // Only some fields can be modified
      $p_entry['filename'] = $v_local_header['filename'];
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "New filename is '".$p_entry['filename']."'");
    }

    // ----- Trace
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting file (with path) '".$p_entry['filename']."', size '$v_header[size]'");

    // ----- Look if extraction should be done
    if ($p_entry['status'] == 'ok') {

      // ----- Do the extraction (if not a folder)
      if (!(($p_entry['external']&0x00000010)==0x00000010)) {
        // ----- Look for not compressed file
        if ($p_entry['compressed_size'] == $p_entry['size']) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting an un-compressed file");
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Reading '".$p_entry['size']."' bytes");

          // ----- Read the file in a buffer (one shot)
          $v_buffer = @fread($this->zip_fd, $p_entry['compressed_size']);

          // ----- Send the file to the output
          echo $v_buffer;
          unset($v_buffer);
        }
        else {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting a compressed file");
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Reading '".$p_entry['size']."' bytes");

          // ----- Read the compressed file in a buffer (one shot)
          $v_buffer = @fread($this->zip_fd, $p_entry['compressed_size']);
          
          // ----- Decompress the file
          $v_file_content = gzinflate($v_buffer);
          unset($v_buffer);

          // ----- Send the file to the output
          echo $v_file_content;
          unset($v_file_content);
        }
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extraction done");
      }
    }

	// ----- Change abort status
	if ($p_entry['status'] == "aborted") {
      $p_entry['status'] = "skipped";
	}

    // ----- Look for post-extract callback
    elseif (isset($p_options[PCLZIP_CB_POST_EXTRACT])) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "A post-callback '".$p_options[PCLZIP_CB_POST_EXTRACT]."()') is defined for the extraction");

      // ----- Generate a local information
      $v_local_header = array();
      $this->privConvertHeader2FileInfo($p_entry, $v_local_header);

      // ----- Call the callback
      // Here I do not use call_user_func() because I need to send a reference to the
      // header.
      eval('$v_result = '.$p_options[PCLZIP_CB_POST_EXTRACT].'(PCLZIP_CB_POST_EXTRACT, $v_local_header);');

      // ----- Look for abort result
      if ($v_result == 2) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "User callback abort the extraction");
      	$v_result = PCLZIP_ERR_USER_ABORTED;
      }
    }

    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privExtractFileAsString(&$p_entry, &$p_string)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::privExtractFileAsString', "p_entry['filename']='".$p_entry['filename']."'");
    $v_result=1;

    // ----- Read the file header
    $v_header = array();
    if (($v_result = $this->privReadFileHeader($v_header)) != 1)
    {
      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Found file '".$v_header['filename']."', size '".$v_header['size']."'");

    // ----- Check that the file header is coherent with $p_entry info
    if ($this->privCheckFileHeaders($v_header, $p_entry) != 1) {
        // TBC
    }

    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting file in string (with path) '".$p_entry['filename']."', size '$v_header[size]'");

    // ----- Do the extraction (if not a folder)
    if (!(($p_entry['external']&0x00000010)==0x00000010))
    {
      // ----- Look for not compressed file
//      if ($p_entry['compressed_size'] == $p_entry['size'])
      if ($p_entry['compression'] == 0) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting an un-compressed file");
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Reading '".$p_entry['size']."' bytes");

        // ----- Reading the file
        $p_string = @fread($this->zip_fd, $p_entry['compressed_size']);
      }
      else {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extracting a compressed file (compression method '".$p_entry['compression']."')");

        // ----- Reading the file
        $v_data = @fread($this->zip_fd, $p_entry['compressed_size']);
        
        // ----- Decompress the file
        if (($p_string = @gzinflate($v_data)) === FALSE) {
            // TBC
        }
      }

      // ----- Trace
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Extraction done");
    }
    else {
        // TBC : error : can not extract a folder in a string
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privReadFileHeader(&$p_header)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privReadFileHeader", "");
    $v_result=1;

    // ----- Read the 4 bytes signature
    $v_binary_data = @fread($this->zip_fd, 4);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Binary data is : '".sprintf("%08x", $v_binary_data)."'");
    $v_data = unpack('Vid', $v_binary_data);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Binary signature is : '".sprintf("0x%08x", $v_data['id'])."'");

    // ----- Check signature
    if ($v_data['id'] != 0x04034b50)
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Invalid File header");

      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Invalid archive structure');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Read the first 42 bytes of the header
    $v_binary_data = fread($this->zip_fd, 26);

    // ----- Look for invalid block size
    if (strlen($v_binary_data) != 26)
    {
      $p_header['filename'] = "";
      $p_header['status'] = "invalid_header";
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Invalid block size : ".strlen($v_binary_data));

      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Invalid block size : ".strlen($v_binary_data));

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Extract the values
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Header : '".$v_binary_data."'");
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Header (Hex) : '".bin2hex($v_binary_data)."'");
    $v_data = unpack('vversion/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len', $v_binary_data);

    // ----- Get filename
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "File name length : ".$v_data['filename_len']);
    $p_header['filename'] = fread($this->zip_fd, $v_data['filename_len']);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Filename : \''.$p_header['filename'].'\'');

    // ----- Get extra_fields
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extra field length : ".$v_data['extra_len']);
    if ($v_data['extra_len'] != 0) {
      $p_header['extra'] = fread($this->zip_fd, $v_data['extra_len']);
    }
    else {
      $p_header['extra'] = '';
    }
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Extra field : \''.bin2hex($p_header['extra']).'\'');

    // ----- Extract properties
    $p_header['version_extracted'] = $v_data['version'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Version need to extract : ('.$p_header['version_extracted'].') \''.($p_header['version_extracted']/10).'.'.($p_header['version_extracted']%10).'\'');
    $p_header['compression'] = $v_data['compression'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Compression method : \''.$p_header['compression'].'\'');
    $p_header['size'] = $v_data['size'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Size : \''.$p_header['size'].'\'');
    $p_header['compressed_size'] = $v_data['compressed_size'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Compressed Size : \''.$p_header['compressed_size'].'\'');
    $p_header['crc'] = $v_data['crc'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'CRC : \''.sprintf("0x%X", $p_header['crc']).'\'');
    $p_header['flag'] = $v_data['flag'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Flag : \''.$p_header['flag'].'\'');
    $p_header['filename_len'] = $v_data['filename_len'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Filename_len : \''.$p_header['filename_len'].'\'');

    // ----- Recuperate date in UNIX format
    $p_header['mdate'] = $v_data['mdate'];
    $p_header['mtime'] = $v_data['mtime'];
    if ($p_header['mdate'] && $p_header['mtime'])
    {
      // ----- Extract time
      $v_hour = ($p_header['mtime'] & 0xF800) >> 11;
      $v_minute = ($p_header['mtime'] & 0x07E0) >> 5;
      $v_seconde = ($p_header['mtime'] & 0x001F)*2;

      // ----- Extract date
      $v_year = (($p_header['mdate'] & 0xFE00) >> 9) + 1980;
      $v_month = ($p_header['mdate'] & 0x01E0) >> 5;
      $v_day = $p_header['mdate'] & 0x001F;

      // ----- Get UNIX date format
      $p_header['mtime'] = mktime($v_hour, $v_minute, $v_seconde, $v_month, $v_day, $v_year);

      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Date : \''.date("d/m/y H:i:s", $p_header['mtime']).'\'');
    }
    else
    {
      $p_header['mtime'] = time();
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Date is actual : \''.date("d/m/y H:i:s", $p_header['mtime']).'\'');
    }

    // ----- Set the stored filename
    $p_header['stored_filename'] = $p_header['filename'];

    // ----- Set the status field
    $p_header['status'] = "ok";

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privReadCentralFileHeader(&$p_header)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privReadCentralFileHeader", "");
    $v_result=1;

    // ----- Read the 4 bytes signature
    $v_binary_data = @fread($this->zip_fd, 4);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Binary data is : '".sprintf("%08x", $v_binary_data)."'");
    $v_data = unpack('Vid', $v_binary_data);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Binary signature is : '".sprintf("0x%08x", $v_data['id'])."'");

    // ----- Check signature
    if ($v_data['id'] != 0x02014b50)
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Invalid Central Dir File signature");

      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Invalid archive structure');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Read the first 42 bytes of the header
    $v_binary_data = fread($this->zip_fd, 42);

    // ----- Look for invalid block size
    if (strlen($v_binary_data) != 42)
    {
      $p_header['filename'] = "";
      $p_header['status'] = "invalid_header";
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Invalid block size : ".strlen($v_binary_data));

      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Invalid block size : ".strlen($v_binary_data));

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Extract the values
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Header : '".$v_binary_data."'");
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Header (Hex) : '".bin2hex($v_binary_data)."'");
    $p_header = unpack('vversion/vversion_extracted/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len/vcomment_len/vdisk/vinternal/Vexternal/Voffset', $v_binary_data);

    // ----- Get filename
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "File name length : ".$p_header['filename_len']);
    if ($p_header['filename_len'] != 0)
      $p_header['filename'] = fread($this->zip_fd, $p_header['filename_len']);
    else
      $p_header['filename'] = '';
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Filename : \''.$p_header['filename'].'\'');

    // ----- Get extra
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Extra length : ".$p_header['extra_len']);
    if ($p_header['extra_len'] != 0)
      $p_header['extra'] = fread($this->zip_fd, $p_header['extra_len']);
    else
      $p_header['extra'] = '';
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Extra : \''.$p_header['extra'].'\'');

    // ----- Get comment
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Comment length : ".$p_header['comment_len']);
    if ($p_header['comment_len'] != 0)
      $p_header['comment'] = fread($this->zip_fd, $p_header['comment_len']);
    else
      $p_header['comment'] = '';
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Comment : \''.$p_header['comment'].'\'');

    if (1)
    {
      // ----- Extract time
      $v_hour = ($p_header['mtime'] & 0xF800) >> 11;
      $v_minute = ($p_header['mtime'] & 0x07E0) >> 5;
      $v_seconde = ($p_header['mtime'] & 0x001F)*2;

      // ----- Extract date
      $v_year = (($p_header['mdate'] & 0xFE00) >> 9) + 1980;
      $v_month = ($p_header['mdate'] & 0x01E0) >> 5;
      $v_day = $p_header['mdate'] & 0x001F;

      // ----- Get UNIX date format
      $p_header['mtime'] = @mktime($v_hour, $v_minute, $v_seconde, $v_month, $v_day, $v_year);

      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Date : \''.date("d/m/y H:i:s", $p_header['mtime']).'\'');
    }
    else
    {
      $p_header['mtime'] = time();
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Date is actual : \''.date("d/m/y H:i:s", $p_header['mtime']).'\'');
    }

    // ----- Set the stored filename
    $p_header['stored_filename'] = $p_header['filename'];

    // ----- Set default status to ok
    $p_header['status'] = 'ok';

    // ----- Look if it is a directory
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Internal (Hex) : '".sprintf("Ox%04X", $p_header['internal'])."'");
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "External (Hex) : '".sprintf("Ox%04X", $p_header['external'])."' (".(($p_header['external']&0x00000010)==0x00000010?'is a folder':'is a file').')');
    if (substr($p_header['filename'], -1) == '/') {
      //$p_header['external'] = 0x41FF0010;
      $p_header['external'] = 0x00000010;
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Force folder external : \''.sprintf("Ox%04X", $p_header['external']).'\'');
    }

    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Header of filename : \''.$p_header['filename'].'\'');

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privCheckFileHeaders(&$p_local_header, &$p_central_header)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privCheckFileHeaders", "");
    $v_result=1;

	// ----- Check the static values
	// TBC
	if ($p_local_header['filename'] != $p_central_header['filename']) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Bad check "filename" : TBC To Be Completed');
	}
	if ($p_local_header['version_extracted'] != $p_central_header['version_extracted']) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Bad check "version_extracted" : TBC To Be Completed');
	}
	if ($p_local_header['flag'] != $p_central_header['flag']) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Bad check "flag" : TBC To Be Completed');
	}
	if ($p_local_header['compression'] != $p_central_header['compression']) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Bad check "compression" : TBC To Be Completed');
	}
	if ($p_local_header['mtime'] != $p_central_header['mtime']) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Bad check "mtime" : TBC To Be Completed');
	}
	if ($p_local_header['filename_len'] != $p_central_header['filename_len']) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Bad check "filename_len" : TBC To Be Completed');
	}

	// ----- Look for flag bit 3
	if (($p_local_header['flag'] & 8) == 8) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Purpose bit flag bit 3 set !');
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'File size, compression size and crc found in central header');
        $p_local_header['size'] = $p_central_header['size'];
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Size : \''.$p_local_header['size'].'\'');
        $p_local_header['compressed_size'] = $p_central_header['compressed_size'];
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Compressed Size : \''.$p_local_header['compressed_size'].'\'');
        $p_local_header['crc'] = $p_central_header['crc'];
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'CRC : \''.sprintf("0x%X", $p_local_header['crc']).'\'');
	}

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privReadEndCentralDir(&$p_central_dir)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privReadEndCentralDir", "");
    $v_result=1;

    // ----- Go to the end of the zip file
    $v_size = filesize($this->zipname);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Size of the file :$v_size");
    @fseek($this->zip_fd, $v_size);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Position at end of zip file : \''.ftell($this->zip_fd).'\'');
    if (@ftell($this->zip_fd) != $v_size)
    {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to go to the end of the archive \''.$this->zipname.'\'');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- First try : look if this is an archive with no commentaries (most of the time)
    // in this case the end of central dir is at 22 bytes of the file end
    $v_found = 0;
    if ($v_size > 26) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Look for central dir with no comment');
      @fseek($this->zip_fd, $v_size-22);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Position after min central position : \''.ftell($this->zip_fd).'\'');
      if (($v_pos = @ftell($this->zip_fd)) != ($v_size-22))
      {
        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to seek back to the middle of the archive \''.$this->zipname.'\'');

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }

      // ----- Read for bytes
      $v_binary_data = @fread($this->zip_fd, 4);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Binary data is : '".sprintf("%08x", $v_binary_data)."'");
      $v_data = @unpack('Vid', $v_binary_data);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Binary signature is : '".sprintf("0x%08x", $v_data['id'])."'");

      // ----- Check signature
      if ($v_data['id'] == 0x06054b50) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Found central dir at the default position.");
        $v_found = 1;
      }

      $v_pos = ftell($this->zip_fd);
    }

    // ----- Go back to the maximum possible size of the Central Dir End Record
    if (!$v_found) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Start extended search of end central dir');
      $v_maximum_size = 65557; // 0xFFFF + 22;
      if ($v_maximum_size > $v_size)
        $v_maximum_size = $v_size;
      @fseek($this->zip_fd, $v_size-$v_maximum_size);
      if (@ftell($this->zip_fd) != ($v_size-$v_maximum_size))
      {
        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to seek back to the middle of the archive \''.$this->zipname.'\'');

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Position after max central position : \''.ftell($this->zip_fd).'\'');

      // ----- Read byte per byte in order to find the signature
      $v_pos = ftell($this->zip_fd);
      $v_bytes = 0x00000000;
      while ($v_pos < $v_size)
      {
        // ----- Read a byte
        $v_byte = @fread($this->zip_fd, 1);

        // -----  Add the byte
        $v_bytes = ($v_bytes << 8) | Ord($v_byte);

        // ----- Compare the bytes
        if ($v_bytes == 0x504b0506)
        {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, 'Found End Central Dir signature at position : \''.ftell($this->zip_fd).'\'');
          $v_pos++;
          break;
        }

        $v_pos++;
      }

      // ----- Look if not found end of central dir
      if ($v_pos == $v_size)
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Unable to find End of Central Dir Record signature");

        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Unable to find End of Central Dir Record signature");

        // ----- Return
        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
        return PclZip::errorCode();
      }
    }

    // ----- Read the first 18 bytes of the header
    $v_binary_data = fread($this->zip_fd, 18);

    // ----- Look for invalid block size
    if (strlen($v_binary_data) != 18)
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "Invalid End of Central Dir Record size : ".strlen($v_binary_data));

      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Invalid End of Central Dir Record size : ".strlen($v_binary_data));

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Extract the values
    ////--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Central Dir Record : '".$v_binary_data."'");
    ////--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Central Dir Record (Hex) : '".bin2hex($v_binary_data)."'");
    $v_data = unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size', $v_binary_data);

    // ----- Check the global size
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Comment length : ".$v_data['comment_size']);
    if (($v_pos + $v_data['comment_size'] + 18) != $v_size) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "The central dir is not at the end of the archive. Some trailing bytes exists after the archive.");

	  if (0) {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT,
	                       'The central dir is not at the end of the archive.'
						   .' Some trailing bytes exists after the archive.');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
	  }
    }

    // ----- Get comment
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Comment size : \''.$v_data['comment_size'].'\'');
    if ($v_data['comment_size'] != 0) {
      $p_central_dir['comment'] = fread($this->zip_fd, $v_data['comment_size']);
    }
    else
      $p_central_dir['comment'] = '';
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Comment : \''.$p_central_dir['comment'].'\'');

    $p_central_dir['entries'] = $v_data['entries'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Nb of entries : \''.$p_central_dir['entries'].'\'');
    $p_central_dir['disk_entries'] = $v_data['disk_entries'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Nb of entries for this disk : \''.$p_central_dir['disk_entries'].'\'');
    $p_central_dir['offset'] = $v_data['offset'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Offset of Central Dir : \''.$p_central_dir['offset'].'\'');
    $p_central_dir['size'] = $v_data['size'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Size of Central Dir : \''.$p_central_dir['size'].'\'');
    $p_central_dir['disk'] = $v_data['disk'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Disk number : \''.$p_central_dir['disk'].'\'');
    $p_central_dir['disk_start'] = $v_data['disk_start'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, 'Start disk number : \''.$p_central_dir['disk_start'].'\'');

    // TBC
    //for(reset($p_central_dir); $key = key($p_central_dir); next($p_central_dir)) {
    //  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "central_dir[$key] = ".$p_central_dir[$key]);
    //}

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privDeleteByRule(&$p_result_list, &$p_options)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privDeleteByRule", "");
    $v_result=1;
    $v_list_detail = array();

    // ----- Open the zip file
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($v_result=$this->privOpenFd('rb')) != 1)
    {
      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Read the central directory informations
    $v_central_dir = array();
    if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)
    {
      $this->privCloseFd();
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Go to beginning of File
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position in file : ".ftell($this->zip_fd)."'");
    @rewind($this->zip_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position in file : ".ftell($this->zip_fd)."'");

    // ----- Scan all the files
    // ----- Start at beginning of Central Dir
    $v_pos_entry = $v_central_dir['offset'];
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position before rewind : ".ftell($this->zip_fd)."'");
    @rewind($this->zip_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position after rewind : ".ftell($this->zip_fd)."'");
    if (@fseek($this->zip_fd, $v_pos_entry))
    {
      // ----- Close the zip file
      $this->privCloseFd();

      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, 'Invalid archive size');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position after fseek : ".ftell($this->zip_fd)."'");

    // ----- Read each entry
    $v_header_list = array();
    $j_start = 0;
    for ($i=0, $v_nb_extracted=0; $i<$v_central_dir['entries']; $i++)
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Read next file header entry (index '$i')");

      // ----- Read the file header
      $v_header_list[$v_nb_extracted] = array();
      if (($v_result = $this->privReadCentralFileHeader($v_header_list[$v_nb_extracted])) != 1)
      {
        // ----- Close the zip file
        $this->privCloseFd();

        //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
        return $v_result;
      }

      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Filename (index '$i') : '".$v_header_list[$v_nb_extracted]['stored_filename']."'");

      // ----- Store the index
      $v_header_list[$v_nb_extracted]['index'] = $i;

      // ----- Look for the specific extract rules
      $v_found = false;

      // ----- Look for extract by name rule
      if (   (isset($p_options[PCLZIP_OPT_BY_NAME]))
          && ($p_options[PCLZIP_OPT_BY_NAME] != 0)) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract with rule 'ByName'");

          // ----- Look if the filename is in the list
          for ($j=0; ($j<sizeof($p_options[PCLZIP_OPT_BY_NAME])) && (!$v_found); $j++) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Compare with file '".$p_options[PCLZIP_OPT_BY_NAME][$j]."'");

              // ----- Look for a directory
              if (substr($p_options[PCLZIP_OPT_BY_NAME][$j], -1) == "/") {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The searched item is a directory");

                  // ----- Look if the directory is in the filename path
                  if (   (strlen($v_header_list[$v_nb_extracted]['stored_filename']) > strlen($p_options[PCLZIP_OPT_BY_NAME][$j]))
                      && (substr($v_header_list[$v_nb_extracted]['stored_filename'], 0, strlen($p_options[PCLZIP_OPT_BY_NAME][$j])) == $p_options[PCLZIP_OPT_BY_NAME][$j])) {
                      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The directory is in the file path");
                      $v_found = true;
                  }
                  elseif (   (($v_header_list[$v_nb_extracted]['external']&0x00000010)==0x00000010) /* Indicates a folder */
                          && ($v_header_list[$v_nb_extracted]['stored_filename'].'/' == $p_options[PCLZIP_OPT_BY_NAME][$j])) {
                      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The entry is the searched directory");
                      $v_found = true;
                  }
              }
              // ----- Look for a filename
              elseif ($v_header_list[$v_nb_extracted]['stored_filename'] == $p_options[PCLZIP_OPT_BY_NAME][$j]) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "The file is the right one.");
                  $v_found = true;
              }
          }
      }

      // ----- Look for extract by ereg rule
      else if (   (isset($p_options[PCLZIP_OPT_BY_EREG]))
               && ($p_options[PCLZIP_OPT_BY_EREG] != "")) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract by ereg '".$p_options[PCLZIP_OPT_BY_EREG]."'");

          if (ereg($p_options[PCLZIP_OPT_BY_EREG], $v_header_list[$v_nb_extracted]['stored_filename'])) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Filename match the regular expression");
              $v_found = true;
          }
      }

      // ----- Look for extract by preg rule
      else if (   (isset($p_options[PCLZIP_OPT_BY_PREG]))
               && ($p_options[PCLZIP_OPT_BY_PREG] != "")) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract with rule 'ByEreg'");

          if (preg_match($p_options[PCLZIP_OPT_BY_PREG], $v_header_list[$v_nb_extracted]['stored_filename'])) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Filename match the regular expression");
              $v_found = true;
          }
      }

      // ----- Look for extract by index rule
      else if (   (isset($p_options[PCLZIP_OPT_BY_INDEX]))
               && ($p_options[PCLZIP_OPT_BY_INDEX] != 0)) {
          //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Extract with rule 'ByIndex'");

          // ----- Look if the index is in the list
          for ($j=$j_start; ($j<sizeof($p_options[PCLZIP_OPT_BY_INDEX])) && (!$v_found); $j++) {
              //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Look if index '$i' is in [".$p_options[PCLZIP_OPT_BY_INDEX][$j]['start'].",".$p_options[PCLZIP_OPT_BY_INDEX][$j]['end']."]");

              if (($i>=$p_options[PCLZIP_OPT_BY_INDEX][$j]['start']) && ($i<=$p_options[PCLZIP_OPT_BY_INDEX][$j]['end'])) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Found as part of an index range");
                  $v_found = true;
              }
              if ($i>=$p_options[PCLZIP_OPT_BY_INDEX][$j]['end']) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Do not look this index range for next loop");
                  $j_start = $j+1;
              }

              if ($p_options[PCLZIP_OPT_BY_INDEX][$j]['start']>$i) {
                  //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Index range is greater than index, stop loop");
                  break;
              }
          }
      }
      else {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "No argument mean remove all file");
      	$v_found = true;
      }

      // ----- Look for deletion
      if ($v_found)
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File '".$v_header_list[$v_nb_extracted]['stored_filename']."', index '$i' need to be deleted");
        unset($v_header_list[$v_nb_extracted]);
      }
      else
      {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 2, "File '".$v_header_list[$v_nb_extracted]['stored_filename']."', index '$i' will not be deleted");
        $v_nb_extracted++;
      }
    }

    // ----- Look if something need to be deleted
    if ($v_nb_extracted > 0) {

        // ----- Creates a temporay file
        $v_zip_temp_name = PCLZIP_TEMPORARY_DIR.uniqid('pclzip-').'.tmp';

        // ----- Creates a temporary zip archive
        $v_temp_zip = new PclZip($v_zip_temp_name);

        // ----- Open the temporary zip file in write mode
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary write mode");
        if (($v_result = $v_temp_zip->privOpenFd('wb')) != 1) {
            $this->privCloseFd();

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
        }

        // ----- Look which file need to be kept
        for ($i=0; $i<sizeof($v_header_list); $i++) {
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Keep entry index '$i' : '".$v_header_list[$i]['filename']."'");

            // ----- Calculate the position of the header
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Offset='". $v_header_list[$i]['offset']."'");
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position before rewind : ".ftell($this->zip_fd)."'");
            @rewind($this->zip_fd);
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position after rewind : ".ftell($this->zip_fd)."'");
            if (@fseek($this->zip_fd,  $v_header_list[$i]['offset'])) {
                // ----- Close the zip file
                $this->privCloseFd();
                $v_temp_zip->privCloseFd();
                @unlink($v_zip_temp_name);

                // ----- Error log
                PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, 'Invalid archive size');

                // ----- Return
                //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
                return PclZip::errorCode();
            }
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position after fseek : ".ftell($this->zip_fd)."'");

            // ----- Read the file header
            $v_local_header = array();
            if (($v_result = $this->privReadFileHeader($v_local_header)) != 1) {
                // ----- Close the zip file
                $this->privCloseFd();
                $v_temp_zip->privCloseFd();
                @unlink($v_zip_temp_name);

                // ----- Return
                //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
                return $v_result;
            }
            
            // ----- Check that local file header is same as central file header
            if ($this->privCheckFileHeaders($v_local_header,
			                                $v_header_list[$i]) != 1) {
                // TBC
            }
            unset($v_local_header);

            // ----- Write the file header
            if (($v_result = $v_temp_zip->privWriteFileHeader($v_header_list[$i])) != 1) {
                // ----- Close the zip file
                $this->privCloseFd();
                $v_temp_zip->privCloseFd();
                @unlink($v_zip_temp_name);

                // ----- Return
                //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
                return $v_result;
            }
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Offset for this file is '".$v_header_list[$i]['offset']."'");

            // ----- Read/write the data block
            if (($v_result = PclZipUtilCopyBlock($this->zip_fd, $v_temp_zip->zip_fd, $v_header_list[$i]['compressed_size'])) != 1) {
                // ----- Close the zip file
                $this->privCloseFd();
                $v_temp_zip->privCloseFd();
                @unlink($v_zip_temp_name);

                // ----- Return
                //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
                return $v_result;
            }
        }

        // ----- Store the offset of the central dir
        $v_offset = @ftell($v_temp_zip->zip_fd);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "New offset of central dir : $v_offset");

        // ----- Re-Create the Central Dir files header
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Creates the new central directory");
        for ($i=0; $i<sizeof($v_header_list); $i++) {
            // ----- Create the file header
            //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Offset of file : ".$v_header_list[$i]['offset']);
            if (($v_result = $v_temp_zip->privWriteCentralFileHeader($v_header_list[$i])) != 1) {
                $v_temp_zip->privCloseFd();
                $this->privCloseFd();
                @unlink($v_zip_temp_name);

                // ----- Return
                //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
                return $v_result;
            }

            // ----- Transform the header to a 'usable' info
            $v_temp_zip->privConvertHeader2FileInfo($v_header_list[$i], $p_result_list[$i]);
        }

        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Creates the central directory footer");

        // ----- Zip file comment
        $v_comment = '';
        if (isset($p_options[PCLZIP_OPT_COMMENT])) {
          $v_comment = $p_options[PCLZIP_OPT_COMMENT];
        }

        // ----- Calculate the size of the central header
        $v_size = @ftell($v_temp_zip->zip_fd)-$v_offset;

        // ----- Create the central dir footer
        if (($v_result = $v_temp_zip->privWriteCentralHeader(sizeof($v_header_list), $v_size, $v_offset, $v_comment)) != 1) {
            // ----- Reset the file list
            unset($v_header_list);
            $v_temp_zip->privCloseFd();
            $this->privCloseFd();
            @unlink($v_zip_temp_name);

            // ----- Return
            //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
            return $v_result;
        }

        // ----- Close
        $v_temp_zip->privCloseFd();
        $this->privCloseFd();

        // ----- Delete the zip file
        // TBC : I should test the result ...
        @unlink($this->zipname);

        // ----- Rename the temporary file
        // TBC : I should test the result ...
        //@rename($v_zip_temp_name, $this->zipname);
        PclZipUtilRename($v_zip_temp_name, $this->zipname);
    
        // ----- Destroy the temporary archive
        unset($v_temp_zip);
    }
    
    // ----- Remove every files : reset the file
    else if ($v_central_dir['entries'] != 0) {
        $this->privCloseFd();

        if (($v_result = $this->privOpenFd('wb')) != 1) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
          return $v_result;
        }

        if (($v_result = $this->privWriteCentralHeader(0, 0, 0, '')) != 1) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
          return $v_result;
        }

        $this->privCloseFd();
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privDirCheck($p_dir, $p_is_dir=false)
  {
    $v_result = 1;

    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privDirCheck", "entry='$p_dir', is_dir='".($p_is_dir?"true":"false")."'");

    // ----- Remove the final '/'
    if (($p_is_dir) && (substr($p_dir, -1)=='/'))
    {
      $p_dir = substr($p_dir, 0, strlen($p_dir)-1);
    }
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Looking for entry '$p_dir'");

    // ----- Check the directory availability
    if ((is_dir($p_dir)) || ($p_dir == ""))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, "'$p_dir' is a directory");
      return 1;
    }

    // ----- Extract parent directory
    $p_parent_dir = dirname($p_dir);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Parent directory is '$p_parent_dir'");

    // ----- Just a check
    if ($p_parent_dir != $p_dir)
    {
      // ----- Look for parent directory
      if ($p_parent_dir != "")
      {
        if (($v_result = $this->privDirCheck($p_parent_dir)) != 1)
        {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
          return $v_result;
        }
      }
    }

    // ----- Create the directory
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Create directory '$p_dir'");
    if (!@mkdir($p_dir, 0777))
    {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_DIR_CREATE_FAIL, "Unable to create directory '$p_dir'");

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result, "Directory '$p_dir' created");
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privMerge(&$p_archive_to_add)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privMerge", "archive='".$p_archive_to_add->zipname."'");
    $v_result=1;

    // ----- Look if the archive_to_add exists
    if (!is_file($p_archive_to_add->zipname))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Archive to add does not exist. End of merge.");

      // ----- Nothing to merge, so merge is a success
      $v_result = 1;

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Look if the archive exists
    if (!is_file($this->zipname))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Archive does not exist, duplicate the archive_to_add.");

      // ----- Do a duplicate
      $v_result = $this->privDuplicate($p_archive_to_add->zipname);

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Open the zip file
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($v_result=$this->privOpenFd('rb')) != 1)
    {
      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Read the central directory informations
    $v_central_dir = array();
    if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)
    {
      $this->privCloseFd();
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Go to beginning of File
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position in zip : ".ftell($this->zip_fd)."'");
    @rewind($this->zip_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position in zip : ".ftell($this->zip_fd)."'");

    // ----- Open the archive_to_add file
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open archive_to_add in binary read mode");
    if (($v_result=$p_archive_to_add->privOpenFd('rb')) != 1)
    {
      $this->privCloseFd();

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Read the central directory informations
    $v_central_dir_to_add = array();
    if (($v_result = $p_archive_to_add->privReadEndCentralDir($v_central_dir_to_add)) != 1)
    {
      $this->privCloseFd();
      $p_archive_to_add->privCloseFd();

      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Go to beginning of File
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position in archive_to_add : ".ftell($p_archive_to_add->zip_fd)."'");
    @rewind($p_archive_to_add->zip_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Position in archive_to_add : ".ftell($p_archive_to_add->zip_fd)."'");

    // ----- Creates a temporay file
    $v_zip_temp_name = PCLZIP_TEMPORARY_DIR.uniqid('pclzip-').'.tmp';

    // ----- Open the temporary file in write mode
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($v_zip_temp_fd = @fopen($v_zip_temp_name, 'wb')) == 0)
    {
      $this->privCloseFd();
      $p_archive_to_add->privCloseFd();

      PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open temporary file \''.$v_zip_temp_name.'\' in binary write mode');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Copy the files from the archive to the temporary file
    // TBC : Here I should better append the file and go back to erase the central dir
    $v_size = $v_central_dir['offset'];
    while ($v_size != 0)
    {
      $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
      $v_buffer = fread($this->zip_fd, $v_read_size);
      @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
      $v_size -= $v_read_size;
    }

    // ----- Copy the files from the archive_to_add into the temporary file
    $v_size = $v_central_dir_to_add['offset'];
    while ($v_size != 0)
    {
      $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
      $v_buffer = fread($p_archive_to_add->zip_fd, $v_read_size);
      @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
      $v_size -= $v_read_size;
    }

    // ----- Store the offset of the central dir
    $v_offset = @ftell($v_zip_temp_fd);
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "New offset of central dir : $v_offset");

    // ----- Copy the block of file headers from the old archive
    $v_size = $v_central_dir['size'];
    while ($v_size != 0)
    {
      $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
      $v_buffer = @fread($this->zip_fd, $v_read_size);
      @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
      $v_size -= $v_read_size;
    }

    // ----- Copy the block of file headers from the archive_to_add
    $v_size = $v_central_dir_to_add['size'];
    while ($v_size != 0)
    {
      $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
      $v_buffer = @fread($p_archive_to_add->zip_fd, $v_read_size);
      @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
      $v_size -= $v_read_size;
    }

    // ----- Merge the file comments
    $v_comment = $v_central_dir['comment'].' '.$v_central_dir_to_add['comment'];

    // ----- Calculate the size of the (new) central header
    $v_size = @ftell($v_zip_temp_fd)-$v_offset;

    // ----- Swap the file descriptor
    // Here is a trick : I swap the temporary fd with the zip fd, in order to use
    // the following methods on the temporary fil and not the real archive fd
    $v_swap = $this->zip_fd;
    $this->zip_fd = $v_zip_temp_fd;
    $v_zip_temp_fd = $v_swap;

    // ----- Create the central dir footer
    if (($v_result = $this->privWriteCentralHeader($v_central_dir['entries']+$v_central_dir_to_add['entries'], $v_size, $v_offset, $v_comment)) != 1)
    {
      $this->privCloseFd();
      $p_archive_to_add->privCloseFd();
      @fclose($v_zip_temp_fd);
      $this->zip_fd = null;

      // ----- Reset the file list
      unset($v_header_list);

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Swap back the file descriptor
    $v_swap = $this->zip_fd;
    $this->zip_fd = $v_zip_temp_fd;
    $v_zip_temp_fd = $v_swap;

    // ----- Close
    $this->privCloseFd();
    $p_archive_to_add->privCloseFd();

    // ----- Close the temporary file
    @fclose($v_zip_temp_fd);

    // ----- Delete the zip file
    // TBC : I should test the result ...
    @unlink($this->zipname);

    // ----- Rename the temporary file
    // TBC : I should test the result ...
    //@rename($v_zip_temp_name, $this->zipname);
    PclZipUtilRename($v_zip_temp_name, $this->zipname);

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privDuplicate($p_archive_filename)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZip::privDuplicate", "archive_filename='$p_archive_filename'");
    $v_result=1;

    // ----- Look if the $p_archive_filename exists
    if (!is_file($p_archive_filename))
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Archive to duplicate does not exist. End of duplicate.");

      // ----- Nothing to duplicate, so duplicate is a success.
      $v_result = 1;

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Open the zip file
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($v_result=$this->privOpenFd('wb')) != 1)
    {
      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Open the temporary file in write mode
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Open file in binary read mode");
    if (($v_zip_temp_fd = @fopen($p_archive_filename, 'rb')) == 0)
    {
      $this->privCloseFd();

      PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open archive file \''.$p_archive_filename.'\' in binary write mode');

      // ----- Return
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, PclZip::errorCode(), PclZip::errorInfo());
      return PclZip::errorCode();
    }

    // ----- Copy the files from the archive to the temporary file
    // TBC : Here I should better append the file and go back to erase the central dir
    $v_size = filesize($p_archive_filename);
    while ($v_size != 0)
    {
      $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Read $v_read_size bytes");
      $v_buffer = fread($v_zip_temp_fd, $v_read_size);
      @fwrite($this->zip_fd, $v_buffer, $v_read_size);
      $v_size -= $v_read_size;
    }

    // ----- Close
    $this->privCloseFd();

    // ----- Close the temporary file
    @fclose($v_zip_temp_fd);

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privErrorLog($p_error_code=0, $p_error_string='')
  {
    if (PCLZIP_ERROR_EXTERNAL == 1) {
      PclError($p_error_code, $p_error_string);
    }
    else {
      $this->error_code = $p_error_code;
      $this->error_string = $p_error_string;
    }
  }
  // --------------------------------------------------------------------------------

  function privErrorReset()
  {
    if (PCLZIP_ERROR_EXTERNAL == 1) {
      PclErrorReset();
    }
    else {
      $this->error_code = 0;
      $this->error_string = '';
    }
  }
  // --------------------------------------------------------------------------------

  function privDecrypt($p_encryption_header, &$p_buffer, $p_size, $p_crc)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::privDecrypt', "size=".$p_size."");
    $v_result=1;
    
    // ----- To Be Modified ;-)
    $v_pwd = "test";
    
    $p_buffer = PclZipUtilZipDecrypt($p_buffer, $p_size, $p_encryption_header,
	                                 $p_crc, $v_pwd);
    
    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privDisableMagicQuotes()
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::privDisableMagicQuotes', "");
    $v_result=1;

    // ----- Look if function exists
    if (   (!function_exists("get_magic_quotes_runtime"))
	    || (!function_exists("set_magic_quotes_runtime"))) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Functions *et_magic_quotes_runtime are not supported");
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
	}

    // ----- Look if already done
    if ($this->magic_quotes_status != -1) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "magic_quote already disabled");
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
	}

	// ----- Get and memorize the magic_quote value
	$this->magic_quotes_status = @get_magic_quotes_runtime();
    //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Current magic_quotes_runtime status is '".($this->magic_quotes_status==0?'disable':'enable')."'");

	// ----- Disable magic_quotes
	if ($this->magic_quotes_status == 1) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Disable magic_quotes");
	  @set_magic_quotes_runtime(0);
	}

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function privSwapBackMagicQuotes()
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, 'PclZip::privSwapBackMagicQuotes', "");
    $v_result=1;

    // ----- Look if function exists
    if (   (!function_exists("get_magic_quotes_runtime"))
	    || (!function_exists("set_magic_quotes_runtime"))) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Functions *et_magic_quotes_runtime are not supported");
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
	}

    // ----- Look if something to do
    if ($this->magic_quotes_status != -1) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "magic_quote not modified");
      //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
	}

	// ----- Swap back magic_quotes
	if ($this->magic_quotes_status == 1) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Enable back magic_quotes");
  	  @set_magic_quotes_runtime($this->magic_quotes_status);
	}

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  }
  // End of class
  // --------------------------------------------------------------------------------

  function PclZipUtilPathReduction($p_dir)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZipUtilPathReduction", "dir='$p_dir'");
    $v_result = "";

    // ----- Look for not empty path
    if ($p_dir != "") {
      // ----- Explode path by directory names
      $v_list = explode("/", $p_dir);

      // ----- Study directories from last to first
      $v_skip = 0;
      for ($i=sizeof($v_list)-1; $i>=0; $i--) {
        // ----- Look for current path
        if ($v_list[$i] == ".") {
          // ----- Ignore this directory
          // Should be the first $i=0, but no check is done
        }
        else if ($v_list[$i] == "..") {
		  $v_skip++;
        }
        else if ($v_list[$i] == "") {
		  // ----- First '/' i.e. root slash
		  if ($i == 0) {
            $v_result = "/".$v_result;
		    if ($v_skip > 0) {
		        // ----- It is an invalid path, so the path is not modified
		        // TBC
		        $v_result = $p_dir;
                //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 3, "Invalid path is unchanged");
                $v_skip = 0;
		    }
		  }
		  // ----- Last '/' i.e. indicates a directory
		  else if ($i == (sizeof($v_list)-1)) {
            $v_result = $v_list[$i];
		  }
		  // ----- Double '/' inside the path
		  else {
            // ----- Ignore only the double '//' in path,
            // but not the first and last '/'
		  }
        }
        else {
		  // ----- Look for item to skip
		  if ($v_skip > 0) {
		    $v_skip--;
		  }
		  else {
            $v_result = $v_list[$i].($i!=(sizeof($v_list)-1)?"/".$v_result:"");
		  }
        }
      }
      
      // ----- Look for skip
      if ($v_skip > 0) {
        while ($v_skip > 0) {
            $v_result = '../'.$v_result;
            $v_skip--;
        }
      }
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function PclZipUtilPathInclusion($p_dir, $p_path)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZipUtilPathInclusion", "dir='$p_dir', path='$p_path'");
    $v_result = 1;
    
    // ----- Look for path beginning by ./
    if (   ($p_dir == '.')
        || ((strlen($p_dir) >=2) && (substr($p_dir, 0, 2) == './'))) {
      $p_dir = PclZipUtilTranslateWinPath(getcwd(), FALSE).'/'.substr($p_dir, 1);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Replacing ./ by full path in p_dir '".$p_dir."'");
    }
    if (   ($p_path == '.')
        || ((strlen($p_path) >=2) && (substr($p_path, 0, 2) == './'))) {
      $p_path = PclZipUtilTranslateWinPath(getcwd(), FALSE).'/'.substr($p_path, 1);
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Replacing ./ by full path in p_path '".$p_path."'");
    }

    // ----- Explode dir and path by directory separator
    $v_list_dir = explode("/", $p_dir);
    $v_list_dir_size = sizeof($v_list_dir);
    $v_list_path = explode("/", $p_path);
    $v_list_path_size = sizeof($v_list_path);

    // ----- Study directories paths
    $i = 0;
    $j = 0;
    while (($i < $v_list_dir_size) && ($j < $v_list_path_size) && ($v_result)) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Working on dir($i)='".$v_list_dir[$i]."' and path($j)='".$v_list_path[$j]."'");

      // ----- Look for empty dir (path reduction)
      if ($v_list_dir[$i] == '') {
        $i++;
        continue;
      }
      if ($v_list_path[$j] == '') {
        $j++;
        continue;
      }

      // ----- Compare the items
      if (($v_list_dir[$i] != $v_list_path[$j]) && ($v_list_dir[$i] != '') && ( $v_list_path[$j] != ''))  {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Items ($i,$j) are different");
        $v_result = 0;
      }

      // ----- Next items
      $i++;
      $j++;
    }

    // ----- Look if everything seems to be the same
    if ($v_result) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Look for tie break");
      // ----- Skip all the empty items
      while (($j < $v_list_path_size) && ($v_list_path[$j] == '')) $j++;
      while (($i < $v_list_dir_size) && ($v_list_dir[$i] == '')) $i++;
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Looking on dir($i)='".($i < $v_list_dir_size?$v_list_dir[$i]:'')."' and path($j)='".($j < $v_list_path_size?$v_list_path[$j]:'')."'");

      if (($i >= $v_list_dir_size) && ($j >= $v_list_path_size)) {
        // ----- There are exactly the same
        $v_result = 2;
      }
      else if ($i < $v_list_dir_size) {
        // ----- The path is shorter than the dir
        $v_result = 0;
      }
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function PclZipUtilCopyBlock($p_src, $p_dest, $p_size, $p_mode=0)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZipUtilCopyBlock", "size=$p_size, mode=$p_mode");
    $v_result = 1;

    if ($p_mode==0)
    {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Src offset before read :".(@ftell($p_src)));
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Dest offset before write :".(@ftell($p_dest)));
      while ($p_size != 0)
      {
        $v_read_size = ($p_size < PCLZIP_READ_BLOCK_SIZE ? $p_size : PCLZIP_READ_BLOCK_SIZE);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
        $v_buffer = @fread($p_src, $v_read_size);
        @fwrite($p_dest, $v_buffer, $v_read_size);
        $p_size -= $v_read_size;
      }
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Src offset after read :".(@ftell($p_src)));
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Dest offset after write :".(@ftell($p_dest)));
    }
    else if ($p_mode==1)
    {
      while ($p_size != 0)
      {
        $v_read_size = ($p_size < PCLZIP_READ_BLOCK_SIZE ? $p_size : PCLZIP_READ_BLOCK_SIZE);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
        $v_buffer = @gzread($p_src, $v_read_size);
        @fwrite($p_dest, $v_buffer, $v_read_size);
        $p_size -= $v_read_size;
      }
    }
    else if ($p_mode==2)
    {
      while ($p_size != 0)
      {
        $v_read_size = ($p_size < PCLZIP_READ_BLOCK_SIZE ? $p_size : PCLZIP_READ_BLOCK_SIZE);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
        $v_buffer = @fread($p_src, $v_read_size);
        @gzwrite($p_dest, $v_buffer, $v_read_size);
        $p_size -= $v_read_size;
      }
    }
    else if ($p_mode==3)
    {
      while ($p_size != 0)
      {
        $v_read_size = ($p_size < PCLZIP_READ_BLOCK_SIZE ? $p_size : PCLZIP_READ_BLOCK_SIZE);
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 4, "Read $v_read_size bytes");
        $v_buffer = @gzread($p_src, $v_read_size);
        @gzwrite($p_dest, $v_buffer, $v_read_size);
        $p_size -= $v_read_size;
      }
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function PclZipUtilRename($p_src, $p_dest)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZipUtilRename", "source=$p_src, destination=$p_dest");
    $v_result = 1;

    // ----- Try to rename the files
    if (!@rename($p_src, $p_dest)) {
      //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Fail to rename file, try copy+unlink");

      // ----- Try to copy & unlink the src
      if (!@copy($p_src, $p_dest)) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Fail to copy file");
        $v_result = 0;
      }
      else if (!@unlink($p_src)) {
        //--(MAGIC-PclTrace)--//PclTraceFctMessage(__FILE__, __LINE__, 5, "Fail to unlink old filename");
        $v_result = 0;
      }
    }

    // ----- Return
    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function PclZipUtilOptionText($p_option)
  {
    //--(MAGIC-PclTrace)--//PclTraceFctStart(__FILE__, __LINE__, "PclZipUtilOptionText", "option='".$p_option."'");
    
    $v_list = get_defined_constants();
    for (reset($v_list); $v_key = key($v_list); next($v_list)) {
	  $v_prefix = substr($v_key, 0, 10);
	  if ((   ($v_prefix == 'PCLZIP_OPT')
         || ($v_prefix == 'PCLZIP_CB_')
         || ($v_prefix == 'PCLZIP_ATT'))
	      && ($v_list[$v_key] == $p_option)) {
          //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_key);
          return $v_key;
	    }
    }
    
    $v_result = 'Unknown';

    //--(MAGIC-PclTrace)--//PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  function PclZipUtilTranslateWinPath($p_path, $p_remove_disk_letter=true)
  {
    if (stristr(php_uname(), 'windows')) {
      // ----- Look for potential disk letter
      if (($p_remove_disk_letter) && (($v_position = strpos($p_path, ':')) != false)) {
          $p_path = substr($p_path, $v_position+1);
      }
      // ----- Change potential windows directory separator
      if ((strpos($p_path, '\\') > 0) || (substr($p_path, 0,1) == '\\')) {
          $p_path = strtr($p_path, '\\', '/');
      }
    }
    return $p_path;
  }
  // --------------------------------------------------------------------------------


?>
