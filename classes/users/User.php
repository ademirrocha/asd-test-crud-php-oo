<?php
namespace classes\users;
include $GLOBALS['PATH'] . "/classes/users/InterfaceUser.php";
use classes\users\InterfaceUser;


class User implements InterfaceUser {

	//fillable
	private $id;
	private $name;
	private $email;
	private $password;
	private $created_at;


	//############### Setters ##############
	public function setId(int $id){
		$this->id = $id;
		return $this;
	}

	public function setName($name){
		$this->name = $name;
		return $this;
	}

	public function setEmail($email){
		$this->email = $email;
		return $this;
	}

	public function setPassword($password){
		$this->password = $password;
		return $this;
	}

	public function setCreatedAt($created_at){
		$this->created_at = $created_at;
		return $this;
	}

	//############### Getters ##############
	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}

	public function getEmail(){
		return $this->email;
	}


	public function getPassword(){
		return $this->password;
	}

	public function getCreatedAt(){
		return $this->created_at;
	}


}