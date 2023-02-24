<?php

namespace Diego\Ig\lib;

use Diego\Ig\lib\View;

class Controller{
    private View $view;

    public function __construct()
    {
        $this->view = new view();
    }

    public function render(string $name, array $data = []){
        $this->view->render($name,$data);
    }

    protected function get(string $param){

        if(!isset($_GET[$param])){
            return null;
        }

        return $_GET[$param];

    }

    protected function post(string $param){
        if(!isset($_POST[$param])){
            return null;
        }

        return $_POST[$param];

    }


    protected function file(string $param){
        if(!isset($_FILES[$param])){
            return null;
        }

        return $_FILES[$param];
    }

}