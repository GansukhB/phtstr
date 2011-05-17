<?php
// MGR Calendar Code Version 1.0b [3.3.04]

// Saves the last month you looked at. Not nesseccary for manager version.
//session_register("calendar_month");
//session_register("calendar_year");

echo "<!-- CALENDAR CODE STARTS HERE -->";
$month = $_GET['month'];
$year = $_GET['year'];
$date = $_GET['date'];
$field_var = $_GET['field_var'];

if($month != "") {
	$calendar_month = $month;
	$calendar_year = $year;
}

if($calendar_month != "") {
	$month = $calendar_month;
	$year = $calendar_year;
}

class MyCalendar extends Calendar {
	function getCalendarLink($month, $year) {
	  $s = getenv('SCRIPT_NAME');
	  return "$s?month=$month&year=$year";
	}
}

class Calendar {
	function Calendar() {
	}
	
	function getDayNames() {
  	return $this->dayNames;
  }
  
  function setDayNames($names) {
  	$this->dayNames = $names;
  }
  
  function getMonthNames() {
  	return $this->monthNames;
  }
  
  function setMonthNames($names) {
  	$this->monthNames = $names;
  }
  
  function getStartDay() {
  	return $this->startDay;
  }
  
  function setStartDay($day) {
  	$this->startDay = $day;
  }
  
  function getStartMonth() {
  	return $this->startMonth;
  }
  
  function setStartMonth($month) {
  	$this->startMonth = $month;
  }
  
  function getCalendarLink($month, $year) {
  	return "";
  }
  
  function getDateLink($day, $month, $year) {
  	return "";
  }
	
	function getCurrentMonthView() {
  	$d = getdate(time());
    return $this->getMonthView($d["mon"], $d["year"]);
	}
  
  function getCurrentYearView() {
  	$d = getdate(time());
    return $this->getYearView($d["year"]);
  }
  
  function getMonthView($month, $year) {
  	return $this->getMonthHTML($month, $year);
  }
  
  function getYearView($year) {
 		return $this->getYearHTML($year);
  }
  
