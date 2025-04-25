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

// Handle form submission to mark contact as seen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $contactId = $_POST['contact_id'];
    $action = $_POST['action'];

    if ($action === 'mark_seen') {
        // Prepare the query for updating the contact's status
        $sql = "UPDATE contacts SET seen = 1 WHERE contact_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $contactId);
            $stmt->execute();
            $_SESSION['message'] = "Contact marked as seen successfully.";
            $stmt->close();
        } else {
            $_SESSION['message'] = "Error preparing query.";
        }
    }
}

// Fetch all contacts
$sql = "SELECT * FROM contacts ORDER BY created_at DESC";
$result = $conn->query($sql);
$contacts = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Contacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    <a class="navbar-brand" href="admin_dashboard.php"><img src="images/invantroy.jpeg" alt="Logo" width="50" height="50" class="rounded-circle"> Admin</a>
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
  <h2 class="mb-4">Manage Contacts</h2>

  <!-- Alert Message -->
  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success">
      <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>
  <?php endif; ?>

  <!-- Contacts Table -->
  <table class="table table-bordered table-striped" id="contactsTable">
    <thead class="table-dark">
      <tr>
        <th>Contact ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Message</th>
        <th>Created At</th>
        <th>Status</th>
        <th>Action</th>
        <th>Contact Back</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($contacts as $contact): ?>
        <tr>
          <td><?php echo $contact['contact_id']; ?></td>
          <td><?php echo $contact['name']; ?></td>
          <td><?php echo $contact['email']; ?></td>
          <td><?php echo $contact['phone']; ?></td>
          <td><?php echo $contact['message']; ?></td>
          <td><?php echo $contact['created_at']; ?></td>
          <td>
            <?php echo $contact['seen'] ? '<span class="badge bg-success">Seen</span>' : '<span class="badge bg-warning">Not Seen</span>'; ?>
          </td>
          <td>
            <?php if ($contact['seen'] == 0): ?>
              <form action="admin_manage_contact.php" method="POST" class="d-inline">
                <input type="hidden" name="contact_id" value="<?php echo $contact['contact_id']; ?>">
                <button type="submit" name="action" value="mark_seen" class="btn btn-success btn-sm"><i class="fas fa-eye"></i> Mark as Seen</button>
              </form>
            <?php else: ?>
              <span class="text-muted">No Action</span>
            <?php endif; ?>
          </td>
          <td>
            <form action="admin_manage_contact.php" method="POST" class="d-inline">
              <input type="hidden" name="contact_id" value="<?php echo $contact['contact_id']; ?>">
              <select name="contact_method" class="form-select form-select-sm">
                <option value="email">Email</option>
                <option value="phone">Phone</option>
                <option value="both">Both</option>
              </select>
              <button type="submit" name="action" value="contact_back" class="btn btn-primary btn-sm mt-2"><i class="fas fa-phone-alt"></i> Contact Back</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
