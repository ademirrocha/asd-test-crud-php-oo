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

	static public function getFind(){
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

	static public function createPost(){
		include ($GLOBALS['PATH'] . '/classes/requests/users/CreateRequest.php');

		$requests = new CreateRequest;
		$errors = $requests->validate();
		
		$back = explode('?', $_SERVER['HTTP_REFERER'])[0] ?? '/users/create';
		if(count($errors) > 0){
			
			$back .= '?errors=' . json_encode($errors) . '&name='. $_POST['name'] . '&email='. $_POST['email'];
		}else{
			$conn = Container::getDB();
			$user = new User;
			
			$password = password_hash( $_POST['password'], PASSWORD_DEFAULT);
			$user->setName($_POST['name'])
				->setEmail($_POST['email'])
				->setPassword($password);
		
			$crud = new CrudUser($conn, $user);
			try {
				$save = $crud->save();
				if($save){
					$back .= '?success=Usuário cadastrado com sucesso!';
				}else{
					if (strpos($save[2], 'Duplicate') == true) {
						$messageError = ['Email' => 'Email já Cadastrado'];
					}else{
						$messageError = ['Error' => 'Erro ao salvar usuario'];
					}
					$back .= '?errors=' . json_encode($messageError) . '&name='. $_POST['name'] . '&email='. $_POST['email'];
				}
			} catch (\Throwable $th) {
				//$messageError = ['Error' => 'Erro ao salvar usuario'];
				//$back .= '?errors=' . json_encode($messageError) . '&name='. $_POST['name'] . '&email='. $_POST['email'];
			}
			
		}
		header('Location: ' . $back);
	}

	static public function update(){
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
				$password = password_hash( $_POST['password'], PASSWORD_DEFAULT);
				$user->setId($_POST['id'])
					->setName($_POST['name'])
					->setEmail($_POST['email'])
					->setPassword($password);
			}else{
				$user->setId($_POST['id'])
					->setName($_POST['name'])
					->setEmail($_POST['email']);
			}
			
			$currentUser = new User;
			$newCrud = new CrudUser($conn, $currentUser);
			$currentUser = $newCrud->find($user->getId());

			
			if(password_verify($_POST['old-password'], $currentUser->getPassword()) == true){

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

			}else{
				$messageError = ['Senha' => 'Senha inválida. A senha deve ser a mesma deste usuário'];
				$back .= '?id='.$_POST['id'].'&errors=' . json_encode($messageError) . '&name='. $_POST['name'] . '&email='. $_POST['email'];
			}
			
		}
		
		header('Location: '.$back);
	}

	static public function delete(){

		include ($GLOBALS['PATH'] . '/classes/requests/users/DeleteRequest.php');

		$requests = new DeleteRequest;
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
				if($crud->delete($_POST['id'])){
					header('Location: /users?success=Usuário deletado com sucesso');
				}
		}
	}

	

	//validatePassword('12345678', $produto1->getPassword());


}
