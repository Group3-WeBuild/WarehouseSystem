<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Requisitions | WITMS</title>
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
                <h5 class="text-white mb-1">WITMS</h5>
                <small class="text-white-50">Procurement Officer</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= base_url('procurement/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link active" href="<?= base_url('procurement/requisitions') ?>">
                    <i class="bi bi-file-text"></i> Requisitions
                </a>
                <a class="nav-link" href="<?= base_url('procurement/purchase-orders') ?>">
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
                    <h5 class="mb-0"><i class="bi bi-file-text text-primary"></i> Purchase Requisitions</h5>
                </div>
                <div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createReqModal"><i class="bi bi-plus-lg"></i> New Requisition</button>
                    <a href="<?= base_url('print/requisitions') ?>" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer"></i> Print</a>
                </div>
            </div>

            <div class="p-4">
                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form class="row g-3" method="get">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="search" placeholder="Search requisition #..." value="<?= esc($_GET['search'] ?? '') ?>">
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="draft" <?= ($_GET['status'] ?? '') == 'draft' ? 'selected' : '' ?>>Draft</option>
                                    <option value="pending" <?= ($_GET['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="approved" <?= ($_GET['status'] ?? '') == 'approved' ? 'selected' : '' ?>>Approved</option>
                                    <option value="rejected" <?= ($_GET['status'] ?? '') == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="from_date" placeholder="From Date" value="<?= esc($_GET['from_date'] ?? '') ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="to_date" placeholder="To Date" value="<?= esc($_GET['to_date'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">🔍 Filter</button>
                                <a href="<?= base_url('procurement/requisitions') ?>" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Requisitions Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        All Requisitions
                        <span class="badge bg-primary"><?= count($requisitions ?? []) ?> total</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($requisitions)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Requisition #</th>
                                        <th>Requested By</th>
                                        <th>Department</th>
                                        <th>Date</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($requisitions as $req): ?>
                                    <tr>
                                        <td><strong><?= esc($req['requisition_number'] ?? 'N/A') ?></strong></td>
                                        <td><?= esc($req['created_by_name'] ?? 'Unknown') ?></td>
                                        <td><?= esc($req['department'] ?? 'N/A') ?></td>
                                        <td><?= date('M d, Y', strtotime($req['created_at'] ?? 'now')) ?></td>
                                        <td>
                                            <?php 
                                            $priority = $req['priority'] ?? 'normal';
                                            $pClass = $priority == 'urgent' ? 'danger' : ($priority == 'high' ? 'warning' : 'secondary');
                                            ?>
                                            <span class="badge bg-<?= $pClass ?>"><?= ucfirst($priority) ?></span>
                                        </td>
                                        <td>
                                            <?php 
                                            $status = $req['status'] ?? 'draft';
                                            $sClass = ['approved' => 'success', 'pending' => 'warning', 'rejected' => 'danger', 'draft' => 'secondary'][$status] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $sClass ?>"><?= ucfirst($status) ?></span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('procurement/requisitions/' . ($req['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-primary">View</a>
                                            <?php if (($req['status'] ?? '') == 'pending'): ?>
                                            <button class="btn btn-sm btn-success" onclick="approveReq(<?= $req['id'] ?? 0 ?>)">Approve</button>
                                            <?php endif; ?>
                                            <?php if (($req['status'] ?? '') == 'approved'): ?>
                                            <a href="<?= base_url('procurement/create-po-from-req/' . ($req['id'] ?? 0)) ?>" class="btn btn-sm btn-info">Create PO</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5">
                            <div style="font-size: 4rem;">📋</div>
                            <h5 class="text-muted">No requisitions found</h5>
                            <p class="text-muted">Start by creating a new purchase requisition</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createReqModal">+ Create Requisition</button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Requisition Modal -->
<div class="modal fade" id="createReqModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Requisition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('procurement/requisitions/create') ?>" method="post">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <input type="text" class="form-control" name="department" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Priority</label>
                            <select class="form-select" name="priority">
                                <option value="normal">Normal</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Required Date</label>
                            <input type="date" class="form-control" name="required_date">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Requisition</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function approveReq(id) {
    if(confirm('Approve this requisition?')) {
        window.location.href = '<?= base_url('procurement/approve-requisition/') ?>' + id;
    }
}
</script>
</body>
</html>