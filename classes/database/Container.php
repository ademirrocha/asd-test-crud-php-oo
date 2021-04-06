<?php
namespace classes\database;

require_once $GLOBALS['PATH'] . '/classes/database/Database.php';
use classes\database\Database;

class Container {
	
	//Metodo que retorna a conexão com o banco de dados
	public static function getDB(){

		//methodo env(), pega config do arquivo .env
		$env = env();
		//pega config do JAWSDB_URL, para configuração heroku
		$jwsdb = getenv('JAWSDB_URL');

		//verifica qual configuração existe
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
		
		//Retorna conxao com o banco de dados
		return new Database($hostname, $database, $username, $password);
	}
}