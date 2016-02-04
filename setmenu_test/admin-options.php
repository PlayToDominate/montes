<?
session_start();
if(!isset($_SESSION['Admin_check']))
{
	header("location: admin.php");
}
//Check that they have the security permission to change the menu
//Connect to db
include('dbconnect.php');
$page_title="Administrator Home | Monte's Grill &amp; Pub";
$sql_specials_Check = "SELECT * FROM Admin WHERE User='".mysql_real_escape_string($_SESSION['Admin_check'])."' AND (Specials = '1' OR Jobs = '1' OR Events = '1' OR Users = '1')";
$rt_User_Check = mysql_query($sql_specials_Check);
if(!$User = mysql_fetch_array($rt_User_Check))
{
	header("location: admin.php");
}
mysql_close();
?>
<?include '../includes/header-admin.php';?>
<body>
<div id="specials" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include '../includes/navigation-admin.php';?>
	<div id="content" data-role="content">
		<?include '../includes/navigation-admin-specials.php';?>
		<h1>Administrator Home</h1>
			<? if($User['Specials'] == '1'){ ?>
      <p class="BodyType"><a href="admin-specials.php">Specials Menu</a></p>
			<? }
			if($User['Events'] == '1'){ ?>
      <p class="BodyType"><a href="admin-events.php">Events</a></p>
			<? }
			if($User['Jobs'] == '1'){ ?>
      <p class="BodyType"><a href="admin-jobs.php">Job Opportunities</a></p>
			<?}
			if($User['Users'] == '1'){ ?>
      <p class="BodyType"><a href="admin-users.php">Users</a></p>
      <? }
			if($User['Posts'] == '1'){ ?>
      <p class="BodyType"><a href="admin-homepage-update.php">Homepage Posts</a></p>
      <? }?>
      <p class="BodyType"><a href="logout.php">Logout</a></p>
    </div>
		</div><!-- end menu-nav-food-specials -->
	</div><!-- end content -->
</div><!-- end specials -->
</body>
</html>