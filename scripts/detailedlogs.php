<?php

	require_once(dirname(__FILE__).'/classes/users.php');
	$objUser = new User();

	$loginDetails = $objUser->fnGetLoginUserDetails();

	if(!$loginDetails['login_status']) {
		die("LOGIN_SESSION_EXPIRED");
	}


	#Include Cyptor lib
	require_once(dirname(dirname(__FILE__))."/includes/Cryptor.php");
	$cryptor = new Cryptor(CRYPTOR_SALT);

	$username = $loginDetails['username'];

	if(!empty($_POST)) { 
		#Save the request details
		$response = $objUser->fnSaveDownloadRequest($username, $_POST);
	} else {
		$response =  'No valid request found';
	}
	echo $response;
?>
