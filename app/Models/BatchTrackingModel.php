<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * BATCH TRACKING MODEL
 * =====================================================
 * 
 * Purpose: Manages batch tracking for quality control
 * Tracks expiration dates, quality status, and batch history
 * 
 * RUBRIC: Batch and Lot Tracking (Midterm)
 * "Batch and lot tracking for quality control and
 *  expiration-sensitive items"
 * 
 * Key Methods:
 * - Create batch from purchase
 * - Track expiration
 * - Quality control status
 * - Batch allocation to orders
 * - Batch expiry alerts
 * =====================================================
 */
class BatchTrackingModel extends Model
{
    protected $table = 'batch_tracking';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'inventory_id',
        'batch_number',
        'reference_number',
        'manufacture_date',
        'expiry_date',
        'days_until_expiry',
        'quantity_received',
        'quantity_available',
        'quantity_used',
        'supplier_id',
        'warehouse_id',
        'quality_status',
        'quality_notes',
        'inspected_by',
        'inspection_date',
        'received_date',
        'received_by'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'inventory_id' => 'required|numeric',
        'batch_number' => 'required|is_unique[batch_tracking.batch_number]',
        'quantity_received' => 'required|numeric|greater_than[0]',
        'expiry_date' => 'valid_date[Y-m-d]'
    ];

    protected $validationMessages = [
        'batch_number' => ['is_unique' => 'This batch number already exists']
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * =====================================================
     * GET ALL ACTIVE BATCHES
     * =====================================================
     * 
     * Returns all batches with available quantity
     * =====================================================
     */
    public function getActiveBatches()
    {
        return $this->select('batch_tracking.*, inventory.product_name, inventory.sku, 
                            vendors.vendor_name, warehouses.warehouse_name')
                    ->join('inventory', 'inventory.id = batch_tracking.inventory_id')
                    ->join('vendors', 'vendors.id = batch_tracking.supplier_id', 'left')
                    ->join('warehouses', 'warehouses.id = batch_tracking.warehouse_id', 'left')
                    ->where('batch_tracking.quality_status', 'Active')
                    ->where('batch_tracking.quantity_available >', 0)
                    ->orderBy('batch_tracking.expiry_date', 'ASC')
                    ->findAll();
    }

    /**
     * =====================================================
     * GET BATCHES BY ITEM
     * =====================================================
     * 
     * Shows all batches for a specific inventory item
     * =====================================================
     */
    public function getBatchesByItem($inventoryId)
    {
        return $this->where('inventory_id', $inventoryId)
                    ->where('quality_status', 'Active')
                    ->where('quantity_available >', 0)
                    ->orderBy('expiry_date', 'ASC')
                    ->findAll();
    }

    /**
     * =====================================================
     * GET BATCHES EXPIRING SOON
     * =====================================================
     * 
     * Shows batches expiring within X days
     * =====================================================
     */
    public function getBatchesExpiringWithin($days = 30)
    {
        $futureDate = date('Y-m-d', strtotime("+{$days} days"));
        
        return $this->select('batch_tracking.*, inventory.product_name, inventory.sku,
                            DATEDIFF(batch_tracking.expiry_date, CURDATE()) as days_remaining')
                    ->join('inventory', 'inventory.id = batch_tracking.inventory_id')
                    ->where('batch_tracking.expiry_date <=', $futureDate)
                    ->where('batch_tracking.expiry_date >=', date('Y-m-d'))
                    ->where('batch_tracking.quality_status', 'Active')
                    ->where('batch_tracking.quantity_available >', 0)
                    ->orderBy('batch_tracking.expiry_date', 'ASC')
                    ->findAll();
    }

    /**
     * =====================================================
     * GET EXPIRED BATCHES
     * =====================================================
     * 
     * Shows all expired batches still in system
     * =====================================================
     */
    public function getExpiredBatches()
    {
        return $this->select('batch_tracking.*, inventory.product_name, inventory.sku')
                    ->join('inventory', 'inventory.id = batch_tracking.inventory_id')
                    ->where('batch_tracking.expiry_date <', date('Y-m-d'))
                    ->where('batch_tracking.quality_status !=', 'Expired')
                    ->findAll();
    }

    /**
     * =====================================================
     * GET BATCHES BY QUALITY STATUS
     * =====================================================
     * 
     * Filters batches by quality control status
     * =====================================================
     */
    public function getBatchesByStatus($status)
    {
        return $this->where('quality_status', $status)
                    ->findAll();
    }

    /**
     * =====================================================
     * GET QUARANTINED BATCHES
     * =====================================================
     * 
     * Shows batches pending quality approval
     * =====================================================
     */
    public function getQuarantinedBatches()
    {
        return $this->select('batch_tracking.*, inventory.product_name, 
                            users.name as inspected_by_name')
                    ->join('inventory', 'inventory.id = batch_tracking.inventory_id')
                    ->join('users', 'users.id = batch_tracking.inspected_by', 'left')
                    ->where('batch_tracking.quality_status', 'Quarantine')
                    ->findAll();
    }

    /**
     * =====================================================
     * ALLOCATE BATCH TO ORDER
     * =====================================================
     * 
     * Assigns batch to specific order
     * Updates quantity used
     * =====================================================
     */
    public function allocateBatchToOrder($batchId, $quantity)
    {
        $batch = $this->find($batchId);
        
        if (!$batch || $batch['quantity_available'] < $quantity) {
            return false;
        }
        
        return $this->update($batchId, [
            'quantity_used' => $batch['quantity_used'] + $quantity,
            'quantity_available' => $batch['quantity_available'] - $quantity
        ]);
    }

    /**
     * =====================================================
     * CALCULATE DAYS UNTIL EXPIRY
     * =====================================================
     * 
     * Updates days_until_expiry field
     * Called automatically or via job scheduler
     * =====================================================
     */
    public function calculateDaysUntilExpiry($batchId)
    {
        $batch = $this->find($batchId);
        
        if (!$batch || !$batch['expiry_date']) {
            return false;
        }
        
        $expiryDate = new \DateTime($batch['expiry_date']);
        $today = new \DateTime();
        $interval = $today->diff($expiryDate);
        $days = $interval->days;
        
        // Mark as expired if past expiry date
        $status = 'Active';
        if ($interval->invert === 1) {
            $status = 'Expired';
            $days = 0;
        }
        
        return $this->update($batchId, [
            'days_until_expiry' => $days,
            'quality_status' => $status
        ]);
    }

    /**
     * =====================================================
     * APPROVE BATCH AFTER INSPECTION
     * =====================================================
     * 
     * Updates quality status to Approved
     * Records inspection details
     * =====================================================
     */
    public function approveBatch($batchId, $inspectorId, $notes = '')
    {
        return $this->update($batchId, [
            'quality_status' => 'Approved',
            'inspected_by' => $inspectorId,
            'inspection_date' => date('Y-m-d H:i:s'),
            'quality_notes' => $notes
        ]);
    }

    /**
     * =====================================================
     * REJECT BATCH AFTER INSPECTION
     * =====================================================
     * 
     * Updates quality status to Rejected
     * Removes from available stock
     * =====================================================
     */
    public function rejectBatch($batchId, $inspectorId, $reason = '')
    {
        return $this->update($batchId, [
            'quality_status' => 'Rejected',
            'inspected_by' => $inspectorId,
            'inspection_date' => date('Y-m-d H:i:s'),
            'quality_notes' => "REJECTED: {$reason}",
            'quantity_available' => 0
        ]);
    }

    /**
     * =====================================================
     * GET BATCH STATISTICS
     * =====================================================
     * 
     * Returns overall batch health metrics
     * =====================================================
     */
    public function getBatchStatistics()
    {
        return [
            'total_active_batches' => $this->where('quality_status', 'Active')->countAllResults(),
            'total_quarantined' => $this->where('quality_status', 'Quarantine')->countAllResults(),
            'total_rejected' => $this->where('quality_status', 'Rejected')->countAllResults(),
            'batches_expiring_30days' => count($this->getBatchesExpiringWithin(30)),
            'expired_batches' => count($this->getExpiredBatches()),
            'total_available_quantity' => $this->selectSum('quantity_available')->get()->getRowArray()['quantity_available'] ?? 0
        ];
    }

    /**
     * =====================================================
     * GENERATE BATCH NUMBER
     * =====================================================
     * 
     * Auto-generates unique batch number
     * Format: BTH-YYYYMMDD-XXXX
     * =====================================================
     */
    public function generateBatchNumber()
    {
        $today = date('Ymd');
        $lastBatch = $this->like('batch_number', "BTH-{$today}", 'after')
                          ->orderBy('id', 'DESC')
                          ->first();
        
        if ($lastBatch) {
            $lastNumber = (int)substr($lastBatch['batch_number'], -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "BTH-{$today}-{$newNumber}";
    }
}
