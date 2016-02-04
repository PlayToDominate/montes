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

$change = "List";  // This var will hold what the page should do.
					// List mean list the current specials with a button that says change me
					// ChangeMe Says print out the options for change on the id we have
					// Changed Says We have successfully changed the special
if(isset($_POST['ChangeMe'])){
	$sql_ToChange = "Select * FROM Specials WHERE id=".mysql_real_escape_string($_POST['ChangeMe_id']);
	$rts_ToChange = mysql_query($sql_ToChange);
	if(!$ToChange = mysql_fetch_array($rts_ToChange)){
		echo $sql_ToChange."<br>";
		echo "Mysql Error: ".mysql_error()."<br>";
		$error = "couldn't get Special";
	} else {
		$change = "ChangeMe";
	}
}
//Check to see if new special is being uploaded
if(isset($_POST['Change']))
{
	//clean Data
	$id = mysql_real_escape_string($_POST['id']);
	$name = mysql_real_escape_string($_POST['name']);
	$Des = mysql_real_escape_string($_POST['Description']);
	$Price = mysql_real_escape_string($_POST['Price']);
	//$Day = mysql_real_escape_string($_POST['DayOfWeek']);
	//$Meal = mysql_real_escape_string($_POST['Meals']);
	$Weight = mysql_real_escape_string($_POST['Weight']);
	$Active = mysql_real_escape_string($_POST['Active']);
	$dateactive = mysql_real_escape_string($_POST['dateactive']);

	$sql_UpdateSpecial = "UPDATE Specials SET name='".$name."',Description='".$Des."',Price='".$Price."',Day='".$Day."',Active='".$Active."',Weight='".$Weight."',dateactive='".$dateactive."',DateAdded=NOW() WHERE id=".$id;
	//Put into Database
	if(!mysql_query($sql_UpdateSpecial))
	{	//Issue error if one happens
		$error = "A problem occured will trying to insert the information into the database: " . mysql_error();
	}else
	{
		$change = "Changed";
	}
}

