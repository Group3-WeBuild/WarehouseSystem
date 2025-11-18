<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * INVENTORY MODEL - Database Interaction
 * =====================================================
 * 
 * This model handles all inventory table operations
 * 
 * DATABASE TABLE: inventory
 * 
 * STUDENT NOTE: Models talk directly to database
 * Controllers use models to get/save data
 * =====================================================
 */

class InventoryModel extends Model
{
    protected $table = 'inventory';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    // Fields that can be inserted/updated
    protected $allowedFields = [
        'product_name',
        'sku',
        'category',
        'description',
        'quantity',
        'unit',
        'unit_price',
        'reorder_level',
        'location',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation rules
    protected $validationRules = [
        'product_name' => 'required|min_length[3]|max_length[255]',
        'sku' => 'required|is_unique[inventory.sku,id,{id}]',
        'category' => 'required',
        'quantity' => 'required|numeric',
        'unit' => 'required',
        'unit_price' => 'required|decimal',
        'reorder_level' => 'required|numeric'
    ];

    protected $validationMessages = [
        'product_name' => [
            'required' => 'Product name is required',
            'min_length' => 'Product name must be at least 3 characters'
        ],
        'sku' => [
            'required' => 'SKU is required',
            'is_unique' => 'This SKU already exists'
        ],
        'quantity' => [
            'required' => 'Quantity is required',
            'numeric' => 'Quantity must be a number'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * =====================================================
     * BACKEND METHOD: Get Low Stock Items
     * =====================================================
     * 
     * PURPOSE: Find inventory items that need reordering
     * 
     * BUSINESS LOGIC:
     * - Compares current quantity with reorder_level
     * - Returns items where stock is low or critical
     * - Only shows active items (not deleted/inactive)
     * 
     * RUBRIC: Real-Time Stock Updates (Initial)
     * - Provides alerts for low stock
     * 
     * RETURNS: Array of items needing restock
     * =====================================================
     */
    public function getLowStockItems()
    {
        // BACKEND QUERY: Find items at or below reorder level
        return $this->where('quantity <=', 'reorder_level', false)
                    ->where('status', 'Active')
                    ->orderBy('quantity', 'ASC') // Lowest stock first
                    ->findAll();
    }

    /**
     * =====================================================
     * BACKEND METHOD: Get Out of Stock Items
     * =====================================================
     * 
     * PURPOSE: Find items completely depleted
     * 
     * BUSINESS LOGIC:
     * - Critical alert for zero quantity items
     * - Prevents order fulfillment issues
     * 
     * RUBRIC: Database Setup & Inventory Module (Basic)
     * - Functional stock monitoring
     * 
     * RETURNS: Array of depleted items
     * =====================================================
     */
    public function getOutOfStockItems()
    {
        // BACKEND QUERY: Find items with zero quantity
        return $this->where('quantity', 0)
                    ->where('status', 'Active')
                    ->orderBy('product_name', 'ASC')
                    ->findAll();
    }

    /**
     * =====================================================
     * BACKEND METHOD: Get Items by Category
     * =====================================================
     * 
     * PURPOSE: Filter inventory by product category
     * 
     * BUSINESS LOGIC:
     * - Helps organize warehouse sections
     * - Supports category-based reporting
     * 
     * PARAMETER: $category - Category name (e.g., "Electronics")
     * 
     * RUBRIC: Database Setup & Inventory Module (Basic)
     * - CRUD operations with filtering
     * 
     * RETURNS: Array of items in specified category
     * =====================================================
     */
    public function getItemsByCategory($category)
    {
        // BACKEND QUERY: Filter by category
        return $this->where('category', $category)
                    ->where('status', 'Active')
                    ->orderBy('product_name', 'ASC')
                    ->findAll();
    }

    /**
     * =====================================================
     * BACKEND METHOD: Search Items
     * =====================================================
     * 
     * PURPOSE: Find items by keyword search
     * 
     * BUSINESS LOGIC:
     * - Searches in product name OR SKU
     * - Case-insensitive search
     * - Supports partial matches
     * 
     * PARAMETER: $keyword - Search term
     * 
     * RUBRIC: User Interface (Basic)
     * - Search functionality for warehouse staff
     * 
     * RETURNS: Array of matching items
     * =====================================================
     */
    public function searchItems($keyword)
    {
        // BACKEND QUERY: Search in multiple fields
        return $this->like('product_name', $keyword)
                    ->orLike('sku', $keyword)
                    ->where('status', 'Active')
                    ->orderBy('product_name', 'ASC')
                    ->findAll();
    }

    /**
     * =====================================================
     * BACKEND METHOD: Calculate Total Inventory Value
     * =====================================================
     * 
     * PURPOSE: Calculate total monetary value of stock
     * 
     * BUSINESS LOGIC:
     * - Formula: SUM(quantity × unit_price)
     * - Only counts active items
     * - Used for financial reporting
     * 
     * RUBRIC: System Design & Architecture
     * - Warehouse integration with financial data
     * 
     * RETURNS: Float - Total value in currency
     * =====================================================
     */
    public function getTotalInventoryValue()
    {
        // BACKEND CALCULATION: Get all active items
        $items = $this->where('status', 'Active')->findAll();
        $totalValue = 0;
        
        // BACKEND LOGIC: Loop through and calculate value
        foreach ($items as $item) {
            // Quantity × Price for each item
            $totalValue += ($item['quantity'] * $item['unit_price']);
        }
        
        return number_format($totalValue, 2, '.', '');
    }

    /**
     * =====================================================
     * BACKEND METHOD: Update Stock Quantity (Real-Time)
     * =====================================================
     * 
     * PURPOSE: Update item quantity with validation
     * 
     * BUSINESS LOGIC:
     * - Prevents negative stock
     * - Updates timestamp automatically
     * - Validates before saving
     * 
     * RUBRIC: Real-Time Stock Updates (Initial)
     * - Accurate and real-time updates
     * 
     * PARAMETERS:
     * - $itemId: Item to update
     * - $newQuantity: New quantity value
     * 
     * RETURNS: Boolean (success/failure)
     * =====================================================
     */
    public function updateStockQuantity($itemId, $newQuantity)
    {
        // BACKEND VALIDATION: Prevent negative stock
        if ($newQuantity < 0) {
            return false;
        }

        // BACKEND UPDATE: Save new quantity with timestamp
        return $this->update($itemId, [
            'quantity' => $newQuantity,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * =====================================================
     * BACKEND METHOD: Get All Active Items with Stats
     * =====================================================
     * 
     * PURPOSE: Get inventory list with computed statistics
     * 
     * BUSINESS LOGIC:
     * - Adds calculated fields to each item
     * - Shows total value per item
     * - Indicates stock status
     * 
     * RUBRIC: Database Setup & Inventory Module (Basic)
     * - Functional database with stock operations
     * 
     * RETURNS: Array with enhanced item data
     * =====================================================
     */
    public function getAllItemsWithStats()
    {
        // BACKEND QUERY: Get all active items
        $items = $this->where('status', 'Active')
                     ->orderBy('product_name', 'ASC')
                     ->findAll();

        // BACKEND LOGIC: Add computed fields
        foreach ($items as &$item) {
            // Calculate total value for this item
            $item['total_value'] = $item['quantity'] * $item['unit_price'];
            
            // Determine stock status
            if ($item['quantity'] == 0) {
                $item['stock_status'] = 'Out of Stock';
            } elseif ($item['quantity'] <= $item['reorder_level']) {
                $item['stock_status'] = 'Low Stock';
            } else {
                $item['stock_status'] = 'In Stock';
            }
        }

        return $items;
    }

    /**
     * =====================================================
     * BACKEND METHOD: Get Categories List
     * =====================================================
     * 
     * PURPOSE: Get unique list of all categories
     * 
     * BUSINESS LOGIC:
     * - Used for filter dropdowns
     * - Helps organize warehouse sections
     * 
     * RUBRIC: User Interface (Basic)
     * - Category filtering support
     * 
     * RETURNS: Array of unique categories
     * =====================================================
     */
    public function getCategories()
    {
        // BACKEND QUERY: Get distinct categories
        return $this->distinct()
                    ->select('category')
                    ->where('status', 'Active')
                    ->orderBy('category', 'ASC')
                    ->findAll();
    }
}
