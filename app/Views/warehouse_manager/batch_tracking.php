<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batch Tracking | Warehouse Manager | WeBuild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6fa;
        }
        .navbar-custom {
            background-color: #1565c0;
        }
        .page-header {
            background: linear-gradient(135deg, #1565c0, #0d47a1);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
            font-weight: 600;
        }
        .badge-active { background-color: #28a745; }
        .badge-expiring { background-color: #ffc107; color: #000; }
        .badge-quarantine { background-color: #dc3545; }
        .table th { background-color: #f8f9fa; }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('warehouse-manager/dashboard') ?>">
                <strong>WeBuild</strong> Warehouse
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <?= esc($user['name'] ?? 'User') ?> | <?= esc($user['role'] ?? 'Warehouse Manager') ?>
                </span>
                <a class="btn btn-outline-light btn-sm" href="<?= base_url('logout') ?>">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Back Button & Header -->
        <div class="mb-3">
            <a href="<?= base_url('warehouse-manager/dashboard') ?>" class="btn btn-outline-primary">
                ← Back to Dashboard
            </a>
        </div>

        <div class="page-header">
            <h2>📦 Batch Tracking</h2>
            <p class="mb-0">Monitor batch inventory, expiration dates, and quarantine status</p>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success"><?= count($activeBatches ?? []) ?></h3>
                        <p class="mb-0">Active Batches</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning"><?= count($expiringBatches ?? []) ?></h3>
                        <p class="mb-0">Expiring Soon</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-danger"><?= count($quarantined ?? []) ?></h3>
                        <p class="mb-0">Quarantined</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Batches -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>✅ Active Batches</span>
                <span class="badge bg-success"><?= count($activeBatches ?? []) ?></span>
            </div>
            <div class="card-body">
                <?php if (!empty($activeBatches)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Batch #</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Manufacturing Date</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($activeBatches as $batch): ?>
                            <tr>
                                <td><strong><?= esc($batch['batch_number'] ?? 'N/A') ?></strong></td>
                                <td><?= esc($batch['product_name'] ?? $batch['item_name'] ?? 'N/A') ?></td>
                                <td><?= number_format($batch['quantity'] ?? 0) ?></td>
                                <td><?= isset($batch['manufacturing_date']) ? date('M d, Y', strtotime($batch['manufacturing_date'])) : 'N/A' ?></td>
                                <td><?= isset($batch['expiry_date']) ? date('M d, Y', strtotime($batch['expiry_date'])) : 'N/A' ?></td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <div>📦</div>
                    <p>No active batches found</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Expiring Soon -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>⚠️ Expiring Soon (Next 30 Days)</span>
                <span class="badge bg-warning text-dark"><?= count($expiringBatches ?? []) ?></span>
            </div>
            <div class="card-body">
                <?php if (!empty($expiringBatches)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Batch #</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Expiry Date</th>
                                <th>Days Left</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($expiringBatches as $batch): ?>
                            <?php 
                                $daysLeft = isset($batch['expiry_date']) ? 
                                    floor((strtotime($batch['expiry_date']) - time()) / 86400) : 0;
                            ?>
                            <tr class="<?= $daysLeft <= 7 ? 'table-danger' : 'table-warning' ?>">
                                <td><strong><?= esc($batch['batch_number'] ?? 'N/A') ?></strong></td>
                                <td><?= esc($batch['product_name'] ?? $batch['item_name'] ?? 'N/A') ?></td>
                                <td><?= number_format($batch['quantity'] ?? 0) ?></td>
                                <td><?= isset($batch['expiry_date']) ? date('M d, Y', strtotime($batch['expiry_date'])) : 'N/A' ?></td>
                                <td><strong><?= $daysLeft ?> days</strong></td>
                                <td>
                                    <button class="btn btn-sm btn-warning">Move to Quarantine</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <div>✅</div>
                    <p>No batches expiring soon</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quarantined -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>🚫 Quarantined Batches</span>
                <span class="badge bg-danger"><?= count($quarantined ?? []) ?></span>
            </div>
            <div class="card-body">
                <?php if (!empty($quarantined)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Batch #</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Reason</th>
                                <th>Quarantine Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($quarantined as $batch): ?>
                            <tr>
                                <td><strong><?= esc($batch['batch_number'] ?? 'N/A') ?></strong></td>
                                <td><?= esc($batch['product_name'] ?? $batch['item_name'] ?? 'N/A') ?></td>
                                <td><?= number_format($batch['quantity'] ?? 0) ?></td>
                                <td><?= esc($batch['quarantine_reason'] ?? 'N/A') ?></td>
                                <td><?= isset($batch['quarantine_date']) ? date('M d, Y', strtotime($batch['quarantine_date'])) : 'N/A' ?></td>
                                <td>
                                    <button class="btn btn-sm btn-success">Release</button>
                                    <button class="btn btn-sm btn-danger">Dispose</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <div>✅</div>
                    <p>No quarantined batches</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>