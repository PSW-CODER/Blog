<?php

require '../_classes/Database/UserTable.php';
require '../_classes/HTTP.php';

use _classes\HTTP;
use _classes\Database\MySQL;
use _classes\Database\UserTable;

$table = new UserTable(new MySQL());

if($table){
    if(isset($_POST['submit'])){        
        $alreadyEmail = $table->checkEmail($_POST['email']);

        if($alreadyEmail){
            echo"
                <script>
                    alert('Email already exist.');
                    window.location.href = '../admin/add-user.php';
                </script>
            ";
        }else{
            if(!empty($_POST['name']) &&
            !empty($_POST['emial']) &&
            !empty($_POST['password']) &&
            !empty($_POST['role'])
            ){
                $data = [
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'password' => md5($_POST['password']),
                    'role' => $_POST['role']
                ];
            
                $table->addUser($data);
                HTTP::redirect('admin/user-list.php');
            }else{
                echo"
                <script>
                    alert('Please enter email and password and name and role');
                    window.location.href = '../admin/add-user.php';
                </script>
                ";               
            }                          
        }        
    }
}



