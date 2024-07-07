<?php
namespace _classes;

class Auth{

    static function check(){
        if(isset($_SESSION['user'])){
            return $_SESSION['user'];
        }else{
            echo "
                <script>
                    alert('You need to login to continue.');
                    window.location.href = 'login.php';
                </script>
            ";
        }
    }
}

