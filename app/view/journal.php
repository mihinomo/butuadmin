
<?php 
$pp = "Journal";
if(isset($_GET['r1'])){
	$r1 = $_GET['r1'];
	$last = Account::getPreviousClose('journal',$r1,'null');
	$pre = Account::getClosed($r1);
	$from = $last['dated'];
	$to = $pre['dated'];

}else{
	$last = Account::getLastClose('journal','null');
	$from = $last['dated'];
	$to = date("Y-m-d");
}



?>
  <div class="app-title">
	
	<ul class="app-breadcrumb breadcrumb side">
	  <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
	  <li class="breadcrumb-item">Bussiness Journal</li>
	</ul>
  </div>
  
  <div class='statusMsg'></div>
  <div class="row">
	<div class="col-12 col-sm-6 col-md-3">
		<div class="info-box">
		  <span class="info-box-icon bg-info elevation-1"><i class="fas fa-rupee-sign"></i></span>

		  <div class="info-box-content">
			<span class="info-box-text">Total Revenue</span>
			<span class="info-box-number" id='cash'>
			  0
			</span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	  </div>
	  
	  <div class="col-12 col-sm-6 col-md-3">
		<div class="info-box">
		  <span class="info-box-icon bg-info elevation-1"><i class="fas fa-wallet"></i></span>

		  <div class="info-box-content">
			<span class="info-box-text">Balance Capital</span>
			<span class="info-box-number" id='balance'>
			  0
			</span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	  </div>
	  <!-- /.col -->
	  <div class="col-12 col-sm-6 col-md-3">
		<div class="info-box mb-3">
		  <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-hand-holding-usd"></i></span>

		  <div class="info-box-content">
			<span class="info-box-text">Takings</span>
			<span class="info-box-number" id='credit'>0</span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	  </div>
	  <div class="col-12 col-sm-6 col-md-3">
		<div class="info-box mb-3">
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
				<select class='form-control' name='r1' id='preclose'>
					<option>Previous Closes</option>
					<?php Ledger::closedJournalsOptions('journal','null',$from); ?>
				</select>
			</div>
			<div class='col-md-6 m-2'>
				<button class='btn btn-primary ml-2'>Close Book</button>
				<button class='btn btn-primary ml-2'>Generate Period</button>
				<button id='pdf' class='btn btn-primary ml-2'>Print</button>
			</div>

		</div>
		
	  <div class="table-responsive col-md-12 m-1">
			<table class="table table-hover table-bordered" id="ctable">
			  <thead>
				<tr>
					<th style='width:100px;'>Serial</th>
					<th style='width:200px;'>Dated</th>
					<th style='width:600px;'>Particulars</th>
					<th style='width:200px;'>Journal Folio</th>
					<th style='width:300px;'>Debit (Dr.)</th>
					<th style='width:300px;'>Credit (Cr.)</th>
					<th style='width:300px;'>Account</th>
				</tr>
			  </thead>
			  <tbody id='tbody'>
				
			  </tbody>
			</table>
		</div>
	  
	</div>
	<!---------- suppliers ------------->
	<br>
	<hr>
	<br><br><div class='col-md-12' style='height:100px;'></div>
	
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
$("#preclose").on('change', function(){
	id = $(this).val();
	link = '/journal/?r1='+id;
	window.location.replace(link);
});

  $(function() {
		console.log("Api Request has been fired");
		var r1 = "<?php echo $from; ?>";
		var r2 = "<?php echo $to; ?>";
		var half = "r1="+r1+"&r2="+r2;
		$.ajax({
			type: 'POST',
			dataType: "json",
			url: '/api/journal/?'+half,
			success: function(data){ 
				$.each(data, function( index, value ) {
				   var row = $("<tr id='tt'><td>" + value.serial + "</td><td>" + value.dated + "</td><td>" + value.particulars + "</td><td>" + value.lf + "</td><td> " + value.debit + "</td><td> " + value.credit + "</td><td>" + value.account + "</td></tr>");
				   $("#ctable").append(row);
				});
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert('error: ' + textStatus + ': ' + errorThrown);
			}
		});
		return false;//suppress natural form submission
	});
	$(function() {
		console.log("Api Request has been fired");
		var r1 = "<?php echo $from; ?>";
		var r2 = "<?php echo $to; ?>";
		var half = "r1="+r1+"&r2="+r2;
		$.ajax({
			type: 'POST',
			dataType: "json",
			url: '/api/journalscore/?'+half,
			success: function(data){ 
				console.log(data);
				$("#debit").text(data.debit);
				$("#credit").text(data.credit);
				$("#cash").text(data.total);
				$("#balance").text(data.balance);
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert('error: ' + textStatus + ': ' + errorThrown);
			}
		});
		return false;//suppress natural form submission
	});


function reload_table() {
$('#tbody').empty();
console.log("Api Request has been fired");
$.ajax({
	type: 'POST',
	dataType: "json",
	url: 'api/api.php?journal',
	success: function(data){ 
		$.each(data, function( index, value ) {
		   var row = $("<tr id='tt'><td>" + value.serial + "</td><td>" + value.dated + "</td><td>" + value.particulars + "</td><td>" + value.lf + "</td><td> " + value.debit + "</td><td> " + value.credit + "</td><td>" + value.account + "</td></tr>");
		   $("#ctable").append(row);
		});
		$('#ctable').DataTable();
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
		url: 'php/ledger.php?addjournal',
		data: new FormData(this),
		dataType: 'json',
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function(){
			//
		},
		success: function(response){ console.log(response);
			$('.statusMsg').html('');
			if(response.status == 1){
				$('#addcustomer')[0].reset();
				$('.statusMsg').html('<p class="alert alert-success">'+response.message+'</p>');
			}else{
				$('.statusMsg').html('<p class="alert alert-danger">'+response.message+'</p>');
				
			}
			$('#exampleModal').modal('hide');
			reload_table();
		}
	});
});
});
</script>
