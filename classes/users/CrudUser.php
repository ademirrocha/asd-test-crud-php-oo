<?php
namespace classes\users;

use classes\users\IntefaceUser;
use classes\database\InterfaceDatabase;
use classes\users\User;
class CrudUser {
	//Atributes
	private $db;
	private $user;

	//Constructor
	public function __construct (InterfaceDatabase $db, InterfaceUser $user){
		$this->db = $db->connect();
		$this->user = $user;
	}


	public function save(){
		
		$sql = "INSERT INTO `users` (
			`id`,
			`name`,
			`email`,
			`password`,
			`created_at`
		) VALUES (
			:id,:name,:email,:password,:created_at
		)";

		$stmt = $this->db->prepare($sql);

		$stmt->bindValue(':id', $this->user->getId());
		$stmt->bindValue(':name', $this->user->getName());
		$stmt->bindValue(':email', $this->user->getEmail());
		$stmt->bindValue(':password', $this->user->getPassword());
		$stmt->bindValue(':created_at', date('Y-m-d'));

		$result = $stmt->execute();

		if(!$result){
			echo "<pre>";
				print_r($stmt->errorInfo());
			echo "</pre>";
			return $stmt->errorInfo();
		} else {
			return $this->db->lastInsertId();
		}

	}

	public function delete(int $id){
		
		$id = filter_var($id, FILTER_VALIDATE_INT);

		if($id === 0 && !is_int($id) && $id <= 0 && !$id){
			return false;
		}

		$query = "DELETE FROM `users` where id = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id', $id);
		$result = $stmt->execute();
		if(!$result){
			echo "<pre>";
				print_r($stmt->errorInfo());
			echo "</pre>";
			return false;
		} else {
			return $stmt->rowCount();
		}
	}


	public function update(){
				
		$id = filter_var($this->user->getId(), FILTER_VALIDATE_INT);
		if($id === 0 && !is_int($id) && $id <= 0 && !$id){
			return false;
		}

		if(! is_null($this->user->getPassword())){
			$sql = "UPDATE `users` set `name` = :name, `email` = :email, `password` = :password where `id` = :id";
		}else{
			$sql = "UPDATE `users` set `name` = :name, `email` = :email where `id` = :id";
		}
		
		$stmt = $this->db->prepare($sql);

		$stmt->bindValue(':id', $this->user->getId());
		$stmt->bindValue(':name', $this->user->getName());
		$stmt->bindValue(':email', $this->user->getEmail());
		if(! is_null($this->user->getPassword())){
			$stmt->bindValue(':password', $this->user->getPassword());
		}
		
			
		
		$result = $stmt->execute();

		if(!$result){
			echo "<pre>";
				print_r($stmt->errorInfo());
			echo "</pre>";
			return false;		
		} else {
			return $result;
		}

	}


	public function all(){
		$sql = "SELECT * FROM `users`";

		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$users = array();
		foreach($result as $key => $value){
			$user = new User;
			$user->setId($value['id'])
				->setName($value['name'])
				->setEmail($value['email'])
				->setCreatedAt($value['created_at']);
		
			array_push($users, $user);
		}
		return $users;
	}



	public function find(int $id){
		
		$id = filter_var($id, FILTER_VALIDATE_INT);
		$sql = "SELECT * FROM `users` where id = :id";

		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':id', $id);
		$result = $stmt->execute();
		if(!$result){
			echo "<pre>";
				print_r($stmt->errorInfo());
			echo "<;pre>";
			return false;
		} else {
			$value = $stmt->fetch(\PDO::FETCH_ASSOC);

			$user = new User;
			if($value){
				$user->setId($value['id'])
					->setName($value['name'])
					->setEmail($value['email'])
					->setCreatedAt($value['created_at']);
			}
			
			return $user;
		}

	}

}