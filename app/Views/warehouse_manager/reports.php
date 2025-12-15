<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Reports | WITMS</title>
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
                <h5 class="text-white mb-1">WITMS</h5>
                <small class="text-white-50">Warehouse Manager</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= base_url('warehouse-manager/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-manager/inventory') ?>">
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
                <a class="nav-link active" href="<?= base_url('warehouse-manager/reports') ?>">
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
                    <h5 class="mb-0"><i class="bi bi-file-earmark-bar-graph text-primary"></i> Warehouse Reports</h5>
                </div>
                <span class="text-muted"><?= date('M d, Y') ?></span>
            </div>

            <div class="p-4">
                <h5 class="mb-4">Generate Reports</h5>
                
                <!-- Report Cards -->
                <div class="row">
                    <div class="col-md-4">
                        <a href="<?= base_url('print/inventory') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-primary"><i class="bi bi-box-seam"></i></div>
                                <h5>Inventory Report</h5>
                                <p class="text-muted small">Current stock levels, values, and locations</p>
                                <span class="badge bg-primary">PDF Export</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('print/stock-movements') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-success"><i class="bi bi-arrow-left-right"></i></div>
                                <h5>Movement Report</h5>
                                <p class="text-muted small">Stock in/out transactions and transfers</p>
                                <span class="badge bg-success">PDF Export</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('print/orders') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-warning"><i class="bi bi-cart"></i></div>
                                <h5>Order Report</h5>
                                <p class="text-muted small">Order fulfillment and shipping status</p>
                                <span class="badge bg-warning text-dark">PDF Export</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <a href="<?= base_url('print/low-stock') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-danger"><i class="bi bi-exclamation-triangle"></i></div>
                                <h5>Low Stock Alert</h5>
                                <p class="text-muted small">Items below reorder point</p>
                                <span class="badge bg-danger">PDF Export</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('print/batch-expiry') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-info"><i class="bi bi-calendar-x"></i></div>
                                <h5>Expiry Report</h5>
                                <p class="text-muted small">Batches expiring within 30/60/90 days</p>
                                <span class="badge bg-info">PDF Export</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('print/valuation') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-secondary"><i class="bi bi-currency-dollar"></i></div>
                                <h5>Valuation Report</h5>
                                <p class="text-muted small">Total inventory value by category</p>
                                <span class="badge bg-secondary">PDF Export</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Custom Report Generator -->
                <div class="card mt-4">
                    <div class="card-header">
                        <i class="bi bi-sliders"></i> Custom Report Generator
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('warehouse-manager/generate-report') ?>" method="post" target="_blank">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Report Type</label>
                                    <select class="form-select" name="report_type" required>
                                        <option value="">Select Type</option>
                                        <option value="inventory">Inventory Summary</option>
                                        <option value="movements">Stock Movements</option>
                                        <option value="orders">Orders</option>
                                        <option value="valuation">Valuation</option>
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
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category">
                                        <option value="">All Categories</option>
                                        <?php foreach ($categories ?? [] as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-file-earmark-pdf"></i> Generate
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Recent Reports -->
                <div class="card mt-4">
                    <div class="card-header">
                        <i class="bi bi-clock-history"></i> Recently Generated Reports
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentReports)): ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Report Name</th>
                                    <th>Type</th>
                                    <th>Generated On</th>
                                    <th>Generated By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentReports as $report): ?>
                                <tr>
                                    <td><?= esc($report['name'] ?? 'Report') ?></td>
                                    <td><span class="badge bg-secondary"><?= ucfirst($report['type'] ?? 'General') ?></span></td>
                                    <td><?= date('M d, Y h:i A', strtotime($report['created_at'] ?? 'now')) ?></td>
                                    <td><?= esc($report['user_name'] ?? 'System') ?></td>
                                    <td><a href="<?= $report['url'] ?? '#' ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Download</a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-file-earmark display-4"></i>
                            <p class="mt-2">No recent reports. Generate one using the options above.</p>
                        </div>
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
