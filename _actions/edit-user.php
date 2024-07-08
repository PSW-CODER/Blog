<?php
session_start();
require '../_classes/Database/UserTable.php';
require '../_classes/HTTP.php';
require '../_classes/common.php';

use _classes\HTTP;
use _classes\Database\MySQL;
use _classes\Database\UserTable;

$table = new UserTable(new MySQL());

if($table){
    $alreadyEmail = $table->updateEmail($_POST['email'], $_POST['userId']);

    if($alreadyEmail){
        echo"
            <script>
                alert('Email already exist.');
                window.location.href = '../admin/user-list.php';
            </script>
        ";
    }else{
        $data = [
            'id' => $_POST['userId'],
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'role' => $_POST['role']
        ];
    
        $table->update($data);
        HTTP::redirect("admin/user-list.php");
    }
}
