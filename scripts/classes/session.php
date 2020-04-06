<?php

	//Initialize Database
	include(dirname(dirname(dirname(__FILE__)))."/includes/database.php");

	//Class for Session Management
	class Session  {

		function __construct() {
			global $database;

			//Start the Session
			$this->fnStartSession();
		}


		function fnStartSession() {
			//Start the Session
			if (!isset($_SESSION))
				session_start();
		}
	}
?>

