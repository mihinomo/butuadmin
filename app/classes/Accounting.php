<?php 

class Accounting extends Controller {
    

    public static function CreateJournalView(){
        
        $backbtn = "/dashboard/";
        require_once('./app/view/common/headernav.php');
        
        require_once('./app/view/journal.php');
        
        require_once('./app/view/common/footer.php');
    }

    public static function CreateLedgerView(){
        
        $backbtn = "/dashboard/";
        require_once('./app/view/common/headernav.php');
        
        require_once('./app/view/ledger.php');
        
        require_once('./app/view/common/footer.php');
    }
    
}


class Account extends Accounting {

    public static function closeAccount(){
        $date = date("Y-m-d");
        $last = self::getLastClose('journal','null');
        $date2 = $last['dated'];
        $jcredit = Ledger::fetch_journal_credit($date2,$date);
        $jdebit = Ledger::fetch_journal_debit($date2,$date);
    
        $acc = self::getAccounts();
        foreach($acc as $x){
            $ac = self::getLastClose('ledger',$x['uid']);
            $accredit = Ledger::fetch_ledger_credit($ac['dated'],$date,$x['uid']);
            $acdebit = Ledger::fetch_ledger_debit($ac['dated'],$date,$x['uid']);
            self::insertClose('ledger',$x['uid'],$acdebit,$accredit,$date);
        }
    
        $req =['01','02','03'];
        foreach($req as $r){
            $ac = self::getLastClose('ledger',$r);
            $accredit = Ledger::fetch_ledger_credit($ac['dated'],$date,$r);
            $acdebit = Ledger::fetch_ledger_debit($ac['dated'],$date,$r);
            self::insertClose('ledger',$r,$acdebit,$accredit,$date);
        }
        self::insertClose('journal','null',$jdebit,$jcredit,$date);
        
    }
    
    public static function getAccounts(){
        $accounts = DB::query("select * from laccounts where status='active'");
        return $accounts;
    }
    
    public static function getLastClose($tp,$acc){
        if($tp=='journal'){
            $stmt = DB::query("select * from closebook where type='journal' order by id desc");
        }else{
            if($acc=='all'){
                $stmt = DB::query("select * from closebook where type='journal' order by id desc");
            }else{
                $stmt = DB::query("select * from closebook where type='ledger' and account='$acc' order by id desc");
            }
        }
        return $stmt[0];
        
    }
    
    public static function insertClose($tp,$ac,$debit,$credit,$date){
        if($tp=='journal'){
            $stmt = DB::insert("insert into closebook(id,type,account,debit,credit,dated) values(null,'$tp','0','$debit','$credit','$date')");
        }else{
            $stmt = DB::insert("insert into closebook(id,type,account,debit,credit,dated) values(null,'ledger','$ac','$debit','$credit','$date')");
        }
        
    }

    public static function getClosed($id){
        $stmt = DB::query("select * from closebook where id='$id'");
        return $stmt[0];
    }

    public static function getPreviousClose($tp,$id,$ac){
        if($tp=='journal'){
            $stmt = DB::query("select * from closebook where type='$tp' and id<'$id' order by id desc");
        }else{
            if($ac=='all'){
                $stmt = DB::query("select * from closebook where type='journal' and id<'$id' order by id desc");
            }else{
                $stmt = DB::query("select * from closebook where type='ledger' and account='$ac' and id<'$id' order by id desc");
            }
            
        }
        return $stmt[0];
    }
    
    public static function closeBookdb(){
        $stmt ="create table if not exists closebook(
            id int(255) unsigned not null auto_increment primary key,
            type text not null,
            account text not null,
            debit text not null,
            credit text not null,
            dated text not null
        )";
        DB::insert($stmt);
        $date = date("Y-m-d");
        $stmt2 = "insert into closebook(id, type, account, debit, credit, dated) values(null, 'journal', '', '0', '0', '$date')";
        $stmt3 = "insert into closebook(id, type, account, debit, credit, dated) values(null, 'ledger', '01', '0', '0', '$date')";
        $stmt4 = "insert into closebook(id, type, account, debit, credit, dated) values(null, 'ledger', '02', '0', '0', '$date')";
        DB::insert($stmt2);
        DB::insert($stmt3);
        DB::insert($stmt4);
    
    }
}


?>