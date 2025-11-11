<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Audit Logs | IT Administrator | WeBuild</title>
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
      <a href="<?= base_url('admin/audit-logs') ?>" class="active">Audit Logs</a>
      <a href="<?= base_url('admin/system-health') ?>">System Health</a>
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
        <h5><strong>Audit Logs</strong></h5>
        <p class="text-muted mb-4">Comprehensive system activity monitoring and compliance tracking</p>

        <!-- Quick Actions -->
        <div class="mb-4 d-flex flex-wrap gap-2">
          <button class="btn btn-primary btn-sm" onclick="generateAuditReport()">Generate Report</button>
          <button class="btn btn-outline-secondary btn-sm" onclick="exportAuditLogs()">Export Logs</button>
          <button class="btn btn-outline-secondary btn-sm" onclick="archiveLogs()">Archive Old Logs</button>
        </div>

        <!-- Stats Boxes -->
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>Total Events</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>Security Events</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>Failed Operations</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>—</h4>
              <p>Data Size</p>
            </div>
          </div>
        </div>

        <!-- Audit Logs Table -->
        <div>
          <h6><strong>Active Audit Logs</strong></h6>
          <table class="table table-bordered table-sm mt-2">
            <thead class="table-light">
              <tr>
                <th>Timestamp</th>
                <th>Level</th>
                <th>Event</th>
                <th>User</th>
                <th>Action</th>
                <th>Resource</th>
                <th>IP Address</th>
                <th>Result</th>
                <th>Details</th>
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
                <td></td>
                <td><span class="badge bg-success">SUCCESS</span></td>
                <td>
                  <button class="btn btn-outline-primary btn-sm">View</button>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function generateAuditReport() {
    alert('Generating audit report... This will be available for download shortly.');
    // In production: window.location.href = '<?= base_url('admin/generate-audit-report') ?>';
  }

  function exportAuditLogs() {
    let csv = 'Timestamp,User,Action,IP Address,Status\n';
    
    $('.table tbody tr').each(function() {
      const cells = $(this).find('td');
      if (cells.length > 1) {
        const row = [];
        cells.each(function(index) {
          if (index < 5) {
            row.push('"' + $(this).text().trim().replace(/"/g, '""') + '"');
          }
        });
        csv += row.join(',') + '\n';
      }
    });
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'audit_logs_' + new Date().getTime() + '.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
  }

  function archiveLogs() {
    if (confirm('Archive logs older than 90 days?')) {
      alert('Old logs archived successfully.');
      // In production: $.ajax({ url: '<?= base_url('admin/archive-logs') ?>', method: 'POST' ...
    }
  }
</script>
</body>
</html>
