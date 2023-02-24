<?php

namespace Diego\Ig\lib;

class View{
    
    public $d;

    function render(string $name, array $data = []){
        $this->d = $data;
        require "src/views/" . $name . ".php"; 

    }
}