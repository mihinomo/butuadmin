<?php 
function showOrderPending() {
    // Ensure global DB class instance is used instead of $con21 for a more modern approach
    $sql = "SELECT * FROM orders WHERE status = '0' ORDER BY id DESC";
    $orders = DB::executeQuery($sql, [], false);  // Assuming executeQuery fetches multiple rows

    if ($orders) {
        foreach ($orders as $row) {
            $lot = Lottery::getLot($row['lid']);  // Ensure get_lot is refactored to use DB::executeQuery as well
            echo '<tr>
                    <td>' . htmlspecialchars(ucfirst($row['id'])) . '</td>
                    <td>' . htmlspecialchars(ucfirst($lot['name'])) . '</td>
                    <th scope="row">' . htmlspecialchars($row['serials']) . '</th>
                    <td>' . htmlspecialchars($row['name']) . '</td>
                    <td>' . htmlspecialchars($row['address']) . '</td>
                    <td><a href="https://wa.me/+91' . htmlspecialchars($row['phone']) . '" class="text-primary">' . htmlspecialchars($row['phone']) . '</a></td>
                    <td class="text-success">' . htmlspecialchars(Emp::agentName($row['agent'])) . '</td>
                    <td>' . htmlspecialchars($row['otime']) . '</td>
                    <td><a href="?approve=' . htmlspecialchars($row['oid']) . '" class="btn btn-success" style="margin-right:1rem;">Mark</a>
                        <a class="" href="?disapprove=' . htmlspecialchars($row['oid']) . '"><i class="bi bi-trash"></i></a></td>
                </tr>';
        }
    } else {
        echo "<tr><td colspan='9'>No pending orders found.</td></tr>";
    }
}


?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Active Orders</h1><br>
          </div><!-- /.col -->

          <div class="col-12">
            <div class="card recent-sales overflow-auto">


                <div class="card-body">
                <h5 class="card-title">Recent Orders <span>| Approvals</span></h5>

                <table class="table table-striped dt-responsive" id="mytable">
                    <thead>
                    <tr>
                        <th scope="col">Sys ID</th>
                        <th scope="col">Lottery</th>
                        <th scope="col">Serial</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Address</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Agent</th>
                        <th scope="col">Dated</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php showOrderPending(); ?>
                    </tbody>
                </table>

                </div>

            </div>
            </div><!-- End Recent Sales -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
</div>  