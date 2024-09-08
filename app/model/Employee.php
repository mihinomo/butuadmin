<?php 

class Employee extends Controller{

    public static function MakeIndex(){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/agents.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    public static function Eform($name){
        $var = 0;
        $backbtn = "/employee/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/headnav.php");//header file
        require_once('./app/view/editagent.php');
    }

    public static function editForm($rid){
        $var = 0;
        $backbtn = "/agents/";
        $row = Employee::agentArray($rid);
        require_once("./app/view/common/headernav.php");//header file
        require_once('./app/view/editagent.php');
    }

    
    public static function agentArray($id){
        $agent = DB::query("select * from agents where id='$id'");
        return $agent[0];
    }

    public static function ShowAgents(){
        $agent = DB::query("select * from agents");
        $loop = $agent;
        foreach($loop as $l){
            if($l['type'] != 'AA'){
                
                
                echo "<tr>
                    <td>".$l['name']."</td>
                    <td>".$l['phone']."</td>
                    <td>".$l['joined']."</td>
                    <td>".$l['aid']."</td>
                    <td>".($l['status'] == '0' ?
                    "<a href='?enable=".$l['phone']."' onclick='return confir();' class='mr-3 text-danger'><i class='fas fa-play'></i></a>" :
                    "<a href='?disable=".$l['phone']."' onclick='return confir();' class='mr-3'><i class='fas fa-stop'></i></a>").
                    "<a href='/editagent/".$l['id']."/' class='mr-3'><i class='fas fa-edit'></i></a>
                    <a onclick='deleteagent(".$l['id'].");'><i class='fas fa-trash'></i></a></td>
                </tr>";
            }
        }
    }
    

    public static function gencid($n){

        // Take a generator string which consist of
        // all numeric digits
        $generator = "1934052678";
        $result = "";
    
        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand()%(strlen($generator))), 1);
        }
    
        // Return result
        return $result;
    }

    public static function genPermissions($per) {
        $permissions = json_decode($per, true);
        $permissionLabels = [
            'lot' => 'Lottery',
            'orders' => 'Orders',
            'updates' => 'Updates',
            'frontend' => 'Front-End',
            'admin' => 'Admin',
            'gallery' => 'Gallery',
            'accounting' => 'Accounting',
            'hrm' => 'HRM'
        ];
    
        echo "<table>";
        foreach ($permissionLabels as $key => $label) {
            $checked = isset($permissions[$key]) && $permissions[$key] == '1' ? "checked='checked'" : "";
            echo "<tr>
                    <td style='width:100px'>" . htmlspecialchars($label) . "</td>
                    <td><label>
                        <input id='onoff_{$key}' name='{$key}' type='checkbox' {$checked}>
                        </label>
                    </td>
                </tr>";
        }
        echo "</table>";
    }
    
    public static function pagePermissions($per){
        $per = json_decode($per,true);
        //var_dump($per);
        echo "<table>";
        if($per['pos']=='1'){
            echo "<tr>
                    <td style='width:100px'>POS</td>
                    <td><label>
                        <input id='onoff' name='posp' type='checkbox' checked='checked'>
                        </label>
                    </td>
                </tr>";
        }else{
            echo "<tr>
                    <td style='width:100px'>POS</td>
                    <td><label>
                        <input id='onoff' name='posp' type='checkbox'>
                        </label>
                    </td>
                </tr>";
        }
        if($per['man']=='1'){
            echo "<tr>
                    <td style='width:100px'>Manufacture</td>
                    <td><label>
                        <input id='onoff' name='man' type='checkbox' checked='checked'>
                        </label>
                    </td>
                </tr>";
        }else{
            echo "<tr>
                    <td style='width:100px'>Manufacture</td>
                    <td><label>
                        <input id='onoff' name='man' type='checkbox'>
                        </label>
                    </td>
                </tr>";
        }
        if($per['journal']=='1'){
            echo "<tr>
                    <td style='width:100px'>Journal</td>
                    <td><label>
                        <input id='onoff' name='journal' type='checkbox' checked='checked'>
                        </label>
                    </td>
                </tr>";
        }else{
            echo "<tr>
                    <td style='width:100px'>Journal</td>
                    <td><label>
                        <input id='onoff' name='journal' type='checkbox'>
                        </label>
                    </td>
                </tr>";
        }
        if($per['purchase']=='1'){
            echo "<tr>
                    <td style='width:100px'>Purchases</td>
                    <td><label>
                        <input id='onoff' name='purchase' type='checkbox' checked='checked'>
                        </label>
                    </td>
                </tr>";
        }else{
            echo "<tr>
                    <td style='width:100px'>Purchases</td>
                    <td><label>
                        <input id='onoff' name='purchase' type='checkbox'>
                        </label>
                    </td>
                </tr>";
        }
        echo "</table>";
    }

    public static function rolesArray() {
        // Define all possible permissions
        $allPermissions = ['orders', 'gallery', 'updates', 'hrm', 'lot', 'frontend', 'accounting','admin'];
        
        $permissions = array();
        foreach ($allPermissions as $permission) {
            $permissions[$permission] = isset($_POST[$permission]) ? "1" : "0";
        }
    
        return $permissions;
    }
    
    
    
    public static function accessArray(){
        $page = array();
        if(isset($_POST['man'])){
            $page['man'] = "1";
        }else{
            $page['man'] = "0";
        }
        if(isset($_POST['posp'])){
            $page['pos'] = "1";
        }else{
            $page['pos'] = "0";
        }
        if(isset($_POST['ledger'])){
            $page['pos'] = "1";
        }else{
            $page['pos'] = "0";
        }
        if(isset($_POST['purchase'])){
            $page['purchase'] = "1";
        }else{
            $page['purchase'] = "0";
        }
        return $page;
    }

}



