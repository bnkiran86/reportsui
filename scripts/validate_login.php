<?php

if($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST['ajaxCall']) && $_POST['ajaxCall'] == "yes") {
		include_once("classes/users.php");
		$objUser = new User();


		$username = htmlentities(addslashes(filter_var($_POST['username'], FILTER_SANITIZE_STRING)));
		$password = htmlentities(addslashes(filter_var($_POST['password'], FILTER_SANITIZE_STRING)));

		$details = $objUser->fnAuthenticate($username, $password);

		#print_r($details);exit;

		if(is_array($details) &&  $details != "NO_USER_AVAILABLE") {
			echo "SUCCESS";
			$_SESSION['LOGSUI']['login_status'] = '1';
			$_SESSION['LOGSUI']['username'] = $username;
		} else
			echo "FAILURE";
	} else
		die("Access Denied");
} else
	die("Access Denied");
?>

