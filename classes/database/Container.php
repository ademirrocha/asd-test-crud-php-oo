<?php
namespace classes\database;

require_once $GLOBALS['PATH'] . '/classes/database/Database.php';
use classes\database\Database;

class Container {
	

	public static function getDB(){
		$env = env();
		
		return new Database(getenv('DB_HOST').':'.getenv('DB_PORT'), getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
	}
}