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

// Handle form submission to delete user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $userId = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action === 'delete') {
        // Prepare the query for deleting the user
        $sql = "DELETE FROM users WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $_SESSION['message'] = "User deleted successfully.";
            $stmt->close();
        } else {
            $_SESSION['message'] = "Error preparing query.";
        }
    } elseif ($action === 'update_role') {
        $role = $_POST['role'];
        $sql = "UPDATE users SET role = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("si", $role, $userId);
            $stmt->execute();
            $_SESSION['message'] = "User role updated successfully.";
            $stmt->close();
        } else {
            $_SESSION['message'] = "Error preparing query.";
        }
    } elseif ($action === 'update_user') {
        // Handling user update (editing)
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // If password is provided, hash it, otherwise retain the old password
        if (!empty($password)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("sssi", $username, $email, $passwordHash, $userId);
                $stmt->execute();
                $_SESSION['message'] = "User updated successfully.";
                $stmt->close();
            } else {
                $_SESSION['message'] = "Error preparing query.";
            }
        } else {
            $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssi", $username, $email, $userId);
                $stmt->execute();
                $_SESSION['message'] = "User updated successfully.";
                $stmt->close();
            } else {
                $_SESSION['message'] = "Error preparing query.";
            }
        }
    }
}

// Fetch all users
$sql = "SELECT * FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
$users = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
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
        <li class="nav-item"><a class="nav-link active d-flex align-items-center" href="index.php"><i class="fas fa-home me-1"></i> Home</a></li>
        <li class="nav-item"><a class="nav-link d-flex align-items-center" href="cart.html"><i class="fas fa-shopping-cart me-1"></i> Order here</a></li>
        <li class="nav-item"><a class="nav-link d-flex align-items-center" href="contact.html"><i class="fas fa-envelope me-1"></i> Contact</a></li>
        <li class="nav-item"><a class="nav-link d-flex align-items-center" href="admin_dashboard.php"><i class="fas fa-user-shield me-1"></i> Admin</a></li>
        <li class="nav-item ms-3"><div class="input-group"><input type="text" id="searchBar" placeholder="Search..." class="form-control"><button id="searchButton" class="btn btn-outline-light"><i class="fas fa-search"></i></button></div></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Admin Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="admin_dashboard.php"><img src="images/invantroy.jpeg" alt="Logo" width="50" height="50" class="rounded-circle">Admin</a>
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
  <h2 class="mb-4">Manage Users</h2>

  <!-- Alert Message -->
  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success">
      <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>
  <?php endif; ?>

  <!-- Users Table -->
  <table class="table table-bordered" id="usersTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user): ?>
        <tr>
          <td><?php echo $user['id']; ?></td>
          <td><?php echo $user['username']; ?></td>
          <td><?php echo $user['email']; ?></td>
          <td><?php echo $user['role']; ?></td>
          <td><?php echo $user['created_at']; ?></td>
          <td>
            <!-- Edit User -->
            <button class="btn btn-info btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $user['id']; ?>">Edit</button>

            <!-- Update Role -->
            <form action="admin_manage_user.php" method="POST" class="d-inline">
              <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
              <select name="role" class="form-select form-select-sm" required>
                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
              </select>
              <button type="submit" name="action" value="update_role" class="btn btn-warning btn-sm mt-2">Update Role</button>
            </form>
            <!-- Delete User -->
            <form action="admin_manage_user.php" method="POST" class="d-inline">
              <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
              <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm mt-2">Delete</button>
            </form>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $user['id']; ?>" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel<?php echo $user['id']; ?>">Edit User - <?php echo $user['username']; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="admin_manage_user.php" method="POST">
                      <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                      <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" value="<?php echo $user['username']; ?>" required>
                      </div>
                      <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
                      </div>
                      <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="New password">
                      </div>
                      <button type="submit" name="action" value="update_user" class="btn btn-primary">Save Changes</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
