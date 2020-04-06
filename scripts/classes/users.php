<?php

	
	//User Information Class
	include_once(dirname(__FILE__)."/session.php");

	class User extends Session {
		static private $objSesion = "";
		var $returnMSg = "";
		var $returnArray  = "";

		function __construct() {
			$this->objSession = new Session();
		}

		function fnAuthenticate($username, $password) {
			/*
			global $configUsers;			
			
			if(isset($configUsers[$username.":".$password])) {
				return $configUsers[$username.":".$password];
			} else {
				return "NO_USER_AVAILABLE";
			}
			*/

			global $database;
			
			$sQry = " SELECT * FROM tbl_users WHERE username = :username AND password = :password AND status = :status";
			$PDO_Stmt = $database->fnFetchRecords($sQry , array(':username'=>$username, ':password' => $password, ':status' => '1'));
			if($PDO_Stmt->rowCount() <= 0 ) {
				return "NO_USER_AVAILABLE";
			} else {
				return $PDO_Stmt->fetch();
			}
		}

		function fnGetLoginUserDetails() {
			unset($this->returnArray);

			if(isset($_SESSION['LOGSUI']['login_status']) && $_SESSION['LOGSUI']['login_status'] == "1") {
				$this->returnArray['login_status'] = true;
				$this->returnArray['username'] = $_SESSION['LOGSUI']['username'];
			} else
				$this->returnArray['login_status'] = false;

			return $this->returnArray;
		}

		function fnSaveDownloadRequest($username, $requestData) {
			global $database;

			$reporttag = ( isset($requestData['reporttag']) && !empty($requestData['reporttag']) ) ?  addslashes(trim($requestData['reporttag'])) : uniqid();

			

			$iData = array(
				'report_tag' => $reporttag,
				'username' => $username,
				'request_query' => json_encode($requestData),
				'status' => '0',
				'timestamp' => time()
			);

			$iQry  = " INSERT INTO tbl_logs_requests (report_tag, username, request_query,status,timestamp ) ";
			$iQry .= " VALUES (:report_tag, :username, :request_query, :status, :timestamp)";

			$insertID = $database->fnSaveRecords($iQry, $iData);


			#$insertID = $stmt->lastInsertId();
			
			if($insertID > 0) {
				return 'Your requested logs will be available with 24 hours';
			} else {
				return 'Internal Error Occured, kindly contact administrator';
			}
		}
		function fnGetFileExtension($fileName) {
			return substr(strrchr($fileName,'.'),1);
		}
	}
?>
