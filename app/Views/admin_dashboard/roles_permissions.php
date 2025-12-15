<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Roles & Permissions | IT Administrator | WeBuild</title>
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
    <div class="col-md-2 px-0 sidebar">
      <div class="text-center py-4">
        <h5 class="text-white mb-1">WITMS</h5>
        <small class="text-white-50">System Administrator</small>
      </div>
      <nav class="nav flex-column">
        <a class="nav-link" href="<?= base_url('admin/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a class="nav-link" href="<?= base_url('admin/user-accounts') ?>"><i class="bi bi-people"></i> User Accounts</a>
        <a class="nav-link active" href="<?= base_url('admin/roles-permissions') ?>"><i class="bi bi-shield-lock"></i> Roles & Permissions</a>
        <a class="nav-link" href="<?= base_url('admin/active-sessions') ?>"><i class="bi bi-person-check"></i> Active Sessions</a>
        <a class="nav-link" href="<?= base_url('admin/security-policies') ?>"><i class="bi bi-file-earmark-lock"></i> Security Policies</a>
        <a class="nav-link" href="<?= base_url('admin/audit-logs') ?>"><i class="bi bi-journal-text"></i> Audit Logs</a>
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
          <span class="me-3"><?= date('F d, Y | h:i A') ?> | <?= esc($user['role']) ?> | <strong><?= esc($user['username']) ?></strong></span>
          <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary btn-sm">Logout</a>
        </div>
      </div>

      <!-- Page Container -->
      <div class="page-container">
        <h5><strong>Roles & Permissions</strong></h5>
        <p class="text-muted mb-4">Define and manage user roles and their associated permissions</p>

        <!-- Quick Actions -->
        <div class="mb-4 d-flex flex-wrap gap-2">
          <button class="btn btn-primary btn-sm" onclick="alert('Custom role creation coming soon')">+ Create Custom Role</button>
          <button class="btn btn-outline-secondary btn-sm" onclick="exportRoles()">Export Roles</button>
          <button class="btn btn-outline-secondary btn-sm" onclick="window.location.href='<?= base_url('admin/audit-logs') ?>'">Audit Roles</button>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function exportRoles() {
    let csv = 'Role,Description,Permissions,Users Assigned\n';
    
    $('.role-card').each(function() {
      const role = $(this).find('h5').text();
      const description = $(this).find('p:first').text().replace(/"/g, '""');
      const usersAssigned = $(this).find('.badge').text();
      
      csv += `"${role}","${description}","Multiple","${usersAssigned}"\n`;
    });
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'roles_export_' + new Date().getTime() + '.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
  }
</script>
</body>
</html>
