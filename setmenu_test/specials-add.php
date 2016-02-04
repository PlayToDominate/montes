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


//Check to see if new special is being uploaded
if(isset($_POST['Submit']))
{
	//clean Data
	$name = mysql_real_escape_string($_POST['name']);
	$Des = mysql_real_escape_string($_POST['Description']);
	$Price = mysql_real_escape_string($_POST['Price']);
	//$Day = mysql_real_escape_string($_POST['DayOfWeek']);
	$Meal = mysql_real_escape_string($_POST['Meals']);
	$Weight = mysql_real_escape_string($_POST['Weight']);
	$Active = mysql_real_escape_string($_POST['Activate']);
	$dateactive_orig = mysql_real_escape_string($_POST['dateactive']);

	list($month, $day, $year) = split('/', $_POST['dateactive']);
	$dateactive_new = date("Y-m-d H:i:s", mktime(23, 59, 0, $month, $day, $year));

	$sql_InsertSpecial = "INSERT INTO Specials (name,Description,Price,Meal,Active,Weight,dateactive,DateAdded) VALUES ('".$name."','".$Des."','".$Price."','".$Meal."','".$Active."','".$Weight."','".$dateactive_new."',NOW())";
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
      <?
	if(isset($Error))
	{
		echo "<p class=Error_msg>".$Error."</p>";
	}
?>
	  <p align="center" class="Menu_Item_Header">Add Specials</p>
   <form id="Add_Special" name="Add_Special" method="post" action="<? echo $_SERVER['PHP_SELF']?>">
         <p>Meals Name:
           <select name="name" id="name" />
						<option value="Breakfast Special">Breakfast Special</option>
						<option value="Lunch Special">Lunch Special</option>
						<option value="Lunch Salad Bar">Lunch Salad Bar</option>
						<option value="Today's Soup">Today's Soup</option>
						<option value="Dinner Special">Dinner Special</option>
					</select>
         </p>
          <p>Description of Meal:<br />
			<textarea name="Description" id="Description" cols="45" rows="5"></textarea>
          </p>
          <p>Price:
            <input type="text" name="Price" id="Price" />
          </p>
          <p>Date Active (ex. 01/31/2012):
            <input type="text" name="dateactive" id="dateactive"/>
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
          <p>Activate Now?<br />
          <label>
<input type="radio" name="Activate" value="1" id="Activate_0" />
            Yes</label>
          <br />
          <label>
<input name="Activate" type="radio" id="Activate_1" value="0" checked="checked" />
            No</label>
          </p>
            <p>
              <input type="submit" name="Submit" id="Submit" value="Add Special" />
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
