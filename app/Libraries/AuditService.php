<?php

namespace App\Libraries;

use App\Models\AuditTrailModel;

/**
 * =====================================================
 * AUDIT SERVICE - Complete Activity Logging
 * =====================================================
 * 
 * Purpose: Provides centralized audit logging for all
 * system activities ensuring compliance and accountability.
 * 
 * Features:
 * - Automatic action logging
 * - User activity tracking
 * - Data change capture
 * - Security event logging
 * - Performance metrics
 * 
 * RUBRIC: Audit Trail & Security (Finals)
 * "Complete audit logs and robust role-based security"
 * =====================================================
 */
class AuditService
{
    protected $auditModel;
    protected $session;

    public function __construct()
    {
        $this->auditModel = new AuditTrailModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Log a general action
     */
    public function logAction(
        string $module,
        string $action,
        string $description,
        ?string $tableName = null,
        ?int $recordId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        string $status = 'success'
    ) {
        $request = \Config\Services::request();
        $startTime = defined('BOOT_TIME') ? BOOT_TIME : microtime(true);
        
        $data = [
            'user_id' => $this->session->get('user_id'),
            'username' => $this->session->get('username') ?? 'system',
            'module' => $module,
            'controller' => $request->getUri()->getSegment(1) ?? 'unknown',
            'action' => $action,
            'table_name' => $tableName ?? 'general',
            'record_id' => $recordId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'changes_summary' => $this->generateChangesSummary($oldValues, $newValues),
            'description' => $description,
            'status' => $status,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString(),
            'request_method' => $request->getMethod(),
            'endpoint' => current_url(),
            'execution_time_ms' => round((microtime(true) - $startTime) * 1000, 2),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        return $this->auditModel->insert($data);
    }

    /**
     * Log user authentication events
     */
    public function logAuth(string $action, string $username, bool $success, ?string $reason = null)
    {
        $request = \Config\Services::request();
        
        return $this->auditModel->insert([
            'user_id' => $this->session->get('user_id'),
            'username' => $username,
            'module' => 'Authentication',
            'controller' => 'Auth',
            'action' => $action,
            'description' => $success ? 
                "User '{$username}' {$action} successfully" : 
                "Failed {$action} attempt for '{$username}'" . ($reason ? ": {$reason}" : ''),
            'status' => $success ? 'success' : 'failed',
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString(),
            'request_method' => $request->getMethod(),
            'endpoint' => current_url(),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Log inventory changes
     */
    public function logInventoryChange(
        string $action,
        int $itemId,
        string $productName,
        ?array $oldValues = null,
        ?array $newValues = null
    ) {
        return $this->logAction(
            'Inventory',
            $action,
            "Inventory item '{$productName}' (ID: {$itemId}) - {$action}",
            'inventory',
            $itemId,
            $oldValues,
            $newValues
        );
    }

    /**
     * Log stock movements
     */
    public function logStockMovement(
        string $movementType,
        int $itemId,
        int $quantity,
        ?string $reference = null
    ) {
        return $this->logAction(
            'Stock Movement',
            $movementType,
            "Stock {$movementType}: {$quantity} units for item ID {$itemId}" . 
                ($reference ? " (Ref: {$reference})" : ''),
            'stock_movements',
            $itemId,
            null,
            ['quantity' => $quantity, 'reference' => $reference]
        );
    }

    /**
     * Log order activities
     */
    public function logOrderActivity(
        string $action,
        int $orderId,
        string $orderNumber,
        ?array $oldValues = null,
        ?array $newValues = null
    ) {
        return $this->logAction(
            'Orders',
            $action,
            "Order '{$orderNumber}' (ID: {$orderId}) - {$action}",
            'orders',
            $orderId,
            $oldValues,
            $newValues
        );
    }

    /**
     * Log financial transactions
     */
    public function logFinancialTransaction(
        string $module,
        string $action,
        float $amount,
        ?int $recordId = null,
        ?string $reference = null
    ) {
        return $this->logAction(
            $module,
            $action,
            "{$action} - Amount: PHP " . number_format($amount, 2) . 
                ($reference ? " (Ref: {$reference})" : ''),
            strtolower(str_replace(' ', '_', $module)),
            $recordId,
            null,
            ['amount' => $amount, 'reference' => $reference]
        );
    }

    /**
     * Log security events
     */
    public function logSecurityEvent(string $event, string $details, string $severity = 'info')
    {
        $request = \Config\Services::request();
        
        return $this->auditModel->insert([
            'user_id' => $this->session->get('user_id'),
            'username' => $this->session->get('username') ?? 'system',
            'module' => 'Security',
            'controller' => 'Security',
            'action' => $event,
            'description' => $details,
            'status' => $severity,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString(),
            'request_method' => $request->getMethod(),
            'endpoint' => current_url(),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Log data export/report generation
     */
    public function logReportGeneration(string $reportName, string $format, ?array $parameters = null)
    {
        return $this->logAction(
            'Reports',
            'generate',
            "Generated report: {$reportName} ({$format})",
            null,
            null,
            null,
            $parameters
        );
    }

    /**
     * Generate changes summary from old and new values
     */
    protected function generateChangesSummary(?array $oldValues, ?array $newValues): ?string
    {
        if (!$oldValues && !$newValues) {
            return null;
        }

        if (!$oldValues) {
            return 'New record created';
        }

        if (!$newValues) {
            return 'Record deleted';
        }

        $changes = [];
        foreach ($newValues as $key => $newValue) {
            $oldValue = $oldValues[$key] ?? null;
            if ($oldValue !== $newValue) {
                $changes[] = "{$key}: '{$oldValue}' → '{$newValue}'";
            }
        }

        return empty($changes) ? 'No changes detected' : implode('; ', $changes);
    }

    /**
     * Get audit trail for specific user
     */
    public function getUserActivity(int $userId, int $limit = 100)
    {
        return $this->auditModel->getAuditsByUser($userId, $limit);
    }

    /**
     * Get audit trail for specific module
     */
    public function getModuleActivity(string $module, int $limit = 100)
    {
        return $this->auditModel->getAuditsByModule($module, $limit);
    }

    /**
     * Get security events
     */
    public function getSecurityEvents(int $limit = 100)
    {
        return $this->auditModel->getAuditsByModule('Security', $limit);
    }

    /**
     * Get authentication history
     */
    public function getAuthHistory(int $limit = 100)
    {
        return $this->auditModel->getAuditsByModule('Authentication', $limit);
    }

    /**
     * Get recent activity summary
     */
    public function getRecentActivitySummary(int $hours = 24)
    {
        $db = \Config\Database::connect();
        
        $since = date('Y-m-d H:i:s', strtotime("-{$hours} hours"));
        
        return $db->table('audit_trail')
            ->select('module, action, COUNT(*) as count')
            ->where('timestamp >=', $since)
            ->groupBy('module, action')
            ->orderBy('count', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get audit statistics
     */
    public function getAuditStatistics(int $days = 30)
    {
        $db = \Config\Database::connect();
        $since = date('Y-m-d', strtotime("-{$days} days"));

        // Total actions
        $totalActions = $db->table('audit_trail')
            ->where('timestamp >=', $since)
            ->countAllResults();

        // By module
        $byModule = $db->table('audit_trail')
            ->select('module, COUNT(*) as count')
            ->where('timestamp >=', $since)
            ->groupBy('module')
            ->orderBy('count', 'DESC')
            ->get()
            ->getResultArray();

        // By status
        $byStatus = $db->table('audit_trail')
            ->select('status, COUNT(*) as count')
            ->where('timestamp >=', $since)
            ->groupBy('status')
            ->get()
            ->getResultArray();

        // Most active users
        $activeUsers = $db->table('audit_trail')
            ->select('username, COUNT(*) as action_count')
            ->where('timestamp >=', $since)
            ->where('username IS NOT NULL')
            ->groupBy('username')
            ->orderBy('action_count', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        return [
            'period_days' => $days,
            'total_actions' => $totalActions,
            'by_module' => $byModule,
            'by_status' => $byStatus,
            'most_active_users' => $activeUsers
        ];
    }
}
