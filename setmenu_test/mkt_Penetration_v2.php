<?

    function d($string) {
      global $DEBUG;
      if ($DEBUG) {
        print "<P class=\"debug\">(Revision:02-26-2008)<br>$string</P>\n";
      }
    }

	require 'database.php';
	require 'widgets.php';
    require 'COMMON_FUNCTIONS.php';
	require 'standard_report.php';

    $DEBUG      = $_REQUEST['debug'];
 	$site     	= $_REQUEST['site'];
 	$server    	= "seattle";
 	$group_by  	= "";
 	$srch_args = "";
 	$criteria = "";

	$conn1		= connect($site);
	$conn2		= connect($site);
//	$conn3		= connect($site);

	$criteria= "";


//*************************************************
// Report info
// Revisions:                
//	sr	09/24/04	Add cd_Group Search and Drill Down
//	sr	11/12/04	JhnS punch list
//	sr	11/17/04	JhnS punch list.  Header based re-sort
//                  Variablize site and server
//	sr	11/18/04	URL encode spaces in sort links
//	sr	12/12/04	Change ListingZip SQL to LIKE
//                  Change to _v2 for implementation.
//	sr	12/16/04	Fix LIKE = SQL error
//	sr	02/26/08	Add debug
//*************************************************
$report_name = "Market Penetration Report";
$this_file = "mkt_Penetration_v2.php";

//*************************************************
// Base SQL
//*************************************************
	$search_arg_sql= "";
	$where    = "   WHERE RPT_Mkt_Share.cd_MLS = Listing.cd_MLS AND RPT_Mkt_Share.Agent_Type    = 'L' ";

	$from	  = " FROM RPT_Mkt_Share, Listing ";

    $maxrecords = 250;

    if (array_key_exists("maxrecords", $_REQUEST))
    {
    	$maxrecords = $_REQUEST['maxrecords'];
	}



if (array_key_exists('order_by', $_REQUEST))
{
    $order_by = $_REQUEST['order_by'];
	if ($order_by == "LIST_UNITS_CY") {
	    $sort = " ORDER BY
	    		     SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN 1 ELSE 0 END)
	    		  DESC ";
	    $criteria = $criteria."Sorted By List Units Current Period"."<BR>";
	}

	if ($order_by == "MKT_SHARE_CY") {
	    $sort = " ORDER BY
					(SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN 1 ELSE 0 END))/<Pop_no_Listings_CY>.00
				  DESC";
	    $criteria = $criteria."Sorted By Market Share Current Period"."<BR>";
	}

	if ($order_by == "LIST_UNITS_PY") {
	    $sort = " ORDER BY SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN 1 ELSE 0 END) DESC ";
	    $criteria = $criteria."Sorted By List Units Previous Year"."<BR>";
	}

	if ($order_by == "MKT_SHARE_PY") {
	    $sort = " ORDER BY
					(SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN 1 ELSE 0 END))/<Pop_no_Listings_PY>.00
				  DESC";
	    $criteria = $criteria."Sorted By Market Share Previous Year"."<BR>";
	}

	if ($order_by == "LIST_CHGPCT") {
	    $sort = " ORDER BY
					(SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN 1 ELSE 0 END))/<Pop_no_Listings_CY>.00-
					(SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN 1 ELSE 0 END))/<Pop_no_Listings_PY>.00
				  DESC";
	    $criteria = $criteria."Sorted By % Change In Market Share"."<BR>";
	}

	if ($order_by == "LIST_VOL_CY") {
	    $sort = " ORDER BY SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN RPT_Mkt_Share.Listing_Price ELSE 0 END) DESC";
	    $criteria = $criteria."Sorted By List Volume Current Period"."<BR>";
	}

	if ($order_by == "VOL_SHARE_CY") {
	    $sort = " ORDER BY
				  	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN RPT_Mkt_Share.Listing_Price ELSE 0 END)/<Pop_Listing_Vol_CY>
	    		  DESC";
	    $criteria = $criteria."Sorted By Volume Share Current Period"."<BR>";
	}

	if ($order_by == "LIST_VOL_PY") {
	    $sort = " ORDER BY SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN RPT_Mkt_Share.Listing_Price ELSE 0 END) DESC";
	    $criteria = $criteria."Sorted By List Volume Previous Year"."<BR>";
	}

	if ($order_by == "VOL_SHARE_PY") {
	    $sort = " ORDER BY
				  	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN RPT_Mkt_Share.Listing_Price ELSE 0 END)/<Pop_Listing_Vol_PY>
	    		  DESC";
	    $criteria = $criteria."Sorted By Volume Share Previous Year"."<BR>";
	}

	if ($order_by == "LIST_VOL_CHGPCT") {
	    $sort = " ORDER BY
	    		  	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN RPT_Mkt_Share.Listing_Price ELSE 0 END)/<Pop_Listing_Vol_CY> -
					SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN RPT_Mkt_Share.Listing_Price ELSE 0 END)/<Pop_Listing_Vol_PY>
 			      DESC";
	    $criteria = $criteria."Sorted By % Change In Volume Share"."<BR>";
	}

	if ($order_by == "LIST_CHG") {
	    $sort = " ORDER BY
	    		     SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN 1 ELSE 0 END) -
					 SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN 1 ELSE 0 END)
	    		  DESC";
	    $criteria = $criteria."Sorted By Change In Market Share"."<BR>";
	}

	if ($order_by == "LIST_VOL_CHG") {
	    $sort = " ORDER BY
						SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN RPT_Mkt_Share.Listing_Price ELSE 0 END)-
						SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN RPT_Mkt_Share.Listing_Price ELSE 0 END)
				  DESC";
	    $criteria = $criteria."Sorted By Change In Volume Share"."<BR>";
	}



	if ($order_by == "OFFICE") {
	    $sort = " ORDER BY SUM(RPT_Mkt_Share.cd_Office) ASC";
	    $criteria = $criteria."Sorted By Office"."<BR>";
	}
	if ($order_by == "AGENT") {
	    //$sort = " ORDER BY SUM(RPT_Mkt_Share.cd_Agent) DESC";
	    $sort = " ORDER BY UPPER(RPT_Mkt_Share.LastName) ASC";
	    $criteria = $criteria."Sorted By Agent Last Name"."<BR>";
	}
    $srch_args = $srch_args."&order_by=".$_REQUEST['order_by'];
}



//OFFICE AGENT COMPANY
$group_by ="OFFICE";
if (array_key_exists('group_by', $_REQUEST))
{
	$group_by = $_REQUEST['group_by'];
}

if ($group_by == "COMPANY")
{
	$select_clause = " CASE WHEN  MAX(RPT_Office_Groups.ds_Group) is NOT null THEN MAX(RPT_Office_Groups.ds_Group) ELSE MAX(RPT_Mkt_Share.nm_Office) END AS grp_Name,
					   CASE WHEN  MAX(RPT_Office_Groups.ds_Group) is NOT null THEN MAX(RPT_Office_Groups.cd_Group) ELSE NULL END AS cd_Group,
					   CASE WHEN  MAX(RPT_Office_Groups.ds_Group) is null THEN MAX(RPT_Mkt_Share.cd_Office) ELSE NULL END AS cd_Office,
					   CASE WHEN  MAX(RPT_Office_Groups.ds_Group) is null THEN MAX(RPT_Mkt_Share.cd_Source_Office) ELSE NULL END AS cd_Source_Office,	";

	$where    = " WHERE RPT_Mkt_Share.cd_MLS   = Listing.cd_MLS
				  AND RPT_Mkt_Share.Agent_Type    = 'L'
                  AND RPT_Mkt_Share.cd_Office *= RPT_Office_Groups.cd_Office
                  AND RPT_Mkt_Share.cd_Source_Office *= RPT_Office_Groups.cd_Source ";


	$grouping = " GROUP BY CASE WHEN RPT_Office_Groups.cd_Group is not null THEN cd_Group ELSE RPT_Mkt_Share.cd_Office END";
	$from	  = " FROM RPT_Mkt_Share, Listing, RPT_Office_Groups ";
}


