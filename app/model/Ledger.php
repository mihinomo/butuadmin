<?php 
class Ledger extends DB {
	static function journal_entry($date,$rmks,$type,$bill,$amount,$account,$uid){
		$stmt = "insert into journal(id,date,particulars,lf,type,bill,amount,rmks,extras)values(null,'$date','$rmks','$account','$type','$bill','$amount','','$uid')";
		$query = DB::insert($stmt);
	}
	static function ledger_entry($jf,$date,$rmks,$type,$amount,$account,$uid){
		$stmt = "insert into ledger(id,date,particulars,jf,type,amount,rmks,extras,account)values(null,'$date','$rmks','$jf','$type','$amount','','$uid','$account')";
		$query = DB::insert($stmt);
	}
	static function journal_folio($oid){
		$query = DB::query("select * from journal where bill='$oid'");
		$row = $query[0];
		if(empty($query)){
			return "";
		}else{
			return $row['id'];
		}
	}
	static function journal_folio_last(){
		$query = DB::query("select * from journal order by id desc");
		$row = $query[0];
		if(empty($query)){
			return "";
		}else{
			return $row['id'];
		}
	}
	static function sale_bill($oid){
		$query = DB::query("select * from sales where purid='$oid'");
		$row = $query[0];
		if(empty($query)){
			return "";
		}else{
			return $row['id'];
		}
	}
	static function insale_bill($oid){
		$query = DB::query("select * from invoices where invid='$oid'");
		$row = $query[0];
		if(empty($query)){
			return "";
		}else{
			return $row['id'];
		}
	}


	public static function getJournal(){
		$r1 = $_GET['r1'];
		$r2 = $_GET['r2'];
		$jj = "lf";
		if($r1=='null'){
			$query=DB::query("select * from journal order by id desc limit 0, 25");
		}else{
			$query=DB::query("select * from journal where date between '$r1' and '$r2' order by id desc");
		}
		$i = 1;
		$emparray = array();
		foreach($query as $row){
			$temparray['serial'] = $i;
			$temparray['id'] = $row['id'];
			$temparray['dated'] = date('m/d/Y',strtotime($row['date']));
			$temparray['particulars'] = $row['particulars'];
			$temparray['credit'] = self::check_type_credit($row['type'],$row['amount']);
			$temparray['debit'] = self::check_type_debit($row['type'],$row['amount']);
			if($jj=='jf'){
				$temparray['account'] = self::fetch_ledger_accounts($row['account']);
				$temparray['jf'] = $row[$jj];
			}else{
				$temparray['account'] = "----";
				$temparray['lf'] = self::fetch_ledger_accounts($row['lf']);
			}
			array_push($emparray,$temparray);
			$i++;
		}
		header('Content-type: application/json');
		echo json_encode($emparray);
	}

	public static function getLedger(){
		$r1 = $_GET['r1'];
		$r2 = $_GET['r2'];
		$jj = "jf";
		$acc = $_GET['acc'];
		$emparray = array();
		if($acc=='null'){
			$query=DB::query("select * from ledger order by id desc");
		}else{
			if($acc=='all'){
				$query=DB::query("select * from ledger where date between '$r1' and '$r2' order by id desc");
			}else{
				$query=DB::query("select * from ledger where account='$acc' and date between '$r1' and '$r2' order by id");
			}
		}

		$i = 1;

		foreach($query as $row){
			$temparray['serial'] = $i;
			$temparray['tid'] = $row['extras'];
			$temparray['id'] = $row['id'];
			$temparray['dated'] = date('m/d/Y',strtotime($row['date']));
			$temparray['particulars'] = $row['particulars'];
			$temparray['credit'] = self::check_type_credit($row['type'],$row['amount']);
			$temparray['debit'] = self::check_type_debit($row['type'],$row['amount']);
			if($jj=='jf'){
				$temparray['account'] = self::fetch_ledger_accounts($row['account']);
				$temparray['jf'] = $row[$jj];
			}else{
				$temparray['account'] = "----";
				$temparray['lf'] = self::fetch_ledger_accounts($row['lf']);
			}
			array_push($emparray,$temparray);
			$i++;
		}

		echo json_encode($emparray);
	}

