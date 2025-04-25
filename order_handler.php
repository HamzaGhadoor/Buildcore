<?php
$host = "localhost";
$user = "root"; // Default XAMPP user
$password = ""; // Default password is empty
$database = "buildcore"; // Your database name

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuildCore - Your Construction Companion</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- jQuery CDN Link -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
    <!-- Custom CSS for improved styling -->    <!-- Bootstrap CSS -->
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
        }
        .navbar {
            margin-bottom: 20px;
        }
        .cart-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }
        .order-summary {
            background-color: #e9ecef;
            padding: 20px;
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        .btn-info {
            background-color: #17a2b8;
            border: none;
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
     <!-- Admin Navbar -->
     <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_dashboard.php">Admin</a>
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


    <!-- Cart Section -->
    <section class="container my-5 cart-section">
        <h2>Your Cart</h2>
        <table id="cartTable" class="table">
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Material Type</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><img src="images/concrete_block.jpg" alt="Concrete Blocks" width="80"></td>
                    <td>Concrete Block</td>
                    <td>
                        <select class="material-type" data-product-id="1" onchange="updatePrice(this)">
                            <option value="70">A2 (16"x6"x8") - ₨70</option>
                            <option value="65">A3 (12"x5"x8") - ₨65</option>
                            <option value="40">A4 (12"x4"x8") - ₨40</option>
                            <option value="35">A5 (16"x3"x8") - ₨35</option>
                        </select>
                    </td>
                    <td><input type="number" value="1" min="1" class="quantity" onchange="updateTotal(this)"></td>
                    <td class="unit-price">₨70</td>
                    <td class="total-price">₨70</td>
                    <td><button class="btn-remove btn btn-danger btn-sm" onclick="removeItem(this)">Remove</button></td>
                </tr>
                <tr>
                    <td><img src="images/cement.jpg" alt="Cement" width="80"></td>
                    <td>Cement</td>
                    <td>
                        <select class="material-type" data-product-id="2" onchange="updatePrice(this)">
                            <option value="1465">Kohat Cement - ₨1,465</option>
                            <option value="1460">Cherat Cement - ₨1,460</option>
                            <option value="1475">Maple Leaf Cement - ₨1,475</option>
                            <option value="1460">Power Cement - ₨1,460</option>
                        </select>
                    </td>
                    <td><input type="number" value="1" min="1" class="quantity" onchange="updateTotal(this)"></td>
                    <td class="unit-price">₨1,465</td>
                    <td class="total-price">₨1,465</td>
                    <td><button class="btn-remove btn btn-danger btn-sm" onclick="removeItem(this)">Remove</button></td>
                </tr>
                <tr>
                    <td><img src="images/iron.jpg" alt="Iron Rod" width="80"></td>
                    <td>Iron Rod</td>
                    <td>
                        <select class="material-type" data-product-id="3" onchange="updatePrice(this)">
                            <option value="258">3 Sutar (10mm) - ₨258</option>
                            <option value="256">4 Sutar (12mm) - ₨256</option>
                            <option value="256">5 Sutar (16mm) - ₨256</option>
                            <option value="256">6 Sutar (20mm) - ₨256</option>
                        </select>
                    </td>
                    <td><input type="number" value="1" min="1" class="quantity" onchange="updateTotal(this)"></td>
                    <td class="unit-price">₨258</td>
                    <td class="total-price">₨258</td>
                    <td><button class="btn-remove btn btn-danger btn-sm" onclick="removeItem(this)">Remove</button></td>
                </tr>
                <tr>
                    <td><img src="images/bricks.jpg" alt="Bricks" width="80"></td>
                    <td>Bricks</td>
                    <td>
                        <select class="material-type" data-product-id="4" onchange="updatePrice(this)">
                            <option value="17000">A Class Brick - ₨17,000</option>
                            <option value="16000">Awwal Brick (Machine-Made) - ₨16,000</option>
                            <option value="18000">Fly Ash Brick - ₨18,000</option>
                        </select>
                    </td>
                    <td><input type="number" value="1" min="1" class="quantity" onchange="updateTotal(this)"></td>
                    <td class="unit-price">₨17,000</td>
                    <td class="total-price">₨17,000</td>
                    <td><button class="btn-remove btn btn-danger btn-sm" onclick="removeItem(this)">Remove</button></td>
                </tr>
            </tbody>
        </table>
    </section>
    <section class="container my-5">
        <h2>Place Your Order</h2>
        <form id="orderForm" method="POST" action="place_order.php" onsubmit="return validateForm()">
            <!-- Customer Info Section -->
            <div class="customer-info mb-3">
                <h3>Customer Information</h3>
                <div class="mb-3">
                    <label for="customerName" class="form-label">Name</label>
                    <input type="text" id="customerName" name="customer_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="customerPhone" class="form-label">Phone Number</label>
                    <input type="text" id="customerPhone" name="customer_phone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="customerEmail" class="form-label">Email</label>
                    <input type="email" id="customerEmail" name="customer_email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="feedbackMessage" class="form-label">Feedback</label>
                    <textarea id="feedbackMessage" name="feedback_message" class="form-control" rows="3" required></textarea>
                </div>
            </div>
            <!-- Order Summary Section -->
            <div class="order-summary mb-3">
                <h3>Order Summary</h3>
                <p>Subtotal: <span id="subtotal">₨20,793</span></p>
                <p>Delivery Charges: <span id="delivery">₨100</span></p>
                <p>Total Cost: <span id="totalCost">₨20,893</span></p>
                <input type="hidden" id="orderItems" name="order_items">
                <input type="hidden" id="totalOrderCost" name="totalCost">
                <button type="button" class="btn btn-primary btn-checkout" onclick="confirmOrder()">Place Order</button>
                <button type="button" class="btn btn-secondary" onclick="printOrder()">Print Order</button>
                <button type="button" class="btn btn-info" onclick="downloadOrder()">Download Order</button>
            </div>
        </form>
    </section>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 BuildCore. All Rights Reserved.</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
    updateSummary();

    // Ensure proper event delegation for dynamic elements
    document.querySelector("#cartTable").addEventListener("change", (event) => {
        if (event.target.classList.contains("quantity")) {
            updateTotal(event.target);
        } else if (event.target.classList.contains("material-type")) {
            updatePrice(event.target);
        }
    });

    document.querySelector("#orderForm").addEventListener("submit", (event) => {
        if (!validateForm()) {
            event.preventDefault(); // Stop form submission if validation fails
        }
    });
});

function validateForm() {
    const customerName = document.getElementById("customerName").value.trim();
    const customerPhone = document.getElementById("customerPhone").value.trim();
    const customerEmail = document.getElementById("customerEmail").value.trim();
    const feedbackMessage = document.getElementById("feedbackMessage").value.trim();
    const cartItems = document.querySelectorAll("#cartTable tbody tr").length;

    if (!customerName || !customerPhone || !customerEmail || !feedbackMessage) {
        alert("Please fill in all customer details before placing the order.");
        return false;
    }

    if (!/^\d+$/.test(customerPhone)) {
        alert("Please enter a valid phone number (numbers only).");
        return false;
    }

    if (cartItems === 0) {
        alert("Your cart is empty. Please add items before placing an order.");
        return false;
    }

    return true;
}

function updateTotal(input) {
    const row = input.closest("tr");
    const unitPrice = parseFloat(row.querySelector(".unit-price").innerText.replace("₨", "")) || 0;
    const quantity = parseInt(input.value) || 1;
    const totalPrice = unitPrice * quantity;
    
    row.querySelector(".total-price").innerText = `₨${totalPrice.toFixed(2)}`;
    updateSummary();
}

function updatePrice(select) {
    const row = select.closest("tr");
    const unitPrice = parseFloat(select.value) || 0;
    
    row.querySelector(".unit-price").innerText = `₨${unitPrice}`;
    updateTotal(row.querySelector(".quantity"));
}

function updateSummary() {
    let subtotal = 0;
    document.querySelectorAll(".total-price").forEach(price => {
        subtotal += parseFloat(price.innerText.replace("₨", "")) || 0;
    });

    const delivery = 100;
    const totalCost = subtotal + delivery;

    document.getElementById("subtotal").innerText = `₨${subtotal.toFixed(2)}`;
    document.getElementById("delivery").innerText = `₨${delivery.toFixed(2)}`;
    document.getElementById("totalCost").innerText = `₨${totalCost.toFixed(2)}`;
    document.getElementById("totalOrderCost").value = totalCost.toFixed(2);
}

function removeItem(button) {
    if (confirm("Are you sure you want to remove this item?")) {
        button.closest("tr").remove();
        updateSummary();
    }
}

function confirmOrder() {
    if (!validateForm()) return;

    let orderSummary = "Your order includes:\n";
    document.querySelectorAll("#cartTable tbody tr").forEach(row => {
        const productName = row.querySelector("td:nth-child(2)").innerText;
        const materialType = row.querySelector(".material-type").selectedOptions[0].text;
        const quantity = row.querySelector(".quantity").value;
        orderSummary += `- ${productName} (${materialType}) x ${quantity}\n`;
    });

    orderSummary += `\nTotal Cost: ${document.getElementById("totalCost").innerText}`;

    if (confirm(orderSummary + "\n\nConfirm order?")) {
        document.querySelector("#orderForm").submit();
    }
}
    </script>
</body>
</html>