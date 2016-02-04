<?
session_start();
if(!isset($_SESSION['Admin_check']))
{
	header("location: ../admin.php");
}
//Check that they have the security permission to change the menu
//Connect to db
include('../dbconnect.php');
?>
<?php
	// Get values from form
	$special_id = mysql_real_escape_string($_POST['special_id']);
	$special_type_id = mysql_real_escape_string($_POST['special_type_id']);
	$price = mysql_real_escape_string($_POST['price']);
	$name = mysql_real_escape_string($_POST['name']);
	$repeat_day = mysql_real_escape_string($_POST['repeat_day']);
	$fg_repeat = mysql_real_escape_string($_POST['fg_repeat']);
	$description = mysql_real_escape_string($_POST['description']);

if($special_id){
	if($repeat_day){
		$sql="UPDATE special SET repeat_day='".$repeat_day."', fg_repeat='".$fg_repeat."' WHERE special_id=".$special_id."";
		//Put into Database
		if(!mysql_query($sql))	{	//Issue error if one happens
			echo "There was a problem. Please try again.";
			echo $sql;
		}else	{
			echo "<p id=\"response\" style=\"color:red;\">Saved Repeating Day as $repeat_day</p>";
		}

	} else {
		$sql="UPDATE special SET special_type_id=".$special_type_id.", price='".$price."', name='".$name."', description='".$description."', fg_repeat='N', repeat_day='N/A' WHERE special_id=".$special_id."";
		//Put into Database
		if(!mysql_query($sql))	{	//Issue error if one happens
			echo "There was a problem. Please try again.";
			echo $sql;
		}else	{
			echo "<p id=\"response\" style=\"color:red;\">Special sucessfully updated.</p>";
		}
	}
} else {
	$sql="INSERT INTO special(special_type_id, price, name, description,repeat_day, fg_repeat)VALUES(".$special_type_id.",'".$price."','".$name."','".$description."','N/A', 'N')";
	//Put into Database
	if(!mysql_query($sql))	{	//Issue error if one happens
		echo "There was a problem. Please try again.";
		echo $sql;
	}else	{
		$special_id = mysql_insert_id();

	$daily_specials = mysql_query("
SELECT
special_type.description as st_description
FROM special, special_type
WHERE special.special_type_id = special_type.special_type_id AND special.special_id = $special_id
		")
		or die(mysql_error());
		while($info = mysql_fetch_array( $daily_specials)) {
		$st_description_bad=$info['st_description'];
		$st_description = str_replace("\\", "", $st_description_bad );
		}

?>

<tr bgcolor="#D3D3D3">
	<td id="type-<?=$special_id?>"><?=$st_description ?></td>
	<td id="name-<?=$special_id?>"><?=$name?></td>
	<td id="price-<?=$special_id?>"><?=$price?></td>
	<td id="desc-<?=$special_id?>"><?=$description?></td>
	<td>
		<!--
		<button id="add-date-<?=$special_id?>" class="add-date" value="<?=$special_id?>">Add Date(s)</button><br />
		<button id="add-repeat-date-btn<?=$special_id?>" class="add-repeat-date" value="<?=$special_id?>">Add Repeating Date</button><br />
		<button id="add-special-<?=$special_id?>" class="update-special" value="<?=$special_id?>">Update Special</button>
		-->
		&nbsp;
	</td>
</tr>
<tr>
	<td colspan="5">
		<div id="update-date-<?=$special_id?>" class="add-update">
			<?
			$specials_dates = mysql_query("
				SELECT
				special_date.special_date_id as special_date_id,
				special_start_date,
				special_end_date
				FROM special, special_date
				WHERE
				special_date.special_id = special.special_id
				AND special_date.special_id= $special_id
				ORDER BY special_start_date ASC
			")
			or die(mysql_error());

			while($info = mysql_fetch_array( $specials_dates)) {
				$date = $info['special_end_date'];
				$unix_end_date = strtotime ($date);
				$start_date = $info['special_start_date'];
				$unix_start_date = strtotime ($start_date);
				$current_date_id = $info['special_date_id'];
			?>
			<form name="update-date<?=$current_date_id?>" id="update-date<?=$current_date_id?>" method="post">
				<input type="hidden" name="special_end_date" value=""/>
				<input type="hidden" name="special_date_id" value="<?=$current_date_id?>"/>
				<?if ($unix_start_date >= time() ) {?>
				<input type="text" name="special_start_date" value="<?=date("m/d/Y", $unix_start_date)?>"/>
				<input type="submit" id="update-date-submit<?=$current_date_id?>" name="Submit" value="Update"/>
				<?} else {?>
				<input type="text" name="special_start_date" value="<?=date("m/d/Y", $unix_start_date)?>" disabled/>
				<input type="submit" id="update-date-submit<?=$current_date_id?>" name="Submit" value="Update" disabled/>
				<?}?>
				<!--<button id="delete-date-submit<?=$special_id?>" name="Delete" value="<?=$current_date_id?>">Delete</button>-->
				<br />
			</form>
			<?}?>
			<form name="add-date<?=$special_id?>" id="add-date<?=$special_id?>" method="post">
				<input type="hidden" name="special_end_date" id="special_end_date<?=$special_id?>" value=""/>
				<input type="hidden" name="special_id" id="special_id<?=$special_id?>" value="<?=$special_id?>"/>
				<input type="date" name="special_start_date" id="special_start_date<?=$special_id?>" value=""/>
				<input type="submit" class="add-date-submit" id="add-date-submit<?=$special_id?>" name="Submit" value="Save"/>
			</form>
		</div>
		<div id="add-repeat-date-<?=$special_id?>" class="add-update">
			<form name="add-repeat-date-form<?=$special_id?>" id="add-repeat-date-form<?=$special_id?>">
			<input type="hidden" name="special_id" value="<?=$special_id?>"/>
			<input type="hidden" name="fg_repeat" id="repeat<?=$special_id?>" value="<?=$special_repeat?>"/>
			<table>
				<tr>
					<td><label>Type</label><br />
						<input type="radio" name="repeat_day" value="N"<?if($special_repeat_day == 'N' || (!$special_repeat_day)){?> checked="checked"<?}?>/>N/A<br />
						<input type="radio" name="repeat_day" value="Sunday"<?if($special_repeat_day == 'Sunday'){?> checked="checked"<?}?>/>Sunday<br />
						<input type="radio" name="repeat_day" value="Monday"<?if($special_repeat_day == 'Monday'){?> checked="checked"<?}?>/>Monday<br />
						<input type="radio" name="repeat_day" value="Tuesday"<?if($special_repeat_day == 'Tuesday'){?> checked="checked"<?}?>/>Tuesday<br />
						<input type="radio" name="repeat_day" value="Wednesday"<?if($special_repeat_day == 'Wednesday'){?> checked="checked"<?}?>/>Wednesday<br />
						<input type="radio" name="repeat_day" value="Thursday"<?if($special_repeat_day == 'Thursday'){?> checked="checked"<?}?>/>Thursday<br />
						<input type="radio" name="repeat_day" value="Friday"<?if($special_repeat_day == 'Friday'){?> checked="checked"<?}?>/>Friday<br />
						<input type="radio" name="repeat_day" value="Saturday"<?if($special_repeat_day == 'Saturday'){?> checked="checked"<?}?>/>Saturday
					</td>
					<td><input type="submit" id="add-repeat-date-submit<?=$special_id?>" name="Submit" value="Save"/></td>
				</tr>
			</table>
			</form>
		</div>
		<div id="update-special-<?=$special_id?>" class="add-update">
			<form name="update-special-form<?=$special_id?>" id="update-special-form<?=$special_id?>">
			<input type="hidden" name="special_id" value="<?=$special_id?>"/>
			<table>
				<tr>
					<td><label>Type</label><br />
						<select name="special_type_id">
						<?php

							$list_special_types = mysql_query("SELECT special_type_id, description FROM special_type ORDER BY description");
							while($info = mysql_fetch_array( $list_special_types)) {
							$description_bad=$info['description'];
							$description = str_replace("\\", "", $description_bad );
						?>
						<option value="<?=$info['special_type_id']?>"<?if($special_type_id == $info['special_type_id']){?> selected="selected"<?}?>><?=$description?></option>
						<?}?>
						</select>
					</td>
					<td><label>Special</label><br /><input type="text" size="25" name="name" value="<?=$name?>"/></td>
					<td><label>Price</label><br /><input type="text" size="10" name="price" value="<?=$price?>"/></td>
					<td><label>Description</label><br /><textarea name="description"><?=$description?></textarea></td>
					<td><input type="submit" id="update-special-submit<?=$special_id?>" name="Submit" value="Save"/></td>
				</tr>
			</table>
			</form>
		</div>
	</td>
</tr>
<?}}?>