<?php
namespace classes\auth;
require_once $GLOBALS['PATH'] . '/classes/users/User.php';
require_once $GLOBALS['PATH'] . '/classes/auth/Auth.php';
include ($GLOBALS['PATH'] . '/classes/database/Container.php');

use classes\database\Container;
use classes\users\User;
use classes\auth\Auth;

use classes\requests\auth\LoginRequest;
use classes\requests\users\UpdateRequest;
use classes\requests\users\DeleteRequest;

class AuthController {


	public function getLogin(){
		include($GLOBALS['PATH'] . '/views/login/index.php');
	}

	public function postLogin(){
		include ($GLOBALS['PATH'] . '/classes/requests/auth/LoginRequest.php');

		$requests = new LoginRequest;
		$errors = $requests->validate();
		if(count($errors) > 0){
			
            $back = explode('?', $_SERVER['HTTP_REFERER'])[0] ?? '/login';

            $back .= '?errors=' . json_encode($errors);
            
			header('Location: ' . $back);
		}else{
			$conn = Container::getDB();
			$user = new User;
			$crud = new CrudUser($conn, $user);
			
			$user = $crud->find($_GET['id']);
	
			include($GLOBALS['PATH'] . '/views/users/edit.php');
			
		}
		
		
	}


	function validatePassword($password, $hash){
		
		if (crypt($password, $hash) === $hash) {
			echo 'Senha OK!';
		} else {
			echo 'Senha incorreta!';
		}
	}

	//validatePassword('12345678', $produto1->getPassword());


}
