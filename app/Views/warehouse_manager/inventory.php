<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Inventory Management') ?> | WeBuild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-radius: 10px;
        }
        .stat-card {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            color: #fff;
            margin-bottom: 20px;
        }
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
                <small class="text-white-50">Warehouse Manager</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= base_url('warehouse-manager/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link active" href="<?= base_url('warehouse-manager/inventory') ?>">
                    <i class="bi bi-box-seam"></i> Inventory
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-manager/barcode-scanner') ?>">
                    <i class="bi bi-qr-code-scan"></i> Barcode Scanner
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
                <a class="nav-link" href="<?= base_url('warehouse-manager/reports') ?>">
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
                    <h5 class="mb-0"><i class="bi bi-box-seam text-primary"></i> Inventory Management</h5>
                </div>
                <div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        <i class="bi bi-plus-lg"></i> Add New Item
                    </button>
                    <a href="<?= base_url('print/inventory') ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-printer"></i> Print
                    </a>
                </div>
            </div>

            <div class="p-4">
                <!-- Stats Row -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card bg-primary">
                            <h3><?= number_format(count($inventory ?? [])) ?></h3>
                            <small>Total Items</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-success">
                            <h3><?= number_format(count(array_filter($inventory ?? [], fn($i) => ($i['quantity'] ?? 0) > ($i['reorder_level'] ?? 0)))) ?></h3>
                            <small>In Stock</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-warning">
                            <h3><?= number_format(count(array_filter($inventory ?? [], fn($i) => ($i['quantity'] ?? 0) > 0 && ($i['quantity'] ?? 0) <= ($i['reorder_level'] ?? 0)))) ?></h3>
                            <small>Low Stock</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-danger">
                            <h3><?= number_format(count(array_filter($inventory ?? [], fn($i) => ($i['quantity'] ?? 0) == 0))) ?></h3>
                            <small>Out of Stock</small>
                        </div>
                    </div>
                </div>

                <!-- Filters Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search inventory...">
                            </div>
                            <div class="col-md-2">
                                <select id="categoryFilter" class="form-select">
                                    <option value="">All Categories</option>
                                    <option value="Cement & Concrete">Cement & Concrete</option>
                                    <option value="Steel & Metal">Steel & Metal</option>
                                    <option value="Wood & Plywood">Wood & Plywood</option>
                                    <option value="Paint & Coatings">Paint & Coatings</option>
                                    <option value="Hardware & Fasteners">Hardware & Fasteners</option>
                                    <option value="Plumbing Supplies">Plumbing Supplies</option>
                                    <option value="Electrical Supplies">Electrical Supplies</option>
                                    <option value="Tools & Equipment">Tools & Equipment</option>
                                    <option value="Safety Equipment">Safety Equipment</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select id="statusFilter" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="in-stock">In Stock</option>
                                    <option value="low-stock">Low Stock</option>
                                    <option value="out-of-stock">Out of Stock</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary" onclick="applyFilters()"><i class="bi bi-funnel"></i> Filter</button>
                                <button class="btn btn-outline-secondary" onclick="resetFilters()"><i class="bi bi-x-circle"></i> Reset</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="inventoryTable" class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>SKU</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Reorder Level</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($inventory)): ?>
                                        <?php foreach ($inventory as $item): ?>
                                            <tr>
                                                <td><code><?= esc($item['sku'] ?? '') ?></code></td>
                                                <td><strong><?= esc($item['product_name'] ?? '') ?></strong></td>
                                                <td><?= esc($item['category'] ?? '') ?></td>
                                                <td>
                                                    <span class="badge bg-<?= ($item['quantity'] ?? 0) <= ($item['reorder_level'] ?? 0) ? 'danger' : 'success' ?>">
                                                        <?= number_format($item['quantity'] ?? 0) ?>
                                                    </span>
                                                </td>
                                                <td>₱<?= number_format($item['unit_price'] ?? 0, 2) ?></td>
                                                <td><?= number_format($item['reorder_level'] ?? 0) ?></td>
                                                <td>
                                                    <?php if (($item['quantity'] ?? 0) == 0): ?>
                                                        <span class="badge bg-danger">Out of Stock</span>
                                                    <?php elseif (($item['quantity'] ?? 0) <= ($item['reorder_level'] ?? 0)): ?>
                                                        <span class="badge bg-warning text-dark">Low Stock</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">In Stock</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-info" onclick="viewItem(<?= $item['id'] ?? 0 ?>)" title="View">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button class="btn btn-outline-warning" onclick="editItem(<?= $item['id'] ?? 0 ?>)" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button class="btn btn-outline-success" onclick="adjustStock(<?= $item['id'] ?? 0 ?>)" title="Adjust Stock">
                                                            <i class="bi bi-arrow-left-right"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger" onclick="deleteItem(<?= $item['id'] ?? 0 ?>)" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <i class="bi bi-inbox display-4 text-muted"></i>
                                                <p class="text-muted mt-2">No inventory items found</p>
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                                    <i class="bi bi-plus-lg"></i> Add First Item
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Add New Inventory Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('warehouse-manager/inventory/add') ?>" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="product_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="">Select Category</option>
                                <option value="Cement & Concrete">Cement & Concrete</option>
                                <option value="Steel & Metal">Steel & Metal</option>
                                <option value="Wood & Plywood">Wood & Plywood</option>
                                <option value="Paint & Coatings">Paint & Coatings</option>
                                <option value="Hardware & Fasteners">Hardware & Fasteners</option>
                                <option value="Plumbing Supplies">Plumbing Supplies</option>
                                <option value="Electrical Supplies">Electrical Supplies</option>
                                <option value="Tools & Equipment">Tools & Equipment</option>
                                <option value="Safety Equipment">Safety Equipment</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit Price (₱)</label>
                            <input type="number" name="unit_price" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Initial Quantity</label>
                            <input type="number" name="quantity" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Reorder Level</label>
                            <input type="number" name="reorder_level" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Unit of Measure</label>
                            <select name="unit" class="form-select">
                                <option value="pcs">Pieces</option>
                                <option value="pieces">Pieces</option>
                                <option value="bags">Bags</option>
                                <option value="kg">Kilograms</option>
                                <option value="m">Meters</option>
                                <option value="box">Boxes</option>
                                <option value="sheets">Sheets</option>
                                <option value="cans">Cans</option>
                                <option value="rolls">Rolls</option>
                                <option value="sets">Sets</option>
                                <option value="pairs">Pairs</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Item Modal -->
