<?php
include __DIR__ . '/php/connection.php'; // Correct path to the connection file

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php'); // Redirect to login if not logged in
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input fields
    if (isset($_POST['product_id']) && isset($_POST['new_price']) && !empty($_POST['product_id']) && !empty($_POST['new_price'])) {
        $product_id = intval($_POST['product_id']); // Ensure it's an integer
        $new_price = floatval($_POST['new_price']); // Ensure it's a float

        // Get old price
        $sql_old_price = "SELECT price FROM products WHERE id = ?";
        $stmt_old_price = $conn->prepare($sql_old_price);
        $stmt_old_price->bind_param("i", $product_id);
        $stmt_old_price->execute();
        $result_old_price = $stmt_old_price->get_result();

        if ($result_old_price->num_rows > 0) {
            $old_price_row = $result_old_price->fetch_assoc();
            $old_price = $old_price_row['price'];

            // Update price for the selected product
            $sql_update = "UPDATE products SET price = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("di", $new_price, $product_id);

            if ($stmt_update->execute()) {
                // Log price change in the history table
                $sql_history = "INSERT INTO price_history (product_id, old_price, new_price, date) 
                                VALUES (?, ?, ?, NOW())";
                $stmt_history = $conn->prepare($sql_history);
                $stmt_history->bind_param("idd", $product_id, $old_price, $new_price);
                $stmt_history->execute();

                echo "<script>alert('Price updated successfully'); window.location.href='update_price.php';</script>";
            } else {
                echo "<script>alert('Error updating price');</script>";
            }

            $stmt_update->close();
            $stmt_history->close();
        } else {
            echo "<script>alert('Product not found');</script>";
        }

        $stmt_old_price->close();
    } else {
        echo "<script>alert('Please select a product and enter a valid price');</script>";
    }
}

// Fetch current products
$sql = "SELECT id, name FROM products";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Price - BuildCore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap CSS -->
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
<body><!-- Main Navigation Bar -->
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
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
<div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">
            <img src="images/update.jpeg"cghange password Logo" width="50" height="50" class="rounded-circle">Admin
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="orders.php"><i class="bi bi-card-checklist me-1"></i>Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="feedback.php"><i class="bi bi-chat-dots me-1"></i>Feedback</a></li>
                <li class="nav-item"><a class="nav-link" href="update_price.php"><i class="bi bi-pencil-square me-1"></i>Update Price</a></li>
                <li class="nav-item"><a class="nav-link" href="history.php"><i class="bi bi-clock-history me-1"></i>History</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

    <!-- Update Price Section -->
    <section class="container my-5">
        <h2 class="text-center">Update Product Price</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="product_id" class="form-label">Select Product</label>
                <select class="form-select" id="product_id" name="product_id" required>
                    <option value="">-- Select Product --</option>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="new_price" class="form-label">New Price</label>
                <input type="number" class="form-control" id="new_price" name="new_price" step="0.01" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary w-100">Update Price</button>
            </div>
        </form>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 BuildCore. All Rights Reserved.</p>
    </footer>
    <script src="update_price.php"></script>
</body>
</html>
