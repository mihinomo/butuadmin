<?php 

class Dashboard extends DB {
    

    public static function CreateView($name){
        if(isset($_POST['create_store'])){
            $title=$_POST['st_nm'];
            $phone=$_POST['st_ph'];
            $address=$_POST['st_addr'];
            $region=$_POST['st_region'];
            $desc=$_POST['st_desc'];
            $type=$_POST['st_type'];
            $stid=md5($title.$phone);
            $today = date('Y-m-d H:i:a s');
            $stmt="insert into stores(id,stid,title,descr,region,type,contact,address,zone,registered,status) values(null,'$stid','$title','$desc','$region','$type','$phone','$address','','$today','1')";
            $query=DB::insert($stmt);
            if($query){
                Widget::alertRed('Ok',"Successful",'/dashboard/');
            }else{
                Widget::alertGreen("Oops! Please Try Again");
            }
        }

        $aid = $_COOKIE['login'];
        $agent = Emp::getAgent($aid);

        if($agent['type']=='AA'){
        //$views = [];
            $views = array("pos"=>"1","hrm"=>'1',"erp"=>"1","crm"=>"1","accounting"=>"1","import"=>"1");
        }else{
            $views = json_decode($agent['roles'],true);
        }
        require_once('./app/view/common/header.php');
        if(Auth::checkStore()=='False'){
            require_once('./app/view/newstore.php');
        }else{
            require_once('./app/view/'.$name.'.php');
        }
        
        require_once('./app/view/common/footer.php');
        //Index::test();
    }

    public static function CreateProgress(){
        if(isset($_GET['aid'])){
            $aid = $_GET['aid'];
        }else{
            $aid = "null";
        }

        require_once('./app/view/common/header.php');
        require_once('./app/view/progresslottery.php');
        require_once('./app/view/common/footer.php');
        //Index::test();
    }
	
	public static function makeProgressLotteryDash() {
        $lotteries = DB::query("SELECT * FROM lotteries WHERE status='1' order by ddate asc limit 0,3");

        foreach ($lotteries as $lottery) {
            $leaf = self::makeLeafFromBook($lottery['lid']);
            $books = max(self::countBooks($lottery['lid']),1);
            $bookings = self::countBookings($lottery['lid']);
            $totalBooks = $books * $leaf;
            $percent = ($bookings / $totalBooks) * 100;

            $link = "https://www.arunachalluckydraw.in/buynow/{$lottery['lid']}/";
            self::renderProgressBar($lottery['name'], $bookings, $totalBooks, $percent, $link, $lottery['lid']);

        }
    }

    public static function makeProgressLottery() {
        $lotteries = self::fetchActiveLotteries();

        foreach ($lotteries as $lottery) {
            $leaf = self::makeLeafFromBook($lottery['lid']);
            $books = max(self::countBooks($lottery['lid']),1);
            $bookings = self::countBookings($lottery['lid']);
            $totalBooks = $books * $leaf;
            $percent = ($bookings / $totalBooks) * 100;

            $link = "https://www.arunachalluckydraw.in/buynow/{$lottery['lid']}/";
            self::renderProgressBar($lottery['name'], $bookings, $totalBooks, $percent, $link, $lottery['lid']);

        }
    }

    private static function fetchActiveLotteries() {
        return DB::query("SELECT * FROM lotteries WHERE status='1'");
    }

    public static function makeagentProgress($lot){
        $available = [];
        $agents = DB::query("select * from agents where status='1'");
        foreach($agents as $agent){
            $aid = $agent['aid'];
            $phone = $agent['phone'];
            $check = DB::query("select * from books where lid='$lot' and whatsapp='$phone'");
            if(!empty($check)){
                $total_books = count($check);
                $leaf = self::makeLeafFromBook($lot);
                $total = $total_books*$leaf;
                $bookings = count(DB::query("select * from bookings where lid='$lot' and agent='$aid'"));
                $percent = ($bookings / $total) * 100;
                echo '<div class="progress-group mb-3">';
                echo $agent['name'];
                echo " || <span class='float-end'><b>{$bookings}</b>/{$total}</span> || Books - ".$total_books;
                echo "<div class='progress'>";
                echo "<div class='progress-bar bg-info' style='width: {$percent}%' aria-valuenow='{$percent}' aria-valuemin='0' aria-valuemax='100'></div>";
                echo "</div>";
                echo "</div>";
            }
        }
    }

