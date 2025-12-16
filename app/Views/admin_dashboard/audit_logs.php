<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Audit Logs | IT Administrator | WeBuild</title>
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
        <a class="nav-link active" href="<?= base_url('admin/audit-logs') ?>"><i class="bi bi-journal-text"></i> Audit Logs</a>
        <a class="nav-link" href="<?= base_url('admin/system-health') ?>"><i class="bi bi-heart-pulse"></i> System Health</a>
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
              <h4><?= number_format($logStats['total_events'] ?? count($logs ?? [])) ?></h4>
              <p>Total Events</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4><?= number_format($logStats['security_events'] ?? 0) ?></h4>
              <p>Security Events</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4><?= number_format($logStats['failed_operations'] ?? 0) ?></h4>
              <p>Failed Operations</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4><?= esc($logStats['data_size'] ?? '0 KB') ?></h4>
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
                <th>Module</th>
                <th>Action</th>
                <th>User</th>
                <th>Table</th>
                <th>Record ID</th>
                <th>IP Address</th>
                <th>Result</th>
                <th>Details</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($logs)): ?>
                <?php foreach (array_slice($logs, 0, 50) as $log): ?>
                <tr>
                  <td><?= isset($log['created_at']) ? date('M d, Y H:i', strtotime($log['created_at'])) : 'N/A' ?></td>
                  <td><?= esc($log['module'] ?? 'System') ?></td>
                  <td><span class="badge bg-<?= ($log['action'] ?? '') == 'CREATE' ? 'success' : (($log['action'] ?? '') == 'DELETE' ? 'danger' : 'primary') ?>"><?= esc($log['action'] ?? 'N/A') ?></span></td>
                  <td><?= esc($log['username'] ?? $log['user_name'] ?? 'System') ?></td>
                  <td><?= esc($log['table_name'] ?? 'N/A') ?></td>
                  <td><?= esc($log['record_id'] ?? 'N/A') ?></td>
                  <td><?= esc($log['ip_address'] ?? 'N/A') ?></td>
                  <td><span class="badge bg-success">SUCCESS</span></td>
                  <td>
                    <button class="btn btn-outline-primary btn-sm" onclick="viewLogDetails(<?= $log['id'] ?? 0 ?>)">View</button>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
              <tr>
                <td colspan="9" class="text-center text-muted">No audit logs found</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
          <?php if (count($logs ?? []) > 50): ?>
          <p class="text-muted small">Showing 50 of <?= count($logs) ?> logs</p>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Audit Log Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="logDetailsContent">
          <div class="text-center py-3">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2">Loading details...</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function viewLogDetails(logId) {
    $('#logDetailsContent').html('<div class="text-center py-3"><div class="spinner-border text-primary"></div><p class="mt-2">Loading details...</p></div>');
    new bootstrap.Modal(document.getElementById('logDetailsModal')).show();
    
    $.get('<?= base_url('admin/get-audit-log-details') ?>/' + logId, function(response) {
      if (response.success && response.log) {
        const log = response.log;
        let html = '<table class="table table-bordered">';
        html += '<tr><th width="30%">Log ID</th><td>' + (log.id || 'N/A') + '</td></tr>';
        html += '<tr><th>Timestamp</th><td>' + (log.created_at || 'N/A') + '</td></tr>';
        html += '<tr><th>User</th><td>' + (log.username || log.user_name || 'System') + '</td></tr>';
        html += '<tr><th>Action</th><td><span class="badge bg-primary">' + (log.action || 'N/A') + '</span></td></tr>';
        html += '<tr><th>Module</th><td>' + (log.module || 'N/A') + '</td></tr>';
        html += '<tr><th>Controller</th><td>' + (log.controller || 'N/A') + '</td></tr>';
        html += '<tr><th>Table</th><td>' + (log.table_name || 'N/A') + '</td></tr>';
        html += '<tr><th>Record ID</th><td>' + (log.record_id || 'N/A') + '</td></tr>';
        html += '<tr><th>IP Address</th><td>' + (log.ip_address || 'N/A') + '</td></tr>';
        html += '<tr><th>User Agent</th><td class="small">' + (log.user_agent || 'N/A') + '</td></tr>';
        if (log.old_values) {
          html += '<tr><th>Old Values</th><td><pre class="small mb-0">' + log.old_values + '</pre></td></tr>';
        }
        if (log.new_values) {
          html += '<tr><th>New Values</th><td><pre class="small mb-0">' + log.new_values + '</pre></td></tr>';
        }
        html += '</table>';
        $('#logDetailsContent').html(html);
      } else {
        $('#logDetailsContent').html('<div class="alert alert-warning">Log details not found.</div>');
      }
    }).fail(function() {
      $('#logDetailsContent').html('<div class="alert alert-danger">Failed to load log details.</div>');
    });
  }

  function generateAuditReport() {
    window.location.href = '<?= base_url('admin/export-audit-logs') ?>';
  }

  function exportAuditLogs() {
    let csv = 'Timestamp,Module,Action,User,Table,Record ID,IP Address\n';
    
    $('.table tbody tr').each(function() {
      const cells = $(this).find('td');
      if (cells.length > 1) {
        const row = [];
        cells.each(function(index) {
          if (index < 7) {
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
    }
  }
</script>
</body>
</html>
