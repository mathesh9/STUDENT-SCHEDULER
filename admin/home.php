<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;

    }

    .status-container {
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .status-text {
      display: flex;
      align-items: center;
      padding: 3px;
      border-radius: 5px;
      font-size: 14px;
      margin-left: 15px;

    }

    .status-dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      margin-right: 10px;
      cursor: pointer;
    }

    .status-dropdown {
      display: none;
      position: absolute;
      top: 0;
      left: 0;
      transform: translateX(15px);
      width: 100%;
      z-index: 1;
      background: darkblue;
      border: 1px solid blueviolet;
      border-radius: 5px;
      padding: 0;
      margin: 0;
    }

    .status-dropdown select {
      text-align: center;
      width: 100%;
      border: 5px;
      border-radius: 10px;
      padding: 1px;
      font-size: 14px;
      background-color: aliceblue;

    }

    .table {
      width: 100%;
      margin-bottom: 1rem;
      color: #212529;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      overflow: hidden;
      font-family: 'Roboto', sans-serif;
    }

    .table th,
    .table td {
      padding: 1rem;
      vertical-align: top;
      border: 1px solid #dee2e6;
    }

    .table thead th {
      vertical-align: bottom;
      border-bottom: 2px solid #dee2e6;
      background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
      color: #ffffff;
      text-transform: uppercase;
    }

    .table-hover tbody tr:hover {
      color: #212529;
      background-color: rgba(0, 0, 0, 0.075);
      transition: background-color 0.3s ease;
    }

    .table-bordered th,
    .table-bordered td {
      border: 1px solid #dee2e6;
    }

    .table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(0, 0, 0, 0.05);
    }

    .table thead th {
      text-align: center;
      background-color: #007bff;
    }

    .table tbody tr td {
      text-align: center;
    }

    h3 {
      margin-top: 1rem;
      margin-bottom: 1rem;
      color: #343a40;
      font-weight: 300;
    }

    #today-tasks-table,
    #upcoming-tasks-table {
      margin-top: 2rem;
      display: none;
    }

    .info-box {
      cursor: pointer;
      transition: transform 0.3s ease;
    }

    .info-box:hover {
      transform: scale(1.05);
    }

    .task-status {
      width: 100%;
      margin: 0 auto;
    }

    .table-container {
      padding: 20px;
      background: #f1f1f1;
      border-radius: 8px;
    }
  </style>
</head>

