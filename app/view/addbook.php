<?php 
if(isset($_GET['begin'])) {
    $lid = $_GET['lid'];
    $begin = $_GET['begin'];
    $end = $_GET['end'];
    $sl = $_GET['kser'];
    $bno = $_GET['bokno'];
    $wssp = $_GET['wssp'];

    // Prepare the SQL query using placeholders for parameters
    $sql = "INSERT INTO books (lid, begin, end, whatsapp, sl, bookno, status) VALUES (:lid, :begin, :end, :whatsapp, :sl, :bookno, '1')";
    $params = [
        ':lid' => $lid,
        ':begin' => $begin,
        ':end' => $end,
        ':whatsapp' => $wssp,
        ':sl' => $sl,
        ':bookno' => $bno
    ];

    // Execute the query using the executeQuery method
    $result = DB::executeQuery($sql, $params);

    // Check the result; executeQuery should be adjusted to return the number of affected rows for INSERT statements
    if ($result > 0) {
        $link = "/showbooks/" . htmlspecialchars($lid)."/";
        Widget::jsAlert("Successful", $link);
    } else {
        echo "<script>alert('Database Error');</script>";
    }
}
if(isset($_GET['agent'])){
    $ag = $_GET['agent'];
}else{
    $ag = 'all';
}


$lot = Lottery::getLot($lid);

function selectedAgent($aid){
    $q = DB::query("select * from agents where phone='$aid'");
    $row = $q[0];
    return "<option value='".$row['phone']."'>".$row['name']."</option>";
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Add New Book</h1><br>
          </div><!-- /.col -->
          <div style="clear:both;"></div>
            <?php require_once('./app/view/extra/bookform.php'); ?>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
</div>

<script>
    $(document).ready(function() {
        $('#agents').change(function() {
            var selectedAgent = $(this).val();
            var baseUrl = window.location.href.split('?')[0];  // Get the base URL without query parameters
            window.location.replace(baseUrl + '?agent=' + encodeURIComponent(selectedAgent));
        });
    });
    $(document).ready(function () {
        $("#gridCheck").change(function () {
            if($(this).is(":checked")) {
                $('#vis').css('display','block');         
            }else{
                $('#vis').css('display','none'); 
            }
        });
    });
</script>