if ($group_by == "OFFICE")
{
	$select_clause = " MAX(RPT_Mkt_Share.nm_Office) as grp_Name, MAX(RPT_Mkt_Share.cd_Office) as cd_Office,MAX(RPT_Mkt_Share.cd_Source_Office) as cd_Source_Office,	";

	$grouping = "   GROUP BY RPT_Mkt_Share.cd_Office,RPT_Mkt_Share.cd_Source_Office";
}

if ($group_by == "AGENT")
{
	$select_clause = " MAX(RPT_Mkt_Share.FirstName+' '+RPT_Mkt_Share.LastName) as grp_Name,
	                   MAX(RPT_Mkt_Share.nm_Office) as nm_Office,
	                   MAX(RPT_Mkt_Share.cd_Agent) AS cd_Agent,	";

	$grouping = "   GROUP BY RPT_Mkt_Share.cd_Agent";
}
if ($group_by == "LISTING")
{
	$select_clause = " MAX(RPT_Mkt_Share.MLS_Number) as grp_Name,
	                   MAX(RPT_Mkt_Share.FirstName+' '+RPT_Mkt_Share.LastName) as nm_Agent,
	                   MAX(RPT_Mkt_Share.cd_Agent) AS cd_Agent,	   ";

	$grouping = "   GROUP BY RPT_Mkt_Share.cd_MLS";
}



$sql_totals = "SELECT

	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN 1 ELSE 0 END) AS Pop_no_Listings_CY,
	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN 1 ELSE 0 END) AS Pop_no_Listings_PY,

	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN RPT_Mkt_Share.Listing_Price ELSE 0 END) AS Pop_Listing_Vol_CY,
	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN RPT_Mkt_Share.Listing_Price ELSE 0 END) AS Pop_Listing_Vol_PY ".$from;


