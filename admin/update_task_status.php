<?php
require_once '../config.php'; // Adjust the path as necessary

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);
$taskId = $data['taskId'];
$status = $data['status'];

// Update the task status
$sql = "UPDATE schedule_list SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $status, $taskId);

$response = [];
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
}

$stmt->close();
$conn->close();

echo json_encode($response);
