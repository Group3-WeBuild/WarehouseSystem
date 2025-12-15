<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discrepancy Review | WeBuild</title>
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
        .variance-positive { color: #28a745; font-weight: bold; }
        .variance-negative { color: #dc3545; font-weight: bold; }
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
            <a href="<?= base_url('inventory-auditor/discrepancy-review') ?>" class="active">⚠️ Discrepancies</a>
            <a href="<?= base_url('inventory-auditor/reports') ?>">📈 Reports</a>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <a href="<?= base_url('logout') ?>">🚪 Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-0">
            <div class="topbar">
                <div>
                    <a href="<?= base_url('inventory-auditor/dashboard') ?>" class="btn btn-outline-secondary btn-sm me-2">← Back</a>
                    <span class="fw-bold">Discrepancy Review</span>
                </div>
                <div>
                    <a href="<?= base_url('print/discrepancies') ?>" target="_blank" class="btn btn-outline-secondary btn-sm">🖨️ Print Report</a>
                </div>
            </div>

            <div class="p-4">
                <!-- Summary Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-danger text-white text-center py-3">
                            <h3 class="mb-0"><?= $stats['pending'] ?? count($discrepancies ?? []) ?></h3>
                            <small>Pending Review</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark text-center py-3">
                            <h3 class="mb-0"><?= $stats['under_investigation'] ?? 0 ?></h3>
                            <small>Under Investigation</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white text-center py-3">
                            <h3 class="mb-0"><?= $stats['resolved'] ?? 0 ?></h3>
                            <small>Resolved</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white text-center py-3">
                            <h3 class="mb-0">₱<?= number_format($stats['total_variance_value'] ?? 0, 2) ?></h3>
                            <small>Total Variance Value</small>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form class="row g-3" method="get">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="search" placeholder="Search item or count #..." value="<?= esc($_GET['search'] ?? '') ?>">
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="pending" <?= ($_GET['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="investigating" <?= ($_GET['status'] ?? '') == 'investigating' ? 'selected' : '' ?>>Investigating</option>
                                    <option value="resolved" <?= ($_GET['status'] ?? '') == 'resolved' ? 'selected' : '' ?>>Resolved</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="variance_type">
                                    <option value="">All Variances</option>
                                    <option value="shortage" <?= ($_GET['variance_type'] ?? '') == 'shortage' ? 'selected' : '' ?>>Shortage</option>
                                    <option value="overage" <?= ($_GET['variance_type'] ?? '') == 'overage' ? 'selected' : '' ?>>Overage</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="from_date" value="<?= esc($_GET['from_date'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">🔍 Filter</button>
                                <a href="<?= base_url('inventory-auditor/discrepancy-review') ?>" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Discrepancies Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Inventory Discrepancies
                        <span class="badge bg-danger"><?= count($discrepancies ?? []) ?> items with variance</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($discrepancies)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Count Session</th>
                                        <th>Item</th>
                                        <th>SKU</th>
                                        <th>System Qty</th>
                                        <th>Counted Qty</th>
                                        <th>Variance</th>
                                        <th>Value Impact</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($discrepancies as $d): ?>
                                    <?php 
                                    $variance = ($d['counted_quantity'] ?? 0) - ($d['system_quantity'] ?? 0);
                                    $valueImpact = abs($variance) * ($d['unit_cost'] ?? 0);
                                    ?>
                                    <tr>
                                        <td><?= esc($d['count_number'] ?? 'N/A') ?></td>
                                        <td><strong><?= esc($d['item_name'] ?? 'Unknown') ?></strong></td>
                                        <td><code><?= esc($d['sku'] ?? 'N/A') ?></code></td>
                                        <td class="text-center"><?= $d['system_quantity'] ?? 0 ?></td>
                                        <td class="text-center"><?= $d['counted_quantity'] ?? 0 ?></td>
                                        <td class="text-center">
                                            <span class="<?= $variance > 0 ? 'variance-positive' : 'variance-negative' ?>">
                                                <?= $variance > 0 ? '+' : '' ?><?= $variance ?>
                                            </span>
                                        </td>
                                        <td class="text-end text-danger">₱<?= number_format($valueImpact, 2) ?></td>
                                        <td>
                                            <?php 
                                            $status = $d['resolution_status'] ?? 'pending';
                                            $sClass = ['resolved' => 'success', 'investigating' => 'warning', 'pending' => 'danger'][$status] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $sClass ?>"><?= ucfirst($status) ?></span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#resolveModal" 
                                                    onclick="setResolveData(<?= $d['id'] ?? 0 ?>, '<?= esc($d['item_name'] ?? '') ?>', <?= $variance ?>)">
                                                Resolve
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="investigate(<?= $d['id'] ?? 0 ?>)">
                                                🔍
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5">
                            <div style="font-size: 4rem;">✅</div>
                            <h5 class="text-success">No pending discrepancies!</h5>
                            <p class="text-muted">All inventory counts match the system records</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resolve Modal -->
<div class="modal fade" id="resolveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resolve Discrepancy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('inventory-auditor/resolve-discrepancy') ?>" method="post">
                <input type="hidden" name="discrepancy_id" id="resolveDiscrepancyId">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Item:</strong> <span id="resolveItemName"></span><br>
                        <strong>Variance:</strong> <span id="resolveVariance"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Resolution Type</label>
                        <select class="form-select" name="resolution_type" required>
                            <option value="">Select Resolution</option>
                            <option value="adjust_system">Adjust System Quantity</option>
                            <option value="recount">Request Recount</option>
                            <option value="write_off">Write Off</option>
                            <option value="found">Items Found</option>
                            <option value="data_error">Data Entry Error</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Resolution Notes</label>
                        <textarea class="form-control" name="resolution_notes" rows="3" required placeholder="Explain the resolution..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Resolve Discrepancy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function setResolveData(id, itemName, variance) {
    document.getElementById('resolveDiscrepancyId').value = id;
    document.getElementById('resolveItemName').textContent = itemName;
    document.getElementById('resolveVariance').textContent = (variance > 0 ? '+' : '') + variance;
}

function investigate(id) {
    if(confirm('Mark this discrepancy as under investigation?')) {
        window.location.href = '<?= base_url('inventory-auditor/investigate-discrepancy/') ?>' + id;
    }
}
</script>
</body>
</html>
