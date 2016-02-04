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


if(isset($_POST['submit']))
{
	//Change Active Specials
	$sql_GetJobs = "SELECT * FROM Jobs";
	$rt_Jobs = mysql_query($sql_GetJobs);
	while($Jobs = mysql_fetch_array($rt_Jobs))
	{
		if(isset($_POST['Job'.$Jobs['id']]))
		{
			$sql_ChangeActive = "DELETE FROM Jobs WHERE id=".$Jobs['id'];
			mysql_query($sql_ChangeActive);	
		}
	}
}

function printActiveJobs($link)
{
//Select all active jobs
	$Category = 'Nothing';
	$sql_Jobs = "SELECT jc.Category,j.id,j.Title,j.Description,j.Wage,j.Active FROM Jobs as j LEFT JOIN Job_Cats as jc ON j.Category=jc.id WHERE ACTIVE = '0' ORDER BY Category";
	$rts_Jobs = mysql_query($sql_Jobs,$link);
	while($Job = mysql_fetch_array($rts_Jobs))
	{
		if($Category != $Job['Category'])
		{
			$Category = $Job['Category'];
			echo "<p class='Job_Cat'>".$Category."</p>";
		}
		$Description = str_replace("\n","<br>",$Job['Description']);
		echo "<p><input name=\"Job".$Job['id']."\" type=\"checkbox\" id=\"Job".$Job['id']."\" />";
		echo " <span class='Job_Title'>".$Job['Title']."</span><br>";
		echo "<span class='Job_Description'>".$Description."</span><br>";
		echo "<span class='Job_Wage'>".$Job['Wage']."</span>";
	    echo "</p>";
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
      <p class="Menu_Item_Header">Delete Jobs      </p>
      <p>
<form action="<? echo $_POST['PHP_SELF'] ?>" method="post" name="Delete" target="_self">
        <?
printActiveJobs($link);
mysql_close();
?>
<input name="submit" type="submit" value="Delete Jobs" />
</form>
      </p>
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
