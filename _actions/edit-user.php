<?php

require '../_classes/Database/UserTable.php';
require '../_classes/HTTP.php';

use _classes\HTTP;
use _classes\Database\MySQL;
use _classes\Database\UserTable;

$table = new UserTable(new MySQL());

if($table){
    $alreadyEmail = $table->checkEmail($_POST['email']);

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
