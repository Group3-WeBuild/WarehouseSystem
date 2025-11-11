<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Settings | WeBuild</title>
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

    .form-label {
      font-weight: 600;
    }

    .form-check-label {
      margin-left: 0.25rem;
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
        <a href="<?= base_url('accounts-receivable/aging-report') ?>">Aging Report</a>
        <a href="<?= base_url('accounts-receivable/settings') ?>" class="active">Settings</a>
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
          <h5><strong>Settings</strong></h5>
          <p class="text-muted mb-4">Manage your account preferences and notifications</p>

          <!-- User Profile -->
          <h6 class="mb-3"><strong>User Profile</strong></h6>
          <form>
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Full Name:</label>
                <input type="text" class="form-control" value="">
              </div>
              <div class="col-md-6">
                <label class="form-label">Role:</label>
                <input type="text" class="form-control" value="" disabled>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Email:</label>
                <input type="email" class="form-control" value="">
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone:</label>
                <input type="text" class="form-control" value="">
              </div>
            </div>

            <!-- Notification Preferences -->
            <h6 class="mb-3 mt-4"><strong>Notification Preferences</strong></h6>
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" id="emailFollowups" checked>
              <label class="form-check-label" for="emailFollowups">
                Email notifications for overdue follow-ups
              </label>
            </div>
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" id="dailySummary" checked>
              <label class="form-check-label" for="dailySummary">
                Daily summary reports
              </label>
            </div>
            <div class="form-check mb-4">
              <input class="form-check-input" type="checkbox" id="smsAlerts">
              <label class="form-check-label" for="smsAlerts">
                SMS alerts for urgent follow-ups
              </label>
            </div>

            <button type="submit" class="btn btn-blue">Save Settings</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</body>
</html>
