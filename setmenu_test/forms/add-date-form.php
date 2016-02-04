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
	$special_date_id = mysql_real_escape_string($_POST['special_date_id']);
	$special_start_date = mysql_real_escape_string($_POST['special_start_date']);
	//$special_end_date = mysql_real_escape_string($_POST['special_end_date']);

$date = preg_replace('/\D/','/',$special_start_date);
$special_start_date_fmt =  date('Y-m-d 23:59:59', strtotime($date));

if($special_date_id){

	$sql="UPDATE special_date SET special_start_date='".$special_start_date_fmt."' WHERE special_date_id=".$special_date_id."";

	//Put into Database
	if(!mysql_query($sql))	{	//Issue error if one happens
		echo "There was a problem. Please try again.";
		echo $sql;
	}else	{
		$start_date = $special_start_date_fmt;
		$unix_start_date = strtotime ($start_date);
		echo date("m/d/Y", $unix_start_date);
	}
} else {

	$sql="INSERT INTO special_date(special_id, special_start_date, special_end_date)VALUES(".$special_id.",'".$special_start_date_fmt."','".$special_start_date_fmt."')";

	//Put into Database
	if(!mysql_query($sql))	{	//Issue error if one happens
		echo "There was a problem. Please try again.";
		echo $sql;
	}else	{
		//echo "Success!";

		$start_date = $special_start_date_fmt;
		$unix_start_date = strtotime ($start_date);
?>

	<form name="update-date<?=$special_date_id?>" method="post">
		<input type="hidden" name="special_end_date" value=""/>
		<input type="hidden" name="special_date_id" value="<?=$special_date_id?>"/>
		<?if ($unix_start_date >= time() ) {?>
		<input type="text" name="special_start_date" value="<?=date("m/d/Y", $unix_start_date)?>"/>
		<input type="submit" id="update-date-submit<?=$special_date_id?>" name="Submit" value="Update"/>
		<?} else {?>
		<input type="text" name="special_start_date" value="<?=date("m/d/Y", $unix_start_date)?>" disabled/>
		<input type="submit" id="update-date-submit<?=$special_date_id?>" name="Submit" value="Update" disabled/>
		<?}?>
		<!--<button id="delete-date-submit<?=$special_date_id?>" name="Delete" value="<?=$special_date_id?>">Delete</button>--><br />
	</form>

	<?}}?>