<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Financial KPIs | WeBuild Warehouse System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f6fa; }
        .sidebar { background: linear-gradient(180deg, #1a237e 0%, #0d47a1 100%); color: #fff; min-height: 100vh; padding-top: 20px; }
        .sidebar h5 { text-align: center; font-weight: 700; margin-bottom: 25px; }
        .sidebar a { display: block; color: #cfd8dc; text-decoration: none; padding: 12px 20px; border-radius: 8px; margin: 5px 10px; font-size: 14px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: rgba(255,255,255,0.15); color: #fff; }
        .sidebar a i { margin-right: 10px; width: 20px; text-align: center; }
        .topbar { background: #fff; padding: 15px 30px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; }
        .topbar h4 { margin: 0; color: #1a237e; }
        .main-content { padding: 30px; }
        .card-custom { background: #fff; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .card-custom h5 { font-weight: 600; margin-bottom: 20px; color: #333; }
        .kpi-big { font-size: 32px; font-weight: 700; }
        .kpi-label { color: #666; font-size: 14px; }
        .period-card { border-radius: 10px; padding: 20px; }
        .period-30 { background: #e3f2fd; border: 1px solid #1565c0; }
        .period-90 { background: #e8f5e9; border: 1px solid #2e7d32; }
        .period-365 { background: #fff3e0; border: 1px solid #ef6c00; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar">
            <h5><i class="bi bi-graph-up-arrow"></i> Analytics</h5>
            <a href="<?= base_url('analytics/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="<?= base_url('analytics/forecasting') ?>"><i class="bi bi-graph-up"></i> Demand Forecasting</a>
            <a href="<?= base_url('analytics/inventory-kpis') ?>"><i class="bi bi-box-seam"></i> Inventory KPIs</a>
            <a href="<?= base_url('analytics/warehouse-performance') ?>"><i class="bi bi-building"></i> Warehouse Performance</a>
            <a href="<?= base_url('analytics/financial-kpis') ?>" class="active"><i class="bi bi-currency-dollar"></i> Financial KPIs</a>
            <a href="<?= base_url('analytics/trends') ?>"><i class="bi bi-bar-chart-line"></i> Trend Analysis</a>
            <a href="<?= base_url('analytics/reorder-recommendations') ?>"><i class="bi bi-cart-check"></i> Reorder Recommendations</a>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <a href="<?= base_url('dashboard') ?>"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
        </div>

        <div class="col-md-10">
            <div class="topbar">
                <div>
                    <h4><i class="bi bi-currency-dollar me-2"></i><?= esc($pageTitle ?? 'Financial KPIs') ?></h4>
                    <small class="text-muted"><?= esc($breadcrumb ?? 'Analytics > Financial KPIs') ?></small>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted"><i class="bi bi-person-circle me-1"></i><?= esc($user['name'] ?? 'User') ?></span>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>

            <div class="main-content">
                <!-- Period Comparison -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="period-card period-30">
                            <h6 class="text-primary"><i class="bi bi-calendar-week me-2"></i>Last 30 Days</h6>
                            <hr>
                            <div class="mb-3">
                                <p class="kpi-label mb-1">Total Revenue</p>
                                <p class="kpi-big text-primary">₱<?= number_format($kpis30['total_revenue'] ?? 0, 2) ?></p>
                            </div>
                            <div class="mb-3">
                                <p class="kpi-label mb-1">Accounts Receivable</p>
                                <p class="h5">₱<?= number_format($kpis30['accounts_receivable'] ?? 0, 2) ?></p>
                            </div>
                            <div class="mb-3">
                                <p class="kpi-label mb-1">Accounts Payable</p>
                                <p class="h5">₱<?= number_format($kpis30['accounts_payable'] ?? 0, 2) ?></p>
                            </div>
                            <div>
                                <p class="kpi-label mb-1">Orders Completed</p>
                                <p class="h5"><?= number_format($kpis30['orders_completed'] ?? 0) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="period-card period-90">
                            <h6 class="text-success"><i class="bi bi-calendar-month me-2"></i>Last 90 Days</h6>
                            <hr>
                            <div class="mb-3">
                                <p class="kpi-label mb-1">Total Revenue</p>
                                <p class="kpi-big text-success">₱<?= number_format($kpis90['total_revenue'] ?? 0, 2) ?></p>
                            </div>
                            <div class="mb-3">
                                <p class="kpi-label mb-1">Accounts Receivable</p>
                                <p class="h5">₱<?= number_format($kpis90['accounts_receivable'] ?? 0, 2) ?></p>
                            </div>
                            <div class="mb-3">
                                <p class="kpi-label mb-1">Accounts Payable</p>
                                <p class="h5">₱<?= number_format($kpis90['accounts_payable'] ?? 0, 2) ?></p>
                            </div>
                            <div>
                                <p class="kpi-label mb-1">Orders Completed</p>
                                <p class="h5"><?= number_format($kpis90['orders_completed'] ?? 0) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="period-card period-365">
                            <h6 class="text-warning"><i class="bi bi-calendar me-2"></i>Last 365 Days</h6>
                            <hr>
                            <div class="mb-3">
                                <p class="kpi-label mb-1">Total Revenue</p>
                                <p class="kpi-big text-warning">₱<?= number_format($kpis365['total_revenue'] ?? 0, 2) ?></p>
                            </div>
                            <div class="mb-3">
                                <p class="kpi-label mb-1">Accounts Receivable</p>
                                <p class="h5">₱<?= number_format($kpis365['accounts_receivable'] ?? 0, 2) ?></p>
                            </div>
                            <div class="mb-3">
                                <p class="kpi-label mb-1">Accounts Payable</p>
                                <p class="h5">₱<?= number_format($kpis365['accounts_payable'] ?? 0, 2) ?></p>
                            </div>
                            <div>
                                <p class="kpi-label mb-1">Orders Completed</p>
                                <p class="h5"><?= number_format($kpis365['orders_completed'] ?? 0) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Value -->
                <div class="card-custom">
                    <h5><i class="bi bi-box-seam me-2"></i>Inventory Value Metrics</h5>
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <p class="kpi-label">Current Inventory Value</p>
                            <p class="kpi-big text-primary">₱<?= number_format($kpis30['inventory_value'] ?? 0, 2) ?></p>
                        </div>
                        <div class="col-md-4 text-center">
                            <p class="kpi-label">Outstanding Receivables</p>
                            <p class="kpi-big text-success">₱<?= number_format($kpis30['accounts_receivable'] ?? 0, 2) ?></p>
                        </div>
                        <div class="col-md-4 text-center">
                            <p class="kpi-label">Outstanding Payables</p>
                            <p class="kpi-big text-danger">₱<?= number_format($kpis30['accounts_payable'] ?? 0, 2) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Trend Chart -->
                <div class="card-custom">
                    <h5><i class="bi bi-graph-up me-2"></i>Revenue Trends (Last 30 Days)</h5>
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($trends['labels'] ?? ['Week 1', 'Week 2', 'Week 3', 'Week 4']) ?>,
            datasets: [{
                label: 'Revenue',
                data: <?= json_encode($trends['revenue'] ?? [0, 0, 0, 0]) ?>,
                borderColor: '#1565c0',
                backgroundColor: 'rgba(21, 101, 192, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
</body>
</html>
