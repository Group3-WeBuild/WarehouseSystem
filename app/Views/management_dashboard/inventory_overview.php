<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventory Overview | Top Management | WeBuild</title>
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

    .stat-card {
      background-color: #fff;
      border: none;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      text-align: center;
      padding: 20px;
      border-radius: 8px;
      transition: 0.3s;
      cursor: pointer;
    }

    .stat-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    .stat-card h4 {
      color: #0d47a1;
      font-weight: 700;
    }

    .stat-card p {
      color: #212529;
      font-size: 0.9rem;
      margin-bottom: 0;
    }

    .warehouse-card {
      background-color: #fff;
      border: none;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      padding: 20px;
      border-radius: 8px;
      transition: 0.3s;
      cursor: pointer;
      margin-bottom: 15px;
    }

    .warehouse-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    .warehouse-card h6 {
      font-weight: 600;
      color: #0d47a1;
    }

    .warehouse-card p {
      font-size: 0.9rem;
      color: #212529;
    }

  </style>
</head>

<body>
<div class="container-fluid">
  <div class="row">
 <!-- Sidebar -->
<div class="col-md-2 sidebar">
  <h5>WeBuild</h5>
  <a href="<?= base_url('management/dashboard') ?>">Dashboard</a>
  <a href="<?= base_url('management/financial-reports') ?>">Financial Reports</a>
  <a href="<?= base_url('management/inventory-overview') ?>" class="active">Inventory Overview</a>
  <a href="<?= base_url('management/warehouse-analytics') ?>">Warehouse Analytics</a>
  <a href="<?= base_url('management/forecasting') ?>">Forecasting</a>
  <a href="<?= base_url('management/performance-kpis') ?>">Performance KPIs</a>
  <a href="<?= base_url('management/executive-reports') ?>">Executive Reports</a>
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
        <h5><strong>Inventory Overview</strong></h5>
        <p class="text-muted mb-4">Real-time inventory tracking across all WeBuild warehouses</p>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <div class="stat-card">
              <h4>—</h4>
              <p>Total Items</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-card">
              <h4>—</h4>
              <p>Low Stock Items</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-card">
              <h4>—</h4>
              <p>Average Capacity</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-card">
              <h4>—</h4>
              <p>Monthly Turnover</p>
            </div>
          </div>
        </div>

        <!-- Warehouse Cards -->
        <div class="row g-3">
          <div class="col-md-6">
            <div class="warehouse-card">
              <h6>Warehouse A</h6>
              <p>Capacity: —</p>
              <p>Items: —</p>
              <p>Value: —</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="warehouse-card">
              <h6>Warehouse B</h6>
              <p>Capacity: —</p>
              <p>Items: —</p>
              <p>Value: —</p>
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
