<?
session_start();
if(!isset($_SESSION['Admin_check']))
{
	header("location: admin.php");
}
//Check that they have the security permission to change the menu
//Connect to db
include('dbconnect.php');
$sql_specials_Check = "SELECT * FROM Admin WHERE User='".mysql_real_escape_string($_SESSION['Admin_check'])."' AND Specials = '1'";
$rt_User_Check = mysql_query($sql_specials_Check);
if(!$User = mysql_fetch_array($rt_User_Check))
{
	$Error = urlencode("Sorry you don't have permissions to access this part of the site");
	header("location: admin-options.php?Error=".$Error);
}
$page_title="Update Specials | Monte's Grill &amp; Pub";
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
SELECT
special.special_id as special_id,
special.special_type_id as special_type_id,
special.name,
special.price,
special.fg_repeat,
special.repeat_day,
special.description as s_description,
special_type.description as st_description
FROM special, special_type
WHERE special.special_type_id = special_type.special_type_id
		")
		or die(mysql_error());


?>
<?include '../includes/header-admin.php';?>
<body>
<div id="specials" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include '../includes/navigation-admin.php';?>
	<div id="loading" style="float:left;padding:3%;width:70%;"><h1>...Loading</h1></div>
	<div id="content" data-role="content">
		<?include '../includes/navigation-admin-specials.php';?>
		<h1>Update Specials</h1>
		<?php
			$previous = '';
			$found = false;
		?>
		<div id="menu-nav-food-specials" rel="#food-specials" data-role="collapsible" data-collapsed="true" data-content-theme="a">
			<div id="food-specials" class="food-menu">
				<table id="specials-list">
					<tr>
						<td><label>Type</label></td>
						<td><label>Special</label></td>
						<td><label>Price</label></td>
						<td><label>Description</label></td>
					</tr>
					<?
						while($info = mysql_fetch_array( $daily_specials)) {
						$st_description_bad=$info['st_description'];
						$st_description = str_replace("\\", "", $st_description_bad );
						$current_special = $info['special_id'];
						$special_type_id = $info['special_type_id'];
						$special_name = $info['name'];
						$special_price = $info['price'];
						$special_description = $info['s_description'];
						$special_repeat_day = $info['repeat_day'];
						$special_repeat = $info['fg_repeat'];
					?>
					<tr bgcolor="#D3D3D3">
						<td id="type-<?=$current_special?>"><?=$st_description ?></td>
						<td id="name-<?=$current_special?>"><?=$special_name?></td>
						<td id="price-<?=$current_special?>"><?=$special_price?></td>
						<td id="desc-<?=$current_special?>"><?=$special_description?></td>
						<td nowrap>
							<button id="add-date-<?=$current_special?>" class="add-date" value="<?=$current_special?>">Add Date(s)</button><br />
							<button id="add-repeat-date-btn<?=$current_special?>" class="add-repeat-date" value="<?=$current_special?>">Add Repeating Date</button><br />
							<button id="add-special-<?=$current_special?>" class="update-special" value="<?=$current_special?>">Update Special</button>
						</td>
					</tr>
					<tr>
						<td colspan="5">
							<div id="update-date-<?=$current_special?>" class="add-update">
								<?
								date_default_timezone_set('America/Chicago');
								$specials_dates = mysql_query("
									SELECT
									special_date.special_date_id as special_date_id,
									special_start_date,
									special_end_date
									FROM special, special_date
									WHERE
									special_date.special_id = special.special_id
									AND special_date.special_id= $current_special
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
									<!--<button id="delete-date-submit<?=$current_special?>" name="Delete" value="<?=$current_date_id?>">Delete</button>-->
									<br />
								</form>
								<?}?>
								<form name="add-date<?=$current_special?>" id="add-date<?=$current_special?>" method="post">
									<input type="hidden" name="special_end_date" id="special_end_date<?=$current_special?>" value=""/>
									<input type="hidden" name="special_id" id="special_id<?=$current_special?>" value="<?=$current_special?>"/>
									<input type="date" name="special_start_date" id="special_start_date<?=$current_special?>" value=""/>
									<input type="submit" class="add-date-submit" id="add-date-submit<?=$current_special?>" name="Submit" value="Save"/>
								</form>
							</div>


							<div id="add-repeat-date-<?=$current_special?>" class="add-update">
								<form name="add-repeat-date-form<?=$current_special?>" id="add-repeat-date-form<?=$current_special?>">
								<input type="hidden" name="special_id" value="<?=$current_special?>"/>
								<input type="hidden" name="fg_repeat" id="repeat<?=$current_special?>" value="<?=$special_repeat?>"/>
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
										<td><input type="submit" id="add-repeat-date-submit<?=$current_special?>" name="Submit" value="Save"/></td>
									</tr>
								</table>
								<p class="add-repeat-response"></p>
								</form>
							</div>


							<div id="update-special-<?=$current_special?>" class="add-update">
								<form name="update-special-form<?=$current_special?>" id="update-special-form<?=$current_special?>">
								<input type="hidden" name="special_id" value="<?=$current_special?>"/>
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
										<td><label>Special</label><br /><input type="text" size="25" name="name" value="<?=$special_name?>"/></td>
										<td><label>Price</label><br /><input type="text" size="10" name="price" value="<?=$special_price?>"/></td>
										<td><label>Description</label><br /><textarea name="description"><?=$special_description?></textarea></td>
										<td><input type="submit" id="update-special-submit<?=$current_special?>" name="Submit" value="Save"/></td>
									</tr>
								</table>
								</form>
							</div>
						</td>
					</tr>
					<? } ?>
				</table>
				<button name="add-new-special" id="add-new-special">Add New Special</button>
				<div id="add-special" class="add-update">
					<form id="add-new-special-form" name="add-new-special-form">
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
								<option value="<?=$info['special_type_id']?>"><?=$description?></option>
								<?}?>
								</select>
							</td>
							<td><label>Special</label><br /><input type="text" size="25" name="name" value=""/></td>
							<td><label>Price</label><br /><input type="text" size="10" name="price" value=""/></td>
							<td><label>Description</label><br /><textarea name="description"></textarea></td>
							<td><input type="submit" id="add-special-submit" name="Submit" value="Save"/></td>
						</tr>
					</table>
					</form>
				</div>
			</div><!-- end food-specials -->
			<?mysql_close();?>
		</div><!-- end menu-nav-food-specials -->
	</div><!-- end content -->
