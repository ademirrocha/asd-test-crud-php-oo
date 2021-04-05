<?php
namespace classes\database;

require_once $GLOBALS['PATH'] . '/classes/database/Database.php';
use classes\database\Database;

class Container {
	

	public static function getDB(){
		$env = env();
		return new Database($env['DB_HOST'].':'.$env['DB_PORT'], $env['DB_DATABASE'], $env['DB_USERNAME'], $env['DB_PASSWORD']);
	}
}