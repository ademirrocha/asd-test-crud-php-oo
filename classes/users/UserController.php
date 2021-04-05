<?php
namespace classes\users;
require_once $GLOBALS['PATH'] . '/classes/users/User.php';
require_once $GLOBALS['PATH'] . '/classes/users/CrudUser.php';
include ($GLOBALS['PATH'] . '/classes/database/Container.php');
include ($GLOBALS['PATH'] . '/classes/requests/users/GetFindRequest.php');

use classes\database\Container;
use classes\users\User;
use classes\users\CrudUser;

use classes\requests\users\GetFindRequest;

class UserController {


	public function getAll(){
		$conn = Container::getDB();
		$user = new User;
		$crud = new CrudUser($conn, $user);
		
		$users = $crud->all();
		
		include($GLOBALS['PATH'] . '/views/users/list.php');
	}

	public function getFind(){
		$requests = new GetFindRequest;
		$errors = $requests->validate();
		if(count($errors) > 0){
			;
			$back = $_SERVER['HTTP_REFERER'] ?? '/';
			header('Location: ' . $back . '?errors' . json_encode($errors));
		}else{
			$conn = Container::getDB();
			$user = new User;
			$crud = new CrudUser($conn, $user);
			
			$user = $crud->find($_GET['id']);
	
			include($GLOBALS['PATH'] . '/views/users/edit.php');
			
		}
		
		
	}

	public function create(){
		$conn = Container::getDB();
		$user = new User;
		$user->setId(0)->setName("Maria V")->setEmail('maria@gmail.com')->setPassword(crypt('12345678'));
		
		$crud = new CrudUser($conn, $user);
		echo "<pre>";
			print_r($crud->save());
	}

	public function update(){
		$conn = Container::getDB();
		$user = new User;
		$user->setId(3)->setName("Ademir Rocha")->setEmail('exemplo@gmail.com')->setPassword(crypt('12345678'));

		$crud = new CrudUser($conn, $user);
		echo "<pre>";
			print_r($crud->update());
	}

	public function delete(){
		$conn = Container::getDB();
		$user = new User;
		$crud = new CrudUser($conn, $user);
		echo "<pre>";
			print_r($crud->delete(3));
	}

	function validatePassword($password, $hash){
		
		if (crypt($password, $hash) === $hash) {
			echo 'Senha OK!';
		} else {
			echo 'Senha incorreta!';
		}
	}

	function generate_salt($len = 8) {
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`~!@#$%^&*()-=_+';
		$l = strlen($chars) - 1;
		$str = '';
		for ($i = 0; $i<$len; ++$i) {
			$str .= $chars[rand(0, $l)];
		}
		return $str;
	}

	//validatePassword('12345678', $produto1->getPassword());


}
