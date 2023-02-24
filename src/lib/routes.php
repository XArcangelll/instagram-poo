<?php

use Diego\Ig\controllers\Signup;
use Diego\Ig\controllers\Login;
use Diego\Ig\controllers\Home;
use Diego\Ig\controllers\Actions;
use Diego\Ig\controllers\Profile;

$router = new \Bramus\Router\Router();

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../config/");
$dotenv->load();

$router->before('GET', '/', function() { 

    if(isset($_SESSION['user'])){
        //$user = unserialize($_SESSION['user']);
        header('location: /instagram/home');
    }else{
        header('location: /instagram/login');
        //exit();
    }
});

function auth(){
    if(isset($_SESSION["user"])){
        header("location: /instagram/home");
        exit();
    }
}

function notAuth(){
    if(!isset($_SESSION["user"])){
        header("location: /instagram/login");
        exit();
    }
}

$router->get("/", function(){
    
});

$router->get("/login", function(){
    auth();
    $controller = new Login;
    $controller->render("login/index");
});

$router->post("/auth", function(){
    auth();
    $controller = new Login;
    $controller->auth();
});

$router->get("/signup", function(){
    auth();
    $controller = new Signup;
    $controller->render("signup/index");
});

$router->post("/register", function(){
    auth();
    $controller = new Signup;
    $controller->register();
});

$router->get("/home", function(){
    notAuth();
    $user =   unserialize($_SESSION["user"]);
    $controller = new Home($user);
    $controller->index();
});

$router->post("/publish", function(){
    notAuth();
    $user =   unserialize($_SESSION["user"]);
    $controller = new Home($user);
    $controller->store();
});



$router->post("/addLike", function(){
    notAuth();
    $user =   unserialize($_SESSION["user"]);
    $controller = new Actions($user);
    $controller->like();
});

$router->get("/signout", function(){
    notAuth();
    unset($_SESSION["user"]);
    header("location: /instagram/login");
});

$router->get("/profile", function(){
    notAuth();
    $user =   unserialize($_SESSION["user"]);
   $controller = new Profile();
   $controller->getUserProfile($user);
});

$router->get("/profile/{username}", function($username){
    $controller = new Profile();
    $controller->getUsernameProfile($username);
});

$router->run();