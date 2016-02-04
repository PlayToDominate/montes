<?include('includes/dbconnect.php');?>
<?php
	$page_title="Events | Monte's Grill &amp; Pub";
?>
<?include 'includes/header.php';?>
<body id="menu">
<div id="specials" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include 'includes/navigation.php';?>
	<div id="content" data-role="content">
		<h1>Events</h1>
		<div id="events" class="food-menu" style="display:block;">
			<iframe src="https://www.google.com/calendar/embed?src=vtgej41nhfo30cji677i7la750%40group.calendar.google.com&ctz=America/Chicago" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
		</div>
	</div>
	<?include 'includes/right_bar.php';?>
	<?include 'includes/footer.php';?>
</div>
</body>
</html>
