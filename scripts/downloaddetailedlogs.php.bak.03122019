<?php
	include_once(dirname(dirname(__FILE__))."/includes/config.php");

	$dbConn = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS); 


	$username = (isset($_GET['username'])) ?  addslashes(trim($_GET['username'])) : "";
	$server = (isset($_GET['server'])) ?  addslashes(trim($_GET['server'])) : "";
	$selecteddate = (isset($_GET['selecteddate'])) ?  addslashes(trim($_GET['selecteddate'])) : "";
?>

<html lang="en">
<meta http-equiv="content-type" content="text-html; charset=utf-8">
<body>
<!-- Progress bar holder -->
<div id="progress" style="width:500px;border:1px solid #ccc;"></div>
<!-- Progress information -->
<div id="information" style="width"></div>

<?php
	if($username == "" || $server == "" || $selecteddate == "") {
		echo '<script language="javascript">document.getElementById("information").innerHTML="Access denied, invalid request";</script>';
		$zipfilename = '';
		$status = '3';
		$reason = 'Invalid request';
	} else {
		$monthYear = date("mY",strtotime($selecteddate));
		$date = date("d", strtotime($selecteddate));

	
		$folderPath = "/".LOGS_ROOT_FOLDER."/".$monthYear."/".$server."/".$date."/".$username."/";
		$tmp_folderPath = "/tmp".$folderPath;

		//Remove redirectory if exists
		@rmdir($tmp_folderPath);
	
		//make directory structure inside /tmp/
		@mkdir($tmp_folderPath, 0777, true);

		//echo "<br/>".$folderPath;

		//Copy files from MIS_REPORTLOGS to /tmp/ folder
		exec("cp ".$folderPath."* ".$tmp_folderPath);
	
		$files = array_values(array_diff(scandir($folderPath), array('.', '..')));
		//echo "<pre>";print_r($files);echo "</pre>";

	
		$filescount = @count($files);
		//echo "<br/>No.of Files:".$filescount;

		if($filescount <=0 ) {
			$zipfilename = '';
			$status = '3';
			$reason = 'No Data available';
			echo '<script language="javascript">document.getElementById("information").innerHTML="No Data available";</script>';
		} else {
			$reason = '';
			$status = '2';
			$files = preg_filter('/^/', $tmp_folderPath, $files);
			//echo "<pre>";print_r($files);echo "</pre>";

			//Zip the files
			$logsPath = dirname(dirname(__FILE__));
			$zipfilename = "generatedFiles/".$username.'-'.time().'.zip';
			$files = implode(" ",$files);
			$zipcmd = " zip -j -r ".$logsPath."/".$zipfilename." ".$files;
			//echo $zipcmd;
	
			shell_exec($zipcmd);
		
			// Tell user that the process is completed
			$html  = "Process completed.";
			$html .= "<a href='../".$zipfilename."'>Click here to download</a>";
			$downloadLink  = '<script language="javascript">';
			$downloadLink .= 'document.getElementById("information").innerHTML="'.$html.'";';
			$downloadLink .= '</script>';
			echo $downloadLink;
		}

		//Remove files from /tmp/ folder
		#@shell_exec("rm -r /tmp/".LOGS_ROOT_FOLDER."/*");
	}

	$percent = '100%';
	echo '<script language="javascript">document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;background-image:url(../media/pbar-ani.gif);\">&nbsp;</div>";</script>';

	//Save the request details
	$iQry  = " INSERT INTO `".LOGS_DATABASE_NAME."`.tbl_request_details VALUES (NULL, '".$server."', '".$username."', 'DETAILEDLOGS', ";
	$iQry .= " '".serialize(array('selecteddate'=>$selecteddate))."', '', '".$zipfilename."', '".$status."', '".$reason."', ";
	$iQry .= " UNIX_TIMESTAMP(NOW()) ) ";

	//echo $iQry;	
	mysql_query($iQry, $dbConn);
	mysql_close($dbConn);
		
	
?>


