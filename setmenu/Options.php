<?
session_start();
if(!isset($_SESSION['Admin_check']))
{
	header("location: ./admin.php");
}
//Check that they have the security permission to change the menu
//Connect to db
include('dbconnect.php');
$sql_specials_Check = "SELECT * FROM Admin WHERE User='".mysql_real_escape_string($_SESSION['Admin_check'])."' AND (Specials = '1' OR Jobs = '1' OR Events = '1' OR Users = '1')";
$rt_User_Check = mysql_query($sql_specials_Check);
if(!$User = mysql_fetch_array($rt_User_Check))
{
	header("location: ./admin.php");	
}
mysql_close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Monte's  Grill & Pub</title>
    <meta http-equiv="keywords" content="" />
    <meta http-equiv="description" content=""  />	
    <link href="../monte.css" rel="stylesheet" type="text/css" />
</head>
    
  <body>
<div class="Body_container">
<div class="Top_container">
</div>
	<div class="Content_container">
<?
	include('../includes/Top.php');
?>
  <div class="Main">
    <div class="MainVideoPane" >
      <p class="Menu_Item_Header">Admin Area</p>
	<? if($User['Specials'] == '1')
		{ ?>
      <p class="BodyType"><a href="/setmenu/Specials.php">Specials Menu</a></p>
	<? }
		if($User['Events'] == '1')
		{ ?>
      <p class="BodyType"><a href="/setmenu/Events/DisplayEvents.php">Events</a></p>
	<? }
		if($User['Jobs'] == '1')
		{ ?>
      <p class="BodyType"><a href="/setmenu/Jobs.php">Job Opportunities</a></p>
	<? 
		}
		if($User['Users'] == '1')
		{ ?>      
      <p class="BodyType"><a href="/setmenu/Users/Add_Users.php">Users</a></p>
      <? }?>
      <p class="BodyType"><a href="/setmenu/Logout.php">Logout</a></p>
    </div>
     <div class="Footer" id="Footer">
    <div align="center"><span class="BodyType">608A West Verona Ave.<br />
Verona WI, 53593<br />
(608) 845-9669</span></div>
  </div>
  </div>
</div>
<div class="Bottom_bg"></div>
</div>
  </body>
  </html>
