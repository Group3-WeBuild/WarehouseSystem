<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory KPIs | WeBuild Warehouse System</title>
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
        .kpi-big { font-size: 36px; font-weight: 700; color: #1a237e; }
        .abc-a { background: #e8f5e9; border-left: 4px solid #2e7d32; }
        .abc-b { background: #fff3e0; border-left: 4px solid #ef6c00; }
        .abc-c { background: #ffebee; border-left: 4px solid #c62828; }
        .table-kpi th { background: #f8f9fa; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar">
            <h5><i class="bi bi-graph-up-arrow"></i> Analytics</h5>
            <a href="<?= base_url('analytics/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="<?= base_url('analytics/forecasting') ?>"><i class="bi bi-graph-up"></i> Demand Forecasting</a>
            <a href="<?= base_url('analytics/inventory-kpis') ?>" class="active"><i class="bi bi-box-seam"></i> Inventory KPIs</a>
            <a href="<?= base_url('analytics/warehouse-performance') ?>"><i class="bi bi-building"></i> Warehouse Performance</a>
            <a href="<?= base_url('analytics/financial-kpis') ?>"><i class="bi bi-currency-dollar"></i> Financial KPIs</a>
            <a href="<?= base_url('analytics/trends') ?>"><i class="bi bi-bar-chart-line"></i> Trend Analysis</a>
            <a href="<?= base_url('analytics/reorder-recommendations') ?>"><i class="bi bi-cart-check"></i> Reorder Recommendations</a>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <a href="<?= base_url('dashboard') ?>"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
        </div>

        <div class="col-md-10">
            <div class="topbar">
                <div>
                    <h4><i class="bi bi-box-seam me-2"></i><?= esc($pageTitle ?? 'Inventory KPIs') ?></h4>
                    <small class="text-muted"><?= esc($breadcrumb ?? 'Analytics > Inventory KPIs') ?></small>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted"><i class="bi bi-person-circle me-1"></i><?= esc($user['name'] ?? 'User') ?></span>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>

            <div class="main-content">
                <!-- ABC Analysis -->
                <div class="card-custom">
                    <h5><i class="bi bi-bar-chart-steps me-2"></i>ABC Analysis</h5>
                    <p class="text-muted mb-4">Pareto analysis classifying inventory items by value contribution.</p>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card-custom abc-a">
                                <h6>Class A (High Value)</h6>
                                <p class="kpi-big"><?= count($abcAnalysis['A'] ?? []) ?></p>
                                <p class="text-muted">Items (~20% of items, ~80% of value)</p>
                                <small>₱<?= number_format(array_sum(array_column($abcAnalysis['A'] ?? [], 'total_value')), 2) ?></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-custom abc-b">
                                <h6>Class B (Medium Value)</h6>
                                <p class="kpi-big"><?= count($abcAnalysis['B'] ?? []) ?></p>
                                <p class="text-muted">Items (~30% of items, ~15% of value)</p>
                                <small>₱<?= number_format(array_sum(array_column($abcAnalysis['B'] ?? [], 'total_value')), 2) ?></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-custom abc-c">
                                <h6>Class C (Low Value)</h6>
                                <p class="kpi-big"><?= count($abcAnalysis['C'] ?? []) ?></p>
                                <p class="text-muted">Items (~50% of items, ~5% of value)</p>
                                <small>₱<?= number_format(array_sum(array_column($abcAnalysis['C'] ?? [], 'total_value')), 2) ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Turnover -->
                <div class="card-custom">
                    <h5><i class="bi bi-arrow-repeat me-2"></i>Inventory Turnover Ratios</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-kpi">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Turnover Ratio</th>
                                    <th>Days of Supply</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($turnoverRatios)): ?>
                                    <?php foreach (array_slice($turnoverRatios, 0, 15) as $item): ?>
                                        <tr>
                                            <td><?= esc($item['product_name']) ?></td>
                                            <td><code><?= esc($item['sku']) ?></code></td>
                                            <td>
                                                <strong><?= number_format($item['turnover_ratio'], 2) ?></strong>
                                            </td>
                                            <td><?= $item['days_of_supply'] == 999 ? '∞' : number_format($item['days_of_supply'], 0) ?> days</td>
                                            <td>
                                                <?php if ($item['turnover_ratio'] >= 6): ?>
                                                    <span class="badge bg-success">Fast Moving</span>
                                                <?php elseif ($item['turnover_ratio'] >= 2): ?>
                                                    <span class="badge bg-warning">Normal</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Slow Moving</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center text-muted">No turnover data available</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Reorder Points -->
                <div class="card-custom">
                    <h5><i class="bi bi-cart-check me-2"></i>Calculated Reorder Points</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-kpi">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Current Stock</th>
                                    <th>Avg Daily Usage</th>
                                    <th>Calculated ROP</th>
                                    <th>Safety Stock</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($reorderPoints)): ?>
                                    <?php foreach (array_slice($reorderPoints, 0, 15) as $item): ?>
                                        <tr class="<?= $item['needs_reorder'] ? 'table-warning' : '' ?>">
                                            <td><?= esc($item['product_name']) ?></td>
                                            <td><?= number_format($item['current_stock']) ?></td>
                                            <td><?= number_format($item['avg_daily_usage'], 2) ?></td>
                                            <td><strong><?= number_format($item['reorder_point']) ?></strong></td>
                                            <td><?= number_format($item['safety_stock']) ?></td>
                                            <td>
                                                <?php if ($item['needs_reorder']): ?>
                                                    <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> Reorder Now</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success"><i class="bi bi-check"></i> OK</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center text-muted">No reorder point data available</td></tr>
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
