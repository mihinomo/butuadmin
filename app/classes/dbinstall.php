<?php 




class Dbinstall extends DB{
    public static function updates(){
        DB::query("alter table agents add roles text");
        DB::query("alter table agents add accessibility text");
        DB::query("alter table agents add ledger text");
        DB::query("alter table agents add type text");
        DB::query("alter table books add sl text");
        DB::query("alter table books add bookno text");
        DB::query("alter table books add status text");
    }
    public static function employeeDB(){
        $stmt = "create TABLE IF NOT EXISTS agents (
            id int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name text NOT NULL,
            type text,
            phone text NOT NULL,
            address text,
            aid text NOT NULL,
            password text,
            joined text,
            status text NOT NULL,
            image text,
            roles text,
            accessibility text,
            ledger text
        )";
        DB::insert($stmt);
        $stmt2 = "insert into agents(id,name,type,phone,aid,password,status) values(null,'SuperAdmin','AA','8258079172','112233','minomin','1')";
        DB::insert($stmt2);
        echo "Successfully Installed Agents Plugin<br>";
    }

    public static function settings(){
        $stmt = "create table if not exists settings (
            id int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            widgets text,
            allowances text,
            extra text,
            moreextra text,
            printer text
  
          )";
          //DB::insert("drop table settings");
          DB::insert($stmt);
          $widgets = ["salecounter"=>"1","invoicecounter"=>"1","invoicesells"=>"1","lastexpenditure"=>"1","invoicechart"=>"1"];
          $j = json_encode($widgets);
          DB::insert("insert into settings(id,widgets,allowances,printer) values(null,'$j','','')");
        echo "Successfully Installed Settings Plugin<br>";
    }

    public static function store(){
        $stmt = "create table if not exists stores (
            id int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            stid text NOT NULL,
            title text NOT NULL,
            descr text NOT NULL,
            region text NOT NULL,
            type text NOT NULL,
            contact text NOT NULL,
            address text NOT NULL,
            zone text NOT NULL,
            registered text NOT NULL,
            status text NOT NULL
        )";
        DB::insert($stmt);
        echo "Successfully Installed Stores Plugin<br>";
    }

    public static function journal(){
        $stmt = "create table if not exists journal (
            id int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            date text NOT NULL,
            particulars text NOT NULL,
            lf text NOT NULL,
            type text NOT NULL,
            bill text NOT NULL,
            amount text NOT NULL,
            rmks text NOT NULL,
            extras text NOT NULL
          )";
          DB::insert($stmt);
        echo "Successfully Installed Journal Plugin<br>";
    }

    public static function ledger(){
        $stmt = "create table if not exists ledger (
            id int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            date text NOT NULL,
            particulars text NOT NULL,
            jf text NOT NULL,
            type text NOT NULL,
            amount text NOT NULL,
            rmks text NOT NULL,
            extras text NOT NULL,
            account text NOT NULL
          )";
        DB::insert($stmt);
        echo "Successfully Installed Ledger Plugin<br>";
    }

    public static function ledgerAccounts(){
        $stmt = "create TABLE IF NOT EXISTS laccounts (
            id int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name text NOT NULL,
            uid text NOT NULL,
            added text NOT NULL,
            description text NOT NULL,
            status text NOT NULL
            
        )";
        DB::insert($stmt);
        echo "Successfully Installed Ledger Account Plugin<br>";
    }

    public static function lottery(){
        $stmt = "create TABLE IF NOT EXISTS lotteries (
            id int UNSIGNED NOT NULL AUTO_INCREMENT primary key,
            name text NOT NULL,
            added text NOT NULL,
            price text NOT NULL,
            refers text NOT NULL,
            descr text NOT NULL,
            lid text NOT NULL,
            image text NOT NULL,
            image2 text NOT NULL,
            ddate text NOT NULL,
            status text NOT NULL,
            venue text NOT NULL,
            whatsapp text,
            active text,
            dtime text,
            leaf int,

            
        )";
        DB::insert($stmt);
        echo "Successfully Installed Ledger Account Plugin<br>";
    }
    public static function bookings(){
        $stmt = "create TABLE IF NOT EXISTS bookings (
            id int UNSIGNED NOT NULL AUTO_INCREMENT primary key,
            serial text NOT NULL,
            lid text NOT NULL,
            pstatus text NOT NULL,
            name text NOT NULL,
            phone text NOT NULL,
            address text NOT NULL,
            dated text NOT NULL,
            book text NOT NULL,
            refer text NOT NULL,
            status text NOT NULL,
            review text,
            confirm text,
            agent text,
            bookno text
            
        )";
        DB::insert($stmt);
        echo "Successfully Installed Ledger Account Plugin<br>";
    }

    public static function books(){
        $stmt = "create TABLE IF NOT EXISTS books (
            id int UNSIGNED NOT NULL AUTO_INCREMENT primary key,
            lid text NOT NULL,
            begin text NOT NULL,
            end text NOT NULL,
            whatsapp text null,
            sl text null,
            bookno text,
            status text
            
        )";
        DB::insert($stmt);
        echo "Successfully Installed Ledger Account Plugin<br>";
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
        $stmt5 = "insert into closebook(id, type, account, debit, credit, dated) values(null, 'ledger', '03', '0', '0', '$date')";
        $stmt6 = "insert into closebook(id, type, account, debit, credit, dated) values(null, 'ledger', '04', '0', '0', '$date')";
        $stmt7 = "insert into closebook(id, type, account, debit, credit, dated) values(null, 'ledger', '05', '0', '0', '$date')";
        DB::insert($stmt2);
        DB::insert($stmt3);
        DB::insert($stmt4);
        DB::insert($stmt5);
        DB::insert($stmt6);
        DB::insert($stmt7);
        echo "Successfully Installed Close Book Plugin<br>";
    }
}

