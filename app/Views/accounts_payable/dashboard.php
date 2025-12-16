<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - WeBuild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #F3F4F6; color: #1F2937; }
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
        .topbar { background-color: #E5E7EB; padding: 10px 20px; display: flex; justify-content: space-between; }
        .dashboard-card { background: white; border-radius: 10px; padding: 15px; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 px-0 sidebar">
            <div class="text-center py-4">
                <h5 class="text-white mb-1">WeBuild</h5>
                <small class="text-white-50">Accounts Payable</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link active" href="<?= base_url('accounts-payable/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a class="nav-link" href="<?= base_url('accounts-payable/pending-invoices') ?>"><i class="bi bi-file-earmark-text"></i> Pending Invoices</a>
                <a class="nav-link" href="<?= base_url('accounts-payable/approved-invoices') ?>"><i class="bi bi-file-earmark-check"></i> Approved Invoices</a>
                <a class="nav-link" href="<?= base_url('accounts-payable/payment-processing') ?>"><i class="bi bi-credit-card"></i> Payment Processing</a>
                <a class="nav-link" href="<?= base_url('accounts-payable/vendor-management') ?>"><i class="bi bi-building"></i> Vendor Management</a>
                <a class="nav-link" href="<?= base_url('accounts-payable/payment-reports') ?>"><i class="bi bi-file-bar-graph"></i> Payment Reports</a>
                <a class="nav-link" href="<?= base_url('accounts-payable/overdue-payments') ?>"><i class="bi bi-exclamation-triangle"></i> Overdue Payments</a>
                <a class="nav-link" href="<?= base_url('accounts-payable/audit-trail') ?>"><i class="bi bi-journal-text"></i> Audit Trail</a>
            </nav>
        </div>
        <div class="col-md-10 p-0">
            <div class="topbar">
                <div>Date: <?php echo date("F j, Y"); ?> | Time: <?php echo date("h:i A"); ?></div>
                <div>
                    <?= esc($user['role']) ?> | <?= esc($user['name']) ?> 
                    <a href="<?= base_url('logout') ?>" class="btn btn-sm btn-outline-dark">Logout</a>
                </div>
            </div>
            <div class="p-4">
                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <h3>Accounts Payable Dashboard</h3>
                <p class="text-muted">Welcome, <?= esc($user['name']) ?>!</p>
                
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="dashboard-card">
                            <h5>Pending Invoices</h5>
                            <p class="fs-2 text-primary">24</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-card">
                            <h5>Pending Payments</h5>
                            <p class="fs-2 text-warning">₱45,230</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-card">
                            <h5>Overdue Items</h5>
                            <p class="fs-2 text-danger">8</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-card">
                            <h5>This Month Processed</h5>
                            <p class="fs-2 text-success">₱12,450</p>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Recent Activities</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <small class="text-muted">2 hours ago</small><br>
                                        Invoice #INV-2024-001 approved
                                    </li>
                                    <li class="mb-2">
                                        <small class="text-muted">4 hours ago</small><br>
                                        Payment processed for Vendor ABC Corp
                                    </li>
                                    <li class="mb-2">
                                        <small class="text-muted">1 day ago</small><br>
                                        New invoice received from XYZ Supplies
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Quick Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="<?= base_url('accounts-payable/pending-invoices') ?>" class="btn btn-primary">Review Pending Invoices</a>
                                    <a href="<?= base_url('accounts-payable/payment-processing') ?>" class="btn btn-success">Process Payments</a>
                                    <a href="<?= base_url('accounts-payable/overdue-payments') ?>" class="btn btn-warning">Check Overdue Items</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
</body>
</html>