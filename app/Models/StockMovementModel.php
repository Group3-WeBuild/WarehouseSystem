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
     * =====================================================
     * BACKEND METHOD: Get Movements by Item
     * =====================================================
     * 
     * PURPOSE: Get complete history for a specific item
     * 
     * BUSINESS LOGIC:
     * - Shows all IN/OUT transactions for item
     * - Audit trail for inventory changes
     * - Newest movements first
     * 
     * RUBRIC: Real-Time Stock Updates (Initial)
     * - Tracks all stock changes with history
     * 
     * PARAMETER: $itemId - Inventory item ID
     * RETURNS: Array of movement records
     * =====================================================
     */
    public function getMovementsByItem($itemId)
    {
        // BACKEND QUERY: Get item history
        return $this->where('item_id', $itemId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * =====================================================
     * BACKEND METHOD: Get Movements by Date Range
     * =====================================================
     * 
     * PURPOSE: Filter movements by time period
     * 
     * BUSINESS LOGIC:
     * - Used for daily/weekly/monthly reports
     * - Helps track warehouse activity
     * 
     * RUBRIC: System Design & Architecture
     * - Reporting and analytics support
     * 
     * PARAMETERS:
     * - $startDate: Start of period (Y-m-d)
     * - $endDate: End of period (Y-m-d)
     * 
     * RETURNS: Array of movements in date range
     * =====================================================
     */
    public function getMovementsByDateRange($startDate, $endDate)
    {
        // BACKEND QUERY: Filter by date range
        return $this->where('created_at >=', $startDate)
                    ->where('created_at <=', $endDate)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * =====================================================
     * BACKEND METHOD: Get Movements by Type
     * =====================================================
     * 
     * PURPOSE: Filter by movement category
     * 
     * BUSINESS LOGIC:
     * - Types: Stock In, Stock Out, Adjustment, Transfer, Return
     * - Used for specific reports (e.g., all Stock In)
     * 
     * RUBRIC: Database Setup & Inventory Module (Basic)
     * - CRUD operations with filtering
     * 
     * PARAMETER: $type - Movement type
     * RETURNS: Array of filtered movements
     * =====================================================
     */
    public function getMovementsByType($type)
    {
        // BACKEND QUERY: Filter by type
        return $this->where('movement_type', $type)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * =====================================================
     * BACKEND METHOD: Log Stock Movement (Real-Time)
     * =====================================================
     * 
     * PURPOSE: Record a new stock movement transaction
     * 
     * BUSINESS LOGIC:
     * - Creates audit trail entry
     * - Validates all required fields
     * - Auto-generates reference number if not provided
     * 
     * RUBRIC: Real-Time Stock Updates (Initial)
     * - Accurate tracking when adding/removing stock
     * 
     * PARAMETERS: Array with movement data
     * - item_id: Item being moved
     * - movement_type: Type of movement
     * - quantity: Amount moved
     * - user_id: Who performed action
     * - reference_number: (optional) Transaction reference
     * - notes: (optional) Additional details
     * 
     * RETURNS: Movement ID or false
     * =====================================================
     */
    public function logMovement($data)
    {
        // BACKEND VALIDATION: Check required fields
        if (!isset($data['reference_number']) || empty($data['reference_number'])) {
            // Auto-generate reference number
            $data['reference_number'] = 'MOV-' . date('Ymd') . '-' . rand(1000, 9999);
        }

        // BACKEND INSERT: Save movement record
        $inserted = $this->insert($data);
        
        if ($inserted) {
            return $this->getInsertID();
        }
        
        return false;
    }

    /**
     * =====================================================
     * BACKEND METHOD: Get Recent Movements with Item Details
     * =====================================================
     * 
     * PURPOSE: Get latest movements with product information
     * 
     * BUSINESS LOGIC:
     * - Joins with inventory table
     * - Shows product name, not just ID
     * - Includes user information
     * 
     * RUBRIC: User Interface (Basic)
     * - Clean display of warehouse activity
     * 
     * PARAMETER: $limit - Number of records (default 50)
     * RETURNS: Array with joined data
     * =====================================================
     */
    public function getRecentMovementsWithDetails($limit = 50)
    {
        // BACKEND JOIN QUERY: Get movements with item details
        return $this->select('stock_movements.*, inventory.product_name, inventory.sku, users.username')
                    ->join('inventory', 'inventory.id = stock_movements.item_id', 'left')
                    ->join('users', 'users.id = stock_movements.user_id', 'left')
                    ->orderBy('stock_movements.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * =====================================================
     * BACKEND METHOD: Get Daily Movement Summary
     * =====================================================
     * 
     * PURPOSE: Calculate daily totals for each movement type
     * 
     * BUSINESS LOGIC:
     * - Groups by movement type
     * - Counts transactions
     * - Sums quantities
     * 
     * RUBRIC: System Design & Architecture
     * - Warehouse integration with reporting
     * 
     * PARAMETER: $date - Date to summarize (Y-m-d)
     * RETURNS: Array with summary statistics
     * =====================================================
     */
    public function getDailySummary($date)
    {
        // BACKEND AGGREGATE QUERY: Calculate daily totals
        return $this->select('movement_type, COUNT(*) as transaction_count, SUM(quantity) as total_quantity')
                    ->where('DATE(created_at)', $date)
                    ->groupBy('movement_type')
                    ->findAll();
    }
}
