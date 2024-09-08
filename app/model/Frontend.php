<?php 

class Frontend extends Controller{
    
    public static function index(){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/frontend.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    public static function updateWebContent($field, $val) {
        $value = htmlspecialchars($val);
        $sql = "UPDATE webcontent SET $field = :value WHERE id = '1'";
        $params = [':value' => $value];
        $result = DB::executeQuery($sql, $params);
    
        if ($result > 0) {
            Widget::jsAlert('Successfully Updated ' . htmlspecialchars($field), 'webcontent.php');
        } else {
            Widget::justalert("Database Error");
        }
    }

    public static function fetchWebContent() {
        $sql = "SELECT * FROM webcontent WHERE id = '1'";
        $result = DB::executeQuery($sql, [], true); // Assuming true fetches a single row
    
        if ($result) {
            return $result;
        } else {
            Widget::justalert("Failed to retrieve web content");
            return false; // Handle the error as needed
        }
    }

    

}