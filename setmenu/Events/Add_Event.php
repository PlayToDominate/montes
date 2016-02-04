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

$Success = false;

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

//Check to see if new special is being uploaded
if(isset($_POST['Submit']))
{
	//include database connection
	include('../dbconnect.php');
	//clean Data
	$Title = mysql_real_escape_string($_POST['Title']);
	$Des = mysql_real_escape_string($_POST['Description']);
	$Year = mysql_real_escape_string($_POST['Year']);
	$Month = mysql_real_escape_string($_POST['Month']);
	$Day = mysql_real_escape_string($_POST['Day']);
	$Weight = mysql_real_escape_string($_POST['Weight']);
	$Active = mysql_real_escape_string($_POST['Activate']);
	$Recurr = mysql_real_escape_string($_POST['Recurr']);
	$R_Day = mysql_real_escape_string($_POST['R_Day']);
	$R_Month = mysql_real_escape_string($_POST['R_Month']);
	$R_Year = mysql_real_escape_string($_POST['R_Year']);
	switch($Recurr)
	{
		case 'Week':
			$sql_InsertEvent = "INSERT INTO Events (Title,Description,Month,Day,Year,Weight) VALUES ('".$Title."','".$Des."','".$Month."','".$Day."','".$Year."','".$Weight."')";
			//Put into Database
			if(!mysql_query($sql_InsertEvent))
			{	//Issue error if one happens
				$Error = "A problem occured will trying to insert the information into the database: " . mysql_error();
			}else
			{
				$Recurr_id = mysql_insert_id();
				$Month = GetMonth($Month);
				$Ref_Time = strtotime($Day.' '.$Month.' '.$Year);
				//Figure out how many weeks are between start date and end date
				$R_Month = GetMonth($R_Month);
				$End_Time = strtotime($R_Day.' '.$R_Month.' '.$R_Year);
				if($End_Time - $Ref_Time < 604800)
				{//Recurr untill time is set for less then a week, don't recurr
					$weeks = 0;
				}else
				{
					$weeks = ($End_Time - $Ref_Time)/604800;
					//set recurr id on the original record
					$sql_setRecurr="UPDATE Events SET recurr_id = '".$Recurr_id."' WHERE id='".$Recurr_id."'";
					mysql_query($sql_setRecurr);
				} 
				for($i = 1; $i <= $weeks; $i++)
				{
					$Ref_Time += 604800;
					$Day = date('j',$Ref_Time);
					$Month = date('m',$Ref_Time);
					$Year = date('Y',$Ref_Time);
					$sql_InsertEvent = "INSERT INTO Events (Title,Description,Month,Day,Year,recurr_id,Weight) VALUES ('".$Title."','".$Des."','".$Month."','".$Day."','".$Year."','".$Recurr_id."','".$Weight."')";
					mysql_query($sql_InsertEvent);
				}
				$Success = true;
			}
			break;
		default:
			$sql_InsertSpecial = "INSERT INTO Events (Title,Description,Month,Day,Year,Weight) VALUES ('".$Title."','".$Des."','".$Month."','".$Day."','".$Year."','".$Weight."')";
			//Put into Database
			if(!mysql_query($sql_InsertSpecial))
			{	//Issue error if one happens
				$Error = "A problem occured will trying to insert the information into the database: " . mysql_error();
			}else
			{
				$Success = true;
			}
			break;
	}
}
$now = getdate();
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
    <div class="MainVideoPane" >
      <p align="center"> <span class="HighlightBodyType"><a href="/setmenu/Events/Add_Event.php">Add Events</a> || <a href="/setmenu/Events/DisplayEvents.php">Display Events</a> || <a href="/setmenu/Events/Delete_Events.php">Delete</a> || <a href="/setmenu/Events/Modify_Events.php">Modify</a> || <a href="/setmenu/Logout.php">Logout</a></span></p>
      <p class="Menu_Item_Header">ADD EVENT</p>
      <?
	  	if($Success)
		{
			echo "This event was successfully added<br>";
			$Description = str_replace("\n","<br>",$_POST['Description']);
			echo "<p><span class='Job_Title'>".$_POST['Title']."</span><br>";
			echo "<span class='Job_Description'>".$Description."</span><br>";
			echo "<span class='Job_Wage'>".$_POST['Month']."/".$_POST['Day']."/".$_POST['Year']."</span>";
			echo "</p>";	
			echo "<hr>";		
		}
	  ?>
      <form id="Add_Job" name="Add_Job" method="post" action="<? echo $_SERVER['../jobs/PHP_SELF']?>">
        <p>Event Title:
          <input type="text" name="Title" id="Title" />
        </p>
        <p>Event Description:
          <br />
          <textarea name="Description" id="Description" cols="45" rows="5"></textarea>
        </p>
        <p>Date: 
          <select name="Month" id="Month">
              <option value="1" selected="selected">Jan</option>
              <option value="2">Feb</option>
              <option value="3">Mar</option>
              <option value="4">Apr</option>
              <option value="5">May</option>
              <option value="6">Jun</option>
              <option value="7">Jul</option>
              <option value="8">Aug</option>
              <option value="9">Sept</option>
              <option value="10">Oct</option>
              <option value="11">Nov</option>
              <option value="12">Dec</option>
            </select>
        /
        <select name="Day" id="Day">
          <option value="1" selected="selected">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>
          <option value="21">21</option>
          <option value="22">22</option>
          <option value="23">23</option>
          <option value="24">24</option>
          <option value="25">25</option>
          <option value="26">26</option>
          <option value="27">27</option>
          <option value="28">28</option>
          <option value="29">29</option>
          <option value="30">30</option>
          <option value="31">31</option>
        </select>
        /
        <select name="Year" id="Year">
        <?
        	echo "<option value=\"".$now['year']."\" selected=\"selected\">".$now['year']."</option>";
			$ct_i = 1;
			while($ct_i < 10)
			{
				$year = $now['year'] + $ct_i;
				echo "<option value=\"".year."\" >".$year."</option>";
				$ct_i++;
			}
		?>
        </select>
        </p>
        <p>Event Occurs every:<br />
          <input type="radio" name="Recurr" id="Recurr" value="Week" /> Week<br />
          <input type="radio" name="Recurr" id="Recurr" value="" checked="checked" /> Doesn't Recurr</p>
   		<p>Recurrs till: 
          <select name="R_Month" id="R_Month">
              <option value="1" selected="selected">Jan</option>
              <option value="2">Feb</option>
              <option value="3">Mar</option>
              <option value="4">Apr</option>
              <option value="5">May</option>
              <option value="6">Jun</option>
              <option value="7">Jul</option>
              <option value="8">Aug</option>
              <option value="9">Sept</option>
              <option value="10">Oct</option>
              <option value="11">Nov</option>
              <option value="12">Dec</option>
            </select>
        /
        <select name="R_Day" id="R_Day">
          <option value="1" selected="selected">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>
          <option value="21">21</option>
          <option value="22">22</option>
          <option value="23">23</option>
          <option value="24">24</option>
          <option value="25">25</option>
          <option value="26">26</option>
          <option value="27">27</option>
          <option value="28">28</option>
          <option value="29">29</option>
          <option value="30">30</option>
          <option value="31">31</option>
        </select>
        /
        <select name="R_Year" id="R_Year">
        <?
        	echo "<option value=\"".$now['year']."\" selected=\"selected\">".$now['year']."</option>";
			$ct_i = 1;
			while($ct_i < 10)
			{
				$year = $now['year'] + $ct_i;
				echo "<option value=\"".year."\" >".$year."</option>";
				$ct_i++;
			}
		?>
        </select>
        </p>        
        <p>Order
          <select name="Weight" id="Weight">
              <option value="1" selected="selected">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
          </select>
          (1 to 10, 1 is higher up the list)</p>
        <p>
          <input type="submit" name="Submit" id="Submit" value="Add Event" />
        </p>
      </form>
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