	public static function getLedgerScore(){
		$r1 = $_GET['r1'];
		$r2 = $_GET['r2'];
		$acc = $_GET['acc'];
		$data = array();
		$jj = "lf";
		$data["total"] = self::fetch_ledger_credit($r1,$r2,$acc);
		$data["balance"] = self::fetch_ledger_credit($r1,$r2,$acc)-self::fetch_ledger_debit($r1,$r2,$acc);
		$data["debit"] = self::fetch_ledger_debit($r1,$r2,$acc);
		$data["credit"] = self::fetch_ledger_credit($r1,$r2,$acc);
		$data["status"] = "Ok";
		$data["message"] = "Successfully Connected to ledger api";
		header('Content-type: application/json');
		echo json_encode($data);
	}



	public static function getJounalScore(){
		$r1 = $_GET['r1'];
        $r2 = $_GET['r2'];
        $data =array();
        $jj = "lf";
        $data["total"] = self::fetch_journal_credit($r1,$r2);
        $data["balance"] = self::fetch_journal_credit($r1,$r2)-self::fetch_journal_debit($r1,$r2);
        $data["debit"] = self::fetch_journal_debit($r1,$r2);
        $data["credit"] = self::fetch_journal_credit($r1,$r2);
        $data["status"] = "Ok";
        $data["message"] = "Successfully Connected to ledger api";
		header('Content-type: application/json');
		echo json_encode($data);		
	}

	public static function fetch_ledger_account($ac){
		$pre = ['bank'=>'01','cash'=>'02','Expenditure'=>'03'];
		foreach ($pre as $key => $value) {
			if($ac == $value){
				echo "<option value=".$value." selected>".ucfirst($key)."</option>";	
			}else{
				echo "<option value=".$value.">".ucfirst($key)."</option>";
			}		
		}
		$query = DB::query("select * from laccounts where status='active'");
		foreach($query as $row){
			if($ac==$row['uid']){
				echo "<option value=".$row['uid']." selected>".ucfirst($row['name'])."</option>";
			}else{
				echo "<option value=".$row['uid'].">".ucfirst($row['name'])."</option>";
			}
			
		}
	}

	public static function ledgerAcountOptions(){
		
		$query = DB::query("select * from laccounts where status='active'");
		foreach($query as $row){
			echo "<option value=".$row['uid'].">".ucfirst($row['name'])."</option>";
		}
	}

	public static function fetch_ledger_accounts($type){
		if($type=='01'){
			return "Bank Account";
		}elseif($type=='02'){
			return "Cash";
		}elseif($type=='03'){
			return "Expenditure";
		}elseif($type=='05'){
			return "Salary";
		}else{
			$query = DB::query("select * from laccounts where uid='$type'");
			if(empty($query)){
				return "<i class='text-danger'>Deleted Account</i>";
			}else{
				$row = $query[0];
				return $row['name'];
			}
		}
	}
	
	// C H E C K ------FUNCTIONS
	public static function check_type_credit($type,$amount){
		if($type=='credit'){
			return $amount;
		}else{
			return "---";
		}
	}
	public static function check_type_debit($type,$amount){
		if($type=='debit'){
			return $amount;
		}else{
			return "---";
		}
	}
	public static function fetch_journal_credit($date1,$date2){
		$am = 0;
		if($date1=='null'){
			$query=DB::query("select * from journal where type='credit' and date='$date2'");
		}else{
			$query = DB::query("select * from journal where type='credit' and date between '$date1' and '$date2' ");
		}
		foreach($query as $row){
			$am = $am + $row['amount'];
		}
		
		return $am;
	}
	
	public static function fetch_journal_debit($date1,$date2){
		$am = 0;
		if($date1=='null'){
			$query=DB::query("select * from journal where type='debit' and date='$date2'");
		}else{
			$query = DB::query("select * from journal where type='debit' and date between '$date1' and '$date2' ");
		}
		foreach($query as $row){
			$am = $am + $row['amount'];
		}
		return $am;
	}
	public static function fetch_ledger_credit($date1,$date2,$acc){
		$am = 0;
		if($acc=='null'){
			$query=DB::query("select * from ledger where type='debit' and date='$date2'");
		}else{
			if($acc=='all'){
				$query=DB::query("select * from ledger where type='credit' and date between '$date1' and '$date2'");
			}else{
				$query=DB::query("select * from ledger where type='credit' and account='$acc' and date between '$date1' and '$date2'");
			}
		}
		foreach($query as $row){
			$am = $am + $row['amount'];
		}
		return $am;
	}
	
