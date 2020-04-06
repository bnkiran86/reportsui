<?php

	require_once(dirname(__FILE__)."/config.php");

	class PDOConnect
	{

		private static $instance = null;
		private $pdo;

		private function __construct() {

			$dbDetails = array(
						'DB_NAME' => DB_NAME,
						'DB_HOST' => DB_SERVER,
						'DB_USERNAME' => DB_USER,
						'DB_PASSWORD' => DB_PASS
					);


			$opt  = array(
					PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					PDO::ATTR_EMULATE_PREPARES   => FALSE,
				);

			#DSN Generation
			$dsn = "mysql:host=".$dbDetails['DB_HOST'].";dbname=".$dbDetails['DB_NAME'];

			try{
				$this->pdo = new PDO($dsn, $dbDetails['DB_USERNAME'], $dbDetails['DB_PASSWORD']);
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				$this->pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
			} catch(PDOException $e) {
				#echo $e->getMessage();
				die("Internal issue occured, kinldy contact administrator");
			}
		}

		private function __clone() {
			// Stopping Clonning of Object
			return false;
		}

		private function __wakeup() {
			// Stopping unserialize of object
			return false;
		}

		public static function getInstance() {
			if( !isset(self::$instance) || self::$instance === null) {
				self::$instance = new PDOConnect();
			}
			return self::$instance;
		}

		public function __call($method, $args) {
			if ( method_exists($this->pdo, $method) ) {
				return call_user_func_array(array($this->pdo, $method), $args);
			}
		}
	};
?>
