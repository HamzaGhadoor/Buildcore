<?php
$conn = new mysqli("localhost", "root", "", "buildcore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$type = isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : '';
$minPrice = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$maxPrice = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 999999;
$inStockOnly = isset($_GET['in_stock']) ? true : false;

$sql = "SELECT * FROM products WHERE (name LIKE '%$search%' OR type LIKE '%$search%')";
if (!empty($type)) {
    $sql .= " AND type LIKE '%$type%'";
}
$sql .= " AND price BETWEEN $minPrice AND $maxPrice";
if ($inStockOnly) {
    $sql .= " AND stock_quantity > 0";
}
$sql .= " ORDER BY id ASC";

$result = $conn->query($sql);
?>

<?php if (isset($_GET['ajax'])): ?>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow">
                    <img src="' . $row["image"] . '" class="card-img-top" style="height:250px; object-fit:cover;" alt="' . $row["name"] . '">
                    <div class="card-body text-center">
                        <h5 class="card-title">' . $row["name"] . '</h5>
                        <p class="card-text">Price: Rs. ' . $row["price"] . ' per ' . $row["unit"] . '</p>
                        <a href="register.php?product=' . urlencode($row["name"]) . '" class="btn btn-primary">Purchase Now</a>
                    </div>
                </div>
            </div>';
        }
    } else {
        echo '<p class="text-center">No products found matching your criteria.</p>';
    }
    $conn->close();
    exit;
    ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Products - BuildCore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container my-5">
    <h2 class="mb-4 text-center">Search & Filter Products</h2>

    <form id="searchForm" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="search" id="searchInput" class="form-control" placeholder="Keyword (e.g., Cement)">
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="Block">Blocks</option>
                <option value="Cement">Cement</option>
                <option value="Iron">Iron Rods</option>
                <option value="Brick">Bricks</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="min_price" class="form-control" placeholder="Min Price">
        </div>
        <div class="col-md-2">
            <input type="number" name="max_price" class="form-control" placeholder="Max Price">
        </div>
        <div class="col-md-1 d-flex align-items-center">
            <input type="checkbox" name="in_stock" class="form-check-input me-1"> In Stock
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit">Search</button>
        </div>
    </form>

    <div class="row" id="resultsArea">
        <!-- AJAX results will be loaded here -->
    </div>

    <a href="index.php" class="btn btn-secondary mt-4">Back to Home</a>
</div>

<script>
    $(document).ready(function () {
        // Trigger initial load
        $('#searchForm').submit();

        $('#searchForm').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: 'search.php',
                type: 'GET',
                data: $(this).serialize() + '&ajax=1',
                success: function (data) {
                    $('#resultsArea').html(data);
                }
            });
        });
    });
</script>

</body>
</html>
