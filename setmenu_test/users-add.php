<?
session_start();
if(!isset($_SESSION['Admin_check']))
{
	header("location: ../admin.php");
}
//Check that they have the security permission to change the menu
//Connect to db
include('../dbconnect.php');
$sql_specials_Check = "SELECT * FROM Admin WHERE User='".mysql_real_escape_string($_SESSION['Admin_check'])."' AND Users = '1'";
$rt_User_Check = mysql_query($sql_specials_Check);
if(!$User = mysql_fetch_array($rt_User_Check))
{
	$Error = urlencode("Sorry you don't have permissions to access this part of the site");
	header("location: ../Options.php?Error=".$Error);	
}


//Check to see if new special is being uploaded
if(isset($_POST['Submit']))
{
	//clean Data
	$Name = mysql_real_escape_string($_POST['Name']);
	$Password = mysql_real_escape_string($_POST['Password']);
	if(isset($_POST['Jobs']))
	{
		$Jobs = '1';
	}else
	{
		$Jobs = '0';
	}
	if(isset($_POST['Events']))
	{
		$Events = '1';
	}else
	{
		$Events = '0';
	}
	if(isset($_POST['Specials']))
	{
		$Specials = '1';
	}else
	{
		$Specials = '0';
	}
	if(isset($_POST['Users']))
	{
		$Users = '1';
	}else
	{
		$Users = '0';
	}
	$sql_InsertSpecial = "INSERT INTO Admin (User, Pass,Jobs,Events,Specials,Users) VALUES ('".$Name."','".$Password."','".$Jobs."','".$Events."','".$Specials."','".$Users."')";
	//Put into Database
	if(!mysql_query($sql_InsertSpecial))
	{	//Issue error if one happens
		$Error = "A problem occured will trying to insert the information into the database: " . mysql_error();
	}
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
      <p align="center"> <span class="HighlightBodyType"><a href="/setmenu/Users/Add_Users.php">Add</a> || <a href="/setmenu/Users/Delete_Users.php">Delete</a> || <a href="/setmenu/Users/Modify_Users.php">Modify</a> ||<a href="/setmenu/Logout.php">Logout</a></span></p>
<?
	if(isset($Error))
	{
		echo "<p class=Error_msg>".$Error."</p>";
	}
?>
	  <p align="center" class="Menu_Item_Header">Add Users</p>
   <form id="Add_Special" name="Add_Special" method="post" action="<? echo $_SERVER['../PHP_SELF']?>">
         <p class="BodyType">Username:
         <input type="text" name="Name" id="Name" />
         </p>
          <p class="BodyType">Password:
          <input type="password" name="Password" id="Password" />
          </p>
          <p class="BodyType">Able to Modify:<br />
	      <input type="checkbox" name="Jobs" id="Jobs" /> 
		  Jobs<br />
          <input type="checkbox" name="Events" id="Events" /> 
          Events<br />
          <input type="checkbox" name="Specials" id="Specials" /> 
          Specials<br />
          <input type="checkbox" name="Users" id="Users" /> 
          Users</p>
          <p>
            <input type="submit" name="Submit" id="Submit" value="Add Users" />
      </p>
	  </form>
      <p>&nbsp;</p>
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
