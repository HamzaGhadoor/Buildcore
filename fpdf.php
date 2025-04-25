<?php
require('fpdf.php');

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
