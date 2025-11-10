<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Accounts Receivable Dashboard</title>
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

    /* Dashboard container */
    .dashboard-container {
      background-color: #fff;
      margin: 25px;
      padding: 25px;
      border-radius: 6px;
      box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
    }

    .stat-box {
      background-color: #fff;
      border: none;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
      text-align: center;
      padding: 20px;
      border-radius: 8px;
      transition: 0.3s;
    }

    .stat-box:hover {
      transform: translateY(-3px);
    }

    .stat-box h4 {
      color: #0d47a1;
      font-weight: 700;
    }

    .stat-box p {
      color: #212529;
      margin-bottom: 0;
    }

    /* Quick Actions */
    .quick-actions button {
      margin-right: 10px;
      margin-bottom: 10px;
    }

    /* Table */
    .recent-table th, .recent-table td {
      vertical-align: middle;
    }

    .recent-table thead {
      background-color: #f8f9fa;
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
      <a href="dashboard.php" class="active">Dashboard</a>
      <a href="manage_invoices.php">Manage Invoices</a>
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

      <!-- Dashboard Content -->
      <div class="dashboard-container">
        <h5><strong>Accounts Receivable Dashboard</strong></h5>
        <p class="text-muted mb-4">Monitor, Track & Manage Customer Payments</p>

        <!-- Stats Boxes -->
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>Total Outstanding</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>Overdue Amount</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>Pending Invoices</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>This Month Collections</p>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-4">
          <h6><strong>Quick Actions</strong></h6>
          <div class="quick-actions mt-2">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#invoiceModal">Create New Invoice</button>
            <button class="btn btn-success btn-sm">Record Payment</button>
            <button class="btn btn-warning btn-sm text-dark">Send Reminders</button>
            <button class="btn btn-secondary btn-sm">Generate Report</button>
          </div>
        </div>

        <!-- Recent Activities -->
        <div>
          <h6><strong>Recent Activities</strong></h6>
          <table class="table table-bordered table-sm mt-2 recent-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Activity</th>
                <th>Client</th>
                <th>Amount</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>—</td>
                <td>—</td>
                <td>—</td>
                <td>—</td>
                <td><span class="badge bg-success">Paid</span></td>
              </tr>
              <tr>
                <td>—</td>
                <td>—</td>
                <td>—</td>
                <td>—</td>
                <td><span class="badge bg-warning text-dark">Pending</span></td>
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
            <label class="form-label">Collections / Amount</label>
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
