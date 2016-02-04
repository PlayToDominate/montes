<?
session_start();
if(!isset($_SESSION['Admin_check']))
{
	header("location: admin.php");
}
//Check that they have the security permission to change the menu
//Connect to db
include('dbconnect.php');
$sql_specials_Check = "SELECT * FROM Admin WHERE User='".mysql_real_escape_string($_SESSION['Admin_check'])."' AND Posts = '1'";
$rt_User_Check = mysql_query($sql_specials_Check);
if(!$User = mysql_fetch_array($rt_User_Check)){
	$Error = urlencode("Sorry you don't have permissions to access this part of the site");
	header("location: admin-options.php?Error=".$Error);
} else {
	$page_title="Preview Homepage | Monte's Grill &amp; Pub";
	$sql_specials_Check = "SELECT * FROM Admin WHERE User='".mysql_real_escape_string($_SESSION['Admin_check'])."' AND Posts = '1'";
	$rt_User_Check = mysql_query($sql_specials_Check);
	if(!$User = mysql_fetch_array($rt_User_Check)) {
		$Error = urlencode("Sorry you don't have permissions to access this part of the site");
		header("location: Options.php?Error=".$Error);
	}

	$daily_posts = mysql_query("
SELECT
posts.post_id as post_id,
posts.title,
posts.description,
posts.location,
posts.active,
posts.sort
FROM posts
WHERE active='Y'
ORDER BY sort,date_added DESC
		")
		or die(mysql_error());
}
?>
<?include '../includes/header-admin.php';?>
<body id="index">
<div id="homepage" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include '../includes/navigation-admin.php';?>
	<div id="content" data-role="content">
		<?include '../includes/navigation-admin-posts.php';?>
		<h1>Homepage Preview</h1>
		<div id="fullMenu">
			<div id="menu-nav-specials" rel="#specials" data-role="collapsible" data-collapsed="true">
				<div id="specials" class="food-menu">
					<?php
						while($info = mysql_fetch_array( $daily_posts)) {
					?>
					<h2><?=$info['title']?></h2>
					<p><?=$info['description']?></p>
					<?}?>
				</div>
			</div><!-- end food-specials -->
		<? mysql_close();?>
		</div><!-- end menu-nav-food-specials -->
	</div><!-- end content -->
</div><!-- end specials -->
</body>
</html>