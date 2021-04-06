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

define("SALT", uniqid()); 

class UserController {

	/*/--------------------------------------------------------------//
		Metodo getAll() para visualizar pagina de listagem de usuários
	//--------------------------------------------------------------/*/
	static public function getAll(){

		//conectar com o banco de dados e instânciar a class User
		$conn = Container::getDB(); 
		$user = new User;    
		
		//instanciar o CRUD de usuario e buscar todos os users
		$crud = new CrudUser($conn, $user);  
		$users = $crud->all();				 

		//Exige a pagina de listagem de usuarios
		include($GLOBALS['PATH'] . '/views/users/list.php');   

	}
	//Final do metodo getAll()


	/*/--------------------------------------------------------------//
	/*	Inicio do Metodo getFind() 
	/*  Faz a busca um usuario específico
	//--------------------------------------------------------------/*/
	static public function getFind(){
		//include file GetFindRequest.php utilizado para validação do dados
		include ($GLOBALS['PATH'] . '/classes/requests/users/GetFindRequest.php');

		//instância a class GetFindRequest e fazer a validação dos dados
		$requests = new GetFindRequest;
		$errors = $requests->validate();

		//Verificação se houve erro nos dados
		if(count($errors) > 0){
			if( isset($errors['id']) ){
				$back = '/users?errors=' . json_encode(['id' => $errors['id']]);
			}else{
				$back = explode('?', $_SERVER['HTTP_REFERER'])[0] ?? '/users';
				$back .= '?id='.$_POST['id'].'&errors=' . json_encode($errors);
			}

			//Redireciona para pagina anterior ou para /users
			header('Location: ' . $back); 
		}else{

			$conn = Container::getDB();
			$user = new User;

			$crud = new CrudUser($conn, $user);
			$user = $crud->find($_GET['id']);
	
			include($GLOBALS['PATH'] . '/views/users/edit.php');
		}
	}
	//Final do metodo getFind()

	
	/*/--------------------------------------------------------------//
	/*	Inicio do Metodo createGet() 
	/*  Exibe a pagina de cadastro de usuario
	//--------------------------------------------------------------/*/
	static public function createGet(){
		include($GLOBALS['PATH'] . '/views/users/register.php');
	}
	//Final do metodo createGet()

	
	/*/--------------------------------------------------------------//
	/*	Inicio do Metodo createPost() 
	/*  Salva os dados de cadastro do usuario
	//--------------------------------------------------------------/*/
	static public function createPost(){
		include ($GLOBALS['PATH'] . '/classes/requests/users/CreateRequest.php');

		$requests = new CreateRequest;
		$errors = $requests->validate();
		
		//Criar url de redirecionar
		$back = explode('?', $_SERVER['HTTP_REFERER'])[0] ?? '/users/create';

		if(count($errors) > 0){
			//seta os params de retorno
			$back .= '?errors=' . json_encode($errors) . '&name='. $_POST['name'] . '&email='. $_POST['email'];

		}else{

			$conn = Container::getDB();
			$user = new User;
			
			//Cria criptografia de senha
			$password = crypt($_POST['password'], SALT);

			//seta os dados do usuario na instância da class User
			$user->setName($_POST['name'])
				->setEmail($_POST['email'])
				->setPassword($password);
		
			$crud = new CrudUser($conn, $user);

			try {
				//metodo save(), salva os dados do usuario no banco de dados
				$save = $crud->save();

				//Verificação se o cadastro foi com successo
				if($save){
					$back .= '?success=Usuário cadastrado com sucesso!';
				}else{

					//cria message de retorno com erro
					$messageError = ['Error' => 'Erro ao salvar usuario'];
					$back .= '?errors=' . json_encode($messageError) . '&name='. $_POST['name'] . '&email='. $_POST['email'];
				
				}
			} catch (\Throwable $th) {
				echo '<pre>';
					print_r($th);
			}
			
		}

		//Redireciona para $back
		header('Location: ' . $back);
	}
	//Final do metodo createPost()

	
	/*/--------------------------------------------------------------//
	/*	Inicio do Metodo update() 
	/*  Salva os alterações nos dados de usuario
	//--------------------------------------------------------------/*/
	static public function update(){

		include ($GLOBALS['PATH'] . '/classes/requests/users/UpdateRequest.php');

		//Validação dos dados
		$requests = new UpdateRequest;
		$errors = $requests->validate();

		$back = explode('?', $_SERVER['HTTP_REFERER'])[0] ?? '/users/find';

		//Verifiva se hove erro nos dados recebidos
		if(count($errors) > 0){

			//Verifica se id não existe no banco ou deixar enviar, força o redirecionamento para pagina /users
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

			//Verifica se foi enviado nova senha para alteração
			if(isset($_POST['password']) && ! empty($_POST['password']) ){
				//Criptografia de senha
				$password = crypt($_POST['password'], SALT);

				$user->setId($_POST['id'])
					->setName($_POST['name'])
					->setEmail($_POST['email'])
					->setPassword($password);
			}else{

				$user->setId($_POST['id'])
					->setName($_POST['name'])
					->setEmail($_POST['email']);

			}
			
			//Busca no banco de dados o user que vai ser alterado
			$currentUser = new User;
			$newCrud = new CrudUser($conn, $currentUser);
			$currentUser = $newCrud->find($user->getId());

			//Verifica se a senha do usuario enviada é a mesma que está no banco de dados
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

				//seta errors caso a senha esteja incorreta
				$messageError = ['Senha' => 'Senha inválida. A senha deve ser a mesma deste usuário'];
				$back .= '?id='.$_POST['id'].'&errors=' . json_encode($messageError) . '&name='. $_POST['name'] . '&email='. $_POST['email'];
			
			}
			
		}
		
		header('Location: '.$back);

	}
	//Final do metodo update()

	
	/*/--------------------------------------------------------------//
	/*	Inicio do Metodo delete() 
	/*  Deleta o usuario do banco de dados
	//--------------------------------------------------------------/*/
	static public function delete(){

		include ($GLOBALS['PATH'] . '/classes/requests/users/DeleteRequest.php');

		$requests = new DeleteRequest;
		$errors = $requests->validate();
		
		if(count($errors) > 0){

			//Verifica se id não existe no banco ou deixar enviar, força o redirecionamento para pagina /users
			if( isset($errors['id']) ){

				$back = '/users?errors=' . json_encode(['id' => $errors['id']]);

			}else{

				$back = explode('?', $_SERVER['HTTP_REFERER'])[0] ?? '/users';
				$back .= '?id='.$_POST['id'].'&errors=' . json_encode($errors);

			}

			header('Location: ' . $back);

		}else{

			//Caso não houver nenhum impedimento, faz o delete do usuario
			$conn = Container::getDB();
			$user = new User;
			$crud = new CrudUser($conn, $user);

			$crud->delete($_POST['id']);

			//Redireciona para pagina de /users
			header('Location: /users?success=Usuário deletado com sucesso');
			
		}

	}
	//Final do metodo delete()


}
