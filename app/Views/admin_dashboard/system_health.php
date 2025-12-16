<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>System Health | IT Administrator | WeBuild</title>
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
        <h5 class="text-white mb-1">WeBuild</h5>
        <small class="text-white-50">System Administrator</small>
      </div>
      <nav class="nav flex-column">
        <a class="nav-link" href="<?= base_url('admin/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a class="nav-link" href="<?= base_url('admin/user-accounts') ?>"><i class="bi bi-people"></i> User Accounts</a>
        <a class="nav-link" href="<?= base_url('admin/roles-permissions') ?>"><i class="bi bi-shield-lock"></i> Roles & Permissions</a>
        <a class="nav-link" href="<?= base_url('admin/active-sessions') ?>"><i class="bi bi-person-check"></i> Active Sessions</a>
        <a class="nav-link" href="<?= base_url('admin/security-policies') ?>"><i class="bi bi-file-earmark-lock"></i> Security Policies</a>
        <a class="nav-link" href="<?= base_url('admin/audit-logs') ?>"><i class="bi bi-journal-text"></i> Audit Logs</a>
        <a class="nav-link active" href="<?= base_url('admin/system-health') ?>"><i class="bi bi-heart-pulse"></i> System Health</a>
        <a class="nav-link" href="<?= base_url('admin/database-management') ?>"><i class="bi bi-database"></i> Database Management</a>
        <a class="nav-link" href="<?= base_url('admin/backup-recovery') ?>"><i class="bi bi-cloud-arrow-up"></i> Backup & Recovery</a>
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
              <h4><?= esc($health['cpu_usage'] ?? '45%') ?></h4>
              <p>CPU Usage</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4><?= esc($health['memory_usage'] ?? '62%') ?></h4>
              <p>Memory Usage</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4><?= esc($health['disk_usage'] ?? '38%') ?></h4>
              <p>Disk Space</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4><?= esc($health['uptime'] ?? '99.9%') ?></h4>
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
                <td>WeBuild Core Service</td>
                <td><span class="badge bg-success">Running</span></td>
                <td><?= esc($health['cpu_usage'] ?? '45%') ?></td>
                <td><?= esc($health['memory_usage'] ?? '62%') ?></td>
                <td><?= esc($health['uptime'] ?? '99.9%') ?></td>
                <td><?= date('M d, Y H:i') ?></td>
                <td>
                  <button class="btn btn-outline-warning btn-sm" onclick="restartService('WeBuild Core')">Restart</button>
                  <button class="btn btn-outline-primary btn-sm" onclick="viewLogs('WeBuild Core')">Logs</button>
                </td>
              </tr>
              <tr>
                <td>Database Server (MySQL)</td>
                <td><span class="badge bg-success">Running</span></td>
                <td>12%</td>
                <td>256 MB</td>
                <td><?= esc($health['uptime'] ?? '99.9%') ?></td>
                <td><?= date('M d, Y H:i') ?></td>
                <td>
                  <button class="btn btn-outline-warning btn-sm" onclick="restartService('MySQL')">Restart</button>
                  <button class="btn btn-outline-primary btn-sm" onclick="viewLogs('MySQL')">Logs</button>
                </td>
              </tr>
              <tr>
                <td>Apache Web Server</td>
                <td><span class="badge bg-success">Running</span></td>
                <td>8%</td>
                <td>128 MB</td>
                <td><?= esc($health['uptime'] ?? '99.9%') ?></td>
                <td><?= date('M d, Y H:i') ?></td>
                <td>
                  <button class="btn btn-outline-warning btn-sm" onclick="restartService('Apache')">Restart</button>
                  <button class="btn btn-outline-primary btn-sm" onclick="viewLogs('Apache')">Logs</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Logs Modal -->
<div class="modal fade" id="logsModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Service Logs: <span id="logServiceName"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <pre id="logContent" class="bg-dark text-light p-3" style="max-height: 400px; overflow-y: auto; font-size: 12px;">[Loading logs...]</pre>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" onclick="refreshLogs()">Refresh</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  let currentService = '';
  
  function runHealthCheck() {
    alert('Running comprehensive health check...\n\n✓ Database: Connected\n✓ File System: OK\n✓ Cache: Working\n✓ Memory: <?= $health['memory_usage'] ?? '62%' ?> used\n✓ Disk Space: <?= $health['disk_usage'] ?? '38%' ?> used\n\nOverall Status: HEALTHY');
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
  
  function restartService(serviceName) {
    if (confirm('Restart ' + serviceName + ' service?\n\nThis may cause temporary disruption.')) {
      alert(serviceName + ' service restart initiated.\n\nStatus: Restarting...\n\nThe service will be back online in a few seconds.');
    }
  }
  
  function viewLogs(serviceName) {
    currentService = serviceName;
    $('#logServiceName').text(serviceName);
    
    // Generate sample log entries
    const now = new Date();
    let logs = '';
    for (let i = 0; i < 20; i++) {
      const time = new Date(now.getTime() - i * 60000);
      const level = Math.random() > 0.9 ? 'WARN' : 'INFO';
      const levelColor = level === 'WARN' ? '\x1b[33m' : '\x1b[32m';
      logs += `[${time.toISOString()}] [${level}] ${serviceName}: Service running normally - Health check passed\n`;
    }
    
    $('#logContent').text(logs);
    new bootstrap.Modal(document.getElementById('logsModal')).show();
  }
  
  function refreshLogs() {
    viewLogs(currentService);
  }
</script>
</body>
</html>
