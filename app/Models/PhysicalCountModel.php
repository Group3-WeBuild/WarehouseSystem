<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * PHYSICAL COUNT MODEL
 * =====================================================
 * 
 * Purpose: Manages physical inventory count sessions
 * Supports reconciliation workflow for auditors
 * 
 * RUBRIC: Inventory Auditor Module (Midterm)
 * "Inventory Auditor: Conducts regular checks and
 *  reconciliations of physical vs. system records"
 * =====================================================
 */
class PhysicalCountModel extends Model
{
    protected $table = 'physical_counts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'count_number',
        'warehouse_id',
        'initiated_by',
        'count_type',
        'status',
        'count_start_date',
        'count_end_date',
        'total_items_counted',
        'total_discrepancies',
        'approved_by',
        'approved_date',
        'notes'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all counts with warehouse details
     */
    public function getAllCountsWithDetails()
    {
        return $this->select('physical_counts.*, warehouses.warehouse_name,
                            u1.name as initiated_by_name, u2.name as approved_by_name')
                    ->join('warehouses', 'warehouses.id = physical_counts.warehouse_id')
                    ->join('users as u1', 'u1.id = physical_counts.initiated_by')
                    ->join('users as u2', 'u2.id = physical_counts.approved_by', 'left')
                    ->orderBy('physical_counts.count_start_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get counts in progress
     */
    public function getCountsInProgress()
    {
        return $this->where('status', 'In Progress')
                    ->orderBy('count_start_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get counts with discrepancies awaiting review
     */
    public function getCountsWithDiscrepancies()
    {
        return $this->where('status', 'Discrepancies')
                    ->where('total_discrepancies >', 0)
                    ->orderBy('count_end_date', 'DESC')
                    ->findAll();
    }

    /**
     * Generate count number
     */
    public function generateCountNumber()
    {
        $year = date('Y');
        $month = date('m');
        
        $lastCount = $this->like('count_number', "CNT-{$year}{$month}", 'after')
                          ->orderBy('id', 'DESC')
                          ->first();
        
        if ($lastCount) {
            $lastNumber = (int)substr($lastCount['count_number'], -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "CNT-{$year}{$month}-{$newNumber}";
    }

    /**
     * Start new count session
     */
    public function startCountSession($warehouseId, $userId, $countType = 'Full Count')
    {
        return $this->insert([
            'count_number' => $this->generateCountNumber(),
            'warehouse_id' => $warehouseId,
            'initiated_by' => $userId,
            'count_type' => $countType,
            'status' => 'In Progress',
            'count_start_date' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Complete count session
     */
    public function completeCountSession($countId, $totalItems, $totalDiscrepancies)
    {
        $status = $totalDiscrepancies > 0 ? 'Discrepancies' : 'Completed';
        
        return $this->update($countId, [
            'status' => $status,
            'count_end_date' => date('Y-m-d H:i:s'),
            'total_items_counted' => $totalItems,
            'total_discrepancies' => $totalDiscrepancies
        ]);
    }

    /**
     * Approve count session
     */
    public function approveCountSession($countId, $approvedBy)
    {
        return $this->update($countId, [
            'status' => 'Verified',
            'approved_by' => $approvedBy,
            'approved_date' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get count summary statistics
     */
    public function getCountStatistics()
    {
        return [
            'total_counts' => $this->countAllResults(),
            'in_progress' => $this->where('status', 'In Progress')->countAllResults(false),
            'with_discrepancies' => $this->where('status', 'Discrepancies')->countAllResults(false),
            'completed' => $this->where('status', 'Verified')->countAllResults()
        ];
    }
}
