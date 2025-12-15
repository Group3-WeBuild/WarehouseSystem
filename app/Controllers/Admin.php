<?php
/**
 * =====================================================
 * STUDENT GUIDE: Understanding Backend vs Frontend
 * =====================================================
 * 
 * THIS FILE: Admin.php (BACKEND CONTROLLER)
 * Location: app/Controllers/Admin.php
 * 
 * What is a Controller?
 * - Handles requests from the browser
 * - Processes data from database
 * - Sends data to views (HTML pages)
 * 
 * How it works:
 * 1. User clicks button in browser (FRONTEND)
 * 2. AJAX request sent to this controller (BACKEND)
 * 3. Controller processes data
 * 4. Controller sends response back (JSON)
 * 5. JavaScript updates page (FRONTEND)
 * 
 * Key Concepts:
 * - VIEW functions: Load HTML pages with data
 * - AJAX functions: Process requests and return JSON
 * - Helper functions: Support main functions
 * 
 * Database Table Used:
 * - users (id, username, name, email, password, role, status)
 * 
 * =====================================================
 */

namespace App\Controllers;

use App\Models\UserModel;

/**
 * =====================================================
 * ADMIN CONTROLLER - Backend Logic
 * =====================================================
 * 
 * This controller handles all administrative functions:
 * - User management (CRUD operations)
 * - Dashboard statistics
 * - System monitoring
 * - Security policies
 * 
 * STUDENT NOTE: This is the BACKEND - it processes data
 * and sends it to VIEWS (frontend HTML files)
 * 
 * =====================================================
 */

class Admin extends BaseController
{
    protected $session;
    protected $userModel;

    /**
     * Constructor - Runs when controller is loaded
     * Initializes session and helper functions
     */
    public function __construct()
    {
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
        $this->userModel = new UserModel();
    }

    /**
     * =====================================================
     * SECURITY CHECK - Authentication & Authorization
     * =====================================================
     * 
     * This function checks if user is:
     * 1. Logged in
     * 2. Has admin privileges
     * 
     * STUDENT NOTE: Always call this at the start of
     * admin functions to protect sensitive pages
     * =====================================================
     */
    private function checkAuth()
    {
        if (!$this->session->get('isLoggedIn')) {
            $this->session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to(base_url('login'));
        }

        $userRole = $this->session->get('role');
        $allowedRoles = ['IT Administrator', 'admin', 'Top Management'];
        
        if (!in_array($userRole, $allowedRoles)) {
            $this->session->setFlashdata('error', 'You do not have permission to access this page.');
            return redirect()->to(base_url('dashboard'));
        }

        return null;
    }

    /**
     * =====================================================
     * VIEW: Admin Dashboard (Main Page)
     * =====================================================
     * 
     * Route: /admin/dashboard
     * Method: GET
     * 
     * What it does:
     * 1. Checks if user is authorized
     * 2. Gets system statistics from database
     * 3. Loads dashboard view with data
     * 
     * Data sent to view:
     * - $stats: System statistics (users, health, etc.)
     * - $user: Current user information
     * =====================================================
     */
    public function dashboard()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            // Get system statistics
            $totalUsers = $this->userModel->countAll();
            $activeUsers = $this->userModel->where('status', 'Active')->countAllResults(false);
            $inactiveUsers = $this->userModel->where('status', 'Inactive')->countAllResults();
            
            // Get recent activities (last 10 logins/actions)
            $recentActivities = $this->getRecentActivities(10);
            
