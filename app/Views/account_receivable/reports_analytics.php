<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reports & Analytics | WeBuild</title>
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
    <div class="col-md-2 sidebar">
      <h5>WeBuild</h5>
      <a href="dashboard.php">Dashboard</a>
      <a href="manage_invoices.php">Manage Invoices</a>
      <a href="record_payments.php">Record Payments</a>
      <a href="client_management.php">Client Management</a>
      <a href="overdue_followups.php">Overdue Follow-ups</a>
      <a href="reports_analytics.php" class="active">Reports & Analytics</a>
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
