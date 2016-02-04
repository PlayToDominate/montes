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

$page_title="Add Users | Monte's Grill &amp; Pub";
//Check to see if new special is being uploaded
if(isset($_POST['Submit']))
{
	//clean Data
	$Name = mysql_real_escape_string($_POST['Name']);
	$Password = mysql_real_escape_string($_POST['Password']);
	if(isset($_POST['Jobs']))
	{
		$Jobs = '1';
	}else
	{
		$Jobs = '0';
	}
	if(isset($_POST['Events']))
	{
		$Events = '1';
	}else
	{
		$Events = '0';
	}
	if(isset($_POST['Specials']))
	{
		$Specials = '1';
	}else
	{
		$Specials = '0';
	}
	if(isset($_POST['Users']))
	{
		$Users = '1';
	}else
	{
		$Users = '0';
	}
	$sql_InsertSpecial = "INSERT INTO Admin (User, Pass,Jobs,Events,Specials,Users) VALUES ('".$Name."','".$Password."','".$Jobs."','".$Events."','".$Specials."','".$Users."')";
	//Put into Database
	if(!mysql_query($sql_InsertSpecial))
	{	//Issue error if one happens
		$Error = "A problem occured will trying to insert the information into the database: " . mysql_error();
	}
}
mysql_close();
?>
<?include '../includes/header-admin.php';?>
<body>
<div id="specials" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include '../includes/navigation-admin.php';?>
	<div id="content" data-role="content">
		<?include '../includes/navigation-admin-users.php';?>
		<h1>Add Users</h1>
<?
	if(isset($Error))
	{
		echo "<p class=Error_msg>".$Error."</p>";
	}
?>
   <form id="Add_Special" name="Add_Special" method="post" action="<? echo $_SERVER['../PHP_SELF']?>">
         <p class="BodyType">Username:
         <input type="text" name="Name" id="Name" />
         </p>
          <p class="BodyType">Password:
          <input type="password" name="Password" id="Password" />
          </p>
          <p class="BodyType">Able to Modify:<br />
	      <input type="checkbox" name="Jobs" id="Jobs" />
		  Jobs<br />
          <input type="checkbox" name="Events" id="Events" />
          Events<br />
          <input type="checkbox" name="Specials" id="Specials" />
          Specials<br />
          <input type="checkbox" name="Users" id="Users" />
          Users</p>
          <p>
            <input type="submit" name="Submit" id="Submit" value="Add Users" />
      </p>
	  </form>
		</div><!-- end menu-nav-food-specials -->
	</div><!-- end content -->
</div><!-- end specials -->
</body>
</html>
