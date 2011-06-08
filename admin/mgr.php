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
	
<script language=JavaScript>
/**
This is a JavaScript library that will allow you to easily add some basic DHTML
drop-down datepicker functionality to your Notes forms. This script is not as
full-featured as others you may find on the Internet, but it's free, it's easy to
understand, and it's easy to change.

You'll also want to include a stylesheet that makes the datepicker elements
look nice. An example one can be found in the database that this script was
originally released with, at:

http://www.nsftools.com/tips/NotesTips.htm#datepicker

I've tested this lightly with Internet Explorer 6 and Mozilla Firefox. I have no idea
how compatible it is with other browsers.

version 1.5
December 4, 2005
Julian Robichaux -- http://www.nsftools.com

HISTORY
--  version 1.0 (Sept. 4, 2004):
Initial release.

--  version 1.1 (Sept. 5, 2004):
Added capability to define the date format to be used, either globally (using the
defaultDateSeparator and defaultDateFormat variables) or when the displayDatePicker
function is called.

--  version 1.2 (Sept. 7, 2004):
Fixed problem where datepicker x-y coordinates weren't right inside of a table.
Fixed problem where datepicker wouldn't display over selection lists on a page.
Added a call to the datePickerClosed function (if one exists) after the datepicker
is closed, to allow the developer to add their own custom validation after a date
has been chosen. For this to work, you must have a function called datePickerClosed
somewhere on the page, that accepts a field object as a parameter. See the
example in the comments of the updateDateField function for more details.

--  version 1.3 (Sept. 9, 2004)
Fixed problem where adding the <div> and <iFrame> used for displaying the datepicker
was causing problems on IE 6 with global variables that had handles to objects on
the page (I fixed the problem by adding the elements using document.createElement()
and document.body.appendChild() instead of document.body.innerHTML += ...).

--  version 1.4 (Dec. 20, 2004)
Added "targetDateField.focus();" to the updateDateField function (as suggested
by Alan Lepofsky) to avoid a situation where the cursor focus is at the top of the
form after a date has been picked. Added "padding: 0px;" to the dpButton CSS
style, to keep the table from being so wide when displayed in Firefox.

-- version 1.5 (Dec 4, 2005)
Added display=none when datepicker is hidden, to fix problem where cursor is
not visible on input fields that are beneath the date picker. Added additional null
date handling for date errors in Safari when the date is empty. Added additional
error handling for iFrame creation, to avoid reported errors in Opera. Added
onMouseOver event for day cells, to allow color changes when the mouse hovers
over a cell (to make it easier to determine what cell you're over). Added comments
in the style sheet, to make it more clear what the different style elements are for.
*/

var datePickerDivID = "datepicker";
var iFrameDivID = "datepickeriframe";

var dayArrayShort = new Array('Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa');
var dayArrayMed = new Array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
var dayArrayLong = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
var monthArrayShort = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
var monthArrayMed = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
var monthArrayLong = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
 
// these variables define the date formatting we're expecting and outputting.
// If you want to use a different format by default, change the defaultDateSeparator
// and defaultDateFormat variables either here or on your HTML page.
var defaultDateSeparator = "";        // common values would be "/" or "."
var defaultDateFormat = "ymd"    // valid values are "mdy", "dmy", and "ymd"
var dateSeparator = defaultDateSeparator;
var dateFormat = defaultDateFormat;

