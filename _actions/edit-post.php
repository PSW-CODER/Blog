<?php

require '../_classes/Database/PostTable.php';
require '../_classes/HTTP.php';

use _classes\HTTP;
use _classes\Database\MySQL;
use _classes\Database\PostTable;

$table = new PostTable(new MySQL());

if($table){

    $data = [
        'id' => $_POST['postId'],
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'image' => $_FILES['image']['name'],
    ];

    $id = $data['id'];

    if(!empty($data['image'])){
        $targetFile = '../images/'.$_FILES['image']['name'];
        $imageType = pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION);

        if($imageType != "png" && $imageType != "jpg" && $imageType != "jpeg"){
            echo "
                <script>
                    alert('Image type must be png or jpg, jpeg');
                    window.location.href = '../admin/edit-post.php?post-id=$id';
                </script>
            ";
        }else{
            move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
            $table->updateWithImage($data);
            HTTP::redirect("admin/index.php");
        }
    }else{
        $table->update($data);
        HTTP::redirect("admin/index.php");
    }
}
