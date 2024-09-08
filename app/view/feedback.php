<?php 
function show_feeds() {
    // Fetch all feed entries using a prepared statement
    $sql = "SELECT * FROM feed ORDER BY id DESC";
    $feeds = DB::executeQuery($sql, [], false); // Assuming executeQuery handles SELECT queries and returns an array of results

    if ($feeds) {
        foreach ($feeds as $row) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['phone']) . "</td>
                    <td>" . htmlspecialchars($row['dated']) . "</td>
                    <td>" . htmlspecialchars($row['msg']) . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No feeds found.</td></tr>";
    }
}
// Update feed records to mark them as not seen
$updateSql = "UPDATE feed SET seen = '0' WHERE seen = '1'";
$updateResult = DB::executeQuery($updateSql, []); // No parameters are needed here

if (!$updateResult) {
    // Optionally handle the error case, maybe log it or notify someone
    error_log("Failed to update feed seen status.");
}


?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Results</h1><br>
          </div><!-- /.col -->
          <table class="table table-hover table-bordered" id="ctable">
                <thead>
                <tr>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Dated</th>
                    <th>Message</th>
                </tr>
                </thead>
                <tbody id='tbody'>
                    <?php show_feeds(); ?>
                </tbody>
            </table>

        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
</div>   