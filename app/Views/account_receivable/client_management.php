<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Client Management | WeBuild</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6fa;
      margin: 0;
      padding: 0;
    }

    /* Sidebar */
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

    /* Topbar */
    .topbar {
      background-color: #e9ecef;
      border-bottom: 1px solid #ccc;
      padding: 10px 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* Page Container */
    .page-container {
      background-color: #fff;
      margin: 25px;
      padding: 25px;
      border-radius: 6px;
      box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
    }

    .table th, .table td {
      vertical-align: middle;
    }

    .badge {
      font-size: 0.8rem;
    }
  </style>
</head>

<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 px-0 sidebar">
      <div class="text-center py-4">
        <h5 class="text-white mb-1">WITMS</h5>
        <small class="text-white-50">Accounts Receivable</small>
      </div>
      <nav class="nav flex-column">
        <a class="nav-link" href="<?= base_url('accounts-receivable/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a class="nav-link" href="<?= base_url('accounts-receivable/manage-invoices') ?>"><i class="bi bi-file-earmark-text"></i> Manage Invoices</a>
        <a class="nav-link" href="<?= base_url('accounts-receivable/record-payments') ?>"><i class="bi bi-cash-coin"></i> Record Payments</a>
        <a class="nav-link active" href="<?= base_url('accounts-receivable/client-management') ?>"><i class="bi bi-people"></i> Client Management</a>
        <a class="nav-link" href="<?= base_url('accounts-receivable/overdue-followups') ?>"><i class="bi bi-bell"></i> Overdue Follow-ups</a>
        <a class="nav-link" href="<?= base_url('accounts-receivable/reports-analytics') ?>"><i class="bi bi-bar-chart"></i> Reports & Analytics</a>
        <a class="nav-link" href="<?= base_url('accounts-receivable/aging-report') ?>"><i class="bi bi-calendar3"></i> Aging Report</a>
        <a class="nav-link" href="<?= base_url('accounts-receivable/settings') ?>"><i class="bi bi-gear"></i> Settings</a>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 p-0">
      <!-- Top Bar -->
      <div class="topbar">
        <input type="text" class="form-control w-25" placeholder="Search">
        <div>
          <span class="me-3"><?= date('F d, Y | h:i A') ?> | <?= esc($user['role']) ?> | <strong><?= esc($user['username']) ?></strong></span>
          <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary btn-sm">Logout</a>
        </div>
      </div>

      <!-- Page Content -->
      <div class="page-container">
        <h5><strong>Client Management</strong></h5>
        <p class="text-muted mb-4">Customer Account Center</p>

        <!-- Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0"><strong>Manage Client</strong></h6>
          <div>
            <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#addClientModal">+ Add Client</button>
            <button class="btn btn-secondary btn-sm">Export</button>
          </div>
        </div>

        <!-- Table -->
        <div>
          <h6><strong>Client List</strong></h6>
          <table class="table table-bordered table-sm mt-2">
            <thead class="table-light">
              <tr>
                <th>Client Name</th>
                <th>Contact Person</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Balance</th>
                <th>Credit Limit</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                  <button class="btn btn-outline-info btn-sm">View</button>
                  <button class="btn btn-outline-warning btn-sm">Edit</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Client Modal -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addClientModalLabel">Add New Client</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Company Name</label>
            <input type="text" class="form-control" placeholder="Enter Company Name">
          </div>
          <div class="mb-3">
            <label class="form-label">Contact Person</label>
            <input type="text" class="form-control" placeholder="Enter Contact Person">
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" placeholder="Enter Email">
          </div>
          <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="tel" class="form-control" placeholder="Enter Phone Number">
          </div>
          <div class="mb-3">
            <label class="form-label">Credit Limit</label>
            <input type="number" class="form-control" placeholder="Enter Credit Limit">
          </div>
          <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea class="form-control" rows="2" placeholder="Enter Address"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm">Add Client</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
