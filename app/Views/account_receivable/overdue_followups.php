<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Overdue Follow-ups | WeBuild</title>
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
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-2 px-0 sidebar">
        <div class="text-center py-4">
          <h5 class="text-white mb-1">WeBuild</h5>
          <small class="text-white-50">Accounts Receivable</small>
        </div>
        <nav class="nav flex-column">
          <a class="nav-link" href="<?= base_url('accounts-receivable/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
          <a class="nav-link" href="<?= base_url('accounts-receivable/manage-invoices') ?>"><i class="bi bi-file-earmark-text"></i> Manage Invoices</a>
          <a class="nav-link" href="<?= base_url('accounts-receivable/record-payments') ?>"><i class="bi bi-cash-coin"></i> Record Payments</a>
          <a class="nav-link" href="<?= base_url('accounts-receivable/client-management') ?>"><i class="bi bi-people"></i> Client Management</a>
          <a class="nav-link active" href="<?= base_url('accounts-receivable/overdue-followups') ?>"><i class="bi bi-bell"></i> Overdue Follow-ups</a>
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
          <h5><strong>Overdue Follow-ups</strong></h5>
          <p class="text-muted mb-4">Past Due Collections Center</p>

          <!-- Buttons -->
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <button class="btn btn-primary btn-sm me-2">Bulk Reminders</button>
              <button class="btn btn-secondary btn-sm">Collection Report</button>
            </div>
          </div>

          <!-- Stat Boxes -->
          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <div class="stat-box">
                <h4>—</h4>
                <p>Overdue Invoices</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="stat-box">
                <h4>—</h4>
                <p>Total Overdue Amount</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="stat-box">
                <h4>—</h4>
                <p>Average Days Overdue</p>
              </div>
            </div>
          </div>

          <!-- Table -->
          <div>
            <h6><strong>Client Management</strong></h6>
            <table class="table table-bordered table-sm mt-2">
              <thead class="table-light">
                <tr>
                  <th>Invoice#</th>
                  <th>Client</th>
                  <th>Amount</th>
                  <th>Due Date</th>
                  <th>Days Overdue</th>
                  <th>Last Contact</th>
                  <th>Priority</th>
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
                  <td><span class="badge bg-danger">High</span></td>
                  <td>
                    <button class="btn btn-outline-warning btn-sm">Remind</button>
                    <button class="btn btn-outline-info btn-sm">Call</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>

</body>
</html>
