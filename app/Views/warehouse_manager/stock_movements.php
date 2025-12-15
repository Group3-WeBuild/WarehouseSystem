<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Movements | WeBuild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
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
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border-radius: 10px; }
        .card-header { background-color: #f8f9fa; font-weight: 600; }
        .stat-card { text-align: center; padding: 20px; border-radius: 10px; color: #fff; }
        .movement-in { color: #28a745; }
        .movement-out { color: #dc3545; }
        .movement-transfer { color: #17a2b8; }
        .movement-adjust { color: #ffc107; }
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
                <a class="nav-link" href="<?= base_url('warehouse-manager/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-manager/inventory') ?>">
                    <i class="bi bi-box-seam"></i> Inventory
                </a>
                <a class="nav-link active" href="<?= base_url('warehouse-manager/stock-movements') ?>">
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
        <div class="col-md-10 main-content p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="<?= base_url('warehouse-manager/dashboard') ?>" class="btn btn-outline-secondary btn-sm me-2">← Back</a>
                    <span class="fw-bold fs-5"><i class="bi bi-arrow-left-right"></i> Stock Movements</span>
                </div>
                <div>
                    <button class="btn btn-success btn-sm me-1" data-bs-toggle="modal" data-bs-target="#stockInModal"><i class="bi bi-arrow-down-circle"></i> Stock In</button>
                    <button class="btn btn-danger btn-sm me-1" data-bs-toggle="modal" data-bs-target="#stockOutModal"><i class="bi bi-arrow-up-circle"></i> Stock Out</button>
                    <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#transferModal"><i class="bi bi-arrow-repeat"></i> Transfer</button>
                    <a href="<?= base_url('print/stock-movements') ?>" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer"></i> Print</a>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card bg-success">
                        <h3><i class="bi bi-arrow-down-circle"></i> <?= $stats['stock_in'] ?? 0 ?></h3>
                        <small>Stock In (Today)</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card bg-danger">
                        <h3><i class="bi bi-arrow-up-circle"></i> <?= $stats['stock_out'] ?? 0 ?></h3>
                        <small>Stock Out (Today)</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card bg-info">
                        <h3><i class="bi bi-arrow-repeat"></i> <?= $stats['transfers'] ?? 0 ?></h3>
                        <small>Transfers (Today)</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card bg-warning text-dark">
                        <h3><i class="bi bi-pencil-square"></i> <?= $stats['adjustments'] ?? 0 ?></h3>
                        <small>Adjustments (Today)</small>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form class="row g-3" method="get">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="search" placeholder="Search item, reference..." value="<?= esc($_GET['search'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="type">
                                <option value="">All Types</option>
                                <option value="in" <?= ($_GET['type'] ?? '') == 'in' ? 'selected' : '' ?>>Stock In</option>
                                <option value="out" <?= ($_GET['type'] ?? '') == 'out' ? 'selected' : '' ?>>Stock Out</option>
                                <option value="transfer" <?= ($_GET['type'] ?? '') == 'transfer' ? 'selected' : '' ?>>Transfer</option>
                                <option value="adjustment" <?= ($_GET['type'] ?? '') == 'adjustment' ? 'selected' : '' ?>>Adjustment</option>
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
                            <a href="<?= base_url('warehouse-manager/stock-movements') ?>" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Movements Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    Stock Movement History
                    <span class="badge bg-primary"><?= count($movements ?? []) ?> records</span>
                </div>
                <div class="card-body">
                    <?php if (!empty($movements)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date/Time</th>
                                    <th>Type</th>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Reference</th>
                                    <th>From/To</th>
                                    <th>User</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($movements as $m): ?>
                                <tr>
                                    <td><?= date('M d, Y h:i A', strtotime($m['created_at'] ?? 'now')) ?></td>
                                    <td>
                                        <?php 
                                        $type = $m['movement_type'] ?? $m['type'] ?? 'in';
                                        $icons = ['in' => '↓', 'out' => '↑', 'transfer' => '↔', 'adjustment' => '✎', 'Stock In' => '↓', 'Stock Out' => '↑', 'Transfer' => '↔', 'Adjustment' => '✎', 'Return' => '↩'];
                                        $classes = ['in' => 'movement-in', 'out' => 'movement-out', 'transfer' => 'movement-transfer', 'adjustment' => 'movement-adjust', 'Stock In' => 'movement-in', 'Stock Out' => 'movement-out', 'Transfer' => 'movement-transfer', 'Adjustment' => 'movement-adjust', 'Return' => 'movement-out'];
                                        $badgeColors = ['in' => 'success', 'out' => 'danger', 'transfer' => 'info', 'adjustment' => 'warning', 'Stock In' => 'success', 'Stock Out' => 'danger', 'Transfer' => 'info', 'Adjustment' => 'warning', 'Return' => 'danger'];
                                        ?>
                                        <span class="badge bg-<?= $badgeColors[$type] ?? 'secondary' ?>"><?= ucfirst($type) ?></span>
                                    </td>
                                    <td><?= esc($m['item_name'] ?? $m['product_name'] ?? 'Unknown') ?></td>
                                    <td>
                                        <span class="<?= $classes[$type] ?? '' ?> fw-bold">
                                            <?= ($m['quantity'] ?? 0) < 0 ? '' : (in_array($type, ['in', 'Stock In']) ? '+' : '-') ?><?= abs($m['quantity'] ?? 0) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($m['reference_number'] ?? 'N/A') ?></td>
                                    <td>
                                        <?php if ($type == 'transfer' || $type == 'Transfer'): ?>
                                        <?= esc($m['from_location'] ?? 'N/A') ?> → <?= esc($m['to_location'] ?? 'N/A') ?>
                                        <?php else: ?>
                                        <?= esc($m['location_name'] ?? $m['warehouse_name'] ?? 'Main') ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($m['user_name'] ?? 'System') ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewDetails(<?= $m['id'] ?? 0 ?>)"><i class="bi bi-eye"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-arrow-left-right display-1 text-muted"></i>
                        <h5 class="text-muted mt-3">No stock movements found</h5>
                        <p class="text-muted">Record stock movements using the buttons above</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock In Modal -->
<div class="modal fade" id="stockInModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-arrow-down-circle"></i> Record Stock In</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('warehouse-manager/stock-in') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Item</label>
                        <select class="form-select" name="item_id" required>
                            <option value="">Select Item</option>
                            <?php foreach ($items ?? [] as $item): ?>
                            <option value="<?= $item['id'] ?>"><?= esc($item['name'] ?? $item['product_name'] ?? '') ?> (<?= $item['sku'] ?? '' ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference (PO#, Invoice#)</label>
                        <input type="text" class="form-control" name="reference">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Record Stock In</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Stock Out Modal -->
<div class="modal fade" id="stockOutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-arrow-up-circle"></i> Record Stock Out</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('warehouse-manager/stock-out') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Item</label>
                        <select class="form-select" name="item_id" required>
                            <option value="">Select Item</option>
                            <?php foreach ($items ?? [] as $item): ?>
                            <option value="<?= $item['id'] ?>"><?= esc($item['name'] ?? $item['product_name'] ?? '') ?> (Stock: <?= $item['quantity'] ?? 0 ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <select class="form-select" name="reason">
                            <option value="sale">Sale</option>
                            <option value="damage">Damage</option>
                            <option value="expired">Expired</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Record Stock Out</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Transfer Modal -->
<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-arrow-repeat"></i> Transfer Stock</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('warehouse-manager/transfer-stock') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Item</label>
                        <select class="form-select" name="item_id" required>
                            <option value="">Select Item</option>
                            <?php foreach ($items ?? [] as $item): ?>
                            <option value="<?= $item['id'] ?>"><?= esc($item['name'] ?? $item['product_name'] ?? '') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">From Location</label>
                            <select class="form-select" name="from_location" required>
                                <option value="">Select</option>
                                <?php foreach ($locations ?? [] as $loc): ?>
                                <option value="<?= $loc['id'] ?>"><?= esc($loc['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">To Location</label>
                            <select class="form-select" name="to_location" required>
                                <option value="">Select</option>
                                <?php foreach ($locations ?? [] as $loc): ?>
                                <option value="<?= $loc['id'] ?>"><?= esc($loc['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function viewDetails(id) {
    alert('View movement details for ID: ' + id);
}
</script>
</body>
</html>
