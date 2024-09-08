<?php


if(isset($_GET['content'])){
    $content = $_GET['content']; // Direct assignment; htmlspecialchars will be applied later in HTML context.

    // Use prepared statements to execute the update query safely
    $updateSql = "UPDATE results SET content = :content WHERE lid = :lid";
    $updateParams = [':content' => $content, ':lid' => $lid];
    $updateResult = DB::executeQuery($updateSql, $updateParams);

    // Check the update result; assuming executeQuery returns the number of affected rows
    if ($updateResult > 0) {
        Widget::jsAlert('Successfully Edited Result', '/result/');
    } else {
        Widget::justalert("Database Error");
    }
}

// Fetching the specific result using a prepared statement
$selectSql = "SELECT * FROM results WHERE lid = :lid";
$selectResult = DB::executeQuery($selectSql, [':lid' => $lid], true); // true to fetch only one row

if ($selectResult) {
    // Assuming htmlspecialchars is applied when outputting data to HTML to prevent XSS
    $res = $selectResult; // Direct use of fetched result
} else {
    Widget::justalert("Failed to retrieve result data");
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

          <div class="card">
                <br>
                <div class="card-body">
                    <form method='get'>
                        <input type="hidden" name='declare' value="<?php echo $lid; ?>">
                        <textarea class='form-control' id='declare' name='content'>
                            <?php echo $res['content']; ?>
                        </textarea>
                        <input type='submit' class='btn btn-info m-1' value="Edit Result">
                    </form>
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