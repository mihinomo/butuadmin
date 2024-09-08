
<?php 
$pp = "Ledger";



if(isset($_GET['r1'])){
	$r1 = $_GET['r1'];
	$r2 = $_GET['r2'];
	if($r1=='0'){
		if($r2=='all'){
			$last = Account::getLastClose('journal','null');
			$from = $last['dated'];
			$to = date("Y-m-d");
		}else{
			$last = Account::getLastClose('ledger',$r2);
			$from = $last['dated'];
			$to = date("Y-m-d");
		}
	}else{
		if($r2=='all'){
			$pre = Account::getClosed($r1);
			$last = Account::getPreviousClose('journal',$r1,'null');
			$from = $last['dated'];
			$to = $pre['dated'];
		}else{
			$pre = Account::getClosed($r1);
			$last = Account::getPreviousClose('ledger',$r1,$r2);
			$from = $last['dated'];
			$to = $pre['dated'];
		}
	}
}else{
	$r1='0';
    $r2 = $_GET['r2'];
    if($r2=='all'){
		$last = Account::getLastClose('journal','null');
		$from = $last['dated'];
		$to = date("Y-m-d");

	}else{
		$last = Account::getLastClose('ledger',$r2);
		$from = $last['dated'];
		$to = date("Y-m-d");
	}
    
}






?>

  <div class="app-title">
	
	<ul class="app-breadcrumb breadcrumb side">
	  <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
	  <li class="breadcrumb-item">Bussiness Journal</li>
	</ul>
  </div>
  <div class='col-md-5 m-2'>
		<button class="btn btn-success ml-2" data-toggle="modal" data-target="#exampleModal">New Entry</button>
		<a href='/balancesheet/' class='btn btn-primary ml-2'>Generate Position</a>
		<button id='pdf' class='btn btn-primary ml-2'>Print</button>
	</div>
  <div class="row">
	
	<div class="col-12 col-sm-6 col-md-3">
		<div class="info-box" style='background:#99999e;'>
		  <span class="info-box-icon bg-info elevation-1"><i class="fas fa-rupee-sign"></i></span>

		  <div class="info-box-content">
			<span class="info-box-text">Balance Cash</span>
			<span class="info-box-number" id='cash'>
			  0
			</span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	  </div>
	  
	  <div class="col-12 col-sm-6 col-md-3">
		<div class="info-box mb-3" style='background:#99999e;'>
		  <span class="info-box-icon bg-success elevation-1"><i class="fa fa-hand-holding-usd"></i></span>

		  <div class="info-box-content">
			<span class="info-box-text">Total Credit</span>
			<span class="info-box-number" id='credit'>0</span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	  </div>
	  <div class="col-12 col-sm-6 col-md-3">
		<div class="info-box mb-3" style='background:#99999e;'>
		  <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-money-check-alt"></i></span>

		  <div class="info-box-content">
			<span class="info-box-text">Expenses</span>
			<span class="info-box-number" id='debit'>0</span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	  </div>

	<hr>





	<div class="col-md-12">
	  	<div class="row">
			<div class='col-md-3 m-2'>
				<select class='form-control' name='sion' id='sion'>
					<option>--Select Account--</option>
					<?php Ledger::fetch_ledger_account($r2); ?>

				</select>
			</div>
            <div class='col-md-3 m-2'>
				<select class='form-control' name='psion' id='psion'>
					<option value='0'>Previous Closes</option>
					<?php Ledger::closedJournalsOptions('ledger',$r2,$from); ?>
				</select>
			</div>
			

		</div>
		
	  <div class="table-responsive col-md-12 m-1">
			<table class="table table-hover table-bordered" id="ctable">
			  <thead>
				<tr class='bg-info'>
					<th style='width:100px;'>Serial</th>
					<th style='width:200px;'>Dated</th>
					<th style='width:600px;'>Particulars</th>
					<th style='width:200px;'>Journal Folio</th>
					<th style='width:300px;'>Withdraw(Dr.)</th>
					<th style='width:300px;'>Deposit (Cr.)</th>
					<th style='width:300px;'>Account</th>
					<th style='width:300px;'>Action</th>
				</tr>
			  </thead>
			  <tbody id='ledgertable'>
				<tr>
                    <td> ------- </td>
                    <td> <?php echo $last['dated']; ?> </td>
                    <td> Prviously Closed -- </td>
                    <td> ------- </td>
                    <td> <?php echo $last['debit']; ?> </td>
                    <td> <?php echo $last['credit']; ?> </td>
                    <td> ------- </td>
                </tr>
			  </tbody>
			</table>
		</div>
	  
	</div>
	<!---------- suppliers ------------->
	<br>
	<hr>
	<br><br>
	<hr>
		<br><br><div class='col-md-12' style='height:100px;'></div>
		<div class='col-md-6'>
			<table class="table" id='cattable'>
			<h4 class='text-dark'>Ledger Accounts<a class='btn btn-primary' style='float:right;' data-toggle="modal" data-target="#account"><i class='fa fa-plus'> </i> Add Account</a></h4>
			  <thead class="thead-dark">
				<tr>
				  <th scope="col">Name</th>
				  <th scope="col">Description</th>
				  <th scope="col">Added</th>
				  <th scope="col">Action</th>
				</tr>
			  </thead>
			  <tbody id='cat'>
				
			  </tbody>
			</table>
		</div>
  </div>
