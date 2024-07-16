<?php 
// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Determine the current URL to handle redirections appropriately
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $link = "https"; 
} else {
    $link = "http"; 
}
$link .= "://"; 
$link .= $_SERVER['HTTP_HOST']; 
$link .= $_SERVER['REQUEST_URI'];

// Check if the user is not authenticated and redirect to login page if necessary
if (!isset($_SESSION['userdata']) && !strpos($link, 'login.php') && !strpos($link, 'register.php')) {
    header('Location: admin/login.php');
    exit;
}

// If the user is already authenticated and tries to access login page, redirect to the dashboard
if (isset($_SESSION['userdata']) && strpos($link, 'login.php')) {
    header('Location: admin/index.php');
    exit;
}

// Define user types and handle access control
$module = array('', 'admin', 'staff', 'user');
if (isset($_SESSION['userdata']) && (strpos($link, 'index.php') || strpos($link, 'admin/')) && $_SESSION['userdata']['type'] ==  3) {
    echo "<script>alert('Access Denied!');location.replace('".base_url.$module[$_SESSION['userdata']['type']]."');</script>";
    exit;
}




?>
