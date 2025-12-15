<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | WITMS</title>
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
                <h5 class="text-white mb-1">WITMS</h5>
                <small class="text-white-50">Warehouse Staff</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= base_url('warehouse-staff/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-staff/inventory') ?>">
                    <i class="bi bi-box-seam"></i> View Inventory
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-staff/stock-movements') ?>">
                    <i class="bi bi-arrow-left-right"></i> Stock Movements
                </a>
                <a class="nav-link active" href="<?= base_url('warehouse-staff/orders') ?>">
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
                    <h5 class="mb-0"><i class="bi bi-cart text-primary"></i> Orders</h5>
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
                    <div class="col-md-3">
                        <div class="card text-center p-3 bg-warning text-dark">
                            <h3><?= number_format(count(array_filter($orders ?? [], fn($o) => ($o['status'] ?? '') === 'Pending'))) ?></h3>
                            <small>Pending</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center p-3 bg-info text-white">
                            <h3><?= number_format(count(array_filter($orders ?? [], fn($o) => ($o['status'] ?? '') === 'In Progress'))) ?></h3>
                            <small>In Progress</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center p-3 bg-success text-white">
                            <h3><?= number_format(count(array_filter($orders ?? [], fn($o) => ($o['status'] ?? '') === 'Completed'))) ?></h3>
                            <small>Completed</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center p-3 bg-primary text-white">
                            <h3><?= number_format(count($orders ?? [])) ?></h3>
                            <small>Total Orders</small>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search orders...">
                            </div>
                            <div class="col-md-3">
                                <select id="statusFilter" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="bi bi-table"></i> Order List</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="ordersTable" class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer/Reference</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($orders)): ?>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td><strong>#<?= esc($order['id'] ?? '') ?></strong></td>
                                                <td><?= esc($order['customer_name'] ?? $order['reference'] ?? 'N/A') ?></td>
                                                <td><?= esc($order['item_count'] ?? $order['total_items'] ?? '-') ?></td>
                                                <td>₱<?= number_format($order['total_amount'] ?? $order['total'] ?? 0, 2) ?></td>
                                                <td>
                                                    <?php
                                                    $status = $order['status'] ?? 'Pending';
                                                    $statusClass = match($status) {
                                                        'Completed' => 'success',
                                                        'In Progress' => 'info',
                                                        'Cancelled' => 'danger',
                                                        default => 'warning'
                                                    };
                                                    ?>
                                                    <span class="badge bg-<?= $statusClass ?>"><?= esc($status) ?></span>
                                                </td>
                                                <td><?= isset($order['created_at']) ? date('M d, Y', strtotime($order['created_at'])) : 'N/A' ?></td>
                                                <td>
                                                    <?php if (($order['status'] ?? '') === 'Pending'): ?>
                                                        <button class="btn btn-sm btn-info" onclick="updateStatus(<?= $order['id'] ?? 0 ?>, 'In Progress')">
                                                            <i class="bi bi-play-fill"></i> Start
                                                        </button>
                                                    <?php elseif (($order['status'] ?? '') === 'In Progress'): ?>
                                                        <button class="btn btn-sm btn-success" onclick="updateStatus(<?= $order['id'] ?? 0 ?>, 'Completed')">
                                                            <i class="bi bi-check-lg"></i> Complete
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <i class="bi bi-inbox display-4 text-muted"></i>
                                                <p class="text-muted mt-2">No orders found</p>
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
    var table = $('#ordersTable').DataTable({
        pageLength: 25,
        order: [[0, 'desc']]
    });

    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('#statusFilter').on('change', function() {
        table.column(4).search(this.value).draw();
    });
});

function updateStatus(orderId, status) {
    if (confirm('Update order status to "' + status + '"?')) {
        fetch('<?= base_url('warehouse-staff/update-order-status/') ?>' + orderId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'status=' + encodeURIComponent(status)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to update status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
}
</script>
</body>
</html>
