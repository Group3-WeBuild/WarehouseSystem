<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendors Management | WITMS</title>
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
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border-radius: 10px; }
        .table th { background-color: #f8f9fa; font-weight: 600; }
        .stat-card { 
            text-align: center; 
            padding: 20px; 
            border-radius: 10px; 
            color: #fff; 
            margin-bottom: 20px; 
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 px-0 sidebar">
            <div class="text-center py-4">
                <h5 class="text-white mb-1">WITMS</h5>
                <small class="text-white-50">Procurement Officer</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= base_url('procurement/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link" href="<?= base_url('procurement/requisitions') ?>">
                    <i class="bi bi-file-text"></i> Requisitions
                </a>
                <a class="nav-link" href="<?= base_url('procurement/purchase-orders') ?>">
                    <i class="bi bi-clipboard-check"></i> Purchase Orders
                </a>
                <a class="nav-link" href="<?= base_url('procurement/delivery-tracking') ?>">
                    <i class="bi bi-truck"></i> Delivery Tracking
                </a>
                <a class="nav-link active" href="<?= base_url('procurement/vendors') ?>">
                    <i class="bi bi-building"></i> Vendors
                </a>
                <a class="nav-link" href="<?= base_url('procurement/reports') ?>">
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
                    <h5 class="mb-0"><i class="bi bi-building text-primary"></i> Vendors Management</h5>
                </div>
                <div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVendorModal">
                        <i class="bi bi-plus-lg"></i> Add Vendor
                    </button>
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

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card bg-primary">
                            <h3><?= count($vendors ?? []) ?></h3>
                            <small>Total Vendors</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-success">
                            <h3><?= count(array_filter($vendors ?? [], fn($v) => ($v['status'] ?? '') === 'Active')) ?></h3>
                            <small>Active Vendors</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-warning">
                            <h3><?= count(array_filter($vendors ?? [], fn($v) => ($v['status'] ?? '') === 'Inactive')) ?></h3>
                            <small>Inactive Vendors</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-info">
                            <h3><?= count(array_filter($vendors ?? [], fn($v) => !empty($v['email']))) ?></h3>
                            <small>With Email</small>
                        </div>
                    </div>
                </div>

                <!-- Vendors Table -->
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="bi bi-table"></i> Vendor List</h6>
                        <input type="text" id="searchInput" class="form-control form-control-sm" style="width: 200px;" placeholder="Search vendors...">
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="vendorsTable" class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Vendor Name</th>
                                        <th>Contact Person</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Payment Terms</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($vendors)): ?>
                                        <?php foreach ($vendors as $vendor): ?>
                                            <tr>
                                                <td>#<?= esc($vendor['id'] ?? '') ?></td>
                                                <td><strong><?= esc($vendor['vendor_name'] ?? $vendor['name'] ?? 'Unknown') ?></strong></td>
                                                <td><?= esc($vendor['contact_person'] ?? '-') ?></td>
                                                <td><?= esc($vendor['email'] ?? '-') ?></td>
                                                <td><?= esc($vendor['phone'] ?? '-') ?></td>
                                                <td><?= esc($vendor['payment_terms'] ?? 'Net 30') ?></td>
                                                <td>
                                                    <span class="badge bg-<?= ($vendor['status'] ?? 'Active') === 'Active' ? 'success' : 'secondary' ?>">
                                                        <?= esc($vendor['status'] ?? 'Active') ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-info" onclick="viewVendor(<?= $vendor['id'] ?>)" title="View">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button class="btn btn-outline-warning" onclick="editVendor(<?= $vendor['id'] ?>)" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <i class="bi bi-building display-4 text-muted"></i>
                                                <p class="text-muted mt-2">No vendors found</p>
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVendorModal">
                                                    <i class="bi bi-plus-lg"></i> Add First Vendor
                                                </button>
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

<!-- Add Vendor Modal -->
<div class="modal fade" id="addVendorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Add New Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('procurement/create-vendor') ?>" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Vendor Name</label>
                            <input type="text" name="vendor_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tax ID</label>
                            <input type="text" name="tax_id" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Terms</label>
                            <select name="payment_terms" class="form-select">
                                <option value="Net 30">Net 30</option>
                                <option value="Net 60">Net 60</option>
                                <option value="COD">Cash on Delivery</option>
                                <option value="Prepaid">Prepaid</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Add Vendor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#vendorsTable').DataTable({
        pageLength: 25,
        order: [[0, 'desc']]
    });

    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });
});

function viewVendor(id) {
    alert('View vendor #' + id + ' - Feature coming soon');
}

function editVendor(id) {
    alert('Edit vendor #' + id + ' - Feature coming soon');
}
</script>
</body>
</html>
