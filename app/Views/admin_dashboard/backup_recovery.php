<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Backup & Recovery | IT Administrator | WeBuild</title>
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
    <div class="col-md-2 px-0 sidebar">
      <div class="text-center py-4">
        <h5 class="text-white mb-1">WITMS</h5>
        <small class="text-white-50">System Administrator</small>
      </div>
      <nav class="nav flex-column">
        <a class="nav-link" href="<?= base_url('admin/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a class="nav-link" href="<?= base_url('admin/user-accounts') ?>"><i class="bi bi-people"></i> User Accounts</a>
        <a class="nav-link" href="<?= base_url('admin/roles-permissions') ?>"><i class="bi bi-shield-lock"></i> Roles & Permissions</a>
        <a class="nav-link" href="<?= base_url('admin/active-sessions') ?>"><i class="bi bi-person-check"></i> Active Sessions</a>
        <a class="nav-link" href="<?= base_url('admin/security-policies') ?>"><i class="bi bi-file-earmark-lock"></i> Security Policies</a>
        <a class="nav-link" href="<?= base_url('admin/audit-logs') ?>"><i class="bi bi-journal-text"></i> Audit Logs</a>
        <a class="nav-link" href="<?= base_url('admin/system-health') ?>"><i class="bi bi-heart-pulse"></i> System Health</a>
        <a class="nav-link" href="<?= base_url('admin/database-management') ?>"><i class="bi bi-database"></i> Database Management</a>
        <a class="nav-link active" href="<?= base_url('admin/backup-recovery') ?>"><i class="bi bi-cloud-arrow-up"></i> Backup & Recovery</a>
        <a class="nav-link" href="<?= base_url('admin/settings') ?>"><i class="bi bi-gear"></i> Settings</a>
      </nav>
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
        <h5><strong>Backup & Recovery Management</strong></h5>
        <p class="text-muted mb-4">Comprehensive backup management, recovery procedures, and disaster recovery planning</p>

        <!-- Quick Actions -->
        <div class="mb-4 d-flex flex-wrap gap-2">
          <button class="btn btn-primary btn-sm" onclick="startManualBackup()">Start Manual Backup</button>
          <button class="btn btn-danger btn-sm" onclick="emergencyRecovery()">Emergency Recovery</button>
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
<script>
  function startManualBackup() {
    if (confirm('Start manual backup now? This will create a full system backup.')) {
      alert('Manual backup initiated...\n\nBackup ID: BKP_' + Date.now() + '\nType: Full System Backup\nStatus: In Progress\n\nEstimated completion: 5-10 minutes\nYou will receive a notification when complete.');
    }
  }

  function emergencyRecovery() {
    const password = prompt('⚠️ EMERGENCY RECOVERY MODE ⚠️\n\nThis will restore the system to the last known good state.\nEnter admin password to continue:');
    
    if (password) {
      if (confirm('Are you absolutely sure? This will:\n\n1. Stop all services\n2. Restore from last backup\n3. Reset configurations\n4. Log out all users\n\nProceed with emergency recovery?')) {
        alert('Emergency recovery process initiated.\n\nPlease wait while the system is being restored...\n\nEstimated time: 15-30 minutes');
      }
    }
  }

  function restoreBackup(backupId) {
    if (confirm('Restore from backup: ' + backupId + '?\n\nThis will replace current data with the backup version.')) {
      alert('Restore process initiated for backup: ' + backupId);
    }
  }

  function deleteBackup(backupId) {
    if (confirm('Permanently delete backup: ' + backupId + '?\n\nThis action cannot be undone.')) {
      alert('Backup deleted: ' + backupId);
    }
  }
</script>
</body>
</html>
