<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Financial Reports | Top Management | WeBuild</title>
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

    .report-card {
      background-color: #fff;
      border: none;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      text-align: left;
      padding: 20px;
      border-radius: 8px;
      transition: 0.3s;
      cursor: pointer;
    }

    .report-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    .report-card h6 {
      font-weight: 600;
      color: #0d47a1;
    }

    .report-card p {
      color: #212529;
      font-size: 0.9rem;
    }

    .filter-group {
      margin-bottom: 20px;
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
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
  <a href="financial_reports.php"class="active">Financial Reports</a>
  <a href="inventory_overview.php">Inventory Overview</a>
  <a href="warehouse_analytics.php">Warehouse Analytics</a>
  <a href="revenue_tracking.php">Revenue Tracking</a>
  <a href="expense_management.php">Expense Management</a>
  <a href="profit_analysis.php">Profit Analysis</a>
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
        <h5><strong>Financial Reports</strong></h5>
        <p class="text-muted mb-4">Comprehensive financial analysis and reporting for WeBuild operations</p>

        <!-- Filter Dropdowns -->
        <div class="filter-group">
          <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
              Select Period
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Last 3 Months</a></li>
              <li><a class="dropdown-item" href="#">Last 6 Months</a></li>
              <li><a class="dropdown-item" href="#">Last Year</a></li>
              <li><a class="dropdown-item" href="#">Custom Range</a></li>
            </ul>
          </div>

          <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
              Select Report Type
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">All Reports</a></li>
              <li><a class="dropdown-item" href="#">P&L Statements</a></li>
              <li><a class="dropdown-item" href="#">Cash Flow Reports</a></li>
              <li><a class="dropdown-item" href="#">Balance Sheets</a></li>
              <li><a class="dropdown-item" href="#">Tax Reports</a></li>
            </ul>
          </div>

          <button class="btn btn-primary btn-sm">Generate Report</button>
        </div>

        <!-- Reports Cards -->
        <div class="row g-3">
          <div class="col-md-4">
            <div class="report-card">
              <h6>Monthly P&L Statement</h6>
              <p>Detailed profit and loss analysis</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="report-card">
              <h6>Cash Flow Report</h6>
              <p>Inflow and outflow analysis</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="report-card">
              <h6>Revenue Analysis</h6>
              <p>Revenue trends and projections</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="report-card">
              <h6>Expense Breakdown</h6>
              <p>Detailed expense categorization</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="report-card">
              <h6>Balance Sheet</h6>
              <p>Assets, liabilities, and equity</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="report-card">
              <h6>Tax Reports</h6>
              <p>Tax preparation and compliance</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
