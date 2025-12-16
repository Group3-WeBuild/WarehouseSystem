<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management | WeBuild</title>
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
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border-radius: 10px; }
        .card-header { background-color: #f8f9fa; font-weight: 600; }
        .stat-card { text-align: center; padding: 20px; border-radius: 10px; color: #fff; margin-bottom: 20px; }
        .table th { background-color: #f8f9fa; font-weight: 600; }
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
                <a class="nav-link active" href="<?= base_url('warehouse-manager/orders') ?>">
                    <i class="bi bi-cart"></i> Orders
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-manager/batch-tracking') ?>">
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
            <div class="topbar">
                <div>
                    <a href="<?= base_url('warehouse-manager/dashboard') ?>" class="btn btn-outline-secondary btn-sm me-2">← Back</a>
                    <span class="fw-bold"><i class="bi bi-cart"></i> Orders Management</span>
                </div>
                <div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newOrderModal"><i class="bi bi-plus-lg"></i> New Order</button>
                    <a href="<?= base_url('print/orders') ?>" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer"></i> Print</a>
                </div>
            </div>

            <div class="p-4">
                <!-- Stats Row -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card bg-primary">
                            <h3><?= $stats['total'] ?? count($orders ?? []) ?></h3>
                            <small>Total Orders</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-warning">
                            <h3><?= $stats['pending'] ?? count(array_filter($orders ?? [], fn($o) => ($o['status'] ?? '') == 'Pending')) ?></h3>
                            <small>Pending</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-info">
                            <h3><?= $stats['processing'] ?? count(array_filter($orders ?? [], fn($o) => ($o['status'] ?? '') == 'Processing')) ?></h3>
                            <small>Processing</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-success">
                            <h3><?= $stats['completed'] ?? count(array_filter($orders ?? [], fn($o) => ($o['status'] ?? '') == 'Completed')) ?></h3>
                            <small>Completed</small>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form class="row g-3" method="get">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="search" placeholder="Search order #, customer..." value="<?= esc($_GET['search'] ?? '') ?>">
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="Pending" <?= ($_GET['status'] ?? '') == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="Processing" <?= ($_GET['status'] ?? '') == 'Processing' ? 'selected' : '' ?>>Processing</option>
                                    <option value="Completed" <?= ($_GET['status'] ?? '') == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="Cancelled" <?= ($_GET['status'] ?? '') == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="from_date" value="<?= esc($_GET['from_date'] ?? '') ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="to_date" value="<?= esc($_GET['to_date'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
                                <a href="<?= base_url('warehouse-manager/orders') ?>" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        All Orders
                        <span class="badge bg-primary"><?= count($orders ?? []) ?> records</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($orders)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><strong><?= esc($order['order_number'] ?? 'N/A') ?></strong></td>
                                        <td><?= esc($order['customer_name'] ?? 'Unknown') ?></td>
                                        <td><?= date('M d, Y', strtotime($order['order_date'] ?? $order['created_at'] ?? 'now')) ?></td>
                                        <td><?= esc($order['item_count'] ?? 0) ?> items</td>
                                        <td>₱<?= number_format($order['total_amount'] ?? 0, 2) ?></td>
                                        <td>
                                            <?php 
                                            $status = $order['status'] ?? 'Pending';
                                            $sClass = ['Completed' => 'success', 'Processing' => 'primary', 'Pending' => 'warning', 'Cancelled' => 'danger'][$status] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $sClass ?>"><?= $status ?></span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('warehouse-manager/orders/' . ($order['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                            <?php if (($order['status'] ?? '') == 'Pending'): ?>
                                            <button class="btn btn-sm btn-success" onclick="processOrder(<?= $order['id'] ?? 0 ?>)"><i class="bi bi-check-lg"></i> Process</button>
                                            <?php endif; ?>
                                            <?php if (($order['status'] ?? '') == 'Processing'): ?>
                                            <button class="btn btn-sm btn-info" onclick="completeOrder(<?= $order['id'] ?? 0 ?>)"><i class="bi bi-check-all"></i> Complete</button>
                                            <?php endif; ?>
                                            <a href="<?= base_url('print/order/' . ($order['id'] ?? 0)) ?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-cart display-1 text-muted"></i>
                            <h5 class="text-muted mt-3">No orders found</h5>
                            <p class="text-muted">Orders will appear here when customers place them</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Order Modal -->
<div class="modal fade" id="newOrderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('warehouse-manager/orders/create') ?>" method="post">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer</label>
                            <select class="form-select" name="customer_id" required>
                                <option value="">Select Customer</option>
                                <?php foreach ($customers ?? [] as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= esc($c['client_name'] ?? $c['name'] ?? 'Unknown') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Required Date</label>
                            <input type="date" class="form-control" name="required_date">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Shipping Address</label>
                            <textarea class="form-control" name="shipping_address" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function processOrder(id) {
    if(confirm('Start processing this order?')) {
        window.location.href = '<?= base_url('warehouse-manager/process-order/') ?>' + id;
    }
}
function shipOrder(id) {
    if(confirm('Mark this order as shipped?')) {
        window.location.href = '<?= base_url('warehouse-manager/ship-order/') ?>' + id;
    }
}
</script>
</body>
</html>
