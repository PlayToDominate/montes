<?
session_start();
if(!isset($_SESSION['Admin_check']))
{
	header("location: ./admin.php");
}
//Check that they have the security permission to change the menu
//Connect to db
include('../dbconnect.php');
$sql_specials_Check = "SELECT * FROM Admin WHERE User='".mysql_real_escape_string($_SESSION['Admin_check'])."' AND Jobs = '1'";
$rt_User_Check = mysql_query($sql_specials_Check);
if(!$User = mysql_fetch_array($rt_User_Check))
{
	$Error = urlencode("Sorry you don't have permissions to access this part of the site");
	header("location: ./Options.php?Error=".$Error);	
}

$Success = false;
//Check to see if new special is being uploaded
if(isset($_POST['Submit']))
{
	//include database connection
	include('dbconnect.php');
	//clean Data
	$Category = mysql_real_escape_string($_POST['Category']);
		
	$sql_InsertSpecial = "INSERT INTO Job_Cats (Category) VALUES ('".$Category."')";
	//Put into Database
	if(!mysql_query($sql_InsertSpecial))
	{	//Issue error if one happens
		$Error = "A problem occured will trying to insert the information into the database: " . mysql_error();
	}else
	{
		$Success = true;
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
      <p align="center"> <span class="HighlightBodyType"><a href="/setmenu/jobs/Add_Jobs.php">Add Job</a> || <a href="/setmenu/jobs/Add_Category.php">Add Category</a> || <a href="/setmenu/jobs/List_Active_Jobs.php">List Active</a> || <a href="/setmenu/jobs/Activate_Jobs.php">Activate/Inactivate</a> || <a href="/setmenu/jobs/Delete_Jobs.php">Delete</a> || <a href="/setmenu/jobs/Modify_Jobs.php">Modify</a> </span></p>
      <p class="Menu_Item_Header">ADD JOB CATEGORY</p>
	  Current Categories:<br />
      <?
			$sql_Cats = "SELECT * FROM Job_Cats";
			$rts_Cats = mysql_query($sql_Cats);
			while($Category = mysql_fetch_array($rts_Cats))
			{
				echo $Category['Category']."<br>\n\r";
			}		
	  ?>
      <form id="Add_Job" name="Add_Job" method="post" action="<? echo $_SERVER['PHP_SELF']?>">
        <p>Job Category:
          <input type="text" name="Category" id="Category" />
        </p>
        <p>
          <input type="submit" name="Submit" id="Submit" value="Add Job Category" />
        </p>
      </form>
      
      <p>
        <?
mysql_close();
?>
      </p>
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
