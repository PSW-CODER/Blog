<?php

require '../_classes/Database/UserTable.php';
require '../_classes/HTTP.php';

use _classes\HTTP;
use _classes\Database\MySQL;
use _classes\Database\UserTable;

$table = new UserTable(new MySQL());

if($table){
    if(isset($_GET['user-id'])){
        $table->delete($_GET['user-id']);
        HTTP::redirect('admin/user-list.php');
    }
}




