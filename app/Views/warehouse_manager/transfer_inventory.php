<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Inventory | WeBuild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f6fa; }
        .sidebar { background-color: #5c6bc0; color: #fff; min-height: 100vh; padding-top: 20px; }
        .sidebar h5 { text-align: center; font-weight: 600; margin-bottom: 25px; }
        .sidebar a { display: block; color: #c5cae9; text-decoration: none; padding: 12px 20px; margin: 5px 10px; border-radius: 5px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: #7986cb; color: #fff; }
        .topbar { background-color: #fff; border-bottom: 1px solid #ddd; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; }
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border-radius: 10px; margin-bottom: 20px; }
        .card-header { background-color: #f8f9fa; font-weight: 600; }
        .transfer-arrow { font-size: 3rem; color: #5c6bc0; }
        .warehouse-box { border: 2px dashed #ddd; border-radius: 10px; padding: 20px; text-align: center; min-height: 150px; }
        .warehouse-box.selected { border-color: #5c6bc0; background-color: #f0f2ff; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <h5>🏭 Warehouse</h5>
            <a href="<?= base_url('warehouse-manager/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="<?= base_url('warehouse-manager/inventory') ?>"><i class="bi bi-box-seam"></i> Inventory</a>
            <a href="<?= base_url('warehouse-manager/stock-movements') ?>" class="active"><i class="bi bi-arrow-left-right"></i> Stock Movements</a>
            <a href="<?= base_url('warehouse-manager/orders') ?>"><i class="bi bi-cart"></i> Orders</a>
            <a href="<?= base_url('warehouse-manager/batch-tracking') ?>"><i class="bi bi-upc-scan"></i> Batch Tracking</a>
            <a href="<?= base_url('warehouse-manager/reports') ?>"><i class="bi bi-file-earmark-text"></i> Reports</a>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <a href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-0">
            <div class="topbar">
                <div>
                    <a href="<?= base_url('warehouse-manager/stock-movements') ?>" class="btn btn-outline-secondary btn-sm me-2">← Back</a>
                    <span class="fw-bold"><i class="bi bi-arrow-repeat"></i> Transfer Inventory</span>
                </div>
            </div>

            <div class="p-4">
                <!-- Transfer Form -->
                <form action="<?= base_url('warehouse-manager/process-transfer') ?>" method="post" id="transferForm">
                    <!-- Source and Destination -->
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header bg-danger text-white">
                                    <i class="bi bi-box-arrow-up"></i> From (Source)
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Source Warehouse</label>
                                        <select class="form-select" name="from_warehouse_id" id="fromWarehouse" required onchange="loadSourceInventory()">
                                            <option value="">Select Source Warehouse</option>
                                            <?php foreach ($warehouses ?? [] as $wh): ?>
                                            <option value="<?= $wh['id'] ?>" <?= ($sourceWarehouse['id'] ?? '') == $wh['id'] ? 'selected' : '' ?>><?= esc($wh['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="warehouse-box" id="sourceInfo">
                                        <i class="bi bi-building display-4 text-muted"></i>
                                        <p class="text-muted mt-2">Select a source warehouse</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-center">
                            <div class="transfer-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <i class="bi bi-box-arrow-in-down"></i> To (Destination)
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Destination Warehouse</label>
                                        <select class="form-select" name="to_warehouse_id" id="toWarehouse" required>
                                            <option value="">Select Destination Warehouse</option>
                                            <?php foreach ($warehouses ?? [] as $wh): ?>
                                            <option value="<?= $wh['id'] ?>"><?= esc($wh['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="warehouse-box" id="destInfo">
                                        <i class="bi bi-building display-4 text-muted"></i>
                                        <p class="text-muted mt-2">Select a destination warehouse</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items to Transfer -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-box-seam"></i> Items to Transfer</span>
                            <button type="button" class="btn btn-sm btn-primary" onclick="addItem()"><i class="bi bi-plus-lg"></i> Add Item</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Available Qty</th>
                                            <th>Transfer Qty</th>
                                            <th>Notes</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsBody">
                                        <tr class="item-row">
                                            <td>
                                                <select class="form-select item-select" name="items[0][item_id]" onchange="updateAvailable(this)">
                                                    <option value="">Select Item</option>
                                                    <?php foreach ($items ?? [] as $item): ?>
                                                    <option value="<?= $item['id'] ?>" data-quantity="<?= $item['quantity'] ?? 0 ?>"><?= esc($item['name']) ?> (<?= $item['sku'] ?? '' ?>)</option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td class="available-qty text-center">-</td>
                                            <td>
                                                <input type="number" class="form-control transfer-qty" name="items[0][quantity]" min="1" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="items[0][notes]" placeholder="Optional">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(this)"><i class="bi bi-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Transfer Details -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-info-circle"></i> Transfer Details
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Transfer Reference</label>
                                        <input type="text" class="form-control" name="reference" placeholder="Auto-generated" readonly value="TRF-<?= date('YmdHis') ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Transfer Date</label>
                                        <input type="date" class="form-control" name="transfer_date" value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Priority</label>
                                        <select class="form-select" name="priority">
                                            <option value="normal">Normal</option>
                                            <option value="urgent">Urgent</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Reason for Transfer</label>
                                <textarea class="form-control" name="reason" rows="2" placeholder="Explain the reason for this transfer..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= base_url('warehouse-manager/stock-movements') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-repeat"></i> Process Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let itemIndex = 1;

function addItem() {
    const tbody = document.getElementById('itemsBody');
    const row = document.createElement('tr');
    row.className = 'item-row';
    row.innerHTML = `
        <td>
            <select class="form-select item-select" name="items[${itemIndex}][item_id]" onchange="updateAvailable(this)">
                <option value="">Select Item</option>
                <?php foreach ($items ?? [] as $item): ?>
                <option value="<?= $item['id'] ?>" data-quantity="<?= $item['quantity'] ?? 0 ?>"><?= esc($item['name']) ?> (<?= $item['sku'] ?? '' ?>)</option>
                <?php endforeach; ?>
            </select>
        </td>
        <td class="available-qty text-center">-</td>
        <td>
            <input type="number" class="form-control transfer-qty" name="items[${itemIndex}][quantity]" min="1" required>
        </td>
        <td>
            <input type="text" class="form-control" name="items[${itemIndex}][notes]" placeholder="Optional">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(this)"><i class="bi bi-trash"></i></button>
        </td>
    `;
    tbody.appendChild(row);
    itemIndex++;
}

function removeItem(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        btn.closest('tr').remove();
    } else {
        alert('At least one item is required');
    }
}

function updateAvailable(select) {
    const row = select.closest('tr');
    const option = select.options[select.selectedIndex];
    const qty = option.dataset.quantity || '-';
    row.querySelector('.available-qty').textContent = qty;
    
    const qtyInput = row.querySelector('.transfer-qty');
    if (qty !== '-') {
        qtyInput.max = qty;
    }
}

function loadSourceInventory() {
    const warehouseId = document.getElementById('fromWarehouse').value;
    if (warehouseId) {
        document.getElementById('sourceInfo').innerHTML = '<i class="bi bi-check-circle display-4 text-success"></i><p class="text-success mt-2">Warehouse Selected</p>';
    }
}

document.getElementById('toWarehouse').addEventListener('change', function() {
    if (this.value) {
        document.getElementById('destInfo').innerHTML = '<i class="bi bi-check-circle display-4 text-success"></i><p class="text-success mt-2">Warehouse Selected</p>';
    }
});

document.getElementById('transferForm').addEventListener('submit', function(e) {
    const from = document.getElementById('fromWarehouse').value;
    const to = document.getElementById('toWarehouse').value;
    
    if (from === to) {
        e.preventDefault();
        alert('Source and destination warehouses cannot be the same');
    }
});
</script>
</body>
</html>
