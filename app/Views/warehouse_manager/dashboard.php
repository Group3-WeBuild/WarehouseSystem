<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Warehouse Manager Dashboard') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .stat-card.primary { border-color: #0d6efd; }
        .stat-card.warning { border-color: #ffc107; }
        .stat-card.danger { border-color: #dc3545; }
        .stat-card.success { border-color: #198754; }
        .stat-card.info { border-color: #0dcaf0; }
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
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 px-0 sidebar">
                <div class="text-center py-4">
                    <h5 class="text-white">WITMS</h5>
                    <p class="text-white-50 small">Warehouse Manager</p>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="<?= base_url('warehouse-manager/dashboard') ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="<?= base_url('warehouse-manager/inventory') ?>">
                        <i class="bi bi-box-seam"></i> Inventory
                    </a>
                    <a class="nav-link" href="<?= base_url('warehouse-manager/stock-movements') ?>">
                        <i class="bi bi-arrow-left-right"></i> Stock Movements
                    </a>
                    <a class="nav-link" href="<?= base_url('warehouse-manager/orders') ?>">
                        <i class="bi bi-cart"></i> Orders
                    </a>
                    <a class="nav-link" href="<?= base_url('warehouse-manager/batch-tracking') ?>">
                        <i class="bi bi-upc-scan"></i> Batch Tracking
                    </a>
                    <a class="nav-link" href="<?= base_url('warehouse-manager/reports') ?>">
                        <i class="bi bi-file-earmark-text"></i> Reports
                    </a>
                    <hr class="bg-light">
                    <a class="nav-link text-danger" href="<?= base_url('logout') ?>">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <!-- Top Bar -->
                <div class="bg-white border-bottom p-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Warehouse Manager Dashboard</h4>
                        <div class="d-flex align-items-center">
                            <span class="me-3">Welcome, <strong><?= esc($user['name'] ?? 'User') ?></strong></span>
                            <span class="badge bg-primary"><?= esc($user['role'] ?? '') ?></span>
                        </div>
                    </div>
                </div>

                <div class="px-4">
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

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-1">Total Items</h6>
                                            <h2 class="mb-0"><?= number_format($stats['totalItems'] ?? 0) ?></h2>
                                        </div>
                                        <i class="bi bi-box-seam fs-1 text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card warning">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-1">Low Stock</h6>
                                            <h2 class="mb-0"><?= number_format($stats['lowStockItems'] ?? 0) ?></h2>
                                        </div>
                                        <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card danger">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-1">Out of Stock</h6>
                                            <h2 class="mb-0"><?= number_format($stats['outOfStockItems'] ?? 0) ?></h2>
                                        </div>
                                        <i class="bi bi-x-circle fs-1 text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card success">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-1">Pending Orders</h6>
                                            <h2 class="mb-0"><?= number_format($stats['pendingOrders'] ?? 0) ?></h2>
                                        </div>
                                        <i class="bi bi-cart fs-1 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Stock Movements</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($recentMovements)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Type</th>
                                                        <th>Quantity</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach (array_slice($recentMovements, 0, 5) as $movement): ?>
                                                        <tr>
                                                            <td>
                                                                <span class="badge bg-<?= $movement['movement_type'] === 'IN' ? 'success' : 'danger' ?>">
                                                                    <?= esc($movement['movement_type'] ?? '') ?>
                                                                </span>
                                                            </td>
                                                            <td><?= number_format($movement['quantity'] ?? 0) ?></td>
                                                            <td><?= date('M d, Y', strtotime($movement['created_at'] ?? 'now')) ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted text-center py-4">No recent movements</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0"><i class="bi bi-cart-check"></i> Pending Orders</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($pendingOrders)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Order #</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach (array_slice($pendingOrders, 0, 5) as $order): ?>
                                                        <tr>
                                                            <td>#<?= esc($order['id'] ?? '') ?></td>
                                                            <td>
                                                                <span class="badge bg-warning">
                                                                    <?= esc($order['status'] ?? 'Pending') ?>
                                                                </span>
                                                            </td>
                                                            <td><?= date('M d, Y', strtotime($order['created_at'] ?? 'now')) ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted text-center py-4">No pending orders</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <a href="<?= base_url('warehouse-manager/inventory') ?>" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-plus-circle"></i> Add Inventory
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="<?= base_url('warehouse-manager/stock-movements') ?>" class="btn btn-outline-success w-100">
                                        <i class="bi bi-arrow-down-circle"></i> Stock In
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="<?= base_url('warehouse-manager/stock-movements') ?>" class="btn btn-outline-danger w-100">
                                        <i class="bi bi-arrow-up-circle"></i> Stock Out
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="<?= base_url('warehouse-manager/reports') ?>" class="btn btn-outline-info w-100">
                                        <i class="bi bi-file-earmark-bar-graph"></i> Generate Report
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