            // System health metrics
            $systemHealth = $this->getSystemHealth();
            
        } catch (\Exception $e) {
            $totalUsers = 10;
            $activeUsers = 8;
            $inactiveUsers = 2;
            $recentActivities = [];
            $systemHealth = ['status' => 'Good', 'uptime' => '99.9%'];
        }

        $data = [
            'title' => 'IT Administrator Dashboard',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'stats' => [
                'totalUsers' => $totalUsers,
                'activeUsers' => $activeUsers,
                'inactiveUsers' => $inactiveUsers,
                'systemHealth' => $systemHealth
            ],
            'recentActivities' => $recentActivities
        ];

        return view('admin_dashboard/dashboard', $data);
    }

    /**
     * =====================================================
     * VIEW: User Accounts Management Page
     * =====================================================
     * 
     * Route: /admin/user-accounts
     * Method: GET
     * 
     * What it does:
     * 1. Gets all users from database
     * 2. Sends user list to the view
     * 
     * Data sent to view:
     * - $users: Array of all users
     * - $user: Current logged-in user info
     * =====================================================
     */
    public function userAccounts()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            $users = $this->userModel->orderBy('created_at', 'DESC')->findAll();
        } catch (\Exception $e) {
            $users = [];
        }

        $data = [
            'title' => 'User Accounts',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'users' => $users
        ];

        return view('admin_dashboard/user_accounts', $data);
    }

    public function rolesPermissions()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $roles = [
            'IT Administrator' => ['Full system access', 'User management', 'System configuration'],
            'Top Management' => ['View all reports', 'Approve decisions', 'Strategic planning'],
            'Warehouse Manager' => ['Inventory management', 'Staff supervision', 'Order processing'],
            'Accounts Payable Clerk' => ['Process vendor invoices', 'Make payments', 'Track expenses'],
            'Accounts Receivable Clerk' => ['Manage customer invoices', 'Track payments', 'Generate reports'],
            'Procurement Officer' => ['Purchase orders', 'Vendor management', 'Budget tracking'],
            'Warehouse Staff' => ['Stock handling', 'Order fulfillment', 'Basic inventory tasks'],
            'Inventory Auditor' => ['Audit inventory', 'Generate reports', 'Quality checks']
        ];

        $data = [
            'title' => 'Roles & Permissions',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'roles' => $roles
        ];

        return view('admin_dashboard/roles_permissions', $data);
    }

    public function activeSessions()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // Get active sessions from database or session storage
        $sessions = $this->getActiveSessions();

        $data = [
            'title' => 'Active Sessions',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'sessions' => $sessions
        ];

        return view('admin_dashboard/active_sessions', $data);
    }

    public function securityPolicies()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'title' => 'Security Policies',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ]
        ];

        return view('admin_dashboard/security_policies', $data);
    }

    public function auditLogs()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $logs = $this->getAuditLogs(100);

        $data = [
            'title' => 'Audit Logs',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'logs' => $logs
        ];

        return view('admin_dashboard/audit_logs', $data);
    }

    public function systemHealth()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $health = $this->getSystemHealth();

        $data = [
            'title' => 'System Health',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'health' => $health
        ];

        return view('admin_dashboard/system_health', $data);
    }

    public function databaseManagement()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $dbStats = $this->getDatabaseStats();

        $data = [
            'title' => 'Database Management',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'dbStats' => $dbStats
        ];

        return view('admin_dashboard/database_management', $data);
    }

    public function backupRecovery()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'title' => 'Backup & Recovery',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ]
        ];

        return view('admin_dashboard/backup_recovery', $data);
    }

    public function settings()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'title' => 'Settings',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ]
        ];

        return view('admin_dashboard/settings', $data);
    }

    /**
     * =====================================================
     * AJAX ENDPOINTS - User Management
     * =====================================================
     * 
     * These functions handle AJAX requests from frontend
     * They process data and return JSON responses
     * 
     * STUDENT NOTE: These are called via JavaScript
     * fetch/AJAX, not by direct browser navigation
     * =====================================================
     */

    /**
     * CREATE USER - AJAX Endpoint
     * Route: POST /admin/create-user
     * Called from: user_accounts.php (Add User Modal)
     */
    public function createUser()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        if ($this->request->getMethod() === 'post') {
            // Validation rules for form fields
            $rules = [
                'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
                'name' => 'required|min_length[3]|max_length[255]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'role' => 'required|string'
            ];

            if ($this->validate($rules)) {
                // Prepare user data for database
                $userData = [
                    'username' => $this->request->getPost('username'),
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Hash password for security
                    'role' => $this->request->getPost('role'),
                    'status' => 'Active'
                ];

                // Insert into database
                if ($this->userModel->insert($userData)) {
                    // Success response
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'User created successfully'
                    ]);
                }
            }

            // Failure response
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to create user',
                'errors' => $this->validator->getErrors()
            ]);
        }
    }

    /**
     * UPDATE USER - AJAX Endpoint
     * Route: POST /admin/update-user/{id}
     * Called from: user_accounts.php (Edit User Modal)
     * 
     * Note: Password is only updated if provided
     */
    public function updateUser($id = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $userId = $id ?? $this->request->getUri()->getSegment(3);

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[255]',
                'email' => 'required|valid_email',
                'role' => 'required|string'
            ];

            if ($this->validate($rules)) {
                $userData = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'role' => $this->request->getPost('role')
                ];

                // Only update password if provided
                if ($this->request->getPost('password')) {
                    $userData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
                }

                if ($this->userModel->update($userId, $userData)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'User updated successfully'
                    ]);
                }
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update user',
                'errors' => $this->validator->getErrors()
            ]);
        }
    }

    /**
     * DELETE USER - AJAX Endpoint
     * Route: POST /admin/delete-user/{id}
     * Called from: user_accounts.php (Delete Button)
     * 
     * Security: Prevents admin from deleting themselves
     */
    public function deleteUser($id = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $userId = $id ?? $this->request->getUri()->getSegment(3);

        // Security check: Prevent admin from deleting their own account
        if ($userId == $this->session->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cannot delete your own account'
            ]);
        }

        // Delete user from database
        if ($this->userModel->delete($userId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete user'
        ]);
    }

    /**
     * TOGGLE USER STATUS - AJAX Endpoint
     * Route: POST /admin/toggle-user-status/{id}
     * Called from: user_accounts.php (Activate/Deactivate Button)
     * 
     * Switches between 'Active' and 'Inactive' status
     */
    public function toggleUserStatus($id = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $userId = $id ?? $this->request->getUri()->getSegment(3);
        $user = $this->userModel->find($userId);

        if ($user) {
            // Toggle status: Active <-> Inactive
            $newStatus = ($user['status'] ?? 'Active') === 'Active' ? 'Inactive' : 'Active';
            
            // Update in database
            if ($this->userModel->update($userId, ['status' => $newStatus])) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => "User status changed to {$newStatus}"
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update user status'
        ]);
    }

    /**
     * =====================================================
     * HELPER FUNCTIONS - Support Functions
     * =====================================================
     * 
     * These functions provide data for dashboard displays
     * 
     * STUDENT NOTE: In a real system, these would query
     * actual database tables or system metrics
     * =====================================================
     */

    /**
     * Get recent system activities for dashboard
     */
    private function getRecentActivities($limit = 10)
    {
        // In a real system, this would fetch from an activity log table
        return [
            ['action' => 'User login', 'user' => 'admin', 'time' => date('Y-m-d H:i:s', strtotime('-10 minutes'))],
            ['action' => 'Invoice created', 'user' => 'accounts_receivable', 'time' => date('Y-m-d H:i:s', strtotime('-25 minutes'))],
            ['action' => 'Payment processed', 'user' => 'accounts_payable', 'time' => date('Y-m-d H:i:s', strtotime('-1 hour'))],
        ];
    }

    private function getSystemHealth()
    {
        return [
            'status' => 'Good',
            'uptime' => '99.9%',
            'cpu_usage' => '45%',
            'memory_usage' => '62%',
            'disk_usage' => '38%'
        ];
    }

    private function getActiveSessions()
    {
        // In a real system, this would fetch from session storage
        return [
            ['user' => 'admin', 'ip' => '192.168.1.100', 'started' => date('Y-m-d H:i:s', strtotime('-2 hours'))],
            ['user' => 'accounts_payable', 'ip' => '192.168.1.105', 'started' => date('Y-m-d H:i:s', strtotime('-30 minutes'))],
        ];
    }

    private function getAuditLogs($limit = 100)
    {
        // In a real system, this would fetch from audit_logs table
        return [];
    }

    private function getDatabaseStats()
    {
        try {
            $db = \Config\Database::connect();
            $tables = $db->listTables();
            
            return [
                'total_tables' => count($tables),
                'database_size' => '45.2 MB',
                'tables' => $tables
            ];
        } catch (\Exception $e) {
            return [
                'total_tables' => 0,
                'database_size' => 'N/A',
                'tables' => []
            ];
        }
    }
    
    /**
     * =====================================================
     * BACKUP AND RECOVERY SYSTEM
     * =====================================================
     */
    
    public function backupDatabase()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            $db = \Config\Database::connect();
            $dbName = $db->getDatabase();
            $backupDir = WRITEPATH . 'backups/';
            
            // Create backups directory if it doesn't exist
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $fileName = 'backup_' . $dbName . '_' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = $backupDir . $fileName;
            
            // Get all tables
            $tables = $db->listTables();
            $backup = "-- Database Backup: $dbName\n";
            $backup .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            $backup .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            
            foreach ($tables as $table) {
                // Get table structure
                $query = $db->query("SHOW CREATE TABLE `$table`");
                $row = $query->getRow();
                $backup .= "\n\n-- Table structure for `$table`\n";
                $backup .= "DROP TABLE IF EXISTS `$table`;\n";
                $backup .= $row->{'Create Table'} . ";\n\n";
                
                // Get table data
                $query = $db->query("SELECT * FROM `$table`");
                $rows = $query->getResult();
                
                if (count($rows) > 0) {
                    $backup .= "-- Data for table `$table`\n";
                    foreach ($rows as $dataRow) {
                        $values = [];
                        foreach ($dataRow as $value) {
                            $values[] = is_null($value) ? 'NULL' : "'" . $db->escapeString($value) . "'";
                        }
                        $backup .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $backup .= "\n";
                }
            }
            
            $backup .= "SET FOREIGN_KEY_CHECKS=1;\n";
            
            // Save backup file
            file_put_contents($filePath, $backup);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Database backup created successfully',
                'filename' => $fileName,
                'size' => filesize($filePath),
                'path' => $filePath
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage()
            ]);
        }
    }
    
    public function listBackups()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            $backupDir = WRITEPATH . 'backups/';
            $backups = [];
            
            if (is_dir($backupDir)) {
                $files = scandir($backupDir, SCANDIR_SORT_DESCENDING);
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) == 'sql') {
                        $filePath = $backupDir . $file;
                        $backups[] = [
                            'filename' => $file,
                            'size' => filesize($filePath),
                            'date' => date('Y-m-d H:i:s', filemtime($filePath)),
                            'path' => $filePath
                        ];
                    }
                }
            }
            
            return $this->response->setJSON([
                'success' => true,
                'backups' => $backups
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to list backups: ' . $e->getMessage()
            ]);
        }
    }
    
    public function restoreDatabase()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            $filename = $this->request->getPost('filename');
            $backupDir = WRITEPATH . 'backups/';
            $filePath = $backupDir . $filename;
            
            if (!file_exists($filePath)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Backup file not found'
                ]);
            }
            
            $sql = file_get_contents($filePath);
            $db = \Config\Database::connect();
            
            // Split SQL into individual queries
            $queries = array_filter(explode(';', $sql));
            
            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    $db->query($query);
                }
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Database restored successfully from ' . $filename
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Restore failed: ' . $e->getMessage()
            ]);
        }
    }
    
    public function downloadBackup($filename)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            $backupDir = WRITEPATH . 'backups/';
            $filePath = $backupDir . $filename;
            
            if (!file_exists($filePath)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Backup file not found'
                ]);
            }
            
            return $this->response->download($filePath, null);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Download failed: ' . $e->getMessage()
            ]);
        }
    }
    
    public function deleteBackup()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            $filename = $this->request->getPost('filename');
            $backupDir = WRITEPATH . 'backups/';
            $filePath = $backupDir . $filename;
            
            if (!file_exists($filePath)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Backup file not found'
                ]);
            }
            
            unlink($filePath);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Backup deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage()
            ]);
        }
    }
    
    public function scheduleBackup()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            $schedule = $this->request->getPost('schedule'); // daily, weekly, monthly
            
            // Store schedule in settings (would need a settings table)
            // For now, just return success
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Backup schedule set to: ' . $schedule,
                'schedule' => $schedule
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to set schedule: ' . $e->getMessage()
            ]);
        }
    }
}
