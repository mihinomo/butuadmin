



<?php 


if(isset($_POST['uid'])){
    $per = Employee::rolesArray();
    $pp = Employee::accessArray();
    $age = Employee::agentArray($rid);
    $prephone = $age['phone'];
    $id = $_POST['uid'];
    $user = $_POST['ename'];
    $phone = $_POST['ephone'];
    if($prephone!=$phone){
        DB::query("update books set whatsapp='$phone' where whatsapp='$prephone'");
    }
    
    $address = $_POST['eaddress'];
    $pass = $_POST['pass'];
    $roles = json_encode($per);
    $page = json_encode($pp);
    $stmt = "update agents set name='$user', phone='$phone', address='$address', password='$pass', roles='$roles' where id='$id'";
    if(DB::insert($stmt)){
        echo "<script>alert('Successfully Saved Changes');</script>";
    }else{
        echo "<script>alert('Database Error');</script>";
    }
}

?>





<br><br><br>
<?php 

$agent = Employee::agentArray($rid);
$per = $agent['roles'];
//var_dump($agent);
//$ppage = $agent['accessibility'];
//var_dump($per);
?>

<form class="form-horizontal" id='editcustomer' method='POST' enctype="multipart/form-data">
<div class="row">
    <div class="card col-md-4 p-2 m-3">
        <h3>Edit Agent</h3><br>
        <hr>
        <input type='hidden' name='uid' value='<?php echo $rid; ?>' />
        <div class="form-group row">
            <label class="control-label col-md-3">Name</label>
            <div class="col-md-8">
            <input class="form-control" name='ename' value="<?php echo $agent['name']; ?>" id='cname' type="text" >
            </div>
        </div>
        <div class="form-group row">
            <label class="control-label col-md-3">Phone</label>
            <div class="col-md-8">
            <input class="form-control col-md-8" name='ephone' value="<?php echo $agent['phone']; ?>" type="text" maxlength='10' >
            </div>
        </div>
        <div class="form-group row">
            <label class="control-label col-md-3">Password</label>
            <div class="col-md-8">
            <input class="form-control col-md-8" name='pass' value="<?php echo $agent['password']; ?>" type="text" maxlength='10' >
            </div>
        </div>
        <div class="form-group row">
            <label class="control-label col-md-3">Address</label>
            <div class="col-md-8">
            <textarea class="form-control" rows="2" name='eaddress' id='caddress'><?php echo $agent['address']; ?></textarea>
            </div>
        </div>
    </div>
    <div class="card col-md-3 p-2 m-3">
        <h3>Site Permissions</h3><br><hr>
            <?php Employee::genPermissions($per); ?>
    </div>
    

</div>
<div style='clear:both;'></div>
    <button class="btn btn-primary m-3" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save Changes</button>
</form>


<script>
$(document).ready(function(e){
		// Submit form data via Ajax
		$("#addagent").on('submit', function(e){
			$('#tbody').empty();
			e.preventDefault();
			$.ajax({
				type: 'POST',
				url: '/insert/addagent/',
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
					$('#editmodal').modal('hide');
					reload_table();
				}
			});
		});
	});
</script>