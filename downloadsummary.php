<?php

	require_once(dirname(__FILE__).'/scripts/classes/users.php');
	require_once(dirname(__FILE__).'/includes/config.php');
	$objUser = new User();

	$loginDetails = $objUser->fnGetLoginUserDetails();

	if(!$loginDetails['login_status']) {
		header("Location: login.html");
	}

	//downloaded file should be deleted even after cancel download
	ignore_user_abort(true);

	
	$server = $loginDetails['server'];
        $username = $loginDetails['username'];
	
	$timestamp = strtotime(date('Y-m-d H:i:s'));
	
	//Default values
        $toDate = date("Y-m-d", strtotime("-1 days"));
        $fromDate = date("Y-m-d", strtotime("last day of -3 month") );

        $campaigntag = "";

        if(
                (isset($_GET['fromDate']) && $_GET['fromDate'] != "")  &&
                (isset($_GET['toDate']) && $_GET['toDate'] != "")
        ) {
                $fromDate = date("Y-m-d", strtotime(htmlentities(filter_var($_GET['fromDate'], FILTER_SANITIZE_STRING)) ) );
                $toDate = date("Y-m-d", strtotime(htmlentities(filter_var($_GET['toDate'], FILTER_SANITIZE_STRING)) ) );
        }


        if((isset($_GET['campaigntag']) && $_GET['campaigntag'] != "")) {
                $campaigntag = htmlentities(addslashes(filter_var(trim($_GET['campaigntag']), FILTER_SANITIZE_STRING)));
        }

	//echo $server;echo " ".$username;
	
	$dbConn = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
	
	$outputfile=$timestamp."_summarylog.csv";
	$outputfilepath="/tmp/".$outputfile;

$sQry="SELECT 'CAMPAIGN DATE','USERNAME','CAMPAIGNTAG','SENDERID','TOTALBASE(COUNT)','DELIVERED(COUNT)','FAILED(COUNT)','DND(COUNT)', ";
$sQry .= "'REJECTED(COUNT)','BLACKLIST(COUNT)','SUBMITTED(COUNT)','TOTALCREDITS','DELIVERED(CREDITS)','FAILED(CREDITS)', ";
$sQry .= "'DND(CREDITS)','REJECTED(CREDITS)','BLACKLIST(CREDITS)','SUBMITTED(CREDITS)', ";
$sQry .= "'BILLABLE CREDITS(EXCLUDED DND,BLACKLIST,REJECTED)' UNION ALL SELECT campaigndate,username,campaigntag,senderid,totalbase,";
$sQry .= "delivered_count,failed_count,dnd_count,rejected_count,blacklist_count,submitted_count,totalcredits,delivered_credits, ";
$sQry .= "failed_credits,dnd_credits,rejected_credits,blacklist_credits,submitted_credits,delivered_credits+failed_credits+submitted_credits ";
$sQry .= "INTO OUTFILE '".$outputfilepath."' FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\\n' FROM tbl_campaign_summary WHERE server = '".$server."'  AND username = '".$username."' AND campaigndate BETWEEN '".$fromDate."' AND '".$toDate."'";
	
if ($campaigntag != ""){
	$sQry .= "AND campaigntag = '".$campaigntag."'";
}

	//$sQry .= " ORDER BY campaigndate DESC";

	//echo $sQry;exit;
	mysqli_query($dbConn,$sQry);

	header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$outputfile.'"');
        header('Content-Length: ' . filesize($outputfilepath));
        readfile($outputfilepath);
        //chmod($outputfilepath,777);
	@unlink($outputfilepath);

	 //ob_clean();
         //flush();

	/*if (file_exists($outputfilepath)){
		if (unlink($outputfilepath)) {   
			echo "success";
		} else {
			echo "fail";    
		}   
	} else {
		echo "file does not exist";
	}*/

?>
