<?php 
if (isset($_POST['name'])) {
    // Initialize the data from POST, escaping is handled by prepared statements later.
    $today = date("Y-m-d h:i:s a");
    $name = $_POST['name'];
    $price = $_POST['price'];
    $com = $_POST['com'];
    $ddate = $_POST['ddate'];
    $des = $_POST['des'];
    $wssp = $_POST['wssp'];
    $venue = $_POST['venue'];
    $dtime = $_POST['ddtime'];
    $tarPOST_dir = "images/";
    $image = $tarPOST_dir . basename($_FILES["image"]["name"]);
    $imageb = $tarPOST_dir . basename($_FILES["imageb"]["name"]);

    // Move uploaded files.
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $image)) {
        if (move_uploaded_file($_FILES["imageb"]["tmp_name"], $imageb)) {
            $lid = uniqid();

            // Use prepared statements to insert data securely
            $query = "INSERT INTO lotteries (id, name, added, price, refers, descr, lid, image, image2, ddate, status, venue, whatsapp, dtime)
                      VALUES (NULL, :name, :today, :price, :com, :des, :lid, :image, :imageb, :ddate, '1', :venue, :wssp, :dtime)";
            $params = [
                ':name' => $name,
                ':today' => $today,
                ':price' => $price,
                ':com' => $com,
                ':des' => $des,
                ':lid' => $lid,
                ':image' => $image,
                ':imageb' => $imageb,
                ':ddate' => $ddate,
                ':venue' => $venue,
                ':wssp' => $wssp,
                ':dtime' => $dtime
            ];
            $result = DB::insert($query, $params);

            if($result){
                Widget::jsAlert("Successfully Added lottery","/lotteries/");
            } else {
                Widget::alertGreen("Error");
            }
        }
    }
}




// if(DB::insert($query, $params)){
//     Widget::alertRed("Continue","Successfully Added lottery","/lotteries/");
// } else {
//     Widget::alertGreen("Error");
// }
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->







                <div class="card m-2">
                    <div class="card-body">

                        <!-- Multi Columns Form -->
                        <form class="row g-3" method='post' enctype="multipart/form-data">
                        <div class="col-md-12">
                            <label for="inputName5" class="form-label">Lottery Name</label>
                            <input type="text" class="form-control" name='name' required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputEmail5" class="form-label">Price</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Rs</span>
                                <input type="tel" class="form-control" name='price' required>
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inputPassword5" class="form-label">Commission</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Rs</span>
                                <input type="tel" class="form-control" name='com' required>
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inputPassword5" class="form-label">Whatsapp</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bi bi-whatsapp'></i></span>
                                <input type="tel" class="form-control" name='wssp' required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inputPassword5" class="form-label">Venue</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bi bi-house'></i></span>
                                <input type="text" class="form-control" name='venue' required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="inputAddress5" class="form-label">Image Front</label>
                            <input type="file" class="form-control" name='image' required>
                        </div>
                        <div class="col-12">
                            <label for="inputAddress5" class="form-label">Image Back</label>
                            <input type="file" class="form-control" name='imageb' required>
                        </div>
                        <div class="col-12">
                            <label for="inputDate" class="col-sm-2 col-form-label">Draw Date</label>
                            <input type="date" class="form-control" name='ddate' required>
                        </div>
                        <div class="col-12">
                            <label for="inputDate" class="col-sm-2 col-form-label">Draw Time</label>
                            <input type="datetime-local" id='miika' class="form-control" name='ddtime' required>
                        </div>
                        <div class="col-12">
                            <label for="inputPassword" class="col-sm-2 col-form-label">Description</label>
                            <textarea id="desc" class="form-control" name='des'></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="gridCheck">
                            <label class="form-check-label" for="gridCheck">
                                I Have Re-checked Proceed
                            </label>
                            </div>
                        </div>
                        <div class="text-center" id='vis' style='display:none;'>
                            <button type="submit" class="btn btn-primary" id='submit'>Submit</button>
                        </div>
                        </form><!-- End Multi Columns Form -->

                    </div>
                </div>




            </div>
        </div>
    </div>
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
$(document).ready(function () {
    $("#gridCheck").change(function () {
        if($(this).is(":checked")) {
            $('#vis').css('display','block');         
        }else{
            $('#vis').css('display','none'); 
        }
    });
});
</script>