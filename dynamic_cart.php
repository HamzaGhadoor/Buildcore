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

// Fetch all products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuildCore - Dynamic Cart</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

    <!-- Cart Section -->
    <section class="container my-5">
        <div class="card shadow-xl border-0 rounded-4">
            <div class="card-header bg-primary text-white text-center py-4">
                <h2>Your Cart</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="cartTable" class="table table-hover align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Material</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" class="img-fluid rounded" width="70"></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td>
                                            <select class="form-select material-type" data-product-id="<?php echo $row['id']; ?>" onchange="updatePrice(this)">
                                                <option value="<?php echo $row['price']; ?>"><?php echo $row['type']; ?> - ₨<?php echo $row['price']; ?></option>
                                            </select>
                                        </td>
                                        <td><input type="number" value="0" min="1" class="form-control quantity" onchange="updateTotal(this)"></td>
                                        <td class="unit-price text-primary">₨<?php echo $row['price']; ?></td>
                                        <td class="total-price text-success">₨0</td>
                                        <td><button class="btn btn-danger btn-sm" onclick="removeItem(this)"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No products available.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; 2025 BuildCore. All Rights Reserved.</p>
    </footer>

    <!-- JavaScript -->
    <script>
        function updatePrice(select) {
            const row = select.closest("tr");
            const unitPrice = parseFloat(select.value);
            row.querySelector(".unit-price").innerText = `₨${unitPrice.toFixed(2)}`;
            const quantity = parseInt(row.querySelector(".quantity").value) || 0;
            const totalPrice = unitPrice * quantity;
            row.querySelector(".total-price").innerText = `₨${totalPrice.toFixed(2)}`;
            updateSummary();
        }

        function updateTotal(input) {
            const row = input.closest("tr");
            const unitPrice = parseFloat(row.querySelector(".unit-price").innerText.replace("₨", ""));
            const quantity = parseInt(input.value) || 0;
            const totalPrice = unitPrice * quantity;
            row.querySelector(".total-price").innerText = `₨${totalPrice.toFixed(2)}`;
            updateSummary();
        }

        function updateSummary() {
            let subtotal = 0;
            document.querySelectorAll(".total-price").forEach(price => {
                subtotal += parseFloat(price.innerText.replace("₨", ""));
            });
            console.log("Updated subtotal: ₨" + subtotal.toFixed(2));
        }

        function removeItem(button) {
            button.closest("tr").remove();
            updateSummary();
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>