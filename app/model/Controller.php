<?php 

class Controller extends DB {
    

    public static function CreateView($name){
        require_once('./app/view/'.$name.'.php');
        //Index::test();
    }
    

    
}


?>