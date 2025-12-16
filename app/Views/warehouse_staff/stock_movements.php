<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Movements | WeBuild</title>
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
                <a class="nav-link" href="<?= base_url('warehouse-staff/inventory') ?>">
                    <i class="bi bi-box-seam"></i> View Inventory
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-staff/barcode-scanner') ?>">
                    <i class="bi bi-qr-code-scan"></i> Barcode Scanner
                </a>
                <a class="nav-link active" href="<?= base_url('warehouse-staff/stock-movements') ?>">
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
                    <h5 class="mb-0"><i class="bi bi-arrow-left-right text-primary"></i> Stock Movements</h5>
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

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-center p-3 bg-success text-white">
                            <h3><?= number_format(count(array_filter($movements ?? [], fn($m) => ($m['type'] ?? '') === 'IN'))) ?></h3>
                            <small>Stock In</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3 bg-danger text-white">
                            <h3><?= number_format(count(array_filter($movements ?? [], fn($m) => ($m['type'] ?? '') === 'OUT'))) ?></h3>
                            <small>Stock Out</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3 bg-primary text-white">
                            <h3><?= number_format(count($movements ?? [])) ?></h3>
                            <small>Total Movements</small>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search movements...">
                            </div>
                            <div class="col-md-3">
                                <select id="typeFilter" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="Stock In">Stock In</option>
                                    <option value="Stock Out">Stock Out</option>
                                    <option value="Transfer">Transfer</option>
                                    <option value="Adjustment">Adjustment</option>
                                    <option value="Return">Return</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" id="dateFilter" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Movements Table -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="bi bi-table"></i> Stock Movement History (Read-Only View)</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="movementsTable" class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Reference</th>
                                        <th>Performed By</th>
                                        <th>Date</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($movements)): ?>
                                        <?php foreach ($movements as $movement): ?>
                                            <tr>
                                                <td>#<?= esc($movement['id'] ?? '') ?></td>
                                                <td><strong><?= esc($movement['product_name'] ?? $movement['item_name'] ?? 'N/A') ?></strong></td>
                                                <td>
                                                    <span class="badge bg-<?= ($movement['type'] ?? '') === 'IN' ? 'success' : 'danger' ?>">
                                                        <?= ($movement['type'] ?? '') === 'IN' ? 'Stock In' : 'Stock Out' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong class="text-<?= ($movement['type'] ?? '') === 'IN' ? 'success' : 'danger' ?>">
                                                        <?= ($movement['type'] ?? '') === 'IN' ? '+' : '-' ?><?= number_format($movement['quantity'] ?? 0) ?>
                                                    </strong>
                                                </td>
                                                <td><code><?= esc($movement['reference_number'] ?? 'N/A') ?></code></td>
                                                <td><?= esc($movement['performed_by'] ?? $movement['username'] ?? 'System') ?></td>
                                                <td><?= isset($movement['created_at']) ? date('M d, Y H:i', strtotime($movement['created_at'])) : 'N/A' ?></td>
                                                <td><?= esc($movement['notes'] ?? $movement['reason'] ?? '-') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <i class="bi bi-inbox display-4 text-muted"></i>
                                                <p class="text-muted mt-2">No stock movements found</p>
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
    var table = $('#movementsTable').DataTable({
        pageLength: 25,
        order: [[0, 'desc']]
    });

    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('#typeFilter').on('change', function() {
        table.column(2).search(this.value).draw();
    });
});
</script>
</body>
</html>