<body>
  <h1>Welcome, <?php echo $_settings->userdata('username') ?>!</h1>
  <hr>
  <div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
      <div class="info-box" id="today-tasks-icon">
        <span class="info-box-icon bg-gradient-navy elevation-1"><i class="fas fa-calendar-day"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Today's Scheduled Tasks</span>
          <span class="info-box-number text-right">
            <?php
            $schedule = $conn->query("SELECT * FROM schedule_list where '" . date('Y-m-d') . "' BETWEEN date(schedule_from) and date(schedule_to) " . ($_settings->userdata('type') != 1 ? " and user_id = '{$_settings->userdata('id')}'" : ""))->num_rows;
            echo format_num($schedule);
            ?>
          </span>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
      <div class="info-box" id="upcoming-tasks-icon">
        <span class="info-box-icon bg-gradient-warning elevation-1"><i class="fas fa-calendar"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Upcoming Scheduled Tasks</span>
          <span class="info-box-number text-right">
            <?php
            $schedule = $conn->query("SELECT * FROM schedule_list where unix_timestamp(date(schedule_from)) > '" . strtotime(date('Y-m-d')) . "' " . ($_settings->userdata('type') != 1 ? " and user_id = '{$_settings->userdata('id')}'" : ""))->num_rows;
            echo format_num($schedule);
            ?>
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Fetch today's tasks -->
  <?php
  $today = date('Y-m-d');
  $todays_tasks = $conn->query("SELECT s.*, c.name as category_name FROM schedule_list s LEFT JOIN category_list c ON s.category_id = c.id WHERE '$today' BETWEEN date(s.schedule_from) and date(s.schedule_to)" . ($_settings->userdata('type') != 1 ? " and s.user_id = '{$_settings->userdata('id')}'" : ""));

  function getStatusColor($status)
  {
    switch ($status) {
      case 'Not Started':
        return '#ff0000'; // Red
      case 'In Progress':
        return '#007bff'; // Blue
      case 'Completed':
        return '#28a745'; // Green
      default:
        return '#6c757d'; // Gray
    }
  }
  ?>

  <!-- Fetch upcoming tasks -->
  <?php
  $upcoming_tasks = $conn->query("SELECT s.*, c.name as category_name FROM schedule_list s LEFT JOIN category_list c ON s.category_id = c.id WHERE unix_timestamp(date(s.schedule_from)) > '" . strtotime(date('Y-m-d')) . "'" . ($_settings->userdata('type') != 1 ? " and s.user_id = '{$_settings->userdata('id')}'" : ""));
  ?>

  <div id="today-tasks-table" class="table-container" style="display: none;">
    <h3>Today's Scheduled Tasks</h3>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Title</th>
          <th>Description</th>
          <th>Category</th>
          <th>Start</th>
          <th>End</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($task = $todays_tasks->fetch_assoc()) : ?>
          <tr>
            <td><?php echo $task['title']; ?></td>
            <td><?php echo $task['description']; ?></td>
            <td><?php echo $task['category_name']; ?></td>
            <td><?php echo $task['schedule_from']; ?></td>
            <td><?php echo $task['schedule_to']; ?></td>
            <td>
              <div class="status-container">
                <span class="status-dot" style="background-color: <?php echo getStatusColor($task['status']); ?>"></span>
                <div class="status-text" data-task-id="<?php echo $task['id']; ?>">
                  <span><?php echo $task['status']; ?></span>
                </div>
                <div class="status-dropdown">
                  <select class="form-control task-status" data-task-id="<?php echo $task['id']; ?>">
                    <option value="Not Started" <?php echo $task['status'] == 'Not Started' ? 'selected' : ''; ?>>Not Started</option>
                    <option value="In Progress" <?php echo $task['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Completed" <?php echo $task['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                  </select>
                </div>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div id="upcoming-tasks-table" class="table-container" style="display: none;">
    <h3>Upcoming Scheduled Tasks</h3>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Title</th>
          <th>Description</th>
          <th>Category</th>
          <th>Start</th>
          <th>End</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($task = $upcoming_tasks->fetch_assoc()) : ?>
          <tr>
            <td><?php echo $task['title']; ?></td>
            <td><?php echo $task['description']; ?></td>
            <td><?php echo $task['category_name']; ?></td>
            <td><?php echo $task['schedule_from']; ?></td>
            <td><?php echo $task['schedule_to']; ?></td>
            <td>
              <div class="status-container">
                <span class="status-dot" style="background-color: <?php echo getStatusColor($task['status']); ?>"></span>
                <div class="status-text" data-task-id="<?php echo $task['id']; ?>">
                  <span><?php echo $task['status']; ?></span>
                </div>
                <div class="status-dropdown">
                  <select class="form-control task-status" data-task-id="<?php echo $task['id']; ?>">
                    <option value="Not Started" <?php echo $task['status'] == 'Not Started' ? 'selected' : ''; ?>>Not Started</option>
                    <option value="In Progress" <?php echo $task['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Completed" <?php echo $task['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                  </select>
                </div>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <script>
    document.getElementById('today-tasks-icon').addEventListener('click', function() {
      var todayTasksTable = document.getElementById('today-tasks-table');
      var upcomingTasksTable = document.getElementById('upcoming-tasks-table');
      if (todayTasksTable.style.display === 'none') {
        todayTasksTable.style.display = 'block';
        upcomingTasksTable.style.display = 'none';
      } else {
        todayTasksTable.style.display = 'none';
      }
    });

    document.getElementById('upcoming-tasks-icon').addEventListener('click', function() {
      var upcomingTasksTable = document.getElementById('upcoming-tasks-table');
      var todayTasksTable = document.getElementById('today-tasks-table');
      if (upcomingTasksTable.style.display === 'none') {
        upcomingTasksTable.style.display = 'block';
        todayTasksTable.style.display = 'none';
      } else {
        upcomingTasksTable.style.display = 'none';
      }
    });

    document.querySelectorAll('.status-dot').forEach(function(statusDot) {
      statusDot.addEventListener('click', function() {
        var dropdown = this.nextElementSibling.nextElementSibling;
        dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'block' : 'none';
      });
    });

    document.querySelectorAll('.task-status').forEach(function(select) {
      select.addEventListener('change', function() {
        var taskId = this.getAttribute('data-task-id');
        var newStatus = this.value;
        // Perform AJAX request to update status
        fetch('update_task_status.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              taskId: taskId,
              status: newStatus
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              var statusText = this.closest('.status-container').querySelector('.status-text span');
              var statusDot = this.closest('.status-container').querySelector('.status-dot');
              statusText.textContent = newStatus;
              statusDot.style.backgroundColor = getStatusColor(newStatus);
              this.parentElement.style.display = 'none';
            } else {
              console.error('Failed to update status.');
            }
          })
          .catch(error => {
            console.error('Error:', error);
          });
      });
    });

    function getStatusColor(status) {
      switch (status) {
        case 'Not Started':
          return '#ff0000'; // Red
        case 'In Progress':
          return '#007bff'; // Blue
        case 'Completed':
          return '#28a745'; // Green
        default:
          return '#6c757d'; // Gray
      }
    }
  </script>
</body>

</html>