	public static function fetch_ledger_debit($date1,$date2,$acc){
		$am = 0;
		
		if($acc=='null'){
			$query=DB::query("select * from ledger where type='debit' and date='$date2'");
		}else{
			if($acc=='all'){
				$query=DB::query("select * from ledger where type='debit' and date between '$date1' and '$date2'");
			}else{
				$query=DB::query("select * from ledger where type='debit' and account='$acc' and date between '$date1' and '$date2'");
			}
		}
		
		foreach($query as $row){
			$am = $am + $row['amount'];
		}
		return $am;
	}

	public static function fetch_ledger_account_table(){
		$query = DB::query("select * from laccounts where status='active'");
		foreach($query as $row){
			echo "<tr>
				<td>".$row['name']."</td>
				<td>".$row['description']."</td>
				<td>".$row['added']."</td>
				<td><a onclick='delete_ledger_account(".$row['id'].")'><i class='fa fa-trash'></i></a></td>
			</tr>";
		}
		
	}

	public static function laccounts(){
		$query = DB::query("select * from laccounts");
		header('Content-type: application/json');
		echo json_encode($query);	
	}

	public static function closedJournalsOptions($tp,$ac,$date){
		if($tp=='journal'){
			$q = DB::query("select * from closebook where type='$tp' order by id desc");
		}else{
			if($ac=='all'){
				$q = DB::query("select * from closebook where type='journal' order by id desc");
			}else{
				$q = DB::query("select * from closebook where type='$tp' and account='$ac' order by id desc");
			}
			
		}
		foreach($q as $row){
			if($row['dated']!=$date){
				echo "<option value=".$row['id'].">".$row['dated']."</option>";
			}
		}
		
	}

	public static function expenditureWidget(){
		$query = DB::query("select * from ledger where account='03' order by id desc limit 0, 10");
		foreach($query as $row){
			echo '<li class="item">
                    
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">'.$row['date'].'
                        <span class="badge '.($row['type']=='debit'? "badge-danger":"badge-warning").' float-right">₹ '.$row['amount'].'</span></a>
                      <span class="product-description">
                        '.$row['particulars'].'
                      </span>
                    </div>
                  </li>';
		}
	}


	public static function balanceSheet(){
		$accounts = ["01","02","03"];
		$de = 0;
		$ce = 0;
		$dbacc = self::query("select * from laccounts");
		foreach($dbacc as $row){
			array_push($accounts,$row['uid']);
		}
		//var_dump($accounts);
		foreach($accounts as $a){	
			$q = self::query("select * from closebook where account='$a' order by id desc");
			$last = $q[0];
			$lastdebit = $last['debit'];
			$lastcredit = $last['credit'];
			$lsdt = $last['dated'];
			$lendt = date("Y-m-d");
			$newcredit = self::fetch_ledger_credit($lsdt,$lendt,$a);
			$newdebit = self::fetch_ledger_debit($lsdt,$lendt,$a);
			$account = self::fetch_ledger_accounts($a);
			$credit = $lastcredit+$newcredit;
			$debit = $lastdebit+$newdebit;
			$de = $de+$debit;
			$ce = $ce+$credit;
			$bal = $credit-$debit;
			echo "<tr>
					<td class='text-info'>".$account."</td>
					<td class='text-info'>₹ ".$credit."</td>
					<td class='text-info'>₹ ".$debit."</td>
					<td class='text-info'>₹ ".$bal."</td>
					
				</tr>";
			
		}
		$balance = $ce-$de;
		echo "<tr style='border-top:2px solid white;'>
			<td class='text-info'></td>
			<td class='text-info'>₹ ".$ce."</td>
			<td class='text-info'>₹ ".$de."</td>
			<td class='text-info'></td>
			
		</tr>
		<tr>
			<td class='text-info'>-</td>
			<td class='text-info'></td>
			<td class='text-info'></td>
			<td class='text-info'></td>
			
		</tr>
		<tr>
			<td class='text-info'>-</td>
			<td class='text-info'></td>
			<td class='text-info'></td>
			<td class='text-info'></td>
		</tr>
		<tr>
			<td class='text-info'>-</td>
			<td class='text-info'></td>
			<td class='text-info'></td>
			<td class='text-info'></td>
		</tr>
		<tr style='border-top:2px solid white;'>
			<td class='text-info'>Position</td>
			<td class='text-info'></td>
			<td class='text-info'></td>
			<td class='text-info'>₹ ".$balance."</td>
			
		</tr>";
	}
}

?>