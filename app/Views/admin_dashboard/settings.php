<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>System Settings | IT Administrator | WeBuild</title>
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

    .settings-tabs .nav-link {
      color: #333;
      font-weight: 600;
    }
    .settings-tabs .nav-link.active {
      color: #0d47a1;
      border-bottom: 3px solid #0d47a1;
    }

    .settings-section {
      background-color: #f8f9fa;
      padding: 20px;
      border-radius: 5px;
      margin-bottom: 20px;
    }

    .form-label { font-weight: 600; }

    .toggle-switch {
      width: 50px;
      height: 25px;
      background-color: #ccc;
      border-radius: 25px;
      position: relative;
      cursor: pointer;
      transition: background-color 0.3s;
      display: inline-block;
    }
    .toggle-switch::after {
      content: "";
      width: 21px;
      height: 21px;
      background-color: white;
      border-radius: 50%;
      position: absolute;
      top: 2px;
      left: 2px;
      transition: left 0.3s;
    }
    .toggle-switch.active {
      background-color: #0d47a1;
    }
    .toggle-switch.active::after {
      left: 27px;
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
        <a class="nav-link" href="<?= base_url('admin/roles-permissions') ?>"><i class="bi bi-shield-lock"></i> Roles & Permissions</a>
        <a class="nav-link" href="<?= base_url('admin/active-sessions') ?>"><i class="bi bi-person-check"></i> Active Sessions</a>
        <a class="nav-link" href="<?= base_url('admin/security-policies') ?>"><i class="bi bi-file-earmark-lock"></i> Security Policies</a>
        <a class="nav-link" href="<?= base_url('admin/audit-logs') ?>"><i class="bi bi-journal-text"></i> Audit Logs</a>
        <a class="nav-link" href="<?= base_url('admin/system-health') ?>"><i class="bi bi-heart-pulse"></i> System Health</a>
        <a class="nav-link" href="<?= base_url('admin/database-management') ?>"><i class="bi bi-database"></i> Database Management</a>
        <a class="nav-link" href="<?= base_url('admin/backup-recovery') ?>"><i class="bi bi-cloud-arrow-up"></i> Backup & Recovery</a>
        <a class="nav-link active" href="<?= base_url('admin/settings') ?>"><i class="bi bi-gear"></i> Settings</a>
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
        <h5><strong>System Settings</strong></h5>
        <p class="text-muted mb-4">Configure global system parameters, application settings, and operational preferences</p>

        <div class="mb-3 d-flex gap-2">
          <button class="btn btn-success btn-sm" onclick="saveAllSettings()">Save All Settings</button>
          <button class="btn btn-secondary btn-sm" onclick="resetToDefaults()">Reset to Defaults</button>
        </div>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs settings-tabs mb-4" id="settingsTabs" role="tablist">
          <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#general">General Settings</button></li>
          <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#security">Security Settings</button></li>
          <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#performance">Performance Settings</button></li>
        </ul>

        <div class="tab-content">

          <!-- General Settings -->
          <div class="tab-pane fade show active" id="general">
            <div class="row">
              <div class="col-md-6 settings-section">
                <h6><strong>Company Information</strong></h6>
                <label class="form-label mt-2">Company Name</label>
                <input type="text" class="form-control mb-2" value="WeBuild Construction Company">
                <label class="form-label">Company Address</label>
                <input type="text" class="form-control mb-2" value="Ramon Magsaysay Memorial Colleges">
                <label class="form-label">Company Website</label>
                <input type="text" class="form-control" value="https://www.webuild.com">
              </div>

              <div class="col-md-6 settings-section">
                <h6><strong>Application Configuration</strong></h6>
                <label class="form-label mt-2">Default Language</label>
                <select class="form-select mb-2"><option selected>English</option></select>
                <label class="form-label">Timezone</label>
                <select class="form-select mb-2"><option selected>UTC (Coordinated Universal Time)</option></select>
                <label class="form-label">Currency</label>
                <select class="form-select"><option selected>Philippines Peso (₱)</option></select>
              </div>
            </div>
          </div>

          <!-- Security Settings -->
          <div class="tab-pane fade" id="security">
            <div class="row">
              <div class="col-md-6 settings-section">
                <h6><strong>Authentication & Access Control</strong></h6>
                <p class="mt-3"><span class="toggle-switch active"></span> Require 2FA for all administrative accounts</p>
                <p><span class="toggle-switch active"></span> Enforce complex password requirements</p>
                <label class="form-label">Maximum Login Attempts</label>
                <input type="number" class="form-control w-50" value="5">
              </div>

              <div class="col-md-6 settings-section">
                <h6><strong>Password Policy</strong></h6>
                <p class="mt-3"><span class="toggle-switch active"></span> Password must contain at least one uppercase letter</p>
                <p><span class="toggle-switch active"></span> Password must contain at least one numeric character</p>
                <label class="form-label">Minimum Password Length</label>
                <input type="number" class="form-control w-50" value="8">
              </div>
            </div>
          </div>

          <!-- Performance Settings -->
          <div class="tab-pane fade" id="performance">
            <div class="row">
              <div class="col-md-6 settings-section">
                <h6><strong>System Performance</strong></h6>
                <label class="form-label mt-2">Memory Usage Threshold (%)</label>
                <input type="number" class="form-control mb-2" value="85">
                <label class="form-label">CPU Usage Threshold (%)</label>
                <input type="number" class="form-control mb-2" value="80">
                <label class="form-label">Disk Usage Threshold (%)</label>
                <input type="number" class="form-control" value="90">
              </div>

              <div class="col-md-6 settings-section">
                <h6><strong>Database Performance</strong></h6>
                <p class="mt-3"><span class="toggle-switch active"></span> Enable automatic query optimization</p>
                <p><span class="toggle-switch active"></span> Use connection pooling for better performance</p>
                <label class="form-label">Cache Size (MB)</label>
                <input type="number" class="form-control w-50" value="512">
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Toggle switch interactivity
  document.querySelectorAll('.toggle-switch').forEach(toggle => {
    toggle.addEventListener('click', () => toggle.classList.toggle('active'));
  });

  function saveAllSettings() {
    // Collect all form values
    const settings = {
      companyName: document.querySelector('input[value*="WeBuild"]')?.value,
      timezone: document.querySelector('select')?.value,
      // Add more settings as needed
    };
    
    if (confirm('Save all current settings?')) {
      alert('Settings saved successfully!\n\nAll configuration changes have been applied.');
      // In production: $.ajax({ url: '<?= base_url('admin/save-settings') ?>', method: 'POST', data: settings ...
    }
  }

  function resetToDefaults() {
    if (confirm('⚠️ Reset all settings to default values?\n\nThis will undo all custom configurations.')) {
      if (confirm('Are you absolutely sure? This action cannot be undone.')) {
        alert('Settings reset to factory defaults.\n\nPlease review all configurations and save when ready.');
        location.reload();
      }
    }
  }
</script>
</body>
</html>
