<?php

namespace Diego\Ig\lib;

use Diego\Ig\lib\Database;

class Model{

    private Database $db;

    public function __construct()
    {
        
        $this->db = new DataBase();
    }

    public function query($query){
        return $this->db->connect()->query($query);
    }

    public function prepare($query){
        return $this->db->connect()->prepare($query);
    }



    

}