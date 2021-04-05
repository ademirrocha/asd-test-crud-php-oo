<?php 

include "./vendor/Router.php";

use classes\users\UserController;

/**
 * -----------------------------------------------
 * PHP Route Things
 * -----------------------------------------------
 */

//define your route. This is main page route. for example www.example.com
Route::add('/', function(){

    //define which page you want to display while user hit main page. 
    include('myindex.php');
});


// route for www.example.com/join
Route::add('/join', function(){
    include('/join.php');
});


Route::add('/login', function(){
    include('./views/login/index.php');
   
});

Route::add('/forget', function(){
    include('forget.php');
});



Route::add('/logout', function(){
    include('logout.php');
});

Route::add('/users', function(){
    include('./classes/users/UserController.php');
    UserController::getAll();
    
});

Route::add('/users/find', function(){
    include('./classes/users/UserController.php');
    UserController::getfind();
});

Route::add('/users/create', function(){
    include('./classes/users/UserController.php');
    UserController::create();
});

Route::add('/users/update', function(){
    include('./classes/users/UserController.php');
    UserController::update();
});

Route::add('/users/delete', function(){
    include('./classes/users/UserController.php');
    UserController::delete();
});

Route::add('/notFound', function(){
    include('notFound.php');
});





//method for execution routes    
Route::submit();