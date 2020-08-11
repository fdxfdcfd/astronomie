<?php

namespace app\code\Post\Block;

include 'app/code/Post/Model/Post.php';

class Index
{
    public function getPostData()
    {
        $post = new \Post\Model\Post();
        return $post->get(1);
    }
}