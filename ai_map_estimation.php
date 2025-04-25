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

include 'connection.php';

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rooms = intval($_POST['rooms']);
    $wallHeight = floatval($_POST['wallHeight']) / 12; // Convert to feet
    $wallWidth = floatval($_POST['wallWidth']) / 12;   // Convert to feet
    $wallThickness = floatval($_POST['wallThickness']) / 12; // Convert to feet
    $doors = intval($_POST['doors']);
    $windows = intval($_POST['windows']);
    $material = $_POST['material'];
    $mapFile = $_FILES['map']['tmp_name'];

    // Calculate the total wall area
    $totalWallArea = 4 * $rooms * $wallHeight * $wallWidth;

    // Calculate door and window area
    $doorArea = $doors * (7 * 3); // Assuming 7x3 ft per door
    $windowArea = $windows * (4 * 3); // Assuming 4x3 ft per window

    // Subtract door and window area
    $usableWallArea = $totalWallArea - $doorArea - $windowArea;

    // Fetch material prices from the database
    $sql = "SELECT * FROM materials WHERE material_type = '$material'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $materialData = $result->fetch_assoc();
        $unitPrice = $materialData['price_per_unit'];
        $unitVolume = $materialData['volume_per_unit']; // In cubic feet

        // Calculate required materials
        $totalMaterials = $usableWallArea / $unitVolume;
        $totalCost = $totalMaterials * $unitPrice;

        echo json_encode([
            'success' => true,
            'materials' => ceil($totalMaterials),
            'totalCost' => ceil($totalCost),
            'message' => 'Estimation successful!',
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Material data not found!'
        ]);
    }
}
?>
