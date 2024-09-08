<?php 

if(isset($_GET['agent'])){
    $ag = $_GET['agent'];
}else{
    $ag = 'all';
}
function show_book_sales($lid, $agent) {
    // Prepare the query using placeholders for parameters
    $params = [':lid' => $lid];
    $sql = "SELECT bookings.*, lotteries.name as lot_name, agents.name as emp_name 
            FROM bookings 
            LEFT JOIN lotteries ON lotteries.lid = bookings.lid 
            LEFT JOIN agents ON agents.aid = bookings.refer
            WHERE bookings.lid = :lid";

    if ($agent !== 'all') {
        $sql .= " AND bookings.refer = :agent";
        $params[':agent'] = $agent;
    }

    $sql .= " ORDER BY bookings.serial DESC";

    // Execute the query
    $query = DB::executeQuery($sql, $params);

    // Check if query was successful and contains data
    if ($query) {
        foreach ($query as $row) {
            echo "<tr>
                <td>" . htmlspecialchars($row['serial']) . "</td>
                <td>" . htmlspecialchars($row['lot_name']) . "</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['phone']) . "</td>
                <td>" . htmlspecialchars($row['dated']) . "</td>
                <td>" . htmlspecialchars($row['emp_name']) . "</td>
                </tr>";
        }
    } else {
        // Handle case where no data was returned or there was an error
        echo "<div class='alert alert-warning' role='alert'>No bookings available for the specified agent.</div>";
    }
}


function bookScore($lid, $agent) {
    $ar = array();

    if ($agent == 'all') {
        $query = "SELECT COUNT(*) AS total FROM bookings WHERE lid = :lid AND pstatus = '1'";
        $params = [':lid' => $lid];
    } else {
        $query = "SELECT COUNT(*) AS total FROM bookings WHERE lid = :lid AND refer = :agent AND pstatus = '1'";
        $params = [':lid' => $lid, ':agent' => $agent];
    }

    // Execute the query and fetch the result as a single row
    $row = DB::executeQuery($query, $params, true);
    if ($row) {
        $sold = $row['total']; // Access the 'total' directly from the fetched row
        $lot = Lottery::getLot($lid); // Fetch lottery details

        $ar['sold'] = $sold;
        $ar['amount'] = $sold * $lot['price']; // Ensure 'price' is accessed correctly
    } else {
        // Handle cases where no data is returned or query fails
        $ar['sold'] = 0;
        $ar['amount'] = 0;
    }
    
    return $ar;
}


function selectedAgent($aid){
    $q = DB::query("select * from agents where aid='$aid'");
    $row = $q[0];
    return "<option value='".$row['aid']."'>".$row['name']."</option>";
}

$lot = Lottery::getLot($lid);
$score = bookScore($lid,$ag);


?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sales</h1><br>
          </div><!-- /.col -->

                <div class="col-12">
                    <div class="card top-selling overflow-auto">

                        <div class="card-body pb-0">
                            <h5 class="card-title">Confirmed Sales:  <?php echo $score['sold']; ?><span>| <select id='agents'><?php echo ($ag=='all'?"<option value='all'>All Agents</option>":selectedAgent($ag)); ?><option value="all">All Agents</option><?php Emp::makeAgentLista(); ?></select></span></h5>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered datatable" id="mytable">

                                    <thead>
                                    <tr>
                                        <th>Serial</th>
                                        <th>Coupon</th>
                                        <th>Customer</th>
                                        <th>Phone</th>
                                        <th>Dated</th>
                                        <th>Agent</th>
                                    </tr>
                                    </thead>
                                    <tbody id='tbody'>
                                        <?php show_book_sales($lid,$ag); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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
</script>