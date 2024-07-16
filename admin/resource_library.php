<?php
require_once('../config.php');
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // For testing purposes, but in production, this should be managed by your login system
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $link = $_POST['link'] ?? '';
    $note = $_POST['note'] ?? '';

    $error = false;
    $file_path = '';

    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file_name = basename($_FILES['file']['name']);
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir) && !mkdir($upload_dir, 0755, true)) {
            $error = true;
        } else {
            $file_path = $upload_dir . $file_name;
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
                $error = true;
            }
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("INSERT INTO resource_library (user_id, title, description, file_path, link, note, date_added) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("isssss", $user_id, $title, $description, $file_path, $link, $note);
        if ($stmt->execute()) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Database error: ' . $stmt->error));
        }
        $stmt->close();
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Failed to upload the file or create directory.'));
    }
    exit;
}

// Handle delete resource
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $resource_id = $_POST['resource_id'];
    $stmt = $conn->prepare("DELETE FROM resource_library WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $resource_id, $user_id);
    if ($stmt->execute()) {
        echo json_encode(array('status' => 'success'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Database error: ' . $stmt->error));
    }
    $stmt->close();
    exit;
}

// Handle fetching resources
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'fetch') {
    $stmt = $conn->prepare("SELECT * FROM resource_library WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $resources = array();
    while ($row = $result->fetch_assoc()) {
        $resources[] = $row;
    }
    $stmt->close();
    echo json_encode(array('status' => 'success', 'resources' => $resources));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<?php require_once('inc/header.php'); ?>

<body>
    <?php require_once('inc/navigation.php'); ?>
    <div class="container">
        <h2>Resource Library</h2>
        <div id="alert-container"></div>
        <form id="resource-form" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="file">Upload File:</label>
                <input type="file" class="form-control-file" id="file" name="file">
            </div>
            <div class="form-group">
                <label for="link">Link:</label>
                <input type="url" class="form-control" id="link" name="link">
            </div>
            <div class="form-group">
                <label for="note">Note:</label>
                <textarea class="form-control" id="note" name="note"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Resource</button>
        </form>
        <hr>
        <h3>My Resources</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>File</th>
                    <th>Link</th>
                    <th>Note</th>
                    <th>Date Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="resource-list">
                <!-- Resources will be loaded here by AJAX -->
            </tbody>
        </table>
    </div>
    <?php require_once('inc/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function loadResources() {
                $.ajax({
                    url: 'resource_library.php',
                    type: 'GET',
                    data: {
                        action: 'fetch'
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            $('#resource-list').empty();
                            $.each(data.resources, function(index, resource) {
                                var resourceItem = '<tr>';
                                resourceItem += '<td>' + resource.title + '</td>';
                                resourceItem += '<td>' + resource.description + '</td>';
                                if (resource.file_path) {
                                    resourceItem += '<td><a href="' + resource.file_path + '" target="_blank"><i class="fas fa-download"></i></a></td>';
                                } else {
                                    resourceItem += '<td></td>';
                                }
                                if (resource.link) {
                                    resourceItem += '<td><a href="' + resource.link + '" target="_blank"><i class="fas fa-link"></i></a></td>';
                                } else {
                                    resourceItem += '<td></td>';
                                }
                                resourceItem += '<td>' + resource.note + '</td>';
                                resourceItem += '<td>' + new Date(resource.date_added).toLocaleString() + '</td>';
                                resourceItem += '<td><button class="btn btn-danger btn-sm delete-resource" data-id="' + resource.id + '"><i class="fas fa-trash"></i></button></td>';
                                resourceItem += '</tr>';
                                $('#resource-list').append(resourceItem);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#alert-container').html('<div class="alert alert-danger" role="alert">An error occurred: ' + error + '</div>');
                    }
                });
            }

            $('#resource-form').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: 'resource_library.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            $('#alert-container').html('<div class="alert alert-success" role="alert">Resource added successfully!</div>');
                            $('#resource-form')[0].reset();
                            loadResources();
                        } else {
                            $('#alert-container').html('<div class="alert alert-danger" role="alert">' + data.message + '</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#alert-container').html('<div class="alert alert-danger" role="alert">An error occurred: ' + error + '</div>');
                    }
                });
            });

            $(document).on('click', '.delete-resource', function() {
                var resourceId = $(this).data('id');

                $.ajax({
                    url: 'resource_library.php',
                    type: 'POST',
                    data: {
                        action: 'delete',
                        resource_id: resourceId
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            $('#alert-container').html('<div class="alert alert-success" role="alert">Resource deleted successfully!</div>');
                            loadResources();
                        } else {
                            $('#alert-container').html('<div class="alert alert-danger" role="alert">' + data.message + '</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#alert-container').html('<div class="alert alert-danger" role="alert">An error occurred: ' + error + '</div>');
                    }
                });
            });

            loadResources();
        });
    </script>
</body>

</html>
<?php
ob_end_flush();
?>