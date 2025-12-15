<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Auditor Dashboard | WeBuild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f6fa; }
        .sidebar { background-color: #2e7d32; color: #fff; min-height: 100vh; padding-top: 20px; }
        .sidebar h5 { text-align: center; font-weight: 600; margin-bottom: 25px; }
        .sidebar a { display: block; color: #c8e6c9; text-decoration: none; padding: 12px 20px; margin: 5px 10px; border-radius: 5px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: #388e3c; color: #fff; }
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
            <h5>🔍 Auditor</h5>
            <a href="<?= base_url('inventory-auditor/dashboard') ?>" class="active">📊 Dashboard</a>
            <a href="<?= base_url('inventory-auditor/count-sessions') ?>">📋 Count Sessions</a>
            <a href="<?= base_url('inventory-auditor/discrepancy-review') ?>">⚠️ Discrepancies</a>
            <a href="<?= base_url('inventory-auditor/reports') ?>">📈 Reports</a>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <a href="<?= base_url('logout') ?>">🚪 Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-0">
            <div class="topbar">
                <h5 class="mb-0">Inventory Auditor Dashboard</h5>
                <div>
                    <span class="me-3"><?= date('M d, Y | h:i A') ?></span>
                    <strong><?= esc($user['name'] ?? 'Auditor') ?></strong>
                </div>
            </div>

            <div class="p-4">
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h3 class="text-primary"><?= $stats['total_counts'] ?? 0 ?></h3>
                            <p>Total Count Sessions</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h3 class="text-warning"><?= $stats['in_progress'] ?? 0 ?></h3>
                            <p>In Progress</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h3 class="text-success"><?= $stats['completed'] ?? 0 ?></h3>
                            <p>Completed</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h3 class="text-danger"><?= $stats['discrepancies'] ?? 0 ?></h3>
                            <p>Pending Discrepancies</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-12"><h5 class="mb-3">Quick Actions</h5></div>
                    <div class="col-md-3">
                        <a href="<?= base_url('inventory-auditor/count-sessions') ?>" class="text-decoration-none">
                            <div class="quick-action">
                                <div class="text-primary" style="font-size:2rem;">📋</div>
                                <strong>Start New Count</strong>
                                <p class="text-muted small mb-0">Begin inventory count</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= base_url('inventory-auditor/discrepancy-review') ?>" class="text-decoration-none">
                            <div class="quick-action">
                                <div class="text-warning" style="font-size:2rem;">⚠️</div>
                                <strong>Review Discrepancies</strong>
                                <p class="text-muted small mb-0">Resolve variances</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= base_url('inventory-auditor/reports') ?>" class="text-decoration-none">
                            <div class="quick-action">
                                <div class="text-success" style="font-size:2rem;">📈</div>
                                <strong>Generate Report</strong>
                                <p class="text-muted small mb-0">Export audit reports</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= base_url('print/audit-sessions') ?>" target="_blank" class="text-decoration-none">
                            <div class="quick-action">
                                <div class="text-info" style="font-size:2rem;">🖨️</div>
                                <strong>Print Summary</strong>
                                <p class="text-muted small mb-0">Export to PDF</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Recent Counts -->
                <div class="card">
                    <div class="card-header">Recent Count Sessions</div>
                    <div class="card-body">
                        <?php if (!empty($recentCounts)): ?>
                        <table class="table table-hover">
                            <thead><tr><th>Count #</th><th>Warehouse</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
                            <tbody>
                                <?php foreach (array_slice($recentCounts, 0, 5) as $count): ?>
                                <tr>
                                    <td><?= esc($count['count_number'] ?? 'N/A') ?></td>
                                    <td><?= esc($count['warehouse_name'] ?? 'All') ?></td>
                                    <td><?= date('M d, Y', strtotime($count['count_start_date'] ?? 'now')) ?></td>
                                    <td><span class="badge bg-<?= ($count['status'] ?? '') == 'completed' ? 'success' : 'warning' ?>"><?= ucfirst($count['status'] ?? 'N/A') ?></span></td>
                                    <td><a href="<?= base_url('inventory-auditor/active-count/' . ($count['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <p class="text-center text-muted py-4">No recent count sessions. <a href="<?= base_url('inventory-auditor/count-sessions') ?>">Start one now</a></p>
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