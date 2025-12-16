<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Inventory | WITMS</title>
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
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border-radius: 10px; margin-bottom: 20px; }
        .card-header { background-color: #f8f9fa; font-weight: 600; }
        .low-stock { background-color: #fff3cd !important; }
        .out-of-stock { background-color: #f8d7da !important; }
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
                <h5 class="text-white mb-1">WITMS</h5>
                <small class="text-white-50">Warehouse Manager</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= base_url('warehouse-manager/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link active" href="<?= base_url('warehouse-manager/inventory') ?>">
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
                    <h5 class="mb-0"><i class="bi bi-building text-primary"></i> <?= esc($warehouse['warehouse_name'] ?? $warehouse['name'] ?? 'Warehouse') ?> Inventory</h5>
                </div>
                <div>
                    <a href="<?= base_url('warehouse-manager/transfer-inventory/' . ($warehouse['id'] ?? 0)) ?>" class="btn btn-info btn-sm"><i class="bi bi-arrow-repeat"></i> Transfer Stock</a>
                    <a href="<?= base_url('print/warehouse-inventory/' . ($warehouse['id'] ?? 0)) ?>" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer"></i> Print</a>
                </div>
            </div>

            <div class="p-4">
                <!-- Warehouse Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Warehouse:</strong><br>
                                <?= esc($warehouse['warehouse_name'] ?? $warehouse['name'] ?? 'Main Warehouse') ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Location:</strong><br>
                                <?= esc($warehouse['location'] ?? 'N/A') ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Total Items:</strong><br>
                                <?= number_format($stats['total_items'] ?? count($inventory ?? [])) ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Total Value:</strong><br>
                                ₱<?= number_format($stats['total_value'] ?? 0, 2) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Row -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white text-center py-3">
                            <h3 class="mb-0"><?= $stats['total_items'] ?? count($inventory ?? []) ?></h3>
                            <small>Total SKUs</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white text-center py-3">
                            <h3 class="mb-0"><?= $stats['in_stock'] ?? 0 ?></h3>
                            <small>In Stock</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark text-center py-3">
                            <h3 class="mb-0"><?= $stats['low_stock'] ?? 0 ?></h3>
                            <small>Low Stock</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white text-center py-3">
                            <h3 class="mb-0"><?= $stats['out_of_stock'] ?? 0 ?></h3>
                            <small>Out of Stock</small>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form class="row g-3" method="get">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="search" placeholder="Search item or SKU..." value="<?= esc($_GET['search'] ?? '') ?>">
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="category">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories ?? [] as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ($_GET['category'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['category_name'] ?? $cat['name'] ?? 'Unknown') ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="stock_status">
                                    <option value="">All Status</option>
                                    <option value="in_stock" <?= ($_GET['stock_status'] ?? '') == 'in_stock' ? 'selected' : '' ?>>In Stock</option>
                                    <option value="low_stock" <?= ($_GET['stock_status'] ?? '') == 'low_stock' ? 'selected' : '' ?>>Low Stock</option>
                                    <option value="out_of_stock" <?= ($_GET['stock_status'] ?? '') == 'out_of_stock' ? 'selected' : '' ?>>Out of Stock</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
                                <a href="<?= current_url() ?>" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Inventory Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Inventory Items
                        <span class="badge bg-primary"><?= count($inventory ?? []) ?> items</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($inventory)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>SKU</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Location</th>
                                        <th>Quantity</th>
                                        <th>Reorder Point</th>
                                        <th>Unit Cost</th>
                                        <th>Total Value</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($inventory as $item): ?>
                                    <?php 
                                    $qty = $item['quantity'] ?? 0;
                                    $reorderPoint = $item['reorder_point'] ?? 10;
                                    $rowClass = $qty <= 0 ? 'out-of-stock' : ($qty <= $reorderPoint ? 'low-stock' : '');
                                    $unitCost = $item['unit_cost'] ?? 0;
                                    ?>
                                    <tr class="<?= $rowClass ?>">
                                        <td><code><?= esc($item['sku'] ?? 'N/A') ?></code></td>
                                        <td><strong><?= esc($item['product_name'] ?? $item['name'] ?? 'Unknown') ?></strong></td>
                                        <td><?= esc($item['category_name'] ?? $item['category'] ?? 'N/A') ?></td>
                                        <td><?= esc($item['bin_location'] ?? 'N/A') ?></td>
                                        <td>
                                            <span class="badge bg-<?= $qty <= 0 ? 'danger' : ($qty <= $reorderPoint ? 'warning' : 'success') ?>">
                                                <?= $qty ?>
                                            </span>
                                        </td>
                                        <td><?= $reorderPoint ?></td>
                                        <td class="text-end">₱<?= number_format($unitCost, 2) ?></td>
                                        <td class="text-end">₱<?= number_format($qty * $unitCost, 2) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewItem(<?= $item['id'] ?? 0 ?>)"><i class="bi bi-eye"></i></button>
                                            <a href="<?= base_url('warehouse-manager/transfer-item/' . ($item['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-info"><i class="bi bi-arrow-repeat"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-box-seam display-1 text-muted"></i>
                            <h5 class="text-muted mt-3">No inventory items found</h5>
                            <p class="text-muted">This warehouse has no inventory records</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function viewItem(id) {
    window.location.href = '<?= base_url('warehouse-manager/item/') ?>' + id;
}
</script>
</body>
</html>
