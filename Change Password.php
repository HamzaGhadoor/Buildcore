<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'buildcore';

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $adminId = $_SESSION['admin_id']; // Admin ID stored in session after login

    // Fetch the current password from the database
    $sql = "SELECT password FROM admin WHERE id = '$adminId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($currentPassword, $row['password'])) { // Check if current password is correct
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the new password
            $updateSql = "UPDATE admin SET password = '$hashedNewPassword' WHERE id = '$adminId'";

            if ($conn->query($updateSql) === TRUE) {
                echo "<div class='alert alert-success'>Password updated successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Current password is incorrect!</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Admin not found!</div>";
    }
}

$conn->close();
?>
