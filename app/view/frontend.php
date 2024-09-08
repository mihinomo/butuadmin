<?php 


if (isset($_GET['about'])) {
    Frontend::updateWebContent('about', $_GET['about']);
}

if (isset($_GET['faq'])) {
    Frontend::updateWebContent('faq', $_GET['faq']);
}

if (isset($_GET['contact'])) {
    Frontend::updateWebContent('contact', $_GET['contact']);
}

if (isset($_POST['sclis'])) {
    Frontend::updateWebContent('waft', htmlspecialchars($_POST['sclis']));
}

if (isset($_GET['privacy'])) {
    Frontend::updateWebContent('privacy', $_GET['privacy']);
}

if (isset($_GET['terms'])) {
    Frontend::updateWebContent('terms', $_GET['terms']);
}

if (isset($_GET['seo'])) {
    Frontend::updateWebContent('seo', $_GET['seo']);
}

if (isset($_GET['seodesc'])) {
    Frontend::updateWebContent('seodesc', $_GET['seodesc']);
}



$r = Frontend::fetchWebContent();

?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Front END</h1><br>
          </div><!-- /.col -->
          <?php require_once("./app/view/extra/webcontent.php"); ?>

        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
</div>   
<script src="https://cdn.tiny.cloud/1/aqv4rvrs6dji1w7ywj6259comw4rkqw7j852qpt2rvjaccxm/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
  tinymce.init({
    selector: 'textarea',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
  });
</script>