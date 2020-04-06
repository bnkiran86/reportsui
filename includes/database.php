<?php

	include_once(dirname(__FILE__)."/PDOConnect.php");

	class MySQLDB extends PDOConnect
	{

		protected $dbConn = null;

		public function __construct() {
			$this->dbConn = PDOConnect::getInstance();
		}

		private function __clone() {
			// Stopping Clonning of Object
			return false;
		}

		private function __wakeup() {
			// Stopping unserialize of object
			return false;
		}

		public function fnSetCharset() {
			$this->dbConn->exec("set character_set_results='utf8'");
			$this->dbConn->exec("SET NAMES 'utf8'");
		}

		public  function fnFetchRecords($sql , $args = [] , $limit = '' , $offset = '') {
			if (!$args) {
				return "INVALID_ACCESS";
			}

			$stmt = $this->dbConn->prepare($sql);
			$stmt->execute($args);

			//$stmt->debugDumpParams();
			return $stmt;
		}

		public function fnSaveRecords($sql , $data) {
			$stmt = $this->dbConn->prepare($sql);
			$stmt->execute($data);

			//$stmt->debugDumpParams();
			
			return $this->dbConn->lastInsertId();
		}


		public function fnCloseConn(){
			@$this->dbConn = null;
			@$this->dbConn->close();
		}
	};

	/* Create database connection */
	$database = new MySQLDB();
?>
