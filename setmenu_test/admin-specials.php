<?
session_start();
if(!isset($_SESSION['Admin_check']))
{
	header("location: admin.php");
}
//Check that they have the security permission to change the menu
//Connect to db
include('dbconnect.php');
$sql_specials_Check = "SELECT * FROM Admin WHERE User='".mysql_real_escape_string($_SESSION['Admin_check'])."' AND Specials = '1'";
$rt_User_Check = mysql_query($sql_specials_Check);
if(!$User = mysql_fetch_array($rt_User_Check)){
	$Error = urlencode("Sorry you don't have permissions to access this part of the site");
	header("location: admin-options.php?Error=".$Error);
} else {
	$page_title="Current Specials | Monte's Grill &amp; Pub";
	$sql_specials_Check = "SELECT * FROM Admin WHERE User='".mysql_real_escape_string($_SESSION['Admin_check'])."' AND Specials = '1'";
	$rt_User_Check = mysql_query($sql_specials_Check);
	if(!$User = mysql_fetch_array($rt_User_Check)) {
		$Error = urlencode("Sorry you don't have permissions to access this part of the site");
		header("location: Options.php?Error=".$Error);
	}

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
}
?>
<?include '../includes/header-admin.php';?>
<body>
<div id="specials" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include '../includes/navigation-admin.php';?>
	<div id="content" data-role="content">
		<?include '../includes/navigation-admin-specials.php';?>
		<h1>Current Specials</h1>
		<?php
			$previous = '';
			$found = false;
			$specialCount=0;
		?>
		<div id="menu-nav-food-specials<?=$specialCount?>" rel="#food-specials<?=$specialCount?>" data-role="collapsible" data-collapsed="true" data-content-theme="a">
			<div id="food-specials<?=$specialCount?>" class="food-menu">
				<table id="myTable" class="tablesorter">
					<thead>
					<tr>
						<th>Type</th>
						<th>Special</th>
						<th>Price</th>
						<th>Date</th>
						<th>Day</th>
						<th>Description</th>
						<!--
						<th>DOW</th>
						<th>Sort</th>
						-->
					</tr>
					</thead>
					<tbody>
					<?foreach($special_items as $info) {?>
					<?
						date_default_timezone_set('America/Chicago');
						if($info['special_start_date'] > 0){
							$date = $info['special_start_date'];
							$unix_date = strtotime ($date);
						} else {
							$repeat_day = $info['repeat_day'];
							$unix_date = strtotime("this $repeat_day");
						}
					?>
					<tr bgcolor="#D3D3D3">
						<td><?=$info['st_description']?></td>
						<td><?=$info['name']?></td>
						<td><?=$info['price']?></td>
						<td nowrap><?=date("F d Y", $unix_date)?></td>
						<td><?=date("l", $unix_date)?></td>
						<td><?=$info['s_description']?></td>
						<!--
						<td><?=$info['DOW']?></td>
						<td><?=$info['SORT']?></td>
						-->
					</tr>
					<?}?>
					</tbody>
				</table>
			</div><!-- end food-specials -->
		<? mysql_close();?>
		</div><!-- end menu-nav-food-specials -->
	</div><!-- end content -->
</div><!-- end specials -->
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("table").tablesorter({
		//sortList: [[3,0]]
		headers{
			3 : { sorter: "shortDate"  }
		}
	});
});
</script>
</body>
</html>