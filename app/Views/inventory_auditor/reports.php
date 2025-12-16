<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Reports | WITMS</title>
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
                <h5 class="text-white mb-1">WITMS</h5>
                <small class="text-white-50">Inventory Auditor</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= base_url('inventory-auditor/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link" href="<?= base_url('inventory-auditor/count-sessions') ?>">
                    <i class="bi bi-clipboard-check"></i> Count Sessions
                </a>
                <a class="nav-link" href="<?= base_url('inventory-auditor/discrepancy-review') ?>">
                    <i class="bi bi-exclamation-triangle"></i> Discrepancies
                </a>
                <a class="nav-link active" href="<?= base_url('inventory-auditor/reports') ?>">
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
                    <h5 class="mb-0"><i class="bi bi-file-earmark-bar-graph text-primary"></i> Audit Reports</h5>
                </div>
                <span class="text-muted"><?= date('M d, Y') ?></span>
            </div>

            <div class="p-4">
                <h5 class="mb-4">Generate Audit Reports</h5>
                
                <!-- Report Cards -->
                <div class="row">
                    <div class="col-md-4">
                        <a href="<?= base_url('print/audit-sessions') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-primary">📋</div>
                                <h5>Count Session Summary</h5>
                                <p class="text-muted small">Overview of all count sessions with completion rates</p>
                                <span class="badge bg-primary">PDF Export</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('print/discrepancies') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-danger">⚠️</div>
                                <h5>Discrepancy Report</h5>
                                <p class="text-muted small">All inventory variances and resolutions</p>
                                <span class="badge bg-danger">PDF Export</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('print/accuracy-report') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-success">📊</div>
                                <h5>Accuracy Report</h5>
                                <p class="text-muted small">Inventory accuracy metrics over time</p>
                                <span class="badge bg-success">PDF Export</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <a href="<?= base_url('print/variance-analysis') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-warning">📉</div>
                                <h5>Variance Analysis</h5>
                                <p class="text-muted small">Detailed breakdown by category and location</p>
                                <span class="badge bg-warning text-dark">PDF Export</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('print/cycle-count-schedule') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-info">📅</div>
                                <h5>Cycle Count Schedule</h5>
                                <p class="text-muted small">Upcoming scheduled counts and frequencies</p>
                                <span class="badge bg-info">PDF Export</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('print/auditor-performance') ?>" target="_blank" class="text-decoration-none">
                            <div class="card report-card text-center p-4">
                                <div class="report-icon text-secondary">👤</div>
                                <h5>Auditor Performance</h5>
                                <p class="text-muted small">Counts completed and accuracy by auditor</p>
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
                        <form action="<?= base_url('inventory-auditor/generate-report') ?>" method="post" target="_blank">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Report Type</label>
                                    <select class="form-select" name="report_type" required>
                                        <option value="">Select Type</option>
                                        <option value="sessions">Count Sessions</option>
                                        <option value="discrepancies">Discrepancies</option>
                                        <option value="accuracy">Accuracy</option>
                                        <option value="variances">Variances</option>
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
                                    <label class="form-label">Warehouse</label>
                                    <select class="form-select" name="warehouse_id">
                                        <option value="">All Warehouses</option>
                                        <?php foreach ($warehouses ?? [] as $wh): ?>
                                        <option value="<?= $wh['id'] ?>"><?= esc($wh['warehouse_name'] ?? $wh['name'] ?? 'Unknown') ?></option>
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

                <!-- Accuracy Overview -->
                <div class="card mt-4">
                    <div class="card-header">
                        📊 Accuracy Overview (Last 30 Days)
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h2 class="text-success"><?= number_format($accuracy['overall'] ?? 95.5, 1) ?>%</h2>
                                <p class="text-muted">Overall Accuracy</p>
                            </div>
                            <div class="col-md-3">
                                <h2 class="text-primary"><?= $accuracy['counts_completed'] ?? 12 ?></h2>
                                <p class="text-muted">Counts Completed</p>
                            </div>
                            <div class="col-md-3">
                                <h2 class="text-warning"><?= $accuracy['items_counted'] ?? 1250 ?></h2>
                                <p class="text-muted">Items Counted</p>
                            </div>
                            <div class="col-md-3">
                                <h2 class="text-danger"><?= $accuracy['discrepancies_found'] ?? 45 ?></h2>
                                <p class="text-muted">Discrepancies Found</p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h6>Accuracy by Category</h6>
                        <?php 
                        $categoryAccuracy = $accuracy['by_category'] ?? [
                            ['category_name' => 'Raw Materials', 'accuracy' => 98],
                            ['category_name' => 'Finished Goods', 'accuracy' => 94],
                            ['category_name' => 'Packaging', 'accuracy' => 97],
                            ['category_name' => 'Supplies', 'accuracy' => 91],
                        ];
                        foreach ($categoryAccuracy as $cat): ?>
                        <div class="row align-items-center mb-2">
                            <div class="col-md-3"><?= esc($cat['category_name'] ?? $cat['name'] ?? 'Unknown') ?></div>
                            <div class="col-md-7">
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-<?= $cat['accuracy'] >= 95 ? 'success' : ($cat['accuracy'] >= 90 ? 'warning' : 'danger') ?>" style="width: <?= $cat['accuracy'] ?>%"></div>
                                </div>
                            </div>
                            <div class="col-md-2 text-end"><strong><?= $cat['accuracy'] ?>%</strong></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
