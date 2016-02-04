<?
session_start();
if(!isset($_SESSION['Admin_check']))
{
	header("location: admin.php");
}
//Check that they have the security permission to change the menu
//Connect to db
include('dbconnect.php');
$sql_specials_Check = "SELECT * FROM Admin WHERE User='".mysql_real_escape_string($_SESSION['Admin_check'])."' AND Events = '1'";
$rt_User_Check = mysql_query($sql_specials_Check);
if(!$User = mysql_fetch_array($rt_User_Check))
{
	$Error = urlencode("Sorry you don't have permissions to access this part of the site");
	header("location: admin-options.php?Error=".$Error);
}
$page_title="Events | Monte's Grill &amp; Pub";
//get date
if(isset($_GET['Month']) && isset($_GET['Year']))
{
	switch($_GET['Month'])
	{
		case 1:
			$month = 'January';
			break;
		case 2:
			$month = 'February';
			break;
		case 3:
			$month = 'March';
			break;
		case 4:
			$month = 'April';
			break;
		case 5:
			$month = 'May';
			break;
		case 6:
			$month = 'June';
			break;
		case 7:
			$month = 'July';
			break;
		case 8:
			$month = 'August';
			break;
		case 9:
			$month = 'September';
			break;
		case 10:
			$month = 'October';
			break;
		case 11:
			$month = 'November';
			break;
		case 12:
			$month = 'December';
			break;
		default:
			$month = date('m',time());
	}
	if((0 < $_GET['Month'] && $_GET['Month'] < 13) && (2007 < $_GET['Year'] && $_GET['Year'] < 2100))
	{
		$now = getdate(strtotime('1 '.$month." ".$_GET['Year']));
	}else
	{
		$now = getdate();
	}
}else
{
	$now = getdate();
}
$FirstDayofWeek = date('w',strtotime('1 '.$now['month'].' '.$now['year']));
$Days = cal_days_in_month(CAL_GREGORIAN, $now['mon'], $now['year']);
$SubYear = 0;

$sql_GetEvents = "SELECT * FROM Events where Year = '".$now['year']."' AND Month = '".$now['mon']."' ORDER BY Day";
//echo $sql_GetEvents."<br>";
if(!$rts_Events = mysql_query($sql_GetEvents))
{
	echo "sql Problem ". mysql_error();
}elseif(!$Event = mysql_fetch_array($rts_Events))
{
//	echo "getting results problem " . mysql_error();
}

?>
<?include '../includes/header-admin.php';?>
<body>
<div id="specials" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include '../includes/navigation-admin.php';?>
	<div id="content" data-role="content">
		<?include '../includes/navigation-admin-events.php';?>
		<h1>Events</h1>
      <table width="784" border="1" cellspacing="0" cellpadding="2">
        <tr>
          <td width="112"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Month=<? if($now['mon'] == 1){ echo "12";$SubYear = 1;}else{echo $now['mon'] - 1;} ?>&Year=<? if($SubYear == 1){ echo $now['year'] - 1;}else{echo $now['year'];} ?>">&lt;&lt;</a></td>
          <td colspan="5"><div align="center"><? echo $now['month']." ".$now['year']?></div></td>
          <td width="112"><div align="right"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Month=<? if($now['mon'] == 12){ echo "1";$SubYear = 2;}else{echo $now['mon'] + 1;} ?>&Year=<? if($SubYear == 2){ echo $now['year'] + 1;}else{echo $now['year'];} ?>">&gt;&gt;</a></div></td>
        </tr>
        <tr>
          <td valign="top"><div align="center">Sunday</div></td>
          <td width="112" valign="top"><div align="center">Monday</div></td>
          <td width="112" valign="top"><div align="center">Tuesday</div></td>
          <td width="112" valign="top"><div align="center">Wednesday</div></td>
          <td width="112" valign="top"><div align="center">Thurday</div></td>
          <td width="112" valign="top"><div align="center">Friday</div></td>
          <td valign="top"><div align="center">Saturday</div></td>
        </tr>
				<?
				//Top row
						echo "<tr>";
					$Day = 1;
					for($i = 0; $i < $FirstDayofWeek || $i < 7 ; $i++)
					{
						echo "<td valign=\"top\">";
						if($i >= $FirstDayofWeek)
						{
							echo "<div align='right'>".$Day."</div>";
							while($Event['Day'] == $Day)
							{
								echo "<a href='event-detail.php?Eventid=".$Event['id']."'>".$Event['Title']."</a><br />";
								if(!$Event = mysql_fetch_array($rts_Events))
								{//No events left, Day to 40 so it won't ever be true
									$Event['Day'] = 40;
								}
							}
							$Day++;
						}else
						{
							echo "&nbsp;";
						}
						echo "</td>";
					}
					echo "</tr>";
				//Print Rest of Table
					for($i = 0; $Day <= $Days || $i%7 != 0; $i++)
					{
						if($i%7 == 0)
						{
							echo "<tr>";
						}
						if($Day <= $Days)
						{
							echo "<td valign=\"top\"><div align='right'>".$Day."</div>";
							while($Event['Day'] == $Day)
							{
								echo "<a href='event-detail.php?Eventid=".$Event['id']."'>".$Event['Title']."</a><br />";
								if(!$Event = mysql_fetch_array($rts_Events))
								{//No events left, Day to 40 so it wont ever be true
									$Event['Day'] = 40;
								}
							}
							echo "</td>";
							$Day++;
						}else
						{
							echo "<td>&nbsp;</td>";
						}
						if($i%7 == 6)
						{
							echo "</tr>";
						}
					}
				?>
      </table>
		</div><!-- end menu-nav-food-specials -->
	</div><!-- end content -->
</div><!-- end specials -->
</body>
</html>