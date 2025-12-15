<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reorder Recommendations | WeBuild Warehouse System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
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
        .alert-count { font-size: 48px; font-weight: 700; }
        .table-reorder th { background: #f8f9fa; }
        .urgency-high { background: #ffebee; }
        .urgency-medium { background: #fff3e0; }
        .urgency-low { background: #e8f5e9; }
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
                <a class="nav-link" href="<?= base_url('analytics/trends') ?>"><i class="bi bi-graph-up"></i> Trends</a>
                <a class="nav-link" href="<?= base_url('analytics/forecasting') ?>"><i class="bi bi-graph-up-arrow"></i> Forecasting</a>
                <a class="nav-link active" href="<?= base_url('analytics/reorder-recommendations') ?>"><i class="bi bi-cart-check"></i> Reorder Recommendations</a>
            </nav>
        </div>

        <div class="col-md-10">
            <div class="topbar">
                <div>
                    <h4><i class="bi bi-cart-check me-2"></i><?= esc($pageTitle ?? 'Reorder Recommendations') ?></h4>
                    <small class="text-muted"><?= esc($breadcrumb ?? 'Analytics > Reorder') ?></small>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted"><i class="bi bi-person-circle me-1"></i><?= esc($user['name'] ?? 'User') ?></span>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>

            <div class="main-content">
                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card-custom text-center border-danger" style="border-left: 5px solid #c62828;">
                            <p class="alert-count text-danger"><?= count($needsReorder ?? []) ?></p>
                            <p class="text-muted mb-0">Items Need Reordering</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-custom text-center" style="border-left: 5px solid #1565c0;">
                            <p class="alert-count text-primary"><?= count($allItems ?? []) ?></p>
                            <p class="text-muted mb-0">Total Items Analyzed</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-custom text-center" style="border-left: 5px solid #2e7d32;">
                            <p class="alert-count text-success">
                                <?= count($allItems ?? []) > 0 ? round((count($allItems) - count($needsReorder ?? [])) / count($allItems) * 100) : 0 ?>%
                            </p>
                            <p class="text-muted mb-0">Stock Health</p>
                        </div>
                    </div>
                </div>

                <!-- Items Needing Reorder -->
                <div class="card-custom">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="bi bi-exclamation-triangle text-danger me-2"></i>Items Requiring Immediate Reorder</h5>
                        <a href="<?= base_url('analytics/export/reorder') ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-download"></i> Export List
                        </a>
                    </div>
                    
                    <?php if (!empty($needsReorder)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-reorder">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Current Stock</th>
                                        <th>Reorder Point</th>
                                        <th>Safety Stock</th>
                                        <th>Avg Daily Usage</th>
                                        <th>Days Until Stockout</th>
                                        <th>Suggested Order Qty</th>
                                        <th>Urgency</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($needsReorder as $item): ?>
                                        <?php 
                                        $daysLeft = $item['avg_daily_usage'] > 0 
                                            ? round($item['current_stock'] / $item['avg_daily_usage']) 
                                            : 999;
                                        $urgencyClass = $daysLeft <= 3 ? 'urgency-high' : ($daysLeft <= 7 ? 'urgency-medium' : 'urgency-low');
                                        $suggestedQty = max(0, ($item['reorder_point'] * 2) - $item['current_stock']);
                                        ?>
                                        <tr class="<?= $urgencyClass ?>">
                                            <td><strong><?= esc($item['product_name']) ?></strong></td>
                                            <td><code><?= esc($item['sku']) ?></code></td>
                                            <td class="text-danger fw-bold"><?= number_format($item['current_stock']) ?></td>
                                            <td><?= number_format($item['reorder_point']) ?></td>
                                            <td><?= number_format($item['safety_stock']) ?></td>
                                            <td><?= number_format($item['avg_daily_usage'], 2) ?>/day</td>
                                            <td>
                                                <?php if ($daysLeft <= 3): ?>
                                                    <span class="badge bg-danger"><?= $daysLeft ?> days</span>
                                                <?php elseif ($daysLeft <= 7): ?>
                                                    <span class="badge bg-warning"><?= $daysLeft ?> days</span>
                                                <?php else: ?>
                                                    <span class="badge bg-info"><?= $daysLeft == 999 ? 'N/A' : $daysLeft . ' days' ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="fw-bold"><?= number_format($suggestedQty) ?></td>
                                            <td>
                                                <?php if ($daysLeft <= 3): ?>
                                                    <span class="badge bg-danger"><i class="bi bi-exclamation-circle"></i> Critical</span>
                                                <?php elseif ($daysLeft <= 7): ?>
                                                    <span class="badge bg-warning"><i class="bi bi-exclamation-triangle"></i> High</span>
                                                <?php else: ?>
                                                    <span class="badge bg-info"><i class="bi bi-info-circle"></i> Medium</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle text-success" style="font-size: 64px;"></i>
                            <h4 class="mt-3 text-success">All Stock Levels Are Healthy!</h4>
                            <p class="text-muted">No items currently require reordering.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- All Items Status -->
                <div class="card-custom">
                    <h5><i class="bi bi-list-check me-2"></i>All Items Stock Status</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Current Stock</th>
                                    <th>Reorder Point</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($allItems)): ?>
                                    <?php foreach (array_slice($allItems, 0, 20) as $item): ?>
                                        <tr>
                                            <td><?= esc($item['product_name']) ?></td>
                                            <td><?= number_format($item['current_stock']) ?></td>
                                            <td><?= number_format($item['reorder_point']) ?></td>
                                            <td>
                                                <?php if ($item['needs_reorder']): ?>
                                                    <span class="badge bg-danger">Reorder Needed</span>
                                                <?php elseif ($item['current_stock'] <= $item['reorder_point'] * 1.5): ?>
                                                    <span class="badge bg-warning">Low Stock</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">OK</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
