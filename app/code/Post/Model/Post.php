<?php

namespace Post\Model;

include 'ResourceModel/Post.php';
class Post
{
    public function get($id)
    {
        $resourceModel = new \Post\Model\ResourceModel\Post();
        return $resourceModel->load($id);
    }

    public function getList($where = '', $limit = 0, $page = 1, $order = 'ASC')
    {
        $resourceModel = new \Post\Model\ResourceModel\Post();
        return $resourceModel->getCollection($where, $limit, $page, $order);
    }

    public function save($data)
    {
        $resourceModel = new \Post\Model\ResourceModel\Post();
        $resourceModel->save($data);
    }

    public function delete($id)
    {
        $resourceModel = new \Post\Model\ResourceModel\Post();
        $resourceModel->delete($id);
    }
}
