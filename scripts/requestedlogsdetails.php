<?php

	require_once(dirname(__FILE__).'/classes/users.php');
	$objUser = new User();

	$loginDetails = $objUser->fnGetLoginUserDetails();

	if(!$loginDetails['login_status']) {
		header("Location: ../login.html");
	}

	
	$data = array();

	//print_r($_POST);
	/*
	* Paging
	*/
	$limit = "10";
	$offset = "0";
	if ( isset( $_POST['iDisplayStart'] ) && $_POST['iDisplayLength'] != '-1' )
	{
		#$sLimit = " LIMIT ".intval( filter_var($_POST['iDisplayStart'], FILTER_SANITIZE_NUMBER_INT) ).", ". intval( filter_var($_POST['iDisplayLength'], FILTER_SANITIZE_NUMBER_INT));

		$offset = intval( filter_var($_POST['iDisplayStart'], FILTER_SANITIZE_NUMBER_INT) );
		$limit = intval( filter_var($_POST['iDisplayLength'], FILTER_SANITIZE_NUMBER_INT) );
	}


	//Default values
	$fromDate = date("Y-m")."-01";
	$toDate = date("Y-m-d");
	$requesttag = "";

	if( 
		(isset($_POST['fromDate']) && $_POST['fromDate'] != "")  &&
		(isset($_POST['toDate']) && $_POST['toDate'] != "")
	) {
		$fromDate = date("Y-m-d", strtotime(htmlentities(filter_var($_POST['fromDate'], FILTER_SANITIZE_STRING)) ) );
		$toDate = date("Y-m-d", strtotime(htmlentities(filter_var($_POST['toDate'], FILTER_SANITIZE_STRING)) ) );
	}


	if((isset($_POST['requesttag']) && $_POST['requesttag'] != "")) {
		$requesttag = htmlentities(addslashes(filter_var(trim($_POST['requesttag']), FILTER_SANITIZE_STRING)));
	}
	
	$username = $loginDetails['username'];


	$searchArgs = array(':username' => $username, ':fromdate' => $fromDate , ':todate' => $toDate);
	$sQry  = " SELECT *, FROM_UNIXTIME(timestamp) as REQUESTEDDATE FROM tbl_logs_requests WHERE username = :username ";
	$sQry .= " AND DATE(FROM_UNIXTIME(timestamp)) BETWEEN :fromdate  AND :todate  ";
	if($requesttag != "" && strlen($requesttag) > 1) {
		$sQry .= " AND report_tag LIKE :report_tag";
		$searchArgs[':report_tag'] = "%$requesttag%";
	}
	$sQry .= " ORDER BY id DESC ";

	#print_r($searchArgs);
	#echo $sQry;
	
	$CampPDO_Stmt = $database->fnFetchRecords($sQry , $searchArgs );
	$totalCampaigns = $CampPDO_Stmt->rowCount();
	$CampPDO_Stmt->closeCursor();
	
	#Fetch only requested no.of records
	$sQry = $sQry." LIMIT  :offset, :limit ";
	$searchArgs[':limit'] = $limit;
	$searchArgs[':offset'] = $offset;

	//echo $sQry; print_r($searchArgs);
	$PDO_Stmt = $database->fnFetchRecords($sQry , $searchArgs );
	$CampData = $PDO_Stmt->fetchAll();
	$records = $PDO_Stmt->rowCount();

		

	foreach($CampData as $details) {
		//echo "<pre>";print_r($details);echo "</pre>";

		//$linkIdentifier = "";
		$temp = array();
		//$temp[] = "<input type='checkbox' onclick='fnSelectForExport(this,this.id)' id='".base64_encode($linkIdentifier)."'  name='".base64_encode($linkIdentifier)."' />";

		$temp[] = $details['REQUESTEDDATE'];
		$temp[] = $details['report_tag'];

		$link = '';
		if($details['status'] == '0') {
			$status = "Request Accepted";
		} else if($details['status'] == '1') {
			$status = "Request processing";
		} else if($details['status'] == '2') {
			$status = 'Completed';
			$link = "<a href='downloadlog.php?i=".$details['downloadlink']."'>Download</a>";
		} else {
			$status = "Failed to process the request";
			$link = 'NA';
		}
		$temp[] = $status;
		$temp[] = $link;


		$data[] = $temp;
	}

	$output = array(
			"sEcho" => intval(filter_var($_POST['sEcho'], FILTER_SANITIZE_NUMBER_INT)),
			"iTotalRecords" => count($data),
			//"iTotalRecords" => $records,
			"iTotalDisplayRecords" => $totalCampaigns,
			//"iTotalDisplayRecords" => $records,
			"aaData" => array()
		);




	//Sort the Data
	if(!empty($data)) {
		$tmp_len = count($data);
		for($i=0;$i<$tmp_len;$i++) {
			if(isset($data[$i]) && !empty($data[$i]))
				$output['aaData'][] = $data[$i];
		}
	}


	echo json_encode($output);

	//@unlink($filteredDetails);
?>
