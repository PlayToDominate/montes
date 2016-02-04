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
		
	$maketemp= mysql_query("CREATE TEMPORARY TABLE temp_all_specials (special_type_id INT, special_start_date DATETIME, name VARCHAR(255), price DECIMAL (11,2), s_description VARCHAR(255), st_description VARCHAR(255))") ;
	$maketempresult = mysql_query($maketemp);


		$all_specials_sql= "
			SELECT
				special_type_id, 
				special_start_date, 
				name, 
				price, 
				s_description, 
				st_description 
			FROM temp_all_specials 
			WHERE special_start_date >= CURDATE()
			ORDER BY special_start_date ASC, special_type_id ASC
		";
	 
	$daily_specials_sql = "
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
	";
	$repeating_specials_sql = "
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
	";
	
	$repeating_specials = mysql_query($repeating_specials_sql) or die(mysql_error());
	$daily_specials = mysql_query($daily_specials_sql) or die(mysql_error());
		
		while($info = mysql_fetch_array( $daily_specials)) {
			//$name_bad=$info['name'];
			//$name = str_replace("\\", "", $name_bad );
			//$st_description_bad=$info['st_description'];
			//$st_description = str_replace("\\", "", $st_description_bad );
			$name=$info['name'];
			$st_description=$info['st_description'];
            $insert_all_specials_sql = "INSERT INTO temp_all_specials (special_type_id, special_start_date, name, price, s_description, st_description)  VALUES (".$info['special_type_id'].",'".$info['special_start_date']."','".$name."',".$info['price'].",'".$info['s_description']."','".$st_description."')";
			$insert_all_specials= mysql_query($insert_all_specials_sql);
        }
		while($info = mysql_fetch_array( $repeating_specials)) {
			$repeat_day = $info['repeat_day'];
			$unix_date = strtotime("this $repeat_day");
			$final_date = date("Y-m-d 23:59:59", $unix_date);
			//$name_bad=$info['name'];
			//$name = str_replace("\\", "", $name_bad );
			//$st_description_bad=$info['st_description'];
			//$st_description = str_replace("\\", "", $st_description_bad );
			$repeat_name=$info['name'];
			$st_description=$info['st_description'];
            $insert_all_specials1_sql="INSERT INTO temp_all_specials (special_type_id, special_start_date, name, price, s_description, st_description) VALUES (".$info['special_type_id'].",'".$final_date."','".$repeat_name."',".$info['price'].",'".$info['s_description']."','".$st_description."')";
			$insert_all_specials1= mysql_query($insert_all_specials1_sql);
        }
		$all_specials = mysql_query($all_specials_sql) or die(mysql_error());
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
					$yesterday = date(time() - (24 * 60 * 60));
					$tomorrow = date(time() + (24 * 60 * 60));
					$today_orig = date(time());
					$today = strtotime ($today_orig);

						$found = false;
					while($info = mysql_fetch_array( $all_specials)) {
						$date = $info['special_start_date'];
						$unix_date = strtotime ($date);
						if (($unix_date >= time())  && ($unix_date <= $tomorrow)){
						$found = true;
					?>
					<?if ( $unix_date != $previous) {?>
					<h2>Specials For <?=date("l, F jS", $unix_date)?></h2>
					<?}?>
					<!--<p>Today: <?=date("l, F jS h:i a", $today_orig)?><br />Yesterday: <?=date("l, F jS h:i a", $yesterday)?><br />Tomorrow: <?=date("l, F jS h:i a", $tomorrow)?><br />Special Date: <?=$info['special_start_date']?></p>-->
					<h4><?=$info['st_description']?><?if(($info['price'] !='N/A') && ($info['price'] !='0.00')){?> - <?=$info['price']?><?}?></h4>
					<p style="padding-bottom:0;"><?=$info['name']?> <span style="font-style:italic;"><?=$info['s_description']?></span></p>
					<?
					$previous = $unix_date;
					}
					}?>
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
