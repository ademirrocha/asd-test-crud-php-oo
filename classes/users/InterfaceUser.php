<?php
namespace classes\users;

interface InterfaceUser {

	//metodos	

	//Setters
	public function setId(int $id);
	public function setName($name);
	public function setEmail($email);
	public function setPassword($password);
	public function setCreatedAt($created_at);


	//Getters
	public function getId();
	public function getName();
	public function getEmail();
	public function getPassword();
	public function getCreatedAt();

}