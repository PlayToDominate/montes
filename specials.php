<?include('includes/dbconnect.php');?>
<?php
	$page_title="Specials | Monte's Grill &amp; Pub";

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
<?include 'includes/header.php';?>
<body id="menu">
<div id="specials" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include 'includes/navigation.php';?>
	<div id="content" data-role="content">
		<h1>Specials</h1>
		<div id="menu-nav-food-specials" rel="#food-specials" data-role="collapsible" data-collapsed="true">
			<h3>Food Specials</h3>
			<div id="food-specials" class="food-menu">
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
					if ($unix_date >= time()) {
					$found = true;
				?>
				<?if ( $unix_date != $previous) {?>
				<h2><?=date("l, F jS", $unix_date)?></h2>
				<?}?>
				<!--<p>Today: <?=date("l, F jS h:i a", $today_orig)?><br />Yesterday: <?=date("l, F jS h:i a", $yesterday)?><br />Tomorrow: <?=date("l, F jS h:i a", $tomorrow)?><br />Special Date: <?=$info['special_start_date']?></p>-->
				<h4><?=$info['st_description']?><?if(($info['price'] !='N/A') && ($info['price'] !='0.00')){?> - <?=$info['price']?><?}?></h4>
				<p><?=$info['name']?></p>
				<p style="font-style:italic;"><?=$info['s_description']?></p>
				<?
				$previous = $unix_date;
				}
				}?>
				<?if(!$found){?>
				<p>Sorry, no posted Specials today. Please check back soon!</p>
				<?}?>
			</div>
		</div>
		<div id="menu-nav-drink-specials" rel="#drink-specials" data-role="collapsible" data-collapsed="true">
			<h3>Happy Hour/Drink Specials</h3>
			<div id="drink-specials" class="food-menu">
				<h4>Happy Hour (2-6pm Monday - Friday)</h4>
				<p>Domestic Bottles: 2.75<br />Rail Mixers: 2.75<br />16oz Tap Beer: 3.75<br />50&cent; off call mixers, imports and craft bottles</p>
				<h4>Late Night Happy Hour (10pm-Midnight Everyday)</h4>
				<p>50&cent; off (not including high-end alcohol and craft beer)</p>
				<h4>Bartender Specials</h4>
				<p>$3.50 tall of Captain Morgan Black<br />$2.75 Bacardi Mixers</p>
				<h4>Beer Specials</h4>
				<p>Tyranena 3 Beaches, Murphy's Stout, and Chick Beer for only <span class="bold italic">$1</span><br />Guinness: $2</p>
			</div>
		</div>
	</div>
	<?include 'includes/right_bar.php';?>
	<?include 'includes/footer.php';?>
</div>
</body>
</html>