$sql = "SELECT ".
	$select_clause.

	"SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN 1 ELSE 0 END) -
	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN 1 ELSE 0 END)
		AS no_Listing_Chg,

	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN RPT_Mkt_Share.Listing_Price ELSE 0 END)-
	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN RPT_Mkt_Share.Listing_Price ELSE 0 END)
		AS Listing_Vol_Chg,

    SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN 1 ELSE 0 END) AS no_Listings_CY,
	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN 1 ELSE 0 END) AS no_Listings_PY,

    (SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN 1 ELSE 0 END))/<Pop_no_Listings_CY>.00
    	AS PctTot_Listings_CY,
	(SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN 1 ELSE 0 END))/<Pop_no_Listings_PY>.00
		AS PctTot_Listings_PY,
	(SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN 1 ELSE 0 END))/<Pop_no_Listings_CY>.00-
	(SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN 1 ELSE 0 END))/<Pop_no_Listings_PY>.00
		AS	PctTot_Listings_CHG,

	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN RPT_Mkt_Share.Listing_Price ELSE 0 END) AS Listing_Vol_CY,
	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN RPT_Mkt_Share.Listing_Price ELSE 0 END) AS Listing_Vol_PY,
	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN RPT_Mkt_Share.Listing_Price ELSE 0 END)/<Pop_Listing_Vol_CY>
		AS PctTot_Listing_Vol_CY,
	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN RPT_Mkt_Share.Listing_Price ELSE 0 END)/<Pop_Listing_Vol_PY>
		AS PctTot_Listing_Vol_PY,

	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= '<STARTDATE>' AND RPT_Mkt_Share.Listing_Date <= '<ENDDATE>' THEN RPT_Mkt_Share.Listing_Price ELSE 0 END)/<Pop_Listing_Vol_CY> -
	SUM(CASE WHEN RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR, -1,'<STARTDATE>') AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR, -1,'<ENDDATE>') THEN RPT_Mkt_Share.Listing_Price ELSE 0 END)/<Pop_Listing_Vol_PY>
		AS PctTot_Listing_Vol_CHG	".$from;


	$sort1="";
	$tp_Improvement = Array("");
	$cd_Source = Array("");
	$cd_Office = Array("");
	$cd_Group = Array("");
	$ListingStatus = Array("");
	$ListingCity = Array("");
	$cd_Geo1 = Array("");
	$cd_Geo2 = Array("");
	$cd_Geo3 = Array("");
	$cd_Geo4 = Array("");


	$tp_2=Array("");


	if (array_key_exists("cd_Source", $_REQUEST))
	{

		$cd_Source = $_REQUEST['cd_Source'];
		$criteria = $criteria."MLS Source Equals ".array_to_string($cd_Source, " OR ");

		if (count($cd_Source) > 0)
		{
			for ($i=0; $i < count($cd_Source); $i++)
			{
				if (strlen($cd_Source[$i]) > 0)
				{
					if (strpos($where, " AND (RPT_Mkt_Share.cd_Source") === FALSE)
					{
						$where = $where." AND (RPT_Mkt_Share.cd_Source = '".$cd_Source[$i]."' ";
						$search_arg_sql=$search_arg_sql." AND (RPT_Mkt_Share.cd_Source = '".$cd_Source[$i]."' ";
						$srch_args = $srch_args."&cd_Source[]=".$cd_Source[$i];
					}
					else
					{
						$where = $where." OR RPT_Mkt_Share.cd_Source = '".$cd_Source[$i]."' ";
						$search_arg_sql=$search_arg_sql." OR RPT_Mkt_Share.cd_Source = '".$cd_Source[$i]."' ";
						$srch_args = $srch_args."&cd_Source[]=".$cd_Source[$i];
					}
				}
			}
			if (strpos($where, " AND (RPT_Mkt_Share.cd_Source") > -1)
			{
				$where = $where.") ";
				$search_arg_sql=$search_arg_sql.")";
			}
			$criteria = $criteria."<BR>";
		}

	}


	if (array_key_exists("tp_Improvement", $_REQUEST))
	{

		$tp_Improvement = $_REQUEST['tp_Improvement'];
		//$criteria = $criteria."Improvement Type Equals ".array_to_string($tp_Improvement, " OR ");
		$criteria = $criteria."Improvement Type Equals ";

		if (count($tp_Improvement) > 0)
		{
			for ($i=0; $i < count($tp_Improvement); $i++)
			{
				if (strlen($tp_Improvement[$i]) > 0)
				{
					if (strpos($where, " AND (tp_Improvement") === FALSE)
					{
					    list($code, $description) = split(":",$tp_Improvement[$i]);
						$criteria = $criteria.$description;
						$where = $where." AND (tp_Improvement = '".$code."' ";
						$search_arg_sql=$search_arg_sql." AND (tp_Improvement = '".$code."' ";
						$srch_args = $srch_args."&tp_Improvement[]=".$tp_Improvement[$i];
					}
					else
					{
						list($code, $description) = split(":",$tp_Improvement[$i]);
						$criteria = $criteria." OR ".$description;
						$where = $where." OR tp_Improvement = '".$code."' ";
						$search_arg_sql=$search_arg_sql." OR tp_Improvement = '".$code."' ";
						$srch_args = $srch_args."&tp_Improvement[]=".$tp_Improvement[$i];
					}
				}
			}
			if (strpos($where, " AND (tp_Improvement") > -1)
			{
				$where = $where.") ";
				$search_arg_sql=$search_arg_sql.")";
			}
			$criteria = $criteria."<BR>";
		}

	}

	if (array_key_exists("cd_Group", $_REQUEST))
	{

		$cd_Group = $_REQUEST['cd_Group'];
		$criteria = $criteria."Company Equals ".array_to_string($cd_Group, " OR ");

		if (count($cd_Group) > 0)
		{
			for ($i=0; $i < count($cd_Group); $i++)
			{
				if (strlen($cd_Group[$i]) > 0)
				{
					if (strpos($where, " AND (RPT_Mkt_Share.cd_Office IN (SELECT cd_Office from RPT_Office_Groups WHERE cd_Group=") === FALSE)
					{
						$where = $where." AND (RPT_Mkt_Share.cd_Office IN (SELECT cd_Office from RPT_Office_Groups WHERE cd_Group= '".$cd_Group[$i]."') ";
						$search_arg_sql=$search_arg_sql." AND (RPT_Mkt_Share.cd_Office IN (SELECT cd_Office from RPT_Office_Groups WHERE cd_Group= '".$cd_Group[$i]."') ";
						$srch_args = $srch_args."&cd_Group[]=".$cd_Group[$i];
					}
					else
					{
						$where = $where." OR RPT_Mkt_Share.cd_Office IN (SELECT cd_Office from RPT_Office_Groups WHERE cd_Group= '".$cd_Group[$i]."') ";
						$search_arg_sql=$search_arg_sql." OR RPT_Mkt_Share.cd_Office IN (SELECT cd_Office from RPT_Office_Groups WHERE cd_Group= '".$cd_Group[$i]."') ";
						$srch_args = $srch_args."&cd_Group[]=".$cd_Group[$i];
					}
				}
			}
			if (strpos($where, " AND (RPT_Mkt_Share.cd_Office IN (SELECT cd_Office from RPT_Office_Groups WHERE cd_Group=") > -1)
			{
				$where = $where.") ";
				$search_arg_sql=$search_arg_sql.")";
			}
			$criteria = $criteria."<BR>";
		}

	}

	if (array_key_exists("cd_Office", $_REQUEST))
	{

		$cd_Office = $_REQUEST['cd_Office'];
		$criteria = $criteria."Office Equals ".array_to_string($cd_Office, " OR ");

		if (count($cd_Office) > 0)
		{
			for ($i=0; $i < count($cd_Office); $i++)
			{
				if (strlen($cd_Office[$i]) > 0)
				{
					if (strpos($where, " AND (RPT_Mkt_Share.cd_Office") === FALSE)
					{
						$where = $where." AND (RPT_Mkt_Share.cd_Office = '".$cd_Office[$i]."' ";
						$search_arg_sql=$search_arg_sql." AND (RPT_Mkt_Share.cd_Office = '".$cd_Office[$i]."' ";
						$srch_args = $srch_args."&cd_Office[]=".$cd_Office[$i];
					}
					else
					{
						$where = $where." OR RPT_Mkt_Share.cd_Office = '".$cd_Office[$i]."' ";
						$search_arg_sql=$search_arg_sql." OR RPT_Mkt_Share.cd_Office = '".$cd_Office[$i]."' ";
						$srch_args = $srch_args."&cd_Office[]=".$cd_Office[$i];
					}
				}
			}
			if (strpos($where, " AND (RPT_Mkt_Share.cd_Office") > -1)
			{
				$where = $where.") ";
				$search_arg_sql=$search_arg_sql.")";
			}
			$criteria = $criteria."<BR>";
		}

	}

	if (array_key_exists("cd_Agent", $_REQUEST))
	{

		$cd_Agent = $_REQUEST['cd_Agent'];
		$criteria = $criteria."Agent Equals ".array_to_string($cd_Agent, " OR ");

		if (count($cd_Agent) > 0)
		{
			for ($i=0; $i < count($cd_Agent); $i++)
			{
				if (strlen($cd_Agent[$i]) > 0)
				{
					if (strpos($where, " AND (RPT_Mkt_Share.cd_Agent") === FALSE)
					{
						$where = $where." AND (RPT_Mkt_Share.cd_Agent = ".$cd_Agent[$i]." ";
						$search_arg_sql=$search_arg_sql." AND (RPT_Mkt_Share.cd_Agent = ".$cd_Agent[$i]." ";
						$srch_args = $srch_args."&cd_Agent[]=".$cd_Agent[$i];
					}
					else
					{
						$where = $where." OR RPT_Mkt_Share.cd_Agent = ".$cd_Agent[$i]." ";
						$search_arg_sql=$search_arg_sql." OR RPT_Mkt_Share.cd_Agent = ".$cd_Agent[$i]." ";
						$srch_args = $srch_args."&cd_Agent[]=".$cd_Agent[$i];
					}
				}
			}
			if (strpos($where, " AND (RPT_Mkt_Share.cd_Agent") > -1)
			{
				$where = $where.") ";
				$search_arg_sql=$search_arg_sql.")";
			}
			$criteria = $criteria."<BR>";
		}

	}


	if (array_key_exists("ListingStatus", $_REQUEST))
	{

		$ListingStatus = $_REQUEST['ListingStatus'];
		$criteria = $criteria."Status Equals ".array_to_string($ListingStatus, " OR ");

		if (count($ListingStatus) > 0)
		{
			for ($i=0; $i < count($ListingStatus); $i++)
			{
				if (strlen($ListingStatus[$i]) > 0)
				{
					if (strpos($where, " AND (RPT_Mkt_Share.ListingStatus") === FALSE)
					{
						$where = $where." AND (RPT_Mkt_Share.ListingStatus = '".$ListingStatus[$i]."' ";
						$search_arg_sql=$search_arg_sql." AND (RPT_Mkt_Share.ListingStatus = '".$ListingStatus[$i]."' ";
						$srch_args = $srch_args."&ListingStatus[]=".$ListingStatus[$i];
					}
					else
					{
						$where = $where." OR RPT_Mkt_Share.ListingStatus = '".$ListingStatus[$i]."' ";
						$search_arg_sql=$search_arg_sql." OR RPT_Mkt_Share.ListingStatus = '".$ListingStatus[$i]."' ";
						$srch_args = $srch_args."&ListingStatus[]=".$ListingStatus[$i];
					}
				}
			}
			if (strpos($where, " AND (RPT_Mkt_Share.ListingStatus") > -1)
			{
				$where = $where.") ";
				$search_arg_sql=$search_arg_sql.")";
			}
			$criteria = $criteria."<BR>";
		}

	}

	if (array_key_exists("ListingCity", $_REQUEST))
	{

		$ListingCity = $_REQUEST['ListingCity'];
		$criteria = $criteria."City Equals ".array_to_string($ListingCity, " OR ");

		if (count($ListingCity) > 0)
		{
			for ($i=0; $i < count($ListingCity); $i++)
			{
				if (strlen($ListingCity[$i]) > 0)
				{
					if (strpos($where, " AND (Listing.ListingCity") === FALSE)
					{
						$where = $where." AND (Listing.ListingCity = '".$ListingCity[$i]."' ";
						$search_arg_sql=$search_arg_sql." AND (Listing.ListingCity = '".$ListingCity[$i]."' ";
						$srch_args = $srch_args."&ListingCity[]=".$ListingCity[$i];
					}
					else
					{
						$where = $where." OR Listing.ListingCity = '".$ListingCity[$i]."' ";
						$search_arg_sql=$search_arg_sql." OR Listing.ListingCity = '".$ListingCity[$i]."' ";
						$srch_args = $srch_args."&ListingCity[]=".$ListingCity[$i];
					}
				}
			}
			if (strpos($where, " AND (Listing.ListingCity") > -1)
			{
				$where = $where.") ";
				$search_arg_sql=$search_arg_sql.")";
			}
			$criteria = $criteria."<BR>";
		}

	}