/**
This is the main function you'll call from the onClick event of a button.
Normally, you'll have something like this on your HTML page:

Start Date: <input name="StartDate">
<input type=button value="select" onclick="displayDatePicker('StartDate');">

That will cause the datepicker to be displayed beneath the StartDate field and
any date that is chosen will update the value of that field. If you'd rather have the
datepicker display beneath the button that was clicked, you can code the button
like this:

<input type=button value="select" onclick="displayDatePicker('StartDate', this);">

So, pretty much, the first argument (dateFieldName) is a string representing the
name of the field that will be modified if the user picks a date, and the second
argument (displayBelowThisObject) is optional and represents an actual node
on the HTML document that the datepicker should be displayed below.

In version 1.1 of this code, the dtFormat and dtSep variables were added, allowing
you to use a specific date format or date separator for a given call to this function.
Normally, you'll just want to set these defaults globally with the defaultDateSeparator
and defaultDateFormat variables, but it doesn't hurt anything to add them as optional
parameters here. An example of use is:

<input type=button value="select" onclick="displayDatePicker('StartDate', false, 'dmy', '.');">

This would display the datepicker beneath the StartDate field (because the
displayBelowThisObject parameter was false), and update the StartDate field with
the chosen value of the datepicker using a date format of dd.mm.yyyy
*/
function displayDatePicker(dateFieldName, displayBelowThisObject, dtFormat, dtSep)
{
  var targetDateField = document.getElementsByName (dateFieldName).item(0);
 
  // if we weren't told what node to display the datepicker beneath, just display it
  // beneath the date field we're updating
  if (!displayBelowThisObject)
    displayBelowThisObject = targetDateField;
 
  // if a date separator character was given, update the dateSeparator variable
  if (dtSep)
    dateSeparator = dtSep;
  else
    dateSeparator = defaultDateSeparator;
 
  // if a date format was given, update the dateFormat variable
  if (dtFormat)
    dateFormat = dtFormat;
  else
    dateFormat = defaultDateFormat;
 
  var x = displayBelowThisObject.offsetLeft;
  var y = displayBelowThisObject.offsetTop + displayBelowThisObject.offsetHeight ;
 
  // deal with elements inside tables and such
  var parent = displayBelowThisObject;
  while (parent.offsetParent) {
    parent = parent.offsetParent;
    x += parent.offsetLeft;
    y += parent.offsetTop ;
  }
 
  drawDatePicker(targetDateField, x, y);
}


/**
Draw the datepicker object (which is just a table with calendar elements) at the
specified x and y coordinates, using the targetDateField object as the input tag
that will ultimately be populated with a date.

This function will normally be called by the displayDatePicker function.
*/
function drawDatePicker(targetDateField, x, y)
{
  var dt = getFieldDate(targetDateField.value );
 
  // the datepicker table will be drawn inside of a <div> with an ID defined by the
  // global datePickerDivID variable. If such a div doesn't yet exist on the HTML
  // document we're working with, add one.
  if (!document.getElementById(datePickerDivID)) {
    // don't use innerHTML to update the body, because it can cause global variables
    // that are currently pointing to objects on the page to have bad references
    //document.body.innerHTML += "<div id='" + datePickerDivID + "' class='dpDiv'></div>";
    var newNode = document.createElement("div");
    newNode.setAttribute("id", datePickerDivID);
    newNode.setAttribute("class", "dpDiv");
    newNode.setAttribute("style", "visibility: hidden;");
    document.body.appendChild(newNode);
  }
 
  // move the datepicker div to the proper x,y coordinate and toggle the visiblity
  var pickerDiv = document.getElementById(datePickerDivID);
  pickerDiv.style.position = "absolute";
  pickerDiv.style.left = x + "px";
  pickerDiv.style.top = y + "px";
  pickerDiv.style.visibility = (pickerDiv.style.visibility == "visible" ? "hidden" : "visible");
  pickerDiv.style.display = (pickerDiv.style.display == "block" ? "none" : "block");
  pickerDiv.style.zIndex = 10000;
 
  // draw the datepicker table
  refreshDatePicker(targetDateField.name, dt.getFullYear(), dt.getMonth(), dt.getDate());
}