<br><br>
  <!--common script for all pages-->
<script type="text/javascript" src="/resources/old/jspdf.min.js"></script>
<script type="text/javascript" src="/resources/old/jspdf.plugin.autotable.min.js"></script>
<script type="text/javascript" src="/resources/old/tableHTMLExport.js"></script>
<script type="text/javascript" src="/resources/old/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/resources/old/plugins/dataTables.bootstrap.min.js"></script>
<!-- Page specific javascripts-->
<script type="text/javascript">
$("#pdf").on("click",function(){
	var file="<?php echo $pp."-".date('d-m-y') ?>";
	$("#ctable").tableHTMLExport({
	  type:'pdf',
	  filename:file+'.pdf'
	});
});
$("#sion").on('change', function(){
	r2 = $(this).val();
	console.log(r2);
	r1 = $("#psion").val();
	link = '/ledger/?r1='+r1+'&r2='+r2;
	window.location.replace(link);
});
$("#psion").on('change', function(){
	id = $(this).val();
	link = '/ledger/?r1='+id+'&r2=<?php echo $r2; ?>';
	window.location.replace(link);
});
$(function() {
    //console.log("ledger Request has been fired");
    var r1 = "<?php echo $from; ?>";
	var r2 = "<?php echo $to; ?>";
	var acc = "<?php echo $r2; ?>";
	var half = "r1="+r1+"&r2="+r2+"&acc="+acc;
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '/api/ledger/?'+half,
        success: function(data){ 
            $.each(data, function( index, value ) {
                var row = $("<tr id='tt'><td>" + value.serial + "</td><td>" + value.dated + "</td><td>" + value.particulars + "</td><td>" + value.jf + "</td><td> " + value.debit + "</td><td> " + value.credit + "</td><td>" + value.account + "</td><td><a onclick='deleteentry(\""+value.tid+"\");'><i class='fas fa-trash'></i></a></td></tr>");
                $("#ledgertable").append(row);
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert('error: ' + textStatus + ': ' + errorThrown);
        }
    });
    return false;//suppress natural form submission
});
$(function() {
	//console.log("ledger score Request has been fired");
	var r1 = "<?php echo $from; ?>";
	var r2 = "<?php echo $to; ?>";
	var acc = "<?php echo $r2; ?>";
	var half = "r1="+r1+"&r2="+r2+"&acc="+acc;
	$.ajax({
		type: 'POST',
		dataType: "json",
		url: '/api/ledgerscore/?'+half,
		success: function(data){ 
			console.log(data);
			$("#debit").text(data.debit);
			$("#credit").text(data.credit);
			$("#cash").text(data.balance);
		},
		error: function(jqXHR, textStatus, errorThrown){
			alert('error: ' + textStatus + ': ' + errorThrown);
		}
	});
	return false;//suppress natural form submission
});