// sr 12-14-2004 Removed for ListingZip LIKE Search
//	if (array_key_exists("mListingZip", $_REQUEST))
//	{
//		$mListingZip = $_REQUEST['mListingZip'];
//		if (strlen($mListingZip) > 0)
//		{
//			$criteria = $criteria."Zipcodes ".str_replace("S","",$mListingZip)."<BR>";
// 			$where = $where." AND Listing.ListingZip IN ( ". str_replace("S","'",$mListingZip) .")";
// 			$search_arg_sql=$search_arg_sql." AND Listing.ListingZip IN ( ". str_replace("S","'",$mListingZip) .")";
// 			$srch_args = $srch_args."&mListingZip=".$mListingZip;
//		}
//	}

	if (array_key_exists("ListingZip", $_REQUEST))
	{

		$ListingZip = $_REQUEST['ListingZip'];
//		$srch_args = $srch_args."&ListingZip=".$_REQUEST['ListingZip']; commented out by joe k 12-17-2004

		if (count($ListingZip) > 0)
		{
			//$criteria = $criteria."Zipcodes"." Equals ".array_to_string($ListingZip, " OR ");
			for ($i=0; $i < count($ListingZip); $i++)
			{
				if (strlen($ListingZip[$i]) > 0)
				{
					if (strpos($where, " AND (Listing.ListingZip") === FALSE)
					{
						$where = $where." AND (Listing.ListingZip LIKE '".$ListingZip[$i]."%' ";
						$search_arg_sql=$search_arg_sql." AND (Listing.ListingZip LIKE '".$ListingZip[$i]."%' ";
						$srch_args = $srch_args."&ListingZip[]=".$ListingZip[$i];
						$criteria = $criteria."Zipcodes"." Begins With ".$ListingZip[$i];
					}
					else
					{
						$where = $where." OR Listing.ListingZip LIKE '".$ListingZip[$i]."%' ";
						$search_arg_sql=$search_arg_sql." OR Listing.ListingZip LIKE '".$ListingZip[$i]."%' ";
						$srch_args = $srch_args."&ListingZip[]=".$ListingZip[$i];
						$criteria = $criteria." OR ".$ListingZip[$i];
					}
				}
			}
			if (strpos($where, " AND (Listing.ListingZip") > -1)
			{
			    $criteria = $criteria."<BR>";
				$where = $where.") ";
				$search_arg_sql=$search_arg_sql.")";
			}
			//$criteria = $criteria."<BR>";
		}

	}


	if (array_key_exists("cd_Geo1", $_REQUEST))
	{

		$cd_Geo1 = $_REQUEST['cd_Geo1'];
		$srch_args = $srch_args."&ds_Geo1=".$_REQUEST['ds_Geo1'];

		if (count($cd_Geo1) > 0)
		{
			$criteria = $criteria.$_REQUEST['ds_Geo1']." Equals ".array_to_string($cd_Geo1, " OR ");
			for ($i=0; $i < count($cd_Geo1); $i++)
			{
				if (strlen($cd_Geo1[$i]) > 0)
				{
					if (strpos($where, " AND (RPT_Mkt_Share.cd_Geo1") === FALSE)
					{
						$where = $where." AND (RPT_Mkt_Share.cd_Geo1 = '".$cd_Geo1[$i]."' ";
						$search_arg_sql=$search_arg_sql." AND (RPT_Mkt_Share.cd_Geo1 = '".$cd_Geo1[$i]."' ";
						$srch_args = $srch_args."&cd_Geo1[]=".$cd_Geo1[$i];
					}
					else
					{
						$where = $where." OR RPT_Mkt_Share.cd_Geo1 = '".$cd_Geo1[$i]."' ";
						$search_arg_sql=$search_arg_sql." OR RPT_Mkt_Share.cd_Geo1 = '".$cd_Geo1[$i]."' ";
						$srch_args = $srch_args."&cd_Geo1[]=".$cd_Geo1[$i];
					}
				}
			}
			if (strpos($where, " AND (RPT_Mkt_Share.cd_Geo1") > -1)
			{
				$where = $where.") ";
				$search_arg_sql=$search_arg_sql.")";
			}
			$criteria = $criteria."<BR>";
		}

	}

	if (array_key_exists("cd_Geo2", $_REQUEST))
	{

		$cd_Geo2 = $_REQUEST['cd_Geo2'];
		$srch_args = $srch_args."&ds_Geo2=".$_REQUEST['ds_Geo2'];

		if (count($cd_Geo2) > 0)
		{
		    $criteria = $criteria.$_REQUEST['ds_Geo2']." Equals ".array_to_string($cd_Geo2, " OR ");
			for ($i=0; $i < count($cd_Geo2); $i++)
			{
				if (strlen($cd_Geo2[$i]) > 0)
				{
					if (strpos($where, " AND (RPT_Mkt_Share.cd_Geo2") === FALSE)
					{
						$where = $where." AND (RPT_Mkt_Share.cd_Geo2 = '".$cd_Geo2[$i]."' ";
						$search_arg_sql=$search_arg_sql." AND (RPT_Mkt_Share.cd_Geo2 = '".$cd_Geo2[$i]."' ";
						$srch_args = $srch_args."&cd_Geo2[]=".$cd_Geo2[$i];
					}
					else
					{
						$where = $where." OR RPT_Mkt_Share.cd_Geo2 = '".$cd_Geo2[$i]."' ";
						$search_arg_sql=$search_arg_sql." OR RPT_Mkt_Share.cd_Geo2 = '".$cd_Geo2[$i]."' ";
						$srch_args = $srch_args."&cd_Geo2[]=".$cd_Geo2[$i];
					}
				}
			}
			if (strpos($where, " AND (RPT_Mkt_Share.cd_Geo2") > -1)
			{
				$where = $where.") ";
				$search_arg_sql=$search_arg_sql.")";
			}
			$criteria = $criteria."<BR>";
		}

	}

	if (array_key_exists("cd_Geo3", $_REQUEST))
	{

		$cd_Geo3 = $_REQUEST['cd_Geo3'];
		$srch_args = $srch_args."&ds_Geo3=".$_REQUEST['ds_Geo3'];

		if (count($cd_Geo3) > 0)
		{
		    $criteria = $criteria.$_REQUEST['ds_Geo3']." Equals ".array_to_string($cd_Geo3, " OR ");
			for ($i=0; $i < count($cd_Geo3); $i++)
			{
				if (strlen($cd_Geo3[$i]) > 0)
				{
					if (strpos($where, " AND (RPT_Mkt_Share.cd_Geo3") === FALSE)
					{
						$where = $where." AND (RPT_Mkt_Share.cd_Geo3 = '".$cd_Geo3[$i]."' ";
						$search_arg_sql=$search_arg_sql." AND (RPT_Mkt_Share.cd_Geo3 = '".$cd_Geo3[$i]."' ";
						$srch_args = $srch_args."&cd_Geo3[]=".$cd_Geo3[$i];
					}
					else
					{
						$where = $where." OR RPT_Mkt_Share.cd_Geo3 = '".$cd_Geo3[$i]."' ";
						$search_arg_sql=$search_arg_sql." OR RPT_Mkt_Share.cd_Geo3 = '".$cd_Geo3[$i]."' ";
						$srch_args = $srch_args."&cd_Geo3[]=".$cd_Geo3[$i];
					}
				}
			}
			if (strpos($where, " AND (RPT_Mkt_Share.cd_Geo3") > -1)
			{
				$where = $where.") ";
				$search_arg_sql=$search_arg_sql.")";
			}
			$criteria = $criteria."<BR>";
		}

	}

	if (array_key_exists("cd_Geo4", $_REQUEST))
	{

		$cd_Geo4 = $_REQUEST['cd_Geo4'];
		$srch_args = $srch_args."&ds_Geo4=".$_REQUEST['ds_Geo4'];

		if (count($cd_Geo4) > 0)
		{
			$criteria = $criteria.$_REQUEST['ds_Geo4']." Equals ".array_to_string($cd_Geo4, " OR ");
			for ($i=0; $i < count($cd_Geo4); $i++)
			{
				if (strlen($cd_Geo4[$i]) > 0)
				{
					if (strpos($where, " AND (RPT_Mkt_Share.cd_Geo4") === FALSE)
					{
						$where = $where." AND (RPT_Mkt_Share.cd_Geo4 = '".$cd_Geo4[$i]."' ";
						$search_arg_sql=$search_arg_sql." AND (RPT_Mkt_Share.cd_Geo4 = '".$cd_Geo4[$i]."' ";
						$srch_args = $srch_args."&cd_Geo4[]=".$cd_Geo4[$i];
					}
					else
					{
						$where = $where." OR RPT_Mkt_Share.cd_Geo4 = '".$cd_Geo4[$i]."' ";
						$search_arg_sql=$search_arg_sql." OR RPT_Mkt_Share.cd_Geo4 = '".$cd_Geo4[$i]."' ";
						$srch_args = $srch_args."&cd_Geo4[]=".$cd_Geo4[$i];
					}
				}
			}
			if (strpos($where, " AND (RPT_Mkt_Share.cd_Geo4") > -1)
			{
				$where = $where.") ";
				$search_arg_sql=$search_arg_sql.")";
			}
			$criteria = $criteria."<BR>";
		}

	}



    //*********************************************************************************************
	if (array_key_exists("start_Listing_Date", $_REQUEST) AND array_key_exists("end_Listing_Date", $_REQUEST))
	{
		$start_Listing_Date = $_REQUEST['start_Listing_Date'];
		$end_Listing_Date   = $_REQUEST['end_Listing_Date'];
		if (strlen($start_Listing_Date) > 0)
		{
			$criteria = $criteria."Listing Date On or After ".$start_Listing_Date."<BR>";
			$criteria = $criteria."Listing Date On or Before ".$end_Listing_Date."<BR>";
 			$where = $where." AND ((RPT_Mkt_Share.Listing_Date >= '".$start_Listing_Date."' ".
 			                " AND RPT_Mkt_Share.Listing_Date <= '".$end_Listing_Date."') ".
  			                " OR (RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR,-1,'".$start_Listing_Date."')".
 			                " AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR,-1,'".$end_Listing_Date."')  ))";

 			$search_arg_sql=$search_arg_sql." AND ((RPT_Mkt_Share.Listing_Date >= '".$start_Listing_Date."' ".
 			                " AND RPT_Mkt_Share.Listing_Date <= '".$end_Listing_Date."') ".
  			                " OR (RPT_Mkt_Share.Listing_Date >= DATEADD(YEAR,-1,'".$start_Listing_Date."')".
 			                " AND RPT_Mkt_Share.Listing_Date <= DATEADD(YEAR,-1,'".$end_Listing_Date."')  ))";
 			$srch_args = $srch_args."&start_Listing_Date=".$start_Listing_Date;
 			$srch_args = $srch_args."&end_Listing_Date=".$end_Listing_Date;
		}
	}


    //*********************************************************************************************
	if (array_key_exists("start_dt_Sale", $_REQUEST))
	{

		$start_dt_Sale = $_REQUEST['start_dt_Sale'];
		if (strlen($start_dt_Sale) > 0)
		{
			$criteria = $criteria."Sale Date On or Before ".$start_dt_Sale."<BR>";
 			$where = $where." AND RPT_Mkt_Share.dt_Sale >='".$start_dt_Sale."' ";
 			$search_arg_sql=$search_arg_sql." AND RPT_Mkt_Share.dt_Sale >='".$start_dt_Sale."' ";
 			$srch_args = $srch_args."&start_dt_Sale=".$start_dt_Sale;
		}
	}

	if (array_key_exists("end_dt_Sale", $_REQUEST))
	{

		$end_dt_Sale = $_REQUEST['end_dt_Sale'];
		if (strlen($end_dt_Sale) > 0)
		{
			$criteria = $criteria."Sale Date On or Before ".$end_dt_Sale."<BR>";
 			$where = $where." AND RPT_Mkt_Share.dt_Sale <='".$end_dt_Sale."' ";
 			$search_arg_sql=$search_arg_sql." AND RPT_Mkt_Share.dt_Sale <='".$end_dt_Sale."' ";
 			$srch_args = $srch_args."&end_dt_Sale=".$end_dt_Sale;
		}
	}

    //*********************************************************************************************
	if (array_key_exists("min_amt_Sale_Price", $_REQUEST))
	{

		$min_amt_Sale_Price = $_REQUEST['min_amt_Sale_Price'];
		if (strlen($min_amt_Sale_Price) > 0)
		{
			$criteria = $criteria."Sale Price >= ".$min_amt_Sale_Price."<BR>";
 			$where = $where." AND RPT_Mkt_Share.amt_Sale_Price >= ".$min_amt_Sale_Price;
 			$search_arg_sql=$search_arg_sql." AND RPT_Mkt_Share.amt_Sale_Price >= ".$min_amt_Sale_Price;
 			$srch_args = $srch_args."&min_amt_Sale_Price=".$min_amt_Sale_Price;
		}
	}

	if (array_key_exists("max_amt_Sale_Price", $_REQUEST))
	{

		$max_amt_Sale_Price = $_REQUEST['max_amt_Sale_Price'];
		if (strlen($max_amt_Sale_Price) > 0)
		{
			$criteria = $criteria."Sale Price <= ".$max_amt_Sale_Price."<BR>";
 			$where = $where." AND RPT_Mkt_Share.amt_Sale_Price <= ".$max_amt_Sale_Price;
 			$search_arg_sql=$search_arg_sql." AND RPT_Mkt_Share.amt_Sale_Price <= ".$max_amt_Sale_Price;
 			$srch_args = $srch_args."&max_amt_Sale_Price=".$max_amt_Sale_Price;
		}
	}
    //*********************************************************************************************
	if (array_key_exists("min_Listing_Price", $_REQUEST))
	{

		$min_Listing_Price = $_REQUEST['min_Listing_Price'];
		if (strlen($min_Listing_Price) > 0)
		{
			$criteria = $criteria."List Price >= ".$min_Listing_Price."<BR>";
 			$where = $where." AND RPT_Mkt_Share.Listing_Price >=".$min_Listing_Price;
 			$search_arg_sql=$search_arg_sql." AND RPT_Mkt_Share.Listing_Price >=".$min_Listing_Price;
 			$srch_args = $srch_args."&min_Listing_Price=".$min_Listing_Price;
		}
	}

	if (array_key_exists("max_Listing_Price", $_REQUEST))
	{

		$end_Listing_Price = $_REQUEST['max_Listing_Price'];
		if (strlen($max_Listing_Price) > 0)
		{
			$criteria = $criteria."List Price <= ".$max_Listing_Price."<BR>";
 			$where = $where." AND RPT_Mkt_Share.Listing_Price <=".$max_Listing_Price;
 			$search_arg_sql=$search_arg_sql." AND RPT_Mkt_Share.Listing_Price <=".$max_Listing_Price;
 			$srch_args = $srch_args."&max_Listing_Price=".$max_Listing_Price;
		}
	}
    //*********************************************************************************************

	if (array_key_exists("sort1", $_REQUEST))
	{

		$sort1 = $_REQUEST['sort1'];

		if (strlen($sort1) > 0)
		{
			$sortdir1 = $_REQUEST['sortdir1'];

			$sort = $sort." ".$sort1." ".$sortdir1;
		}

	}




