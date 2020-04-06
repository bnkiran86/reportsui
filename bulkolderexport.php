<?php

	include_once(dirname(__FILE__).'/scripts/classes/users.php');
        $objUser = new User();

        $loginDetails = $objUser->fnGetLoginUserDetails();

        if(!$loginDetails['login_status']) {
                header("Location: login.html");
        }

	$server = $loginDetails['server'];
	$username = $loginDetails['username'];

	$currentMonth = date("mY");
	$logsPath = "/mis_reportlogs/".$currentMonth."/".$server."/";

	if(isset($_POST['q']) && $_POST['q'] != "") {
		$q = addslashes(trim($_POST['q']));

		$q = explode(",",base64_decode($q));
		//echo "<pre>";print_r($q);echo "</pre>";

		//make temp dir inside generatedFiles folder
		$tmpDir = "/tmp/".$username."-".uniqid();

		$zipfile = "generatedFiles/".$username."-".uniqid().".zip";

		if (!file_exists($tmpDir)) {
			mkdir($tmpDir, 0777, true);
		}

		//Copy the original files inside this temp Dir
		foreach($q as $key => $q) {
			if($q != "") {
				$param = base64_decode($q);
				$temp = explode("-",$param);
				//echo "<pre>";print_r($temp);echo "</pre>";
				if(count($temp) == 3) {
					$orgPath = $logsPath."/".$temp[0]."/".$temp[1]."/".$temp[2];
					exec("cp ".$orgPath." ".$tmpDir);
				}
			}
		}

		//Zip all files inside tempDir & force download
		exec("zip -9jpr ".$zipfile." ".$tmpDir );

		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename="'.basename($zipfile).'"');
		header('Content-Length: '.filesize($zipfile) );

		ob_clean();
		flush();

		readfile($zipfile);

		//Remove ZIP file after download
		@unlink($zipfile);
		@unlink($tmpDir);

	} else {
		die("ACCESS DENIED");
	}

	
?>