$daily_specials = mysql_query("
	SELECT id,Name,Description,Price,Day,Meal,Active,Weight,dateactive
	FROM Specials
	WHERE Active = '1'
	ORDER BY dateactive ASC, Weight ASC
	")
	or die(mysql_error());

function PrintSpecials($id, $link){
	$NoSpecials = true;
	$sql_AllActive = "SELECT * FROM Specials WHERE id=".$id;
	if(!($rt_ActiveSpecials = mysql_query($sql_AllActive,$link))){
		echo "Query error<br>" . mysql_error($link);
	}
	echo "<span class=\"BodyType\"><a href=\"Add_Specials.php?Day=".$Day."\"> Add a Special</a></span>";
}

$update_week = mysql_query("SELECT distinct(dateactive) FROM Specials where dateactive > 0 ORDER BY dateactive DESC")
or die(mysql_error());
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
				// if error print it
				switch($change){
				case 'Changed':
			?>
<!-- This displays after updating a special -->
			<p align="center" class="Menu_Item_Header"> Modifed Special</p>
      <?//chose day, print our special..
			$sql_AllActive = "SELECT * FROM Specials WHERE id = ".mysql_real_escape_string($_POST['id']);
			if(!($rt_ActiveSpecials = mysql_query($sql_AllActive))){
				echo "Query error<br>" . mysql_error();
			}
			if($ActiveSpecial = mysql_fetch_array($rt_ActiveSpecials)){
				echo "<p class=\"HighlightBodyType\">";
				switch($ActiveSpecial['Day']){
					case 1:
						echo "Monday";
						break;
					case 2:
						echo "Tuesday";
						break;
					case 3:
						echo "Wednesday";
						break;
					case 4:
						echo "Thursday";
						break;
					case 5:
						echo "Friday";
						break;
					case 6:
						echo "Saturday";
						break;
					case 7:
						echo "Sunday";
						break;
					case 8:
						echo "Weekend";
						break;
				}
				echo "</p>";
				PrintSpecials($ActiveSpecial['id'], $link);
				//			echo "<p><span class=\"Menu_Items\">".$ActiveSpecial['Name']."</span><span class=\"BodyType\"> ".$ActiveSpecial['Description']." - ".$ActiveSpecial['Price']."</span></p>";
			} else {
				echo "<p><span class=\"BodyType\"> Special with that id could not be found.</span></p>";
			}
			?>
			<p><a href="Modify_Special.php">Modify another special</a></p>
			<p><span class="Menu_Items"><?=$ActiveSpecial['Name']?></span><span class="BodyType"> <?=$ActiveSpecial['Description']?> - <?=$ActiveSpecial['Price']?></span></p>
			<?
						break;
					case 'ChangeMe':
			?>
<!-- This modifies individual specials -->
	  <p align="center" class="Menu_Item_Header">Modify Special</p>
		<form id="Add_Special" name="Add_Special" method="post" action="<?$_SERVER['PHP_SELF']?>">
  		<input type="hidden" name="id" id="id" value="<? echo $ToChange['id'] ?>" />
         <p>Meals Name:
           <!--<input type="text" name="Name" id="Name" value="<? echo $ToChange['Name']?>" />-->
					<select name="name" id="name">
						<option value="Breakfast Special" <? if($ToChange['name'] == "Breakfast Special"){?>selected="selected"<?}?>>Breakfast Special</option>
						<option value="Lunch Special" <? if($ToChange['name'] == "Lunch Special"){?>selected="selected"<?}?>>Lunch Special</option>
						<option value="Lunch Salad Bar" <? if($ToChange['name'] == "Lunch Salad Bar"){?>selected="selected"<?}?>>Lunch Salad Bar</option>
						<option value="Today's Soup" <? if($ToChange['name'] == "Today's Soup"){?>selected="selected"<?}?>>Today's Soup</option>
						<option value="Dinner Special" <? if($ToChange['name'] == "Dinner Special"){?>selected="selected"<?}?>>Dinner Special</option>
					</select>
         </p>
          <p>Description of Meal:<br />
            <textarea name="Description" id="Description" cols="45" rows="5"><? echo $ToChange['Description']?></textarea>
          </p>
          <p>Price:
            <input type="text" name="Price" id="Price" value="<? echo $ToChange['Price']?>" />
          </p>
          <p>Date Active:
          	<select name="dateactive">
							<?php
							 while($info = mysql_fetch_array($update_week)) {
								$date = $info['dateactive'];
								$pretty_date = date('m/d/Y',strtotime($date));
							?>
							<option value="<?=$info['dateactive']?>" <? if($ToChange['dateactive'] == $info['dateactive']){?>selected="selected"<?}?>><?=$pretty_date?></option>
							<?}?>
          	</select>
          </p>
          <!--
          <p>Day of week:
            <select name="DayOfWeek" id="DayOfWeek">
              <option value="1" <? if($ToChange['Day'] == 1){ echo "selected=\"selected\""; }?>>Monday</option>
              <option value="2" <? if($ToChange['Day'] == 2){ echo "selected=\"selected\""; }?>>Tuesday</option>
              <option value="3" <? if($ToChange['Day'] == 3){ echo "selected=\"selected\""; }?>>Wednesday</option>
              <option value="4" <? if($ToChange['Day'] == 4){ echo "selected=\"selected\""; }?>>Thursday</option>
              <option value="5" <? if($ToChange['Day'] == 5){ echo "selected=\"selected\""; }?>>Friday</option>
              <option value="6" <? if($ToChange['Day'] == 6){ echo "selected=\"selected\""; }?>>Saturday</option>
              <option value="7" <? if($ToChange['Day'] == 7){ echo "selected=\"selected\""; }?>>Sunday</option>
              <option value="8" <? if($ToChange['Day'] == 8){ echo "selected=\"selected\""; }?>>Weekend</option>
            </select>
          </p>
          -->
          <p>Order
            <select name="Weight" id="Weight">
              <option value="1" <? if($ToChange['Weight'] == 1){ echo "selected=\"selected\""; }?>>1</option>
              <option value="2" <? if($ToChange['Weight'] == 2){ echo "selected=\"selected\""; }?>>2</option>
              <option value="3" <? if($ToChange['Weight'] == 3){ echo "selected=\"selected\""; }?>>3</option>
              <option value="4" <? if($ToChange['Weight'] == 4){ echo "selected=\"selected\""; }?>>4</option>
              <option value="5" <? if($ToChange['Weight'] == 5){ echo "selected=\"selected\""; }?>>5</option>
              <option value="6" <? if($ToChange['Weight'] == 6){ echo "selected=\"selected\""; }?>>6</option>
              <option value="7" <? if($ToChange['Weight'] == 7){ echo "selected=\"selected\""; }?>>7</option>
              <option value="8" <? if($ToChange['Weight'] == 8){ echo "selected=\"selected\""; }?>>8</option>
              <option value="9" <? if($ToChange['Weight'] == 9){ echo "selected=\"selected\""; }?>>9</option>
              <option value="10" <? if($ToChange['Weight'] == 10){ echo "selected=\"selected\""; }?>>10</option>
            </select>
          	(1 to 10, 1 is higher up each day)
         </p>
          <p>
						Activate Now?<br />
						<label><input type="radio" name="Active" id="Activate_0" value="1" <? if($ToChange['Active'] == 1){ echo "checked=\"checked\""; }?> />Yes</label>
						<br />
						<label><input type="radio" name="Active" id="Activate_1" value="0" <? if($ToChange['Active'] == 0){ echo "checked=\"checked\""; }?> />No</label>
          </p>
          <p><input type="submit" name="Change" id="Change" value="Modify Special" /></p>
	  		</form>
    	  <p>&nbsp;</p>
				<?
							break;
						case 'List':
						default:

				?>
<!-- This is the default List -->
			<?php
				$previous = '';
				while($info = mysql_fetch_array( $daily_specials)) {
				$date = $info['dateactive'];
				$unix_date = strtotime ($date);
				if ($unix_date >= time() ) {

			?>
			<?if ( $unix_date != $previous) {?>
			<p class="Menu_Items"><?=date("l, F jS", $unix_date)?></p>
			<?}?>
			<form id="change_Special" name="change_Special" method="post" action="<?=$_SERVER['PHP_SELF']?>" >
			<input type="hidden" name="ChangeMe_id" id="ChangeMe_id" value="<?=$info['id']?>" />
			<input name="ChangeMe" type="submit" id="ChangeMe" value="ChangeMe" />
			<p><span class="HighlightBodyType"><?=$info['Name']?><br></span><span class="BodyType"> <?=$info['Description']?><?if($info['Price'] !='N/A'){?> - <?=$info['Price']?><?}?></span></p>
			</form>
			<?
			$previous = $unix_date;
			}}?>

<?
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
