

<?php 

class Auth extends DB{
    public static function IndexAuth(){
        if(isset($_COOKIE['login'])){
            header('Location: /dashboard/');
            exit();
        }
    }
    public static function strictPage(){
        if(!isset($_COOKIE['login'])){
            header('Location: /index/');
            exit();
        }
    }

    public static function ValidateUser($user,$pass){
        $query = DB::query("select * from agents where aid='$user' and password='$pass'");
        if(!empty($query)){
            setcookie("login",$user,time()+86400,"/");
            Widget::alertRed("Dashboard",'Successfully Logged In',"/dashboard/");
        }else{
            Widget::alertGreen("Invalid Username or password");
        }
    }

    public static function logout(){
        setcookie('login','', time()-0,'/');
        header('Location: /index/');
        exit();
    }

    
    public static function checkStore(){
        $see = DB::query("select * from stores where status='1'");
        if(empty($see)){
            $pro = "False";
        }else{
            $pro = "True";
        }
        return $pro;
    }

    public static function getStore(){
        $see = DB::query("select * from stores where status='1'");
        return $see[0];
    }
}


?>