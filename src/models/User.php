<?php

namespace Diego\Ig\models;

use  Diego\Ig\lib\Database;
use  Diego\Ig\lib\Model;
use PDO;
use PDOException;

class User extends Model{

    private int $id;
    private array $posts;
    private string $profile;

    public function __construct(private string $username, private string $password)
    {
        parent::__construct();
        $this->posts = [];
        $this->profile = '';
    }

    public function save(){
        try {
            //validar si existe el ususario;
            $hash = $this->getHashedPassword($this->password);
            $query = $this->prepare("INSERT INTO users(username,password,profile) VALUES(:username, :password, :profile)");
            $query->execute([
                "username" => $this->username,
                "password" => $hash,
                "profile" => $this->profile
            ]);
            return true;
        } catch (PDOException $e) {
           error_log($e->getMessage());
           return false;
        }
    }

    public static function exists($username){
        try{
            $db = new Database();
            $query = $db->connect()->prepare('SELECT username FROM users WHERE username = :username');
            $query->execute( ['username' => $username]);
            
            if($query->rowCount() > 0){
                return true;
            }else{
                return false;
            }
        }catch(PDOException $e){
            error_log($e->getMessage());
            return false;
        }
    }

    public static function get($username){
        try{
            $db = new Database();
            $query = $db->connect()->prepare('SELECT * FROM users WHERE username = :username');
            $query->execute([ 'username' => $username]);
            //en esta variable de data guardas la informacion de la fila q estas haciendo consulta
            $data = $query->fetch(PDO::FETCH_ASSOC);
            error_log($data['username']);
            error_log($data['password']);
            $user = new User($data['username'], $data['password']);
            $user->setId($data['user_id']);
            $user->setProfile($data['profile']);
            return $user;
        }catch(PDOException $e){
            error_log($e->getMessage());
            return null;
        }
    }

    public static function getById(int $user_id): User{
        try{
            $db = new Database();
            $query = $db->connect()->prepare('SELECT * FROM users WHERE user_id = :user_id');
            $query->execute([ 'user_id' => $user_id]);
            //en esta variable de data guardas la informacion de la fila q estas haciendo consulta
            $data = $query->fetch(PDO::FETCH_ASSOC);
            error_log($data['username']);
            error_log($data['password']);
            $user = new User($data['username'], $data['password']);
            $user->setId($data['user_id']);
            $user->setProfile($data['profile']);
            return $user;
        }catch(PDOException $e){
            error_log($e->getMessage());
            return null;
        }
    }

    private function getHashedPassword($password){
        return password_hash($password,PASSWORD_DEFAULT, ["cost"=>10]);
    }

    public function comparePassword(string $current):bool{
        try{
            return password_verify($current, $this->password);
        }catch(PDOException $e){
            return NULL;
        }
    }

    public function publish(PostImage $post){
      try{
            $query = $this->prepare("INSERT INTO posts(user_id,title,media) values(:user_id,:title,:media)");
            $query->execute([
                "user_id" => $this->id,
                "title" => $post->getTitle(),
                "media" => $post->getImage()
            ]);

            return true;
      }catch(PDOException $e){
        return false;
      }
    }

    public function fetchPosts(){
        $this->posts = PostImage::getAll($this->id);
    }



    public function getId():string{
        return $this->id;
    }

    public function setId(string $value){
        $this->id = $value;
    }

    public function getUsername(){
        return $this->username;
    }

    public function setUsername(string $value){
        $this->username = $value;
    }

    public function getPosts(){
        return $this->posts;
    }

    public function setPosts($value){
        $this->posts = $value;
    }

    public function setProfile($value){
        $this->profile = $value;
    }

    public function getProfile(){
        return $this->profile;
    }

}