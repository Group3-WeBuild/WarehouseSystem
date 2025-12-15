<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice Management | WeBuild</title>
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

    /* Buttons and Filters */
    .filter-section button {
      margin-right: 10px;
    }

    .filter-inputs input,
    .filter-inputs select {
      margin-right: 10px;
      margin-bottom: 10px;
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
        <a class="nav-link active" href="<?= base_url('accounts-receivable/manage-invoices') ?>"><i class="bi bi-file-earmark-text"></i> Manage Invoices</a>
        <a class="nav-link" href="<?= base_url('accounts-receivable/record-payments') ?>"><i class="bi bi-cash-coin"></i> Record Payments</a>
        <a class="nav-link" href="<?= base_url('accounts-receivable/client-management') ?>"><i class="bi bi-people"></i> Client Management</a>
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
        <h5><strong>Invoice Management</strong></h5>
        <p class="text-muted mb-4">Create, Send & Track Customer Invoices</p>

        <!-- Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0"><strong>Invoice Awaiting Review</strong></h6>
          <div>
            <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#invoiceModal">+ Add New Invoice</button>
            <button class="btn btn-secondary btn-sm">Export</button>
          </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section mb-4">
          <h6><strong>Client Management</strong></h6>
          <div class="filter-inputs mt-2 d-flex flex-wrap align-items-center">
            <input type="text" class="form-control w-auto" placeholder="Search by vendor, invoice#, or PO#">
            <select class="form-select w-auto">
              <option>All Status</option>
              <option>Paid</option>
              <option>Pending</option>
              <option>Overdue</option>
            </select>
            <select class="form-select w-auto">
              <option>All Clients</option>
              <option>ABC Corporation</option>
              <option>XYZ Company</option>
            </select>
            <button class="btn btn-outline-primary btn-sm">Apply Filter</button>
          </div>
        </div>

        <!-- Table -->
        <div>
          <h6><strong>Overdue Follow-ups</strong></h6>
          <table class="table table-bordered table-sm mt-2">
            <thead class="table-light">
              <tr>
                <th>Invoice#</th>
                <th>Client</th>
                <th>Issue Date</th>
                <th>Due Date</th>
                <th>Amount</th>
                <th>Status</th>
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
                <td><span class="badge bg-success">Paid</span></td>
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

<!-- Create Invoice Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="invoiceModalLabel">Create New Invoice</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Client</label>
            <select class="form-select">
              <option selected>Select Client</option>
              <option>Client A</option>
              <option>Client B</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Issue Date</label>
            <input type="date" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Amount</label>
            <input type="number" class="form-control" placeholder="Enter Amount">
          </div>
          <div class="mb-3">
            <label class="form-label">Due Date</label>
            <input type="date" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" rows="3" placeholder="Invoice description ..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm">Save Invoice</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
