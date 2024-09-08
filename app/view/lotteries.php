
<?php 
// Function to handle deletions, enabling, and disabling of lotteries

if(isset($_GET['del'])){
    $del = $_GET['del'];
    if(DB::insert("delete from lotteries where id='$del'")){
        Widget::jsAlert("Successful","/lotteries/");
    }else{
        Widget::jsAlert("Database Error","/lotteries/");
    }
}
if(isset($_GET['enb'])){
    $enb = $_GET['enb'];
    if(DB::insert("update lotteries set active='1' where lid='$enb'")){
        Widget::jsAlert("Successful","/lotteries/");
    }else{
        Widget::jsAlert("Database Error","/lotteries/");
    }
}
if(isset($_GET['dis'])){
    $enb = $_GET['dis'];
    if(DB::insert("update lotteries set active='0' where lid='$enb'")){
        Widget::jsAlert("Successful","/lotteries/");
    }else{
        Widget::jsAlert("Database Error","/lotteries/");
    }
}

// Function to display lotteries
function show_lotteries() {
    // Modified query to optimize performance
    $sql = "SELECT l.*, 
                   (SELECT COUNT(*) FROM bookings b WHERE b.lid = l.lid AND b.pstatus = '1') AS sold_count, 
                   (SELECT COUNT(*) FROM books bk WHERE bk.lid = l.lid) AS book_count 
            FROM lotteries l
            WHERE l.status = '1'";

    // Execute the query using the executeQuery method
    $data = DB::executeQuery($sql, [], false); // False because we expect multiple rows

    if ($data) {
        foreach ($data as $query) {
            echo '<tr>
                    <th scope="row"><a href="#"><img src="' . htmlspecialchars($query['image']) . '" alt=""></a></th>
                    <td><a href="#" class="text-primary fw-bold">' . ucfirst(htmlspecialchars($query['name'])) . '</a></td>
                    <td class="fw-bold">₹' . htmlspecialchars($query['price']) . '</td>
                    <td class="fw-bold">₹' . htmlspecialchars($query['refers']) . '</td>
                    <td>' . make_edbutton($query['active'], $query['lid']) . '</td>
                    <td>' . $query['sold_count'] . ' | ' . $query['book_count'] . '</td>
                    <td><a href="/sales/' . htmlspecialchars($query['lid']) . '/?agent=all">View Sales</a></td>
                    <td class="fw-bold">' . htmlspecialchars($query['venue']) . '</td>
                    <td><a href="/declare/' . htmlspecialchars($query['lid']) . '/">Declare</a></td>
                    <td><a onclick="del(' . htmlspecialchars($query['id']) . ');" style="margin-right:1rem;"><i class="fa fa-trash"></i></a><a href="/showbooks/' . htmlspecialchars($query['lid']) . '/" style="margin-right:1rem;"><i class="fa fa-eye"></i></a><a href="/editlottery/' . htmlspecialchars($query['lid']) . '/"><i class="fa fa-edit"></i></a></td>
                </tr>';
        }
    } else {
        // Handle case where no data was returned or there was an error
        echo "<tr><td colspan='10'>No data available or error in data retrieval.</td></tr>";
    }
}




function make_edbutton($val, $lid) {
    if ($val == "1") {
        // Returns a button to disable the lottery
        return '<a href="?dis=' . $lid . '" class="btn btn-success">Enable</a>';
    } else {
        // Returns a button to enable the lottery
        return '<a href="?enb=' . $lid . '" class="btn btn-danger">Disable</a>';
    }
}


// Function to count books
function countBooks($lid) {
    $data = DB::query("SELECT COUNT(*) AS count FROM books WHERE lid = :lid", [':lid' => $lid]);
    return $data[0]['count'] ?? 0;  // Similar null coalescence
}

?>


<div class="col-12">
    <div class="card top-selling overflow-auto">

        <div class="card-body pb-0">
            <h5 class="card-title">Active Lotteries <span>| Today</span></h5>

            <table class="table table-striped dt-responsive" id='mytable'>
            <thead>
                <tr>
                    <th scope="col">Preview</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Commission</th>
                    <th scope="col">Disable/Enable</th>
                    <th scope="col">Sold</th>
                    <th scope="col">Sales</th>
                    <th scope="col">Venue</th>
                    <th scope="col">Result</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php show_lotteries(); ?>
            </tbody>
            </table>

        </div>

    </div>
</div><!-- End Top Selling -->
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
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "pageLength": 50,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#mytable_wrapper .col-md-6:eq(0)');
    
  });
function del(a){
    if (confirm("Are you sure you want to delete this entry? This action cannot be undone.")) {
        window.location.replace('?del=' + a);
    }
}
    
    
</script>