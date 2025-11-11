<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aging Report | WeBuild</title>
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

    /* Page Container */
    .page-container {
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

    .table th, .table td {
      vertical-align: middle;
    }

    .badge {
      font-size: 0.8rem;
    }

    .btn-blue {
      background-color: #0d47a1;
      color: white;
    }

    .btn-blue:hover {
      background-color: #1565c0;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-2 sidebar">
        <h5>WeBuild</h5>
        <a href="<?= base_url('accounts-receivable/dashboard') ?>">Dashboard</a>
        <a href="<?= base_url('accounts-receivable/manage-invoices') ?>">Manage Invoices</a>
        <a href="<?= base_url('accounts-receivable/record-payments') ?>">Record Payments</a>
        <a href="<?= base_url('accounts-receivable/client-management') ?>">Client Management</a>
        <a href="<?= base_url('accounts-receivable/overdue-followups') ?>">Overdue Follow-ups</a>
        <a href="<?= base_url('accounts-receivable/reports-analytics') ?>">Reports & Analytics</a>
        <a href="<?= base_url('accounts-receivable/aging-report') ?>" class="active">Aging Report</a>
        <a href="<?= base_url('accounts-receivable/settings') ?>">Settings</a>
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
          <h5><strong>Accounts Receivable Aging Report</strong></h5>
          <p class="text-muted mb-4">Track Outstanding Balances by Age Periods</p>

          <!-- Export Button -->
          <div class="d-flex justify-content-end mb-4">
            <button class="btn btn-blue btn-sm">Export</button>
          </div>

          <!-- Stat Boxes for Aging Periods -->
          <div class="row g-3 mb-4">
            <div class="col-md-3">
              <div class="stat-box">
                <h4>—</h4>
                <p>Current (0-30 days)</p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat-box">
                <h4>—</h4>
                <p>31-60 days</p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat-box">
                <h4>—</h4>
                <p>61-90 days</p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat-box">
                <h4>—</h4>
                <p>90+ days</p>
              </div>
            </div>
          </div>

          <!-- Aging Table -->
          <div>
            <h6><strong>Client Aging Details</strong></h6>
            <table class="table table-bordered table-sm mt-2">
              <thead class="table-light">
                <tr>
                  <th>Client</th>
                  <th>Total Outstanding</th>
                  <th>Current</th>
                  <th>31-60 Days</th>
                  <th>61-90 Days</th>
                  <th>90+ Days</th>
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
                  <td></td>
                  <td>
                    <button class="btn btn-outline-warning btn-sm">Contact</button>
                  </td>
                </tr>
                <tr>
                <!-- Add more rows dynamically -->
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>

</body>
</html>
