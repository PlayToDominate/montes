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
	$id = mysql_real_escape_string($_POST['id']);
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
	$sql_Update_User = "UPDATE Admin  SET User='".$Name."',";
	if($_POST['Password'] != '')
	{
		$sql_Update_User .= "Pass='".$Password."', ";
	}
	$sql_Update_User .= "Jobs='".$Jobs."',Events='".$Events."',Specials='".$Specials."',Users='".$Users."' WHERE Id='".$id."'";
	//Put into Database
	if(!mysql_query($sql_Update_User))
	{	//Issue error if one happens
		$Error = "A problem occured will trying to insert the information into the database: " . mysql_error();
	}
}

if(isset($_GET['User_id']))
{
	$Mode = 'Modify';
	$sql_userinfo = "SELECT * FROM Admin WHERE id='".mysql_real_escape_string($_GET['User_id'])."'";
	$rts_userinfo = mysql_query($sql_userinfo);
}else
{
	$Mode = 'List';
	$sql_AllUsers = "SELECT * FROM Admin";
	$rts_userinfo = mysql_query($sql_AllUsers);
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
switch($Mode)
{
	case 'Modify':
		if($user = mysql_fetch_array($rts_userinfo))
		{
?>
	  <p align="center" class="Menu_Item_Header">Modify Users</p>
   <form id="Modify_User" name="Modify_User" method="post" action="<? echo $_SERVER['../PHP_SELF']?>">
   		<input type="hidden" name="id" value="<? echo $_GET['User_id']?>" />
         <p class="BodyType">Username:
         <input type="text" name="Name" id="Name" value="<? echo $user['User']?>" />
         </p>
          <p class="BodyType">Password:
            <input type="password" name="Password" id="Password" />
          </p>
    <p class="BodyType">Able to Modify:<br />
	      <input type="checkbox" name="Jobs" id="Jobs" <? if($user['Jobs'] =='1'){ echo "checked=checked";} ?>/> 
		  Jobs<br />
          <input type="checkbox" name="Events" id="Events" <? if($user['Events'] =='1'){ echo "checked=checked";} ?>/> 
          Events<br />
          <input type="checkbox" name="Specials" id="Specials" <? if($user['Specials'] =='1'){ echo "checked=checked";} ?>/> 
          Specials<br />
          <input type="checkbox" name="Users" id="Users" <? if($user['Users'] =='1'){ echo "checked=checked";} ?>/> 
          Users</p>
          <p>
            <input type="submit" name="Submit" id="Submit" value="Modify User" />
      </p>
	  </form>
      
      <?
	  }else
	  {
	  	echo "Sorry that user id doesn't exist<br />";
	  }
	  ?>
      <p>&nbsp;</p>
<?
		break;
	case 'List':
	default:
?>
	  <p align="center" class="Menu_Item_Header">Modify Users (List)</p>
      <?		while($user = mysql_fetch_array($rts_userinfo))
		{
?>
		<p class="BodyType"><a href="<? echo $_SERVER['PHP_SELF']?>?User_id=<? echo $user['Id']?>"><? echo $user['User']?></a> Can Access: 
        <?
			if($user['Jobs'] == '1')
			{
				echo "Jobs ";
			}
			if($user['Events'] == '1')
			{
				echo "Events ";
			}
			if($user['Specials'] == '1')
			{
				echo "Specials ";
			}
			if($user['Users'] == '1')
			{
				echo "Users ";
			}
?>
		</p>
<?
		}
		break;
}     
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
