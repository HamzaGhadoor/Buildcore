<?php
include 'config.php';

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Fetch order details before deleting
    $sql_fetch = "SELECT * FROM ordr WHERE id = ?";
    $stmt_fetch = $conn->prepare($sql_fetch);
    $stmt_fetch->bind_param("i", $order_id);
    $stmt_fetch->execute();
    $result = $stmt_fetch->get_result();
    $order = $result->fetch_assoc();
    $stmt_fetch->close();

    if (!$order) {
        die("Error: Order not found.");
    }

    // Delete related order items first (Foreign Key constraint)
    $sql_items = "DELETE FROM order_items WHERE order_id = ?";
    $stmt_items = $conn->prepare($sql_items);
    $stmt_items->bind_param("i", $order_id);
    if (!$stmt_items->execute()) {
        die("Error deleting order items: " . $stmt_items->error);
    }
    $stmt_items->close();

    // Delete the order after deleting related items
    $sql_order = "DELETE FROM ordr WHERE id = ?";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("i", $order_id);
    
    if ($stmt_order->execute()) {
        // Construct deleted order details message
        $deleted_message = "Order deleted successfully!<br>";
        $deleted_message .= "<strong>Order ID:</strong> " . $order['id'] . "<br>";
        $deleted_message .= "<strong>Customer Name:</strong> " . $order['customer_name'] . "<br>";
        $deleted_message .= "<strong>Email:</strong> " . $order['customer_email'] . "<br>";
        $deleted_message .= "<strong>Phone:</strong> " . $order['customer_phone'] . "<br>";
        $deleted_message .= "<strong>Total Price:</strong> $" . $order['total_price'] . "<br>";
        $deleted_message .= "<strong>Order Date:</strong> " . $order['order_date'] . "<br>";

        // Redirect with message
        $stmt_order->close();
        header("Location: manage_orders.php?msg=" . urlencode($deleted_message));
        exit();
    } else {
        $stmt_order->close();
        die("Error deleting order: " . $stmt_order->error);
    }
}

$conn->close();
?>
