<?php
require_once('../config.php');
?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
<?php require_once('inc/header.php') ?>

<head>
  <link rel="stylesheet" href="<?= base_url ?>plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url ?>plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url ?>dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background-image: url("<?php echo validate_image($_settings->info('cover')) ?>");
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      font-family: 'Roboto', sans-serif;
    }

    .login-container {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      height: 100%;
      width: 100%;
    }

    .header-title {
      margin-top: 100px;
      margin-bottom: 50px;
      text-shadow: 6px 4px 7px black;
      font-size: 3.5em;
      color: #fff4f4 !important;
      background: #8080801c;
    }

    .login-form {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .login-form h1 {
      margin-bottom: 24px;
      font-size: 24px;
      font-weight: 400;
      font-family: 'Poppins', sans-serif;
      color: white;
    }

    .input-group-text {
      background-color: rgba(255, 255, 255, 0.3);
      color: white;
      border: none;
    }

    .form-control {
      background: rgba(255, 255, 255, 0.3);
      color: white;
      border: none;
      border-radius: 0 5px 5px 0;
    }

    .form-control::placeholder {
      color: white
    }

    .btn-primary {
      transition: all .5s ease;
      color: #fff;
      border: 3px solid white;
      border-radius: 5px;
      font-family: 'Montserrat', sans-serif;
      text-transform: uppercase;
      text-align: center;
      line-height: 1;
      font-size: 16px;
      background-color: transparent;
      padding: 10px;
      outline: none;
      border-radius: 10px;
    }

    .btn-primary:hover {
      border: 3px solid;
      color: white;
      background-color: indigo
    }

    .login-form a {
      color: white;
      font-weight: 500;
    }

    .login-form a:hover {
      color: purple
    }

    .login-footer {

      margin-top: 20px;
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="header-title">STUDENT SCHEDULER</div>
    <div class="login-form">
      <h1>Welcome!</h1>

      <form id="login-frm" action="" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" autofocus placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <br>

        <div class="row mb-3">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Log in</button>
          </div>
        </div>
      </form>
      <div class="login-footer">
        <p style="color:azure">Don't have an account?
        <p><a href="./register.php">Register here</a>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="<?= base_url ?>plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url ?>dist/js/adminlte.min.js"></script>

  <script>
    $(document).ready(function() {
      end_loader();
    })
  </script>
</body>

</html>