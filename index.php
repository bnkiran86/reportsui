<?php

	include_once(dirname(__FILE__).'/scripts/classes/users.php');
	$objUser = new User();

	$loginDetails = $objUser->fnGetLoginUserDetails();
	
	if($loginDetails['login_status'])
		header("Location: summary.php");
	else
		header("Location: login.html");
?>