<div class="modal fade" id="viewItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-eye"></i> Item Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewItemContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Adjust Stock Modal -->
<div class="modal fade" id="adjustStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-arrow-left-right"></i> Adjust Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('warehouse-manager/inventory/adjust-stock') ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="item_id" id="adjustItemId">
                    <div class="mb-3">
                        <label class="form-label">Adjustment Type</label>
                        <select name="type" class="form-select" required>
                            <option value="add">Add Stock (+)</option>
                            <option value="remove">Remove Stock (-)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <textarea name="reason" class="form-control" rows="2" placeholder="Reason for adjustment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Adjust</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil"></i> Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editItemForm">
                <div class="modal-body">
                    <input type="hidden" name="item_id" id="editItemId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">SKU <small class="text-muted">(Read Only)</small></label>
                            <input type="text" id="editSku" class="form-control" readonly disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Product Name *</label>
                            <input type="text" name="product_name" id="editProductName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category *</label>
                            <select name="category" id="editCategory" class="form-select" required>
                                <option value="">Select Category</option>
                                <option value="Cement & Concrete">Cement & Concrete</option>
                                <option value="Steel & Metal">Steel & Metal</option>
                                <option value="Wood & Plywood">Wood & Plywood</option>
                                <option value="Paint & Coatings">Paint & Coatings</option>
                                <option value="Hardware & Fasteners">Hardware & Fasteners</option>
                                <option value="Plumbing Supplies">Plumbing Supplies</option>
                                <option value="Electrical Supplies">Electrical Supplies</option>
                                <option value="Tools & Equipment">Tools & Equipment</option>
                                <option value="Safety Equipment">Safety Equipment</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit of Measure *</label>
                            <select name="unit" id="editUnit" class="form-select" required>
                                <option value="pcs">Pieces</option>
                                <option value="kg">Kilograms</option>
                                <option value="m">Meters</option>
                                <option value="box">Boxes</option>
                                <option value="bag">Bags</option>
                                <option value="gallon">Gallons</option>
                                <option value="liter">Liters</option>
                                <option value="roll">Rolls</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Unit Price *</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="unit_price" id="editUnitPrice" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Reorder Level *</label>
                            <input type="number" name="reorder_level" id="editReorderLevel" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Location *</label>
                            <input type="text" name="location" id="editLocation" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="editDescription" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div id="editItemError" class="alert alert-danger mt-3 d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#inventoryTable').DataTable({
            order: [[1, 'asc']],
            pageLength: 25,
            language: {
                search: "",
                searchPlaceholder: "Search..."
            }
        });
    });

    function viewItem(id) {
        $('#viewItemContent').html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
        $('#viewItemModal').modal('show');
        
        // Fetch item details via AJAX
        $.get('<?= base_url('warehouse-manager/view-item/') ?>' + id, function(response) {
            if (response.success && response.item) {
                var item = response.item;
                var html = '<table class="table table-borderless">';
                html += '<tr><th>SKU:</th><td>' + (item.sku || 'N/A') + '</td></tr>';
                html += '<tr><th>Product Name:</th><td>' + (item.product_name || 'N/A') + '</td></tr>';
                html += '<tr><th>Category:</th><td>' + (item.category || 'N/A') + '</td></tr>';
                html += '<tr><th>Quantity:</th><td>' + (item.quantity || '0') + ' ' + (item.unit || '') + '</td></tr>';
                html += '<tr><th>Unit Price:</th><td>₱' + parseFloat(item.unit_price || 0).toFixed(2) + '</td></tr>';
                html += '<tr><th>Reorder Level:</th><td>' + (item.reorder_level || 'N/A') + '</td></tr>';
                html += '<tr><th>Location:</th><td>' + (item.location || 'N/A') + '</td></tr>';
                html += '<tr><th>Description:</th><td>' + (item.description || 'No description') + '</td></tr>';
                html += '</table>';
                $('#viewItemContent').html(html);
            } else {
                $('#viewItemContent').html('<div class="alert alert-danger">Failed to load item details.</div>');
            }
        }).fail(function() {
            $('#viewItemContent').html('<div class="alert alert-danger">Error loading item details.</div>');
        });
    }

    function editItem(id) {
        // Show loading state and modal
        $('#editItemError').addClass('d-none');
        $('#editItemModal').modal('show');
        
        // Fetch item details via AJAX
        $.get('<?= base_url('warehouse-manager/view-item/') ?>' + id, function(response) {
            if (response.success && response.item) {
                var item = response.item;
                $('#editItemId').val(item.id);
                $('#editSku').val(item.sku || '');
                $('#editProductName').val(item.product_name || '');
                $('#editCategory').val(item.category || '');
                $('#editUnit').val(item.unit || 'pcs');
                $('#editUnitPrice').val(item.unit_price || 0);
                $('#editReorderLevel').val(item.reorder_level || 0);
                $('#editLocation').val(item.location || '');
                $('#editDescription').val(item.description || '');
            } else {
                $('#editItemError').text('Failed to load item details.').removeClass('d-none');
            }
        }).fail(function() {
            $('#editItemError').text('Error loading item details.').removeClass('d-none');
        });
    }

    // Handle edit form submission
    $('#editItemForm').on('submit', function(e) {
        e.preventDefault();
        
        var itemId = $('#editItemId').val();
        var formData = {
            product_name: $('#editProductName').val(),
            category: $('#editCategory').val(),
            unit: $('#editUnit').val(),
            unit_price: $('#editUnitPrice').val(),
            reorder_level: $('#editReorderLevel').val(),
            location: $('#editLocation').val(),
            description: $('#editDescription').val()
        };
        
        $.post('<?= base_url('warehouse-manager/update-item/') ?>' + itemId, formData, function(response) {
            if (response.success) {
                $('#editItemModal').modal('hide');
                // Show success message and reload
                alert('Item updated successfully!');
                location.reload();
            } else {
                $('#editItemError').text(response.message || 'Failed to update item.').removeClass('d-none');
            }
        }).fail(function() {
            $('#editItemError').text('Error updating item.').removeClass('d-none');
        });
    });

    function adjustStock(id) {
        $('#adjustItemId').val(id);
        $('#adjustStockModal').modal('show');
    }

    function deleteItem(id) {
        if (confirm('Are you sure you want to delete this item?')) {
            window.location.href = '<?= base_url('warehouse-manager/inventory/delete/') ?>' + id;
        }
    }

    function applyFilters() {
        var table = $('#inventoryTable').DataTable();
        
        // Apply search filter
        table.search($('#searchInput').val());
        
        // Apply category filter (column 2)
        var categoryVal = $('#categoryFilter').val();
        table.column(2).search(categoryVal);
        
        // Apply status filter (column 6)
        var statusVal = $('#statusFilter').val();
        table.column(6).search(statusVal);
        
        table.draw();
    }

    function resetFilters() {
        $('#searchInput').val('');
        $('#categoryFilter').val('');
        $('#statusFilter').val('');
        var table = $('#inventoryTable').DataTable();
        table.search('');
        table.column(2).search('');
        table.column(6).search('');
        table.draw();
    }
</script>
</body>
</html>
