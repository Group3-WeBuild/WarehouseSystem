<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Trail - WeBuild</title>
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
        .content-card { background: white; border-radius: 10px; padding: 20px; margin-top: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
        .filter-bar { display: flex; gap: 10px; margin-bottom: 15px; }
        .activity-item { padding: 10px 0; border-bottom: 1px solid #E5E7EB; }
        .activity-item:last-child { border-bottom: none; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 px-0 sidebar">
            <div class="text-center py-4">
                <h5 class="text-white mb-1">WeBuild</h5>
                <small class="text-white-50">Accounts Payable</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= base_url('accounts-payable/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a class="nav-link" href="<?= base_url('accounts-payable/pending-invoices') ?>"><i class="bi bi-file-earmark-text"></i> Pending Invoices</a>
                <a class="nav-link" href="<?= base_url('accounts-payable/approved-invoices') ?>"><i class="bi bi-file-earmark-check"></i> Approved Invoices</a>
                <a class="nav-link" href="<?= base_url('accounts-payable/payment-processing') ?>"><i class="bi bi-credit-card"></i> Payment Processing</a>
                <a class="nav-link" href="<?= base_url('accounts-payable/vendor-management') ?>"><i class="bi bi-building"></i> Vendor Management</a>
                <a class="nav-link" href="<?= base_url('accounts-payable/payment-reports') ?>"><i class="bi bi-file-bar-graph"></i> Payment Reports</a>
                <a class="nav-link" href="<?= base_url('accounts-payable/overdue-payments') ?>"><i class="bi bi-exclamation-triangle"></i> Overdue Payments</a>
                <a class="nav-link active" href="<?= base_url('accounts-payable/audit-trail') ?>"><i class="bi bi-journal-text"></i> Audit Trail</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-0">
            <div class="topbar">
                <div>Date: <?php echo date("F j, Y"); ?> | Time: <?php echo date("h:i A"); ?></div>
                <div>
                    <?= session()->get('role') ?> | <?= session()->get('name') ?> 
                    <a href="<?= base_url('logout') ?>" class="btn btn-sm btn-outline-dark">Logout</a>
                </div>
            </div>
            <div class="p-4">
                <h3>Audit Trail</h3>
                <p>Track all payment transactions and system activities.</p>

                <!-- Filter Bar -->
                <div class="filter-bar">
                    <select class="form-select" style="max-width: 250px;">
                        <option value="">-- Filter by Activity --</option>
                        <option>All Activities</option>
                        <option>Payment Processed</option>
                        <option>Vendor Added</option>
                        <option>Invoice Approved</option>
                        <option>User Login</option>
                        <option>Settings Updated</option>
                    </select>
                    <input type="text" class="form-control" placeholder="Search activities..." style="max-width: 250px;">
                </div>

                <!-- Recent Activities -->
                <div class="content-card">
                    <h5>Recent Activities</h5>
                    <?php
                    // Backend integration: Replace static activities with dynamic audit data
                    // Controller should pass $auditActivities array to this view
                    // Example: $auditActivities = $auditModel->getRecentActivities();
                    ?>
                    <div class="activity-item">
                        <strong>Payment Processed</strong> - INV-2025-015 (₱14,500.00)<br>
                        by Warehouse Manager on 2025-08-23 10:30:15 UTC
                    </div>
                    <div class="activity-item">
                        <strong>Vendor Added</strong> - XYZ Materials added by Admin on 2025-08-22 09:12:45 UTC
                    </div>
                    <div class="activity-item">
                        <strong>Invoice Approved</strong> - INV-2025-012 approved by Finance Head on 2025-08-21 11:45:10 UTC
                    </div>
                    <!-- Additional activities will be generated from $auditActivities array -->
                </div>

            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>