//*************************************************
// ISSUE Main SQL
//*************************************************

	$scriptdata="
	<SCRIPT src=\"http://www.redata.com/vp_graphics/js/date-picker.js\"></SCRIPT>
	<SCRIPT>
	function openOfficeReport(srch_args, cd_Group)
	{
	  var win = 'http://$server.redata.com/reporting/$this_file?site=$site'+ srch_args +'&group_by=OFFICE'+'&cd_Group[]='+cd_Group;
	   //alert(win);
	   return window.open(win,'MyWindow','width=800,height=600,left=100,top=100,resizable=yes,toolbar=yes,location=yes,directories=no,status=yes,menubar=yes,scrollbars=yes');

	}

	function openAgentReport(srch_args, cd_Office, cd_Office_Source)
	{
	  var win = 'http://$server.redata.com/reporting/$this_file?site=$site'+ srch_args +'&group_by=AGENT'+'&cd_Office[]='+cd_Office;
	   //alert(win);
	   return window.open(win,'MyWindow1','width=800,height=600,left=100,top=100,resizable=yes,toolbar=yes,location=yes,directories=no,status=yes,menubar=yes,scrollbars=yes');

	}

	function openListingtReport(srch_args, cd_Agent)
	{
	  var win = 'http://$server.redata.com/reporting/$this_file?site=$site'+ srch_args +'&group_by=LISTING'+'&cd_Agent[]='+cd_Agent;
	   //alert(win);
	   return window.open(win,'MyWindow2','width=800,height=600,left=300,top=100,resizable=yes,toolbar=yes,location=yes,directories=no,status=yes,menubar=yes,scrollbars=yes');

	}
	</SCRIPT>
