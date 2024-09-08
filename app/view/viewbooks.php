<?php 

if(isset($_GET['agent'])){
    $ag = $_GET['agent'];
}else{
    $ag = 'all';
}
if (isset($_GET['delbook'])) {
    // Use parameterized queries to prevent SQL injection
    $book = $_GET['delbook']; // Direct use in the query will be replaced by a placeholder in the prepared statement
    $sql = "DELETE FROM books WHERE id = :book";
    $params = [':book' => $book];

    // Execute the query using the executeQuery method
    $result = DB::executeQuery($sql, $params);

    // Check the result; executeQuery should be modified to return the number of affected rows for DELETE statements
    if ($result > 0) {
        $link = "show.php?lid=" . $lid;  // Ensure $lid is defined and sanitized appropriately
        Widget::jsAlert("Successfully Deleted Book", $link);
    } else {
        // If no rows were affected, handle it as an error or as a "not found" situation
        echo '<script>alert("Database Error or No Book Found to Delete");</script>';
    }
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
            <h1 class="m-0">View Books - <?php echo $lot['name']; ?></h1><br>
          </div><!-- /.col -->

                <div class="col-12">
                    <div class="card top-selling overflow-auto">

                        <div class="card-body pb-0">
                            <h5 class="card-title"> <select id='agents'><?php echo ($ag=='all'?"<option value='all'>All Agents</option>":selectedAgent($ag)); ?><option value="all">All Agents</option><?php Emp::makeAgentListp(); ?></select></span>
                            <?php echo ucfirst($lot['name']); ?></h5><a href="/addbook/<?php echo $lid; ?>/" class="btn btn-info" style="position:absolute; right:10px; top:10px;">Add Book</a>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">

                                <div class="card">
                                    <div class="card-body">
                                        
                                            <?php Book::make_book_seq($lid,$ag); ?>

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
    function del(a){
        if (confirm("Are you sure you want to delete this entry? This action cannot be undone.")) {
            window.location.replace('?delbook=' + a);
        }
    }
</script>