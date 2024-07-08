<?php

namespace _classes\Database;

require 'MySQL.php';

use PDOException;
use _classes\Database\MySQL;

class UserTable{
    private $db = null;
    
    // connect to the database
    public function __construct(MySQL $db){
        $this->db = $db->connect();
    }

    // insert record into database
    public function set($data){
        $query = "INSERT INTO users(name, email, password, image)
                  VALUES (:name, :email, :password, '5907.jpg')";

        $statement = $this->db->prepare($query);
        $statement->execute([':name' => $data['name'],':email' => $data['email'], ':password' => $data['password']]);

        return $this->db->lastInsertId();
    }

    public function addUser($data){
        $query = "INSERT INTO users(name, email, password, image, role_id)
                  VALUES (:name, :email, :password, '5907.jpg', :role)";

        $statement = $this->db->prepare($query);
        $statement->execute([':name' => $data['name'],':email' => $data['email'], ':password' => $data['password'], ':role' => $data['role']]);

        return $this->db->lastInsertId();   
    }

    // email and user validation
    public function checkEmail($email){
        $query = "SELECT * FROM users WHERE email = :email";
        $statement = $this->db->prepare($query);
        $statement->execute([':email' => $email]);
        return $statement->rowCount();
    }

    public function updateEmail($email, $id){
        $query = "SELECT * FROM users WHERE email = :email AND id != :id";
        $statement = $this->db->prepare($query);
        $statement->execute([':email' => $email, ':id' => $id]);
        return $statement->rowCount();
    }

    // select one record from the database
    public function get($id){
        $query = "SELECT * FROM users WHERE id = :id";
        $statemnt = $this->db->prepare($query);
        $statemnt->execute([':id' => $id]);
        return $statemnt->fetch();
    }

    // select all records from the database
    public function getAll(){
        $statemnt = $this->db->prepare("SELECT * FROM users");
        $statemnt->execute();
        return $statemnt->fetchAll();
    }

    // update record from the database
    public function update($data){
        $statemnt = $this->db->prepare("UPDATE users SET name = :name, email = :email, role_id = :role WHERE id = :id");
        $statemnt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':role' => $data['role'],
            ':id' => $data['id'],
        ]);
        return $statemnt->rowCount();
    }

    // delete record
    public function delete($id){
        $statement = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $statement->execute([':id' => $id]);
        return $statement->rowCount();
    }

    // search
    public function getSearch($searchKey, $start, $rowsPerPage){
        $statement = $this->db->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $start, $rowsPerPage");;
        $statement->execute();
        return $statement->fetchAll();
    }

    // search post
    public function getSearchPost($searchKey){
        $statemnt = $this->db->prepare("SELECT * FROM users
                                        WHERE name LIKE '%$searchKey%'");
        $statemnt->execute();
        return $statemnt->fetchAll();
    }

    // pagination
    public function pagination($start = 0, $rowsPerPage){
        $start = $start;    
        $rowsPerPage = $rowsPerPage;
        $query = "SELECT * FROM users ORDER BY users.id DESC LIMIT $start, $rowsPerPage";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    // check the email and password
    public function login($email, $password){
        $statement = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $statement->execute([':email' => $email]);
        $result = $statement->fetch();
        
        if($result){
            if($result->password === $password){
                return $result;
            }else{
                return $result = false;
            }
        }else{
            return $result;
        }
    }
}