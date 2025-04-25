<?php
include __DIR__ . '/php/connection.php'; // Correct path to the connection file
// Define the admin credentials
$username = 'admin';
$password = 'admin123'; // Use a strong password in a real-world scenario
$email = 'admin@example.com';

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert the admin user into the database
$sql = "INSERT INTO admins (username, password, email) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $hashed_password, $email);

if ($stmt->execute()) {
    echo "Admin user created successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>