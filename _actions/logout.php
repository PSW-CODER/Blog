<?php

session_start();

require '../_classes/Auth.php';

use _classes\Auth;

$user = Auth::check();

if($user->role_id === 1){
    session_destroy();
    header("location: ../admin/login.php");
}else{
    session_destroy();
    header("location: ../login.php");
}

