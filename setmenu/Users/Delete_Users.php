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
	$id = mysql_real_escape_string($_POST['id']);
	$sql_Delete_User = "DELETE FROM Admin WHERE id ='".$id."'";
//	echo "SQL: $sql_Delete_User <Br>";
	//Put into Database
	if(!mysql_query($sql_Delete_User))
	{	//Issue error if one happens
		$Error = "A problem occured will trying to insert the information into the database: " . mysql_error();
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
	  <p align="center" class="Menu_Item_Header">Delete Users</p>
      <?
	  	$sql_Delete_users_list = "SELECT * FROM Admin";
		$rts_users = mysql_query($sql_Delete_users_list);
		while($user = mysql_fetch_array($rts_users))
		{
		?>
   <form id="Delete_User" name="Delete_User" method="post" action="<? echo $_SERVER['../PHP_SELF']?>">
		<input type="hidden" name="id" value="<? echo $user['Id'] ?>" />
         <p class="BodyType"><? echo $user['User']?> <input type="submit" name="Submit" id="Submit" value="Delete this User" />
      </p>
	  </form>
      <?
	  	}
	mysql_close();
	?>
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
