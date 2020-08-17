<?php

namespace Post\Model;

include 'ResourceModel/Post.php';
class Category
{
    public function get($id)
    {
        $resourceModel = new \Post\Model\ResourceModel\Category();
        return $resourceModel->load($id);
    }

    public function getList($where = '', $limit = 0, $page = 1, $order = 'ASC')
    {
        $resourceModel = new \Post\Model\ResourceModel\Category();
        return $resourceModel->getCollection($where, $limit, $page, $order);
    }

    public function save($data)
    {
        $resourceModel = new \Post\Model\ResourceModel\Category();
        $resourceModel->save($data);
    }

    public function delete($id)
    {
        $resourceModel = new \Post\Model\ResourceModel\Category();
        $resourceModel->delete($id);
    }
}
