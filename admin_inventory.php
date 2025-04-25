<?php
session_start();

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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $productId = $_POST['product_id'];
    $quantityChange = $_POST['quantity_change'];
    $action = $_POST['action'];

    if ($action === 'add') {
        $sql = "UPDATE products SET stock_quantity = stock_quantity + ? WHERE id = ?";
    } elseif ($action === 'remove') {
        $sql = "UPDATE products SET stock_quantity = GREATEST(stock_quantity - ?, 0) WHERE id = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quantityChange, $productId);
    $stmt->execute();
    $stmt->close();

    $_SESSION['message'] = "Inventory updated successfully.";
}

// Fetch all products
$sql = "SELECT id, name, stock_quantity FROM products";
$result = $conn->query($sql);
$products = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Inventory Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom CSS -->
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
    <a class="navbar-brand" href="admin_dashboard.php">
                <img src="images/invantroy.jpeg"cghange password Logo" width="50" height="50" class="rounded-circle">admin
            </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="orders.php">View Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="feedback.php">View Feedback</a></li>
                <li class="nav-item"><a class="nav-link" href="update_price.php">Update Price</a></li>
                <li class="nav-item"><a class="nav-link" href="history.php">View History</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h2 class="mb-4">Manage Inventory</h2>

    <!-- Alert Message -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Action Buttons -->
    <div class="mb-3 no-print d-flex gap-2">
        <form action="download_inventory.php" method="POST">
            <button type="submit" class="btn btn-primary">Download CSV</button>
        </form>
        <button onclick="window.print()" class="btn btn-secondary">Print Inventory</button>
        <button onclick="shareInventory()" class="btn btn-info text-white">Share via Email</button>
    </div>

    <!-- Inventory Table -->
    <table class="table table-bordered" id="inventoryTable">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Current Stock</th>
                <th class="no-print">Update Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['stock_quantity']; ?></td>
                    <td class="no-print">
                        <form method="POST" class="d-flex">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="number" name="quantity_change" class="form-control me-2" placeholder="Enter quantity" required>
                            <button type="submit" name="action" value="add" class="btn btn-success me-2">Add</button>
                            <button type="submit" name="action" value="remove" class="btn btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Share Inventory Script -->
<script>
function shareInventory() {
    let table = document.getElementById('inventoryTable');
    let rows = table.querySelectorAll('tr');
    let csvContent = "";

    rows.forEach(row => {
        let cols = row.querySelectorAll('td, th');
        let rowData = [];
        cols.forEach(col => rowData.push(col.innerText));
        csvContent += rowData.join(", ") + "%0A";
    });

    let subject = "BuildCore Inventory Data";
    let body = "Here is the current inventory list:%0A%0A" + csvContent;
    window.location.href = `mailto:?subject=${subject}&body=${body}`;
}
</script>
</body>
</html>