";
	do_header($report_name, $scriptdata);
?>


<FORM METHOD="GET" ACTION="<?= $this_file ?>" NAME="form1">
	<INPUT TYPE="HIDDEN" NAME="site" VALUE="<?= $site ?>">
	<H1><?= $report_name ?></H1>
</FORM>



<table bgcolor="#6699CC">
<tr><td>

<div class="annot">
	<h1>Search Criteria:</h1>
	<p><?= $criteria ?></p>
</div>


<TABLE class="results">
	<THEAD>
	<TR>
		<TH>#</TH>
		<? if ($group_by == "LISTING")
 	       {
 	            echo ("<TH>Agent Name</TH>");
		   		echo ("<TH>MLS Number</TH>");
		   }
		?>
		<? if ($group_by == "AGENT")
 	       {
		     	echo ("<TH>Agent Name</TH>");
		    	echo ("<TH>Office Name</TH>");
		   }
		?>
		<? if ($group_by == "OFFICE")
 	       {
		   		echo ("<TH>Office Name</TH>");
		   }
		?>
		<? if ($group_by == "COMPANY")
 	       {
		   		echo ("<TH>Company Name</TH>");
		   }
		?>
		<TH><a href=http://<?= $server ?>.redata.com/reporting/<?= $this_file ?>?site=<?= $site ?><?= str_replace($_REQUEST['order_by'],"LIST_UNITS_CY",str_replace(" ","%20",$srch_args)) ?>&group_by=<?= $group_by ?> ><font color="white">List Units</font></a></TH>
		<TH><a href=http://<?= $server ?>.redata.com/reporting/<?= $this_file ?>?site=<?= $site ?><?= str_replace($_REQUEST['order_by'],"MKT_SHARE_CY",str_replace(" ","%20",$srch_args)) ?>&group_by=<?= $group_by ?> ><font color="white">Market Share<BR>Current Period</font></a></TH>
		<TH><a href=http://<?= $server ?>.redata.com/reporting/<?= $this_file ?>?site=<?= $site ?><?= str_replace($_REQUEST['order_by'],"LIST_UNITS_PY",str_replace(" ","%20",$srch_args)) ?>&group_by=<?= $group_by ?> ><font color="white">List Units<BR>Previous Year</font></a></TH>
		<TH><a href=http://<?= $server ?>.redata.com/reporting/<?= $this_file ?>?site=<?= $site ?><?= str_replace($_REQUEST['order_by'],"MKT_SHARE_PY",str_replace(" ","%20",$srch_args)) ?>&group_by=<?= $group_by ?> ><font color="white">Market Share<BR>Previous Year</font></a></TH>
		<TH><a href=http://<?= $server ?>.redata.com/reporting/<?= $this_file ?>?site=<?= $site ?><?= str_replace($_REQUEST['order_by'],"LIST_CHGPCT",str_replace(" ","%20",$srch_args)) ?>&group_by=<?= $group_by ?> ><font color="white">Change in <BR> Market Share</font></a></TH>

		<TH><a href=http://<?= $server ?>.redata.com/reporting/<?= $this_file ?>?site=<?= $site ?><?= str_replace($_REQUEST['order_by'],"LIST_VOL_CY",str_replace(" ","%20",$srch_args)) ?>&group_by=<?= $group_by ?> ><font color="white">List Volume<BR>Current Period</font></a></TH>
		<TH><a href=http://<?= $server ?>.redata.com/reporting/<?= $this_file ?>?site=<?= $site ?><?= str_replace($_REQUEST['order_by'],"VOL_SHARE_CY",str_replace(" ","%20",$srch_args)) ?>&group_by=<?= $group_by ?> ><font color="white">Volume Share<BR>Current Period</font></a></TH>
		<TH><a href=http://<?= $server ?>.redata.com/reporting/<?= $this_file ?>?site=<?= $site ?><?= str_replace($_REQUEST['order_by'],"LIST_VOL_PY",str_replace(" ","%20",$srch_args)) ?>&group_by=<?= $group_by ?> ><font color="white">List Volume<BR>Previous Year</font></a></TH>
		<TH><a href=http://<?= $server ?>.redata.com/reporting/<?= $this_file ?>?site=<?= $site ?><?= str_replace($_REQUEST['order_by'],"VOL_SHARE_PY",str_replace(" ","%20",$srch_args)) ?>&group_by=<?= $group_by ?> ><font color="white">Volume Share<BR>Previous Year</font></a></TH>
		<TH><a href=http://<?= $server ?>.redata.com/reporting/<?= $this_file ?>?site=<?= $site ?><?= str_replace($_REQUEST['order_by'],"LIST_VOL_CHGPCT",str_replace(" ","%20",$srch_args)) ?>&group_by=<?= $group_by ?> ><font color="white">Change In<BR>Volume Share</font></a></TH>
	</TR>
	</THEAD>

