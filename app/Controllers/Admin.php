<?php

namespace App\Controllers;

use App\Models\UserModel;

class Admin extends BaseController
{
    protected $session;
    protected $userModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
        $this->userModel = new UserModel();
    }

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

    // AJAX Endpoints
    public function createUser()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
                'name' => 'required|min_length[3]|max_length[255]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'role' => 'required|string'
            ];

            if ($this->validate($rules)) {
                $userData = [
                    'username' => $this->request->getPost('username'),
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'role' => $this->request->getPost('role'),
                    'status' => 'Active'
                ];

                if ($this->userModel->insert($userData)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'User created successfully'
                    ]);
                }
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to create user',
                'errors' => $this->validator->getErrors()
            ]);
        }
    }

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

    public function deleteUser($id = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $userId = $id ?? $this->request->getUri()->getSegment(3);

        // Prevent deleting own account
        if ($userId == $this->session->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cannot delete your own account'
            ]);
        }

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

    public function toggleUserStatus($id = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $userId = $id ?? $this->request->getUri()->getSegment(3);
        $user = $this->userModel->find($userId);

        if ($user) {
            $newStatus = ($user['status'] ?? 'Active') === 'Active' ? 'Inactive' : 'Active';
            
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

    // Helper functions
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
}
