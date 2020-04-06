<?php

	include_once(dirname(dirname(__FILE__))."/includes/config.php");
	/*
	include_once("classes/user.php");

	$objUser = new User();
	$loggedinUserDetails = $objUser->fnGetLoginUserDetails();
	$activeUserDetails = $objUser->fnGetActiveUserWithServerDetails();
	
	if($loggedinUserDetails['login_status']) 
	{
	

		include_once("classes/server.php");
		$objServer = new Server();
	*/

		$aColumns = array('requested_timestamp', 'reporttag','reason');

	
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "id";
	
		/* DB table to use */
		$sTable = "ajax";

	
		/* Database connection information */
		$gaSql['user']       = DATABASE_USER;
		$gaSql['password']   = DATABASE_PASS;
		$gaSql['db']         = LOGS_DATABASE_NAME;
		$gaSql['server']     = DATABASE_HOST;

		$database = $gaSql['db'];

	

		
		/* 
		 * Local functions
		 */
		function fatal_error ( $sErrorMessage = '' )
		{
			header( $_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error' );
			die( $sErrorMessage );
		}

	
		/* 
		 * MySQL connection
		 */
		
		if ( ! $gaSql['link'] = mysql_connect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) )
		{
			fatal_error( 'Could not open connection to server' );
		}

		if ( ! mysql_select_db( $gaSql['db'], $gaSql['link'] ) )
		{
			fatal_error( 'Could not select database ' );
		}
		

		/* 
		 * Paging
		 */
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit = " LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
				intval( $_GET['iDisplayLength'] );
		}
	
	
		/*
		 * Ordering
		 */
		$sOrder = "";
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$colmn = $aColumns[ intval( $_GET['iSortCol_'.$i] ) ];
					if(isset($colmn) && $colmn!="")
					{
						$sOrder .= "".$colmn." ".
							($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
					}
				}
			}
		
			//$sOrder = trim($sOrder);
			//echo $sOrder;
			$sOrder = substr_replace( $sOrder, "", -2 );
			//$sOrder = trim($sOrder,',');

		}
		if ($sOrder == "" || $sOrder == "ORDER BY" )
		{
			$sOrder = " ORDER BY id desc ";
		} 

		//echo "Orde=>".$sOrder."<br/>";exit;
	
		/* 
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		$sWhere = "";
	
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{

			$_GET['sSearch'] = addslashes(trim($_GET['sSearch']));


			$sWhere = " AND (";
			$counts = count($aColumns);
			for ( $i=0 ; $i<$counts ; $i++ )
			{
				if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )
				{
					$column = @$aColumns[$i];
					if($column!="") {
						$sWhere .= "".$column." LIKE '%";
						$sWhere .= addslashes( $_GET['sSearch'] )."%' OR ";
					}
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ') ';
		}


		$fromDate = (isset($_GET['fromDate'])) ? trim($_GET['fromDate']) : "";
		$toDate = (isset($_GET['toDate'])) ? trim($_GET['toDate']) : "";
		$reporttag = (isset($_GET['reporttag'])) ? trim($_GET['reporttag']) : "";

		if($fromDate != "" && $toDate !="") {
			//echo $fromDate;
			$fromDate = date("Y-m-d",strtotime($fromDate))." 00:00:00";
			$toDate = date("Y-m-d",strtotime($toDate))." 23:59:59";
			$sWhere .= " AND (requested_timestamp >= UNIX_TIMESTAMP('".$fromDate."') AND requested_timestamp >= UNIX_TIMESTAMP('".$toDate."') ) ";
		}

		if($reporttag != "") {
			$sWhere .= " AND reporttag like '%".$reporttag."%' ";
		}
		
		$cnt = 0;
		$iFilteredTotal = 0;
		$iTotal = 0;
		$deployed_result = array();

		$sQuery  = " SELECT SQL_CALC_FOUND_ROWS  *, from_unixtime(requested_timestamp) as REQ_TS ";
		$sQuery .= " FROM `$database`.tbl_request_details";
		$sQuery .= " WHERE 1";

		$sQuery .= $sWhere;
		$sQuery .= $sOrder;
		$sQuery .= $sLimit;
		//$sQuery .= " LIMIT 1";

	
		//echo "<br/>SQL1::".$sQuery;

		$rResult = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_error() );

		
		
		/* Data set length after filtering */
		$sQuery = " SELECT FOUND_ROWS() ";
		$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_error() );
		$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
		$iFilteredTotal = ( (int)$iFilteredTotal + (int)$aResultFilterTotal[0] );

		while ($arow = mysql_fetch_array($rResult)) 
		{

			$row = array();


			//echo "<br/>Res1::<pre>";print_r($arow);echo "</pre><br/><br/>";
		

			$row[] = $arow['REQ_TS'];
			$row[] = $arow['reporttag'];

			if($arow['status'] == '0') {
				$link= "Logs will be available for download after 24 hours";
			} else if($arow['status'] == '1') {
				$link = "Processing";
			} else if($arow['status'] == '3') {
				$link = "Failed to generate logs due to internal error";
			} else {
				$link = "<a href='".$arow['filepath']."'>Download</a>";
			}

			//echo "<br/>Status : ".$arow['status']." & Link : ".$link;
			$row[] = $link;
			
			//echo "<br/>Details::<pre>";print_r($row);echo "</pre>";
			$deployed_result[] = $row;
		
		}
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);

		//echo "<pre>";print_r($deployed_result);echo "</pre>";

		//Sort the Data
		if(!empty($deployed_result)) {
			$tmp_len = count($deployed_result);
			for($i=0;$i<$tmp_len;$i++) {
				if(isset($deployed_result[$i]) && !empty($deployed_result[$i]))
					$output['aaData'][] = $deployed_result[$i];
			}
		}

		@mysql_close($gaSql['link']);	
		echo json_encode( $output );
	/*
	} else {
		echo "LOGOUT";
	}
	*/

?>
