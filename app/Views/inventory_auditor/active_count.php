<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Count | WeBuild</title>
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
        .count-input { width: 100px; text-align: center; }
        .match { background-color: #d4edda !important; }
        .mismatch { background-color: #f8d7da !important; }
        .progress-bar { transition: width 0.5s ease; }
        .table th { background-color: #f8f9fa; font-weight: 600; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 px-0 sidebar">
            <div class="text-center py-4">
                <h5 class="text-white mb-1">WeBuild</h5>
                <small class="text-white-50">Inventory Auditor</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= base_url('inventory-auditor/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link active" href="<?= base_url('inventory-auditor/count-sessions') ?>">
                    <i class="bi bi-clipboard-check"></i> Count Sessions
                </a>
                <a class="nav-link" href="<?= base_url('inventory-auditor/discrepancy-review') ?>">
                    <i class="bi bi-exclamation-triangle"></i> Discrepancies
                </a>
                <a class="nav-link" href="<?= base_url('inventory-auditor/reports') ?>">
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
                    <h5 class="mb-0"><i class="bi bi-clipboard-check text-primary"></i> Active Count: <?= esc($countSession['count_number'] ?? 'N/A') ?></h5>
                </div>
                <div>
                    <span class="badge bg-warning text-dark me-2"><?= ucfirst($countSession['status'] ?? 'in_progress') ?></span>
                    <button class="btn btn-success btn-sm" onclick="saveAllCounts()"><i class="bi bi-save"></i> Save Progress</button>
                    <button class="btn btn-primary btn-sm" onclick="completeCount()"><i class="bi bi-check-lg"></i> Complete Count</button>
                </div>
            </div>

            <div class="p-4">
                <!-- Count Info -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Count #:</strong><br>
                                        <?= esc($countSession['count_number'] ?? 'N/A') ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Type:</strong><br>
                                        <?= ucfirst($countSession['count_type'] ?? 'Full') ?> Count
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Warehouse:</strong><br>
                                        <?= esc($countSession['warehouse_name'] ?? 'All Locations') ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Started:</strong><br>
                                        <?= date('M d, Y h:i A', strtotime($countSession['count_start_date'] ?? 'now')) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5>Count Progress</h5>
                                <?php 
                                $totalItems = count($items ?? []);
                                $countedItems = count(array_filter($items ?? [], fn($i) => isset($i['counted_quantity'])));
                                $progress = $totalItems > 0 ? round(($countedItems / $totalItems) * 100) : 0;
                                ?>
                                <div class="progress mb-2" style="height: 25px;">
                                    <div class="progress-bar bg-success" style="width: <?= $progress ?>%"><?= $progress ?>%</div>
                                </div>
                                <small class="text-muted"><?= $countedItems ?> of <?= $totalItems ?> items counted</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search/Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="searchItem" placeholder="Search item name or SKU..." onkeyup="filterItems()">
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" id="filterStatus" onchange="filterItems()">
                                    <option value="">All Items</option>
                                    <option value="uncounted">Not Counted</option>
                                    <option value="match">Match</option>
                                    <option value="mismatch">Mismatch</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" id="filterCategory" onchange="filterItems()">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories ?? [] as $cat): ?>
                                    <option value="<?= $cat['id'] ?? '' ?>"><?= esc($cat['category_name'] ?? $cat['name'] ?? 'Unknown') ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Items to Count
                        <span class="badge bg-primary"><?= $totalItems ?> items</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($items)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th>SKU</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Location</th>
                                        <th>System Qty</th>
                                        <th>Counted Qty</th>
                                        <th>Variance</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $index => $item): ?>
                                    <?php 
                                    $systemQty = $item['system_quantity'] ?? $item['quantity'] ?? 0;
                                    $countedQty = $item['counted_quantity'] ?? null;
                                    $variance = $countedQty !== null ? $countedQty - $systemQty : null;
                                    $rowClass = $countedQty !== null ? ($variance == 0 ? 'match' : 'mismatch') : '';
                                    ?>
                                    <tr class="item-row <?= $rowClass ?>" data-category="<?= $item['category_id'] ?? '' ?>" data-status="<?= $countedQty === null ? 'uncounted' : ($variance == 0 ? 'match' : 'mismatch') ?>">
                                        <td><code><?= esc($item['sku'] ?? 'N/A') ?></code></td>
                                        <td><strong><?= esc($item['name'] ?? $item['item_name'] ?? 'Unknown') ?></strong></td>
                                        <td><?= esc($item['category_name'] ?? 'N/A') ?></td>
                                        <td><?= esc($item['location'] ?? 'Main') ?></td>
                                        <td class="text-center"><span class="badge bg-secondary"><?= $systemQty ?></span></td>
                                        <td>
                                            <input type="number" class="form-control count-input" 
                                                   id="count_<?= $item['id'] ?>" 
                                                   data-item-id="<?= $item['id'] ?>" 
                                                   data-system-qty="<?= $systemQty ?>"
                                                   value="<?= $countedQty ?>" 
                                                   min="0" 
                                                   onchange="updateVariance(this)">
                                        </td>
                                        <td class="text-center variance-cell" id="variance_<?= $item['id'] ?>">
                                            <?php if ($variance !== null): ?>
                                            <span class="badge bg-<?= $variance == 0 ? 'success' : 'danger' ?>"><?= $variance > 0 ? '+' : '' ?><?= $variance ?></span>
                                            <?php else: ?>
                                            <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center status-cell" id="status_<?= $item['id'] ?>">
                                            <?php if ($countedQty !== null): ?>
                                            <span class="badge bg-<?= $variance == 0 ? 'success' : 'warning' ?>"><?= $variance == 0 ? '✓ Match' : '⚠️ Check' ?></span>
                                            <?php else: ?>
                                            <span class="badge bg-secondary">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5">
                            <div style="font-size: 4rem;">📦</div>
                            <h5 class="text-muted">No items in this count session</h5>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function updateVariance(input) {
    const itemId = input.dataset.itemId;
    const systemQty = parseInt(input.dataset.systemQty);
    const countedQty = input.value !== '' ? parseInt(input.value) : null;
    const varianceCell = document.getElementById('variance_' + itemId);
    const statusCell = document.getElementById('status_' + itemId);
    const row = input.closest('tr');
    
    if (countedQty !== null) {
        const variance = countedQty - systemQty;
        varianceCell.innerHTML = `<span class="badge bg-${variance == 0 ? 'success' : 'danger'}">${variance > 0 ? '+' : ''}${variance}</span>`;
        statusCell.innerHTML = `<span class="badge bg-${variance == 0 ? 'success' : 'warning'}">${variance == 0 ? '✓ Match' : '⚠️ Check'}</span>`;
        row.classList.remove('match', 'mismatch');
        row.classList.add(variance == 0 ? 'match' : 'mismatch');
        row.dataset.status = variance == 0 ? 'match' : 'mismatch';
    } else {
        varianceCell.innerHTML = '<span class="text-muted">—</span>';
        statusCell.innerHTML = '<span class="badge bg-secondary">Pending</span>';
        row.classList.remove('match', 'mismatch');
        row.dataset.status = 'uncounted';
    }
}

function filterItems() {
    const search = document.getElementById('searchItem').value.toLowerCase();
    const status = document.getElementById('filterStatus').value;
    const category = document.getElementById('filterCategory').value;
    
    document.querySelectorAll('.item-row').forEach(row => {
        const text = row.textContent.toLowerCase();
        const matchSearch = text.includes(search);
        const matchStatus = !status || row.dataset.status === status;
        const matchCategory = !category || row.dataset.category === category;
        row.style.display = matchSearch && matchStatus && matchCategory ? '' : 'none';
    });
}

function saveAllCounts() {
    const counts = [];
    document.querySelectorAll('.count-input').forEach(input => {
        if (input.value !== '') {
            counts.push({
                item_id: input.dataset.itemId,
                counted_quantity: parseInt(input.value)
            });
        }
    });
    
    fetch('<?= base_url('inventory-auditor/save-counts/' . ($countSession['id'] ?? 0)) ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({counts: counts})
    })
    .then(r => r.json())
    .then(data => {
        alert(data.success ? 'Progress saved!' : 'Error saving: ' + data.message);
    });
}

function completeCount() {
    if(confirm('Complete this count session? All items will be marked as counted.')) {
        window.location.href = '<?= base_url('inventory-auditor/complete-count/' . ($countSession['id'] ?? 0)) ?>';
    }
}
</script>
</body>
</html>
