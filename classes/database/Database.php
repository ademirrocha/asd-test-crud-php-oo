<?php
namespace classes\database;

//

require_once $GLOBALS['PATH'] . "/classes/database/InterfaceDatabase.php";
use classes\database\InterfaceDatabase;


class Database implements InterfaceDatabase {
	//Atributos
	private $host;
	private $dbname;
	private $user;
	private $senha;
	private $charset;

	//Metodos
	public function __construct($host, $dbname, $user, $senha, $charset = "utf8"){
		$this->host = $host;
		$this->dbname = $dbname;
		$this->user = $user;
		$this->senha = $senha;
		$this->charset = $charset;
	}

	public function connect(){
		
		try {
			$conn = new \PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->senha);
			// set the PDO error mode to exception
			$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			echo "Connected successfully";
			return $conn;
			//return new \PDO("mysqli: host={$this->host}; dbname={$this->dbname}; charset={$this->charset}", $this->user, $this->senha);
		} catch (\PDOException $erro) {
			echo "<h4>DB: ".$this->dbname."</h4><hr>";
			echo "<h4>Erro! Problema ao tentar conectar com o banco de dados</h4><hr>";
			echo "<h5> Arquivo: " . $erro->getFile() . "<br/>";
			echo " Linha: " . $erro->getLine() . "<br/>";
			echo " Mensagem: " . $erro->getMessage() . "<br/>";
			echo " Informações adicionais: " . $erro->getMessage() . "<br/>" . $erro->getCode() . "<br/>" . $erro->getPrevious() . "<br/></h5>";
		
			die();
		}
	}
}