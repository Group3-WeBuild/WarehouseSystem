<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Executive Reports | Top Management | WeBuild</title>
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
      box-shadow: 0 1px 5px rgba(0,0,0,0.05);
    }

    /* Report Cards */
    .report-card {
      background-color: #fff;
      border: none;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      text-align: center;
      padding: 20px;
      border-radius: 8px;
      transition: 0.3s;
      cursor: pointer;
    }
    .report-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    .report-card h5 {
      color: #0d47a1;
      font-weight: 700;
    }
    .report-card p {
      color: #212529;
      margin-bottom: 0;
    }
  </style>
</head>

<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 px-0 sidebar">
      <div class="text-center py-4">
        <h5 class="text-white mb-1">WeBuild</h5>
        <small class="text-white-50">Top Management</small>
      </div>
      <nav class="nav flex-column">
        <a class="nav-link" href="<?= base_url('management/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a class="nav-link" href="<?= base_url('management/financial-reports') ?>"><i class="bi bi-graph-up"></i> Financial Reports</a>
        <a class="nav-link" href="<?= base_url('management/inventory-overview') ?>"><i class="bi bi-boxes"></i> Inventory Overview</a>
        <a class="nav-link" href="<?= base_url('management/warehouse-analytics') ?>"><i class="bi bi-bar-chart"></i> Warehouse Analytics</a>
        <a class="nav-link" href="<?= base_url('management/forecasting') ?>"><i class="bi bi-graph-up-arrow"></i> Forecasting</a>
        <a class="nav-link" href="<?= base_url('management/performance-kpis') ?>"><i class="bi bi-speedometer"></i> Performance KPIs</a>
        <a class="nav-link active" href="<?= base_url('management/executive-reports') ?>"><i class="bi bi-file-earmark-text"></i> Executive Reports</a>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 p-0">
      <!-- Top Bar -->
      <div class="topbar">
        <input type="text" class="form-control w-25" placeholder="Search">
        <div>
          <span class="me-3"><?= date('M d, Y | h:i A') ?> | <?= esc($user['role'] ?? 'Top Management') ?> | <strong><?= esc($user['name'] ?? 'User') ?></strong></span>
          <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary btn-sm">Logout</a>
        </div>
      </div>

      <!-- Page Container -->
      <div class="page-container">
        <h5><strong>Executive Reports</strong></h5>
        <p class="text-muted mb-4">High-level executive summaries and board reports</p>

        <!-- Report Cards -->
        <div class="row g-3">
          <div class="col-md-4">
            <a href="<?= base_url('management/monthly-report') ?>" class="text-decoration-none">
              <div class="report-card">
                <h5>📊 Monthly Executive Summary</h5>
                <p>Key metrics and highlights</p>
                <span class="badge bg-primary">Generate Report</span>
              </div>
            </a>
          </div>
          <div class="col-md-4">
            <a href="<?= base_url('management/quarterly-report') ?>" class="text-decoration-none">
              <div class="report-card">
                <h5>📈 Board Presentation</h5>
                <p>Quarterly board meeting deck</p>
                <span class="badge bg-primary">Generate Report</span>
              </div>
            </a>
          </div>
          <div class="col-md-4">
            <a href="<?= base_url('management/financial-reports') ?>" class="text-decoration-none">
              <div class="report-card">
                <h5>💰 Financial Overview</h5>
                <p>Revenue, expenses & cash flow</p>
                <span class="badge bg-success">View Report</span>
              </div>
            </a>
          </div>
          <div class="col-md-4">
            <a href="<?= base_url('management/inventory-overview') ?>" class="text-decoration-none">
              <div class="report-card">
                <h5>📦 Inventory Report</h5>
                <p>Stock levels & valuations</p>
                <span class="badge bg-info">View Report</span>
              </div>
            </a>
          </div>
          <div class="col-md-4">
            <a href="<?= base_url('management/warehouse-analytics') ?>" class="text-decoration-none">
              <div class="report-card">
                <h5>🏭 Warehouse Performance</h5>
                <p>Efficiency & utilization</p>
                <span class="badge bg-info">View Report</span>
              </div>
            </a>
          </div>
          <div class="col-md-4">
            <a href="<?= base_url('print/audit-logs') ?>" target="_blank" class="text-decoration-none">
              <div class="report-card">
                <h5>📋 Audit Trail Report</h5>
                <p>System activity log</p>
                <span class="badge bg-warning text-dark">Export PDF</span>
              </div>
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
