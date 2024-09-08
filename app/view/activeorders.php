<?php 
if (isset($_GET['approve'])) {
    $app = $_GET['approve'];  // Direct use, real escaping is handled by prepared statements

    // Update orders status to 'await'
    $sqlOrders = "UPDATE orders SET status = 'await' WHERE oid = :oid";
    $updateOrders = DB::executeQuery($sqlOrders, [':oid' => $app]);

    // Update bookings status to 'await'
    $sqlBookings = "UPDATE bookings SET pstatus = 'await' WHERE oid = :oid";
    $updateBookings = DB::executeQuery($sqlBookings, [':oid' => $app]);

    // Check if both updates were successful
    if ($updateOrders && $updateBookings) {
        Widget::jsAlert("Successfully Approved", "/activeorders/");
    } else {
        Widget::justalert("Database Error");
    }
}
if (isset($_GET['disapprove'])) {
    $app = $_GET['disapprove']; // Direct use, real escaping is handled by prepared statements

    // Delete from orders
    $sqlDeleteOrders = "DELETE FROM orders WHERE oid = :oid";
    $deleteOrders = DB::executeQuery($sqlDeleteOrders, [':oid' => $app]);

    // Delete from bookings
    $sqlDeleteBookings = "DELETE FROM bookings WHERE oid = :oid";
    $deleteBookings = DB::executeQuery($sqlDeleteBookings, [':oid' => $app]);

    // Check if the delete was successful
    if ($deleteOrders && $deleteBookings) {
        Widget::jsAlert("Successfully Removed", "/activeorders/");
    } else {
        Widget::justalert("Database Error");
    }
}

function showOrderPending() {
    // Assuming the orders are fetched using a DB class method or similar
    $orders = DB::executeQuery("SELECT * FROM orders WHERE status = '0' ORDER BY id DESC", [], false);

    if ($orders) {
        foreach ($orders as $row) {
            $lot = Lottery::getLot($row['lid']);  // Ensure get_lot is refactored to use DB class
            echo "<tr>
                    <td>" . htmlspecialchars(ucfirst($row['id'])) . "</td>
                    <td>" . htmlspecialchars(ucfirst($lot['name'])) . "</td>
                    <td>" . htmlspecialchars($row['serials']) . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['address']) . "</td>
                    <td><a href='https://wa.me/+91" . htmlspecialchars($row['phone']) . "' class='text-primary'>" . htmlspecialchars($row['phone']) . "</a></td>
                    <td class='text-success'>" . htmlspecialchars(Emp::agentName($row['agent'])) . "</td>
                    <td>" . htmlspecialchars($row['otime']) . "</td>
                    <td><a href='?approve=" . htmlspecialchars($row['oid']) . "' class='btn btn-success' onclick='return confirmAction(\"approve\");' style='margin-right:1rem;'>Mark</a>
                        <a href='?disapprove=" . htmlspecialchars($row['oid']) . "' class='btn btn-danger m-1' onclick='return confirmAction(\"delete\");'><i class='fa fa-trash'></i></a>
                    </td>
                </tr>";
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
<!-- DataTables -->
<link rel="stylesheet" href="/resources/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="/resources/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="/resources/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<!-- DataTables  & Plugins -->
<script src="/resources/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/resources/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/resources/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/resources/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/resources/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/resources/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/resources/plugins/jszip/jszip.min.js"></script>
<script src="/resources/plugins/pdfmake/pdfmake.min.js"></script>
<script src="/resources/plugins/pdfmake/vfs_fonts.js"></script>
<script src="/resources/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/resources/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/resources/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script>
$(function () {
    $("#mytable").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        pageLength: 50,
        buttons: [
            "copy", 
            "csv", 
            "excel", 
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                orientation: 'landscape',
                pageSize: 'A3',
                customize: function (doc) {
                    doc.styles.tableHeader.fontSize = 6;
                    doc.styles.tableBodyEven.fontSize = 6;
                    doc.styles.tableBodyOdd.fontSize = 6;

                    // Correct handling of page margins
                    var pageMargins = doc.margins || {left: 10, right: 10}; // Default margins if not set
                    var pageWidth = doc.pageSize.width ? doc.pageSize.width : 841.89; // Default width for A4 landscape, if not set

                    // Assuming a 10 pixel margin approximation on both sides
                    var usableWidth = pageWidth - pageMargins.left - pageMargins.right;

                    // Adjust table widths to fit within the usable width
                    // Assuming even distribution for simplicity, adjust as necessary
                    var columnWidths = new Array(doc.content[1].table.body[0].length).fill(usableWidth / doc.content[1].table.body[0].length);
                    doc.content[1].table.widths = columnWidths;
                }
            },
            "print", 
            "colvis"
        ]
    }).buttons().container().appendTo('#mytable_wrapper .col-md-6:eq(0)');
});


function confirmAction(action) {
    var message = "Are you sure you want to " + action + " this order?";
    return confirm(message);
}
</script>
