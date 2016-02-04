<?
session_start();
if(!isset($_SESSION['Admin_check']))
{
	header("location: admin.php");
}
//Check that they have the security permission to change the menu
//Connect to db
include('dbconnect.php');
$sql_specials_Check = "SELECT * FROM Admin WHERE User='".mysql_real_escape_string($_SESSION['Admin_check'])."' AND Users = '1'";
$rt_User_Check = mysql_query($sql_specials_Check);
if(!$User = mysql_fetch_array($rt_User_Check))
{
	$Error = urlencode("Sorry you don't have permissions to access this part of the site");
	header("location: admin-options.php?Error=".$Error);
}
$page_title="Delete Users | Monte's Grill &amp; Pub";

//Check to see if new special is being uploaded
if(isset($_POST['Submit']))
{
	//clean Data
	$id = mysql_real_escape_string($_POST['id']);
	$sql_Delete_User = "DELETE FROM Admin WHERE id ='".$id."'";
//	echo "SQL: $sql_Delete_User <Br>";
	//Put into Database
	if(!mysql_query($sql_Delete_User))
	{	//Issue error if one happens
		$Error = "A problem occured will trying to insert the information into the database: " . mysql_error();
	}
}
?>
<?include '../includes/header-admin.php';?>
<body>
<div id="specials" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include '../includes/navigation-admin.php';?>
	<div id="content" data-role="content">
		<?include '../includes/navigation-admin-users.php';?>
		<h1>Delete Users</h1>
      <?
	if(isset($Error))
	{
		echo "<p class=Error_msg>".$Error."</p>";
	}
?>
      <?
	  	$sql_Delete_users_list = "SELECT * FROM Admin";
		$rts_users = mysql_query($sql_Delete_users_list);
		while($user = mysql_fetch_array($rts_users))
		{
		?>
   <form id="Delete_User" name="Delete_User" method="post" action="<? echo $_SERVER['PHP_SELF']?>">
		<input type="hidden" name="id" value="<? echo $user['Id'] ?>" />
         <p class="BodyType"><? echo $user['User']?> <input type="submit" name="Submit" id="Submit" value="Delete this User" />
      </p>
	  </form>
      <?
	  	}
	mysql_close();
	?>
		</div><!-- end menu-nav-food-specials -->
	</div><!-- end content -->
</div><!-- end specials -->
</body>
</html>