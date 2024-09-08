<?php 

if(isset($_COOKIE['login'])){
  $user = $_COOKIE['login'];
  $person = Emp::getAgent($user);
  $roles = Emp::genValidRoutes($person);
}
$store = Store::getStore();
//var_dump($roles);
?>


<style>
.jconfirm-box{
 color:black;
}
  </style>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $store['title']; ?> | Dashboard 2</title>
  <script src="/resources/plugins/jquery/jquery.min.js"></script>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="/resources/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/resources/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/resources/dist/css/adminlte.min.css">
  <!-- ./wrapper -->
  <link rel="stylesheet" href="/resources/plugins/toastr/toastr.min.css">
<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<!-- jQuery -->

  <link rel="stylesheet" href="/resources/dist/jquery-confirm.min.css">
  <script src="/resources/dist/jquery-confirm.min.js"></script>
  <script src="/resources/plugins/toastr/toastr.min.js"></script>
  <script src="/resources/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="/resources/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <script src="/resources/plugins/moment/moment.min.js"></script>
  <script src="/resources/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

  <script type="text/javascript" src="/resources/old/plugins/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="/resources/old/plugins/dataTables.bootstrap.min.js"></script> 
</head>
<body class="hold-transition dark-mode sidebar-collapse text-sm layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Preloader 
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="/resources/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>-->

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <?php Widget::backBtn($backbtn); ?>
      </li>
      
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

