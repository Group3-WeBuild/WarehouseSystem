<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Warehouse Performance | WeBuild Warehouse System</title>
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
        .kpi-big { font-size: 42px; font-weight: 700; }
        .kpi-label { color: #666; font-size: 14px; }
        .warehouse-card { border-left: 4px solid #1565c0; }
        .progress { height: 25px; border-radius: 8px; }
        .progress-bar { font-weight: 600; }
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
            <a href="<?= base_url('analytics/warehouse-performance') ?>" class="active"><i class="bi bi-building"></i> Warehouse Performance</a>
            <a href="<?= base_url('analytics/financial-kpis') ?>"><i class="bi bi-currency-dollar"></i> Financial KPIs</a>
            <a href="<?= base_url('analytics/trends') ?>"><i class="bi bi-bar-chart-line"></i> Trend Analysis</a>
            <a href="<?= base_url('analytics/reorder-recommendations') ?>"><i class="bi bi-cart-check"></i> Reorder Recommendations</a>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <a href="<?= base_url('dashboard') ?>"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
        </div>

        <div class="col-md-10">
            <div class="topbar">
                <div>
                    <h4><i class="bi bi-building me-2"></i><?= esc($pageTitle ?? 'Warehouse Performance') ?></h4>
                    <small class="text-muted"><?= esc($breadcrumb ?? 'Analytics > Warehouse Performance') ?></small>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted"><i class="bi bi-person-circle me-1"></i><?= esc($user['name'] ?? 'User') ?></span>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>

            <div class="main-content">
                <!-- Summary Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card-custom text-center">
                            <div class="kpi-big text-primary"><?= count($performance['warehouses'] ?? []) ?></div>
                            <p class="kpi-label">Active Warehouses</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card-custom text-center">
                            <div class="kpi-big text-success"><?= number_format($performance['total_items'] ?? 0) ?></div>
                            <p class="kpi-label">Total Items Stored</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card-custom text-center">
                            <div class="kpi-big text-warning"><?= number_format($performance['total_movements'] ?? 0) ?></div>
                            <p class="kpi-label">Movements (30 days)</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card-custom text-center">
                            <div class="kpi-big text-info"><?= number_format($performance['avg_utilization'] ?? 0, 1) ?>%</div>
                            <p class="kpi-label">Avg. Utilization</p>
                        </div>
                    </div>
                </div>

                <!-- Individual Warehouse Performance -->
                <div class="card-custom">
                    <h5><i class="bi bi-building me-2"></i>Warehouse Details</h5>
                    <div class="row">
                        <?php if (!empty($performance['warehouses'])): ?>
                            <?php foreach ($performance['warehouses'] as $warehouse): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card-custom warehouse-card">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="mb-1"><?= esc($warehouse['name']) ?></h6>
                                                <small class="text-muted"><?= esc($warehouse['location']) ?></small>
                                            </div>
                                            <span class="badge bg-<?= $warehouse['status'] == 'Active' ? 'success' : 'secondary' ?>">
                                                <?= esc($warehouse['status']) ?>
                                            </span>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Capacity Utilization</span>
                                                <strong><?= number_format($warehouse['utilization'], 1) ?>%</strong>
                                            </div>
                                            <div class="progress">
                                                <?php 
                                                $util = $warehouse['utilization'];
                                                $barClass = $util >= 90 ? 'bg-danger' : ($util >= 70 ? 'bg-warning' : 'bg-success');
                                                ?>
                                                <div class="progress-bar <?= $barClass ?>" style="width: <?= min($util, 100) ?>%">
                                                    <?= number_format($util, 1) ?>%
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row text-center">
                                            <div class="col-4">
                                                <p class="mb-0 fw-bold"><?= number_format($warehouse['item_count']) ?></p>
                                                <small class="text-muted">Items</small>
                                            </div>
                                            <div class="col-4">
                                                <p class="mb-0 fw-bold"><?= number_format($warehouse['total_quantity']) ?></p>
                                                <small class="text-muted">Units</small>
                                            </div>
                                            <div class="col-4">
                                                <p class="mb-0 fw-bold">₱<?= number_format($warehouse['total_value'] / 1000, 1) ?>K</p>
                                                <small class="text-muted">Value</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <p class="text-muted text-center">No warehouse data available</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Performance Chart -->
                <div class="card-custom">
                    <h5><i class="bi bi-bar-chart me-2"></i>Warehouse Comparison</h5>
                    <canvas id="warehouseChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const ctx = document.getElementById('warehouseChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php 
                if (!empty($performance['warehouses'])) {
                    echo implode(',', array_map(fn($w) => "'" . esc($w['name']) . "'", $performance['warehouses']));
                }
            ?>],
            datasets: [
                {
                    label: 'Items Count',
                    data: [<?php 
                        if (!empty($performance['warehouses'])) {
                            echo implode(',', array_column($performance['warehouses'], 'item_count'));
                        }
                    ?>],
                    backgroundColor: '#1565c0'
                },
                {
                    label: 'Utilization %',
                    data: [<?php 
                        if (!empty($performance['warehouses'])) {
                            echo implode(',', array_column($performance['warehouses'], 'utilization'));
                        }
                    ?>],
                    backgroundColor: '#2e7d32'
                }
            ]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
</body>
</html>
