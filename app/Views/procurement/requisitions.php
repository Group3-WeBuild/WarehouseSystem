<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Requisitions | WeBuild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f6fa; }
        .sidebar { background-color: #1565c0; color: #fff; min-height: 100vh; padding-top: 20px; }
        .sidebar h5 { text-align: center; font-weight: 600; margin-bottom: 25px; }
        .sidebar a { display: block; color: #bbdefb; text-decoration: none; padding: 12px 20px; margin: 5px 10px; border-radius: 5px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: #1976d2; color: #fff; }
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
            <h5>📦 Procurement</h5>
            <a href="<?= base_url('procurement/dashboard') ?>">📊 Dashboard</a>
            <a href="<?= base_url('procurement/requisitions') ?>" class="active">📝 Requisitions</a>
            <a href="<?= base_url('procurement/purchase-orders') ?>">📋 Purchase Orders</a>
            <a href="<?= base_url('procurement/vendors') ?>">🏢 Vendors</a>
            <a href="<?= base_url('procurement/reports') ?>">📈 Reports</a>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <a href="<?= base_url('logout') ?>">🚪 Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-0">
            <div class="topbar">
                <div>
                    <a href="<?= base_url('procurement/dashboard') ?>" class="btn btn-outline-secondary btn-sm me-2">← Back</a>
                    <span class="fw-bold">Purchase Requisitions</span>
                </div>
                <div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createReqModal">+ New Requisition</button>
                    <a href="<?= base_url('print/requisitions') ?>" target="_blank" class="btn btn-outline-secondary btn-sm">🖨️ Print</a>
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