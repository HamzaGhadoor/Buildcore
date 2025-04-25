<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'buildcore';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, stock_quantity FROM products";
$result = $conn->query($sql);

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=inventory.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['Product ID', 'Product Name', 'Stock Quantity']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
$conn->close();
?>
