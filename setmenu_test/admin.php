<?
// Check Admin login
if(isset($_POST['Submit']))
{
	//open db connection
//	echo "including dbconnect <br>";
	include('dbconnect.php');
//	echo "Done including dbconnect";
	$sqlAdmin = "SELECT * FROM Admin WHERE User='".mysql_real_escape_string($_POST['Login'])."' AND Pass ='".mysql_real_escape_string($_POST['Pass'])."'";
	$rt_Admin = mysql_query($sqlAdmin);
	if($Admin = mysql_fetch_array($rt_Admin))
	{// if correct set session and forward to admin area
		session_start();
		$_SESSION['Admin_check'] = $_POST['Login'];
		header("Location: admin-specials.php");
	}else
	{// else give them a bad username/password message
		$Error = "Username/Password Error" . mysql_error();
	}
	//close db connection
	mysql_close();
}
$page_title="Administrator Login | Monte's Grill &amp; Pub";
?>
<?include '../includes/header-admin.php';?>
<body>
<div id="specials" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include '../includes/navigation-admin.php';?>
	<div id="content" data-role="content">
		<?include '../includes/navigation-admin-users.php';?>
		<?
			if(isset($Error))
			{
		?>
		<div class="Error_msg"> <span class="Error_msg_type"><? echo $Error?></span></div>
		<?
			}
		?>
		<h1>Administrator Login</h1>
      <form id="AdminLogin" name="AdminLogin" method="post" action="admin.php">
          <p>Login: <input type="text" name="Login" id="Login" tabindex="1" /></p>

          <p>Password: <input type="password" name="Pass" id="Pass" tabindex="2" /></p>
			<input name="Submit" type="submit" value="Submit" />
      </form>
    </div>
  </div>
		</div><!-- end menu-nav-food-specials -->
	</div><!-- end content -->
</div><!-- end specials -->
</body>
</html>