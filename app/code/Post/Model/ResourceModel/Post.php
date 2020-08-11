<?php

namespace Post\Model\ResourceModel;

include  __DIR__ . '/../../../Framework/Connection.php';


class Post
{
	protected $entityId = 'post_id';

	public function load($id)
	{
		$connection = new \Framework\Connection();
		$conn = $connection->getConnection();
		$sql = "SELECT * FROM post WHERE " . $this->entityId . " = $id";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			$row = $result->fetch_object();
		}
		return $row;
	}

    public function getCollection($where = '', $limit = 0, $page = 1, $order = 'ASC')
    {
        $data = [];
        $connection = new \Framework\Connection();
        $conn = $connection->getConnection();
        $sql = "SELECT * FROM post";
        if ($where) {
            $sql .= " $where";
        }
        $sql .= " ORDER BY post_id " . $order;
        if ($limit) {
            $sql .= " LIMIT " . ($page -1) * $limit . ", $limit";
        }

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function save ($data) {
        $connection = new \Framework\Connection();
        $conn = $connection->getConnection();
        $data= $this->prepareData($data);
        $sql = "INSERT INTO post (post_id, title, short_description, content, identifier, image)
            VALUES (
            '". $data['post_id'] ."',
            '". $data['title'] . "',
            '". $data['short_description'] . "',
            '". $data['content'] . "',
            '". $data['identifier'] . "',
            '". $data['image'] . "'
            )
            ON DUPLICATE KEY UPDATE
             post_id = '". $data['post_id'] ."',
             title = '". $data['title'] ."',
             content = '". $data['content'] ."',
             short_description = '". $data['short_description'] ."',
             identifier = '". $data['identifier'] ."',
             image = '". $data['image'] ."'";
        return $conn->query($sql);
    }

    public function delete($id)
    {
        $connection = new \Framework\Connection();
        $conn = $connection->getConnection();
        $sql = "DELETE FROM post WHERE post_id = ".$id;
        return $conn->query($sql);
    }
    
    private function prepareData($data)
    {
        if (!isset($data['post_id'])) {
            $data['post_id'] = '';
        }
        if (!isset($data['title'])) {
            $data['title'] = '';
        }
        if (!isset($data['short_description'])) {
            $data['short_description'] = '';
        }
        if (!isset($data['content'])) {
            $data['content'] = '';
        }
        if (!isset($data['identifier'])) {
            $data['identifier'] = '';
        }
        if (!isset($data['image'])) {
            $data['image'] = '';
        }
        if (!isset($data['content'])) {
            $data['content'] = '';
        }
	    return $data;
    }
}
