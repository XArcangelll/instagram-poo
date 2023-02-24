<?php

namespace Diego\Ig\controllers;

use Diego\Ig\lib\Controller;
use Diego\Ig\models\User;
use Diego\Ig\models\PostImage;

class Actions extends Controller{

    function __construct(private User $user)
    {
        parent::__construct();
    }

    public function like(){
        $post_id = $this->post('post_id');
        $origin = $this->post("origin");

        if(!is_null($post_id) && !is_null($origin)){
            error_log('like-> no es nulo');
            $post = PostImage::get($post_id);
            var_dump($post);
            $post->addLike($this->user);

            header("location: /instagram/" .$origin);
        }
    }

}