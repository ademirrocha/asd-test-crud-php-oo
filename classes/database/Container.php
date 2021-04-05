<?php
namespace classes\database;

require_once $GLOBALS['PATH'] . '/classes/database/Database.php';
use classes\database\Database;


class Container {
	public static function getDB(){
		return new Database('localhost', 'asd_test_php', 'root', '');
	}
}