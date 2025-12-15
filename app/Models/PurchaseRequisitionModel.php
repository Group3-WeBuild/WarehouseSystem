<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * PURCHASE REQUISITION MODEL
 * =====================================================
 * 
 * Purpose: Manages purchase requisitions
 * Workflow: Draft → Submitted → Approved → Ordered → Received
 * 
 * RUBRIC: Procurement Officer Module
 * =====================================================
 */
class PurchaseRequisitionModel extends Model
{
    protected $table = 'purchase_requisitions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'requisition_number',
        'requested_by',
        'warehouse_id',
        'priority',
        'reason',
        'status',
        'approved_by',
        'approved_date',
        'required_date',
        'notes'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Generate requisition number
     */
    public function generateRequisitionNumber()
    {
        $year = date('Y');
        $month = date('m');
        
        $lastReq = $this->like('requisition_number', "REQ-{$year}{$month}", 'after')
                        ->orderBy('id', 'DESC')
                        ->first();
        
        if ($lastReq) {
            $lastNumber = (int)substr($lastReq['requisition_number'], -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "REQ-{$year}{$month}-{$newNumber}";
    }

    /**
     * Get all requisitions with details
     */
    public function getAllWithDetails()
    {
        return $this->select('purchase_requisitions.*, 
                            u1.name as requested_by_name,
                            u2.name as approved_by_name,
                            warehouses.warehouse_name')
                    ->join('users as u1', 'u1.id = purchase_requisitions.requested_by')
                    ->join('users as u2', 'u2.id = purchase_requisitions.approved_by', 'left')
                    ->join('warehouses', 'warehouses.id = purchase_requisitions.warehouse_id')
                    ->orderBy('purchase_requisitions.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get pending requisitions for approval
     */
    public function getPendingApproval()
    {
        return $this->where('status', 'Submitted')
                    ->orderBy('priority', 'DESC')
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get approved requisitions ready for PO
     */
    public function getApprovedForPO()
    {
        return $this->where('status', 'Approved')
                    ->findAll();
    }

    /**
     * Submit requisition
     */
    public function submitRequisition($id)
    {
        return $this->update($id, [
            'status' => 'Submitted'
        ]);
    }

    /**
     * Approve requisition
     */
    public function approveRequisition($id, $approvedBy)
    {
        return $this->update($id, [
            'status' => 'Approved',
            'approved_by' => $approvedBy,
            'approved_date' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Reject requisition
     */
    public function rejectRequisition($id, $approvedBy, $reason = '')
    {
        return $this->update($id, [
            'status' => 'Rejected',
            'approved_by' => $approvedBy,
            'approved_date' => date('Y-m-d H:i:s'),
            'notes' => $reason
        ]);
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        return [
            'total' => $this->countAllResults(),
            'draft' => $this->where('status', 'Draft')->countAllResults(false),
            'pending' => $this->where('status', 'Submitted')->countAllResults(false),
            'approved' => $this->where('status', 'Approved')->countAllResults(false),
            'ordered' => $this->where('status', 'Ordered')->countAllResults()
        ];
    }
}
