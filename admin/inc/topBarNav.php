<html>
<style>
  .main-header {
    background-image: url('<?= validate_image("uploads/default/image.jpg") ?>') !important;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center center;
  }



  .user-img {
    position: absolute;
    height: 27px;
    width: 27px;
    object-fit: cover;
    left: -7%;
    top: -12%;
  }

  .btn-rounded {
    border-radius: 50px;
  }

  .badge-notify {
    background: red;
    position: relative;
    top: -12px;
    right: 10px;
    border-radius: 50%;
    color: white;
    padding: 5px 10px;
    font-size: 12px;
  }
</style>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light shadow text-sm">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars" style="color:white"></i></a>
    </li>
  </ul>
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Notification Bell Icon -->
    <li class="nav-item dropdown ">
      <a class="nav-link" href="#" id="notificationsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell" style="color:white"></i>
        <span id="notificationCount" class="badge badge-notify">0</span>
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationsDropdown">
        <h6 class="dropdown-header">Notifications</h6>
        <div id="notificationsList">
          <!-- Notifications will be appended here -->
        </div>
      </div>
    </li>
    <!-- User Dropdown Menu -->
    <li class="nav-item">
      <div class="btn-group nav-link">
        <button type="button" class="btn btn-rounded badge badge-dark dropdown-toggle dropdown-icon " data-toggle="dropdown">
          <span class="ml-3"><?php echo ucwords($_settings->userdata('username')) ?></span>

        </button>
        <div class="dropdown-menu dropdown-menu-dark" role="menu">
          <a class="dropdown-item" href="<?php echo base_url . 'admin/?page=user' ?>"><span class="fa fa-user"></span> My Account</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo base_url . '/classes/Login.php?f=logout' ?>"><span class="fas fa-sign-out-alt"></span> Logout</a>
        </div>
      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->

<script>
  $(document).ready(function() {
    // Fetch notifications
    function fetchNotifications() {
      $.ajax({
        url: _base_url_ + "classes/Master.php?f=fetch_notifications",
        method: 'GET',
        dataType: 'json',
        success: function(resp) {
          if (resp.status == 'success') {
            var notifications = resp.data;
            var notificationCount = notifications.length;
            $('#notificationCount').text(notificationCount);
            $('#notificationsList').empty();
            notifications.forEach(function(notification) {
              $('#notificationsList').append('<a class="dropdown-item" href="#">' + notification.message + '</a>');
            });
          }
        },
        error: function(err) {
          console.log('Error fetching notifications', err);
        }
      });
    }

    fetchNotifications();
    setInterval(fetchNotifications, 60000); // Fetch notifications every minute


  });
</script>
</script>

</html>