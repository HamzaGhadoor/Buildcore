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
// mark_as_read.php
include 'config.php';

if (isset($_POST['notificationId'])) {
    $notificationId = $_POST['notificationId'];
    $sql = "UPDATE notifications SET status = 'read' WHERE id = '$notificationId'";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>

<?php
// manage_materials.php
include 'db.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

include 'config.php'; // Database connection

// Handle Adding New Material
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_material'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $dimensions = $_POST['dimensions'];
    $image = $_FILES['image']['name'];

    $target = "images/" . basename($image);

    // Move the uploaded file to the images directory
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO materials (name, price, dimensions, image) VALUES ('$name', '$price', '$dimensions', '$image')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('New material added successfully');window.location.href='manage_materials.php';</script>";
        } else {
            echo "<script>alert('Error adding material');</script>";
        }
    } else {
        echo "<script>alert('Error uploading image');</script>";
    }
}

// Handle Removing Material
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM materials WHERE material_id = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Material removed successfully');window.location.href='manage_materials.php';</script>";
    } else {
        echo "<script>alert('Error removing material');</script>";
    }
}

$sql = "SELECT * FROM materials";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Materials - BuildCore</title>
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="style.css">    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- jQuery CDN Link -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> 
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-oWBQ7fmgRox+7NQK9nZNNbfFLmTVsf5AIUV5h5Q5kVD7zM7N20N2kR2xD3+pxyze" crossorigin="anonymous"></script>
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">

</head>
<body>
    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="images/logo.jpeg" alt="BuildCore Logo" width="50" height="50" class="rounded-circle me-2">
            <span>BuildCore</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavMain">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link active d-flex align-items-center" href="index.php">
                        <i class="fas fa-home me-1"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="cart.html">
                        <i class="fas fa-shopping-cart me-1"></i> Order here
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="ai.html">
                        <i class="fas fa-robot me-1"></i> AI Estimation
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="contact.html">
                        <i class="fas fa-envelope me-1"></i> Contact
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="admin_dashboard.php">
                        <i class="fas fa-user-shield me-1"></i> Admin
                    </a>
                </li>
                <li class="nav-item ms-3">
                    <div class="input-group">
                        <input type="text" id="searchBar" placeholder="Search..." class="form-control">
                        <button id="searchButton" class="btn btn-outline-light">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <!-- Admin Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_dashboar.php">Admin</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="orders.php">View Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="feedback.php">View Feedback</a></li>
                    <li class="nav-item"><a class="nav-link" href="update_price.php">Update Price</a></li>
                    <li class="nav-item"><a class="nav-link active" href="manage_materials.php">Manage Materials</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Manage Materials Section -->
    <section class="container my-5">
        <h2 class="text-center">Manage Materials</h2>

        <!-- Add New Material Form -->
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Material Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>
            <div class="mb-3">
                <label for="dimensions" class="form-label">Dimensions (e.g., 16"x6"x8")</label>
                <input type="text" class="form-control" id="dimensions" name="dimensions" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Material Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <button type="submit" name="add_material" class="btn btn-primary w-100">Add Material</button>
        </form>

        <!-- Display Materials -->
        <table class="table table-bordered mt-5">
            <thead>
                <tr>
                    <th>Material Name</th>
                    <th>Price</th>
                    <th>Dimensions</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row['name'] . "</td>
                            <td>₨" . $row['price'] . "</td>
                            <td>" . $row['dimensions'] . "</td>
                            <td><img src='images/" . $row['image'] . "' alt='Material Image' width='100'></td>
                            <td><a href='manage_materials.php?id=" . $row['material_id'] . "' class='btn btn-danger'>Remove</a></td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 BuildCore. All Rights Reserved.</p>
    </footer>

</body>
</html>

<?php
$conn->close();
?>
