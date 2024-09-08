<?php 

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
    $orders = DB::executeQuery("SELECT * FROM orders WHERE status = '1' ORDER BY id DESC", [], false);

    if ($orders) {
        foreach ($orders as $row) {
            $lot = Lottery::getLot($row['lid']);  // Ensure get_lot is refactored to use DB class
            echo "<tr>
                    <td>" . htmlspecialchars(ucfirst($row['id'])) . "</td>
                    <td>" . htmlspecialchars(ucfirst($lot['name'])) . "</td>
                    <td>" . htmlspecialchars($row['serials']) . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['address']) . "</td>
                    <td>" . htmlspecialchars($row['phone']) . "</td>
                    <td class='text-success'>" . htmlspecialchars(Emp::agentName($row['agent'])) . "</td>
                    <td>" . htmlspecialchars($row['otime']) . "</td>
                    <td>
                        <a href='?disapprove=" . htmlspecialchars($row['oid']) . "' class='btn btn-danger m-1' onclick='return confirmAction(\"delete\");'><i class='fa fa-trash'></i></a>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No pending orders found.</td></tr>";
    }
}

function participantList($lid){
  $query = DB::query("select * from books where lid='$lid' order by begin asc");

}

?>


</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Participants</h1><br>
          </div><!-- /.col -->

          <div class="col-12">
            <div class="card recent-sales overflow-auto">

              <div class="row">
                <div class="col-auto">
                  <!-- First select element -->
                  <select class="form-control" style='width:100px;' id='lottery'>
                    <!-- Options for the first select element -->
                    <option value="none"></option>
                    <?php Lottery::getLotteriesOptions(); ?>
                  </select>
                </div>
                <div class="col-auto">
                  <!-- Second select element -->
                  <select class="form-control" style='width:100px;' id='series'>
                    <!-- Options for the second select element -->
                  </select>
                </div>
              </div>
                <div class="card-body">

                <table class="table table-striped bg-white dt-responsive" id="mytable">
                    <thead>
                    <tr>
                        <th scope="col">Sl. No</th>
                        <th scope="col">Book No</th>
                        <th scope="col">Coupon No</th>
                        <th scope="col">Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Payment</th>
                        <th scope="col">Remarks</th>
                        <th scope="col">Selling Agent</th>
                    </tr>
                    </thead>
                    <tbody>
                      
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
<script src="/resources/plugins/datatables/jquery.dataTables.js"></script>
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
function confirmAction(action) {
    var message = "Are you sure you want to " + action + " this order?";
    return confirm(message);
}

$(document).ready(function(){
    var dataTable;
    // When the value of the first select changes
    $('#lottery').change(function(){
        var lotteryId = $(this).val(); // Get the selected lottery ID
        if (lotteryId !== 'none') {
            // Make an AJAX request
            $.ajax({
                url: '/api/lotser/', // URL to your script to fetch series data
                type: 'GET',
                data: {lotteryId: lotteryId}, // Send the selected lottery ID as data
                success: function(response){
                    // Update the options of the second select with the received data
                    $('#series').html(response);
                },
                error: function(xhr, status, error){
                    console.error(xhr.responseText);
                }
            });
        } else {
            // If 'none' selected, clear the options of the second select
            $('#series').html('');
        }
    });

    function initializeDataTable() {

        $("#mytable").DataTable({
            responsive: true,
            "paging": false,  // Disable pagination
            "ordering": false,
            lengthChange: false,
            autoWidth: false,
            pageLength: 50,
            buttons: [
                "copy",
                "csv",
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
                "excel",
                "print"
            ]
        }).buttons().container().appendTo('#mytable_wrapper .col-md-6:eq(0)');
    }

    // Initialize DataTable when the document is ready
    initializeDataTable();

    $('#series').change(function(){
        var seriesId = $(this).val(); // Get the selected series ID
        var lotteryId = $('#lottery').val(); // Get the selected lottery ID
        
        if (seriesId !== 'none') {
            // Make an AJAX request to fetch data
            $.ajax({
                url: '/api/lotbook/', // Ensure the URL is correct
                type: 'GET',
                data: {lid: lotteryId, agent: 'none', series: seriesId},
                dataType: 'json',
                success: function(data) {
                    if ($.fn.DataTable.isDataTable('#mytable')) {
                        $('#mytable').DataTable().destroy();
                    }
                    console.log(data);  // Log data to console for debugging
                    var html = '';
                    data.forEach(function(book) {
                        // First row for the book
                        html += '<tr>';
                        html += '<td>' + book.sl + '</td>';
                        html += '<td>' + book.bookno + '</td>';
                        html += '<td></td>';
                        html += '<td></td>';
                        html += '<td></td>';
                        html += '<td></td>';
                        html += '<td></td>';
                        html += '<td></td>';
                        html += '<td></td>';
                        html += '</tr>';

                        // Rows for each serial in the book's range
                        for (let serial = parseInt(book.begin); serial <= parseInt(book.end); serial++) {
                            let entry = book.bookEntries.find(e => parseInt(e.serial) === serial);
                            if (entry) {
                                // Booked serial
                                html += '<tr>';
                                html += '<td></td>'; // Leave first two columns empty
                                html += '<td></td>';
                                html += '<td>' + entry.serial + '</td>';
                                html += '<td>' + (entry.name ? entry.name : '-') + '</td>';
                                html += '<td>' + (entry.address ? entry.address : '-') + '</td>';
                                html += '<td>' + (entry.phone ? entry.phone : '-') + '</td>';
                                html += '<td>' + (entry.status ? entry.status : 'Available') + '</td>';
                                html += '<td>-</td>';
                                html += '<td>' + (entry.agentName ? entry.agentName : '-') + '</td>';
                                html += '</tr>';
                            } else {
                                // Unbooked serial
                                html += '<tr>';
                                html += '<td></td>'; // Leave first two columns empty
                                html += '<td></td>';
                                html += '<td>' + serial + '</td>'; // Show serial number
                                html += '<td>-</td>'; // Remaining columns empty
                                html += '<td>-</td>';
                                html += '<td>-</td>';
                                html += '<td>-</td>'; // Status as Available
                                html += '<td>-</td>';
                                html += '<td>-</td>';
                                html += '</tr>';
                            }
                        }
                    });
                    
                    $('#mytable tbody').html(html);
                    
                    //console.log(dataTable);
                    
                    initializeDataTable(); // Reinitialize DataTable
                },
                error: function(err) {
                    console.error('Error loading data:', err);
                    $('#mytable tbody').html('<tr><td colspan="8">Error loading data.</td></tr>');
                    if ($.fn.DataTable.isDataTable('#mytable')) {
                        $('#mytable').DataTable().destroy(false);
                    }
                }
            });
        } else {
            // If 'none' selected, clear the table
            $('#mytable tbody').html('');
            // Destroy existing DataTable instance if it exists
            if ($.fn.DataTable.isDataTable('#mytable')) {
            $('#mytable').DataTable().destroy(false);
        }
        }
    });


});



// reInitTable function definition
function reInitTable() {
    if ($.fn.DataTable.isDataTable('#mytable')) {
        $('#mytable').DataTable().draw(); // Refresh DataTable
    }
}



</script>
