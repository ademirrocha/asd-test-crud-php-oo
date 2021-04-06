<?php 

include "./vendor/Router.php";

use classes\users\UserController;
use classes\auth\AuthController;

/**
 * -----------------------------------------------
 * PHP Route Things
 * -----------------------------------------------
 */

//URL raiz, redireciona para pagina de listagem de usuarios
Route::add('GET', '/', function(){
    header('Location: /users');
});

//Pagina de listagem de usuarios cadastrados
Route::add('GET', '/users', function(){
    include('./classes/users/UserController.php');
    UserController::getAll();
    
});

//Pagina de de visualizar e editar dados de um usuario
Route::add('GET', '/users/find', function(){
    include('./classes/users/UserController.php');
    UserController::getfind();
});

//Pagina de cadastrar usuarios
Route::add('GET', '/users/create', function(){
    include('./classes/users/UserController.php');
    UserController::createGet();
});

//Endpoint de salvar usuario
Route::add('POST', '/users/create', function(){
    include('./classes/users/UserController.php');
    UserController::createPost();
});

//Endpoint de salvar alteração usuario
Route::add('POST', '/users/update', function(){
    include('./classes/users/UserController.php');
    UserController::update();
});

//Endpoint de deletar usuario
Route::add('POST', '/users/delete', function(){
    include('./classes/users/UserController.php');
    UserController::delete();
});

//Pagina de erro para url not found
Route::add('GET', '/notFound', function(){
    include('./notFound.php');
});

//Metodo para pegar arquivo de configuração .env
function env(){
    return parse_ini_file('.env');
}

//Metodo para execução das rotas    
Route::submit();