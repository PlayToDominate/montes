<?
session_start();
if(!isset($_SESSION['Admin_check']))
{
	header("location: ../admin.php");
}
//Check that they have the security permission to change the menu
//Connect to db
include('../dbconnect.php');
$sql_specials_Check = "SELECT * FROM Admin WHERE User='".mysql_real_escape_string($_SESSION['Admin_check'])."' AND Events = '1'";
$rt_User_Check = mysql_query($sql_specials_Check);
if(!$User = mysql_fetch_array($rt_User_Check))
{
	$Error = urlencode("Sorry you don't have permissions to access this part of the site");
	header("location: ../Options.php?Error=".$Error);	
}

//get date
if(isset($_GET['Month']) && isset($_GET['Year']))
{
	switch($_GET['Month'])
	{
		case 1:
			$month = 'January';
			break;
		case 2:
			$month = 'February';
			break;
		case 3:
			$month = 'March';
			break;
		case 4:
			$month = 'April';
			break;
		case 5:
			$month = 'May';
			break;
		case 6:
			$month = 'June';
			break;
		case 7:
			$month = 'July';
			break;
		case 8:
			$month = 'August';
			break;
		case 9:
			$month = 'September';
			break;
		case 10:
			$month = 'October';
			break;
		case 11:
			$month = 'November';
			break;
		case 12:
			$month = 'December';
			break;
		default:
			$month = date('m',time());
	}
	if((0 < $_GET['Month'] && $_GET['Month'] < 13) && (2007 < $_GET['Year'] && $_GET['Year'] < 2100))
	{
		$now = getdate(strtotime('1 '.$month." ".$_GET['Year']));
	}else
	{
		$now = getdate();	
	}
}else
{
	$now = getdate();
}
$FirstDayofWeek = date('w',strtotime('1 '.$now['month'].' '.$now['year']));
$Days = cal_days_in_month(CAL_GREGORIAN, $now['mon'], $now['year']);
$SubYear = 0;

$sql_GetEvents = "SELECT * FROM Events where Year = '".$now['year']."' AND Month = '".$now['mon']."' ORDER BY Day";
//echo $sql_GetEvents."<br>";
if(!$rts_Events = mysql_query($sql_GetEvents))
{
	echo "sql Problem ". mysql_error();
}elseif(!$Event = mysql_fetch_array($rts_Events))
{
//	echo "getting results problem " . mysql_error();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Monte's  Grill & Pub</title>
    <meta http-equiv="keywords" content="" />
    <meta http-equiv="description" content=""  />	
    <link href="../../monte.css" rel="stylesheet" type="text/css" />
</head>
    
  <body>
<div class="Body_container">
<div class="Top_container">
</div>
	<div class="Content_container">
<?
	include('../../includes/Top.php');
?>
  <div class="Main">
    <div class="MainVideoPane">
      <p align="center"> <span class="HighlightBodyType"><a href="/setmenu/Events/Add_Event.php">Add Events</a> || <a href="/setmenu/Events/DisplayEvents.php">Display Events</a> || <a href="/setmenu/Events/Delete_Events.php">Delete</a> || <a href="/setmenu/Events/Modify_Events.php">Modify</a> || <a href="/setmenu/Logout.php">Logout</a></span></p>
      <p class="Menu_Item_Header">Events</p>
      <table width="784" border="1" cellspacing="0" cellpadding="2">
        <tr>
          <td width="112"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Month=<? if($now['mon'] == 1){ echo "12";$SubYear = 1;}else{echo $now['mon'] - 1;} ?>&Year=<? if($SubYear == 1){ echo $now['year'] - 1;}else{echo $now['year'];} ?>">&lt;&lt;</a></td>
          <td colspan="5"><div align="center"><? echo $now['month']." ".$now['year']?></div></td>
          <td width="112"><div align="right"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Month=<? if($now['mon'] == 12){ echo "1";$SubYear = 2;}else{echo $now['mon'] + 1;} ?>&Year=<? if($SubYear == 2){ echo $now['year'] + 1;}else{echo $now['year'];} ?>">&gt;&gt;</a></div></td>
        </tr>
        <tr>
          <td valign="top"><div align="center">Sunday</div></td>
          <td width="112" valign="top"><div align="center">Monday</div></td>
          <td width="112" valign="top"><div align="center">Tuesday</div></td>
          <td width="112" valign="top"><div align="center">Wednesday</div></td>
          <td width="112" valign="top"><div align="center">Thurday</div></td>
          <td width="112" valign="top"><div align="center">Friday</div></td>
          <td valign="top"><div align="center">Saturday</div></td>
        </tr>
<?
//Top row
    echo "<tr>";
	$Day = 1;
	for($i = 0; $i < $FirstDayofWeek || $i < 7 ; $i++)
	{
		echo "<td valign=\"top\">";		
		if($i >= $FirstDayofWeek)
		{
			echo "<div align='right'>".$Day."</div>";
			while($Event['Day'] == $Day)
			{
				echo "<a href='Show_event.php?Eventid=".$Event['id']."'>".$Event['Title']."</a><br />";
				if(!$Event = mysql_fetch_array($rts_Events))
				{//No events left, Day to 40 so it won't ever be true
					$Event['Day'] = 40;
				}				
			}
			$Day++;
		}else
		{
			echo "&nbsp;";
		}
		echo "</td>";
	} 
	echo "</tr>";
//Print Rest of Table
	for($i = 0; $Day <= $Days || $i%7 != 0; $i++)
	{
		if($i%7 == 0)
		{
			echo "<tr>";
		}
		if($Day <= $Days)
		{
			echo "<td valign=\"top\"><div align='right'>".$Day."</div>";
			while($Event['Day'] == $Day)
			{
				echo "<a href='Show_event.php?Eventid=".$Event['id']."'>".$Event['Title']."</a><br />";
				if(!$Event = mysql_fetch_array($rts_Events))
				{//No events left, Day to 40 so it won't ever be true
					$Event['Day'] = 40;
				}				
			}
			echo "</td>";
			$Day++;
		}else
		{
			echo "<td>&nbsp;</td>";
		}
		if($i%7 == 6)
		{
			echo "</tr>";
		}
	}
?>

      </table>
      <p class="BodyType">&nbsp;</p>
    </div>
  </div>
    <div class="Footer" id="Footer">
    <div align="center"><span class="BodyType">608A West Verona Ave.<br />
Verona WI, 53593<br />
(608) 845-9669</span></div>
  </div>
</div>
<div class="Bottom_bg"></div>
</div>
  </body>
  </html>