</div><!-- end specials -->
<script type="text/javascript">
<!--
$(window).load(function(){
  $("#loading").fadeOut("slow");
  $("#content").fadeIn("slow");
});

jQuery(document).ready(function($){
$("#content").hide();
var current_special='1';

 	$('.add-update').hide();
	$("#specials-list").delegate(".add-date", "click", function(){
		var specialID = $(this).val();
		$('#update-date-'+specialID).toggle();
	});

	$("#specials-list").delegate(".update-special", "click", function(){
		var specialID = $(this).val();
		current_special = specialID;
		//alert(current_special);
		$('#update-special-'+specialID).toggle();
	});

	$("#specials-list").delegate(".add-repeat-date", "click", function(){
		var specialID = $(this).val();
		current_special = specialID;
		//alert(current_special);
		$('#add-repeat-date-'+specialID).toggle();
	});

$("#specials-list").delegate("input[type=radio]'", "click", function(){
	var repeat_day = $(this).val();
	var formID = $(this).closest('form').attr('id');
	var specialID = $('#'+formID+' input[name=special_id]').val();
	if(repeat_day =='Sunday'){
		$('#repeat'+specialID).val('0');
	} else if(repeat_day =='Monday'){
		$('#repeat'+specialID).val('1');
	} else if(repeat_day =='Tuesday'){
		$('#repeat'+specialID).val('2')
	} else if(repeat_day =='Wednesday'){
		$('#repeat'+specialID).val('3');
	} else if(repeat_day =='Thursday'){
		$('#repeat'+specialID).val('4');
	} else if(repeat_day =='Friday'){
		$('#repeat'+specialID).val('5');
	} else if(repeat_day =='Saturday'){
		$('#repeat'+specialID).val('6');
	} else {
		$('#repeat'+specialID).val('N');
	}
});

	$('#add-new-special').click(function() {
		$('#add-special').toggle();
	});

//add dates
	$('form').submit(function(){
		var formID = $(this).closest('form').attr('id');
		if(formID.indexOf('add-date') > -1){
			$.ajax({
				url:'forms/add-date-form.php',
				type:'POST',
				data:$(this).serialize(),
				success: function(result){
					//alert('got into ajax success');
					//$('#response').remove();
					$('#'+formID).before(result);
					$('#'+formID+' input[name=special_start_date]').val('');
				}
			});
		} else if(formID.indexOf('add-repeat') > -1){
			//alert('add-repeat');
			$.ajax({
				url:'forms/update-special-form.php',
				type:'POST',
				data:$(this).serialize(),
				success: function(result){
					//alert('got into ajax success');
					//$('#response').remove();
					$('#'+formID).before(result);
				}
			});
		} else if(formID.indexOf('update-date') > -1){
			$.ajax({
				url:'forms/add-date-form.php',
				type:'POST',
				data:$(this).serialize(),
				success: function(result){
					//alert('got into ajax success');
					$('#'+formID+' input[name=special_start_date]').val(result);
				}
			});

		} else if(formID.indexOf('add-new-special') > -1){
			$.ajax({
				url:'forms/update-special-form.php',
				type:'POST',
				data:$(this).serialize(),
				success: function(result){
					//alert('got into ajax success');
					$('#response').hide('slow');
					$('#specials-list').append(result);
					$('#add-new-special-form input[name=type]').val('');
					$('#add-new-special-form input[name=name]').val('');
					$('#add-new-special-form input[name=price]').val('');
					$('#add-new-special-form textarea[name=description]').val('');
					$('.add-update').hide();
				}
			});



		} else {
			var new_type_value = $('#update-special-form'+current_special+' input[name=type]').val();
			var new_name_value = $('#update-special-form'+current_special+' input[name=name]').val();
			var new_price_value = $('#update-special-form'+current_special+' input[name=price]').val();
			var new_desc_value = $('#update-special-form'+current_special+' textarea[name=description]').val();
			$.ajax({
				url:'forms/update-special-form.php',
				type:'POST',
				data:$(this).serialize(),
				success: function(result){
					//alert('got into ajax success');
					$('#response').hide('slow');
					$('#specials-list').append(result);
					$('#type-'+current_special).text(new_type_value);
					$('#name-'+current_special).text(new_name_value);
					$('#price-'+current_special).text(new_price_value);
					$('#desc-'+current_special).text(new_desc_value);
				}
			});
		}
		return false;
	});
});
//-->
</script>
</body>
</html>