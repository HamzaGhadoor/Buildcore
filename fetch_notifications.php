<?php
header('Content-Type: application/json');

// 1. Connect to database
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'buildcore';

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// 2. Fetch Latest Pending Orders with Items
$orderQuery = "
    SELECT o.id AS order_id, o.customer_name, o.order_date, o.total_price,
           i.product_name, i.material_type, i.quantity
    FROM ordr o
    LEFT JOIN order_items i ON o.id = i.order_id
    WHERE o.status = 'pending'
    ORDER BY o.order_date DESC
    LIMIT 10
";

$orderResult = $conn->query($orderQuery);

$orders = [];
if ($orderResult) {
    while ($row = $orderResult->fetch_assoc()) {
        $id = $row['order_id'];
        if (!isset($orders[$id])) {
            $orders[$id] = [
                'id' => $row['order_id'],
                'customer_name' => $row['customer_name'],
                'order_date' => $row['order_date'],
                'total_price' => (float)$row['total_price'],
                'items' => []
            ];
        }

        // Append each order item
        if (!empty($row['product_name'])) {
            $orders[$id]['items'][] = [
                'product_name' => $row['product_name'],
                'material_type' => $row['material_type'],
                'quantity' => (int)$row['quantity']
            ];
        }
    }
}

// 3. Fetch Latest Feedback
$feedbackQuery = "
    SELECT customer_name, feedback_message, submitted_at
    FROM feedback
    ORDER BY submitted_at DESC
    LIMIT 3
";

$feedbackResult = $conn->query($feedbackQuery);
$feedbacks = [];
if ($feedbackResult) {
    while ($row = $feedbackResult->fetch_assoc()) {
        $feedbacks[] = [
            'customer_name' => $row['customer_name'],
            'feedback_message' => $row['feedback_message'],
            'submitted_at' => $row['submitted_at']
        ];
    }
}

// 4. Fetch Latest Contact Messages
$contactQuery = "
    SELECT name, email, message, created_at
    FROM contacts
    ORDER BY created_at DESC
    LIMIT 3
";

$contactResult = $conn->query($contactQuery);
$contacts = [];
if ($contactResult) {
    while ($row = $contactResult->fetch_assoc()) {
        $contacts[] = [
            'name' => $row['name'],
            'email' => $row['email'],
            'message' => $row['message'],
            'created_at' => $row['created_at']
        ];
    }
}

// 5. Final JSON Output
echo json_encode([
    'orders' => array_values($orders),
    'feedback' => $feedbacks,
    'contacts' => $contacts
]);

$conn->close();
?>
