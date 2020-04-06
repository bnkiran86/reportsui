<?php
	//Logout User

	if (!isset($_SESSION))
		session_start();

	//Remove session name
	unset($_SESSION['LOGSUI']);

	//remove all the session variable
	session_unset();

	//Destroy the session
	session_destroy();

	//Redirect to Login Page
	header("Location: index.php");
?>
