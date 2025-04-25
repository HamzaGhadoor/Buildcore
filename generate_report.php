<?php 
session_start();

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

$data = [];
$filename = "";
$labels = [];
$values = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['startDate'] ?? null;
    $endDate = $_POST['endDate'] ?? null;
    $reportType = $_POST['reportType'];

    $stmt = null;
    $filename = "report_" . date("YmdHis") . ".csv";

    switch ($reportType) {
        case 'sales':
            $sql = "SELECT DATE(o.order_date) AS label, SUM(oi.total_price) AS value
                    FROM ordr o
                    JOIN order_items oi ON o.id = oi.order_id
                    WHERE o.order_date BETWEEN ? AND ?
                    GROUP BY label
                    ORDER BY label ASC";
            break;
        case 'inventory':
            $sql = "SELECT name AS label, stock_quantity AS value FROM products WHERE stock_quantity > 0";
            break;
        case 'customers':
            $sql = "SELECT customer_name AS label, COUNT(*) AS value FROM ordr
                    WHERE order_date BETWEEN ? AND ? GROUP BY customer_name";
            break;
        case 'feedback':
            $sql = "SELECT customer_name AS label, COUNT(*) AS value FROM feedback 
                    WHERE submitted_at BETWEEN ? AND ? GROUP BY customer_name";
            break;
        case 'contacts':
            $sql = "SELECT name AS label, COUNT(*) AS value FROM contacts 
                    WHERE created_at BETWEEN ? AND ? GROUP BY name";
            break;
        case 'materials':
            $sql = "SELECT name AS label, price AS value FROM materials";
            break;
        case 'orders':
            $sql = "SELECT DATE(order_date) AS label, COUNT(*) AS value FROM ordr 
                    WHERE order_date BETWEEN ? AND ? GROUP BY label ORDER BY label ASC";
            break;
        default:
            echo "<div class='alert alert-danger'>Invalid report type selected.</div>";
            exit();
    }

    $stmt = $conn->prepare($sql);
    if (!in_array($reportType, ['inventory', 'materials'])) {
        $stmt->bind_param("ss", $startDate, $endDate);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
        $labels[] = $row['label'];
        $values[] = $row['value'];
    }

    if (!empty($data)) {
        $file = fopen($filename, 'w');
        fputcsv($file, array_keys($data[0]));
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BuildCore | Report Generator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap + Icons + Chart.js -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            background-color: #f8f9fa;
        }
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }
        canvas {
            background-color: #fff;
            border-radius: 10px;
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
    
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
<div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">
            <img src="images/report.jpeg"cghange password Logo" width="50" height="50" class="rounded-circle">Admin
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="orders.php"><i class="bi bi-card-checklist me-1"></i>Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="feedback.php"><i class="bi bi-chat-dots me-1"></i>Feedback</a></li>
                <li class="nav-item"><a class="nav-link" href="update_price.php"><i class="bi bi-pencil-square me-1"></i>Update Price</a></li>
                <li class="nav-item"><a class="nav-link" href="history.php"><i class="bi bi-clock-history me-1"></i>History</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Section -->
<div class="container py-5">
    <div class="card shadow p-4 mb-4">
        <h3 class="mb-4 text-center">📊 Generate Custom Report</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Report Type</label>
                <select name="reportType" class="form-select" required>
                    <option value="sales">Sales</option>
                    <option value="inventory">Inventory</option>
                    <option value="customers">Customers</option>
                    <option value="feedback">Feedback</option>
                    <option value="contacts">Contacts</option>
                    <option value="materials">Materials</option>
                    <option value="orders">Orders</option>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Start Date</label>
                    <input type="date" name="startDate" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">End Date</label>
                    <input type="date" name="endDate" class="form-control">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Chart Type</label>
                <select id="chartType" class="form-select">
                    <option value="bar">Bar</option>
                    <option value="line">Line</option>
                    <option value="pie">Pie</option>
                    <option value="doughnut">Doughnut</option>
                </select>
            </div>
            <button class="btn btn-primary w-100" type="submit">Generate Report</button>
        </form>
    </div>

    <?php if (!empty($data)): ?>
    <div class="card shadow p-4 mb-4">
        <h4 class="text-center mb-3">📈 Report Chart</h4>
        <div class="chart-container mb-3">
            <canvas id="reportChart"></canvas>
        </div>
        <div class="text-end">
            <button onclick="downloadChart()" class="btn btn-success me-2">Download Chart</button>
            <a href="<?php echo $filename; ?>" class="btn btn-secondary">Download CSV</a>
        </div>
    </div>

    <div class="card shadow p-4">
        <h4 class="text-center mb-3">📋 Data Table</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <?php foreach (array_keys($data[0]) as $key): ?>
                            <th><?= ucfirst($key); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <?php foreach ($row as $value): ?>
                                <td><?= htmlspecialchars($value); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const ctx = document.getElementById('reportChart');
const chartTypeSelector = document.getElementById('chartType');
const chart = new Chart(ctx, {
    type: chartTypeSelector.value,
    data: {
        labels: <?= json_encode($labels); ?>,
        datasets: [{
            label: "<?= ucfirst($reportType ?? 'Report') ?>",
            data: <?= json_encode($values); ?>,
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'],
            borderWidth: 1,
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: chartTypeSelector.value === 'pie' || chartTypeSelector.value === 'doughnut'
            }
        }
    }
});

chartTypeSelector.addEventListener('change', () => {
    chart.config.type = chartTypeSelector.value;
    chart.options.plugins.legend.display = ['pie', 'doughnut'].includes(chartTypeSelector.value);
    chart.update();
});

function downloadChart() {
    const link = document.createElement('a');
    link.download = "report_chart.png";
    link.href = ctx.toDataURL();
    link.click();
}
</script>
</body>
</html>
