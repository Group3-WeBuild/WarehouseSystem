<!-- ============================================ -->
<!-- BACKEND INTEGRATION: START                 -->
<!-- This PHP code fetches data from the server -->
<!-- ============================================ -->
<?php
/**
 * User Accounts Management Page
 * 
 * BACKEND DATA PASSED FROM CONTROLLER:
 * - $user: Current logged-in user information (username, role, email)
 * - $users: Array of all users from the database
 * 
 * CONTROLLER: App\Controllers\Admin::userAccounts()
 * DATABASE TABLES: users
 */
?>
<!-- ============================================ -->
<!-- BACKEND INTEGRATION: END                   -->
<!-- ============================================ -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Accounts | WeBuild</title>
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
      <div class="col-md-2 px-0 sidebar">
        <div class="text-center py-4">
          <h5 class="text-white mb-1">WITMS</h5>
          <small class="text-white-50">System Administrator</small>
        </div>
        <nav class="nav flex-column">
          <a class="nav-link" href="<?= base_url('admin/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
          <a class="nav-link active" href="<?= base_url('admin/user-accounts') ?>"><i class="bi bi-people"></i> User Accounts</a>
          <a class="nav-link" href="<?= base_url('admin/roles-permissions') ?>"><i class="bi bi-shield-lock"></i> Roles & Permissions</a>
          <a class="nav-link" href="<?= base_url('admin/active-sessions') ?>"><i class="bi bi-person-check"></i> Active Sessions</a>
          <a class="nav-link" href="<?= base_url('admin/security-policies') ?>"><i class="bi bi-file-earmark-lock"></i> Security Policies</a>
          <a class="nav-link" href="<?= base_url('admin/audit-logs') ?>"><i class="bi bi-journal-text"></i> Audit Logs</a>
          <a class="nav-link" href="<?= base_url('admin/system-health') ?>"><i class="bi bi-heart-pulse"></i> System Health</a>
          <a class="nav-link" href="<?= base_url('admin/database-management') ?>"><i class="bi bi-database"></i> Database Management</a>
          <a class="nav-link" href="<?= base_url('admin/backup-recovery') ?>"><i class="bi bi-cloud-arrow-up"></i> Backup & Recovery</a>
          <a class="nav-link" href="<?= base_url('admin/settings') ?>"><i class="bi bi-gear"></i> Settings</a>
        </nav>
      </div>

      <!-- ============================================ -->
      <!-- FRONTEND STARTS HERE                       -->
      <!-- All content below uses static HTML + JS   -->
      <!-- ============================================ -->

      <!-- Main Content -->
      <div class="col-md-10 p-0">
        <!-- Top Bar -->
        <!-- BACKEND: Displays current user info from session -->
        <div class="topbar">
          <input type="text" class="form-control w-25" placeholder="Search">
          <div>
            <span class="me-3"><?= date('F d, Y | h:i A') ?> | <?= esc($user['role']) ?> | <strong><?= esc($user['username']) ?></strong></span>
            <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary btn-sm">Logout</a>
          </div>
        </div>

        <!-- Page Content -->
        <div class="page-container">
          <h5><strong>User Management</strong></h5>
          <p class="text-muted mb-4">Manage user accounts, roles, and permissions across the WITMS system</p>

          <!-- Quick Actions -->
          <div class="mb-4 d-flex flex-wrap gap-2">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">+ Add New User</button>
            <button class="btn btn-secondary btn-sm" onclick="exportUsers()">Export Users</button>
            <button class="btn btn-secondary btn-sm" onclick="alert('Import functionality coming soon')">Import Users</button>
          </div>

          <!-- Filters -->
          <div class="filter-section mb-3">
            <div class="filter-inputs d-flex flex-wrap align-items-center">
              <input type="text" id="searchInput" class="form-control w-auto" placeholder="Search by name, email">
              <select id="roleFilter" class="form-select w-auto">
                <option value="">All Roles</option>
                <option value="IT Administrator">IT Administrator</option>
                <option value="Top Management">Top Management</option>
                <option value="Warehouse Manager">Warehouse Manager</option>
                <option value="Accounts Payable Clerk">Accounts Payable Clerk</option>
                <option value="Accounts Receivable Clerk">Accounts Receivable Clerk</option>
                <option value="Procurement Officer">Procurement Officer</option>
                <option value="Warehouse Staff">Warehouse Staff</option>
                <option value="Inventory Auditor">Inventory Auditor</option>
              </select>
              <select id="statusFilter" class="form-select w-auto">
                <option value="">All Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
              <button class="btn btn-outline-primary btn-sm" onclick="applyFilters()">Apply Filters</button>
              <button class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">Clear Filters</button>
            </div>
          </div>

          <!-- ============================================ -->
          <!-- BACKEND DATA DISPLAY: User Accounts Table  -->
          <!-- Data comes from $users array (Controller)  -->
          <!-- ============================================ -->
          <!-- User Accounts Table -->
          <div>
            <table class="table table-bordered table-sm mt-2">
              <thead class="table-light">
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Username</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="usersTableBody">
                <?php if (!empty($users)): ?>
                  <?php foreach ($users as $userItem): ?>
                    <tr data-user-id="<?= $userItem['id'] ?>">
                      <td><?= esc($userItem['name']) ?></td>
                      <td><?= esc($userItem['email']) ?></td>
                      <td><?= esc($userItem['username']) ?></td>
                      <td><?= esc($userItem['role']) ?></td>
                      <td>
                        <span class="badge bg-<?= ($userItem['status'] ?? 'Active') === 'Active' ? 'success' : 'secondary' ?>">
                          <?= esc($userItem['status'] ?? 'Active') ?>
                        </span>
                      </td>
                      <td>
                        <button class="btn btn-outline-info btn-sm" onclick="editUser(<?= $userItem['id'] ?>)">Edit</button>
                        <button class="btn btn-outline-warning btn-sm" onclick="toggleStatus(<?= $userItem['id'] ?>)">
                          <?= ($userItem['status'] ?? 'Active') === 'Active' ? 'Deactivate' : 'Activate' ?>
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteUser(<?= $userItem['id'] ?>)">Delete</button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center text-muted">No users found</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
            <!-- ============================================ -->
            <!-- BACKEND DATA DISPLAY: END                  -->
            <!-- ============================================ -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ============================================ -->
  <!-- MODALS: Add & Edit User Forms              -->
  <!-- These forms submit to backend via AJAX     -->
  <!-- ============================================ -->
  
  <!-- Add User Modal -->
  <!-- BACKEND: Submits to /admin/create-user via AJAX -->
  <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addUserForm">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" name="password" required minlength="6">
            </div>
            <div class="mb-3">
              <label class="form-label">Role</label>
              <select class="form-select" name="role" required>
                <option value="">Select Role</option>
                <option value="IT Administrator">IT Administrator</option>
                <option value="Top Management">Top Management</option>
                <option value="Warehouse Manager">Warehouse Manager</option>
                <option value="Accounts Payable Clerk">Accounts Payable Clerk</option>
                <option value="Accounts Receivable Clerk">Accounts Receivable Clerk</option>
                <option value="Procurement Officer">Procurement Officer</option>
                <option value="Warehouse Staff">Warehouse Staff</option>
                <option value="Inventory Auditor">Inventory Auditor</option>
              </select>
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

  <!-- Edit User Modal -->
  <!-- BACKEND: Submits to /admin/update-user/{id} via AJAX -->
  <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editUserForm">
          <input type="hidden" id="editUserId" name="user_id">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-control" id="editName" name="name" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" id="editEmail" name="email" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Role</label>
              <select class="form-select" id="editRole" name="role" required>
                <option value="">Select Role</option>
                <option value="IT Administrator">IT Administrator</option>
                <option value="Top Management">Top Management</option>
                <option value="Warehouse Manager">Warehouse Manager</option>
                <option value="Accounts Payable Clerk">Accounts Payable Clerk</option>
                <option value="Accounts Receivable Clerk">Accounts Receivable Clerk</option>
                <option value="Procurement Officer">Procurement Officer</option>
                <option value="Warehouse Staff">Warehouse Staff</option>
                <option value="Inventory Auditor">Inventory Auditor</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">New Password (leave blank to keep current)</label>
              <input type="password" class="form-control" id="editPassword" name="password" minlength="6">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">Update User</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <!-- ============================================ -->
  <!-- JAVASCRIPT: AJAX & Frontend Interactions   -->
  <!-- These functions communicate with backend   -->
  <!-- ============================================ -->
  <script>
    // ==========================================
    // BACKEND AJAX: Add User Form Submission
    // Endpoint: POST /admin/create-user
    // Controller: Admin::createUser()
    // ==========================================
    $('#addUserForm').on('submit', function(e) {
      e.preventDefault();
      
      $.ajax({
        url: '<?= base_url('admin/create-user') ?>',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            alert(response.message);
            $('#addUserModal').modal('hide');
            location.reload();
          } else {
            alert(response.message || 'Failed to create user');
            if (response.errors) {
              console.log(response.errors);
            }
          }
        },
        error: function() {
          alert('An error occurred while creating the user');
        }
      });
    });

    // ==========================================
    // FRONTEND: Edit User Function
    // Populates modal with existing user data
    // ==========================================
    function editUser(userId) {
      // Find user data from table
      const row = $(`tr[data-user-id="${userId}"]`);
      const name = row.find('td:eq(0)').text();
      const email = row.find('td:eq(1)').text();
      const role = row.find('td:eq(3)').text();
      
      // Populate edit form
      $('#editUserId').val(userId);
      $('#editName').val(name);
      $('#editEmail').val(email);
      $('#editRole').val(role);
      $('#editPassword').val('');
      
      // Show modal
      $('#editUserModal').modal('show');
    }

    // ==========================================
    // BACKEND AJAX: Edit User Form Submission
    // Endpoint: POST /admin/update-user/{id}
    // Controller: Admin::updateUser()
    // ==========================================
    $('#editUserForm').on('submit', function(e) {
      e.preventDefault();
      
      const userId = $('#editUserId').val();
      
      $.ajax({
        url: '<?= base_url('admin/update-user/') ?>' + userId,
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            alert(response.message);
            $('#editUserModal').modal('hide');
            location.reload();
          } else {
            alert(response.message || 'Failed to update user');
            if (response.errors) {
              console.log(response.errors);
            }
          }
        },
        error: function() {
          alert('An error occurred while updating the user');
        }
      });
    });

    // ==========================================
    // BACKEND AJAX: Delete User Function
    // Endpoint: POST /admin/delete-user/{id}
    // Controller: Admin::deleteUser()
    // ==========================================
    function deleteUser(userId) {
      if (confirm('Are you sure you want to delete this user?')) {
        $.ajax({
          url: '<?= base_url('admin/delete-user/') ?>' + userId,
          method: 'POST',
          dataType: 'json',
          success: function(response) {
            if (response.success) {
              alert(response.message);
              location.reload();
            } else {
              alert(response.message || 'Failed to delete user');
            }
          },
          error: function() {
            alert('An error occurred while deleting the user');
          }
        });
      }
    }

    // ==========================================
    // BACKEND AJAX: Toggle User Status
    // Endpoint: POST /admin/toggle-user-status/{id}
    // Controller: Admin::toggleUserStatus()
    // Switches between Active/Inactive
    // ==========================================
    function toggleStatus(userId) {
      if (confirm('Are you sure you want to change this user\'s status?')) {
        $.ajax({
          url: '<?= base_url('admin/toggle-user-status/') ?>' + userId,
          method: 'POST',
          dataType: 'json',
          success: function(response) {
            if (response.success) {
              alert(response.message);
              location.reload();
            } else {
              alert(response.message || 'Failed to update status');
            }
          },
          error: function() {
            alert('An error occurred while updating the status');
          }
        });
      }
    }

    // ==========================================
    // FRONTEND: Filter Functions
    // These work on the client-side only
    // No backend calls required
    // ==========================================
    function applyFilters() {
      const searchTerm = $('#searchInput').val().toLowerCase();
      const roleFilter = $('#roleFilter').val();
      const statusFilter = $('#statusFilter').val();
      
      $('#usersTableBody tr').each(function() {
        const row = $(this);
        const name = row.find('td:eq(0)').text().toLowerCase();
        const email = row.find('td:eq(1)').text().toLowerCase();
        const role = row.find('td:eq(3)').text();
        const status = row.find('td:eq(4)').text().trim();
        
        let show = true;
        
        if (searchTerm && !name.includes(searchTerm) && !email.includes(searchTerm)) {
          show = false;
        }
        
        if (roleFilter && role !== roleFilter) {
          show = false;
        }
        
        if (statusFilter && status !== statusFilter) {
          show = false;
        }
        
        row.toggle(show);
      });
    }

    function clearFilters() {
      $('#searchInput').val('');
      $('#roleFilter').val('');
      $('#statusFilter').val('');
      $('#usersTableBody tr').show();
    }

    // ==========================================
    // FRONTEND: Export Users to CSV
    // Creates CSV file on client-side
    // No backend call needed
    // ==========================================
    function exportUsers() {
      // Create CSV content
      let csv = 'Name,Email,Username,Role,Status\n';
      
      $('#usersTableBody tr:visible').each(function() {
        const row = $(this);
        if (row.find('td').length > 1) {
          const name = row.find('td:eq(0)').text();
          const email = row.find('td:eq(1)').text();
          const username = row.find('td:eq(2)').text();
          const role = row.find('td:eq(3)').text();
          const status = row.find('td:eq(4)').text().trim();
          
          csv += `"${name}","${email}","${username}","${role}","${status}"\n`;
        }
      });
      
      // Download CSV
      const blob = new Blob([csv], { type: 'text/csv' });
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = 'users_export_' + new Date().getTime() + '.csv';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      window.URL.revokeObjectURL(url);
    }

    // ==========================================
    // FRONTEND: Real-time search as you type
    // ==========================================
    $('#searchInput').on('keyup', function() {
      applyFilters();
    });
    
    // ==========================================
    // END OF JAVASCRIPT FUNCTIONS
    // ==========================================
  </script>
</body>
</html>
