<?php
session_start();

require '../_classes/Database/CommentTable.php';
require '../_classes/HTTP.php';
require '../_classes/common.php';

use _classes\Database\MySQL;
use _classes\Database\CommentTable;
use _classes\HTTP;

$table = new CommentTable(new MySQL());

if($table){
    $data = [
        'post_id' => $_POST['blogId'],
        'content' => $_POST['comment'],
        'author_id' => $_POST['authorId']
    ];

    $blogId = $data['post_id'];

    $table->setComment($data);
    HTTP::redirect("blog-detail.php?blog=$blogId");
}