$(function() {
	console.log("laccounts Request has been fired");
	$.ajax({
		type: 'POST',
		dataType: "json",
		url: '/api/ledgeraccounts/',
		success: function(data){ 
			console.log(data);
			$("#cat").append("<tr id='tt'><td>Sales</td><td>Pos Related</td><td>02</td><td>-----</td>");
			$("#cat").append("<tr id='tt'><td>Bank</td><td>Bank Account</td><td>01</td><td>-----</td>");
			$("#cat").append("<tr id='tt'><td>Expenditure</td><td>Expenditure, miilii miipa nii</td><td>03</td><td>-----</td>");
			$.each(data, function( index, value ) {
                var row = $("<tr id='tt'><td>" + value.name + "</td><td>" + value.description + "</td><td>" + value.added + '</td><td><a onclick="deleteCat('+value.id+');"><i class="fa fa-trash"></i></a></td></tr>');
                $("#cat").append(row);
            });
		},
		error: function(jqXHR, textStatus, errorThrown){
			alert('error: ' + textStatus + ': ' + errorThrown);
		}
	});
	return false;//suppress natural form submission
});



$(document).ready(function(e){
	// Submit form data via Ajax
	$("#addaccount").on('submit', function(e){
		e.preventDefault();
		$.ajax({
			type: 'POST',
			url: '/insert/addaccount/',
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function(){
				//
			},
			success: function(response){ console.log(response);
				if(response.status == 1){
					
					toastr.success(response.message);
					
				}else{
					toastr.success(response.message);
					
				}
				reloadCategory();
				$('#account').modal('hide');
			}
		});
	});
});

function deleteCat(val){
	$.ajax({
		type: 'POST',
		url: '/insert/delaccount/'+val+'/',
		dataType: 'json',
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function(){
			//
		},
		success: function(response){ console.log(response);
			if(response.status == 1){
				
				toastr.success(response.message);
				
			}else{
				toastr.success(response.message);
				
			}
			reloadCategory();
			$('#account').modal('hide');
		}
	});
}


function deleteentry(val){
	$.ajax({
		type: 'POST',
		url: '/insert/delledger/'+val+'/',
		dataType: 'json',
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function(){
			//
		},
		success: function(response){ console.log(response);
			if(response.status == 1){
				
				toastr.success(response.message);
				
			}else{
				toastr.success(response.message);
				
			}
			reloadLedger();
		}
	});
}

function reloadCategory(){
	$("#cat").empty();
	console.log("Api Request has been fired");
	$.ajax({
		type: 'POST',
		dataType: "json",
		url: '/api/ledgeraccounts/',
		success: function(data){ 
			console.log(data);
			$("#cat").append("<tr id='tt'><td>Sales</td><td>Pos Related</td><td>02</td><td>-----</td>");
			$("#cat").append("<tr id='tt'><td>Bank</td><td>Bank Account</td><td>01</td><td>-----</td>");
			$("#cat").append("<tr id='tt'><td>Expenditure</td><td>Expenditures</td><td>03</td><td>-----</td>");

			$.each(data, function( index, value ) {
                var row = $("<tr id='tt'><td>" + value.name + "</td><td>" + value.description + "</td><td>" + value.added + '</td><td><a onclick="deleteCat('+value.id+');"><i class="fa fa-trash"></i></a></td></tr>');
                $("#cat").append(row);
            });
		},
		error: function(jqXHR, textStatus, errorThrown){
			alert('error: ' + textStatus + ': ' + errorThrown);
		}
	});
	return false;//suppress natural form submission
}
</script>


<style>
#ctable td {
text-transform: capitalize;
}
#ctable i {
maegin:0rem 1rem;
}
.zoom {
padding: 50px;
transition: transform .2s; /* Animation */
width: 50px;
height: 50px;
margin: 0 auto;
}