/**
This is the function that actually draws the datepicker calendar.
*/
function refreshDatePicker(dateFieldName, year, month, day)
{
  // if no arguments are passed, use today's date; otherwise, month and year
  // are required (if a day is passed, it will be highlighted later)
  var thisDay = new Date();
 
  if ((month >= 0) && (year > 0)) {
    thisDay = new Date(year, month, 1);
  } else {
    day = thisDay.getDate();
    thisDay.setDate(1);
  }
 
  // the calendar will be drawn as a table
  // you can customize the table elements with a global CSS style sheet,
  // or by hardcoding style and formatting elements below
  var crlf = "\r\n";
  var TABLE = "<table cols=7 class='dpTable'>" + crlf;
  var xTABLE = "</table>" + crlf;
  var TR = "<tr class='dpTR'>";
  var TR_title = "<tr class='dpTitleTR'>";
  var TR_days = "<tr class='dpDayTR'>";
  var TR_todaybutton = "<tr class='dpTodayButtonTR'>";
  var xTR = "</tr>" + crlf;
  var TD = "<td class='dpTD' onMouseOut='this.className=\"dpTD\";' onMouseOver=' this.className=\"dpTDHover\";' ";    // leave this tag open, because we'll be adding an onClick event
  var TD_title = "<td colspan=5 class='dpTitleTD'>";
  var TD_buttons = "<td class='dpButtonTD'>";
  var TD_todaybutton = "<td colspan=7 class='dpTodayButtonTD'>";
  var TD_days = "<td class='dpDayTD'>";
  var TD_selected = "<td class='dpDayHighlightTD' onMouseOut='this.className=\"dpDayHighlightTD\";' onMouseOver='this.className=\"dpTDHover\";' ";    // leave this tag open, because we'll be adding an onClick event
  var xTD = "</td>" + crlf;
  var DIV_title = "<div class='dpTitleText'>";
  var DIV_selected = "<div class='dpDayHighlight'>";
  var xDIV = "</div>";
 
  // start generating the code for the calendar table
  var html = TABLE;
 
  // this is the title bar, which displays the month and the buttons to
  // go back to a previous month or forward to the next month
  html += TR_title;
  html += TD_buttons + getButtonCode(dateFieldName, thisDay, -1, "&lt;") + xTD;
  html += TD_title + DIV_title + monthArrayLong[ thisDay.getMonth()] + " " + thisDay.getFullYear() + xDIV + xTD;
  html += TD_buttons + getButtonCode(dateFieldName, thisDay, 1, "&gt;") + xTD;
  html += xTR;
 
  // this is the row that indicates which day of the week we're on
  html += TR_days;
  for(i = 0; i < dayArrayShort.length; i++)
    html += TD_days + dayArrayShort[i] + xTD;
  html += xTR;
 
  // now we'll start populating the table with days of the month
  html += TR;
 
  // first, the leading blanks
  for (i = 0; i < thisDay.getDay(); i++)
    html += TD + "&nbsp;" + xTD;
 
  // now, the days of the month
  do {
    dayNum = thisDay.getDate();
    TD_onclick = " onclick=\"updateDateField('" + dateFieldName + "', '" + getDateString(thisDay) + "');\">";
    
    if (dayNum == day)
      html += TD_selected + TD_onclick + DIV_selected + dayNum + xDIV + xTD;
    else
      html += TD + TD_onclick + dayNum + xTD;
    
    // if this is a Saturday, start a new row
    if (thisDay.getDay() == 6)
      html += xTR + TR;
    
    // increment the day
    thisDay.setDate(thisDay.getDate() + 1);
  } while (thisDay.getDate() > 1)
 
  // fill in any trailing blanks
  if (thisDay.getDay() > 0) {
    for (i = 6; i > thisDay.getDay(); i--)
      html += TD + "&nbsp;" + xTD;
  }
  html += xTR;
 
  // add a button to allow the user to easily return to today, or close the calendar
  var today = new Date();
  var todayString = "Today is " + dayArrayMed[today.getDay()] + ", " + monthArrayMed[ today.getMonth()] + " " + today.getDate();
  html += TR_todaybutton + TD_todaybutton;
  html += "<button class='dpTodayButton' onClick='refreshDatePicker(\"" + dateFieldName + "\");'>this month</button> ";
  html += "<button class='dpTodayButton' onClick='updateDateField(\"" + dateFieldName + "\");'>close</button>";
  html += xTD + xTR;
 
  // and finally, close the table
  html += xTABLE;
 
  document.getElementById(datePickerDivID).innerHTML = html;
  // add an "iFrame shim" to allow the datepicker to display above selection lists
  adjustiFrame();
}


/**
Convenience function for writing the code for the buttons that bring us back or forward
a month.
*/
function getButtonCode(dateFieldName, dateVal, adjust, label)
{
  var newMonth = (dateVal.getMonth () + adjust) % 12;
  var newYear = dateVal.getFullYear() + parseInt((dateVal.getMonth() + adjust) / 12);
  if (newMonth < 0) {
    newMonth += 12;
    newYear += -1;
  }
 
  return "<button class='dpButton' onClick='refreshDatePicker(\"" + dateFieldName + "\", " + newYear + ", " + newMonth + ");'>" + label + "</button>";
}


/**
Convert a JavaScript Date object to a string, based on the dateFormat and dateSeparator
variables at the beginning of this script library.
*/
function getDateString(dateVal)
{
  var dayString = "00" + dateVal.getDate();
  var monthString = "00" + (dateVal.getMonth()+1);
  dayString = dayString.substring(dayString.length - 2);
  monthString = monthString.substring(monthString.length - 2);
 
  switch (dateFormat) {
    case "dmy" :
      return dayString + dateSeparator + monthString + dateSeparator + dateVal.getFullYear();
    case "ymd" :
      return dateVal.getFullYear() + dateSeparator + monthString + dateSeparator + dayString;
    case "mdy" :
    default :
      return monthString + dateSeparator + dayString + dateSeparator + dateVal.getFullYear();
  }
}


