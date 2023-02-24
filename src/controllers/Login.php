<?php

namespace Diego\Ig\controllers;

use Diego\Ig\lib\Controller;
use Diego\Ig\models\User;

class Login extends Controller{

    function __construct()
    {
        parent::__construct();

        
    }

    public function auth(){
        $username = $this->post("username");
        $password = $this->post("password");

        if(!is_null($username) && !is_null($password)){

            if(User::exists($username)){
                $user = User::get($username);

                if($user->comparePassword($password)){
                    $_SESSION["user"] = serialize($user);
                    error_log("User logged in");
                    header("location: /instagram/home");
                }else{
                    error_log("No es el mismo password");
                    header("location: /instagram/login");
                }
            }else{
                error_log("User not found");
                header("location: /instagram/login");
            }
       
        }else{
            error_log("Data incomplete");
            header("location: /instagram/login");
        }
    }

    /*public function auth($data){
        if(isset($data['username']) && isset($data['password'])){
            $username = $data['username'];
            $password = $data['password'];

            if(User::exists($username)){
                error_log('si existe');
                error_log('username: '.$username);
                $user = User::get($username);
                
                if($user->comparePasswords($password)){
                
                    $_SESSION["user"] = serialize($user);

                    header('location: home');
                }else{
                    echo "password incorrecto";
                }
            }else{
                header('location: /instagram/login');
            }
        }else{
            $this->render('errors/index');
        }
    }*/
}