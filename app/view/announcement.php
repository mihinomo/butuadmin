<?php 
if(isset($_GET['new'])){
    $description = $_GET['des'];  // Direct use, real escaping is handled by prepared statements
    $dated = date("F d Y");

    if(!empty($description)){
        $sql = "INSERT INTO announcements (description, status, dated) VALUES (:description, '1', :dated)";
        $params = [':description' => $description, ':dated' => $dated];
        $result = DB::executeQuery($sql, $params);

        if($result){
            Widget::jsAlert("Successfully Added", "announcements.php");
        } else {
            Widget::justalert("Database Error");
        }
    } else {
        Widget::justalert("Please Add description");
    }
}
if(isset($_GET['del'])){
    $del = $_GET['del']; // Assume direct use, prepared statements handle safety

    $sql = "DELETE FROM announcements WHERE id = :id";
    $params = [':id' => $del];
    $result = DB::executeQuery($sql, $params);

    if($result){
        Widget::jsAlert("Successfully Deleted", "announcements.php");
    } else {
        Widget::justalert("Database Error");
    }
}
function show_ann() {
    $sql = "SELECT * FROM announcements ORDER BY id DESC";
    $announcements = DB::executeQuery($sql, [], false);

    if ($announcements) {
        foreach ($announcements as $row) {
            echo '<tr>
                    <td><a href="#" class="text-primary fw-bold">AN#' . htmlspecialchars($row['id']) . '</a></td>
                    <td class="fw-bold">' . htmlspecialchars($row['description']) . '</td>
                    <td><a href="?del=' . htmlspecialchars($row['id']) . '" style="margin-right:1rem;"><i class="fa fa-trash"></i></a></td>
                  </tr>';
        }
    } else {
        echo "<tr><td colspan='3'>No announcements found.</td></tr>";
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
                <div class="col-6">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Description</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php show_ann(); ?>
                        </tbody>
                    </table>
                </div>

                    <div class="col-6">
                            <div class="card top-selling overflow-auto">
                                <form>
                                    <h4>Add New Announcement</h4>
                                    <div class="col-12 p-2">
                                        <label for="inputPassword" class="col-sm-2 col-form-label">Description</label>
                                        <textarea id="desc" class="form-control" name='des'></textarea>
                                    </div>
                                    <div class="col-12 p-2">
                                        <input type='submit' name='new' value='Create New' class='btn btn-primary'>
                                    </div>
                                </form>
                            </div>
                    </div>

        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
</div>   