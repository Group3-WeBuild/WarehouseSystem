<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batch Tracking | WeBuild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        .sidebar .nav-link i { margin-right: 10px; }
        .main-content { background: #f8f9fa; min-height: 100vh; }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 15px 25px;
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
        .stat-card { text-align: center; padding: 20px; border-radius: 10px; color: #fff; margin-bottom: 20px; }
        .table th { background-color: #f8f9fa; font-weight: 600; }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
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
                <small class="text-white-50">Warehouse Manager</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= base_url('warehouse-manager/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-manager/inventory') ?>">
                    <i class="bi bi-box-seam"></i> Inventory
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-manager/barcode-scanner') ?>">
                    <i class="bi bi-qr-code-scan"></i> Barcode Scanner
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-manager/stock-movements') ?>">
                    <i class="bi bi-arrow-left-right"></i> Stock Movements
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-manager/orders') ?>">
                    <i class="bi bi-cart"></i> Orders
                </a>
                <a class="nav-link active" href="<?= base_url('warehouse-manager/batch-tracking') ?>">
                    <i class="bi bi-upc-scan"></i> Batch Tracking
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-manager/reports') ?>">
                    <i class="bi bi-file-earmark-bar-graph"></i> Reports
                </a>
                <hr class="mx-3 my-2" style="border-color: rgba(255,255,255,0.2);">
                <a class="nav-link text-danger" href="<?= base_url('logout') ?>">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 px-0 main-content">
            <div class="topbar d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0"><i class="bi bi-upc-scan text-primary"></i> Batch Tracking</h5>
                </div>
                <div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBatchModal">
                        <i class="bi bi-plus-lg"></i> Add Batch
                    </button>
                    <a href="<?= base_url('print/batch-tracking') ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-printer"></i> Print
                    </a>
                </div>
            </div>

            <div class="p-4">
                <!-- Stats Row -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card bg-success">
                            <h3><?= count($activeBatches ?? []) ?></h3>
                            <small>Active Batches</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card bg-warning">
                            <h3><?= count($expiringBatches ?? []) ?></h3>
                            <small>Expiring Soon</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card bg-danger">
                            <h3><?= count($quarantined ?? []) ?></h3>
                            <small>Quarantined</small>
                        </div>
                    </div>
                </div>

                <!-- Active Batches -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-check-circle text-success"></i> Active Batches</span>
                        <span class="badge bg-success"><?= count($activeBatches ?? []) ?></span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($activeBatches)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Batch #</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Manufacturing Date</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($activeBatches as $batch): ?>
                                    <tr>
                                        <td><code><?= esc($batch['batch_number'] ?? 'N/A') ?></code></td>
                                        <td><strong><?= esc($batch['product_name'] ?? $batch['item_name'] ?? 'N/A') ?></strong></td>
                                        <td><?= number_format($batch['quantity'] ?? 0) ?></td>
                                        <td><?= isset($batch['manufacturing_date']) ? date('M d, Y', strtotime($batch['manufacturing_date'])) : 'N/A' ?></td>
                                        <td><?= isset($batch['expiry_date']) ? date('M d, Y', strtotime($batch['expiry_date'])) : 'N/A' ?></td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-info" title="View"><i class="bi bi-eye"></i></button>
                                                <button class="btn btn-outline-warning" title="Move to Quarantine"><i class="bi bi-exclamation-triangle"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="empty-state">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                            <p class="mt-2">No active batches found</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Expiring Soon -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-exclamation-triangle text-warning"></i> Expiring Soon (Next 30 Days)</span>
                        <span class="badge bg-warning text-dark"><?= count($expiringBatches ?? []) ?></span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($expiringBatches)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
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
                                        <td><code><?= esc($batch['batch_number'] ?? 'N/A') ?></code></td>
                                        <td><strong><?= esc($batch['product_name'] ?? $batch['item_name'] ?? 'N/A') ?></strong></td>
                                        <td><?= number_format($batch['quantity'] ?? 0) ?></td>
                                        <td><?= isset($batch['expiry_date']) ? date('M d, Y', strtotime($batch['expiry_date'])) : 'N/A' ?></td>
                                        <td><span class="badge bg-<?= $daysLeft <= 7 ? 'danger' : 'warning' ?>"><?= $daysLeft ?> days</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning"><i class="bi bi-exclamation-triangle"></i> Quarantine</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="empty-state">
                            <i class="bi bi-check-circle display-4 text-success"></i>
                            <p class="mt-2">No batches expiring soon</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quarantined -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-x-circle text-danger"></i> Quarantined Batches</span>
                        <span class="badge bg-danger"><?= count($quarantined ?? []) ?></span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($quarantined)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Batch #</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Reason</th>
                                        <th>Quarantine Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($quarantined as $batch): ?>
                                    <tr>
                                        <td><code><?= esc($batch['batch_number'] ?? 'N/A') ?></code></td>
                                        <td><strong><?= esc($batch['product_name'] ?? $batch['item_name'] ?? 'N/A') ?></strong></td>
                                        <td><?= number_format($batch['quantity'] ?? 0) ?></td>
                                        <td><?= esc($batch['quarantine_reason'] ?? 'N/A') ?></td>
                                        <td><?= isset($batch['quarantine_date']) ? date('M d, Y', strtotime($batch['quarantine_date'])) : 'N/A' ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-success"><i class="bi bi-arrow-return-left"></i> Release</button>
                                                <button class="btn btn-danger"><i class="bi bi-trash"></i> Dispose</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="empty-state">
                            <i class="bi bi-check-circle display-4 text-success"></i>
                            <p class="mt-2">No quarantined batches</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Batch Modal -->
<div class="modal fade" id="addBatchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Add New Batch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('warehouse-manager/batch/add') ?>" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Batch Number</label>
                            <input type="text" name="batch_number" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Product</label>
                            <select name="product_id" class="form-select" required>
                                <option value="">Select Product</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Manufacturing Date</label>
                            <input type="date" name="manufacturing_date" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Add Batch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>