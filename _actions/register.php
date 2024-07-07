<?php

require '../_classes/Database/UserTable.php';
require '../_classes/HTTP.php';

use _classes\Database\MySQL;
use _classes\Database\UserTable;
use _classes\HTTP;

$table = new UserTable(new MySQL());

if($table){
    if(isset($_POST['submit'])){

        // backend validation
        if(!empty($_POST['name']) &&
           !empty($_POST['email']) &&
           !empty($_POST['password'])            
           ){
            $alreadyEmail = $table->checkEmail($_POST['email']);

            if($alreadyEmail){
                echo"
                    <script>
                        alert('Your email already exist.');
                        window.location.href = '../register.php';
                    </script>
                ";
            }else{
                $data = [
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'password' => md5($_POST['password']),
                ];
        
                $table->set($data);
                HTTP::redirect('login.php', 'register=success');   
            }   
        }else{
            echo"
                <script>
                    alert('Please enter email and password and name');
                    window.location.href = '../register.php';
                </script>
            ";                      
        }      
    }
}


