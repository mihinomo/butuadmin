<?php

class Store extends Controller{
    public static function getStore(){
        $q = DB::query("select * from stores where status='1'");
        if(empty($q)){
            return "";
        }else{
            return $q[0];
        }
        
    }
    public static function addStock($pid,$unit){
        $go = "success";
        $query = DB::query("select * from products where id='$pid'");
        $row = $query[0];
        $cs = $row['stock'];
        $ns = $cs+$unit;
        $qq = DB::query("update products set stock='$ns' where id='$pid'");
        if($qq){
            $go = "success";
        }else{
            $go = "fail";
        }
        return $go;
    
    }

    public static function deductStock($pid,$unit){
        $go = "success";
        $query = DB::query("select * from products where id='$pid'");
        $row = $query[0];
        $cs = $row['stock'];
        $ns = $cs-$unit;
        $qq = DB::query("update products set stock='$ns' where id='$pid'");
        if($qq){
            $go = "success";
        }else{
            $go = "fail";
        }
        return $go;
    
    }

    public static function returnSale(){
        $id = $_POST['pid'];
        $qur = DB::query("select * from insale_items where id='$id'");
        $row = $qur[0];
    
        $pid = $row['pid'];
        $qty = $row['qty'];
        if(self::addStock($pid,$qty)=='success'){
            $quu = DB::insert("delete from insale_items where id='$id'");
            if($quu){
                $response['message'] = "Successfully Added To Return Sales";
            }else{
                $response['message'] = "There was an error";
            }
        }else{
            $response['message'] = "There was an error";
        }
        
        return $response;
    }

    public static function getMode($a){
        if($a=='r'){
            echo "Restaurant";
        }elseif($a=='re'){
            echo "Enterprise";
        }elseif($a=='q'){
            echo "Mining";
        }elseif($a=='mn'){
            echo "Manufacturer";
        }else{
            echo "";
        }
    }

}

?>