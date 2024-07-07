<?php

namespace _classes\Database;

require 'MySQL.php';

use PDOException;

class PostTable{

    private $db = null;

    public function __construct(MySQL $db){
        $this->db = $db->connect();
    }

    public function set($data){
        $query = "INSERT INTO posts(title, content, image, author_id) VALUES (:title, :content, :image, :author)";
        $statemnt = $this->db->prepare($query);
        $statemnt->execute([':title' => $data['title'], ':content' => $data['content'], ':image' => $data['image'], ':author' => $data['author']]);
        return $statemnt->rowCount();
    }

    public function get($id){
        $query = "SELECT * FROM posts WHERE id = :id";
        $statemnt = $this->db->prepare($query);
        $statemnt->execute([':id' => $id]);
        return $statemnt->fetch();
    }

    public function getAll(){
        $statemnt = $this->db->prepare("SELECT posts.*, users.name AS author FROM posts
                                        LEFT JOIN users ON posts.author_id = users.id");
        $statemnt->execute();
        return $statemnt->fetchAll();
    }

    public function update($data){
        $statemnt = $this->db->prepare("UPDATE posts SET title = :title, content = :content, updated_at = NOW() WHERE id = :id");
        $statemnt->execute([
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':id' => $data['id'],
        ]);
        return $statemnt->rowCount();
    }

    public function updateWithImage($data){
        $statemnt = $this->db->prepare("UPDATE posts SET title = :title, content = :content, image = :image, updated_at = NOW() WHERE id = :id");
        $statemnt->execute([
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':image' => $data['image'],
            ':id' => $data['id'],
        ]);
        return $statemnt->rowCount();
    }

    public function delete($id){
        $statement = $this->db->prepare("DELETE FROM posts WHERE id = :id");
        $statement->execute([':id' => $id]);
        return $statement->rowCount();
    }

    public function pagination($start = 0, $rowsPerPage){
        $start = $start;    
        $rowsPerPage = $rowsPerPage;
        $query = "SELECT posts.*, users.name AS author FROM posts LEFT JOIN users ON posts.author_id = users.id ORDER BY posts.id DESC LIMIT $start, $rowsPerPage";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getSearch($searchKey, $start, $rowsPerPage){
        $statement = $this->db->prepare("SELECT posts.*, users.name AS author FROM posts LEFT JOIN users ON posts.author_id = users.id WHERE posts.title LIKE '%$searchKey%' ORDER BY posts.id DESC LIMIT $start, $rowsPerPage");;
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getSearchPost($searchKey){
        $statemnt = $this->db->prepare("SELECT * FROM posts
                                        WHERE title LIKE '%$searchKey%'");
        $statemnt->execute();
        return $statemnt->fetchAll();
    }

    public function getComment($id){
        $statement = $this->db->prepare("SELECT comments.*, users.name AS author, users.image AS photo FROM comments 
                                         LEFT JOIN users ON comments.author_id = users.id 
                                         WHERE post_id = :id");
        $statement->execute([':id' => $id]);
        return $statement->fetchAll();
    }
}

