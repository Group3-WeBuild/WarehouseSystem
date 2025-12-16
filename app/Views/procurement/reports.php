<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procurement Reports | WeBuild</title>
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
        .report-card { transition: 0.3s; cursor: pointer; }
        .report-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .report-icon { font-size: 3rem; margin-bottom: 15px; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 px-0 sidebar">
            <div class="text-center py-4">
                <h5 class="text-white mb-1">WeBuild</h5>
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
                <a class="nav-link" href="<?= base_url('procurement/vendors') ?>">
                    <i class="bi bi-building"></i> Vendors
                </a>
                <a class="nav-link active" href="<?= base_url('procurement/reports') ?>">
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
                    <h5 class="mb-0"><i class="bi bi-file-earmark-bar-graph text-primary"></i> Procurement Reports</h5>
                </div>
                <span class="text-muted"><?= date('M d, Y') ?></span>
            </div>

            <div class="p-4">
                <h5 class="mb-4">Generate Reports</h5>
                
                <!-- Report Cards -->
                <div class="row">
                    <div class="col-md-4">
                        <a href="<?= base_url('print/procurement-summary') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-primary">📋</div>
                                <h5>Procurement Summary</h5>
                                <p class="text-muted small">Overview of all procurement activities</p>
                                <span class="badge bg-primary">PDF Export</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('print/purchase-orders') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-success">📦</div>
                                <h5>Purchase Orders Report</h5>
                                <p class="text-muted small">All POs with status and amounts</p>
                                <span class="badge bg-success">PDF Export</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('print/vendor-performance') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-warning">🏢</div>
                                <h5>Vendor Performance</h5>
                                <p class="text-muted small">Delivery times and quality metrics</p>
                                <span class="badge bg-warning text-dark">PDF Export</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <a href="<?= base_url('print/spending-analysis') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-danger">💰</div>
                                <h5>Spending Analysis</h5>
                                <p class="text-muted small">Procurement costs by category</p>
                                <span class="badge bg-danger">PDF Export</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('print/requisitions') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-info">📝</div>
                                <h5>Requisitions Report</h5>
                                <p class="text-muted small">All requisitions and approval status</p>
                                <span class="badge bg-info">PDF Export</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('print/delivery-report') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-secondary">🚚</div>
                                <h5>Delivery Report</h5>
                                <p class="text-muted small">Delivery status and timelines</p>
                                <span class="badge bg-secondary">PDF Export</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Custom Report Generator -->
                <div class="card mt-4">
                    <div class="card-header">
                        ⚙️ Custom Report Generator
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('procurement/generate-report') ?>" method="post" target="_blank">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Report Type</label>
                                    <select class="form-select" name="report_type" required>
                                        <option value="">Select Type</option>
                                        <option value="purchase_orders">Purchase Orders</option>
                                        <option value="requisitions">Requisitions</option>
                                        <option value="spending">Spending Analysis</option>
                                        <option value="vendors">Vendor Performance</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">From Date</label>
                                    <input type="date" class="form-control" name="from_date" value="<?= date('Y-m-01') ?>">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">To Date</label>
                                    <input type="date" class="form-control" name="to_date" value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Vendor</label>
                                    <select class="form-select" name="vendor_id">
                                        <option value="">All Vendors</option>
                                        <?php foreach ($vendors ?? [] as $v): ?>
                                        <option value="<?= $v['id'] ?>"><?= esc($v['vendor_name'] ?? $v['name'] ?? 'Unknown') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        📄 Generate
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Procurement Stats -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">📊 Monthly Summary</div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <h3 class="text-primary">₱<?= number_format($stats['total_spent'] ?? 0, 0) ?></h3>
                                        <p class="text-muted">Total Spent</p>
                                    </div>
                                    <div class="col-6">
                                        <h3 class="text-success"><?= $stats['po_count'] ?? 0 ?></h3>
                                        <p class="text-muted">Purchase Orders</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <h3 class="text-warning"><?= $stats['pending_approval'] ?? 0 ?></h3>
                                        <p class="text-muted">Pending Approval</p>
                                    </div>
                                    <div class="col-6">
                                        <h3 class="text-info"><?= $stats['avg_delivery_days'] ?? 0 ?></h3>
                                        <p class="text-muted">Avg. Delivery Days</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">🏆 Top Vendors (by Spend)</div>
                            <div class="card-body">
                                <?php 
                                $topVendors = $stats['top_vendors'] ?? [
                                    ['name' => 'Vendor A', 'amount' => 150000],
                                    ['name' => 'Vendor B', 'amount' => 120000],
                                    ['name' => 'Vendor C', 'amount' => 95000],
                                    ['name' => 'Vendor D', 'amount' => 80000],
                                ];
                                $maxAmount = max(array_column($topVendors, 'amount'));
                                foreach ($topVendors as $v): 
                                $percentage = $maxAmount > 0 ? ($v['amount'] / $maxAmount) * 100 : 0;
                                ?>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span><?= esc($v['vendor_name'] ?? $v['name'] ?? 'Unknown') ?></span>
                                        <span class="text-muted">₱<?= number_format($v['amount'], 0) ?></span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary" style="width: <?= $percentage ?>%"></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
