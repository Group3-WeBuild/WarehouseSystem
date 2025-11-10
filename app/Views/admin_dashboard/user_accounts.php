<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Accounts | WeBuild</title>
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
      box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
    }

    .quick-actions button {
      margin-right: 10px;
      margin-bottom: 10px;
    }

    .table th, .table td {
      vertical-align: middle;
    }

    .badge {
      font-size: 0.8rem;
    }

    .filter-inputs select,
    .filter-inputs input {
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
      <a href="user_accounts.php" class="active">User Accounts</a>
      <a href="roles_permissions.php">Roles & Permissions</a>
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

        <!-- Page Content -->
        <div class="page-container">
          <h5><strong>User Management</strong></h5>
          <p class="text-muted mb-4">Manage user accounts, roles, and permissions across the WITMS system</p>

          <!-- Quick Actions -->
          <div class="mb-4 d-flex flex-wrap gap-2">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">+ Add New User</button>
            <button class="btn btn-secondary btn-sm">Export Users</button>
            <button class="btn btn-secondary btn-sm">Import Users</button>
          </div>

          <!-- Filters -->
          <div class="filter-section mb-3">
            <div class="filter-inputs d-flex flex-wrap align-items-center">
              <input type="text" class="form-control w-auto" placeholder="Search by name, email">
              <select class="form-select w-auto">
                <option>All Roles</option>
              </select>
              <select class="form-select w-auto">
                <option>All Status</option>
              </select>
              <select class="form-select w-auto">
                <option>All Location</option>
              </select>
              <button class="btn btn-outline-primary btn-sm">Apply Filters</button>
              <button class="btn btn-outline-secondary btn-sm">Clear Filters</button>
            </div>
          </div>

          <!-- User Accounts Table -->
          <div>
            <table class="table table-bordered table-sm mt-2">
              <thead class="table-light">
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Location</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>
                    <button class="btn btn-outline-info btn-sm">Edit</button>
                    <button class="btn btn-outline-danger btn-sm">Delete</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add User Modal -->
  <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-control" placeholder="">
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" placeholder="">
            </div>
            <div class="mb-3">
              <label class="form-label">Role</label>
              <select class="form-select">
                <option selected>Select Role</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Location Access</label>
              <select class="form-select">
                <option selected>All Locations</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Phone Number</label>
              <input type="text" class="form-control" placeholder="">
            </div>
            <div class="mb-3">
              <label class="form-label">Department</label>
              <input type="text" class="form-control" placeholder="">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">Create User</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
