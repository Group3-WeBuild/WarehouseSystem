<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Management - WeBuild</title>
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
        .vendor-card { background: white; border-radius: 10px; padding: 20px; margin-bottom: 15px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
        .btn-add { background-color: #3B82F6; color: white; border: none; }
        .btn-add:hover { background-color: #2563EB; }
        .btn-edit { background-color: #F59E0B; color: white; border: none; }
        .btn-edit:hover { background-color: #D97706; }
        .btn-history { background-color: #10B981; color: white; border: none; }
        .btn-history:hover { background-color: #059669; }
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
                <a class="nav-link active" href="<?= base_url('accounts-payable/vendor-management') ?>"><i class="bi bi-building"></i> Vendor Management</a>
                <a class="nav-link" href="<?= base_url('accounts-payable/payment-reports') ?>"><i class="bi bi-file-bar-graph"></i> Payment Reports</a>
                <a class="nav-link" href="<?= base_url('accounts-payable/overdue-payments') ?>"><i class="bi bi-exclamation-triangle"></i> Overdue Payments</a>
                <a class="nav-link" href="<?= base_url('accounts-payable/audit-trail') ?>"><i class="bi bi-journal-text"></i> Audit Trail</a>
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
                <h3>Vendor Management</h3>
                <p>Manage vendor information and payment terms</p>

                <!-- Add New Vendor Button -->
                <div class="mb-3">
                    <button class="btn btn-add">+ Add New Vendor</button>
                </div>

                <!-- Dynamic vendor data integration point -->
                <?php
                // Backend integration: Replace static vendor cards with dynamic data from database
                // Controller should pass $vendors array to this view
                // Example: $vendors = $vendorModel->findAll();
                ?>
                
                <!-- Vendor List -->
                <div class="vendor-card">
                    <h5>XYZ Materials</h5>
                    <p><strong>Payment Terms:</strong> Net 30</p>
                    <p><strong>Contact:</strong> Amarah Tador</p>
                    <p><strong>Phone:</strong> 09396987543</p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-edit btn-sm">Edit</button>
                        <button class="btn btn-history btn-sm">View History</button>
                    </div>
                </div>

                <!-- Additional vendor cards should be generated from database -->
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>