<?
   setlocale(LC_MONETARY, 'en_US');


   d($srch_args);
   d(str_replace("<ENDDATE>",$end_Listing_Date,str_replace("<STARTDATE>",$start_Listing_Date,$sql_totals.$where))."<BR><BR>");
   $rs_totals = query (str_replace("<ENDDATE>",$end_Listing_Date,str_replace("<STARTDATE>",$start_Listing_Date,$sql_totals.$where)), $conn1);
   $row_totals = next_row ($rs_totals);
// echo $row_totals['Pop_no_Listings_CY']."<BR>";
// echo $row_totals['Pop_no_Listings_PY']."<BR><BR>";

// echo $row_totals['Pop_Listing_Vol_CY']."<BR>";
// echo $row_totals['Pop_Listing_Vol_PY']."<BR>";

// echo str_replace("<Pop_Listing_Vol_CY>",$row_totals['Pop_Listing_Vol_CY'],str_replace("<Pop_Listing_Vol_PY>",$row_totals['Pop_Listing_Vol_PY'],str_replace("<Pop_no_Listings_PY>",$row_totals['Pop_no_Listings_PY'],str_replace("<Pop_no_Listings_CY>",$row_totals['Pop_no_Listings_CY'],str_replace("<ENDDATE>",$end_Listing_Date,str_replace("<STARTDATE>",$start_Listing_Date,$sql.$where.$grouping.$sort))))))."<BR><BR>";


   //$rs = query (str_replace("<ENDDATE>",$end_Listing_Date,str_replace("<STARTDATE>",$start_Listing_Date,$sql.$where.$grouping.$sort)), $conn1);
   //$rs = query (str_replace("<Pop_no_Listings_PY>",$row_totals['Pop_no_Listings_PY'],str_replace("<Pop_no_Listings_CY>",$row_totals['Pop_no_Listings_CY'],str_replace("<ENDDATE>",$end_Listing_Date,str_replace("<STARTDATE>",$start_Listing_Date,$sql.$where.$grouping.$sort)))), $conn1);
   d(str_replace("<Pop_Listing_Vol_CY>",$row_totals['Pop_Listing_Vol_CY'],str_replace("<Pop_Listing_Vol_PY>",$row_totals['Pop_Listing_Vol_PY'],str_replace("<Pop_no_Listings_PY>",$row_totals['Pop_no_Listings_PY'],str_replace("<Pop_no_Listings_CY>",$row_totals['Pop_no_Listings_CY'],str_replace("<ENDDATE>",$end_Listing_Date,str_replace("<STARTDATE>",$start_Listing_Date,$sql.$where.$grouping.$sort)))))));
   $rs = query (str_replace("<Pop_Listing_Vol_CY>",$row_totals['Pop_Listing_Vol_CY'],str_replace("<Pop_Listing_Vol_PY>",$row_totals['Pop_Listing_Vol_PY'],str_replace("<Pop_no_Listings_PY>",$row_totals['Pop_no_Listings_PY'],str_replace("<Pop_no_Listings_CY>",$row_totals['Pop_no_Listings_CY'],str_replace("<ENDDATE>",$end_Listing_Date,str_replace("<STARTDATE>",$start_Listing_Date,$sql.$where.$grouping.$sort)))))), $conn1);

   $iRowCount = 0;

   //sr 11/18/04    while ($row = next_row ($rs) AND $iRowCount < 250) {
   while ($row = next_row ($rs) AND $iRowCount < $maxrecords) {

	 // accumulate data for top 10
		if ($iRowCount <= 9) {
			if ($order_by == "LIST_UNITS_CY")
			{
				$xLabels = $xLabels."&xLabels[]=".urlencode($row['grp_Name']);
				$data1 = $data1."&data1[]=".$row['no_Listings_CY'];
				$data2 = $data2."&data2[]=".$row['no_Listings_PY'];
				$ytitle="List Units";
				$data2label = "Previous Yr";
				$charttitle = "Sorted by List Units Current Year";
			}
			else if ($order_by == "MKT_SHARE_CY")
			{
				$xLabels = $xLabels."&xLabels[]=".urlencode($row['grp_Name']);
				$data1 = $data1."&data1[]=".$row['PctTot_Listings_CY']*100;
				$data2 = $data2."&data2[]=".$row['PctTot_Listings_PY']*100;
				$ytitle="Market Share";
				$data2label = "Previous Yr";
				$charttitle = "Sorted by Market Share Current Period";
			}
			else if ($order_by == "LIST_UNITS_PY")
			{
				$xLabels = $xLabels."&xLabels[]=".urlencode($row['grp_Name']);
				$data1 = $data1."&data1[]=".$row['no_Listings_CY'];
				$data2 = $data2."&data2[]=".$row['no_Listings_PY'];
				$ytitle="List Units";
				$data2label = "Previous Yr";
				$charttitle = "Sorted by List Units Previous Year";
			}
			else if ($order_by == "MKT_SHARE_PY")
			{
				$xLabels = $xLabels."&xLabels[]=".urlencode($row['grp_Name']);
				$data1 = $data1."&data1[]=".$row['PctTot_Listings_CY']*100;
				$data2 = $data2."&data2[]=".$row['PctTot_Listings_PY']*100;
				$ytitle="Market Share";
				$data2label = "Previous Yr";
				$charttitle = "Sorted by Market Share Previous Year";
			}

			else if ($order_by == "LIST_CHGPCT")
			{
				$xLabels = $xLabels."&xLabels[]=".urlencode($row['grp_Name']);
				$data1 = $data1."&data1[]=".$row['PctTot_Listings_CHG']*100;
				$data2 = $data2."&data2[]=".$row['']*100;
				$ytitle="Change In Market Share";
				$data2label = "";
				$charttitle = "Sorted by Change Market Share";
			}

			else if ($order_by == "LIST_VOL_CY")
			{
				$xLabels = $xLabels."&xLabels[]=".urlencode($row['grp_Name']);
				$data1 = $data1."&data1[]=".$row['Listing_Vol_CY'];
				$data2 = $data2."&data2[]=".$row['Listing_Vol_PY'];
				$data2label = "Previous Yr";
				$ytitle="List Volume";
				$charttitle = "Sorted by Listing Volume Current Period";
			}
			else if ($order_by == "VOL_SHARE_CY")
			{
				$xLabels = $xLabels."&xLabels[]=".urlencode($row['grp_Name']);
				$data1 = $data1."&data1[]=".$row['PctTot_Listing_Vol_CY']*100;
				$data2 = $data2."&data2[]=".$row['PctTot_Listing_Vol_PY']*100;
				$data2label = "Previous Yr";
				$ytitle="Volume Share";
				$charttitle = "Sorted by Volume Share Current Period";
			}

			else if ($order_by == "LIST_VOL_PY")
			{
				$xLabels = $xLabels."&xLabels[]=".urlencode($row['grp_Name']);
				$data1 = $data1."&data1[]=".$row['Listing_Vol_CY'];
				$data2 = $data2."&data2[]=".$row['Listing_Vol_PY'];
				$data2label = "Previous Yr";
				$ytitle="List Volume";
				$charttitle = "Sorted by List Volume Previous Year";
			}

			else if ($order_by == "VOL_SHARE_PY")
			{
				$xLabels = $xLabels."&xLabels[]=".urlencode($row['grp_Name']);
				$data1 = $data1."&data1[]=".$row['PctTot_Listing_Vol_CY']*100;
				$data2 = $data2."&data2[]=".$row['PctTot_Listing_Vol_PY']*100;
				$data2label = "Previous Yr";
				$ytitle="Volume Share";
				$charttitle = "Sorted by Volume Share Previous Year";
			}
			else if ($order_by == "LIST_VOL_CHGPCT")
			{
				$xLabels = $xLabels."&xLabels[]=".urlencode($row['grp_Name']);
				$data1 = $data1."&data1[]=".$row['PctTot_Listing_Vol_CHG']*100;
				$data2 = $data2."&data2[]=".$row[''];
				$ytitle="% Change";
				$charttitle = "Sorted by % Change in Volume Share";
			}

			else
			{
				//$primarymarketsharedata[$row['grp_Name']] = array( 'MarketShareData' => $row['Total_Sides'], 'Sale Units' => $row['Sale_Sides'] );
				$xLabels = $xLabels."&xLabels[]=".urlencode($row['grp_Name']);
				$data1 = $data1."&data1[]=".$row['Total_Sides'];
				$ytitle="Total Sides";

			}

		 }


     ?>


	<? $iRowCount++;
	if ($bgcolor == "#ffffff")
	{
	    $bgcolor = "#D3D3D3";
	}
	else
	{
		$bgcolor = "#ffffff";
	}
	?>

	<TR class="<?= ($iRowCount % 2 == 0 ? "even" : "odd") ?>">

		<TD><?= $iRowCount ?></TD>

		<? if ($group_by == "COMPANY")
		   {
		   		if ($row['cd_Office'])
		   		{?>
		    		<TD><A HREF=""onClick="MyWindow=openAgentReport('<?= str_replace("cd_Group[]","Xcd_Group",str_replace("cd_Office[]","Xcd_Office",$srch_args)) ?>','<?= $row["cd_Office"] ?>','<?= $row["cd_Source_Office"] ?>'); return false;"><?= $row['grp_Name'] ?>-<?= $row['cd_Office'] ?></TD>
		        <? }
		        else
		        {?>
		    		<TD><A HREF=""onClick="MyWindow=openOfficeReport('<?= str_replace("cd_Group[]","Xcd_Group",$srch_args) ?>','<?= $row['cd_Group'] ?>'); return false;"><?= $row['grp_Name'] ?></TD>
		     <? }
		   } ?>

		<? if ($group_by == "OFFICE")
		   { ?>
		   <TD><A HREF=""onClick="MyWindow=openAgentReport('<?= str_replace("cd_Group[]","Xcd_Group",str_replace("cd_Office[]","Xcd_Office",$srch_args)) ?>','<?= $row["cd_Office"] ?>','<?= $row["cd_Source_Office"] ?>'); return false;"><?= $row['grp_Name'] ?>-<?= $row['cd_Office'] ?></TD>
		<? }?>

		<? if ($group_by == "AGENT")
		   {?>
		   <!-- <TD><A HREF=""onClick="MyWindow=openListingtReport('<?= str_replace("cd_Agent[]","Xcd_Agent",$srch_args) ?>','<?= $row["cd_Agent"] ?>'); return false;"><?= $row['grp_Name'] ?></TD> -->
		   <TD><?= $row['grp_Name'] ?></TD>
		   <TD><?= $row['nm_Office'] ?></TD>
		<? }?>

		<? if ($group_by == "LISTING")
		   { ?>
		   <TD><?= $row['nm_Agent'] ?></TD>
		   <TD><?= $row['grp_Name'] ?></TD>
		<? }?>

		<TD><?= $row['no_Listings_CY'] ?></TD>
		<TD><?
				if ( $row_totals['Pop_no_Listings_CY'] > 0)
				{
					echo number_format(($row['no_Listings_CY']/$row_totals['Pop_no_Listings_CY'])*100,2);
				}
				else	 {
				}
				?>
		</TD>
		<TD><?= $row['no_Listings_PY'] ?></TD>
		<TD><?
				if ( $row_totals['Pop_no_Listings_PY'] > 0)
				{
					echo number_format(($row['no_Listings_PY']/$row_totals['Pop_no_Listings_PY'])*100,2);
				}
				else	 {
				}
				?>

		</TD>
		<TD><?= number_format($row['PctTot_Listings_CHG']*100,2) ?></TD>

		</TD>

		<TD><?= '$'.number_format($row['Listing_Vol_CY']) ?></TD>
		<TD><?
				if ( $row_totals['Pop_Listing_Vol_CY'] > 0)
				{
					echo number_format(($row['Listing_Vol_CY']/$row_totals['Pop_Listing_Vol_CY'])*100,2);
				}
				else	 {
				}
				?>
		</TD>



		<TD><?= '$'.number_format($row['Listing_Vol_PY']) ?></TD>
		<TD><?
				if ( $row_totals['Pop_Listing_Vol_PY'] > 0)
				{
					echo number_format(($row['Listing_Vol_PY']/$row_totals['Pop_Listing_Vol_PY'])*100,2);
				}
				else	 {
				}
				?>
		</TD>
		<TD><?= number_format($row['PctTot_Listing_Vol_CHG']*100,2) ?></TD>


<?}?>

	</TR>
		<TR class="total">
			<TD></TD>
			<TD>Market Totals</TD>
			<? if ($group_by == "LISTING")
			   {
			   echo "<TD></TD>";
			   }
			?>

			<? if ($group_by == "AGENT")
			   {
			   echo "<TD></TD>";
			   }
			?>
			<TD><?= $row_totals['Pop_no_Listings_CY'] ?></TD>
			<TD></TD>
			<TD><?= $row_totals['Pop_no_Listings_PY'] ?></TD>
			<TD></TD>
			<TD></TD>
			<TD><?= '$'.number_format($row_totals['Pop_Listing_Vol_CY']) ?></TD>
			<TD></TD>
			<TD><?= '$'.number_format($row_totals['Pop_Listing_Vol_PY']) ?></TD>

		</TR>

</TABLE>
</td></tr></table>
<p><a href="/reporting/mkt_penetrationgraph.php?<?= $xLabels ?><?= $data1 ?><?= $data2 ?>&ytitle=<?= urlencode($ytitle) ?>&data2label=<?= $data2label ?>&charttitle=<?= urlencode($charttitle) ?>&criteria=<?= urlencode(str_replace("<BR>","\n",$criteria)) ?>">Market Share Graph</a></p>


<?php

	do_footer();
?>