    private static function renderProgressBar($name, $bookings, $totalBooks, $percent, $link, $lid) {
        echo '<div class="progress-group mb-3">';
        echo "{$name}";
        echo " || <span class='float-end'><b>{$bookings}</b>/{$totalBooks}</span>";
        echo "<div class='progress'>";
        echo "<div class='progress-bar bg-info' style='width: {$percent}%' aria-valuenow='{$percent}' aria-valuemin='0' aria-valuemax='100'></div>";
        echo "</div>";
        echo "<button class='btn btn-primary m-2' onclick=\"window.open('".self::createFacebookLink($link)."')\"><i class='fab fa-facebook'></i></button>";
        echo "<button class='btn btn-success m-2' onclick=\"window.open('".self::createWhatsAppLink($link, $name)."')\"><i class='fab fa-whatsapp'></i></button>";
        echo "<button class='btn btn-secondary m-2' onclick='copyLinkToClipboard(\"{$link}\")'><i class='fas fa-clipboard'></i></button>";
        echo "<a href='' class='btn btn-secondary m-2'>".countViews($lid)."</a>";
        echo "</div>";
    }

    private static function createFacebookLink($url) {
        return "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($url);
    }

    private static function createWhatsAppLink($url, $text) {
        return "https://wa.me/?text=" . urlencode("Book Tickets tickets from Trusted ALD Platform : {$url} - {$text}");
    }
	public static function makeLeafFromBook($lid){
        $query = DB::query("select * from books where lid='$lid'");
        $book = $query[0];
        $begin = $book['begin'];
        $end = $book['end'];
        $dif = $end-$begin+1;
        return $dif;
    }
    
    public static function countBookings($lid){
        $query = DB::query("select * from bookings where lid='$lid'");
        return count($query);
    }
    
    public static function countBooks($lid){
        $query = DB::query("select * from books where lid='$lid'");
        return count($query);
    }
    public static function newOrders(){
        $query = DB::query("select * from orders where status='0'");
        return count($query);
    }
    public static function newBooks(){
        $query = DB::query("select * from bookings where refer='' and pstatus='0'");
        return count($query);
    }

    public static function salesToday(){//show today sales
        $today = date('Y-m-d');
        $query = "SELECT SUM(l.price) AS total_sales FROM bookings b 
                JOIN lotteries l ON b.lid = l.lid 
                WHERE b.dated = '$today'";
        $result = DB::query($query)[0];
        return $result['total_sales'] ?? 0; // Returns 0 if null
    }

    public static function salesYesterday(){//show yesterday sales
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $query = "SELECT SUM(l.price) AS total_sales FROM bookings b 
                JOIN lotteries l ON b.lid = l.lid 
                WHERE b.dated = '$yesterday'";
        $result = DB::query($query)[0];
        return $result['total_sales'] ?? 0; // Returns 0 if null
    }
    public static function salesMonth() {
        $firstDayOfMonth = date('Y-m-01'); // First day of the current month
        $lastDayOfMonth = date('Y-m-t');   // Last day of the current month
    
        $query = "SELECT SUM(l.price) AS total_sales FROM bookings b 
                  JOIN lotteries l ON b.lid = l.lid 
                  WHERE b.dated BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth'";
        $result = DB::query($query)[0];
        return $result['total_sales'] ?? 0; // Returns 0 if null
    }

    public static function salesLastMonth() {
        $firstDayOfLastMonth = date('Y-m-01', strtotime("first day of last month"));
        $lastDayOfLastMonth = date('Y-m-t', strtotime("last day of last month"));
    
        $query = "SELECT SUM(l.price) AS total_sales FROM bookings b 
                  JOIN lotteries l ON b.lid = l.lid 
                  WHERE b.dated BETWEEN '$firstDayOfLastMonth' AND '$lastDayOfLastMonth'";
        $result = DB::query($query)[0];
        return $result['total_sales'] ?? 0; // Returns 0 if null
    }
    
    public static function salesThisWeek() {
        $startOfWeek = date('Y-m-d', strtotime('sunday last week')); // Sunday this week
        $endOfWeek = date('Y-m-d', strtotime('saturday this week'));   // Saturday this week
    
        $query = "SELECT SUM(l.price) AS total_sales FROM bookings b 
                  JOIN lotteries l ON b.lid = l.lid 
                  WHERE b.dated BETWEEN '$startOfWeek' AND '$endOfWeek'";
        $result = DB::query($query)[0];
        return $result['total_sales'] ?? 0; // Returns 0 if null
    }
    
    public static function salesLastWeek() {
        $startOfLastWeek = date('Y-m-d', strtotime('last sunday -1 week')); // Sunday of the previous week
        $endOfLastWeek = date('Y-m-d', strtotime('last saturday'));         // Saturday of the previous week
    
        $query = "SELECT SUM(l.price) AS total_sales FROM bookings b 
                  JOIN lotteries l ON b.lid = l.lid 
                  WHERE b.dated BETWEEN '$startOfLastWeek' AND '$endOfLastWeek'";
        $result = DB::query($query)[0];
        return $result['total_sales'] ?? 0; // Returns 0 if null
    }
    

    
}

function countViews($lid){
    $q = DB::query("select count(*) from linkview where lid='$lid'");
    return $q[0][0];
}