.zoom:hover {
transform: scale(4.5); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
}
</style>
<script>


$(document).ready(function(e){
// Submit form data via Ajax
$("#addjournal").on('submit', function(e){
	e.preventDefault();
	$.ajax({
		type: 'POST',
		url: '/insert/addjournal/',
		data: new FormData(this),
		dataType: 'json',
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function(){
			//
		},
		success: function(response){ console.log(response);
			if(response.status == "1"){
				toastr.success(response.message);
			}else{
				alert(response.message);
			}
			$('#exampleModal').modal('hide');
			reloadLedger();
		}
	});
});
});

function reloadLedger(){
	$("#ledgertable").empty();
	console.log("Reload Api Request has been fired");
	var r1 = "<?php echo $from; ?>";
	var r2 = "<?php echo $to; ?>";
	var acc = "<?php echo $r2; ?>";
	var half = "r1="+r1+"&r2="+r2+"&acc="+acc;
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '/api/ledger/?'+half,
        success: function(data){ 
            $.each(data, function( index, value ) {
                var row = $("<tr id='tt'><td>" + value.serial + "</td><td>" + value.dated + "</td><td>" + value.particulars + "</td><td>" + value.jf + "</td><td> " + value.debit + "</td><td> " + value.credit + "</td><td>" + value.account + "</td><td><a onclick='deleteentry(\""+value.tid+"\");'><i class='fas fa-trash'></i></a></td></tr>");
                $("#ledgertable").append(row);
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert('error: ' + textStatus + ': ' + errorThrown);
        }
    });
}
</script>

<!-- Modal -->
<div class="modal fade" id="account" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Ledger Account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="tile">
            <div class="tile-body">
              <form class="form-horizontal" id='addaccount' method='get' enctype="multipart/form-data">
			  <input type='hidden' name='euid' id='euid' value='' />
                <div class="form-group row">
                  <label class="control-label col-md-3">Name</label>
                  <div class="col-md-8">
                    <input class="form-control" name='name' id='name' type="text" >
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-3">Short Description</label>
                  <div class="col-md-8">
                    <textarea class="form-control" name='description' id='desc' type="text"></textarea>
                  </div>
                </div>
            </div>
            <div class="tile-footer">
              <div class="row">
                <div class="col-md-8 col-md-offset-3">
                  <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
          </div>
		  
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-info" id="exampleModalLabel">Create New Ledger Entry</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style='background:#99999e;'>
              <form class="form-horizontal" id='addjournal' enctype="multipart/form-data">
                <div class='box col-md-12' style='float:center;'>
				<div class="upload_form_cont" style='background:#99999e;'>
					
					<textarea class="form-control mb-2" id="title" name="particulars" rows='3' placeholder="Enter Particulars" required></textarea>
					<input type="text" class='form-control' id="amount" name="amount" placeholder="Enter Exact Amount" required ><br>
					
					
					<input type='hidden' name="dc" value='0' required>
					<select name="from" class='form-control' required>
						<option>Account Withdrawn from</option>
						<option value='none'>None</option>
						<option value='02'>Cash Account</option>
						<option value='01'>Bank Account</option>
						<option value='03'>Expenditure</option>
						<?php Ledger::ledgerAcountOptions(); ?>
						  
					</select><br>
					<select name="to" class='form-control' required>
						<option>Account Deposited to</option>
						<option value='none'>None</option>
						<option value='02'>Cash Account</option>
						<option value='01'>Bank Account</option>
						<option value='03'>Expenditure</option>
						<?php Ledger::ledgerAcountOptions(); ?>
						  
					</select><br>
					
					<div>
						  
					<br>
					<input type="submit" id='submit' class='btn btn-success' name="add_productbt" value="Add Entry"/>
					</div>
					
				</form>
			</div>
		  
      </div>
    </div>
  </div>
</div>
<script>
$("#amount").on('keyup',function(){
	val = $(this).val();
	if(isNaN(val)){
		alert("Please Enter Amount in digits");
		$(this).val("");
	}
});

</script>