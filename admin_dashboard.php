<?php
// Database connection
include 'php/connection.php';
session_start();

// Check if the user is logged in and has an admin role
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit;
}

// Fetch admin username
$user_id = $_SESSION['id'];
$sql = "SELECT username FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$admin = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BuildCore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
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

    <!-- Admin Dashboard -->
    <section class="container my-5">
        <h2 class="text-center mb-4 text-primary">Welcome, <?= htmlspecialchars($admin['username']); ?>!</h2>
        <div class="row g-4">
            <!-- Card for Manage Orders -->
            <div class="col-md-4">
                <div class="card h-100 text-center shadow-lg" onclick="location.href='manage_orders.php'" style="cursor: pointer;">
                    <div class="card-body">
                        <i class="fas fa-boxes fa-4x mb-3 text-primary"></i>
                        <h5 class="card-title">Manage Orders</h5>
                    </div>
                </div>
            </div>
            <!-- Card for Manage Materials -->
            <div class="col-md-4">
                <div class="card h-100 text-center shadow-lg" onclick="location.href='manage_matrials.php'" style="cursor: pointer;">
                    <div class="card-body">
                        <i class="fas fa-tools fa-4x mb-3 text-success"></i>
                        <h5 class="card-title">Manage Materials</h5>
                    </div>
                </div>
            </div>
            <!-- Card for Generate Reports -->
            <div class="col-md-4">
                <div class="card h-100 text-center shadow-lg" onclick="location.href='generate_report.php'" style="cursor: pointer;">
                    <div class="card-body">
                        <i class="fas fa-file-alt fa-4x mb-3 text-warning"></i>
                        <h5 class="card-title">Generate Reports</h5>
                    </div>
                </div>
            </div>
            <!-- Card for View Notifications -->
            <div class="col-md-4">
                <div class="card h-100 text-center shadow-lg" onclick="location.href='view_notifications.html'" style="cursor: pointer;">
                    <div class="card-body">
                        <i class="fas fa-bell fa-4x mb-3 text-danger"></i>
                        <h5 class="card-title">View Notifications</h5>
                    </div>
                </div>
            </div>
            <!-- Card for Manage Inventory -->
            <div class="col-md-4">
                <div class="card h-100 text-center shadow-lg" onclick="location.href='admin_inventory.php'" style="cursor: pointer;">
                    <div class="card-body">
                        <i class="fas fa-cogs fa-4x mb-3 text-info"></i>
                        <h5 class="card-title">Manage Inventory</h5>
                    </div>
                </div>
            </div>
            <!-- Card for Manage Contact -->
            <div class="col-md-4">
                <div class="card h-100 text-center shadow-lg" onclick="location.href='admin_manage_contact.php'" style="cursor: pointer;">
                    <div class="card-body">
                        <i class="fas fa-comments fa-4x mb-3 text-success"></i>
                        <h5 class="card-title">Manage Contact</h5>
                    </div>
                </div>
            </div>
            <!-- Card for Manage Users -->
            <div class="col-md-4">
                <div class="card h-100 text-center shadow-lg" onclick="location.href='admin_manage_user.php'" style="cursor: pointer;">
                    <div class="card-body">
                        <i class="fas fa-users fa-4x mb-3 text-primary"></i>
                        <h5 class="card-title">Manage Users</h5>
                    </div>
                </div>
            </div>
            <!-- Card for Change Password -->
            <div class="col-md-4">
                <div class="card h-100 text-center shadow-lg" onclick="location.href='change_password.php'" style="cursor: pointer;">
                    <div class="card-body">
                        <i class="fas fa-key fa-4x mb-3 text-info"></i>
                        <h5 class="card-title">Change Password</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 BuildCore</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
