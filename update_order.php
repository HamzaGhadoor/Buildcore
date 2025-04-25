<?php
include 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid order ID.");
}

$order_id = intval($_GET['id']);

// Allowed statuses (Move this to the top so it's available everywhere)
$allowed_statuses = ['Pending', 'Shipped', 'Delivered', 'Processing', 'Under Process', 'Canceled'];

// Fetch order details
$sql = "SELECT o.customer_name, o.status, oi.product_id, oi.product_name, oi.material_type, oi.quantity, oi.unit_price, oi.total_price 
        FROM ordr o
        JOIN order_items oi ON o.id = oi.order_id
        WHERE o.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

// Check if order exists
if (!$order) {
    die("Order not found.");
}

// Initialize missing values to avoid undefined index errors
$order['product_id'] = isset($order['product_id']) ? $order['product_id'] : '';
$order['product_name'] = isset($order['product_name']) ? $order['product_name'] : '';
$order['material_type'] = isset($order['material_type']) ? $order['material_type'] : '';
$order['quantity'] = isset($order['quantity']) ? $order['quantity'] : 0;
$order['unit_price'] = isset($order['unit_price']) ? $order['unit_price'] : 0;
$order['total_price'] = isset($order['total_price']) ? $order['total_price'] : 0;
$order['status'] = isset($order['status']) ? $order['status'] : 'Pending';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = trim($_POST['customer_name']);
    $product_id = intval($_POST['product_id']);
    $product_name = trim($_POST['product_name']);
    $material_type = trim($_POST['material_type']);
    $quantity = floatval($_POST['quantity']);
    $unit_price = floatval($_POST['unit_price']);
    $total_price = $quantity * $unit_price;
    $status = trim($_POST['status']);

    // Validate status
    if (!in_array($status, $allowed_statuses)) {
        die("Invalid status value.");
    }

    // Update order details
    $sql = "UPDATE ordr SET customer_name = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $customer_name, $status, $order_id);
    $stmt->execute();
    $stmt->close();

    // Update order items
    $sql = "UPDATE order_items SET product_id = ?, product_name = ?, material_type = ?, quantity = ?, unit_price = ?, total_price = ? WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issiddi", $product_id, $product_name, $material_type, $quantity, $unit_price, $total_price, $order_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Order updated successfully!'); window.location='admin_dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order - BuildCore Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.html"><i class="fas fa-hard-hat"></i> BuildCore</a>
    </div>
</nav>

<div class="container mt-5">
    <h2><i class="fas fa-edit"></i> Update Order</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Customer Name</label>
            <input type="text" class="form-control" name="customer_name" value="<?= htmlspecialchars($order['customer_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" class="form-control" name="product_name" value="<?= htmlspecialchars($order['product_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Material Type</label>
            <input type="text" class="form-control" name="material_type" value="<?= htmlspecialchars($order['material_type']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($order['quantity']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Unit Price</label>
            <input type="number" class="form-control" id="unit_price" name="unit_price" value="<?= htmlspecialchars($order['unit_price']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Total Price</label>
            <input type="number" class="form-control" id="total_price" name="total_price" value="<?= htmlspecialchars($order['total_price']) ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Order Status</label>
            <select class="form-select" name="status" required>
                <option value="" disabled>Select Order Status</option>
                <?php 
                foreach ($allowed_statuses as $s) { ?>
                    <option value="<?= $s ?>" <?= ($order['status'] === $s) ? 'selected' : '' ?>>
                        <?= ucfirst($s) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Order</button>
    </form>
</div>

<script>
    document.getElementById('quantity').addEventListener('input', calculateTotal);
    document.getElementById('unit_price').addEventListener('input', calculateTotal);
    
    function calculateTotal() {
        let quantity = parseFloat(document.getElementById('quantity').value) || 0;
        let unit_price = parseFloat(document.getElementById('unit_price').value) || 0;
        document.getElementById('total_price').value = (quantity * unit_price).toFixed(2);
    }
</script>
</body>
</html>
