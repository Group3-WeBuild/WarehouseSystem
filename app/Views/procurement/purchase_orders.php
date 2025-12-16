<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Orders | WeBuild</title>
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
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border-radius: 10px; }
        .card-header { background-color: #f8f9fa; font-weight: 600; }
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
                <small class="text-white-50">Procurement Officer</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= base_url('procurement/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link" href="<?= base_url('procurement/requisitions') ?>">
                    <i class="bi bi-file-text"></i> Requisitions
                </a>
                <a class="nav-link active" href="<?= base_url('procurement/purchase-orders') ?>">
                    <i class="bi bi-clipboard-check"></i> Purchase Orders
                </a>
                <a class="nav-link" href="<?= base_url('procurement/delivery-tracking') ?>">
                    <i class="bi bi-truck"></i> Delivery Tracking
                </a>
                <a class="nav-link" href="<?= base_url('procurement/vendors') ?>">
                    <i class="bi bi-building"></i> Vendors
                </a>
                <a class="nav-link" href="<?= base_url('procurement/reports') ?>">
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
                    <h5 class="mb-0"><i class="bi bi-clipboard-check text-primary"></i> Purchase Orders</h5>
                </div>
                <div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createPOModal"><i class="bi bi-plus-lg"></i> New Purchase Order</button>
                    <a href="<?= base_url('print/purchase-orders') ?>" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer"></i> Print</a>
                </div>
            </div>

            <div class="p-4">
                <!-- Stats Row -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center py-3">
                                <h4 class="mb-0"><?= count($purchaseOrders ?? []) ?></h4>
                                <small>Total POs</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark">
                            <div class="card-body text-center py-3">
                                <h4 class="mb-0"><?= count(array_filter($purchaseOrders ?? [], fn($p) => ($p['status'] ?? '') == 'Pending')) ?></h4>
                                <small>Pending</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center py-3">
                                <h4 class="mb-0"><?= count(array_filter($purchaseOrders ?? [], fn($p) => ($p['status'] ?? '') == 'Sent')) ?></h4>
                                <small>Sent to Vendor</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center py-3">
                                <h4 class="mb-0"><?= count(array_filter($purchaseOrders ?? [], fn($p) => ($p['status'] ?? '') == 'Received')) ?></h4>
                                <small>Received</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form class="row g-3" method="get">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="search" placeholder="Search PO # or vendor..." value="<?= esc($_GET['search'] ?? '') ?>">
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="Draft" <?= ($_GET['status'] ?? '') == 'Draft' ? 'selected' : '' ?>>Draft</option>
                                    <option value="Pending" <?= ($_GET['status'] ?? '') == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="Sent" <?= ($_GET['status'] ?? '') == 'Sent' ? 'selected' : '' ?>>Sent</option>
                                    <option value="Received" <?= ($_GET['status'] ?? '') == 'Received' ? 'selected' : '' ?>>Received</option>
                                    <option value="Cancelled" <?= ($_GET['status'] ?? '') == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
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
                                <a href="<?= base_url('procurement/purchase-orders') ?>" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- PO Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        All Purchase Orders
                        <span class="badge bg-primary"><?= count($purchaseOrders ?? []) ?> records</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($purchaseOrders)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>PO Number</th>
                                        <th>Vendor</th>
                                        <th>Order Date</th>
                                        <th>Expected Delivery</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($purchaseOrders as $po): ?>
                                    <tr>
                                        <td><strong><?= esc($po['po_number'] ?? 'N/A') ?></strong></td>
                                        <td><?= esc($po['vendor_name'] ?? 'Unknown') ?></td>
                                        <td><?= date('M d, Y', strtotime($po['order_date'] ?? $po['created_at'] ?? 'now')) ?></td>
                                        <td>
                                            <?php 
                                            $expected = $po['expected_delivery_date'] ?? null;
                                            $isOverdue = $expected && strtotime($expected) < time() && ($po['status'] ?? '') != 'received';
                                            ?>
                                            <span class="<?= $isOverdue ? 'text-danger fw-bold' : '' ?>">
                                                <?= $expected ? date('M d, Y', strtotime($expected)) : 'N/A' ?>
                                                <?= $isOverdue ? ' (Overdue)' : '' ?>
                                            </span>
                                        </td>
                                        <td>₱<?= number_format($po['total_amount'] ?? 0, 2) ?></td>
                                        <td>
                                            <?php 
                                            $status = $po['status'] ?? 'Draft';
                                            $sClass = ['Received' => 'success', 'Sent' => 'info', 'Pending' => 'warning', 'Draft' => 'secondary', 'Cancelled' => 'danger'][$status] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $sClass ?>"><?= $status ?></span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('procurement/purchase-orders/' . ($po['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-primary">View</a>
                                            <?php if (($po['status'] ?? '') == 'Sent'): ?>
                                            <a href="<?= base_url('procurement/receive/' . ($po['id'] ?? 0)) ?>" class="btn btn-sm btn-success">Receive</a>
                                            <?php endif; ?>
                                            <a href="<?= base_url('print/po/' . ($po['id'] ?? 0)) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">🖨️</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5">
                            <div style="font-size: 4rem;">📦</div>
                            <h5 class="text-muted">No purchase orders found</h5>
                            <p class="text-muted">Create a new purchase order to get started</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPOModal">+ Create Purchase Order</button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create PO Modal -->
<div class="modal fade" id="createPOModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('procurement/purchase-orders/create') ?>" method="post">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Vendor</label>
                            <select class="form-select" name="vendor_id" required>
                                <option value="">Select Vendor</option>
                                <?php foreach ($vendors ?? [] as $v): ?>
                                <option value="<?= $v['id'] ?>"><?= esc($v['vendor_name'] ?? $v['name'] ?? 'Unknown') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Expected Delivery Date</label>
                            <input type="date" class="form-control" name="expected_delivery_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Terms</label>
                            <select class="form-select" name="payment_terms">
                                <option value="net30">Net 30</option>
                                <option value="net60">Net 60</option>
                                <option value="cod">Cash on Delivery</option>
                                <option value="prepaid">Prepaid</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Shipping Address</label>
                            <input type="text" class="form-control" name="shipping_address" placeholder="Warehouse address">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create PO</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>