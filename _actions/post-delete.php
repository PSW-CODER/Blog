<?php

require '../_classes/Database/PostTable.php';
require '../_classes/HTTP.php';

use _classes\HTTP;
use _classes\Database\MySQL;
use _classes\Database\PostTable;

$table = new PostTable(new MySQL());

if($table){
    if(isset($_GET['post-id'])){
        $table->delete($_GET['post-id']);
        HTTP::redirect('admin/index.php');
    }
}




