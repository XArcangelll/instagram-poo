<?php

namespace Diego\Ig\controllers;

use Diego\Ig\lib\Controller;
use Diego\Ig\lib\UtilImages;
use Diego\Ig\models\User;
use Diego\Ig\models\PostImage;

class Home extends Controller{

        public function __construct(private User $user)
        {
            parent::__construct();
        }

        public function index(){
            $posts = PostImage::getFeed();
            $this->render('home/index', ['user' => $this->user, 'posts' => $posts]);
        }
    
        public function store(){
    
            $title = $this->post('title');
            $image = $this->file('image');
    
            if(!is_null($title) && !is_null($image)){
               $filename = UtilImages::storeImage($image);

               $post = new PostImage($title,$filename);
               $this->user->publish($post);
               header("location: /instagram/home");
            }else{
                header("location: /instagram/home");
            }
        }

}    