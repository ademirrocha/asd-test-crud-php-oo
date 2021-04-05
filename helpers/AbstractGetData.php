<?php
namespace helpers;

use classes\database\InterfaceDatabase;
class AbstractGetData {
	//Atributes
	private $db;
	
	//Constructor
	public function __construct (InterfaceDatabase $db){
		$this->db = $db->connect();
	}


	public function get($table, $column, $value){
		
		$sql = "SELECT * FROM `$table` where $column = :$column";

		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':'.$column, $value);
		$result = $stmt->execute();
		if(!$result){
			echo "<pre>";
				print_r($stmt->errorInfo());
			echo "<;pre>";
			return false;
		} else {
			$data = $stmt->fetch(\PDO::FETCH_ASSOC);
			return $data;
		}

	}

}