/**
Convert a string to a JavaScript Date object.
*/
function getFieldDate(dateString)
{
  var dateVal;
  var dArray;
  var d, m, y;
 
  try {
    dArray = splitDateString(dateString);
    if (dArray) {
      switch (dateFormat) {
        case "dmy" :
          d = parseInt(dArray[0], 10);
          m = parseInt(dArray[1], 10) - 1;
          y = parseInt(dArray[2], 10);
          break;
        case "ymd" :
          d = parseInt(dArray[2], 10);
          m = parseInt(dArray[1], 10) - 1;
          y = parseInt(dArray[0], 10);
          break;
        case "mdy" :
        default :
          d = parseInt(dArray[1], 10);
          m = parseInt(dArray[0], 10) - 1;
          y = parseInt(dArray[2], 10);
          break;
      }
      dateVal = new Date(y, m, d);
    } else if (dateString) {
      dateVal = new Date(dateString);
    } else {
      dateVal = new Date();
    }
  } catch(e) {
    dateVal = new Date();
  }
 
  return dateVal;
}


/**
Try to split a date string into an array of elements, using common date separators.
If the date is split, an array is returned; otherwise, we just return false.
*/
function splitDateString(dateString)
{
  var dArray;
  if (dateString.indexOf("/") >= 0)
    dArray = dateString.split("/");
  else if (dateString.indexOf(".") >= 0)
    dArray = dateString.split(".");
  else if (dateString.indexOf("-") >= 0)
    dArray = dateString.split("-");
  else if (dateString.indexOf("\\") >= 0)
    dArray = dateString.split("\\");
  else
    dArray = false;
 
  return dArray;
}

/**
Update the field with the given dateFieldName with the dateString that has been passed,
and hide the datepicker. If no dateString is passed, just close the datepicker without
changing the field value.

Also, if the page developer has defined a function called datePickerClosed anywhere on
the page or in an imported library, we will attempt to run that function with the updated
field as a parameter. This can be used for such things as date validation, setting default
values for related fields, etc. For example, you might have a function like this to validate
a start date field:

function datePickerClosed(dateField)
{
  var dateObj = getFieldDate(dateField.value);
  var today = new Date();
  today = new Date(today.getFullYear(), today.getMonth(), today.getDate());
 
  if (dateField.name == "StartDate") {
    if (dateObj < today) {
      // if the date is before today, alert the user and display the datepicker again
      alert("Please enter a date that is today or later");
      dateField.value = "";
      document.getElementById(datePickerDivID).style.visibility = "visible";
      adjustiFrame();
    } else {
      // if the date is okay, set the EndDate field to 7 days after the StartDate
      dateObj.setTime(dateObj.getTime() + (7 * 24 * 60 * 60 * 1000));
      var endDateField = document.getElementsByName ("EndDate").item(0);
      endDateField.value = getDateString(dateObj);
    }
  }
}

*/
function updateDateField(dateFieldName, dateString)
{
  var targetDateField = document.getElementsByName (dateFieldName).item(0);
  if (dateString)
    targetDateField.value = dateString;
 
  var pickerDiv = document.getElementById(datePickerDivID);
  pickerDiv.style.visibility = "hidden";
  pickerDiv.style.display = "none";
 
  adjustiFrame();
  targetDateField.focus();
 
  // after the datepicker has closed, optionally run a user-defined function called
  // datePickerClosed, passing the field that was just updated as a parameter
  // (note that this will only run if the user actually selected a date from the datepicker)
  if ((dateString) && (typeof(datePickerClosed) == "function"))
    datePickerClosed(targetDateField);
}


