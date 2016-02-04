<?include('includes/dbconnect.php');?>
<?php
	$page_title="Monte's Grill &amp; Pub";
?>
<?PHP require_once('includes/header.php'); ?>
<body id="index">
<div id="homepage" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include 'includes/navigation.php';?>
	<div id="content">
		<h2>Monte's Grill &amp; Pub: Your Hometown Bar in Verona, Wisconsin</h2>
		<p>Monte's Grill &amp; Pub has worked hard to be known as the friendliest place in town and some of the best food. Come for the hometown feeling, come for the food and come enjoy the game! With 4 plasma TVs and 10 TVs altogether, you won't miss any of the action. You will fall in love with the oldest Verona owned Grill &amp; Pub, so please come visit us and ask for Monte - I'd love to meet you! </p>
		<h2>Father's Day Brunch</h2>
		<p>Well, if we have a Mother's Day Brunch, we should have a Father's Day Brunch too! We'll be celebration on Sunday, June the 16. More details to come!</p>
		<h2>New Night for Team Trivia</h2>
		<p>We've added a new night and time for Team Trivia - Sundays from 4pm-6pm, and Tuesdays 7pm-9pm!</p>
		<h2>Today's Food Specials</h2>
		<?

					$previous = '';
					$yesterday = date(time() - (24 * 60 * 60));
					$tomorrow = date(time() + (24 * 60 * 60));
					$today_orig = date(time());
					$today = strtotime ($today_orig);
					$day = date("d",$today_orig);
					$month = date("m",$today_orig);
					$next_month = date("m",strtotime("+1 months"));
					$year = date("Y",$today_orig);
					$found = false;

					$daily_specials = mysql_query("
					SELECT id,Name,Description,Price,Day,Meal,Active,Weight,dateactive
					FROM Specials
					WHERE Active = '1'
					ORDER BY dateactive ASC, Weight ASC
						")
			or die(mysql_error());

					$daily_events = mysql_query("
					SELECT id,Title,Description,Year,Month,Day,recurr_id,Weight
					FROM Events
					WHERE (Day >= '$day' AND Month = '$month'  OR Month > '$month') AND Month <= '$next_month' AND Year = '$year'
					ORDER BY Year ASC, Month ASC, Day ASC, Weight ASC
						")
			or die(mysql_error());
		?>
		<?
			while($info = mysql_fetch_array( $daily_specials)) {
			$date = $info['dateactive'];
			$unix_date = strtotime ($date);
			$name_bad=$info['Name'];
			$name = str_replace("\\", "", $name_bad );
			if (($unix_date >= time()) && ($unix_date <= $tomorrow)) {
			$found = true;
		?>
		<?if ( $unix_date != $previous) {?>
		<!--<h3><?=date("l, F jS", $unix_date)?></h3>-->
		<?}?>
		<p><span style="font-weight:bold;"><?=$name?><?if($info['Price'] !='N/A'){?> - <?=$info['Price']?><?}?></span><br /><?=$info['Description']?></p>
		<?
		$previous = $unix_date;
		}}?>
		<?if(!$found){?>
		<p>Sorry, no posted Food Specials today. Please check back soon!</p>
		<?}?>
		<p><a href="menu.php">View Full Menu</a></p>
		<!--
		<h2>Free WiFi</h2>
		<p>That's right, we have FREE WiFi! Just ask your server/bartender for the password and you'll be on your way! Come for the WiFi and stay for the food, TVs and hometown atmosphere!</p>
		<h2>Great Food and Drinks</h2>
		<p><a href="menu.php">Browse through our brand new menu!</a> We have a wide variety of food, from burgers to fish to appetizers that all make your mouth water! Be sure to browse our <a href="drinks.php">drink list</a> and see the different beers we have on tap, wine by the glass, our fantastic Old Fashioneds and much more!</p>
		<h2>Monte's Grill &amp; Pub <a href="events">Calendar of Events</a> and Specials</h2>
		<p>Make sure to keep up with all that is going on at Monte's Grill &amp; Pub. Add an event to your calendar by clicking on it and selecting "Copy to My Calendar"! Also be sure to <a href="specials.php">check out our specials</a> and see what our cooks have up their sleeve for today!</p>
		<h2>Safe Rides</h2>
		<p>Monte's Grill &amp; Pub is very community and safety conscious, so we offer Safe Rides! Ask your bartender if you need a lift home! Please drink responsibly.</p>
		-->
	</div>
	<?include 'includes/right_bar.php';?>
	<?include 'includes/footer.php';?>
</div>
</body>
</html>
