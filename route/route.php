<?php

Route::set('index.php',function(){
    Auth::IndexAuth();
    Index::MakeIndex('Index'); 
});

Route::set('progresslottery',function(){
    Auth::strictPage();
    Dashboard::CreateProgress(); 
});

Route::set('dashboard',function(){
    Auth::strictPage();
    Dashboard::CreateView('Dashboard');
});

Route::set('agents',function(){
    Auth::strictPage();
    Employee::MakeIndex();
});

Route::set('editagent',function(){
    $explode = explode("/",$_SERVER['REQUEST_URI']);
    $rid = $explode[2];
    Auth::strictPage();
    Employee::editForm($rid);
});

Route::set('addnewlot',function(){
    Auth::strictPage();
    $explode = explode("/",$_SERVER['REQUEST_URI']);
    Lottery::NewIndex();
});

Route::set('lotteries',function(){
    Auth::strictPage();
    $explode = explode("/",$_SERVER['REQUEST_URI']);
    Lottery::MakeIndex();
});

Route::set('sales',function(){
    $explode = explode("/",$_SERVER['REQUEST_URI']);
    $lid = $explode[2];
    Auth::strictPage();
    Lottery::ViewSales($lid);
});

Route::set('showbooks',function(){
    $explode = explode("/",$_SERVER['REQUEST_URI']);
    $lid = $explode[2];
    Auth::strictPage();
    Lottery::ViewBooks($lid);
});

Route::set('addbook',function(){
    $explode = explode("/",$_SERVER['REQUEST_URI']);
    $lid = $explode[2];
    Auth::strictPage();
    Lottery::addBook($lid);
});

Route::set('showsequence',function(){
    
    Auth::strictPage();
    Lottery::sequence();
});

Route::set('declare',function(){
    $explode = explode("/",$_SERVER['REQUEST_URI']);
    $lid = $explode[2];
    Auth::strictPage();
    Lottery::declare($lid);
});

Route::set('result',function(){
    Auth::strictPage();
    Result::index();
});

Route::set('viewresult',function(){
    $explode = explode("/",$_SERVER['REQUEST_URI']);
    $lid = $explode[2];
    Auth::strictPage();
    Result::showresult($lid);
});

Route::set('announcement',function(){
    $explode = explode("/",$_SERVER['REQUEST_URI']);
    $lid = $explode[2];
    Auth::strictPage();
    Result::announce($lid);
});

Route::set('feedback',function(){
    
    Auth::strictPage();
    Result::feedback();
});

Route::set('frontend',function(){
    
    Auth::strictPage();
    Frontend::index();
});

Route::set('activeorders',function(){
    
    Auth::strictPage();
    Order::active();
});

Route::set('revieworders',function(){
    
    Auth::strictPage();
    Order::review();
});

Route::set('confirmorders',function(){
    
    Auth::strictPage();
    Order::confirm();
});

//////// START PLUGINS //////
Route::set('journal',function(){
    Auth::strictPage();
    Accounting::CreateJournalView();
});
Route::set('ledger',function(){
    Auth::strictPage();
    Accounting::CreateLedgerView();
});








Route::set('api',function(){
    
    $explode = explode("/",$_SERVER['REQUEST_URI']);
    $tp = $explode[2];
    if($tp=='journal'){
        Ledger::getJournal();
    }
    if($tp=='ledger'){
        Ledger::getLedger();
    }
    if($tp=='journalscore'){
        Ledger::getJounalScore();
    }
    if($tp=='ledgerscore'){
        Ledger::getLedgerScore();
    }
    if($tp=='ledgeraccounts'){
        Ledger::laccounts();
    }
    if($tp=='lotser'){
        $lid = $_GET['lotteryId'];
        Lottery::makeSeries($lid);
    }
    if($tp=='lotbook'){
        //echo "ayai";
        $lid = $_GET['lid'];
        $series = $_GET['series'];
        echo Book::availableTickets($lid, "none", $series);
    }
    
});

Route::set('insert',function(){
    $i = 0;
    $response = array();
    $explode = explode("/",$_SERVER['REQUEST_URI']);
    $tp = $explode[2];
    if($tp=='delaccount'){
        $id = $explode[3];
        $query = DB::insert("delete from laccounts where id='$id'");
        if($query){
            $response["status"] = "1";
            $response['message'] = "Successfully Deleted Category List";
        }else{
            $response["status"] = "failed";
            $response['message'] = "There was an error";
        }
    }
    
    if($tp=='addaccount'){
        $id = uniqid();
        $dated=date('Y-m-d');
        $name = $_POST['name'];
        $des = $_POST['description'];
        $query = DB::insert("insert into laccounts(id,name,uid,added,description,status) values(null,'$name','$id','$dated','$des','active')");
        DB::insert("insert into closebook(id, type, account, debit, credit, dated) values(null, 'ledger', '$id', '0', '0', '$dated')");
        if($query){
            $response["status"] = "1";
            $response['message'] = "Successfully Added to Ledger Accounts";
        }else{
            $response["status"] = "failed";
            $response['message'] = "There was an error";
        }
    }

    if($tp=='addjournal'){
        $uid = uniqid();
		$am=$_POST['amount'];
		$particulars=$_POST['particulars'];
		$from=$_POST['from'];
		$to=$_POST['to'];
		$dc=$_POST['dc'];
		$ddc=date('Y-m-d');
		if($from=='none'){
			$bill = "null";
			Ledger::journal_entry($ddc,$particulars,'credit',$bill,$am,$to,$uid);
			$jf = Ledger::journal_folio_last();
			Ledger::Ledger_entry($jf,$ddc,$particulars,'credit',$am,$to,$uid);
		}elseif($to=='none'){
            //to Ledger
			$bill = "null";
			Ledger::journal_entry($ddc,$particulars,'debit',$bill,$am,$from,$uid);
			$jf = Ledger::journal_folio_last();
			Ledger::Ledger_entry($jf,$ddc,$particulars,'debit',$am,$from,$uid);
        }else{
			// from Ledger
			$bill = "null";
			Ledger::journal_entry($ddc,$particulars,'debit',$bill,$am,$from,$uid);
			$jf = Ledger::journal_folio_last();
			Ledger::Ledger_entry($jf,$ddc,$particulars,'debit',$am,$from,$uid);
			
			//to Ledger
			$bill = "null";
			Ledger::journal_entry($ddc,$particulars,'credit',$bill,$am,$to,$uid);
			$jf = Ledger::journal_folio_last();
			Ledger::Ledger_entry($jf,$ddc,$particulars,'credit',$am,$to,$uid);
		}
        $response["status"] = "1";
	    $response["message"] = "Successfully Added Ledger Entry";
    }
    
    echo json_encode($response);
});










/////////////////////////////////////////////
/////////////////////////////////////////////
/////////////////////////////////////////////
/////////////////////////////////////////////
/////////////////////////////////////////////

Route::set('install',function(){
    //prequistite
    dbinstall::employeeDB();
    dbinstall::store(); 
    dbinstall::settings();

    //app dbs
    dbinstall::lottery();
    dbinstall::bookings();
    dbinstall::books();

    //plugins
    dbinstall::journal();
    dbinstall::ledger();
    dbinstall::ledgerAccounts(); 
    dbinstall::closeBookdb();
});

Route::set('installupdates',function(){
    //prequistite
    //dbinstall::updates();
    //dbinstall::employeeDB();
    //dbinstall::store();
    //dbinstall::journal();
    //dbinstall::ledger();
    //dbinstall::ledgerAccounts(); 
    //dbinstall::closeBookdb();
});