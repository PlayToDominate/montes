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
$change = "List";

//Check to see if Job needs modifying 

if(isset($_POST['ChangeMe']))
{
	$sql_ToChange = "Select * FROM Jobs WHERE id=".mysql_real_escape_string($_POST['ChangeMe_id']);
	$rts_ToChange = mysql_query($sql_ToChange);
	if(!$ToChange = mysql_fetch_array($rts_ToChange))
	{
		echo $sql_ToChange."<br>";
		echo "Mysql Error: ".mysql_error()."<br>";
		$error = "couldn't get Special";
	}else
	{
		$change = "ChangeMe";
	}
}

if(isset($_POST['Submit']))
{
	//include database connection
	//clean Data
	$Title = mysql_real_escape_string($_POST['Title']);
	$Des = mysql_real_escape_string($_POST['Description']);
	$Wage = mysql_real_escape_string($_POST['Wage']);
	$Category = mysql_real_escape_string($_POST['Category']);
	$Weight = mysql_real_escape_string($_POST['Weight']);
	$Active = mysql_real_escape_string($_POST['Activate']);
		
	$sql_InsertSpecial = "UPDATE Jobs SET Title='".$Title."' ,Description='".$Des."' ,Wage='".$Wage."' ,Category='".$Category."' ,Active='".$Active."' ,Weight='".$Weight."' WHERE id='".mysql_real_escape_string($_POST['id'])."'";
	//Put into Database
	if(!mysql_query($sql_InsertSpecial))
	{	//Issue error if one happens
		$Error = "A problem occured will trying to insert the information into the database: " . mysql_error();
	}else
	{
		$Success = true;
	}
}
function printJobs($link)
{
//Select all active jobs
	$Category = 'Nothing';
	$sql_Jobs = "SELECT jc.Category,j.id,j.Title,j.Description,j.Wage FROM Jobs as j LEFT JOIN Job_Cats as jc ON j.Category=jc.id ORDER BY Category";
	$rts_Jobs = mysql_query($sql_Jobs,$link);
	while($Job = mysql_fetch_array($rts_Jobs))
	{
		if($Category != $Job['Category'])
		{
			$Category = $Job['Category'];
			echo "<p class='Job_Cat'>".$Category."</p>";
		}
		$Description = str_replace("\n","<br>",$Job['Description']);
		echo "<p>";
		echo "<form id=\"change_Job\" name=\"change_Job\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\" >";
		echo "<input type=\"hidden\" name=\"ChangeMe_id\" id=\"ChangeMe_id\" value=\"".$Job['id']."\" />";
		echo "<span class='Job_Title'>".$Job['Title']."</span><br>";
		echo "<span class='Job_Description'>".$Description."</span><br>";
		echo "<span class='Job_Wage'>".$Job['Wage']."</span>";
		echo "<br><input name=\"ChangeMe\" type=\"submit\" id=\"ChangeMe\" value=\"ChangeMe\" />";
		echo "</form>";
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
<?
	
	switch($change)
	{
		case 'Changed':
?>
		<p align="center" class="Menu_Item_Header"> Modifed Job</p>
        <?
		 //chose day, print our special..
			$sql_AllActive = "SELECT * FROM Specials WHERE id = ".mysql_real_escape_string($_POST['id']);
			if(!($rt_ActiveSpecials = mysql_query($sql_AllActive)))
			{
				echo "Query error<br>" . mysql_error();
			}
			if($ActiveSpecial = mysql_fetch_array($rt_ActiveSpecials))
			{
				echo "<p class=\"HighlightBodyType\">";			
				echo "</p>";
				PrintSpecials($ActiveSpecial['Day'], $link);
			}else
			{
				echo "<p><span class=\"BodyType\"> Job with that id could not be found.</span></p>";
			}
			echo "<p><a href=\"Modify_Jobs.php\">Modify another Job</a></p>";
		?>			
<?
			break;
		case 'ChangeMe':
?>

      <p class="Menu_Item_Header">Modify JOB</p>
      <form id="Modify_Job" name="Modify_Job" method="post" action="<? echo $_SERVER['PHP_SELF']?>">
  		<input type="hidden" name="id" id="id" value="<? echo $ToChange['id'] ?>" />
        <p>Job Title:
          <input type="text" name="Title" id="Title" value="<? echo $ToChange['Title']?>" />
        </p>
        <p>Job Description:
          <br />
          <textarea name="Description" id="Description" cols="45" rows="5"><? echo $ToChange['Description']?></textarea>
        </p>
        <p>Compensation:
          <input type="text" name="Wage" id="Wage" value="<? echo $ToChange['Wage']?>"/>
        </p>
        <p>Job Category:
          <select name="Category" id="Category">
		<?
			$sql_Cats = "SELECT * FROM Job_Cats";
			$rts_Cats = mysql_query($sql_Cats);
			while($Category = mysql_fetch_array($rts_Cats))
			{
				echo "<option value=\"".$Category['id']."\" ";
				if($Category['id'] == $ToChange['Category'])
				{	
					echo "selected=\"selected\" ";
				}
				echo ">".$Category['Category']."</option>\n\r";
			}
		?>
            </select>
        </p>
        <p>Order
          <select name="Weight" id="Weight">
		<?
			for($i = 1; $i <=10; $i++)
			{
				echo "<option value=\"".$i."\" ";
				if($i == $ToChange['Weight'])
				{	
					echo "selected=\"selected\" ";
				}
				echo ">".$i."</option>\n\r";
			}
		?>
            </select>
          (1 to 10, 1 is higher up the list)</p>
        <!--        <p>Which meals:
            <select name="Meals" id="Meals">
              <option value="1">Breakfast</option>
              <option value="2">Lunch</option>
              <option value="3">Dinner</option>	
            </select>
          </p>
-->
        <p>Activate Now?<br />
            <label>
            <input type="radio" name="Activate" value="1" id="Activate_0" <? if($ToChange['Active'] == 1){ echo "checked=\"checked\""; }?> />
              Yes</label>
            <br />
            <label>
            <input name="Activate" type="radio" id="Activate_1" value="0" <? if($ToChange['Active'] == 0){ echo "checked=\"checked\""; }?> />
              No</label>
        </p>
        <p>
          <input type="submit" name="Submit" id="Submit" value="Modify Job" />
        </p>
      </form>
<?
			break;
		case 'List':
		default:
			echo "<p class=\"Menu_Item_Header\">Modify JOB</p>";
			printJobs($link);			
			break;
	}
?>
      <p>
        <?
mysql_close();
?>
      </p>
      <p>&nbsp;        </p>
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
