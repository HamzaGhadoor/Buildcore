<?php
include 'config.php';

$sql = "SELECT ordr.id, ordr.customer_name, order_items.product_name, order_items.material_type, 
               order_items.quantity, order_items.unit_price, order_items.total_price, 
               ordr.order_date, ordr.status 
        FROM ordr 
        INNER JOIN order_items ON ordr.id = order_items.order_id
        ORDER BY ordr.id DESC";

$result = $conn->query($sql);

$grouped_orders = [];

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $order_id = $row['id'];
    if (!isset($grouped_orders[$order_id])) {
      $grouped_orders[$order_id] = [
        'customer_name' => $row['customer_name'],
        'order_date' => $row['order_date'],
        'status' => $row['status'],
        'items' => [],
      ];
    }
    $grouped_orders[$order_id]['items'][] = [
      'product_name' => $row['product_name'],
      'material_type' => $row['material_type'],
      'quantity' => $row['quantity'],
      'unit_price' => $row['unit_price'],
      'total_price' => $row['total_price'],
    ];
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Orders - BuildCore Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
    body { background-color: #f7f9fc; }
    .navbar-brand span { font-size: 1.4rem; font-weight: 600; }
    .table th { background-color: #343a40; color: #fff; }
    .section-title { font-weight: 600; color: #333; }
    .order-header { background-color: #e9ecef; font-weight: bold; }
    .badge { font-size: 0.9rem; }
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

    <!-- Admin Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">
                <img src="images/material.jpeg"cghange password Logo" width="50" height="50" class="rounded-circle">Admin</a>     
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

<!-- Include Navbars Here (same as your current code)... -->

<!-- Order Table Section -->
<section class="container my-5">
  <h2 class="text-center section-title mb-4">📋 Manage Customer Orders</h2>
  <div class="table-responsive">
    <table class="table table-bordered table-hover shadow-sm">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Customer</th>
          <th>Product</th>
          <th>Material</th>
          <th>Qty</th>
          <th>Unit Price</th>
          <th>Total</th>
          <th>Date</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($grouped_orders)) {
          foreach ($grouped_orders as $order_id => $order) {
            $rowspan = count($order['items']);
            $first = true;
            foreach ($order['items'] as $item) {
              echo "<tr>";
              if ($first) {
                echo "<td rowspan='{$rowspan}'>{$order_id}</td>
                      <td rowspan='{$rowspan}'>{$order['customer_name']}</td>";
              }
              echo "<td>{$item['product_name']}</td>
                    <td>{$item['material_type']}</td>
                    <td>{$item['quantity']}</td>
                    <td>₨{$item['unit_price']}</td>
                    <td>₨{$item['total_price']}</td>";
              if ($first) {
                echo "<td rowspan='{$rowspan}'>{$order['order_date']}</td>
                      <td rowspan='{$rowspan}'><span class='badge bg-success'>{$order['status']}</span></td>
                      <td rowspan='{$rowspan}'>
                        <a href='update_order.php?id={$order_id}' class='btn btn-sm btn-warning'><i class='fas fa-edit'></i></a>
                        <button class='btn btn-sm btn-danger delete-btn' data-id='{$order_id}'><i class='fas fa-trash'></i></button>
                      </td>";
                $first = false;
              }
              echo "</tr>";
            }
          }
        } else {
          echo "<tr><td colspan='10' class='text-center text-muted'>No orders found.</td></tr>";
        }
        $conn->close();
        ?>
      </tbody>
    </table>
  </div>
</section>

<!-- Delete Modal (same as your current modal code) -->

<!-- Footer and Scripts (same as your current code)... -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(function () {
    let deleteId;
    $('.delete-btn').on('click', function () {
      deleteId = $(this).data('id');
      $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function () {
      if (deleteId) {
        window.location.href = 'delete_order.php?id=' + deleteId;
      }
    });
  });
</script>
</body>
</html>
