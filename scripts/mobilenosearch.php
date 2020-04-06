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
	$mobileno = "";


	if((isset($_POST['mobileno']) && $_POST['mobileno'] != "")) {
		$mobileno = htmlentities(addslashes(filter_var(trim($_POST['mobileno']), FILTER_SANITIZE_STRING)));
		#$mobileno = addslashes(trim($_POST['mobileno']));
	}
	$username = $loginDetails['username'];

	$username = 'demo';

	$searchArgs = array(':userid' => $username, ':currentdate' => date('Y-m-d', strtotime('-1 days')) );
	$sQry  = " SELECT * FROM mis_info WHERE userid = :userid ";
	$sQry .= " AND DATE(p_submitdatetime) = :currentdate ";
	if($mobileno != "" && strlen($mobileno) > 1) {
		$sQry .= " AND mobile LIKE :mobileno";
		$searchArgs[':mobileno'] = "%$mobileno%";
	}
	$sQry .= " ORDER BY p_submitdatetime DESC ";

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
		$temp = array();
		$temp[] = "";
		$temp[] = $details['mobile'];
		$temp[] = $details['senderid'];
		$temp[] = $details['smstext'];
		$temp[] = $details['p_submitdatetime'];
		$temp[] = $details['t_submitdatetime'];
		$temp[] = ($details['t_status'] != "") ? $details['t_status'] : $details['p_status'];


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
