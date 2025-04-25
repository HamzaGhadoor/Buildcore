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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($conn->real_escape_string($_POST['name']));
    $email = trim($conn->real_escape_string($_POST['email']));
    $phone = trim($conn->real_escape_string($_POST['phone']));
    $address = trim($conn->real_escape_string($_POST['address']));
    $message = trim($conn->real_escape_string($_POST['message']));
    $created_at = date("Y-m-d H:i:s"); // Current timestamp

    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        echo "<div class='alert alert-danger'>Name, email, and message are required!</div>";
        exit;
    }

    // Insert into the database
    $sql = "INSERT INTO contacts (name, email, phone, address, message, created_at) 
            VALUES ('$name', '$email', '$phone', '$address', '$message', '$created_at')";

    if ($conn->query($sql) === TRUE) {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Thank You</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
            <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
            <style>
                body {
                    background: linear-gradient(135deg, #1e3c72, #2a5298);
                    height: 100vh;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    font-family: 'Roboto', sans-serif;
                    color: #333;
                }
                .thank-you-card {
                    max-width: 450px;
                    background: #fff;
                    border-radius: 16px;
                    padding: 30px;
                    text-align: center;
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
                    animation: fadeIn 1s ease-in-out;
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(-20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                .thank-you-icon {
                    font-size: 60px;
                    color: #28a745;
                    margin-bottom: 20px;
                    animation: bounce 1.5s infinite ease-in-out;
                }
                @keyframes bounce {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-10px); }
                }
                .btn-custom {
                    background-color: #28a745;
                    color: #fff;
                    border-radius: 30px;
                    padding: 10px 20px;
                    transition: all 0.3s ease;
                }
                .btn-custom:hover {
                    background-color: #218838;
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                }
            </style>
        </head>
        <body>
            <div class='thank-you-card'>
                <i class='fas fa-check-circle thank-you-icon'></i>
                <h2>Thank You, <span class='text-primary'>$name</span>!</h2>
                <p>Your message has been submitted successfully.</p>
                <p class='text-muted'>We appreciate your effort to reach out and will get back to you shortly.</p>
                <a href='index.html' class='btn btn-custom mt-3'>
                    <i class='fas fa-home'></i> Back to Home
                </a>
            </div>
        </body>
        </html>
        ";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

$conn->close();
?>