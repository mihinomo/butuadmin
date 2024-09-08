<!-- jQuery -->
<script src="/resources/plugins/jquery/jquery.min.js"></script>
<link rel="stylesheet" href="/resources/dist/jquery-confirm.min.css">
<script src="/resources/dist/jquery-confirm.min.js"></script>
<?php 

if(isset($_POST['user'])){
    $user = $_POST['user'];
    $pass = $_POST['password'];
    Auth::ValidateUser($user,$pass);
    
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MIN Pos|| Welcome</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/resources/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="/resources/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/resources/dist/css/adminlte.min.css">
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
</head>
<body class="hold-transition login-page" style='background:#888887;'>
<div class="login-box">
  <div class="login-logo">
    <a href=""><b>Arunachal</b> Lucky Draw</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body" style='background:#65628b;'>
      <p class="login-box-msg text-white">Sign in to start your session</p>

      <form method="post" autocomplete="off">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name='user' placeholder="User ID">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name='password' placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row justify-center">
          
          <!-- /.col -->
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-secondary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->


<!-- Bootstrap 4 -->
<script src="/resources/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/resources/dist/js/adminlte.min.js"></script>
</body>
</html>
