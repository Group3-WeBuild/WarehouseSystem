<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reports & Analytics | WeBuild</title>
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

    /* Page container */
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

    .btn-sm {
      font-size: 0.85rem;
    }

    .text-muted {
      color: #6c757d !important;
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
        <a class="nav-link" href="<?= base_url('accounts-receivable/client-management') ?>"><i class="bi bi-people"></i> Client Management</a>
        <a class="nav-link" href="<?= base_url('accounts-receivable/overdue-followups') ?>"><i class="bi bi-bell"></i> Overdue Follow-ups</a>
        <a class="nav-link active" href="<?= base_url('accounts-receivable/reports-analytics') ?>"><i class="bi bi-bar-chart"></i> Reports & Analytics</a>
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

      <!-- Page Container -->
      <div class="page-container">
        <h5><strong>Reports & Analytics</strong></h5>
        <p class="text-muted mb-4">Track Performance & Generate Financial Insights</p>

        <!-- Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0"><strong>Reports Overview</strong></h6>
          <div>
            <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#generateReportModal">Generate Report</button>
            <button class="btn btn-secondary btn-sm">Schedule Report</button>
          </div>
        </div>

        <!-- Recent Reports Table -->
        <div>
          <h6><strong>Recent Reports</strong></h6>
          <table class="table table-bordered table-sm mt-2">
            <thead class="table-light">
              <tr>
                <th>Report Name</th>
                <th>Generate Date</th>
                <th>Period</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Monthly AR Summary</td>
                <td></td>
                <td></td>
                <td><span class="badge bg-secondary">Generated</span></td>
                <td>
                  <button class="btn btn-outline-info btn-sm">Download</button>
                  <button class="btn btn-outline-primary btn-sm">Email</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Generate Report Modal -->
<div class="modal fade" id="generateReportModal" tabindex="-1" aria-labelledby="generateReportModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="generateReportModalLabel">Generate Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Report Type</label>
            <select class="form-select">
              <option selected>Select Report Type</option>
              <option>Accounts Receivable Summary</option>
              <option>Overdue Follow-ups</option>
              <option>Invoice Aging Report</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Start Date</label>
            <input type="date" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">End Date</label>
            <input type="date" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Format</label>
            <select class="form-select">
              <option selected>Excel</option>
              <option>PDF</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm">Generate Report</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
