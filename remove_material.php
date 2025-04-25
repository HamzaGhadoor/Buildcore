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

// remove_material.php
include 'db.php';
include 'config.php'; // Database connection

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $materialName = $data['materialName'];

    // Delete the material from the database
    $sql = "DELETE FROM materials WHERE name = '$materialName'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Material removed successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove material']);
    }
}
?>