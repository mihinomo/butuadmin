<?php 

if(isset($_COOKIE['login'])){
  $store = Store::getStore();
  if(empty($store)){
    $stype = '';
  }else{
    $stype = $store['type'];
  }
  $user = $_COOKIE['login'];
  $person = Emp::getAgent($user);
  $roles = Emp::genValidRoutes($person);
}
//var_dump($roles);
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Arunachal Lucky Draw</title>
  <script src="/resources/plugins/jquery/jquery.min.js"></script>
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="/resources/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/resources/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->

  <link rel="stylesheet" href="/resources/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="/resources/dist/jquery-confirm.min.css">
  <script src="/resources/dist/jquery-confirm.min.js"></script>
  <!-- ./wrapper -->
  
  <link rel="manifest" href="/manifest.json">
  <script>
    if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
      navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
        console.log('ServiceWorker registration successful with scope: ', registration.scope);
      }, function(err) {
        console.error('ServiceWorker registration failed: ', err);
      });
    });
  }
  </script>
<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="/resources/plugins/jquery/jquery.min.js"></script>
</head>
<body class="hold-transition dark-mode sidebar-collapse text-sm layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="/resources/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item d-none d-sm-inline-block">
          <a class="nav-link"><i class="fas fa-circle text-success"></i> <?php Store::getMode($stype); ?></a>
      </li>
      <?php if($user=='112233'){?>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/rinter/" class="nav-link"><i class="fas fa-wrench"></i> Settings</a>
      </li>
      <?php } ?>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="" class="nav-link"><i class="fas fa-plug"></i> Plugins</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard/" class="brand-link">
      <img src="/resources/logo.png" alt="Apatani Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Arunachal Lucky Draw</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="/dashboard/" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <?php if($roles['lot']=='1'){?>
          <li class="nav-item">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-window-restore"></i>
              <p>
                Lotteries
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/addnewlot/" class="nav-link">
                  <i class="fa fa-plus nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/lotteries/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Active Lotteries</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/deactivelot/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Deactive Lotteries</p>
                </a>
              </li>
            </ul>
          </li>
          <?php } ?>
          <?php if($roles['orders']=='1'){?>
          <li class="nav-item">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>
                Orders
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/activeorders/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Active Orders</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/revieworders/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reviewed Orders</p>
                </a>
              </li>
              
            </ul>
          </li>
          <?php } ?>
		  <li class="nav-item">
			<a href="/confirmorders/" class="nav-link">
			  <i class="far fa-circle nav-icon"></i>
			  <p>Participation List</p>
			</a>
		  </li>
          <?php if($roles['accounting']=='1'){?>
          <li class="nav-item">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Accounting
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/journal/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Journal</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/ledger/?r2=all" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ledger</p>
                </a>
              </li>
            </ul>
          </li>
          <?php } ?>
          <?php if($roles['updates']=='1'){?>
          <li class="nav-item">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Updates
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/announcement/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Announcement</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/result/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Result</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/feedback/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Feedback</p>
                </a>
              </li>
            </ul>
          </li>
          <?php } ?>
          <?php if($roles['frontend']=='1'){?>
          <li class="nav-item">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Frontend
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/frontend/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Front Content</p>
                </a>
              </li>
            </ul>
          </li>
          <?php } ?>
          <?php if($roles['admin']=='1'){?>
          <li class="nav-item">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Settings
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/whatsapp/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Whatsapp</p>
                </a>
              </li>
            </ul>
          </li>
          <?php } ?>
          
          <?php if($roles['hrm']=='1'){?>
          <li class="nav-item">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                HRM
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <li class="nav-item">
                <a href="/agents/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Staff List</p>
                </a>
              </li>
            </ul>
          </li>
          <?php } ?>
          <?php if($roles['hrm']=='1'){?>
            <li class="nav-item">
            <a href="?logout" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Gallery
                
              </p>
            </a>
          </li>
        <?php } ?>

          <li class="nav-item">
            <a href="?logout" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Logout
                <span class="right badge badge-danger">END</span>
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
