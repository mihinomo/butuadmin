<?php

class Index extends Controller{

    public static function MakeIndex($name){
        //require_once("./app/views/common/head.php");//header file
        require_once('./app/view/'.$name.'.php');
    }

}

?>