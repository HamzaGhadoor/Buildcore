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

// Fetch orders, order items, feedback, and contacts
$sql = "SELECT 
            o.id AS order_id, o.customer_name, o.customer_phone, o.customer_email, o.order_date, o.status, o.total_price,
            oi.product_name, oi.material_type, oi.quantity, oi.unit_price, oi.total_price AS item_total_price,
            f.customer_name AS feedback_name, f.feedback_message, f.submitted_at AS feedback_date,
            c.name AS contact_name, c.email AS contact_email, c.phone AS contact_phone, c.message AS contact_message, c.created_at AS contact_date
        FROM ordr o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN feedback f ON o.customer_email = f.customer_email
        LEFT JOIN contacts c ON o.customer_email = c.email
        ORDER BY o.order_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - BuildCore</title>
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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

    <style>
        .history-card {
            border-left: 5px solid #007bff;
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
    </style>
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
<!-- Admin Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mt-3">
        <div class="collapse navbar-collapse" id="adminNavbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="logout.php">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </li>
            </ul>
        </div>   
</nav>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashbourd.php">
            <img src="images/history.jpeg" alt="cghange password Logo" width="50" height="50" class="rounded-circle">
            Admin
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
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

<!-- Order History Section -->
<div class="container my-5">
    <h2 class="text-center mb-4">Order History</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="history-card p-3">
                <h5><i class="bi bi-receipt"></i> Order #<?= $row['order_id'] ?></h5>
                <p><i class="bi bi-person-fill"></i> <strong>Customer:</strong> <?= htmlspecialchars($row['customer_name']) ?></p>
                <p><i class="bi bi-telephone-fill"></i> <strong>Phone:</strong> <?= htmlspecialchars($row['customer_phone']) ?></p>
                <p><i class="bi bi-envelope-fill"></i> <strong>Email:</strong> <?= htmlspecialchars($row['customer_email']) ?></p>
                <p><i class="bi bi-calendar-event"></i> <strong>Order Date:</strong> <?= htmlspecialchars($row['order_date']) ?></p>
                <p><i class="bi bi-tag-fill"></i> <strong>Status:</strong> <span class="badge bg-primary"><?= htmlspecialchars($row['status']) ?></span></p>
                <p><i class="bi bi-currency-rupee"></i> <strong>Total Price:</strong> ₨<?= htmlspecialchars($row['total_price']) ?></p>

                <h6 class="mt-3"><i class="bi bi-box-seam"></i> Ordered Items:</h6>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Material</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td><?= htmlspecialchars($row['material_type']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td>₨<?= htmlspecialchars($row['unit_price']) ?></td>
                            <td>₨<?= htmlspecialchars($row['item_total_price']) ?></td>
                        </tr>
                    </tbody>
                </table>

                <?php if ($row['feedback_message']): ?>
                    <h6 class="mt-3"><i class="bi bi-chat-left-text-fill"></i> Feedback:</h6>
                    <p class="bg-light p-2 rounded"><i class="bi bi-person"></i> <?= htmlspecialchars($row['feedback_name']) ?> (<?= htmlspecialchars($row['feedback_date']) ?>)</p>
                    <p><?= htmlspecialchars($row['feedback_message']) ?></p>
                <?php endif; ?>

                <?php if ($row['contact_message']): ?>
                    <h6 class="mt-3"><i class="bi bi-envelope-paper"></i> Contact Inquiry:</h6>
                    <p class="bg-light p-2 rounded"><i class="bi bi-person"></i> <?= htmlspecialchars($row['contact_name']) ?> (<?= htmlspecialchars($row['contact_date']) ?>)</p>
                    <p><i class="bi bi-envelope"></i> Email: <?= htmlspecialchars($row['contact_email']) ?></p>
                    <p><i class="bi bi-telephone"></i> Phone: <?= htmlspecialchars($row['contact_phone']) ?></p>
                    <p><?= htmlspecialchars($row['contact_message']) ?></p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center">No order history found.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2024 BuildCore. All Rights Reserved.</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
