<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * AUDIT TRAIL MODEL
 * =====================================================
 * 
 * Purpose: Logs all system changes for compliance
 * Tracks WHO, WHAT, WHEN, WHERE, WHY
 * 
 * RUBRIC: Audit Trail (Midterm)
 * "Audit trail for all transactions to ensure 
 *  accountability"
 * 
 * Auto-insert via:
 * - Hooks (after every update/delete)
 * - Manual calls in controllers
 * =====================================================
 */
class AuditTrailModel extends Model
{
    protected $table = 'audit_trail';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'user_id',
        'username',
        'module',
        'controller',
        'action',
        'table_name',
        'record_id',
        'old_values',
        'new_values',
        'changes_summary',
        'description',
        'status',
        'ip_address',
        'user_agent',
        'request_method',
        'endpoint',
        'error_message',
        'execution_time_ms',
        'timestamp'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';

    protected $skipValidation = true;
    protected $cleanValidationRules = true;

    /**
     * Get audit logs for specific user
     */
    public function getAuditsByUser($userId, $limit = 100)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('timestamp', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get audit logs by module
     */
    public function getAuditsByModule($module, $limit = 100)
    {
        return $this->where('module', $module)
                    ->orderBy('timestamp', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get audit logs by action
     */
    public function getAuditsByAction($action, $limit = 100)
    {
        return $this->where('action', $action)
                    ->orderBy('timestamp', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get audit logs for specific record
     */
    public function getAuditsForRecord($tableNamem, $recordId)
    {
        return $this->where('table_name', $tableNamem)
                    ->where('record_id', $recordId)
                    ->orderBy('timestamp', 'DESC')
                    ->findAll();
    }

    /**
     * Get audit logs by date range
     */
    public function getAuditsByDateRange($startDate, $endDate, $limit = 500)
    {
        return $this->where('timestamp >=', $startDate)
                    ->where('timestamp <=', $endDate)
                    ->orderBy('timestamp', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get failed operations
     */
    public function getFailedOperations($limit = 100)
    {
        return $this->where('status', 'Failure')
                    ->orderBy('timestamp', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Log system action (helper method)
     */
    public static function logAction($data)
    {
        $auditModel = new self();
        
        // Ensure required fields
        $data['timestamp'] = $data['timestamp'] ?? date('Y-m-d H:i:s');
        $data['ip_address'] = $data['ip_address'] ?? ($_SERVER['REMOTE_ADDR'] ?? '');
        $data['request_method'] = $data['request_method'] ?? ($_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN');
        $data['user_agent'] = $data['user_agent'] ?? ($_SERVER['HTTP_USER_AGENT'] ?? '');
        
        return $auditModel->insert($data);
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        return [
            'total_actions' => $this->countAllResults(),
            'failed_actions' => $this->where('status', 'Failure')->countAllResults(false),
            'total_users' => $this->selectCount('DISTINCT user_id')->get()->getResultArray()[0]['user_id'] ?? 0,
            'total_modules' => $this->selectCount('DISTINCT module')->get()->getResultArray()[0]['module'] ?? 0
        ];
    }
}
