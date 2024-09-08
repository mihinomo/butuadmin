<?php 

class Lottery extends Controller{
    public static function getLot($lid){
        $ar =array();
        $query = DB::query("select * from lotteries where lid='$lid'");
        if(empty($query)){
            $ar['name'] = "Deleted Lottery";
            return $ar;
        }else{
            return $query[0];
        }
        
    }
    public static function getBook($id){
        $query = DB::query("select * from books where id='$id'");
        return $query[0];
    }

    public static function MakeIndex(){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/lotteries.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    public static function NewIndex(){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/newlot.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    public static function ViewSales($lid){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/viewsales.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    public static function ViewBooks($lid){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/viewbooks.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    public static function addBook($lid){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/addbook.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    public static function declare($lid){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/declare.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    public static function sequence(){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        $explode = explode("/",$_SERVER['REQUEST_URI']);
        $lid = $explode[2];
        $bid = $explode[3];
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/showsequence.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    public static function MakeBookings(){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $backbtn ='/dashboard/';
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/headernav.php");//header file
        require_once('./app/view/bookings.php');
        require_once("./app/view/common/footer.php");//footer file
    }


    public static function MakeBooks(){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/showbook.php');
        require_once("./app/view/common/footer.php");//footer file
    }


    public static function newOrder(){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/neworder.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    public static function MakeBuy(){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/buy.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    public static function genSales(){
        $var = 0;
        $backbtn = "/dashboard/";
        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);
        require_once("./app/view/common/header.php");//header file
        require_once('./app/view/gensales.php');
        require_once("./app/view/common/footer.php");//footer file
    }

    public static function show_lotteriesx(){
        $stmt = DB::query("select * from lotteries where status='1'");
        foreach($stmt as $query){
            echo '<tr>
                    <th scope="row"><a href="#"><img src="/resources/'.$query['image'].'" alt="" style="height:50px;"></a></th>
                    <td><a href="#" class="text-primary fw-bold">'.ucfirst($query['name']).'</a></td>
                    <td class="fw-bold">₹'.$query['price'].'</td>
                    <td class="fw-bold">₹'.$query['refers'].'</td>
                    <td>'.self::showSold($query['lid']).'</td>
                    <td><a href="sales.php?lid='.$query['lid'].'">View Sales</a></td>
                    <td class="fw-bold">'.$query['venue'].'</td>
                    <td><a onclick="del('.$query['id'].');" style="margin-right:1rem;"><i class="fa fa-trash"></i></a><a href="/showbook/?lid='.$query['lid'].'" style="margin-right:1rem;"><i class="fa fa-eye"></i></a><a href="editlottery.php?lid='.$query['lid'].'"><i class="fa fa-edit"></i></a></td>
                </tr>';
        }
    }
    public static function show_lotteries(){
        $stmt = DB::query("select * from lotteries where status='1'");
        foreach($stmt as $query){
            echo '<tr>
                    <th scope="row"><a href="#"><img src="/resources/'.$query['image'].'" alt=""></a></th>
                    <td><a href="#" class="text-primary fw-bold">'.ucfirst($query['name']).'</a></td>
                    <td class="fw-bold">₹'.$query['price'].'</td>
                    <td class="fw-bold">₹'.$query['refers'].'</td>
                    <td>'.self::showSelfSold($query['lid']).'</td>
                    <td><a href="sales.php?lid='.$query['lid'].'">View Sales</a></td>
                    <td class="fw-bold">'.$query['venue'].'</td>
                    <td><a href="/showbook/?lid='.$query['lid'].'" style="margin-right:1rem;"><i class="fa fa-eye"></i></a></td>
                </tr>';
        }
    }
    public static function showSold($lid){
        $query = DB::query("select * from bookings where lid='$lid' and pstatus='1'");
        $rows = count($query);
        return $rows;
    }
    public static function showSelfSold($lid){
        $ag = $_COOKIE['user'];
        $query = DB::query("select * from bookings where lid='$lid' and agent='$ag'");
        $rows = count($query);
        return $rows;
    }

    public static function showPending(){
        $query = DB::query("select * from bookings where status='0' order by id desc");
        foreach($query as $row){
            $lot = self::getLot($row['lid']);
            $agent = Emp::getAgent($row['agent']);
            echo '<tr>
                    <td>'.ucfirst($row['id']).'</td>
                    <td>'.ucfirst($lot['name']).'</td>
                    <th scope="row">'.$row['serial'].'</th>
                    <td>'.$row['name'].'</td>
                    <td>'.$row['address'].'</td>
                    <td><a href="https://wa.me/+91'.$row['phone'].'" class="text-primary">'.$row['phone'].'</a></td>
                    <td class="text-success">'.$agent['name'].'</td>
                    <td>'.$row['book'].'</td>
                    <td><a href="?approve='.$row['id'].'" class="btn btn-success" style="margin-right:1rem;">Mark</a><a class="" href="?disapprove='.$row['id'].'"><i class="bi bi-trash"></i></a></td>
                </tr>';
        }
    
    }

    public static function showPendingAgent($ag){
        $query = DB::query("select * from bookings where status='0' and agent='$ag' order by id desc");
        foreach($query as $row){
            $lot = self::getLot($row['lid']);
            $agent = Emp::getAgent($row['agent']);
            echo '<tr>
                    <td>'.ucfirst($row['id']).'</td>
                    <td>'.ucfirst($lot['name']).'</td>
                    <th scope="row">'.$row['serial'].'</th>
                    <td>'.$row['name'].'</td>
                    <td>'.$row['address'].'</td>
                    <td><a href="https://wa.me/+91'.$row['phone'].'" class="text-primary">'.$row['phone'].'</a></td>
                    <td class="text-success">'.$agent['name'].'</td>
                    <td>'.$row['book'].'</td>
                    <td><a href="?approve='.$row['id'].'" class="btn btn-success" style="margin-right:1rem;">Mark</a><a class="" href="?disapprove='.$row['id'].'"><i class="bi bi-trash"></i></a></td>
                </tr>';
        }
    
    }

    public static function showConfirm(){
        $query = DB::query("select * from bookings where status='0' order by id desc");
        foreach($query as $row){
            $lot = self::getLot($row['lid']);
            $agent = Emp::getAgent($row['agent']);
            echo '<tr>
                    <td>'.ucfirst($row['id']).'</td>
                    <td>'.ucfirst($lot['name']).'</td>
                    <th scope="row">'.$row['serial'].'</th>
                    <td>'.$row['name'].'</td>
                    <td>'.$row['address'].'</td>
                    <td><a href="https://wa.me/+91'.$row['phone'].'" class="text-primary">'.$row['phone'].'</a></td>
                    <td class="text-success">'.$agent['name'].'</td>
                    <td>'.$row['book'].'</td>
                    <td><a class="" href="?disapprove='.$row['id'].'"><i class="bi bi-trash"></i></a></td>
                </tr>';
        }
    
    }

    public static function showConfirmAgent($ag){
        $query = DB::query("select * from bookings where status='0' and agent='$ag' order by id desc");
        foreach($query as $row){
            $lot = self::getLot($row['lid']);
            $agent = Emp::getAgent($row['agent']);
            echo '<tr>
                    <td>'.ucfirst($row['id']).'</td>
                    <td>'.ucfirst($lot['name']).'</td>
                    <th scope="row">'.$row['serial'].'</th>
                    <td>'.$row['name'].'</td>
                    <td>'.$row['address'].'</td>
                    <td><a href="https://wa.me/+91'.$row['phone'].'" class="text-primary">'.$row['phone'].'</a></td>
                    <td class="text-success">'.$agent['name'].'</td>
                    <td>'.$row['book'].'</td>
                    <td><a class="" href="?disapprove='.$row['id'].'"><i class="bi bi-trash"></i></a></td>
                </tr>';
        }
    
    }

    public static function make_book_seq($lid){
        $query = DB::query("select * from books where lid='$lid'");
        foreach($query as $row){
            echo '<div class="alert alert-primary bg-primary text-light border-0 fade show" role="alert">
                    Sequence '.$row['begin'].' - '.$row['end'].'
                    <a href="showsequence.php?lid='.$lid.'&book='.$row['id'].'" class="btn btn-secondary btn-sm m-1" style="float:right;">View</a>
                    <a href="?lid='.$lid.'&delbook='.$row['id'].'" class="btn btn-danger btn-sm m-1" style="float:right;">Delete</a>
                </div>';
        }
    }
    
    public static function agent_book_seq($lid,$phone){
        $query = DB::query("select * from books where lid='$lid' and whatsapp='$phone' order by begin asc");
        foreach($query as $row){
            echo '<div class="alert alert-primary bg-primary text-light border-0 fade show p-2" role="alert">
                    Sequence '.$row['begin'].' - '.$row['end'].'
                    <a href="agentsequence.php?lid='.$lid.'&book='.$row['id'].'" class="btn btn-secondary pb-1" style="float:right; margin-left:1rem">View</a>
                    <a href="?lid='.$lid.'&delbook='.$row['id'].'" class="btn btn-danger pb-1 mr-1" style="float:right;">Delete</a>
                </div>';
        }
    }

    public static function makeSeqOrder($lid){
        $query = DB::query("select * from books where lid='$lid' order by begin asc");
        foreach($query as $row){
            echo '<div class="alert alert-primary bg-primary text-light border-0 fade show" role="alert">
                    Sequence '.$row['begin'].' - '.$row['end'].'
                    <a onclick="showSequence('.$row['id'].');" class="btn btn-secondary btn-sm m-1" style="float:right;">View</a>
                </div>';
        }
    }
    public static function sequence_table($lid,$begin,$end,$book){
        $range = range($begin,$end);
        foreach($range as $x){
            $query = DB::query("select * from bookings where lid='$lid' and serial='$x'");
            $row = $query[0];
            if(empty($row)){
                self::table_unsold($x);
            }else{
                
                self::table_sold($x,$row,$lid,$book);
            }
        }
    
    }
    
    public static function table_sold($nos,$row,$lid,$book){
        echo '<tr>
                <th scope="row">'.$nos.'</th>
                <td>'.$row['name'].'</td>
                <td>'.$row['phone'].'</td>
                <td>'.$row['address'].'</td>
                <td>'.self::gen_paystatus($row['pstatus']).'</td>';
        if($row['pstatus']==0){
            echo '<td><a href="?lid='.$lid.'&book='.$book.'&nos='.$row["id"].'" class="btn btn-primary">Approve</a></td>
            </tr>';
        }else{
            echo "<td style='color:red;'>Sold</td></tr>";
        }
                
    }
    
    public static function table_unsold($nos){
        echo '<tr>
                <th scope="row">'.$nos.'</th>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>';
    
    }
    public static function gen_paystatus($i){
        if($i==1){
            return '<span style="color:green;">Approved</span>';
        }else{
            return '<span style="color:red;">Pending</span>';
        }
        
    }

    public static function buySequence($id){
        $book = DB::query("select * from books where id='$id'");
        $book = $book[0];
        $lid = $book['lid'];
        $range = range($book['begin'],$book['end']);
        foreach($range as $x){
            $query = DB::query("select * from bookings where lid='$lid' and serial='$x'");
            if(empty($query)){
                self::sale_unsold($x,$lid,$book['id']);
            }else{
                self::sale_sold($x,$query[0]);
            }
        }
    
    }


    public static function sale_sold($nos,$row){
        echo '<tr>
                <th scope="row">'.$nos.'</th>
                <td class="text-info">'.$row['name'].'</td>
                <td><span style="color:red;">Sold</span></td>
            </tr>';
    }
    
    public static function sale_unsold($nos,$lid,$book){
        echo '<tr>
                <th scope="row">'.$nos.'</th>
                <td class="text-success">Available</td>
                <td style="font-size:10px;"><a href="javascript:void(0);" onclick="addtocart('.$book.','.$nos.');" id="linki" class="btn btn-success btn-sm">Cart</a></td>
                
            </tr>';
    
    }
    public static function getLotteriesOptions(){
        $query = DB::query("select * from lotteries where status='1'");
        foreach($query as $row){
            echo "<option value='".$row['lid']."'>".$row['name']."</option>";
        }
    }
    public static function get_carts($lot){
        $intro = substr($lot['name'],0,8);
        $tickets = $_SESSION['serial'];
        foreach($tickets as $a){
            echo "<tr>
                    <td class='text-sm'>".self::checkAv($lot['lid'],$a)."</td>
                    <td>".$a."</td>
                    <td><a href='?lid=".$lot['lid']."&&del=".$a."' class='btn btn-primary btn-sm'>Remove</a></td>
                </tr>";
        }
    }
    
    public static function checkAv($lid,$serial){
        $query = DB::query("select * from bookings where serial='$serial' and lid='$lid'");
        if(empty($query)){
            return "<p class='text-sm text-success' style='font-size:10px;'>Available</p>";
        }else{
            return "<p class='text-sm text-danger' style='font-size:10px;'>Unavailable</p>";
        }
    }
    public static function getlidBook($book){
        $row = DB::query("select * from books where id='$book'");
        return $row[0];
    }

    public static function checkAvail($lid,$serial){
        $query = DB::query("select * from bookings where serial='$serial' and lid='$lid'");
        if(empty($query)){
            return "yes";
        }else{
            return "no";
        }
    }
    public static function showSales($lid,$agent){
        if($agent=='112233'){
            $books = DB::insert("select * from books where lid='$lid' order by begin asc");
        }else{
            $books = DB::insert("select * from books where lid='$lid' and whatsapp='$agent' order by begin asc");
        }
        
        while($b = $books->fetch(PDO::FETCH_ASSOC)){
            $begin = $b['begin'];
            $end = $b['end'];
            $ag = Emp::getAgent($b['whatsapp']);
            for($r = $begin; $r < $end; ++$r){
                if(self::checkAvail($lid,$r)=='yes'){
                    echo "<tr>
                            <td>".$r."</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td class='text-success'>Available</td>
                        </tr>";
                }else{
                    $user = DB::query("select * from bookings where lid='$lid' and serial='$r'");
                    echo "<tr>
                            <td>".$r."</td>
                            <td>".$user[0]['name']."</td>
                            <td>".$user[0]['phone']."</td>
                            <td>".$user[0]['address']."</td>
                            <td>".$user[0]['book']."</td>
                            <td class='text-success'>".$ag['name']."</td>
                            <td class='text-danger'>Sold</td>
                        </tr>";
                }
                
            }
        }
    
    }

    public static function makeSeries($lid){
        $arr = array();
        $range = range(1,9);
        foreach($range as $r){
            $q = DB::query("select * from books where lid='$lid' and begin like '$r%'");
            if(!empty($q)){
                $c = count($q);
                echo '<option value="'.$r.'">'.$r.'</option>';
            }
        }
      }

}