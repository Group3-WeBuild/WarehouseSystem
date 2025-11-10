<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice Management | WeBuild</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6fa;
      margin: 0;
      padding: 0;
    }

    /* Sidebar */
    .sidebar {
      background-color: #0d47a1;
      color: #fff;
      min-height: 100vh;
      padding-top: 20px;
    }

    .sidebar h5 {
      text-align: center;
      font-weight: 600;
      margin-bottom: 25px;
    }

    .sidebar a {
      display: block;
      color: #cfd8dc;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 5px;
      margin: 5px 10px;
      font-size: 15px;
      transition: 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #1565c0;
      color: #fff;
    }

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
    <div class="col-md-2 sidebar">
      <h5>WeBuild</h5>
      <a href="dashboard.php">Dashboard</a>
      <a href="manage_invoices.php" class="active">Manage Invoices</a>
      <a href="record_payments.php">Record Payments</a>
      <a href="client_management.php">Client Management</a>
      <a href="overdue_followups.php">Overdue Follow-ups</a>
      <a href="reports_analytics.php">Reports & Analytics</a>
      <a href="aging_report.php">Aging Report</a>
      <a href="settings.php">Settings</a>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 p-0">
      <!-- Top Bar -->
      <div class="topbar">
        <input type="text" class="form-control w-25" placeholder="Search">
        <div>
          <span class="me-3">Date | Time | Accounts Receivable Clerk | <strong>Username</strong></span>
          <button class="btn btn-outline-secondary btn-sm">Logout</button>
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
