<?php 

class Result extends Controller{
    
    public static function index(){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/results.php');
        require_once("./app/view/common/footer.php");//footer file
    }
    public static function showresult($lid){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/viewresult.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    public static function announce($lid){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/announcement.php');
        require_once("./app/view/common/footer.php");//footer file
    }
    public static function feedback(){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/feedback.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    

}