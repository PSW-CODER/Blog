<?php
session_start();
require '../_classes/Database/PostTable.php';
require '../_classes/HTTP.php';
require '../_classes/common.php';

use _classes\HTTP;
use _classes\Database\MySQL;
use _classes\Database\PostTable;

$table = new PostTable(new MySQL());

if($table){
    if(isset($_POST['submit'])){

        if(!empty($_POST['title']) && !empty($_POST['content']) && !empty($_FILES['image']['name'])){
            $targetFile = '../images/'.$_FILES['image']['name'];
            $imageType = pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION);
    
            if($imageType != "png" && $imageType != "jpg" && $imageType != "jpeg"){
                echo "
                    <script>
                        alert('Image type must be png or jpg, jpeg');
                        window.location.href = '../admin/create-post.php';
                    </script>
                ";
            }else{
                $data = [
                    'title' => $_POST['title'],
                    'content' => $_POST['content'],
                    'image' => $_FILES['image']['name'],
                    'author' => $_SESSION['user']->id,
                ];
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
    
                $table->set($data);
                HTTP::redirect("admin/index.php");
            }
        }else{
            echo"
            <script>
                alert('Please enter title and content and image');
                window.location.href = '../admin/create-post.php';
            </script>
            ";             
        }
    }
}



