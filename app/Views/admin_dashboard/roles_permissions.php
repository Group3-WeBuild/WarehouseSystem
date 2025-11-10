<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Roles & Permissions | IT Administrator | WeBuild</title>
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

    .stat-box {
      background-color: #fff;
      border: none;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
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

    .stat-box p {
      color: #212529;
      margin-bottom: 0;
    }

    .role-card {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      padding: 20px;
      transition: 0.3s;
      margin-bottom: 15px;
    }

    .role-card h5 {
      font-weight: 700;
      color: #0d47a1;
    }

    .role-card p {
      font-size: 0.85rem;
      color: #212529;
    }

    .role-card .badge {
      font-size: 0.75rem;
    }

    .quick-actions button {
      margin-right: 10px;
      margin-bottom: 10px;
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
      <a href="user_accounts.php">User Accounts</a>
      <a href="roles_permissions.php" class="active">Roles & Permissions</a>
      <a href="active_sessions.php">Active Sessions</a>
      <a href="security_policies.php">Security Policies</a>
      <a href="audit_logs.php">Audit Logs</a>
      <a href="system_health.php">System Health</a>
      <a href="database_management.php">Database Management</a>
      <a href="backup_recovery.php">Backup & Recovery</a>
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
        <h5><strong>Roles & Permissions</strong></h5>
        <p class="text-muted mb-4">Define and manage user roles and their associated permissions</p>

        <!-- Quick Actions -->
        <div class="mb-4 d-flex flex-wrap gap-2">
          <button class="btn btn-primary btn-sm">+ Create Custom Role</button>
          <button class="btn btn-outline-secondary btn-sm">Export Roles</button>
          <button class="btn btn-outline-secondary btn-sm">Audit Roles</button>
        </div>

        <!-- Role Cards -->
        <div class="row">
          <div class="col-md-4">
            <div class="role-card">
              <h5>IT Administrator</h5>
              <p>Manages system security, user roles, and system maintenance.</p>
              <p><span class="badge bg-secondary">— users assigned</span></p>
              <button class="btn btn-outline-info btn-sm">Edit</button>
              <button class="btn btn-outline-primary btn-sm">View Users</button>
            </div>
          </div>

          <div class="col-md-4">
            <div class="role-card">
              <h5>Warehouse Manager</h5>
              <p>Oversees inventory at each warehouse, verifies stock levels, and approves stock movements.</p>
              <p><span class="badge bg-secondary">— users assigned</span></p>
              <button class="btn btn-outline-info btn-sm">Edit</button>
              <button class="btn btn-outline-primary btn-sm">View Users</button>
            </div>
          </div>

          <div class="col-md-4">
            <div class="role-card">
              <h5>Warehouse Staff</h5>
              <p>Scans items in/out, updates the system with stock changes, and assists in physical counts.</p>
              <p><span class="badge bg-secondary">— users assigned</span></p>
              <button class="btn btn-outline-info btn-sm">Edit</button>
              <button class="btn btn-outline-primary btn-sm">View Users</button>
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
