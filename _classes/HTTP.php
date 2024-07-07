<?php

namespace _classes;

class HTTP{
    static $base = "http://localhost/PSW/Self-Study/Intermediate/Assignment/Week-3/Blog_Beta/";
    
    static function redirect($path, $query=""){
        $url = static::$base . $path;
        $query ? $url .= "?$query" : $url ;
        header("location: $url");
        exit(); 
    }
}