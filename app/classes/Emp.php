<?php 

class Emp extends DB{

    public static function getAgent($aid){
        $agent = DB::query("select * from agents where aid='$aid'");
        return $agent[0];
    }
    public static function agentName($aid){
      $params = ['aid' => $aid];
      $agent = DB::query("SELECT name FROM agents WHERE aid = :aid", $params);
      return empty($agent) ? "--" : $agent[0]['name'];
      
    }
    

    public static function genValidRoutes($person){
      $roles = array();
      if($person['type']=='AA'){
        $roles = ["lot"=>"1","orders"=>"1","updates"=>"1","frontend"=>"1","admin"=>"1","accounting"=>"1","hrm"=>"1", "gallery"=>"1"];
      }else{
        $roles = json_decode($person['roles'],true);
      }
      return $roles;
    }

  public static function makeAgentLista(){
      $q = DB::query("select * from agents where status='1'");
      foreach($q as $row){
          echo "<option value='".$row['aid']."'>".$row['name']."</option>";
      }
  }
  public static function makeAgentListp(){
    $q = DB::query("select * from agents where status='1'");
    foreach($q as $row){
        echo "<option value='".$row['phone']."'>".$row['name']."</option>";
    }
}

      
}
