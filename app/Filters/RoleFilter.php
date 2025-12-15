<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\AuditService;

/**
 * =====================================================
 * ROLE AUTHORIZATION FILTER
 * =====================================================
 * 
 * Purpose: Enforces role-based access control (RBAC)
 * for all protected routes.
 * 
 * RUBRIC: Audit Trail & Security (Finals)
 * "Complete audit logs and robust role-based security"
 * =====================================================
 */
class RoleFilter implements FilterInterface
{
    /**
     * Role permissions mapping
     * Defines which roles can access which route groups
     */
    protected $rolePermissions = [
        // Warehouse Management (Manager level)
        'warehouse' => [
            'Warehouse Manager',
            'IT Administrator',
            'Top Management'
        ],
        
        // Warehouse Staff (Limited access)
        'warehouse-staff' => [
            'Warehouse Staff',
            'Warehouse Manager',
            'IT Administrator',
            'Top Management'
        ],
        
        // Inventory Auditing
        'inventory-auditor' => [
            'Inventory Auditor',
            'Warehouse Manager',
            'IT Administrator',
            'Top Management'
        ],
        
        // Procurement
        'procurement' => [
            'Procurement Officer',
            'Warehouse Manager',
            'IT Administrator',
            'Top Management'
        ],
        
        // Accounts Payable
        'accounts-payable' => [
            'Accounts Payable Clerk',
            'IT Administrator',
            'Top Management'
        ],
        
        // Accounts Receivable
        'accounts-receivable' => [
            'Accounts Receivable Clerk',
            'IT Administrator',
            'Top Management'
        ],
        
        // Management Dashboard
        'management' => [
            'Top Management',
            'IT Administrator',
            'CEO',
            'CFO',
            'COO'
        ],
        
        // Administration
        'admin' => [
            'IT Administrator',
            'admin'
        ],
        
        // Analytics
        'analytics' => [
            'Warehouse Manager',
            'Inventory Auditor',
            'Procurement Officer',
            'IT Administrator',
            'Top Management'
        ]
    ];

    /**
     * Before filter - check authorization
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            // Log unauthorized access attempt
            $this->logSecurityEvent('Unauthorized Access Attempt', 
                'Attempted to access protected resource without authentication');
            
            return redirect()->to(base_url('login'))
                ->with('error', 'Please login to access this page.');
        }

        // Get current user role
        $userRole = $session->get('role');
        
        // If no specific role check required
        if (empty($arguments)) {
            return;
        }

        // Check role permission
        $requiredGroup = $arguments[0] ?? null;
        
        if ($requiredGroup && isset($this->rolePermissions[$requiredGroup])) {
            $allowedRoles = $this->rolePermissions[$requiredGroup];
            
            if (!in_array($userRole, $allowedRoles)) {
                // Log access denied
                $this->logSecurityEvent('Access Denied',
                    "User '{$session->get('username')}' with role '{$userRole}' " .
                    "attempted to access '{$requiredGroup}' restricted area");
                
                log_message('error', "ACCESS DENIED - User '{$userRole}' tried to access '{$requiredGroup}'");
                
                // Redirect to unauthorized page instead of user dashboard to prevent loops
                return redirect()->to(base_url('login'))
                    ->with('error', 'You do not have permission to access this area. Please contact your administrator.');
            }
            
            log_message('debug', "ACCESS GRANTED - User '{$userRole}' can access '{$requiredGroup}'");
        }

        // Access granted - log if sensitive area
        if (in_array($requiredGroup, ['admin', 'management', 'accounts-payable', 'accounts-receivable'])) {
            $this->logSecurityEvent('Sensitive Area Access',
                "User '{$session->get('username')}' accessed '{$requiredGroup}' area", 'info');
        }
    }

    /**
     * After filter
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do after
    }

    /**
     * Log security events
     */
    protected function logSecurityEvent(string $event, string $details, string $severity = 'warning')
    {
        try {
            $audit = new AuditService();
            $audit->logSecurityEvent($event, $details, $severity);
        } catch (\Exception $e) {
            // Silently fail if audit logging fails
            log_message('error', 'Failed to log security event: ' . $e->getMessage());
        }
    }
}