/**
Use an "iFrame shim" to deal with problems where the datepicker shows up behind
selection list elements, if they're below the datepicker. The problem and solution are
described at:

http://dotnetjunkies.com/WebLog/jking/archive/2003/07/21/488.aspx
http://dotnetjunkies.com/WebLog/jking/archive/2003/10/30/2975.aspx
*/
function adjustiFrame(pickerDiv, iFrameDiv)
{
  // we know that Opera doesn't like something about this, so if we
  // think we're using Opera, don't even try
  var is_opera = (navigator.userAgent.toLowerCase().indexOf("opera") != -1);
  if (is_opera)
    return;
  
  // put a try/catch block around the whole thing, just in case
  try {
    if (!document.getElementById(iFrameDivID)) {
      // don't use innerHTML to update the body, because it can cause global variables
      // that are currently pointing to objects on the page to have bad references
      //document.body.innerHTML += "<iframe id='" + iFrameDivID + "' src='javascript:false;' scrolling='no' frameborder='0'>";
      var newNode = document.createElement("iFrame");
      newNode.setAttribute("id", iFrameDivID);
      newNode.setAttribute("src", "javascript:false;");
      newNode.setAttribute("scrolling", "no");
      newNode.setAttribute ("frameborder", "0");
      document.body.appendChild(newNode);
    }
    
    if (!pickerDiv)
      pickerDiv = document.getElementById(datePickerDivID);
    if (!iFrameDiv)
      iFrameDiv = document.getElementById(iFrameDivID);
    
    try {
      iFrameDiv.style.position = "absolute";
      iFrameDiv.style.width = pickerDiv.offsetWidth;
      iFrameDiv.style.height = pickerDiv.offsetHeight ;
      iFrameDiv.style.top = pickerDiv.style.top;
      iFrameDiv.style.left = pickerDiv.style.left;
      iFrameDiv.style.zIndex = pickerDiv.style.zIndex - 1;
      iFrameDiv.style.visibility = pickerDiv.style.visibility ;
      iFrameDiv.style.display = pickerDiv.style.display;
    } catch(e) {
    }
 
  } catch (ee) {
  }
 
}


</script>

<style>
body {
	font-family: Verdana, Tahoma, Arial, Helvetica, sans-serif;
	font-size: .8em;
	}

/* the div that holds the date picker calendar */
.dpDiv {
	}


/* the table (within the div) that holds the date picker calendar */
.dpTable {
	font-family: Tahoma, Arial, Helvetica, sans-serif;
	font-size: 12px;
	text-align: center;
	color: #505050;
	background-color: #ece9d8;
	border: 1px solid #AAAAAA;
	}


/* a table row that holds date numbers (either blank or 1-31) */
.dpTR {
	}


/* the top table row that holds the month, year, and forward/backward buttons */
.dpTitleTR {
	}


/* the second table row, that holds the names of days of the week (Mo, Tu, We, etc.) */
.dpDayTR {
	}


/* the bottom table row, that has the "This Month" and "Close" buttons */
.dpTodayButtonTR {
	}


/* a table cell that holds a date number (either blank or 1-31) */
.dpTD {
	border: 1px solid #ece9d8;
	}


/* a table cell that holds a highlighted day (usually either today's date or the current date field value) */
.dpDayHighlightTD {
	background-color: #CCCCCC;
	border: 1px solid #AAAAAA;
	}


/* the date number table cell that the mouse pointer is currently over (you can use contrasting colors to make it apparent which cell is being hovered over) */
.dpTDHover {
	background-color: #aca998;
	border: 1px solid #888888;
	cursor: pointer;
	color: red;
	}


/* the table cell that holds the name of the month and the year */
.dpTitleTD {
	}


/* a table cell that holds one of the forward/backward buttons */
.dpButtonTD {
	}


/* the table cell that holds the "This Month" or "Close" button at the bottom */
.dpTodayButtonTD {
	}


/* a table cell that holds the names of days of the week (Mo, Tu, We, etc.) */
.dpDayTD {
	background-color: #CCCCCC;
	border: 1px solid #AAAAAA;
	color: white;
	}


/* additional style information for the text that indicates the month and year */
.dpTitleText {
	font-size: 12px;
	color: gray;
	font-weight: bold;
	}


/* additional style information for the cell that holds a highlighted day (usually either today's date or the current date field value) */ 
.dpDayHighlight {
	color: 4060ff;
	font-weight: bold;
	}


/* the forward/backward buttons at the top */
.dpButton {
	font-family: Verdana, Tahoma, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: gray;
	background: #d8e8ff;
	font-weight: bold;
	padding: 0px;
	}


/* the "This Month" and "Close" buttons at the bottom */
.dpTodayButton {
	font-family: Verdana, Tahoma, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: gray;
	background: #d8e8ff;
	font-weight: bold;
	}

</style>
    
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
                <div style="padding: 5px 4px 5px 4px; border: 1px solid #6192dc; margin: 2px 7px 3px 7px; background-color: #234d8e;"><a href="../" class="manager_nav_on" target="_blank">MainSite </a></div>
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
