<?php

	require_once(dirname(__FILE__).'/classes/users.php');
	$objUser = new User();

	$loginDetails = $objUser->fnGetLoginUserDetails();

	if(!$loginDetails['login_status']) {
		header("Location: ../login.html");
	}


	#Include Cyptor lib
	require_once(dirname(dirname(__FILE__))."/includes/Cryptor.php");
	$cryptor = new Cryptor(CRYPTOR_SALT);

	
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
	$toDate = date("Y-m-d", strtotime("-1 days"));
	$fromDate = date("Y-m-d", strtotime("last day of -3 month") );
	//echo $toDate;
	$campaigntag = "";

	if( 
		(isset($_POST['fromDate']) && $_POST['fromDate'] != "")  &&
		(isset($_POST['toDate']) && $_POST['toDate'] != "")
	) {
		$fromDate = date("Y-m-d", strtotime(htmlentities(filter_var($_POST['fromDate'], FILTER_SANITIZE_STRING)) ) );
		$toDate = date("Y-m-d", strtotime(htmlentities(filter_var($_POST['toDate'], FILTER_SANITIZE_STRING)) ) );
	}


	if((isset($_POST['campaigntag']) && $_POST['campaigntag'] != "")) {
		$campaigntag = htmlentities(addslashes(filter_var(trim($_POST['campaigntag']), FILTER_SANITIZE_STRING)));
	}
	
	$server = $loginDetails['server'];
	$username = $loginDetails['username'];


	$searchArgs = array( ':username' => $username, ':fromdate' => $fromDate , ':todate' => $toDate);
	$sQry  = " SELECT * FROM tbl_campaign_summary WHERE username = :username ";
	$sQry .= " AND campaigndate BETWEEN :fromdate  AND :todate  ";
	if($campaigntag != "" && strlen($campaigntag) > 1) {
		$sQry .= " AND campaigntag LIKE :campaigntag";
		$searchArgs[':campaigntag'] = "%$campaigntag%";
	}
	$sQry .= " ORDER BY id DESC ";

	//echo $sQry;
	
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

		//Get last 3 elements of array ie., date, username & filename
		$linkIdentifier = base64_encode($cryptor->encrypt($details['id']));

		//$linkIdentifier = "";
		$temp = array();
		//$temp[] = "<input type='checkbox' onclick='fnSelectForExport(this,this.id)' id='".base64_encode($linkIdentifier)."'  name='".base64_encode($linkIdentifier)."' />";
		$temp[] = $details['campaigndate'];
		//$temp[] = $details['campaigntype'];
		$temp[] = $details['campaigntag'];
		$temp[] = $details['senderid'];
		$temp[] = $details['totalbase'];
		$temp[] = $details['delivered_count'];
		$temp[] = $details['failed_count']+$details['unknown_count'];
		$temp[] = $details['dnd_count'];
		$temp[] = $details['rejected_count'];
		$temp[] = $details['blacklist_count'];
		$temp[] = $details['submitted_count'];


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
