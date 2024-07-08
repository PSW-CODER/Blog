<?php
session_start();

require '../_classes/Database/UserTable.php';
require '../_classes/HTTP.php';
require '../_classes/common.php';

use _classes\Database\MySQL;
use _classes\Database\UserTable;
use _classes\HTTP;

$table = new UserTable(new MySQL());

if($table){
    if(isset($_POST['submit'])){
        
        // backend validation
        if(!empty($_POST['email']) && !empty($_POST['password'])){            
            $email = $_POST['email'];
            $password = md5($_POST['password']);
            $user = $table->login($email, $password);
            
            if($user){
                $_SESSION['user'] = $user;
    
                if($_SESSION['user']->role_id === 0){
                    HTTP::redirect("index.php");
                }
    
                if($_SESSION['user']->role_id === 1){
                    HTTP::redirect("admin/index.php");
                }else{
                    echo"
                        <script>
                            alert('You cannot access.');
                            window.location.href = '../login.php';
                        </script>
                    ";
                }
            }else{
                HTTP::redirect("login.php", "login=false");
            }
        }else{
            echo"
                <script>
                    alert('Please enter email and password');
                    window.location.href = '../login.php';
                </script>
            ";
        }
    }
}
