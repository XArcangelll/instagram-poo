<?php

namespace Diego\Ig\models;
use Diego\Ig\lib\Model;
use Diego\Ig\lib\Database;
use PDO;
use PDOException;

class Post extends Model{

    private string $id;
    private array $likes;
    private User $user;

    protected function __construct(private string $title)
    {
        parent::__construct();
        $this->likes = [];
    }

    public function getId():string{
        return $this->id;
    }
    public function getUser():User{
        return $this->user;
    }

    public function setId(string $id){
        $this->id = $id;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getLikes(){
        return count($this->likes);
    }

    public function setLikes($value){
        $this->likes = $value;
    }


    protected function fetchLikes(){
        $items = [];

        try{
            
            $query = $this->prepare('SELECT * FROM likes WHERE post_id = :post_id');
            $query->execute(['post_id' => $this->id]);

            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new Like($p['post_id'],$p["user_id"]);
                $item->setId($p['id']);

                array_push($items, $item);
            }

            $this->likes = $items;
        }catch(PDOException $e){
            echo $e;
        }
    }

    public function CuentaLike($user_id){
        $items = [];
        try{
        $query = $this->prepare('SELECT * FROM likes WHERE post_id = :post_id and user_id = :user_id');
        $query->execute([
            'post_id'  => $this->id, 
            'user_id'  => $user_id
            ]);
        
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                
                if(count($p) == 0){
                    return false;
                }else{
                    return true;
                }
            }

    }catch(PDOException $e){
        return false;
    }
    }

    public function setUser(User $user){
        $this->user = $user;
    }

    public function addLike(User $user){
        if($this->checkIfUserLiked($user->getId())){
            $this->removeLike($user);
        }else{         
            $like = new Like( intval($this->id) , intval($user->getId()) );
            $like->save($user->getId());
            array_push($this->likes, $like); 
        }
     }

     protected function checkIfUserLiked(int $user_id){
      return $this->CuentaLike($user_id);
    }

    public function removeLike(User $user){
        $like = new Like( $this->id, $user->getId());
        $like->remove($user->getId());
    }

   

}   