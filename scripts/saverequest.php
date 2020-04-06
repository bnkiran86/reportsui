<?php
	include_once(dirname(dirname(__FILE__))."/includes/config.php");

	#$dbConn = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS); 


	$username = (isset($_GET['username'])) ?  addslashes(trim($_GET['username'])) : "";
	$server = (isset($_GET['server'])) ?  addslashes(trim($_GET['server'])) : "";
	$type = (isset($_GET['type'])) ?  addslashes(trim($_GET['type'])) : "";
	$fromdate = (isset($_GET['fromdate'])) ?  addslashes(trim($_GET['fromdate'])) : "";
	$todate = (isset($_GET['todate'])) ?  addslashes(trim($_GET['todate'])) : "";
	$campaigntag = (isset($_GET['campaigntag'])) ?  addslashes(trim($_GET['campaigntag'])) : "";
	$mobileno = (isset($_GET['mobileno'])) ?  addslashes(trim($_GET['mobileno'])) : "";
	$reporttag = (isset($_GET['reporttag'])) ?  addslashes(trim($_GET['reporttag'])) : "";
	$selectiontype = (isset($_GET['selectiontype'])) ?  addslashes(strtoupper(trim($_GET['selectiontype']))) : "";
	$searchdate = (isset($_GET['searchdate'])) ?  addslashes(trim($_GET['searchdate'])) : "";

?>

<html lang="en">
<meta http-equiv="content-type" content="text-html; charset=utf-8">
<body>
<!-- Progress bar holder -->
<div id="progress" style="width:500px;border:1px solid #ccc;"></div>
<!-- Progress information -->
<div id="information" style="width"></div>

<?php
	if($username == "" || $server == "" || $type == "" || $selectiontype == "") {
		echo '<script language="javascript">document.getElementById("information").innerHTML="Access denied, invalid request";</script>';
		$status = '3';
		$reason = 'Invalid request';
	} else if($type == "CAMPAIGNWISE" && $campaigntag == "") {
		echo '<script language="javascript">document.getElementById("information").innerHTML="Access denied, invalid request";</script>';
		$status = '3';
		$reason = 'Invalid request';
	} else if($type == "MOBILESEARCH" && $mobileno == "") {
		echo '<script language="javascript">document.getElementById("information").innerHTML="Access denied, invalid request";</script>';
		$status = '3';
		$reason = 'Invalid request';
	} else if($selectiontype == "SPECIFICADATE" && $searchdate == "") {
		echo '<script language="javascript">document.getElementById("information").innerHTML="Access denied, invalid request";</script>';
		$status = '3';
		$reason = 'Invalid request';
	} else if($selectiontype == "DATERANGE" && ($fromdate == "" || $todate == "" )) {
		echo '<script language="javascript">document.getElementById("information").innerHTML="Access denied, invalid request";</script>';
		$status = '3';
		$reason = 'Invalid request';
	} else {
		$status = '0';
		$reason = '';
	}


	if($selectiontype == "DATERANGE") {
		$requests = serialize(array('selectiontype' => $selectiontype, 'searchdate' => $searchdate, 'fromdate' => $fromdate, 'todate' => $todate, 'campaigntag' => $campaigntag, "mobileno" => $mobileno));


		$dbConn = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS);
		//Save the request details
		$iQry  = " INSERT INTO `".LOGS_DATABASE_NAME."`.tbl_request_details VALUES (NULL, '".$server."', '".$username."', '".$type."', ";
		$iQry .= " '".$requests."', '".addslashes($reporttag)."', '', '".$status."', '', UNIX_TIMESTAMP(NOW()) ) ";

		//echo $iQry;	
		mysql_query($iQry, $dbConn);
		mysql_close($dbConn);
		
		echo '<script language="javascript">document.getElementById("information").innerHTML="Your request has been queued up & your log file will be reflected for download after 24 hours";</script>';
	} else {
		if($type == "CAMPAIGNWISE")
			$requesttext = urlencode($campaigntag);
		else
			$requesttext = urlencode($mobileno);

		$pyParams = $server." ".$username." ".$type." ".$requesttext." ".$searchdate;
		//echo "\n"."python ".dirname(__FILE__)."/specificdatelogsgenerator.py ".$pyParams;
		$pyResp = trim(shell_exec("python ".dirname(__FILE__)."/specificdatelogsgenerator.py ".$pyParams));

		if($pyResp == "INVALID_ACCESS") {
			$status  = '3';
			$response = "No Data Available";
		} else if($pyResp == "INVALID_REQUEST") {
			$status = '3';
			$response = "Invalid Request";
		} else {
			$status = '2';
			//$response = "http://search.alertsindia.in:81/logsui/generatedFiles/".$pyResp;
			$response = "<a href='http://search.alertsindia.in:81/logsui/generatedFiles/".$pyResp."'>Click here to download</a>";
		}

		echo '<script language="javascript">document.getElementById("information").innerHTML="'.$response.'";</script>';
	}
?>
