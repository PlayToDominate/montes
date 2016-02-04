<?
session_start();
if(!isset($_SESSION['Admin_check']))
{
	header("location: admin.php");
}
//Check that they have the security permission to change the menu
//Connect to db
include('dbconnect.php');
$sql_specials_Check = "SELECT * FROM Admin WHERE User='".mysql_real_escape_string($_SESSION['Admin_check'])."' AND Events = '1'";
$rt_User_Check = mysql_query($sql_specials_Check);
if(!$User = mysql_fetch_array($rt_User_Check))
{
	$Error = urlencode("Sorry you don't have permissions to access this part of the site");
	header("location: admin-options.php?Error=".$Error);
}
$page_title="Event Details | Monte's Grill &amp; Pub";
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

$sql_GetEvents = "SELECT * FROM Events where id = '".mysql_real_escape_string($_GET['Eventid'])."'";
//echo $sql_GetEvents."<br>";
$error = false;
if(!$rts_Events = mysql_query($sql_GetEvents))
{
	$error="There was a problem connecting to our database, please try back later";
}elseif(!$Event = mysql_fetch_array($rts_Events))
{
	$error="There is not an event with that id.  Visit our Calendar of events <a href='/Monte-Grill-Pub-DisplayEvents.php'>here</a>";
}

?>
<?include '../includes/header-admin.php';?>
<body>
<div id="specials" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include '../includes/navigation-admin.php';?>
	<div id="content" data-role="content">
		<?include '../includes/navigation-admin-events.php';?>
		<h1>Event Detail</h1>
      <?
	if(!$error)
	{
?>
      <div class="Menu_Item_Header"><? echo $Event['Title'] ?></div>
      <p class="BodyType"><strong>When</strong>: <? echo GetMonth($Event['Month'])." ".$Event['Day']." ".$Event['Year'] ?></p>
      <p class="BodyType"><strong>Whats going on?</strong> <br />
      <? echo str_replace("\n","<br>",$Event['Description']) ?></p>
      <div style="height:15px;width:10px;"></div>
 <?
	}else
	{
?>
		<div class="Menu_Item_Header">Error</div>
		<p class="BodyType" align="center"><strong> <? echo $error ?> </strong></p>
        <div style="height:10px;width:10px;"></div>
<?
	}
?>

    </div>
  </div>
  <? mysql_close() ?>
		</div><!-- end menu-nav-food-specials -->
	</div><!-- end content -->
</div><!-- end specials -->
</body>
</html>