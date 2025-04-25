<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'buildcore';

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
session_destroy();
header("Location: login.php");

// logout.php
include 'db.php';
session_start();
session_destroy(); // Destroy the session to log out
header('Location: admin_login.php'); // Redirect to login page
exit;
?>
