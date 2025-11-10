<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profit Analysis | Top Management | WeBuild</title>
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

    /* Stat boxes */
    .stat-box {
      background-color: #fff;
      border: none;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      text-align: center;
      padding: 20px;
      border-radius: 8px;
      transition: 0.3s;
      margin-bottom: 15px;
    }
    .stat-box:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    .stat-box h4 {
      color: #0d47a1;
      font-weight: 700;
    }
    .stat-box p {
      color: #212529;
      margin-bottom: 0;
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
      <a href="profit_analysis.php" class="active">Profit Analysis</a>
      <a href="forecasting.php">Forecasting</a>
      <a href="performance_kpis.php">Performance KPIs</a>
      <a href="executive_reports.php">Executive Reports</a>
      <a href="audit_trail.php">Audit Trail</a>
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
        <h5><strong>Profit Analysis</strong></h5>
        <p class="text-muted mb-4">Comprehensive profit margins and profitability analysis</p>

        <!-- Stats Boxes -->
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>Gross Profit</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>Operating Profit</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>Net Profit</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>EBITDA</p>
            </div>
          </div>
        </div>

        <!-- Profit Margin Analysis Table -->
        <div>
          <h6><strong>Profit Margin Analysis</strong></h6>
          <table class="table table-bordered table-sm mt-2">
            <thead class="table-light">
              <tr>
                <th>Metric</th>
                <th>Value</th>
                <th>Margin</th>
                <th>Notes</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Gross Profit</td>
                <td>—</td>
                <td>—</td>
                <td></td>
              </tr>
              <tr>
                <td>Operating Profit</td>
                <td>—</td>
                <td>—</td>
                <td></td>
              </tr>
              <tr>
                <td>Net Profit</td>
                <td>—</td>
                <td>—</td>
                <td></td>
              </tr>
              <tr>
                <td>EBITDA</td>
                <td>—</td>
                <td>—</td>
                <td></td>
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
