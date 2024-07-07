<?php

namespace _classes\Database;

require 'MySQL.php';

use _classes\Database\MySQL;

class CommentTable{

    private $db = null;

    public function __construct(MySQL $db){
        $this->db = $db->connect();
    }

    public function setComment($data){
        $query = "INSERT INTO comments(content, author_id, post_id)
                  VALUES (:content, :author_id, :post_id)";
        $statement = $this->db->prepare($query);
        $statement->execute([
            ':content' => $data['content'],
            ':author_id' => $data['author_id'],
            ':post_id' => $data['post_id']
        ]);

        return $statement->rowCount();
    }

    public function getAll(){

    }
}