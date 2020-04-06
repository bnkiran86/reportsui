<?php

	
	ignore_user_abort(true);

	require_once(dirname(__FILE__).'/scripts/classes/users.php');
	$objUser = new User();

	$loginDetails = $objUser->fnGetLoginUserDetails();

	if(!$loginDetails['login_status']) {
		header("Location: login.html");
	}

	


	#Include Cyptor lib
	require_once(dirname(__FILE__)."/includes/Cryptor.php");
	$cryptor = new Cryptor(CRYPTOR_SALT);
	


	if(isset($_GET['i']) && $_GET['i'] != "") {
		$server = $loginDetails['server'];
		$username = $loginDetails['username'];

		$encryptedLogId = base64_decode(trim(filter_var($_GET['i'], FILTER_SANITIZE_STRING)));
		
		$logId = $cryptor->decrypt($encryptedLogId);

		if($logId != "" && is_numeric($logId)) {

			#Fetch File details for id
			$sQry  = " SELECT * FROM tbl_logs_generated_details WHERE id = :id AND server = :server  AND username = :username ";
			$PDO_Stmt = $database->fnFetchRecords($sQry , array(':id' => $logId , ':server' => $server , ':username' => $username) );
			if( $PDO_Stmt->rowCount() > 0) {
				$CampData = $PDO_Stmt->fetch();
				//print_r($CampData);
				$PDO_Stmt->closeCursor();

				$params = explode("/",$CampData['filepath']);
				//print_r($params);
				$filename = end($params);
				
				//echo "<br/>File Name : ".$filename."<br/>Extn : ".$objUser->fnGetFileExtension($filename);
				if($filename != "" && trim($objUser->fnGetFileExtension($filename)) == "csv") {
					
					$fileDetails = explode("." , $filename);

					$tmpFilePath = "/tmp/".$filename;
					$zipfile = "generatedFiles/".$fileDetails[0].".zip";
					
					//echo "<br/>Tmp :".$fileDetails[0];

					$filePath = str_replace("/mis_reportlogs/", "/MY_Passport_4TB/SER06/mis_reportlogs/", $CampData['filepath']);
					
					//Copy the required file to /tmp/ folde
					exec("cp ".$filePath." /tmp/");

					//ZIP the file via linux cmd
					exec("zip -9jpr ".$zipfile." ".$tmpFilePath );

					
					header('Content-Type: application/zip');
					header('Content-Disposition: attachment; filename="'.basename($zipfile).'"');
					header('Content-Length: '.filesize($zipfile) );

					ob_clean();
					flush();

					readfile($zipfile);
					
				

					//Remove ZIP file after download
					@unlink($zipfile);
					@unlink($tmpFilePath);
				} else {
					echo "<script>alert('Invalid access');window.location.href='oldercampaignwiselogs.php';</script>";
				}
				
			} else {
				echo "<script>alert('Invalid access');window.location.href='oldercampaignwiselogs.php';</script>";
			}

		} else {
			echo "<script>alert('Invalid request');window.location.href='oldercampaignwiselogs.php';</script>"; 
		}

	} else if(isset($_GET['d']) && $_GET['d'] != "") {
		/*
		$encryptedFile = base64_decode(trim(filter_var($_GET['d'], FILTER_SANITIZE_STRING)));
		$logfile = basename($cryptor->decrypt($encryptedFile));
		*/
		
		$logfile = basename(base64_decode(trim(filter_var($_GET['d'], FILTER_SANITIZE_STRING))));
		//echo "File : ".$logfile;exit;
		$zipfile = "generatedFiles/".$logfile;

		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename="'.basename($zipfile).'"');
		header('Content-Length: '.filesize($zipfile) );

		ob_clean();
		flush();

		readfile($zipfile);


		//Remove ZIP file after download
		@unlink($zipfile);		
		@unlink($logfile);	
		
	} else {
		die("Access Denied");
	}


?>
