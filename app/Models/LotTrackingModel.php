<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * LOT TRACKING MODEL
 * =====================================================
 * 
 * Purpose: Manages individual lots within batches
 * Tracks specific units, serials, and allocations
 * 
 * RUBRIC: Batch and Lot Tracking (Midterm)
 * =====================================================
 */
class LotTrackingModel extends Model
{
    protected $table = 'lot_tracking';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'batch_id',
        'lot_number',
        'serial_number',
        'quantity',
        'warehouse_id',
        'rack_location',
        'status',
        'is_allocated',
        'allocated_to_order_id',
        'allocated_date',
        'shipped_date',
        'notes'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get available lots for order allocation
     */
    public function getAvailableLots($batchId)
    {
        return $this->where('batch_id', $batchId)
                    ->where('status', 'Available')
                    ->where('is_allocated', false)
                    ->findAll();
    }

    /**
     * Get lots by warehouse
     */
    public function getLotsByWarehouse($warehouseId)
    {
        return $this->where('warehouse_id', $warehouseId)
                    ->where('status !=', 'Sold')
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Allocate lot to order
     */
    public function allocateToOrder($lotId, $orderId)
    {
        return $this->update($lotId, [
            'is_allocated' => true,
            'allocated_to_order_id' => $orderId,
            'allocated_date' => date('Y-m-d H:i:s'),
            'status' => 'Reserved'
        ]);
    }

    /**
     * Ship lot
     */
    public function shipLot($lotId)
    {
        return $this->update($lotId, [
            'status' => 'Sold',
            'shipped_date' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get lot statistics
     */
    public function getLotStatistics()
    {
        return [
            'total_lots' => $this->countAllResults(),
            'available' => $this->where('status', 'Available')->countAllResults(false),
            'reserved' => $this->where('status', 'Reserved')->countAllResults(false),
            'sold' => $this->where('status', 'Sold')->countAllResults(),
        ];
    }
}
