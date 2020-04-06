<?php

	date_default_timezone_set('Asia/Kolkata');
	error_reporting( E_ALL ^ E_DEPRECATED );

	ini_set('max_execution_time', 6000); //6000 seconds = 10 minutes
	
	ini_set('display_errors', 0);


	define('DB_SERVER', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASS', 'mysql123');
	define('DB_NAME', 'sms_platform_logs');
	define('LOGS_DATABASE_NAME', 'logsui');
	define('LOGS_ROOT_FOLDER', 'mis_reportlogs');
	define('CRYPTOR_SALT' , "*ku7H]-!hRkQ4hYa]wa[+1K3DC|7;xaPGH)?pUUWKS2'RtFhaAs-v/).1W?F'T");

