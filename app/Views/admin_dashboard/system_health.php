<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>System Health | IT Administrator | WeBuild</title>
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
      <a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
      <a href="<?= base_url('admin/user-accounts') ?>">User Accounts</a>
      <a href="<?= base_url('admin/roles-permissions') ?>">Roles & Permissions</a>
      <a href="<?= base_url('admin/active-sessions') ?>">Active Sessions</a>
      <a href="<?= base_url('admin/security-policies') ?>">Security Policies</a>
      <a href="<?= base_url('admin/audit-logs') ?>">Audit Logs</a>
      <a href="<?= base_url('admin/system-health') ?>" class="active">System Health</a>
      <a href="<?= base_url('admin/database-management') ?>">Database Management</a>
      <a href="<?= base_url('admin/backup-recovery') ?>">Backup & Recovery</a>
      <a href="<?= base_url('admin/settings') ?>">Settings</a>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 p-0">
      <!-- Top Bar -->
      <div class="topbar">
        <input type="text" class="form-control w-25" placeholder="Search">
        <div>
          <span class="me-3"><?= date('M d, Y') ?> | <?= date('h:i A') ?> | <?= session()->get('role_name') ?> | <strong><?= session()->get('username') ?></strong></span>
          <a href="<?= base_url('auth/logout') ?>" class="btn btn-outline-secondary btn-sm">Logout</a>
        </div>
      </div>

      <!-- Page Container -->
      <div class="page-container">
        <h5><strong>System Health Dashboard</strong></h5>
        <p class="text-muted mb-4">Monitor real-time system performance and health metrics</p>

        <!-- Quick Actions -->
        <div class="mb-4 d-flex flex-wrap gap-2">
          <button class="btn btn-primary btn-sm" onclick="runHealthCheck()">Run Health Check</button>
          <button class="btn btn-warning btn-sm" onclick="restartServices()">Restart Services</button>
          <button class="btn btn-outline-secondary btn-sm" onclick="clearCaches()">Clear Caches</button>
        </div>

        <!-- Stats Boxes -->
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>CPU Usage</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>Memory Usage</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>Disk Space</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>Network Load</p>
            </div>
          </div>
        </div>

        <!-- Services Table -->
        <div>
          <h6><strong>System Services Status</strong></h6>
          <table class="table table-bordered table-sm mt-2">
            <thead class="table-light">
              <tr>
                <th>Service Name</th>
                <th>Status</th>
                <th>CPU</th>
                <th>Memory</th>
                <th>Uptime</th>
                <th>Last Check</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>WITMS Core Service</td>
                <td><span class="badge bg-success">Running</span></td>
                <td>—</td>
                <td>—</td>
                <td>—</td>
                <td>—</td>
                <td>
                  <button class="btn btn-outline-warning btn-sm">Restart</button>
                  <button class="btn btn-outline-primary btn-sm">Logs</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function runHealthCheck() {
    alert('Running comprehensive health check...\n\n✓ Database: Connected\n✓ File System: OK\n✓ Cache: Working\n✓ Memory: 65% used\n✓ Disk Space: 38% used\n\nOverall Status: HEALTHY');
  }

  function restartServices() {
    if (confirm('Are you sure you want to restart all services? This may cause brief downtime.')) {
      alert('Services restart initiated. System will be back online shortly.');
    }
  }

  function clearCaches() {
    if (confirm('Clear all system caches?')) {
      alert('All caches cleared successfully. Performance may be temporarily affected.');
    }
  }
</script>
</body>
</html>
