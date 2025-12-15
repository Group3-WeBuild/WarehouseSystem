<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trend Analysis | WeBuild Warehouse System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f6fa; }
        .sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: #fff;
            min-height: 100vh;
            padding-top: 20px;
        }

        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .topbar { background: #fff; padding: 15px 30px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; }
        .topbar h4 { margin: 0; color: #1a237e; }
        .main-content { padding: 30px; }
        .card-custom { background: #fff; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .card-custom h5 { font-weight: 600; margin-bottom: 20px; color: #333; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 px-0 sidebar">
            <div class="text-center py-4">
                <h5 class="text-white mb-1">WITMS</h5>
                <small class="text-white-50">Analytics</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= base_url('analytics/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a class="nav-link" href="<?= base_url('analytics/inventory-kpis') ?>"><i class="bi bi-boxes"></i> Inventory KPIs</a>
                <a class="nav-link" href="<?= base_url('analytics/financial-kpis') ?>"><i class="bi bi-currency-dollar"></i> Financial KPIs</a>
                <a class="nav-link" href="<?= base_url('analytics/warehouse-performance') ?>"><i class="bi bi-building"></i> Warehouse Performance</a>
                <a class="nav-link active" href="<?= base_url('analytics/trends') ?>"><i class="bi bi-graph-up"></i> Trends</a>
                <a class="nav-link" href="<?= base_url('analytics/forecasting') ?>"><i class="bi bi-graph-up-arrow"></i> Forecasting</a>
                <a class="nav-link" href="<?= base_url('analytics/reorder-recommendations') ?>"><i class="bi bi-cart-check"></i> Reorder Recommendations</a>
            </nav>
        </div>

        <div class="col-md-10">
            <div class="topbar">
                <div>
                    <h4><i class="bi bi-bar-chart-line me-2"></i><?= esc($pageTitle ?? 'Trend Analysis') ?></h4>
                    <small class="text-muted"><?= esc($breadcrumb ?? 'Analytics > Trends') ?></small>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted"><i class="bi bi-person-circle me-1"></i><?= esc($user['name'] ?? 'User') ?></span>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>

            <div class="main-content">
                <!-- Stock Movement Trends -->
                <div class="card-custom">
                    <h5><i class="bi bi-arrow-left-right me-2"></i>Stock Movement Trends (7 Days)</h5>
                    <canvas id="movementChart7" height="80"></canvas>
                </div>

                <div class="card-custom">
                    <h5><i class="bi bi-arrow-left-right me-2"></i>Stock Movement Trends (30 Days)</h5>
                    <canvas id="movementChart30" height="80"></canvas>
                </div>

                <!-- Orders Trends -->
                <div class="card-custom">
                    <h5><i class="bi bi-cart me-2"></i>Order Activity Trends</h5>
                    <canvas id="ordersChart" height="80"></canvas>
                </div>

                <!-- Summary Tables -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-custom">
                            <h5><i class="bi bi-calendar-week me-2"></i>7-Day Summary</h5>
                            <table class="table table-sm">
                                <tr><td>Total Stock In</td><td class="fw-bold text-success"><?= number_format($trends7['summary']['total_stock_in'] ?? 0) ?></td></tr>
                                <tr><td>Total Stock Out</td><td class="fw-bold text-danger"><?= number_format($trends7['summary']['total_stock_out'] ?? 0) ?></td></tr>
                                <tr><td>Net Change</td><td class="fw-bold"><?= number_format(($trends7['summary']['total_stock_in'] ?? 0) - ($trends7['summary']['total_stock_out'] ?? 0)) ?></td></tr>
                                <tr><td>Orders Created</td><td class="fw-bold"><?= number_format($trends7['summary']['orders_created'] ?? 0) ?></td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-custom">
                            <h5><i class="bi bi-calendar-month me-2"></i>30-Day Summary</h5>
                            <table class="table table-sm">
                                <tr><td>Total Stock In</td><td class="fw-bold text-success"><?= number_format($trends30['summary']['total_stock_in'] ?? 0) ?></td></tr>
                                <tr><td>Total Stock Out</td><td class="fw-bold text-danger"><?= number_format($trends30['summary']['total_stock_out'] ?? 0) ?></td></tr>
                                <tr><td>Net Change</td><td class="fw-bold"><?= number_format(($trends30['summary']['total_stock_in'] ?? 0) - ($trends30['summary']['total_stock_out'] ?? 0)) ?></td></tr>
                                <tr><td>Orders Created</td><td class="fw-bold"><?= number_format($trends30['summary']['orders_created'] ?? 0) ?></td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // 7-Day Chart
    new Chart(document.getElementById('movementChart7').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($trends7['labels'] ?? []) ?>,
            datasets: [
                { label: 'Stock In', data: <?= json_encode($trends7['stock_in'] ?? []) ?>, backgroundColor: '#2e7d32' },
                { label: 'Stock Out', data: <?= json_encode($trends7['stock_out'] ?? []) ?>, backgroundColor: '#c62828' }
            ]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

    // 30-Day Chart
    new Chart(document.getElementById('movementChart30').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?= json_encode($trends30['labels'] ?? []) ?>,
            datasets: [
                { label: 'Stock In', data: <?= json_encode($trends30['stock_in'] ?? []) ?>, borderColor: '#2e7d32', fill: false, tension: 0.3 },
                { label: 'Stock Out', data: <?= json_encode($trends30['stock_out'] ?? []) ?>, borderColor: '#c62828', fill: false, tension: 0.3 }
            ]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

    // Orders Chart
    new Chart(document.getElementById('ordersChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?= json_encode($trends30['labels'] ?? []) ?>,
            datasets: [
                { label: 'Orders', data: <?= json_encode($trends30['orders'] ?? []) ?>, borderColor: '#1565c0', backgroundColor: 'rgba(21,101,192,0.1)', fill: true, tension: 0.4 }
            ]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
</script>
</body>
</html>
