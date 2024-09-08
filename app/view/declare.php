<?php

if (isset($_GET['content'])) {
    $content = htmlspecialchars($_GET['content']); // Assume that real escaping is handled by prepared statements
    $today = date('Y-m-d'); // Ensure today's date is correctly formatted

    // Insert into results table
    $insertSql = "INSERT INTO results (lid, content, dated, status) VALUES (:lid, :content, :dated, '1')";
    $insertResult = DB::executeQuery($insertSql, [':lid' => $lid, ':content' => $content, ':dated' => $today]);

    if ($insertResult > 0) {
        // Update lotteries status
        $updateLotteries = DB::executeQuery("UPDATE lotteries SET status = '0' WHERE lid = :lid", [':lid' => $lid]);
        // Update bookings status
        $updateBookings = DB::executeQuery("UPDATE bookings SET pstatus = '1' WHERE lid = :lid", [':lid' => $lid]);
        // Update orders status
        $updateOrders = DB::executeQuery("UPDATE orders SET status = '1' WHERE lid = :lid", [':lid' => $lid]);

        if ($updateLotteries > 0 && $updateBookings > 0 && $updateOrders > 0) {
            Widget::jsAlert('Successfully Declared Result', '/results/');
        } else {
            echo '<script>alert("Database Error in Updating Tables");</script>';
        }
    } else {
        echo '<script>alert("Database Error in Inserting Result");</script>';
    }
}


$lot = Lottery::getLot($lid);

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Declaring <?php echo $lot['name']; ?></h1><br>
          </div><!-- /.col -->

                <div class="col-12">
                    <div class="card top-selling overflow-auto">

                        <div class="card-body pb-0">
                            <div class="table-responsive">
                                <div class="card">
                                    <br>
                                    <div class="card-body">
                                        <form method='get'>
                                            <h4>About Content</h4>
                                            <input type="hidden" name='declare' value="<?php echo $lid; ?>">
                                            <textarea class='form-control' id='declare' name='content'>
                                                
                                            </textarea>
                                            <input type='submit' class='btn btn-info m-1' value="Declare Result">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
</div>    

<!-- Place the first <script> tag in your HTML's <head> -->
<script src="https://cdn.tiny.cloud/1/aqv4rvrs6dji1w7ywj6259comw4rkqw7j852qpt2rvjaccxm/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
  tinymce.init({
    selector: 'textarea',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
  });
</script>