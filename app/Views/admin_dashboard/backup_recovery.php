<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Backup & Recovery | IT Administrator | WeBuild</title>
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
    .sidebar h5 { text-align: center; font-weight: 600; margin-bottom: 25px; }
    .sidebar a { display: block; color: #cfd8dc; text-decoration: none; padding: 10px 20px; border-radius: 5px; margin: 5px 10px; font-size: 15px; transition: 0.3s; }
    .sidebar a:hover, .sidebar a.active { background-color: #1565c0; color: #fff; }

    /* Topbar */
    .topbar { background-color: #e9ecef; border-bottom: 1px solid #ccc; padding: 10px 25px; display: flex; justify-content: space-between; align-items: center; }

    /* Page container */
    .page-container { background-color: #fff; margin: 25px; padding: 25px; border-radius: 6px; box-shadow: 0 1px 5px rgba(0,0,0,0.05); }

    /* Stat cards with hover effect */
    .stat-box {
      background-color: #fff;
      border: none;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      text-align: center;
      padding: 20px;
      border-radius: 8px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      margin-bottom: 15px;
      cursor: pointer;
    }
    .stat-box h4 { color: #0d47a1; font-weight: 700; }
    .stat-box p { color: #212529; margin-bottom: 0; }
    .stat-box:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }

    .quick-actions button { margin-right: 10px; margin-bottom: 10px; }
    .table th, .table td { vertical-align: middle; }
    .badge { font-size: 0.8rem; }
  </style>
</head>

<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
      <h5>WeBuild</h5>
      <a href="dashboard.php">Dashboard</a>
      <a href="user_accounts.php">User Accounts</a>
      <a href="roles_permissions.php">Roles & Permissions</a>
      <a href="active_sessions.php">Active Sessions</a>
      <a href="security_policies.php">Security Policies</a>
      <a href="audit_logs.php">Audit Logs</a>
      <a href="system_health.php">System Health</a>
      <a href="database_management.php">Database Management</a>
      <a href="backup_recovery.php" class="active">Backup & Recovery</a>
      <a href="settings.php">Settings</a>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 p-0">
      <!-- Top Bar -->
      <div class="topbar">
        <input type="text" class="form-control w-25" placeholder="Search">
        <div>
          <span class="me-3">Date | Time | IT Administrator | <strong>Username</strong></span>
          <button class="btn btn-outline-secondary btn-sm">Logout</button>
        </div>
      </div>

      <!-- Page Container -->
      <div class="page-container">
        <h5><strong>Backup & Recovery Management</strong></h5>
        <p class="text-muted mb-4">Comprehensive backup management, recovery procedures, and disaster recovery planning</p>

        <!-- Quick Actions -->
        <div class="mb-4 d-flex flex-wrap gap-2">
          <button class="btn btn-primary btn-sm">Start Manual Backup</button>
          <button class="btn btn-danger btn-sm">Emergency Recovery</button>
        </div>

        <!-- Stats Boxes -->
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>Last Backup</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>Success Rate</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>Storage Used</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>RTO Target</p>
            </div>
          </div>
        </div>

        <!-- Scheduled Backup Jobs Table -->
        <div>
          <h6><strong>Scheduled Backup Jobs</strong></h6>
          <button class="btn btn-sm btn-primary mb-2">New Schedule</button>
          <table class="table table-bordered table-sm mt-2">
            <thead class="table-light">
              <tr>
                <th>Job Name</th>
                <th>Frequency</th>
                <th>Target</th>
                <th>Duration</th>
                <th>Next Run</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><span class="badge bg-success">Active</span></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><span class="badge bg-success">Active</span></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><span class="badge bg-success">Active</span></td>
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
