<?php require_once('../config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
<?php require_once('inc/header.php') ?>
<script>
  start_loader()
</script>
<style>
  html,
  body {
    width: 100%;
    height: 100% !important;
  }

  body {
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
    font-size: 3em;
    font-weight: 500;
    font-family: 'Poppins', sans-serif;
    color: azure;
    margin-top: 50px;
    margin-bottom: 50px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    text-align: center;
  }

  .login-form {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 500px;
  }

  .login-form h1 {
    margin-bottom: 24px;
    font-size: 24px;
    font-weight: 400;
    font-family: 'Poppins', sans-serif;
    color: white;
    text-align: center;
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
    border-radius: 5px;
  }

  .form-control::placeholder {
    color: white;
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
    width: 100%;
  }

  .btn-primary:hover {
    border: 3px solid;
    color: white;
    background-color: indigo;
  }

  .login-form a {
    color: white;
    font-weight: 500;
  }

  .login-form a:hover {
    color: #ccc;
  }

  .login-footer {
    margin-top: 20px;
    text-align: center;
  }

  .login-footer .pass_view {
    background: rgba(255, 255, 255, 0.3);
    color: white;
    border: none;
  }

  .form-group {
    margin-bottom: 1.5rem;
  }

  .form-group label {
    color: white;
    margin-bottom: .5rem;
    display: inline-block;
    font-weight: 500;
    text-align: left;
  }
</style>

<body class="">
  <div class="login-container">
    <div class="header-title">STUDENT SELF MANAGEMENT SYSTEM</div>
    <div class="login-form">
      <h1>Registration</h1>
      <form id="register-form" action="" method="post">
        <input type="hidden" name="id">
        <input type="hidden" name="type" value="2">
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
              <label for="firstname" class="control-label">First Name</label>
              <input type="text" class="form-control form-control-sm" required name="firstname" id="firstname">
            </div>
            <div class="form-group">
              <label for="middlename" class="control-label">Middle Name</label>
              <input type="text" class="form-control form-control-sm" name="middlename" id="middlename">
            </div>
            <div class="form-group">
              <label for="lastname" class="control-label">Last Name</label>
              <input type="text" class="form-control form-control-sm" required name="lastname" id="lastname">
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
              <label for="username" class="control-label">Username</label>
              <input type="text" class="form-control form-control-sm" required name="username" id="username">
            </div>
            <div class="form-group">
              <label for="password" class="control-label">Password</label>
              <div class="input-group input-group-sm">
                <input type="password" class="form-control form-control-sm" required name="password" id="password">
                <button tabindex="-1" class="btn btn-outline-secondary btn-sm pass_view" type="button"><i class="fa fa-eye-slash"></i></button>
              </div>
            </div>
            <div class="form-group">
              <label for="cpassword" class="control-label">Confirm Password</label>
              <div class="input-group input-group-sm">
                <input type="password" class="form-control form-control-sm" required id="cpassword">
                <button tabindex="-1" class="btn btn-outline-secondary btn-sm pass_view" type="button"><i class="fa fa-eye-slash"></i></button>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Create Account</button>
          </div>
        </div>
        <div class="login-footer">
          <p style="color:azure">Already have an account? <a href="./login.php">Login</a></p>
        </div>
      </form>
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
      $('.pass_view').click(function() {
        var input = $(this).closest('.input-group').find('input');
        var type = input.attr('type');
        if (type == 'password') {
          input.attr('type', 'text');
          $(this).find('.fa').removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
          input.attr('type', 'password');
          $(this).find('.fa').removeClass('fa-eye').addClass('fa-eye-slash');
        }
      });

      $('#register-form').submit(function(e) {
        e.preventDefault();
        var _this = $(this);
        var el = $('<div>');
        el.addClass('alert alert-danger err_msg');
        el.hide();
        $('.err_msg').remove();
        if ($('#password').val() != $('#cpassword').val()) {
          el.text('Password does not match');
          _this.prepend(el);
          el.show('slow');
          $('html, body').scrollTop(0);
          return false;
        }
        if (_this[0].checkValidity() == false) {
          _this[0].reportValidity();
          return false;
        }
        start_loader();
        $.ajax({
          url: _base_url_ + "classes/Users.php?f=registration",
          method: 'POST',
          data: new FormData($(this)[0]),
          dataType: 'json',
          cache: false,
          processData: false,
          contentType: false,
          error: err => {
            console.log(err);
            alert('An error occurred');
            end_loader();
          },
          success: function(resp) {
            if (resp.status == 'success') {
              location.replace('./login.php');
            } else if (!!resp.msg) {
              el.html(resp.msg);
              el.show('slow');
              _this.prepend(el);
              $('html, body').scrollTop(0);
            } else {
              alert('An error occurred');
              console.log(resp);
            }
            end_loader();
          }
        });
      });
    });
  </script>
</body>

</html>