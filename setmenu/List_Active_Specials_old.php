<?
session_start();
if(!isset($_SESSION['Admin_check']))
{
	header("location: ./admin.php");
}
//Check that they have the security permission to change the menu
//Connect to db
include('dbconnect.php');
$sql_specials_Check = "SELECT * FROM Admin WHERE User='".mysql_real_escape_string($_SESSION['Admin_check'])."' AND Specials = '1'";
$rt_User_Check = mysql_query($sql_specials_Check);
if(!$User = mysql_fetch_array($rt_User_Check))
{
	$Error = urlencode("Sorry you don't have permissions to access this part of the site");
	header("location: ./Options.php?Error=".$Error);	
}

function PrintActiveSpecials($Day, $link)
{
	$NoSpecials = true;
	$sql_AllActive = "SELECT * FROM Specials WHERE Day = ".$Day." AND Active = 1 ORDER BY Weight";
	if(!($rt_ActiveSpecials = mysql_query($sql_AllActive,$link)))
	{
		echo "Query error<br>" . mysql_error($link);
	}
	while($ActiveSpecial = mysql_fetch_array($rt_ActiveSpecials))
	{
		echo "<p>";
		$NoSpecials = false;
/*		echo "<input name=\"SP".$ActiveSpecial['id']."\" type=\"checkbox\" id=\"SP".$ActiveSpecial['id']."\" ";
		if($ActiveSpecial['Active'] == 1)
		{
			echo "checked=\"checked\" ";
		}
		echo "/>";  */
		echo "<span class=\"Menu_Items\">".$ActiveSpecial['Name']."</span><br><span class=\"BodyType\"> ".str_replace("\n","<br />",$ActiveSpecial['Description'])." - $".$ActiveSpecial['Price']."</span>";
		echo "</p>";
	}
	if($NoSpecials)
	{
		echo "<p><span class=\"BodyType\">Sorry no specials today</span></p>";
	}
}

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
    <div class="MainVideoPane">
      <p align="center"> <span class="HighlightBodyType"><a href="Add_Specials.php">Add</a> || <a href="List_Active_Specials.php">List Active</a> || <a href="Active_Specials.php">Activate/Inactivate</a> || <a href="Delete_Specials.php">Delete</a> || <a href="Modify_Special.php">Modify</a> || <a href="/setmenu/Logout.php">Logout</a> </span></p>
      <p class="Menu_Item_Header">Active Specials</p>
      <p class="HighlightBodyType">Monday</p>
    	<?
			PrintActiveSpecials(1, $link)
		?>
      <p class="HighlightBodyType">Tuesday</p>
     	<?
			PrintActiveSpecials(2, $link)
		?>
      <p class="HighlightBodyType">Wednesday</p>
     	<?
			PrintActiveSpecials(3, $link)
		?>
      <p class="HighlightBodyType">Thursday</p>
      	<?
			PrintActiveSpecials(4, $link)
		?>
      <p class="HighlightBodyType">Friday</p>
      	<?
			PrintActiveSpecials(5, $link)
		?>
      <p class="HighlightBodyType">Saturday</p>
      	<?
			PrintActiveSpecials(6, $link)
		?>
      <p class="HighlightBodyType">Sunday</p>
      	<?
			PrintActiveSpecials(7, $link)
		?>
<? /*
      <p class="HighlightBodyType">Monday</p>
<?
	$sql_AllActive = "SELECT * FROM Specials WHERE Active=1 AND Day = 1";
	if(!($rt_ActiveSpecials = mysql_query($sql_AllActive)))
	{
		echo "Query error<br>";
	}
	while($ActiveSpecial = mysql_fetch_array($rt_ActiveSpecials))
	{
		echo "<span class=\"Menu_Items\">".$ActiveSpecial['Name']."</span><span class=\"BodyType\"> ".$ActiveSpecial['Description']." - ".$ActiveSpecial['Price']."</span>";
	} 
?>
      <p class="HighlightBodyType">Tuesday</p>
<?
	$sql_AllActive = "SELECT * FROM Specials WHERE Active=1 AND Day = 2";
	if(!($rt_ActiveSpecials = mysql_query($sql_AllActive)))
	{
		echo "Query error<br>";
	}
	while($ActiveSpecial = mysql_fetch_array($rt_ActiveSpecials))
	{
		echo "<span class=\"Menu_Items\">".$ActiveSpecial['Name']."</span><span class=\"BodyType\"> ".$ActiveSpecial['Description']." - ".$ActiveSpecial['Price']."</span>";
	} 
?>
      <p class="HighlightBodyType">Wednesday</p>
<?
	$sql_AllActive = "SELECT * FROM Specials WHERE Active=1 AND Day = 3";
	if(!($rt_ActiveSpecials = mysql_query($sql_AllActive)))
	{
		echo "Query error<br>";
	}
	while($ActiveSpecial = mysql_fetch_array($rt_ActiveSpecials))
	{
		echo "<span class=\"Menu_Items\">".$ActiveSpecial['Name']."</span><span class=\"BodyType\"> ".$ActiveSpecial['Description']." - ".$ActiveSpecial['Price']."</span>";
	} 
?>
      <p class="HighlightBodyType">Thursday</p>
<?
	$sql_AllActive = "SELECT * FROM Specials WHERE Active=1 AND Day = 4";
	if(!($rt_ActiveSpecials = mysql_query($sql_AllActive)))
	{
		echo "Query error<br>";
	}
	while($ActiveSpecial = mysql_fetch_array($rt_ActiveSpecials))
	{
		echo "<span class=\"Menu_Items\">".$ActiveSpecial['Name']."</span><span class=\"BodyType\"> ".$ActiveSpecial['Description']." - ".$ActiveSpecial['Price']."</span>";
	} 
?>
      <p class="HighlightBodyType">Friday</p>
<?
	$sql_AllActive = "SELECT * FROM Specials WHERE Active=1 AND Day = 5";
	if(!($rt_ActiveSpecials = mysql_query($sql_AllActive)))
	{
		echo "Query error<br>";
	}
	while($ActiveSpecial = mysql_fetch_array($rt_ActiveSpecials))
	{
		echo "<span class=\"Menu_Items\">".$ActiveSpecial['Name']."</span><span class=\"BodyType\"> ".$ActiveSpecial['Description']." - ".$ActiveSpecial['Price']."</span>";
	} 
?>
      <p class="HighlightBodyType">Saturday</p>
<?
	$sql_AllActive = "SELECT * FROM Specials WHERE Active=1 AND Day = 6";
	if(!($rt_ActiveSpecials = mysql_query($sql_AllActive)))
	{
		echo "Query error<br>";
	}
	while($ActiveSpecial = mysql_fetch_array($rt_ActiveSpecials))
	{
		echo "<p><span class=\"Menu_Items\">".$ActiveSpecial['Name']."</span><span class=\"BodyType\"> ".$ActiveSpecial['Description']." - ".$ActiveSpecial['Price']."</span></p>";
	} 
?>
      <p class="HighlightBodyType">Sunday</p>
<?
	$sql_AllActive = "SELECT * FROM Specials WHERE Active=1 AND Day = 7";
	if(!($rt_ActiveSpecials = mysql_query($sql_AllActive)))
	{
		echo "Query error<br>";
	}
	while($ActiveSpecial = mysql_fetch_array($rt_ActiveSpecials))
	{
		echo "<span class=\"Menu_Items\">".$ActiveSpecial['Name']."</span><span class=\"BodyType\"> ".$ActiveSpecial['Description']." - ".$ActiveSpecial['Price']."</span>";
	} 
*/
?>
<?
	mysql_close();
?>     
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
