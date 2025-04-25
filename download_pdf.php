<?php
require_once __DIR__ . '/vendor/autoload.php'; // Ensure correct autoload
require_once __DIR__ . '/php/connection.php'; 

use Mpdf\Mpdf;

// Database Connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'buildcore';
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate Orders Selection
if (!isset($_POST['orders']) || !is_array($_POST['orders'])) {
    die("Error: No orders selected.");
}

$orderIds = $_POST['orders'];
$format = $_POST['format'] ?? 'pdf'; // Default: PDF

// Check if mPDF is Installed
if (!class_exists('Mpdf\Mpdf')) {
    die("Error: mPDF is not installed or autoload is not working!");
}

// Fetch Order Data
$data = [];
foreach ($orderIds as $orderId) {
    $orderId = $conn->real_escape_string($orderId);
    $sql = "SELECT o.id, o.customer_name, o.status, p.name AS material_name, 
                   ph.old_price, ph.new_price, ph.date
            FROM orders o
            JOIN price_history ph ON o.id = ph.order_id  
            JOIN products p ON ph.product_id = p.id
            WHERE o.id = '$orderId'";
    
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();

// Generate PDF
if ($format == 'pdf') {
    $html = "<h1>Price History & Orders</h1><hr>";

    foreach ($data as $row) {
        $html .= "<p><strong>Order ID:</strong> " . htmlspecialchars($row['id']) . "</p>";
        $html .= "<p><strong>Customer:</strong> " . htmlspecialchars($row['customer_name']) . "</p>";
        $html .= "<p><strong>Order Status:</strong> " . htmlspecialchars($row['status']) . "</p>";
        $html .= "<p><strong>Material:</strong> " . htmlspecialchars($row['material_name']) . "</p>";
        $html .= "<p><strong>Old Price:</strong> ₨" . htmlspecialchars($row['old_price']) . "</p>";
        $html .= "<p><strong>New Price:</strong> ₨" . htmlspecialchars($row['new_price']) . "</p>";
        $html .= "<p><strong>Date:</strong> " . htmlspecialchars($row['date']) . "</p><hr>";
    }

    $mpdf->WriteHTML($html);
    $mpdf->Output('orders_history.pdf', 'D');
    exit;
}

// Generate CSV
elseif ($format == 'csv') {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="orders_history.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Order ID', 'Customer Name', 'Status', 'Material', 'Old Price', 'New Price', 'Date']);

    foreach ($data as $row) {
        fputcsv($output, array_map("utf8_encode", $row));
    }

    fclose($output);
    exit;
}

// Generate Excel
elseif ($format == 'excel') {
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header('Content-Disposition: attachment; filename="orders_history.xls"');

    echo "Order ID\tCustomer Name\tStatus\tMaterial\tOld Price\tNew Price\tDate\n";

    foreach ($data as $row) {
        echo implode("\t", array_map("utf8_encode", $row)) . "\n";
    }
    exit;
} else {
    die("Invalid format selected.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderIds = $_POST['orders'];
    
    // Initialize FPDF object
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    // Loop through the selected order IDs and fetch details
    foreach ($orderIds as $orderId) {
        // Fetch order and price history details based on the order ID
        $orderQuery = "SELECT * FROM orders WHERE id = '$orderId'";
        $orderResult = $conn->query($orderQuery);
        $order = $orderResult->fetch_assoc();
        
        // Add order information to PDF
        $pdf->Cell(0, 10, 'Order ID: ' . $order['id'], 0, 1);
        $pdf->Cell(0, 10, 'Customer: ' . $order['customer_name'], 0, 1);

        // Fetch price history for this order
        $priceHistoryQuery = "SELECT * FROM price_history WHERE product_id = '{$order['product_id']}'";
        $priceHistoryResult = $conn->query($priceHistoryQuery);

        while ($priceHistory = $priceHistoryResult->fetch_assoc()) {
            $pdf->Cell(0, 10, 'Material: ' . $priceHistory['material_name'], 0, 1);
            $pdf->Cell(0, 10, 'Old Price: ₨' . $priceHistory['old_price'], 0, 1);
            $pdf->Cell(0, 10, 'New Price: ₨' . $priceHistory['new_price'], 0, 1);
        }

        $pdf->Ln(); // Add a line break between orders
    }

    // Output PDF to browser
    $pdf->Output();
}
?>
