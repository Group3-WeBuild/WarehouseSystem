<!-- 
=====================================================
STUDENT GUIDE: Admin Dashboard - Complete Flow
=====================================================

FILE PURPOSE:
This is the main admin dashboard VIEW (frontend/HTML)

WHAT IS A VIEW?
- Views are HTML files that users see in their browser
- They display data received from the Controller
- They contain forms and buttons that send data back

HOW DATA FLOWS (MVC Pattern):

1. USER CLICKS LINK
   Browser URL: http://localhost/admin/dashboard
   
2. ROUTES (app/Config/Routes.php)
   Matches URL to Controller:
   $routes->get('admin/dashboard', 'Admin::dashboard')
   
3. CONTROLLER (app/Controllers/Admin.php)
   Function: dashboard()
   - Checks if user is logged in
   - Gets data from database (via Model)
   - Prepares data array
   - Loads this VIEW file with data
   
4. VIEW (THIS FILE - dashboard.php)
   - Receives data from controller
   - Displays data using PHP: $variable
   - Shows HTML/CSS to user
   
5. USER INTERACTION (JavaScript/AJAX)
   - User clicks button
   - JavaScript sends AJAX request
   - Goes back to Controller (POST route)
   - Controller processes and responds
   - JavaScript updates page

BACKEND vs FRONTEND in this file:
- BACKEND: <?php ?> code that displays data
- FRONTEND: HTML, CSS, JavaScript

DATA AVAILABLE in this view:
- $stats: System statistics
- $user: Current logged-in user info
  
=====================================================
-->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IT Administrator Dashboard | WeBuild</title>
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

    .stat-box {
      background-color: #fff;
      border: none;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
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
  </style>
</head>

<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
      <h5>WeBuild</h5>
      <a href="<?= base_url('admin/dashboard') ?>" class="active">Dashboard</a>
      <a href="<?= base_url('admin/user-accounts') ?>">User Accounts</a>
      <a href="<?= base_url('admin/roles-permissions') ?>">Roles & Permissions</a>
      <a href="<?= base_url('admin/active-sessions') ?>">Active Sessions</a>
      <a href="<?= base_url('admin/security-policies') ?>">Security Policies</a>
      <a href="<?= base_url('admin/audit-logs') ?>">Audit Logs</a>
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
          <span class="me-3"><?= date('F d, Y | h:i A') ?> | <?= esc($user['role']) ?> | <strong><?= esc($user['username']) ?></strong></span>
          <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary btn-sm">Logout</a>
        </div>
      </div>

      <!-- Page Content -->
      <div class="page-container">
        <h5><strong>IT Administrator Dashboard</strong></h5>
        <p class="text-muted mb-4">Monitor and manage WITMS system security, users, and maintenance</p>

        <!-- Stats Boxes -->
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <div class="stat-box">
              <h4><?= $stats['activeUsers'] ?? 0 ?></h4>
              <p>Active Users</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4><?= $stats['systemHealth']['status'] ?? 'Good' ?></h4>
              <p>Security Status</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4><?= $stats['systemHealth']['uptime'] ?? '99.9%' ?></h4>
              <p>System Uptime</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4><?= date('Y-m-d') ?></h4>
              <p>Last Backup</p>
            </div>
          </div>
        </div>

       <!-- Quick Actions -->
<div class="mb-4">
  <h6><strong>Quick Actions</strong></h6>
  <div class="quick-actions mt-2 d-flex flex-wrap gap-2">
    <a href="<?= base_url('admin/user-accounts') ?>" class="btn btn-primary btn-sm">+ Add New User</a>
    <a href="<?= base_url('admin/active-sessions') ?>" class="btn btn-outline-secondary btn-sm">Active Sessions</a>
    <a href="<?= base_url('admin/audit-logs') ?>" class="btn btn-outline-secondary btn-sm">Audit Logs</a>
    <a href="<?= base_url('admin/backup-recovery') ?>" class="btn btn-outline-secondary btn-sm">Backup & Recovery</a>
  </div>
</div>


        <!-- Recent Activities -->
        <div>
          <h6><strong>Recent Activities</strong></h6>
          <table class="table table-bordered table-sm mt-2">
            <thead class="table-light">
              <tr>
                <th>Timestamp</th>
                <th>User</th>
                <th>Action</th>
                <th>Location</th>
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
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add New User Modal -->
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
