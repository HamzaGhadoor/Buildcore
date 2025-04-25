<?php
header('Content-Type: application/json'); // Ensure JSON response

// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'buildcore';

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input data
    $materialName = trim($_POST['Name']);
    $materialType = trim($_POST['Type']);
    $materialPrice = trim($_POST['Price']);

    if (empty($materialName) || empty($materialType) || empty($materialPrice)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Handle image upload
    if (isset($_FILES['materialImage']) && $_FILES['materialImage']['error'] === 0) {
        $imageName = $_FILES['materialImage']['name'];
        $imageTmpName = $_FILES['materialImage']['tmp_name'];
        $imagePath = 'images/' . basename($imageName);

        // Move uploaded file
        if (move_uploaded_file($imageTmpName, $imagePath)) {
            // Use Prepared Statements to insert data safely
            $stmt = $conn->prepare("INSERT INTO materials (name, type, price, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $materialName, $materialType, $materialPrice, $imageName);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Material added successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error uploading image']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No valid image uploaded']);
    }
}

$conn->close();
?>
