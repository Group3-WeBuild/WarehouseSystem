<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Tracking | WITMS</title>
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
        .delivery-timeline { position: relative; padding-left: 30px; }
        .delivery-timeline::before { content: ''; position: absolute; left: 10px; top: 0; bottom: 0; width: 2px; background: #ddd; }
        .timeline-item { position: relative; padding-bottom: 20px; }
        .timeline-item::before { content: ''; position: absolute; left: -24px; width: 12px; height: 12px; border-radius: 50%; background: #2c3e50; border: 2px solid #fff; }
        .timeline-item.completed::before { background: #28a745; }
        .timeline-item.current::before { background: #ffc107; animation: pulse 1s infinite; }
        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.3); } }
        .table th { background-color: #f8f9fa; font-weight: 600; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 px-0 sidebar">
            <div class="text-center py-4">
                <h5 class="text-white mb-1">WITMS</h5>
                <small class="text-white-50">Procurement Officer</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= base_url('procurement/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link" href="<?= base_url('procurement/requisitions') ?>">
                    <i class="bi bi-file-text"></i> Requisitions
                </a>
                <a class="nav-link" href="<?= base_url('procurement/purchase-orders') ?>">
                    <i class="bi bi-clipboard-check"></i> Purchase Orders
                </a>
                <a class="nav-link active" href="<?= base_url('procurement/delivery-tracking') ?>">
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
                    <h5 class="mb-0"><i class="bi bi-truck text-primary"></i> Delivery Tracking</h5>
                </div>
                <div>
                    <a href="<?= base_url('print/deliveries') ?>" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer"></i> Print</a>
                </div>
            </div>

            <div class="p-4">
                <!-- Stats Row -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-info text-white text-center py-3">
                            <h3 class="mb-0"><?= count($inTransit ?? []) ?></h3>
                            <small>In Transit</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark text-center py-3">
                            <h3 class="mb-0"><?= count($expectedToday ?? []) ?></h3>
                            <small>Expected Today</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white text-center py-3">
                            <h3 class="mb-0"><?= count($overdue ?? []) ?></h3>
                            <small>Overdue</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white text-center py-3">
                            <h3 class="mb-0"><?= count($receivedToday ?? []) ?></h3>
                            <small>Received Today</small>
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
                                    <option value="pending" <?= ($_GET['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending Shipment</option>
                                    <option value="shipped" <?= ($_GET['status'] ?? '') == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                    <option value="in_transit" <?= ($_GET['status'] ?? '') == 'in_transit' ? 'selected' : '' ?>>In Transit</option>
                                    <option value="delivered" <?= ($_GET['status'] ?? '') == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="expected_date" placeholder="Expected Date" value="<?= esc($_GET['expected_date'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">🔍 Filter</button>
                                <a href="<?= base_url('procurement/delivery-tracking') ?>" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Deliveries Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Active Deliveries
                        <span class="badge bg-primary"><?= count($deliveries ?? []) ?> records</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($deliveries)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>PO Number</th>
                                        <th>Vendor</th>
                                        <th>Ship Date</th>
                                        <th>Expected Delivery</th>
                                        <th>Tracking #</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($deliveries as $d): ?>
                                    <?php 
                                    $isOverdue = isset($d['expected_delivery_date']) && strtotime($d['expected_delivery_date']) < time() && ($d['delivery_status'] ?? '') != 'delivered';
                                    ?>
                                    <tr class="<?= $isOverdue ? 'table-danger' : '' ?>">
                                        <td><strong><?= esc($d['po_number'] ?? 'N/A') ?></strong></td>
                                        <td><?= esc($d['vendor_name'] ?? 'Unknown') ?></td>
                                        <td><?= isset($d['ship_date']) ? date('M d, Y', strtotime($d['ship_date'])) : 'N/A' ?></td>
                                        <td>
                                            <?php if (isset($d['expected_delivery_date'])): ?>
                                            <span class="<?= $isOverdue ? 'text-danger fw-bold' : '' ?>">
                                                <?= date('M d, Y', strtotime($d['expected_delivery_date'])) ?>
                                                <?= $isOverdue ? ' (Overdue)' : '' ?>
                                            </span>
                                            <?php else: ?>
                                            N/A
                                            <?php endif; ?>
                                        </td>
                                        <td><code><?= esc($d['tracking_number'] ?? 'N/A') ?></code></td>
                                        <td>
                                            <?php 
                                            $status = $d['delivery_status'] ?? 'pending';
                                            $sClass = ['delivered' => 'success', 'in_transit' => 'info', 'shipped' => 'primary', 'pending' => 'warning'][$status] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $sClass ?>"><?= ucfirst(str_replace('_', ' ', $status)) ?></span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#trackingModal" onclick="showTracking(<?= $d['id'] ?? 0 ?>)">
                                                📍 Track
                                            </button>
                                            <?php if (($d['delivery_status'] ?? '') == 'in_transit'): ?>
                                            <a href="<?= base_url('procurement/receive/' . ($d['id'] ?? 0)) ?>" class="btn btn-sm btn-success">Receive</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5">
                            <div style="font-size: 4rem;">🚚</div>
                            <h5 class="text-muted">No active deliveries</h5>
                            <p class="text-muted">Deliveries will appear here when purchase orders are shipped</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Expected Today -->
                <?php if (!empty($expectedToday)): ?>
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        📅 Expected Today
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($expectedToday as $d): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6><?= esc($d['po_number'] ?? 'N/A') ?></h6>
                                        <p class="mb-1"><strong>Vendor:</strong> <?= esc($d['vendor_name'] ?? 'Unknown') ?></p>
                                        <p class="mb-1"><strong>Tracking:</strong> <?= esc($d['tracking_number'] ?? 'N/A') ?></p>
                                        <a href="<?= base_url('procurement/receive/' . ($d['id'] ?? 0)) ?>" class="btn btn-sm btn-success w-100">Mark as Received</a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Tracking Modal -->
<div class="modal fade" id="trackingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📍 Delivery Tracking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="delivery-timeline">
                    <div class="timeline-item completed">
                        <strong>Order Placed</strong>
                        <p class="text-muted small mb-0">Order confirmed with vendor</p>
                    </div>
                    <div class="timeline-item completed">
                        <strong>Processing</strong>
                        <p class="text-muted small mb-0">Vendor preparing shipment</p>
                    </div>
                    <div class="timeline-item current">
                        <strong>In Transit</strong>
                        <p class="text-muted small mb-0">Package on the way</p>
                    </div>
                    <div class="timeline-item">
                        <strong>Out for Delivery</strong>
                        <p class="text-muted small mb-0">Arriving soon</p>
                    </div>
                    <div class="timeline-item">
                        <strong>Delivered</strong>
                        <p class="text-muted small mb-0">Package received</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showTracking(id) {
    // In a real app, this would fetch tracking data via AJAX
    console.log('Show tracking for delivery:', id);
}
</script>
</body>
</html>
