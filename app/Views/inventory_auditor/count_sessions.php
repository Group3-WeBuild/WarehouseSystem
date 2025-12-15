<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Count Sessions | WeBuild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f6fa; }
        .sidebar { background-color: #2e7d32; color: #fff; min-height: 100vh; padding-top: 20px; }
        .sidebar h5 { text-align: center; font-weight: 600; margin-bottom: 25px; }
        .sidebar a { display: block; color: #c8e6c9; text-decoration: none; padding: 12px 20px; margin: 5px 10px; border-radius: 5px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: #388e3c; color: #fff; }
        .topbar { background-color: #fff; border-bottom: 1px solid #ddd; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; }
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border-radius: 10px; }
        .card-header { background-color: #f8f9fa; font-weight: 600; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <h5>🔍 Auditor</h5>
            <a href="<?= base_url('inventory-auditor/dashboard') ?>">📊 Dashboard</a>
            <a href="<?= base_url('inventory-auditor/count-sessions') ?>" class="active">📋 Count Sessions</a>
            <a href="<?= base_url('inventory-auditor/discrepancy-review') ?>">⚠️ Discrepancies</a>
            <a href="<?= base_url('inventory-auditor/reports') ?>">📈 Reports</a>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <a href="<?= base_url('logout') ?>">🚪 Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-0">
            <div class="topbar">
                <div>
                    <a href="<?= base_url('inventory-auditor/dashboard') ?>" class="btn btn-outline-secondary btn-sm me-2">← Back</a>
                    <span class="fw-bold">Inventory Count Sessions</span>
                </div>
                <div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newCountModal">+ New Count Session</button>
                    <a href="<?= base_url('print/count-sessions') ?>" target="_blank" class="btn btn-outline-secondary btn-sm">🖨️ Print</a>
                </div>
            </div>

            <div class="p-4">
                <!-- Stats Row -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white text-center py-3">
                            <h3 class="mb-0"><?= $stats['total'] ?? count($countSessions ?? []) ?></h3>
                            <small>Total Sessions</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark text-center py-3">
                            <h3 class="mb-0"><?= $stats['in_progress'] ?? 0 ?></h3>
                            <small>In Progress</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white text-center py-3">
                            <h3 class="mb-0"><?= $stats['completed'] ?? 0 ?></h3>
                            <small>Completed</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white text-center py-3">
                            <h3 class="mb-0"><?= $stats['with_discrepancies'] ?? 0 ?></h3>
                            <small>With Discrepancies</small>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form class="row g-3" method="get">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="search" placeholder="Search count #..." value="<?= esc($_GET['search'] ?? '') ?>">
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="scheduled" <?= ($_GET['status'] ?? '') == 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                                    <option value="in_progress" <?= ($_GET['status'] ?? '') == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="completed" <?= ($_GET['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Completed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="from_date" value="<?= esc($_GET['from_date'] ?? '') ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="to_date" value="<?= esc($_GET['to_date'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">🔍 Filter</button>
                                <a href="<?= base_url('inventory-auditor/count-sessions') ?>" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sessions Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Count Sessions
                        <span class="badge bg-primary"><?= count($countSessions ?? []) ?> records</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($countSessions)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Count #</th>
                                        <th>Type</th>
                                        <th>Warehouse</th>
                                        <th>Start Date</th>
                                        <th>Items</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($countSessions as $session): ?>
                                    <tr>
                                        <td><strong><?= esc($session['count_number'] ?? 'N/A') ?></strong></td>
                                        <td><span class="badge bg-secondary"><?= ucfirst($session['count_type'] ?? 'full') ?></span></td>
                                        <td><?= esc($session['warehouse_name'] ?? 'All Locations') ?></td>
                                        <td><?= date('M d, Y', strtotime($session['count_start_date'] ?? 'now')) ?></td>
                                        <td><?= esc($session['item_count'] ?? 0) ?> items</td>
                                        <td>
                                            <?php 
                                            $status = $session['status'] ?? 'scheduled';
                                            $sClass = ['completed' => 'success', 'in_progress' => 'warning', 'scheduled' => 'info'][$status] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $sClass ?>"><?= ucfirst(str_replace('_', ' ', $status)) ?></span>
                                        </td>
                                        <td>
                                            <?php if (($session['status'] ?? '') == 'in_progress'): ?>
                                            <a href="<?= base_url('inventory-auditor/active-count/' . ($session['id'] ?? 0)) ?>" class="btn btn-sm btn-warning">Continue</a>
                                            <?php elseif (($session['status'] ?? '') == 'scheduled'): ?>
                                            <a href="<?= base_url('inventory-auditor/start-count/' . ($session['id'] ?? 0)) ?>" class="btn btn-sm btn-success">Start</a>
                                            <?php else: ?>
                                            <a href="<?= base_url('inventory-auditor/active-count/' . ($session['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-primary">View</a>
                                            <?php endif; ?>
                                            <a href="<?= base_url('print/count-session/' . ($session['id'] ?? 0)) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">🖨️</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5">
                            <div style="font-size: 4rem;">📋</div>
                            <h5 class="text-muted">No count sessions found</h5>
                            <p class="text-muted">Schedule a new inventory count to get started</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCountModal">+ New Count Session</button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Count Modal -->
<div class="modal fade" id="newCountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule New Count Session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('inventory-auditor/create-count-session') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Count Type</label>
                        <select class="form-select" name="count_type" required>
                            <option value="full">Full Count</option>
                            <option value="cycle">Cycle Count</option>
                            <option value="spot">Spot Check</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warehouse/Location</label>
                        <select class="form-select" name="warehouse_id">
                            <option value="">All Locations</option>
                            <?php foreach ($warehouses ?? [] as $wh): ?>
                            <option value="<?= $wh['id'] ?>"><?= esc($wh['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Scheduled Date</label>
                        <input type="date" class="form-control" name="count_start_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule Count</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
