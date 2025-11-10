<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Audit Trail | Top Management | WeBuild</title>
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
      box-shadow: 0 1px 5px rgba(0,0,0,0.05);
    }

    /* Table hover effect */
    .table-hover tbody tr:hover {
      background-color: #e3f2fd;
    }

    .filter-card {
      background-color: #fff;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      margin-bottom: 20px;
    }

    .filter-card label {
      font-weight: 500;
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
      <a href="financial_reports.php">Financial Reports</a>
      <a href="inventory_overview.php">Inventory Overview</a>
      <a href="warehouse_analytics.php">Warehouse Analytics</a>
      <a href="revenue_tracking.php">Revenue Tracking</a>
      <a href="expense_management.php">Expense Management</a>
      <a href="profit_analysis.php">Profit Analysis</a>
      <a href="forecasting.php">Forecasting</a>
      <a href="performance_kpis.php">Performance KPIs</a>
      <a href="executive_reports.php">Executive Reports</a>
      <a href="audit_trail.php" class="active">Audit Trail</a>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 p-0">
      <!-- Top Bar -->
      <div class="topbar">
        <input type="text" class="form-control w-25" placeholder="Search">
        <div>
          <span class="me-3">Date | Time | Top Management | <strong>Username</strong></span>
          <button class="btn btn-outline-secondary btn-sm">Logout</button>
        </div>
      </div>

      <!-- Page Container -->
      <div class="page-container">
        <h5><strong>Audit Trail</strong></h5>
        <p class="text-muted mb-4">Complete audit logs and transaction history for compliance</p>

        <!-- Filter Card -->
        <div class="filter-card row g-3 align-items-end">
          <div class="col-md-3">
            <label>Date From</label>
            <input type="date" class="form-control">
          </div>
          <div class="col-md-3">
            <label>Date To</label>
            <input type="date" class="form-control">
          </div>
          <div class="col-md-3">
            <label>Transaction Type</label>
            <select class="form-select">
              <option selected>All Transactions</option>
              <option>Login</option>
              <option>Data Change</option>
              <option>Financial</option>
            </select>
          </div>
          <div class="col-md-3">
            <button class="btn btn-primary w-100">Apply Filter</button>
          </div>
        </div>

        <!-- Audit Table -->
        <div>
          <h6><strong>Audit Log Entries</strong></h6>
          <table class="table table-bordered table-hover table-sm mt-2">
            <thead class="table-light">
              <tr>
                <th>Timestamp</th>
                <th>User</th>
                <th>Action</th>
                <th>Module</th>
                <th>Details</th>
                <th>IP Address</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="6" class="text-center text-muted">No audit log entries available</td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
