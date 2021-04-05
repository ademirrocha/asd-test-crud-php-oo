<?php
namespace classes\users;
require_once $GLOBALS['PATH'] . '/classes/users/User.php';
require_once $GLOBALS['PATH'] . '/classes/users/CrudUser.php';
include ($GLOBALS['PATH'] . '/classes/database/Container.php');

use classes\database\Container;
use classes\users\User;
use classes\users\CrudUser;

use classes\requests\users\GetFindRequest;
use classes\requests\users\UpdateRequest;
use classes\requests\users\DeleteRequest;
use classes\requests\users\CreateRequest;

class UserController {


	static public function getAll(){
		$conn = Container::getDB();
		$user = new User;
		$crud = new CrudUser($conn, $user);
		
		$users = $crud->all();
		
		include($GLOBALS['PATH'] . '/views/users/list.php');
	}

	public function getFind(){
		include ($GLOBALS['PATH'] . '/classes/requests/users/GetFindRequest.php');

		$requests = new GetFindRequest;
		$errors = $requests->validate();
		if(count($errors) > 0){
			if( isset($errors['id']) ){
				$back = '/users?errors=' . json_encode(['id' => $errors['id']]);
			}else{
				$back = explode('?', $_SERVER['HTTP_REFERER'])[0] ?? '/users';
				$back .= '?id='.$_POST['id'].'&errors=' . json_encode($errors);
			}
			header('Location: ' . $back);
		}else{
			$conn = Container::getDB();
			$user = new User;
			$crud = new CrudUser($conn, $user);
			
			$user = $crud->find($_GET['id']);
	
			include($GLOBALS['PATH'] . '/views/users/edit.php');
			
		}
		
		
	}

	static public function createGet(){
		include($GLOBALS['PATH'] . '/views/users/register.php');
	}

	public function createPost(){
		include ($GLOBALS['PATH'] . '/classes/requests/users/CreateRequest.php');

		$requests = new CreateRequest;
		$errors = $requests->validate();
		$back = explode('?', $_SERVER['HTTP_REFERER'])[0] ?? '/users/create';
		if(count($errors) > 0){
			$back .= '?errors=' . json_encode($errors) . '&name='. $_POST['name'] . '&email='. $_POST['email'];
		}else{
			$conn = Container::getDB();
			$user = new User;
			$user->setName($_POST['name'])
				->setEmail($_POST['email'])
				->setPassword(crypt( $_POST['password'] ));

			$crud = new CrudUser($conn, $user);
			$save = $crud->save();
			if(is_int($save)){
				$back .= '?success=Usuário cadastrado com sucesso!';
			}else{
				if (strpos($save[2], 'Duplicate') == true) {
					$messageError = ['Email' => 'Email já Cadastrado'];
				}else{
					$messageError = ['Error' => $save];
				}
				$back .= '?errors=' . json_encode($messageError) . '&name='. $_POST['name'] . '&email='. $_POST['email'];
			}
		}
		header('Location: ' . $back);
	}

	public function update(){
		include ($GLOBALS['PATH'] . '/classes/requests/users/UpdateRequest.php');

		$requests = new UpdateRequest;
		$errors = $requests->validate();
		$back = explode('?', $_SERVER['HTTP_REFERER'])[0] ?? '/users/find';
		if(count($errors) > 0){
			
			if( isset($errors['id']) ){
				$back = '/users?errors=' . json_encode(['id' => $errors['id']]);
			}else{
				
				$back = explode('?', $_SERVER['HTTP_REFERER'])[0] ?? '/users/find';
				$back .= '?id='.$_POST['id'].'&errors=' . json_encode($errors);
			}
			header('Location: '.$back);
		}else{
			$conn = Container::getDB();
			$user = new User;
			if(isset($_POST['password']) && ! empty($_POST['password']) ){
				
				$user->setId($_POST['id'])
					->setName($_POST['name'])
					->setEmail($_POST['email'])
					->setPassword(crypt( $_POST['password'] ));
			}else{
				$user->setId($_POST['id'])
					->setName($_POST['name'])
					->setEmail($_POST['email']);
			}
			

			$crud = new CrudUser($conn, $user);
			$update = $crud->update();
			
			if($update == 1){
				$back .= '?id='.$_POST['id'].'&success=Dados Atualizados com sucesso!';
			}else{
				
				if (strpos($update[2], 'Duplicate') !== false) {
					
					$messageError = ['Email' => 'Email já Cadastrado'];
				}else{
					$messageError = ['Error' => $update];
				}
				$back .= '?id='.$_POST['id'].'&errors=' . json_encode($messageError) . '&name='. $_POST['name'] . '&email='. $_POST['email'];
			}
		}
		
		header('Location: '.$back);
	}

	public function delete(){

		include ($GLOBALS['PATH'] . '/classes/requests/users/DeleteRequest.php');

		$requests = new DeleteRequest;
		$errors = $requests->validate();
		print_r($errors);
		if(count($errors) > 0){
			if( isset($errors['id']) ){
				$back = '/users?errors=' . json_encode(['id' => $errors['id']]);
			}else{
				$back = explode('?', $_SERVER['HTTP_REFERER'])[0] ?? '/users';
				$back .= '?id='.$_POST['id'].'&errors=' . json_encode($errors);
			}
			header('Location: ' . $back);
		}else{
			$conn = Container::getDB();
			$user = new User;
			$crud = new CrudUser($conn, $user);
				if($crud->delete($_POST['id'])){
					header('Location: /users?success=Usuário deletado com sucesso');
				}
		}
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
