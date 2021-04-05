<?php 

include "./vendor/Router.php";

use classes\users\UserController;

/**
 * -----------------------------------------------
 * PHP Route Things
 * -----------------------------------------------
 */

//define your route. This is main page route. for example www.example.com
Route::add('GET', '/', function(){

    //define which page you want to display while user hit main page. 
    include('myindex.php');
});


Route::add('POST', '/login', function(){
    include('./views/login/index.php');
   
});

Route::add('POST', '/logout', function(){
    include('logout.php');
});

Route::add('GET', '/users', function(){
    include('./classes/users/UserController.php');
    UserController::getAll();
    
});

Route::add('GET', '/users/find', function(){
    include('./classes/users/UserController.php');
    UserController::getfind();
});

Route::add('POST', '/users/create', function(){
    include('./classes/users/UserController.php');
    UserController::create();
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





//method for execution routes    
Route::submit();