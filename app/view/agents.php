<?php 
if(isset($_GET['enable'])){
  $id = $_GET['enable'];
  DB::query("update agents set status='1' where phone='$id'");
  DB::query("update books set status='1' where whatsapp='$id'");
  Widget::jsAlert("Successfully Enabled Agent","/agents/");
}

if(isset($_GET['disable'])){
  $id = $_GET['disable'];
  Widget::jsAlert("Successfully Disabled Agent","/agents/");
  DB::query("update agents set status='0' where phone='$id'");
  DB::query("update books set status='0' where whatsapp='$id'");
}

if(isset($_POST['ename'])){
  $username=$_POST['ename'];
  $phone=$_POST['ephone'];
  $address=$_POST['eaddress'];
  $account=$_POST['acc'];
  $cid = Widget::generatecid('7');
  $aid = Widget::check_aid($cid);
  $today = date('Y-m-d');
  $nami=$_FILES['file']['name'];
  $name=md5($today).$nami;
  $profile=$name;
  $dir='./resources/profile/'.$name;
  $per = ["lot"=>"0","orders"=>"0","updates"=>"0","frontend"=>"0","admin"=>"0","accounting"=>"0","hrm"=>"0", "gallery"=>"0"];
  $pp = ["man"=>"0","pos"=>"0","journal"=>"0","purchase"=>"0"];
  $roles = json_encode($per);
  $pages = json_encode($pp);
  if(employeeExists($phone)=='true'){
    if(move_uploaded_file($_FILES['file']['tmp_name'],$dir)){
          $stmt ="insert into agents(id,name,type,phone,address,aid,password,joined,roles,accessibility,profile,ledger,status) values(null,'$username','bb','$phone','$address','$aid','','$today','$roles','$pages','$dir','$account','1')";
          DB::insert($stmt);
          
    }else{
        echo "<script>alert('There was error uploading profile'); </script>";
    }
  }else{
    echo "<script>alert('Phone Already Enrolled'); </script>";
  }
}
function employeeExists($phone){
  $query = DB::query("select * from agents where phone='$phone'");
  if(empty($query)){
    return "true";
  }else{
    return "false";
  }
}

?>



<div class="content-wrapper">
<button type="button" class="btn btn-info m-2" data-toggle="modal" data-target="#exampleModal">
  New Agent
</button>







<div class="row">
  <div class="col-md-12">
    <div class="tile m-2">
      <div class="tile-body">
        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="ctable">
            <thead class='bg-info'>
              <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Registered</th>
                <th>Employee ID</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id='agenttable'>
              <?php Employee::ShowAgents(); ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Agent</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="tile">
            <div class="tile-body">
              <form class="form-horizontal" id='editcustomer' method='post' enctype="multipart/form-data">
			          <input type='hidden' name='euid' id='euid' value='' />
                <div class="form-group row">
                  <label class="control-label col-md-3">Name</label>
                  <div class="col-md-8">
                    <input class="form-control" name='ename' id='cname' type="text" >
                  </div>
                </div>
                <div class="form-group row">
                <label class="control-label col-md-3">Ledger</label>
                  <div class="col-md-8">
                    <select class='form-control' name='acc' id='acc'>
                      <option>--Select Account--</option>
                      <option value='none'>No Account</option>
                      <?php Ledger::fetch_ledger_account($r2); ?>

                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-3">Phone</label>
                  <div class="col-md-8">
                    <input class="form-control col-md-8" name='ephone' id='cphone' type="text" maxlength='10' >
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-3">Address</label>
                  <div class="col-md-8">
                    <textarea class="form-control" rows="4" name='eaddress' id='caddress'></textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-3">Identity Proof</label>
                  <div class="col-md-8">
                    <input class="form-control" type="file" name='file'>
                  </div>
                </div>
				
            </div>
            <div class="tile-footer">
              <div class="row">
                <div class="col-md-8 col-md-offset-3">
                  <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Register Staff</button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
          </div>
		  
        </form>
      </div>
    </div>
</div>
<link rel="stylesheet" href="/resources/dist/jquery-confirm.min.css">
<script src="/resources/dist/jquery-confirm.min.js"></script>
<script>
function deleteagent(val){
    $.confirm({
        title: '<b class="text-danger">ATTENTION !!</b>',
        content: '<b class="text-info">Do you really want to delete this agent!</b>',
        buttons: {
            somethingElse: {
                text: 'Delete Agent',
                btnClass: 'btn-blue',
                keys: ['enter', 'shift'],
                action: function(){
                    $.ajax({
                        type: 'POST',
                        dataType: "json",
                        url: '/delete/delagent/',
                        data: { id: val },
                        success: function(response){ 
                            if(response.status=='1'){
                                alert(response.message);
                                window.location.replace('/agents/');
                            }else{
                                alert(response.message);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            alert('error: ' + textStatus + ': ' + errorThrown);
                        }
                    });
                }
            },
            cancel: function () {
                $.alert('<b class="text-info">Canceled!</b>');
            }
        }
    });
}

function confir(){
  return confirm("Are you sure you want this action?");
}
</script>