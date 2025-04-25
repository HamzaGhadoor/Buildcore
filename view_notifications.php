<?php
header('Content-Type: application/json');

// Database Connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'buildcore';

$conn = new mysqli($host, $user, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Fetch latest orders with items
$orderQuery = "SELECT o.id, o.customer_name, o.order_date, o.total_price, 
                      i.product_name, i.material_type, i.quantity 
               FROM ordr o 
               LEFT JOIN order_items i ON o.id = i.order_id 
               WHERE o.status = 'pending' 
               ORDER BY o.order_date DESC 
               LIMIT 3";
$orderResult = $conn->query($orderQuery);

$orders = [];
while ($row = $orderResult->fetch_assoc()) {
    $orderId = $row['id'];
    if (!isset($orders[$orderId])) {
        $orders[$orderId] = [
            'id' => $row['id'],
            'customer_name' => $row['customer_name'],
            'order_date' => $row['order_date'],
            'total_price' => $row['total_price'],
            'items' => []
        ];
    }
    if ($row['product_name']) {
        $orders[$orderId]['items'][] = [
            'product_name' => $row['product_name'],
            'material_type' => $row['material_type'],
            'quantity' => $row['quantity']
        ];
    }
}

// Fetch latest feedback
$feedbackQuery = "SELECT id, customer_name, feedback_message, submitted_at 
                  FROM feedback 
                  ORDER BY submitted_at DESC 
                  LIMIT 3";
$feedbackResult = $conn->query($feedbackQuery);
$feedbacks = $feedbackResult->fetch_all(MYSQLI_ASSOC);

// Fetch latest contact messages
$contactQuery = "SELECT id, name, email, message, created_at 
                 FROM contacts 
                 ORDER BY created_at DESC 
                 LIMIT 3";
$contactResult = $conn->query($contactQuery);
$contacts = $contactResult->fetch_all(MYSQLI_ASSOC);

// Return JSON response
echo json_encode([
    "orders" => array_values($orders),
    "feedback" => $feedbacks,
    "contacts" => $contacts
]);

$conn->close();
?>