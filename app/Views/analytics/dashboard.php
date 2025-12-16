<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Analytics Dashboard | WeBuild Warehouse System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6fa;
            margin: 0;
            padding: 0;
        }

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

        .topbar {
            background: #fff;
            padding: 15px 30px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h4 {
            margin: 0;
            color: #1a237e;
        }

        .main-content {
            padding: 30px;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .stat-card.primary .icon { background: #e3f2fd; color: #1565c0; }
        .stat-card.success .icon { background: #e8f5e9; color: #2e7d32; }
        .stat-card.warning .icon { background: #fff3e0; color: #ef6c00; }
        .stat-card.danger .icon { background: #ffebee; color: #c62828; }
        .stat-card.info .icon { background: #e0f7fa; color: #00838f; }

        .stat-card h3 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .stat-card p {
            color: #666;
            margin: 0;
            font-size: 14px;
        }

        .chart-container {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .chart-container h5 {
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .kpi-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .kpi-list li {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kpi-list li:last-child {
            border-bottom: none;
        }

        .kpi-list .label {
            color: #666;
            font-size: 14px;
        }

        .kpi-list .value {
            font-weight: 600;
            font-size: 18px;
            color: #1a237e;
        }

        .trend-up { color: #2e7d32; }
        .trend-down { color: #c62828; }
        .trend-neutral { color: #666; }

        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .quick-link {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: 0.3s;
        }

        .quick-link:hover {
            background: #1a237e;
            color: #fff;
        }

        .quick-link i {
            font-size: 24px;
            margin-right: 15px;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #e3f2fd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1565c0;
        }

        .quick-link:hover i {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 px-0 sidebar">
            <div class="text-center py-4">
                <h5 class="text-white mb-1">WeBuild</h5>
                <small class="text-white-50">Analytics</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link active" href="<?= base_url('analytics/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a class="nav-link" href="<?= base_url('analytics/inventory-kpis') ?>"><i class="bi bi-boxes"></i> Inventory KPIs</a>
                <a class="nav-link" href="<?= base_url('analytics/financial-kpis') ?>"><i class="bi bi-currency-dollar"></i> Financial KPIs</a>
                <a class="nav-link" href="<?= base_url('analytics/warehouse-performance') ?>"><i class="bi bi-building"></i> Warehouse Performance</a>
                <a class="nav-link" href="<?= base_url('analytics/trends') ?>"><i class="bi bi-graph-up"></i> Trends</a>
                <a class="nav-link" href="<?= base_url('analytics/forecasting') ?>"><i class="bi bi-graph-up-arrow"></i> Forecasting</a>
                <a class="nav-link" href="<?= base_url('analytics/reorder-recommendations') ?>"><i class="bi bi-cart-check"></i> Reorder Recommendations</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10">
            <div class="topbar">
                <div>
                    <h4><i class="bi bi-graph-up-arrow me-2"></i><?= esc($pageTitle ?? 'Analytics Dashboard') ?></h4>
                    <small class="text-muted"><?= esc($breadcrumb ?? 'Analytics') ?></small>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted">
                        <i class="bi bi-person-circle me-1"></i>
                        <?= esc($user['name'] ?? 'User') ?> (<?= esc($user['role'] ?? '') ?>)
                    </span>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>

            <div class="main-content">
                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Quick Stats Row -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card primary">
                            <div class="icon"><i class="bi bi-box-seam"></i></div>
                            <h3><?= number_format($summary['total_items'] ?? 0) ?></h3>
                            <p>Total Inventory Items</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card success">
                            <div class="icon"><i class="bi bi-currency-dollar"></i></div>
                            <h3>₱<?= number_format($summary['total_inventory_value'] ?? 0, 2) ?></h3>
                            <p>Total Inventory Value</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card warning">
                            <div class="icon"><i class="bi bi-exclamation-triangle"></i></div>
                            <h3><?= number_format($summary['low_stock_count'] ?? 0) ?></h3>
                            <p>Low Stock Alerts</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card info">
                            <div class="icon"><i class="bi bi-arrow-repeat"></i></div>
                            <h3><?= number_format($summary['stock_movements_today'] ?? 0) ?></h3>
                            <p>Stock Movements Today</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card success">
                            <div class="icon"><i class="bi bi-check-circle"></i></div>
                            <h3><?= number_format($summary['orders_completed'] ?? 0) ?></h3>
                            <p>Orders Completed</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card warning">
                            <div class="icon"><i class="bi bi-clock"></i></div>
                            <h3><?= number_format($summary['orders_pending'] ?? 0) ?></h3>
                            <p>Orders Pending</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card primary">
                            <div class="icon"><i class="bi bi-building"></i></div>
                            <h3><?= number_format($summary['active_warehouses'] ?? 0) ?></h3>
                            <p>Active Warehouses</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card danger">
                            <div class="icon"><i class="bi bi-people"></i></div>
                            <h3><?= number_format($summary['active_users'] ?? 0) ?></h3>
                            <p>Active Users</p>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mt-4">
                    <div class="col-md-8">
                        <div class="chart-container">
                            <h5><i class="bi bi-bar-chart me-2"></i>Stock Movement Trends (Last 30 Days)</h5>
                            <canvas id="movementChart" height="120"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="chart-container">
                            <h5><i class="bi bi-pie-chart me-2"></i>Inventory Distribution</h5>
                            <canvas id="distributionChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h5 class="mb-3"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                        <div class="quick-links">
                            <a href="<?= base_url('analytics/forecasting') ?>" class="quick-link">
                                <i class="bi bi-graph-up"></i>
                                <div>
                                    <strong>Demand Forecasting</strong>
                                    <small class="d-block text-muted">Predict future demand</small>
                                </div>
                            </a>
                            <a href="<?= base_url('analytics/reorder-recommendations') ?>" class="quick-link">
                                <i class="bi bi-cart-check"></i>
                                <div>
                                    <strong>Reorder Alerts</strong>
                                    <small class="d-block text-muted">Items needing restock</small>
                                </div>
                            </a>
                            <a href="<?= base_url('analytics/export/summary') ?>" class="quick-link">
                                <i class="bi bi-download"></i>
                                <div>
                                    <strong>Export Report</strong>
                                    <small class="d-block text-muted">Download analytics data</small>
                                </div>
                            </a>
                            <a href="<?= base_url('analytics/inventory-kpis') ?>" class="quick-link">
                                <i class="bi bi-speedometer2"></i>
                                <div>
                                    <strong>Inventory KPIs</strong>
                                    <small class="d-block text-muted">Turnover & metrics</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Stock Movement Chart
    const movementCtx = document.getElementById('movementChart').getContext('2d');
    new Chart(movementCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($summary['movement_dates'] ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) ?>,
            datasets: [
                {
                    label: 'Stock In',
                    data: <?= json_encode($summary['stock_in_data'] ?? [10, 25, 15, 30, 22, 18, 28]) ?>,
                    borderColor: '#2e7d32',
                    backgroundColor: 'rgba(46, 125, 50, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Stock Out',
                    data: <?= json_encode($summary['stock_out_data'] ?? [8, 20, 12, 25, 18, 15, 20]) ?>,
                    borderColor: '#c62828',
                    backgroundColor: 'rgba(198, 40, 40, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Distribution Chart
    const distCtx = document.getElementById('distributionChart').getContext('2d');
    new Chart(distCtx, {
        type: 'doughnut',
        data: {
            labels: ['Normal Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [
                    <?= $summary['normal_stock'] ?? 80 ?>,
                    <?= $summary['low_stock_count'] ?? 15 ?>,
                    <?= $summary['out_of_stock'] ?? 5 ?>
                ],
                backgroundColor: ['#2e7d32', '#ef6c00', '#c62828']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
</body>
</html>
