<?php
$conn = new mysqli("localhost", "root", "", "buildcore");

$response = [
    "success" => false,
    "message" => "Something went wrong! Please try again."
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Secure user input
    $customer_name = isset($_POST["customer_name"]) ? trim($_POST["customer_name"]) : "";
    $customer_phone = isset($_POST["customer_phone"]) ? trim($_POST["customer_phone"]) : "";
    $customer_email = isset($_POST["customer_email"]) ? trim($_POST["customer_email"]) : "not_provided@example.com";
    $feedback_message = isset($_POST["feedback_message"]) ? trim($_POST["feedback_message"]) : "";
    $order_items = isset($_POST["order_items"]) ? json_decode($_POST["order_items"], true) : [];
    $totalCost = isset($_POST['totalCost']) ? floatval($_POST['totalCost']) : 0.00;

    // ✅ Validate & Sanitize Inputs
    $customer_name = filter_var($customer_name, FILTER_SANITIZE_STRING);
    $customer_phone = filter_var($customer_phone, FILTER_SANITIZE_STRING);
    $customer_email = filter_var($customer_email, FILTER_VALIDATE_EMAIL) ? $customer_email : "not_provided@example.com";
    $feedback_message = $conn->real_escape_string($feedback_message);

    // Begin transaction
    $conn->begin_transaction();

    try {
        // ✅ Insert into Feedback Table
        $stmt = $conn->prepare("INSERT INTO feedback (customer_name, customer_phone, customer_email, feedback_message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $customer_name, $customer_phone, $customer_email, $feedback_message);
        $stmt->execute();
        $stmt->close();

        // ✅ Insert into Orders Table
        $stmt = $conn->prepare("INSERT INTO ordr (customer_name, customer_phone, customer_email, total_price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $customer_name, $customer_phone, $customer_email, $totalCost);
        $stmt->execute();
        $order_id = $stmt->insert_id; // Get last inserted order ID
        $stmt->close();

        // ✅ Insert Order Items (Batch Insert for Performance)
        if (!empty($order_items)) {
            $values = [];
            foreach ($order_items as $item) {
                $product_name = $conn->real_escape_string($item['product_name']);
                $material_type = $conn->real_escape_string($item['material_type']);
                $quantity = intval($item['quantity']);
                $unit_price = floatval($item['unit_price']);
                $total_price = floatval($item['total_price']);

                $values[] = "('$order_id', '$product_name', '$material_type', '$quantity', '$unit_price', '$total_price')";
            }

            $sql = "INSERT INTO order_items (order_id, product_name, material_type, quantity, unit_price, total_price) VALUES " . implode(", ", $values);
            if (!$conn->query($sql)) {
                throw new Exception("Error inserting order items: " . $conn->error);
            }
        }

        // ✅ Commit Transaction
        $conn->commit();
        $response = ["success" => true, "message" => "Your order has been placed successfully!"];
    } catch (Exception $e) {
        $conn->rollback(); // Rollback on failure
        $response = ["success" => false, "message" => "Order failed: " . $e->getMessage()];
    }

    // ✅ Close connection
    $conn->close();
}

// ✅ Convert response to JSON for frontend handling
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
        }
        .message-box {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            display: inline-block;
            text-align: center;
            max-width: 450px;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .message-box h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .message-box p {
            font-size: 18px;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .checkmark, .error-mark {
            font-size: 50px;
            display: block;
            margin-bottom: 10px;
        }
        .success .checkmark {
            color: #28a745;
        }
        .error .error-mark {
            color: #dc3545;
        }
        .home-button {
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 18px;
            transition: background-color 0.3s ease-in-out;
            display: inline-block;
            font-weight: bold;
        }
        .home-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="message-box <?php echo $response['success'] ? 'success' : 'error'; ?>">
        <?php if ($response['success']) : ?>
            <div class="checkmark">✔️</div>
            <h2>Order Placed Successfully!</h2>
            <p><?php echo $response['message']; ?></p>
        <?php else : ?>
            <div class="error-mark">❌</div>
            <h2>Order Failed!</h2>
            <p><?php echo $response['message']; ?></p>
        <?php endif; ?>
        
        <a class="home-button" href="index.php">Return Home</a>
    </div>

    <!-- Optional Auto-Redirect after 5 seconds -->
    <script>
        setTimeout(() => {
            window.location.href = "index.html";
        }, 5000); // Redirect after 5 seconds
    </script>
</body>
</html>
