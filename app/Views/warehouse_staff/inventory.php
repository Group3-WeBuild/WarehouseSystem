<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inventory | WeBuild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
        }
        .stat-card {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            color: #fff;
            margin-bottom: 20px;
        }
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
                <small class="text-white-50">Warehouse Staff</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= base_url('warehouse-staff/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link active" href="<?= base_url('warehouse-staff/inventory') ?>">
                    <i class="bi bi-box-seam"></i> View Inventory
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-staff/barcode-scanner') ?>">
                    <i class="bi bi-qr-code-scan"></i> Barcode Scanner
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
        </div>

        <!-- Main Content -->
        <div class="col-md-10 px-0 main-content">
            <div class="topbar d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0"><i class="bi bi-box-seam text-primary"></i> View Inventory</h5>
                </div>
                <div class="d-flex align-items-center">
                    <span class="me-3">Welcome, <strong><?= esc(session()->get('name') ?? 'User') ?></strong></span>
                    <span class="badge bg-info"><?= esc(session()->get('role') ?? '') ?></span>
                </div>
            </div>

            <div class="p-4">
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

                <!-- Stats Row -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card bg-primary">
                            <h3><?= number_format(count($inventory ?? [])) ?></h3>
                            <small>Total Items</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-success">
                            <h3><?= number_format(count(array_filter($inventory ?? [], fn($i) => ($i['quantity'] ?? 0) > ($i['reorder_level'] ?? 0)))) ?></h3>
                            <small>In Stock</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-warning">
                            <h3><?= number_format(count(array_filter($inventory ?? [], fn($i) => ($i['quantity'] ?? 0) > 0 && ($i['quantity'] ?? 0) <= ($i['reorder_level'] ?? 0)))) ?></h3>
                            <small>Low Stock</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-danger">
                            <h3><?= number_format(count(array_filter($inventory ?? [], fn($i) => ($i['quantity'] ?? 0) == 0))) ?></h3>
                            <small>Out of Stock</small>
                        </div>
                    </div>
                </div>

                <!-- Filters Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search inventory...">
                            </div>
                            <div class="col-md-3">
                                <select id="categoryFilter" class="form-select">
                                    <option value="">All Categories</option>
                                    <option value="Cement & Concrete">Cement & Concrete</option>
                                    <option value="Steel & Metal">Steel & Metal</option>
                                    <option value="Wood & Plywood">Wood & Plywood</option>
                                    <option value="Paint & Coatings">Paint & Coatings</option>
                                    <option value="Hardware & Fasteners">Hardware & Fasteners</option>
                                    <option value="Plumbing Supplies">Plumbing Supplies</option>
                                    <option value="Electrical Supplies">Electrical Supplies</option>
                                    <option value="Tools & Equipment">Tools & Equipment</option>
                                    <option value="Safety Equipment">Safety Equipment</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="statusFilter" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="in-stock">In Stock</option>
                                    <option value="low-stock">Low Stock</option>
                                    <option value="out-of-stock">Out of Stock</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Table (Read-Only) -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="bi bi-table"></i> Inventory Items (Read-Only View)</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="inventoryTable" class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>SKU</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Reorder Level</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($inventory)): ?>
                                        <?php foreach ($inventory as $item): ?>
                                            <tr>
                                                <td><code><?= esc($item['sku'] ?? '') ?></code></td>
                                                <td><strong><?= esc($item['product_name'] ?? '') ?></strong></td>
                                                <td><?= esc($item['category'] ?? '') ?></td>
                                                <td>
                                                    <span class="badge bg-<?= ($item['quantity'] ?? 0) <= ($item['reorder_level'] ?? 0) ? 'danger' : 'success' ?>">
                                                        <?= number_format($item['quantity'] ?? 0) ?>
                                                    </span>
                                                </td>
                                                <td>₱<?= number_format($item['unit_price'] ?? 0, 2) ?></td>
                                                <td><?= number_format($item['reorder_level'] ?? 0) ?></td>
                                                <td>
                                                    <?php if (($item['quantity'] ?? 0) == 0): ?>
                                                        <span class="badge bg-danger">Out of Stock</span>
                                                    <?php elseif (($item['quantity'] ?? 0) <= ($item['reorder_level'] ?? 0)): ?>
                                                        <span class="badge bg-warning text-dark">Low Stock</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">In Stock</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <i class="bi bi-inbox display-4 text-muted"></i>
                                                <p class="text-muted mt-2">No inventory items found</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#inventoryTable').DataTable({
        pageLength: 25,
        order: [[1, 'asc']]
    });

    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('#categoryFilter').on('change', function() {
        table.column(2).search(this.value).draw();
    });

    $('#statusFilter').on('change', function() {
        var value = this.value;
        if (value === 'in-stock') {
            table.column(6).search('In Stock').draw();
        } else if (value === 'low-stock') {
            table.column(6).search('Low Stock').draw();
        } else if (value === 'out-of-stock') {
            table.column(6).search('Out of Stock').draw();
        } else {
            table.column(6).search('').draw();
        }
    });
});
</script>
</body>
</html>
