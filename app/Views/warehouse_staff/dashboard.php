<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title><?= esc($title ?? 'Warehouse Staff Dashboard') ?> | WITMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background-color: #f8f9fa; 
            margin: 0;
            padding: 0;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            position: fixed;
            left: 0;
            top: 0;
            width: 220px;
            z-index: 1000;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        .sidebar .nav-link i { margin-right: 10px; }
        .main-wrapper {
            margin-left: 220px;
            min-height: 100vh;
            background: #f8f9fa;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 15px 25px;
        }
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-radius: 10px;
        }
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .stat-card.primary { border-color: #0d6efd; }
        .stat-card.warning { border-color: #ffc107; }
        .stat-card.success { border-color: #198754; }
    </style>
</head>
<body>
    <!-- Fixed Sidebar -->
    <aside class="sidebar">
        <div class="text-center py-4">
            <h5 class="text-white mb-1">WITMS</h5>
            <small class="text-white-50">Warehouse Staff</small>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link active" href="<?= base_url('warehouse-staff/dashboard') ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a class="nav-link" href="<?= base_url('warehouse-staff/inventory') ?>">
                <i class="bi bi-box-seam"></i> View Inventory
            </a>
            <a class="nav-link" href="<?= base_url('warehouse-staff/stock-movements') ?>">
                <i class="bi bi-arrow-left-right"></i> Stock Movements
            </a>
            <a class="nav-link" href="<?= base_url('warehouse-staff/orders') ?>">
                <i class="bi bi-cart"></i> Orders
            </a>
            <hr class="mx-3 my-2" style="border-color: rgba(255,255,255,0.2);">
            <a class="nav-link text-danger" href="<?= base_url('logout') ?>">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- Main Content Wrapper -->
    <main class="main-wrapper">
        <!-- Top Bar -->
        <header class="topbar d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Warehouse Staff Dashboard</h4>
            <div class="d-flex align-items-center">
                <span class="me-3">Welcome, <strong><?= esc($user['name'] ?? 'User') ?></strong></span>
                <span class="badge bg-info"><?= esc($user['role'] ?? '') ?></span>
            </div>
        </header>

        <!-- Page Content -->
        <section class="p-4">
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
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card stat-card primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Items</h6>
                                    <h2 class="mb-0"><?= $stats['totalItems'] ?? 0 ?></h2>
                                </div>
                                <i class="bi bi-box-seam fs-1 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Low Stock Items</h6>
                                    <h2 class="mb-0"><?= $stats['lowStockItems'] ?? 0 ?></h2>
                                </div>
                                <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Pending Orders</h6>
                                    <h2 class="mb-0"><?= $stats['pendingOrders'] ?? 0 ?></h2>
                                </div>
                                <i class="bi bi-cart fs-1 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0"><i class="bi bi-clock-history text-primary me-2"></i>Recent Stock Movements</h5>
                        </div>
                        <div class="card-body pt-0">
                            <?php if (!empty($recentMovements)): ?>
                                <?php foreach (array_slice($recentMovements, 0, 5) as $movement): ?>
                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                        <div>
                                            <strong><?= esc($movement['product_name'] ?? $movement['item_name'] ?? 'Unknown') ?></strong>
                                            <div class="small text-muted">
                                                Qty: <?= number_format($movement['quantity'] ?? 0) ?> | 
                                                <?= isset($movement['created_at']) ? date('M d, Y', strtotime($movement['created_at'])) : '-' ?>
                                            </div>
                                        </div>
                                        <span class="badge bg-<?= ($movement['type'] ?? '') === 'IN' ? 'success' : 'danger' ?>">
                                            <?= esc($movement['type'] ?? '-') ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-1"></i>
                                    <p class="mb-0 mt-2">No recent stock movements</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0"><i class="bi bi-list-check text-success me-2"></i>Pending Orders</h5>
                        </div>
                        <div class="card-body pt-0">
                            <?php if (!empty($pendingOrders)): ?>
                                <?php foreach (array_slice($pendingOrders, 0, 5) as $order): ?>
                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                        <div>
                                            <strong>Order #<?= esc($order['id'] ?? '-') ?></strong>
                                            <div class="small text-muted">
                                                <?= isset($order['created_at']) ? date('M d, Y H:i', strtotime($order['created_at'])) : '-' ?>
                                            </div>
                                        </div>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-1"></i>
                                    <p class="mb-0 mt-2">No pending orders</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
