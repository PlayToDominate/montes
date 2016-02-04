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

function GetMonth($month_id)
{
	switch($month_id)
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
	}
	return $month;
}

$sql_GetEvents = "SELECT * FROM Events where id = '".mysql_real_escape_string($_GET['Eventid'])."'";
//echo $sql_GetEvents."<br>";
$error = false;
if(!$rts_Events = mysql_query($sql_GetEvents))
{
	$error="There was a problem connecting to our database, please try back later";
}elseif(!$Event = mysql_fetch_array($rts_Events))
{
	$error="There is not an event with that id.  Visit our Calendar of events <a href='/Monte-Grill-Pub-DisplayEvents.php'>here</a>";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Monte's Grill and Pub</title>
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
      <?
	if(!$error)
	{
?>
      <div class="Menu_Item_Header"><? echo $Event['Title'] ?></div>
      <p class="BodyType"><strong>When</strong>: <? echo GetMonth($Event['Month'])." ".$Event['Day']." ".$Event['Year'] ?></p>
      <p class="BodyType"><strong>Whats going on?</strong> <br />
      <? echo str_replace("\n","<br>",$Event['Description']) ?></p>
      <div style="height:15px;width:10px;"></div>
 <?
	}else
	{
?>
		<div class="Menu_Item_Header">Error</div>
		<p class="BodyType" align="center"><strong> <? echo $error ?> </strong></p>
        <div style="height:10px;width:10px;"></div>
<?
	}
?>

    </div>
  </div>
  <? mysql_close() ?>
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
