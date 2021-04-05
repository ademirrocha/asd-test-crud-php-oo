<?php
namespace classes\database;

require_once $GLOBALS['PATH'] . '/classes/database/Database.php';
use classes\database\Database;

class Container {
	

	public static function getDB(){
		$env = env();
		$jwsdb = getenv('JAWSDB_URL');
		if( isset($env['JAWSDB_URL']) || $jwsdb != '' ){
			
			$dbparts = parse_url($jwsdb);

			$hostname = $dbparts['host'];
			$username = $dbparts['user'];
			$password = $dbparts['pass'];
			$database = ltrim($dbparts['path'],'/');
		}else{
			$hostname = $env['DB_HOST'];
			$username = $env['DB_USERNAME'];
			$password = $env['DB_PASSWORD'];
			$database = $env['DB_DATABASE'];
		}
			

			
		
		return new Database($hostname, $database, $username, $password);
	}
}