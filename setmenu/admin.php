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
		header("Location: ./Options.php");
	}else
	{// else give them a bad username/password message
		$Error = "Username/Password Error" . mysql_error();
	}
	//close db connection
	mysql_close();
}
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
<?
	if(isset($Error))
	{
?>
<div class="Error_msg"> <span class="Error_msg_type"><? echo $Error?></span></div>	
<?
	}
?>
      <form id="AdminLogin" name="AdminLogin" method="post" action="admin.php">
          <p>Login: <input type="text" name="Login" id="Login" tabindex="1" /></p>
      
          <p>Password: <input type="password" name="Pass" id="Pass" tabindex="2" /></p>
			<input name="Submit" type="submit" value="Submit" />
      </form>
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
