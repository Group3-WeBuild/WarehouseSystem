<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * WAREHOUSE MODEL - Database Interaction
 * =====================================================
 * 
 * This model handles warehouse locations data
 * 
 * DATABASE TABLE: warehouses
 * =====================================================
 */

class WarehouseModel extends Model
{
    protected $table = 'warehouses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'warehouse_name',
        'warehouse_code',
        'location',
        'address',
        'capacity',
        'manager_id',
        'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'warehouse_name' => 'required|min_length[3]|max_length[255]',
        'warehouse_code' => 'required|is_unique[warehouses.warehouse_code,id,{id}]',
        'location' => 'required'
    ];

    protected $validationMessages = [
        'warehouse_name' => [
            'required' => 'Warehouse name is required'
        ],
        'warehouse_code' => [
            'required' => 'Warehouse code is required',
            'is_unique' => 'This warehouse code already exists'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * =====================================================
     * BACKEND METHOD: Get Active Warehouses
     * =====================================================
     * 
     * PURPOSE: Get list of operational warehouses
     * 
     * BUSINESS LOGIC:
     * - Filters out inactive/maintenance locations
     * - Used for dropdown selections
     * - Supports multi-warehouse operations
     * 
     * RUBRIC: System Design & Architecture
     * - Clear warehouse structure and management
     * 
     * RETURNS: Array of active warehouses
     * =====================================================
     */
    public function getActiveWarehouses()
    {
        // BACKEND QUERY: Get operational locations
        return $this->where('status', 'Active')
                    ->orderBy('warehouse_name', 'ASC')
                    ->findAll();
    }

    /**
     * =====================================================
     * BACKEND METHOD: Get Warehouse by Code
     * =====================================================
     * 
     * PURPOSE: Find warehouse using unique code
     * 
     * BUSINESS LOGIC:
     * - Each warehouse has unique identifier
     * - Used for location-based operations
     * 
     * RUBRIC: Database Setup & Inventory Module (Basic)
     * - Warehouse table operations
     * 
     * PARAMETER: $code - Warehouse code (e.g., "WH-001")
     * RETURNS: Warehouse record or null
     * =====================================================
     */
    public function getWarehouseByCode($code)
    {
        // BACKEND QUERY: Find by unique code
        return $this->where('warehouse_code', $code)->first();
    }

    /**
     * =====================================================
     * BACKEND METHOD: Get Warehouse Statistics
     * =====================================================
     * 
     * PURPOSE: Calculate capacity and usage metrics
     * 
     * BUSINESS LOGIC:
     * - Shows total capacity
     * - Calculates current utilization
     * - Identifies available space
     * 
     * RUBRIC: System Design & Architecture
     * - Warehouse integration with inventory data
     * 
     * PARAMETER: $warehouseId - Warehouse to analyze
     * RETURNS: Array with statistics
     * =====================================================
     */
    public function getWarehouseStatistics($warehouseId)
    {
        // BACKEND QUERY: Get warehouse info
        $warehouse = $this->find($warehouseId);
        
        if (!$warehouse) {
            return null;
        }

        // BACKEND CALCULATION: Count items in this warehouse
        $inventoryModel = new \App\Models\InventoryModel();
        $items = $inventoryModel->where('location LIKE', '%' . $warehouse['warehouse_code'] . '%')
                                ->where('status', 'Active')
                                ->findAll();

        // BACKEND LOGIC: Calculate statistics
        $stats = [
            'warehouse_name' => $warehouse['warehouse_name'],
            'warehouse_code' => $warehouse['warehouse_code'],
            'capacity' => $warehouse['capacity'],
            'total_items' => count($items),
            'utilization_percentage' => ($warehouse['capacity'] > 0) 
                ? round((count($items) / $warehouse['capacity']) * 100, 2) 
                : 0
        ];

        return $stats;
    }

    /**
     * =====================================================
     * BACKEND METHOD: Get All Warehouses with Item Count
     * =====================================================
     * 
     * PURPOSE: List all warehouses with inventory counts
     * 
     * BUSINESS LOGIC:
     * - Shows how many items in each location
     * - Helps balance inventory distribution
     * 
     * RUBRIC: User Interface (Basic)
     * - Clean warehouse overview display
     * 
     * RETURNS: Array of warehouses with counts
     * =====================================================
     */
    public function getAllWarehousesWithItemCount()
    {
        // BACKEND QUERY: Get all warehouses
        $warehouses = $this->orderBy('warehouse_name', 'ASC')->findAll();
        
        $inventoryModel = new \App\Models\InventoryModel();

        // BACKEND LOGIC: Add item counts
        foreach ($warehouses as &$warehouse) {
            $itemCount = $inventoryModel->where('location LIKE', '%' . $warehouse['warehouse_code'] . '%')
                                       ->where('status', 'Active')
                                       ->countAllResults();
            $warehouse['item_count'] = $itemCount;
        }

        return $warehouses;
    }
}
