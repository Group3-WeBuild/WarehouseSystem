<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Overdue Follow-ups | WeBuild</title>
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
        <a href="overdue_followups.php" class="active">Overdue Follow-ups</a>
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
