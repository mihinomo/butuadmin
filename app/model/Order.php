<?php 

class Order{
    
    public static function active(){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/activeorders.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    public static function review(){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/revieworders.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    public static function confirm(){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/confirmorders.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    

    

}