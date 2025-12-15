<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Reports | WeBuild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f6fa; }
        .sidebar { background-color: #2e7d32; color: #fff; min-height: 100vh; padding-top: 20px; }
        .sidebar h5 { text-align: center; font-weight: 600; margin-bottom: 25px; }
        .sidebar a { display: block; color: #c8e6c9; text-decoration: none; padding: 12px 20px; margin: 5px 10px; border-radius: 5px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: #388e3c; color: #fff; }
        .topbar { background-color: #fff; border-bottom: 1px solid #ddd; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; }
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
        <div class="col-md-2 sidebar">
            <h5>🔍 Auditor</h5>
            <a href="<?= base_url('inventory-auditor/dashboard') ?>">📊 Dashboard</a>
            <a href="<?= base_url('inventory-auditor/count-sessions') ?>">📋 Count Sessions</a>
            <a href="<?= base_url('inventory-auditor/discrepancy-review') ?>">⚠️ Discrepancies</a>
            <a href="<?= base_url('inventory-auditor/reports') ?>" class="active">📈 Reports</a>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <a href="<?= base_url('logout') ?>">🚪 Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-0">
            <div class="topbar">
                <div>
                    <a href="<?= base_url('inventory-auditor/dashboard') ?>" class="btn btn-outline-secondary btn-sm me-2">← Back</a>
                    <span class="fw-bold">Audit Reports</span>
                </div>
                <span><?= date('M d, Y') ?></span>
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
                                        <option value="<?= $wh['id'] ?>"><?= esc($wh['name']) ?></option>
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
                        $categories = $accuracy['by_category'] ?? [
                            ['name' => 'Raw Materials', 'accuracy' => 98],
                            ['name' => 'Finished Goods', 'accuracy' => 94],
                            ['name' => 'Packaging', 'accuracy' => 97],
                            ['name' => 'Supplies', 'accuracy' => 91],
                        ];
                        foreach ($categories as $cat): ?>
                        <div class="row align-items-center mb-2">
                            <div class="col-md-3"><?= esc($cat['name']) ?></div>
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