  function getDaysInMonth($month, $year) {
	  if($month < 1 || $month > 12) {
	  	return 0;
	  }
   	$d = $this->daysInMonth[$month - 1];
    if($month == 2) {
    	if($year%4 == 0) {
      	if($year%100 == 0) {
        	if($year%400 == 0) {
          	$d = 29;
          }
        }
        else {
        	$d = 29;
        }
     	}
    }
	return $d;
}

function getMonthHTML($m, $y, $showYear = 1) {
	
  include("../database.php");
  $s = "";
  
  $a = $this->adjustDate($m, $y);
  $month = $a[0];
  $year = $a[1];        
  
  $daysInMonth = $this->getDaysInMonth($month, $year);
  $date = getdate(mktime(12, 0, 0, $month, 1, $year));
  
  $first = $date["wday"];
  $monthName = $this->monthNames[$month - 1];
  
  $prev = $this->adjustDate($month - 1, $year);
  $next = $this->adjustDate($month + 1, $year);
  
  if ($showYear == 1) {
  	$prevMonth = $this->getCalendarLink($prev[0], $prev[1]);
    $nextMonth = $this->getCalendarLink($next[0], $next[1]);
  }
  else {
  	$prevMonth = "";
    $nextMonth = "";
  }
  $header = $monthName . (($showYear > 0) ? " " . $year : "");
?>
<title>Calendar</title>
<body bgcolor="#5E85CA">
<style>
<!--
/* START : CALENDAR STYLE */
a.arrows:link			{color:#4581BE; text-decoration:none;}
a.arrows:visited		{color:#4581BE; text-decoration:none;}
a.arrows:hover			{color:#000000; text-decoration:none;}
	
a.calendar:link			{color:#FFFFFF; text-decoration:none;}
a.calendar:visited		{color:#FFFFFF; text-decoration:none;}
a.calendar:hover		{color:#CCCCCC; text-decoration:none;}

.calendar               {background-color: #ffffff; font-family: Verdana, Arial, Helvetica, sans-serif; font-size : 11; color : #000000;}
.calendarHeader         {font-weight: bold; background-color: #eeeeee;}
.calendarSubHeader      {font-weight: bold; background-color: #ffffff;}

.today_color            {background-color: #ACC6E1; color: #000000; font-weight: normal; font-size : 11;}
.event_color            {background-color: #4581BE; color: #ffffff; font-weight: normal; font-size : 11;}
/* END : CALENDAR STYLE */
-->
</style>
<?
  //$s .= "\t\t<link rel=\"stylesheet\" href=\"mgr_calendar_style.css\">\n";
  $s .= "<table class=\"calendar\" width=\"160\" align=\"center\" border=\"1\" bordercolor=\"#dddddd\" cellspacing=\"0\" cellpadding=\"2\" style=\"border: 1px solid #000000;\">\n";
  $s .= "<tr>\n";
  $s .= "<td align=\"center\" class=\"calendar\">" . (($prevMonth == "") ? "&nbsp;" : "<a href=\"$PHP_SELF?field_var=" . $_GET['field_var'] . "&month=$prev[0]&year=$prev[1]&date=search\" class=\"arrows\"><</a>")  . "</td>\n";
  $s .= "<td align=\"center\" class=\"calendarHeader\" colspan=\"5\">$header</td>\n"; 
  $s .= "<td align=\"center\" class=\"calendar\">" . (($nextMonth == "") ? "&nbsp;" : "<a href=\"$PHP_SELF?field_var=" . $_GET['field_var'] . "&month=$next[0]&year=$next[1]&date=search\" class=\"arrows\">></a>")  . "</td>\n";
  $s .= "</tr>\n";
  
  $s .= "<tr>\n";
  $s .= "<td align=\"center\" class=\"calendarSubHeader\"><font face=\"verdana\" size=\"1\">" . $this->dayNames[($this->startDay)%7] . "</td>\n";
  $s .= "<td align=\"center\" class=\"calendarSubHeader\"><font face=\"verdana\" size=\"1\">" . $this->dayNames[($this->startDay+1)%7] . "</td>\n";
  $s .= "<td align=\"center\" class=\"calendarSubHeader\"><font face=\"verdana\" size=\"1\">" . $this->dayNames[($this->startDay+2)%7] . "</td>\n";
  $s .= "<td align=\"center\" class=\"calendarSubHeader\"><font face=\"verdana\" size=\"1\">" . $this->dayNames[($this->startDay+3)%7] . "</td>\n";
  $s .= "<td align=\"center\" class=\"calendarSubHeader\"><font face=\"verdana\" size=\"1\">" . $this->dayNames[($this->startDay+4)%7] . "</td>\n";
  $s .= "<td align=\"center\" class=\"calendarSubHeader\"><font face=\"verdana\" size=\"1\">" . $this->dayNames[($this->startDay+5)%7] . "</td>\n";
  $s .= "<td align=\"center\" class=\"calendarSubHeader\"><font face=\"verdana\" size=\"1\">" . $this->dayNames[($this->startDay+6)%7] . "</td>\n";
  $s .= "</tr>\n";
  
  $d = $this->startDay + 1 - $first;
  while ($d > 1) {
  	$d -= 7;
  }
	
	$today = getdate(time());
  
  while ($d <= $daysInMonth) {
		$s .= "<tr height=\"15\">\n";       
    
    for ($i = 0; $i < 7; $i++) {
    	$class = ($year == $today["year"] && $month == $today["mon"] && $d == $today["mday"]) ? "calendarToday" : "calendar";
    	if ($d > 0 && $d <= $daysInMonth) {
				if (strLen($d) == "1") {
					$d = "0" . $d;
				}
				
			$tempMonth = $month;
			
			if (strLen($month) == "1") {
				$month = "0" . $month;
			}
			
			// $strDate = $year . "/" . $month . "/" . $d;
			$strDate = $year . $month . $d;
			$strDate2 = $year . $month . $d;
			
			
			/*
			if($strDate2 < date("Ymd")) {
				$sql = "UPDATE calendar SET active = '0' WHERE date = '$strDate'";
				$result = mysql_query($sql);
			}
			*/		
			
			//$event_result = mysql_query("SELECT * FROM events WHERE start_date = '$strDate' AND active = '1'", $db);
			//$event = mysql_fetch_object($event_result);
			//$event_rows = mysql_num_rows($event_result);

			
			if (($d == date("d") and $month == date("m") and $year == date("Y"))) {
				if ($event_rows > 0) {
					$s .= "<td style=\"cursor:hand\" onclick=\"window.opener.data_form." . $_GET['field_var'] . "_year.value='$year';window.opener.data_form." . $_GET['field_var'] . "_month.value='$month';window.opener.data_form." . $_GET['field_var'] . "_day.value='$d'\" class=\"today_color\" align=\"right\" valign=\"top\">";
				}
				else {
					$s .= "<td style=\"cursor:hand\" onclick=\"window.opener.data_form." . $_GET['field_var'] . "_year.value='$year';window.opener.data_form." . $_GET['field_var'] . "_month.value='$month';window.opener.data_form." . $_GET['field_var'] . "_day.value='$d'\" class=\"today_color\" align=\"right\" valign=\"top\">";
				}
			}
			else {
				/*
				if ($event_rows > 0) {
					$s .= "<td style=\"cursor:hand\" onclick=\"location.href='./#?e_id=$event->id'\"  class=\"event_color\" align=\"right\" valign=\"top\">";
				}
				else {
				*/
					$s .= "<td style=\"cursor:hand\" onmouseover=\"this.style.backgroundColor='#eeeeee'\" onmouseout=\"this.style.backgroundColor='#ffffff'\" onclick=\"window.opener.data_form." . $_GET['field_var'] . "_year.value='$year';window.opener.data_form." . $_GET['field_var'] . "_month.value='$month';window.opener.data_form." . $_GET['field_var'] . "_day.value='$d'\" bgcolor=\"#FFFFFF\" class=\"calendar\" align=\"right\" valign=\"top\">";
				//}
			}
			
			$link = "#";
			$s .= (($link == "") ? $d :"$d");
    }
   	else {
			$s .= "<td bgcolor=\"#CCCCCC\" class=\"calendar\" align=\"right\" valign=\"top\">";
			$s .= "<font face=\"verdana\" size=\"1\">&nbsp;</font>";
   	}
    
    	$s .= "</td>\n";
    	$d++;
    	
   	}
  	$s .= "</tr>\n";
	}
    
    $s .= "</table>\n";
    
?>
<?
  	return $s;
  }
  
  function adjustDate($month, $year) {
	  $a = array();
	  $a[0] = $month;
	  $a[1] = $year;
	  
	  while ($a[0] > 12) {
	      $a[0] -= 12;
	      $a[1]++;
	  }
	  
	  while ($a[0] <= 0) {
	      $a[0] += 12;
	      $a[1]--;
	  }
	  
	  return $a;
  }
	
  var $startDay = 0;
  var $startMonth = 1;
  var $dayNames = array("S", "M", "T", "W", "T", "F", "S");
	var $monthNames = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
  var $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
}
?>

<?
$d = getdate(time());

if ($month == "") {
	$month = $d["mon"];
}

if ($year == "") {
	$year = $d["year"];
}

$cal = new MyCalendar;
echo $cal->getMonthView($month, $year);
?>
