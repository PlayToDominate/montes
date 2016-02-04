<?include('includes/dbconnect.php');?>
<?php
	$page_title="Specials | Monte's Grill &amp; Pub";

	$daily_specials = mysql_query("
SELECT
special_start_date,
special_end_date,
name,
price,
repeat,
special.description as s_description,
special_type.description as st_description
FROM special, special_date, special_type
WHERE
special.special_id = special_date.special_id
AND special.special_type_id = special_type.special_type_id
ORDER BY special_start_date ASC, st_description DESC
		")
	or die(mysql_error());

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
					$previous = '';
					$found = false;
					while($info = mysql_fetch_array( $daily_specials)) {
					$today_date = getDate();
					$unix_today_date = strtotime ($today_date);
					$date = $info['special_end_date'];
					$unix_end_date = strtotime ($date);
					$start_date = $info['special_start_date'];
					$unix_start_date = strtotime ($start_date);
					$st_description_bad=$info['st_description'];
					$st_description = str_replace("\\", "", $st_description_bad );
					if ($unix_start_date >= time() ) {
					$found = true;
				?>
				<?if ( $unix_start_date != $previous) {?>
				<h2><?=date("l, F jS", $unix_start_date)?></h2>
				<?}?>
				Special Date: <?=date('w', $unix_start_date)?>
				<!--Today Date: <?=date('w', $unix_today_date)?>-->
				Repeat #: <?=$info['repeat']?>
				<?if($info['repeat'] == date('w', $unix_start_date)){?>
				<h5>THIS ONE!</H5>
				<h4><?=$st_description?><?if($info['price'] !='N/A'){?> - <?=$info['price']?><?}?></h4>
				<p><?=$info['name']?></p>
				<?}?>
				<h4><?=$st_description?><?if($info['price'] !='N/A'){?> - <?=$info['price']?><?}?></h4>
				<p><?=$info['name']?></p>
				<?
				$previous = $unix_start_date;
				}}?>
				<?if(!$found){?>
				<h3>Food Specials</h3>
				<p>Sorry, no posted Food Specials today. Please check back soon!</p>
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
