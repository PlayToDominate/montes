<?include('includes/dbconnect.php');?>
<?php
	$page_title="Monte's Grill &amp; Pub";

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
		
	$daily_specials = mysql_query("
		SELECT
		special.special_type_id,
		special_start_date,
		special_end_date,
		(DAYOFWEEK(special_start_date)-1) AS DOW,
		CONCAT((DAYOFWEEK(special_start_date)-1),special.special_type_id) AS SORT,
		name,
		price,
		special.description as s_description,
		special_type.description as st_description
		FROM special, special_date, special_type
		WHERE
		special.special_id = special_date.special_id
		AND special.special_type_id = special_type.special_type_id
		AND special_date.special_start_date >= CURDATE()
		ORDER BY special_start_date ASC, st_description DESC
	")
	or die(mysql_error());
	$repeating_specials = mysql_query("
		SELECT
		special.special_type_id,
		fg_repeat,
		repeat_day,	
		fg_repeat AS DOW,
		CONCAT(fg_repeat,special.special_type_id) AS SORT,
		name,
		price,
		special.description as s_description,
		special_type.description as st_description
		FROM special, special_type
		WHERE special.special_type_id = special_type.special_type_id 
		AND special.fg_repeat IS NOT NULL 
		AND special.fg_repeat != 'N'
		ORDER BY fg_repeat ASC, st_description DESC
	")
	or die(mysql_error());

	$special_items = array();
	while($info = mysql_fetch_array( $daily_specials)) {
		$special_items[] = $info;
	}
	while($info = mysql_fetch_array( $repeating_specials)) {
		$special_items[] = $info;
	}

	function cmp($a, $b) {
		 return $a['SORT'] - $b['SORT'];
	}

	usort($special_items,"cmp");
?>
<?PHP require_once('includes/header.php'); ?>
<body id="index">
<div id="homepage" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include 'includes/navigation.php';?>
	<div id="content">
		<?php
			while($info = mysql_fetch_array( $daily_posts)) {
		?>
		<h2><?=$info['title']?></h2>
		<p><?=$info['description']?></p>
		<?}?>
		<!--<h2>Today's Food Specials</h2>-->
					<?php
					date_default_timezone_set('America/Chicago');
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
						foreach($special_items as $info) {
							if($info['special_start_date'] > 0){
								$date = $info['special_start_date'];
								$unix_date = strtotime ($date);
							} else {
								$repeat_day = $info['repeat_day'];
								$unix_date = strtotime("this $repeat_day");
							}
						$name_bad=$info['name'];
						$name = str_replace("\\", "", $name_bad );
						if (($unix_date >= time()) && ($unix_date <= $tomorrow)) {
						$found = true;
					?>
					<?if ( $unix_date != $previous) {?>
					<h2>Specials for <?=date("l, F jS", $unix_date)?></h2>
					<?}?>
					<h4><?=$info['st_description']?><?if(($info['price'] !='N/A') && ($info['price'] !='0.00')){?> - <?=$info['price']?><?}?></h4>
					<p style="padding-bottom:1px;"><?=$name?><br /><span style="font-style:italic;"><?=$info['s_description']?></span></p>
					<?
					$previous = $unix_date;
					}}?>
					<?if(!$found){?>
					<p>Sorry, no posted Specials today. Please check back soon!</p>
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
