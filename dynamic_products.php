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

<section class="container my-5">
    <div class="card shadow-xl border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-gradient-primary text-white text-center py-4">
            <h2 class="fw-bold mb-0"><i class="fas fa-shopping-cart me-2"></i>Your Cart</h2>
        </div>
        <div class="card-body p-4">
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
                                    <td class="unit-price fw-semibold text-primary">₨<?php echo $row['price']; ?></td>
                                    <td class="total-price fw-bold text-success">₨0</td>
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

<?php $conn->close(); ?>