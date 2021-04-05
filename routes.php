<?php 

include "./vendor/Router.php";

use classes\users\UserController;
use classes\auth\AuthController;

/**
 * -----------------------------------------------
 * PHP Route Things
 * -----------------------------------------------
 */

//define your route. This is main page route. for example www.example.com
Route::add('GET', '/', function(){
    header('Location: /users');
});

Route::add('GET', '/users', function(){
    include('./classes/users/UserController.php');
    UserController::getAll();
    
});

Route::add('GET', '/users/find', function(){
    include('./classes/users/UserController.php');
    UserController::getfind();
});

Route::add('GET', '/users/create', function(){
    include('./classes/users/UserController.php');
    UserController::createGet();
});

Route::add('POST', '/users/create', function(){
    include('./classes/users/UserController.php');
    UserController::createPost();
});

Route::add('POST', '/users/update', function(){
    include('./classes/users/UserController.php');
    UserController::update();
});

Route::add('POST', '/users/delete', function(){
    include('./classes/users/UserController.php');
    UserController::delete();
});

Route::add('GET', '/notFound', function(){
    include('./notFound.php');
});

function env(){
    return parse_ini_file('.env');
}

//method for execution routes    
Route::submit();