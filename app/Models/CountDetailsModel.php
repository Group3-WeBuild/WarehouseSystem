<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * COUNT DETAILS MODEL
 * =====================================================
 * 
 * Purpose: Stores individual item counts during audit
 * Records discrepancies and verification status
 * 
 * RUBRIC: Inventory Auditor Module (Midterm)
 * =====================================================
 */
class CountDetailsModel extends Model
{
    protected $table = 'count_details';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'count_id',
        'inventory_id',
        'system_quantity',
        'physical_quantity',
        'discrepancy',
        'discrepancy_percentage',
        'has_discrepancy',
        'discrepancy_type',
        'counted_by',
        'verified_by',
        'verification_status',
        'resolution_status',
        'investigation_notes',
        'resolution_action',
        'resolved_date',
        'count_timestamp'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get details for a count session
     */
    public function getDetailsForCount($countId)
    {
        return $this->select('count_details.*, inventory.product_name, inventory.sku,
                            users.name as counted_by_name')
                    ->join('inventory', 'inventory.id = count_details.inventory_id')
                    ->join('users', 'users.id = count_details.counted_by')
                    ->where('count_details.count_id', $countId)
                    ->orderBy('count_details.count_timestamp', 'ASC')
                    ->findAll();
    }

    /**
     * Get items with discrepancies for a count
     */
    public function getDiscrepanciesForCount($countId)
    {
        return $this->where('count_id', $countId)
                    ->where('has_discrepancy', true)
                    ->findAll();
    }

    /**
     * Get pending verification items
     */
    public function getPendingVerification($countId)
    {
        return $this->where('count_id', $countId)
                    ->where('verification_status', 'Pending')
                    ->findAll();
    }

    /**
     * Record count for item
     */
    public function recordCount($countId, $inventoryId, $physicalQuantity, $countedBy)
    {
        // Get system quantity
        $inventoryModel = new InventoryModel();
        $item = $inventoryModel->find($inventoryId);
        $systemQty = $item['quantity'] ?? 0;
        
        // Calculate discrepancy
        $discrepancy = $physicalQuantity - $systemQty;
        $hasDiscrepancy = ($discrepancy != 0);
        $discrepancyType = 'None';
        if ($discrepancy > 0) $discrepancyType = 'Overage';
        if ($discrepancy < 0) $discrepancyType = 'Shortage';
        
        // Calculate percentage
        $discrepancyPct = 0;
        if ($systemQty > 0) {
            $discrepancyPct = round(($discrepancy / $systemQty) * 100, 2);
        }
        
        return $this->insert([
            'count_id' => $countId,
            'inventory_id' => $inventoryId,
            'system_quantity' => $systemQty,
            'physical_quantity' => $physicalQuantity,
            'discrepancy' => $discrepancy,
            'discrepancy_percentage' => $discrepancyPct,
            'has_discrepancy' => $hasDiscrepancy,
            'discrepancy_type' => $discrepancyType,
            'counted_by' => $countedBy,
            'verification_status' => 'Pending',
            'resolution_status' => $hasDiscrepancy ? 'Pending' : 'Not Required',
            'count_timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Verify count record
     */
    public function verifyCount($detailId, $verifiedBy)
    {
        return $this->update($detailId, [
            'verified_by' => $verifiedBy,
            'verification_status' => 'Verified'
        ]);
    }

    /**
     * Resolve discrepancy
     */
    public function resolveDiscrepancy($detailId, $action, $notes = '')
    {
        return $this->update($detailId, [
            'resolution_status' => 'Resolved',
            'resolution_action' => $action,
            'investigation_notes' => $notes,
            'resolved_date' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get discrepancy statistics
     */
    public function getDiscrepancyStats($countId)
    {
        $details = $this->where('count_id', $countId)->findAll();
        
        $overages = 0;
        $shortages = 0;
        $matched = 0;
        
        foreach ($details as $d) {
            if ($d['discrepancy_type'] === 'Overage') $overages++;
            elseif ($d['discrepancy_type'] === 'Shortage') $shortages++;
            else $matched++;
        }
        
        return [
            'total_counted' => count($details),
            'overages' => $overages,
            'shortages' => $shortages,
            'matched' => $matched,
            'accuracy_rate' => count($details) > 0 ? round(($matched / count($details)) * 100, 2) : 0
        ];
    }
}
