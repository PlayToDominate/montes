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
$page_title="Modify Events | Monte's Grill &amp; Pub";
if(isset($_POST['ChangeMe']))
{ // Update Event
	$Title = mysql_real_escape_string($_POST['Title']);
	$Des = mysql_real_escape_string($_POST['Description']);
	$Year = mysql_real_escape_string($_POST['Year']);
	$Month = mysql_real_escape_string($_POST['Month']);
	$Day = mysql_real_escape_string($_POST['Day']);
	$Weight = mysql_real_escape_string($_POST['Weight']);
	$sql_ChangeEvent = "UPDATE Events SET Title = '".$Title."',Description='".$Des."',Year='".$Year."',Month='".$Month."',Day='".$Day."', Weight='".$Weight."' WHERE id='".mysql_real_escape_string($_POST['Event_id'])."'";
	mysql_query($sql_ChangeEvent);
	if($_POST['recurr_Change'] == 'Yes')
	{
		$sql_RecurrEvent .= "UPDATE Events SET Title = '".$Title."',Description='".$Des."', Weight='".$Weight."' WHERE recurr_id = '".mysql_real_escape_string($_POST['recurr_id'])."'";
		mysql_query($sql_RecurrEvent);
	}
}
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
	$Mode = 'Select';
}elseif(isset($_GET['Eventid']))
{// Checking event id. Offer confirmation of delete event
	$sql_getEvent = "SELECT * FROM Events WHERE id = '".mysql_real_escape_string($_GET['Eventid'])."'";
	$rt_Event = mysql_query($sql_getEvent);
	$now = getdate();
	$Mode = 'Change';
}else
{
	$now = getdate();
	$Mode = 'Select';
}
if($Mode =='Select')
{
	$FirstDayofWeek = date('w',strtotime('1 '.$now['month'].' '.$now['year']));
	$Days = cal_days_in_month(CAL_GREGORIAN, $now['mon'], $now['year']);
	$SubYear = 0;

	$sql_GetEvents = "SELECT * FROM Events where Year = '".$now['year']."' AND Month = '".$now['mon']."' ORDER BY Day,Weight";
	//echo $sql_GetEvents."<br>";
	if(!$rts_Events = mysql_query($sql_GetEvents))
	{
		echo "sql Problem ". mysql_error();
	}elseif(!$Event = mysql_fetch_array($rts_Events))
	{
	//	echo "getting results problem " . mysql_error();
	}
}
?>
<?include '../includes/header-admin.php';?>
<body>
<div id="specials" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include '../includes/navigation-admin.php';?>
	<div id="content" data-role="content">
		<?include '../includes/navigation-admin-events.php';?>
		<h1>Modify Events</h1>
<?
switch($Mode)
{
	case 'Change':
		if($Event = mysql_fetch_array($rt_Event))
		{
?>
		<form action="<? echo $_SERVER['PHP_SELF']?>" method="post">
        	<input type="hidden" name="Event_id" id="Event_id" value="<? echo $Event['id'];?>" />
			<div class="BodyType"><strong>Title:</strong>           <input type="text" name="Title" id="Title" value='<? echo $Event['Title']; ?>' /><br />
            <strong>Description:</strong> <br />
			          <textarea name="Description" id="Description" cols="45" rows="5">
<? echo $Event['Description'] ?></textarea><br />
            <strong>Date: </strong>
          <select name="Month" id="Month">
            <option value="1" <? if( $Event['Month'] == '1'){echo " selected=\"selected\""; } ?>>Jan</option>
            <option value="2" <? if( $Event['Month'] == '2'){echo " selected=\"selected\""; } ?>>Feb</option>
            <option value="3" <? if( $Event['Month'] == '3'){echo " selected=\"selected\""; } ?>>Mar</option>
            <option value="4" <? if( $Event['Month'] == '4'){echo " selected=\"selected\""; } ?>>Apr</option>
            <option value="5" <? if( $Event['Month'] == '5'){echo " selected=\"selected\""; } ?>>May</option>
            <option value="6" <? if( $Event['Month'] == '6'){echo " selected=\"selected\""; } ?>>Jun</option>
            <option value="7" <? if( $Event['Month'] == '7'){echo " selected=\"selected\""; } ?>>Jul</option>
            <option value="8" <? if( $Event['Month'] == '8'){echo " selected=\"selected\""; } ?>>Aug</option>
            <option value="9" <? if( $Event['Month'] == '9'){echo " selected=\"selected\""; } ?>>Sept</option>
            <option value="10" <? if( $Event['Month'] == '10'){echo " selected=\"selected\""; } ?>>Oct</option>
            <option value="11" <? if( $Event['Month'] == '11'){echo " selected=\"selected\""; } ?>>Nov</option>
            <option value="12" <? if( $Event['Month'] == '12'){echo " selected=\"selected\""; } ?>>Dec</option>
            </select>
        /
        <select name="Day" id="Day">
        <?
			$ct_i = 1;
			while($ct_i < 31)
			{
				echo "<option value=\"".$ct_i."\"";
				if($ct_i == $Event['Day'])
				{
					echo "selected=\"selected\"";
				}
				echo " >".$ct_i."</option>";
				$ct_i++;
			}
		?>
        </select>
        /
        <select name="Year" id="Year">
        <?
        	echo "<option value=\"".$now['year']."\"";
			if($now[''] == $Event['Year'])
			{
				echo  " selected=\"selected\"";
			}
			echo ">".$now['year']."</option>";
			$ct_i = 1;
			while($ct_i < 10)
			{
				$year = $now['year'] + $ct_i;
				echo "<option value=\"".year."\" >".$year."</option>";
				if($year == $Event['Year'])
				{
					echo "selected=\"selected\"";
				}
				$ct_i++;
			}
		?>
        </select>
		<br />
		<strong>Order: <select name="Weight" id="Weight">
        <?
			$ct_i = 1;
			while($ct_i < 10)
			{
				echo "<option value=\"".$ct_i."\"";
				if($ct_i == $Event['Weight'])
				{
					echo "selected=\"selected\"";
				}
				echo " >".$ct_i."</option>";
				$ct_i++;
			}
		?>
          </select>
          </strong>(1 to 10, 1 is higher up the list)<br />
		<?
		if($Event['recurr_id'] != '0')
		{
			echo '<input type="hidden" name="recurr_id" id="recurr_id" value="'.$Event['recurr_id'].'" />';
			echo"This event happens more then once. <br /> Should we update all recurrances of this event?<br />(Date changes will only affect this event)<br />";
			echo '<input type="radio" name="recurr_Change" id="recurr_yes" value="Yes" />Yes<br />';
			echo '<input type="radio" name="recurr_Change" id="recurr_no" value="no" />No';
		}else
		{
			echo '<input type="hidden" name="recurr_Change" value="no" />';
		}
?>
            <input type="submit" name="ChangeMe" id="ChangeMe" value="Change Me" />
            </div>
      </form>
<?
		}else
		{
			echo "Sorry that event has been deleted";
		}
		break;
	case "Select":
	default:

?>
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
								echo "<a href='event-modify.php?Eventid=".$Event['id']."'>".$Event['Title']."</a><br />";
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
								echo "<a href='event-modify.php?Eventid=".$Event['id']."'>".$Event['Title']."</a><br />";
								if(!$Event = mysql_fetch_array($rts_Events))
								{//No events left, Day to 40 so it won't ever be true
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
				<?
					break;
				}
				?>
		</div><!-- end menu-nav-food-specials -->
	</div><!-- end content -->
</div><!-- end specials -->
</body>
</html>