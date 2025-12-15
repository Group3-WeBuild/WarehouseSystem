<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demand Forecasting | WeBuild Warehouse System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6fa;
        }

        .sidebar {
            background: linear-gradient(180deg, #1a237e 0%, #0d47a1 100%);
            color: #fff;
            min-height: 100vh;
            padding-top: 20px;
        }

        .sidebar h5 { text-align: center; font-weight: 700; margin-bottom: 25px; }
        .sidebar a {
            display: block; color: #cfd8dc; text-decoration: none;
            padding: 12px 20px; border-radius: 8px;
            margin: 5px 10px; font-size: 14px; transition: 0.3s;
        }
        .sidebar a:hover, .sidebar a.active { background-color: rgba(255,255,255,0.15); color: #fff; }
        .sidebar a i { margin-right: 10px; width: 20px; text-align: center; }

        .topbar {
            background: #fff; padding: 15px 30px;
            border-bottom: 1px solid #e0e0e0;
            display: flex; justify-content: space-between; align-items: center;
        }
        .topbar h4 { margin: 0; color: #1a237e; }

        .main-content { padding: 30px; }

        .card-custom {
            background: #fff; border-radius: 12px;
            padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .card-custom h5 { font-weight: 600; margin-bottom: 20px; color: #333; }

        .forecast-value {
            font-size: 32px; font-weight: 700; color: #1a237e;
        }
        .forecast-label { color: #666; font-size: 14px; }

        .method-card {
            background: #f8f9fa; border-radius: 10px;
            padding: 20px; text-align: center;
        }
        .method-card.active { background: #e3f2fd; border: 2px solid #1565c0; }

        .item-select { max-width: 400px; }

        .table-forecast th { background: #f8f9fa; font-weight: 600; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <h5><i class="bi bi-graph-up-arrow"></i> Analytics</h5>
            <a href="<?= base_url('analytics/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="<?= base_url('analytics/forecasting') ?>" class="active"><i class="bi bi-graph-up"></i> Demand Forecasting</a>
            <a href="<?= base_url('analytics/inventory-kpis') ?>"><i class="bi bi-box-seam"></i> Inventory KPIs</a>
            <a href="<?= base_url('analytics/warehouse-performance') ?>"><i class="bi bi-building"></i> Warehouse Performance</a>
            <a href="<?= base_url('analytics/financial-kpis') ?>"><i class="bi bi-currency-dollar"></i> Financial KPIs</a>
            <a href="<?= base_url('analytics/trends') ?>"><i class="bi bi-bar-chart-line"></i> Trend Analysis</a>
            <a href="<?= base_url('analytics/reorder-recommendations') ?>"><i class="bi bi-cart-check"></i> Reorder Recommendations</a>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <a href="<?= base_url('dashboard') ?>"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10">
            <div class="topbar">
                <div>
                    <h4><i class="bi bi-graph-up me-2"></i><?= esc($pageTitle ?? 'Demand Forecasting') ?></h4>
                    <small class="text-muted"><?= esc($breadcrumb ?? 'Analytics > Forecasting') ?></small>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted">
                        <i class="bi bi-person-circle me-1"></i>
                        <?= esc($user['name'] ?? 'User') ?>
                    </span>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>

            <div class="main-content">
                <!-- Item Selection -->
                <div class="card-custom">
                    <h5><i class="bi bi-search me-2"></i>Select Product for Forecasting</h5>
                    <form method="get" action="<?= base_url('analytics/forecasting') ?>">
                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <label class="form-label">Product / Inventory Item</label>
                                <select name="item_id" class="form-select item-select" onchange="this.form.submit()">
                                    <option value="">-- Select a product --</option>
                                    <?php if (!empty($items)): ?>
                                        <?php foreach ($items as $item): ?>
                                            <option value="<?= $item['id'] ?>" 
                                                <?= isset($selectedItem) && $selectedItem['id'] == $item['id'] ? 'selected' : '' ?>>
                                                <?= esc($item['product_name']) ?> (SKU: <?= esc($item['sku']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-calculator"></i> Generate Forecast
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <?php if (isset($selectedItem)): ?>
                <!-- Selected Item Info -->
                <div class="card-custom">
                    <h5><i class="bi bi-box me-2"></i>Product Details</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Product Name:</strong></p>
                            <p class="text-primary"><?= esc($selectedItem['product_name']) ?></p>
                        </div>
                        <div class="col-md-2">
                            <p class="mb-1"><strong>SKU:</strong></p>
                            <p><?= esc($selectedItem['sku']) ?></p>
                        </div>
                        <div class="col-md-2">
                            <p class="mb-1"><strong>Current Stock:</strong></p>
                            <p class="fw-bold"><?= number_format($selectedItem['quantity']) ?> <?= esc($selectedItem['unit']) ?></p>
                        </div>
                        <div class="col-md-2">
                            <p class="mb-1"><strong>Reorder Point:</strong></p>
                            <p><?= number_format($selectedItem['reorder_point']) ?></p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Unit Price:</strong></p>
                            <p>₱<?= number_format($selectedItem['unit_price'], 2) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Forecast Results -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-custom">
                            <h5><i class="bi bi-graph-up me-2"></i>Moving Average Forecast (14 Days)</h5>
                            <?php if (!empty($movingAverage)): ?>
                                <div class="row text-center mb-4">
                                    <div class="col-6">
                                        <div class="method-card">
                                            <p class="forecast-label">Predicted Daily Demand</p>
                                            <p class="forecast-value"><?= number_format($movingAverage['predicted_demand'], 1) ?></p>
                                            <small class="text-muted">units/day</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="method-card">
                                            <p class="forecast-label">Total Period Demand</p>
                                            <p class="forecast-value"><?= number_format($movingAverage['total_period_demand'], 0) ?></p>
                                            <small class="text-muted">units in 14 days</small>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-sm table-forecast">
                                    <tr>
                                        <th>Forecast Period</th>
                                        <td><?= $movingAverage['forecast_period'] ?> days</td>
                                    </tr>
                                    <tr>
                                        <th>Historical Data Used</th>
                                        <td><?= $movingAverage['historical_days'] ?> days</td>
                                    </tr>
                                    <tr>
                                        <th>Confidence Level</th>
                                        <td>
                                            <?php if ($movingAverage['data_points'] >= 10): ?>
                                                <span class="badge bg-success">High</span>
                                            <?php elseif ($movingAverage['data_points'] >= 5): ?>
                                                <span class="badge bg-warning">Medium</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Low</span>
                                            <?php endif; ?>
                                            (<?= $movingAverage['data_points'] ?> data points)
                                        </td>
                                    </tr>
                                </table>
                            <?php else: ?>
                                <p class="text-muted">Insufficient data for moving average forecast.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-custom">
                            <h5><i class="bi bi-calculator me-2"></i>Exponential Smoothing Forecast</h5>
                            <?php if (!empty($exponentialSmoothing)): ?>
                                <div class="row text-center mb-4">
                                    <div class="col-6">
                                        <div class="method-card">
                                            <p class="forecast-label">Predicted Daily Demand</p>
                                            <p class="forecast-value"><?= number_format($exponentialSmoothing['predicted_demand'], 1) ?></p>
                                            <small class="text-muted">units/day</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="method-card">
                                            <p class="forecast-label">Total Period Demand</p>
                                            <p class="forecast-value"><?= number_format($exponentialSmoothing['total_period_demand'], 0) ?></p>
                                            <small class="text-muted">units in 14 days</small>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-sm table-forecast">
                                    <tr>
                                        <th>Smoothing Factor (α)</th>
                                        <td><?= $exponentialSmoothing['alpha'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Forecast Period</th>
                                        <td><?= $exponentialSmoothing['forecast_period'] ?> days</td>
                                    </tr>
                                    <tr>
                                        <th>Responsiveness</th>
                                        <td>
                                            <?php if ($exponentialSmoothing['alpha'] >= 0.5): ?>
                                                <span class="badge bg-info">High (Recent data weighted)</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Moderate (Balanced)</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            <?php else: ?>
                                <p class="text-muted">Insufficient data for exponential smoothing forecast.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Seasonal Analysis -->
                <?php if (!empty($seasonalAnalysis)): ?>
                <div class="card-custom">
                    <h5><i class="bi bi-calendar3 me-2"></i>Seasonal Analysis (Last 90 Days)</h5>
                    <div class="row">
                        <div class="col-md-8">
                            <canvas id="seasonalChart" height="100"></canvas>
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-3">Day of Week Patterns</h6>
                            <table class="table table-sm">
                                <thead>
                                    <tr><th>Day</th><th>Avg Demand</th><th>Index</th></tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                    foreach ($seasonalAnalysis['day_of_week'] as $dayNum => $data): 
                                    ?>
                                        <tr>
                                            <td><?= $days[$dayNum] ?? $dayNum ?></td>
                                            <td><?= number_format($data['average'], 1) ?></td>
                                            <td>
                                                <?php 
                                                $index = $data['index'];
                                                $class = $index > 1.1 ? 'text-success' : ($index < 0.9 ? 'text-danger' : 'text-muted');
                                                ?>
                                                <span class="<?= $class ?>"><?= number_format($index, 2) ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <script>
                    const seasonalCtx = document.getElementById('seasonalChart').getContext('2d');
                    new Chart(seasonalCtx, {
                        type: 'bar',
                        data: {
                            labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                            datasets: [{
                                label: 'Average Daily Demand',
                                data: [
                                    <?php foreach ($seasonalAnalysis['day_of_week'] as $data): ?>
                                        <?= $data['average'] ?>,
                                    <?php endforeach; ?>
                                ],
                                backgroundColor: '#1565c0'
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: { y: { beginAtZero: true } }
                        }
                    });
                </script>
                <?php endif; ?>

                <?php else: ?>
                <!-- No Item Selected -->
                <div class="card-custom text-center py-5">
                    <i class="bi bi-graph-up" style="font-size: 64px; color: #ccc;"></i>
                    <h4 class="mt-3 text-muted">Select a Product to View Forecasts</h4>
                    <p class="text-muted">Choose an inventory item from the dropdown above to generate demand forecasts using multiple methods.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
