<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}
include 'config.php';

$sql = "SELECT ordr.id AS order_id, ordr.customer_name, ordr.order_date, ordr.status, 
               order_items.product_name, order_items.quantity, order_items.total_price 
        FROM ordr 
        INNER JOIN order_items ON ordr.id = order_items.order_id 
        ORDER BY ordr.order_date DESC";

$result = $conn->query($sql);

$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[$row['order_id']]['customer_name'] = $row['customer_name'];
        $orders[$row['order_id']]['order_date'] = $row['order_date'];
        $orders[$row['order_id']]['status'] = $row['status'];
        $orders[$row['order_id']]['items'][] = [
            'product_name' => $row['product_name'],
            'quantity' => $row['quantity'],
            'total_price' => $row['total_price']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuildCore - Admin Orders Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .accordion-button::after {
            transform: scale(1.2);
        }
        .order-summary-table th, .order-summary-table td {
            vertical-align: middle;
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

    
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
<div class="container-fluid">
        <a class="navbar-brand" href="index.html">
            <img src="images/order.jpg" alt="cghange password Logo" width="50" height="50" class="rounded-circle">
HOME
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

<div class="container">
    <h2 class="text-center mb-4"><i class="bi bi-journal-text me-2"></i>All Orders Report</h2>

    <?php if (!empty($orders)): ?>
        <div class="accordion" id="ordersAccordion">
            <?php $index = 0; foreach ($orders as $order_id => $order): ?>
                <div class="accordion-item mb-3 shadow-sm">
                    <h2 class="accordion-header" id="heading<?= $index ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="false" aria-controls="collapse<?= $index ?>">
                            <i class="bi bi-receipt-cutoff me-2"></i><strong>Order ID:</strong> <?= htmlspecialchars($order_id) ?> &nbsp;|&nbsp;
                            <i class="bi bi-person-circle me-2"></i><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']) ?> &nbsp;|&nbsp;
                            <i class="bi bi-calendar-check me-2"></i><strong>Date:</strong> <?= htmlspecialchars($order['order_date']) ?> &nbsp;|&nbsp;
                            <i class="bi bi-info-circle me-2"></i><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?>
                        </button>
                    </h2>
                    <div id="collapse<?= $index ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $index ?>" data-bs-parent="#ordersAccordion">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-bordered order-summary-table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th><i class="bi bi-box-seam me-1"></i>Product</th>
                                            <th><i class="bi bi-stack me-1"></i>Quantity</th>
                                            <th><i class="bi bi-cash-coin me-1"></i>Total Price (₨)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($order['items'] as $item): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                                <td><?= htmlspecialchars($item['quantity']) ?></td>
                                                <td>₨<?= number_format($item['total_price']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php $index++; endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center"><i class="bi bi-exclamation-circle me-2"></i>No orders found.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php $conn->close(); ?> 
