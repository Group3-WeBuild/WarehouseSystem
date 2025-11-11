<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * STOCK MOVEMENT MODEL - Database Interaction
 * =====================================================
 * 
 * This model handles stock movement tracking
 * Records all stock IN/OUT operations
 * 
 * DATABASE TABLE: stock_movements
 * =====================================================
 */

class StockMovementModel extends Model
{
    protected $table = 'stock_movements';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'item_id',
        'movement_type',
        'quantity',
        'reference_number',
        'user_id',
        'notes',
        'created_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = false;
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'item_id' => 'required|numeric',
        'movement_type' => 'required|in_list[Stock In,Stock Out,Adjustment,Transfer,Return]',
        'quantity' => 'required|numeric|greater_than[0]',
        'user_id' => 'required|numeric'
    ];

    protected $validationMessages = [
        'item_id' => [
            'required' => 'Item ID is required'
        ],
        'movement_type' => [
            'required' => 'Movement type is required',
            'in_list' => 'Invalid movement type'
        ],
        'quantity' => [
            'required' => 'Quantity is required',
            'greater_than' => 'Quantity must be greater than 0'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get movements by item
     */
    public function getMovementsByItem($itemId)
    {
        return $this->where('item_id', $itemId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get movements by date range
     */
    public function getMovementsByDateRange($startDate, $endDate)
    {
        return $this->where('created_at >=', $startDate)
                    ->where('created_at <=', $endDate)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get movements by type
     */
    public function getMovementsByType($type)
    {
        return $this->where('movement_type', $type)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
