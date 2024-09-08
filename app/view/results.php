<?php 
function show_results() {
    $sql = "SELECT * FROM results ORDER BY id DESC";
    $results = DB::executeQuery($sql, [], false); // Assuming executeQuery handles SELECT queries and returns an array of results

    if ($results) {
        $i = 1;
        foreach ($results as $row) {
            $lot = Lottery::getLot($row['lid']); // Assuming get_lottery is also refactored to use DB class |<a href='?del=" . htmlspecialchars($row['lid']) . "' class='btn btn-danger'>Delete</a>
            echo "<tr>
                    <td>" . htmlspecialchars($i) . "</td>
                    <td>" . htmlspecialchars($lot['name']) . "</td>
                    <td>" . htmlspecialchars($row['dated']) . "</td>
                    <td><a href='/viewresult/" . htmlspecialchars($row['lid']) . "/' class='btn btn-success'>View/Edit Result</a>
                        
                    </td>
                </tr>";
            $i++;
        }
    } else {
        echo "<tr><td colspan='4'>No results found.</td></tr>";
    }
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

                <div class="col-12">
                    <table class="table table-hover table-bordered datatable" id="ctable">
                        <thead>
                        <tr>
                            <th>Serial</th>
                            <th>Coupon</th>
                            <th>Dated</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id='tbody'>
                            <?php show_results(); ?>
                        </tbody>
                    </table>
                </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
</div>    