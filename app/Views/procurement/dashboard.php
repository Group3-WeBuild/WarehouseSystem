<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procurement Dashboard | WeBuild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f6fa; }
        .sidebar { background-color: #1565c0; color: #fff; min-height: 100vh; padding-top: 20px; }
        .sidebar h5 { text-align: center; font-weight: 600; margin-bottom: 25px; }
        .sidebar a { display: block; color: #bbdefb; text-decoration: none; padding: 12px 20px; margin: 5px 10px; border-radius: 5px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: #1976d2; color: #fff; }
        .topbar { background-color: #fff; border-bottom: 1px solid #ddd; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; }
        .stat-card { background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); text-align: center; margin-bottom: 20px; }
        .stat-card h3 { font-size: 2.5rem; font-weight: 700; margin-bottom: 5px; }
        .stat-card p { color: #666; margin-bottom: 0; }
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border-radius: 10px; margin-bottom: 20px; }
        .card-header { background-color: #f8f9fa; font-weight: 600; }
        .quick-action { padding: 20px; text-align: center; border-radius: 10px; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: 0.3s; }
        .quick-action:hover { transform: translateY(-3px); box-shadow: 0 5px 20px rgba(0,0,0,0.15); }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <h5>📦 Procurement</h5>
            <a href="<?= base_url('procurement/dashboard') ?>" class="active">📊 Dashboard</a>
            <a href="<?= base_url('procurement/requisitions') ?>">📝 Requisitions</a>
            <a href="<?= base_url('procurement/purchase-orders') ?>">📋 Purchase Orders</a>
            <a href="<?= base_url('procurement/vendors') ?>">🏢 Vendors</a>
            <a href="<?= base_url('procurement/reports') ?>">📈 Reports</a>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <a href="<?= base_url('logout') ?>">🚪 Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-0">
            <div class="topbar">
                <h5 class="mb-0">Procurement Dashboard</h5>
                <div>
                    <span class="me-3"><?= date('M d, Y | h:i A') ?></span>
                    <strong><?= esc($user['name'] ?? 'Procurement Officer') ?></strong>
                </div>
            </div>

            <div class="p-4">
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h3 class="text-primary"><?= $reqStats['total'] ?? 0 ?></h3>
                            <p>Total Requisitions</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h3 class="text-warning"><?= $reqStats['pending'] ?? count($pendingApproval ?? []) ?></h3>
                            <p>Pending Approval</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h3 class="text-success"><?= $poStats['total'] ?? 0 ?></h3>
                            <p>Purchase Orders</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h3 class="text-danger"><?= count($overduePOs ?? []) ?></h3>
                            <p>Overdue POs</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-12"><h5 class="mb-3">Quick Actions</h5></div>
                    <div class="col-md-3">
                        <a href="<?= base_url('procurement/requisitions?action=create') ?>" class="text-decoration-none">
                            <div class="quick-action">
                                <div class="text-primary" style="font-size:2rem;">📝</div>
                                <strong>New Requisition</strong>
                                <p class="text-muted small mb-0">Create purchase request</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= base_url('procurement/purchase-orders?action=create') ?>" class="text-decoration-none">
                            <div class="quick-action">
                                <div class="text-success" style="font-size:2rem;">📋</div>
                                <strong>New PO</strong>
                                <p class="text-muted small mb-0">Create purchase order</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= base_url('procurement/vendors') ?>" class="text-decoration-none">
                            <div class="quick-action">
                                <div class="text-info" style="font-size:2rem;">🏢</div>
                                <strong>Manage Vendors</strong>
                                <p class="text-muted small mb-0">View/add vendors</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= base_url('print/procurement-summary') ?>" target="_blank" class="text-decoration-none">
                            <div class="quick-action">
                                <div class="text-secondary" style="font-size:2rem;">🖨️</div>
                                <strong>Print Report</strong>
                                <p class="text-muted small mb-0">Export to PDF</p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row">
                    <!-- Pending Approvals -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                Pending Approvals
                                <a href="<?= base_url('procurement/requisitions?status=pending') ?>" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($pendingApproval)): ?>
                                <table class="table table-hover table-sm">
                                    <thead><tr><th>Req #</th><th>Requestor</th><th>Date</th><th>Action</th></tr></thead>
                                    <tbody>
                                        <?php foreach (array_slice($pendingApproval, 0, 5) as $req): ?>
                                        <tr>
                                            <td><?= esc($req['requisition_number'] ?? 'N/A') ?></td>
                                            <td><?= esc($req['created_by_name'] ?? 'Unknown') ?></td>
                                            <td><?= date('M d', strtotime($req['created_at'] ?? 'now')) ?></td>
                                            <td><a href="<?= base_url('procurement/requisitions/' . ($req['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-success">Review</a></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                <p class="text-center text-muted py-3">No pending approvals</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Overdue POs -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span class="text-danger">⚠️ Overdue Purchase Orders</span>
                                <a href="<?= base_url('procurement/purchase-orders?status=overdue') ?>" class="btn btn-sm btn-outline-danger">View All</a>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($overduePOs)): ?>
                                <table class="table table-hover table-sm">
                                    <thead><tr><th>PO #</th><th>Vendor</th><th>Due Date</th><th>Action</th></tr></thead>
                                    <tbody>
                                        <?php foreach (array_slice($overduePOs, 0, 5) as $po): ?>
                                        <tr>
                                            <td><?= esc($po['po_number'] ?? 'N/A') ?></td>
                                            <td><?= esc($po['vendor_name'] ?? 'Unknown') ?></td>
                                            <td class="text-danger"><?= date('M d', strtotime($po['expected_delivery_date'] ?? 'now')) ?></td>
                                            <td><a href="<?= base_url('procurement/purchase-orders/' . ($po['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-warning">Follow Up</a></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                <p class="text-center text-success py-3">✓ No overdue purchase orders</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Deliveries -->
                <div class="card">
                    <div class="card-header">Pending Deliveries</div>
                    <div class="card-body">
                        <?php if (!empty($pendingDelivery)): ?>
                        <table class="table table-hover">
                            <thead><tr><th>PO #</th><th>Vendor</th><th>Expected Date</th><th>Status</th><th>Action</th></tr></thead>
                            <tbody>
                                <?php foreach (array_slice($pendingDelivery, 0, 10) as $del): ?>
                                <tr>
                                    <td><?= esc($del['po_number'] ?? 'N/A') ?></td>
                                    <td><?= esc($del['vendor_name'] ?? 'Unknown') ?></td>
                                    <td><?= date('M d, Y', strtotime($del['expected_delivery_date'] ?? 'now')) ?></td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>
                                        <a href="<?= base_url('procurement/receive/' . ($del['id'] ?? 0)) ?>" class="btn btn-sm btn-success">Receive</a>
                                        <a href="<?= base_url('procurement/purchase-orders/' . ($del['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <p class="text-center text-muted py-3">No pending deliveries</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>