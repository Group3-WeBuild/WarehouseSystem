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
     * Get low stock items
     */
    public function getLowStockItems()
    {
        return $this->where('quantity <=', 'reorder_level', false)
                    ->where('status', 'Active')
                    ->findAll();
    }

    /**
     * Get out of stock items
     */
    public function getOutOfStockItems()
    {
        return $this->where('quantity', 0)
                    ->where('status', 'Active')
                    ->findAll();
    }

    /**
     * Get items by category
     */
    public function getItemsByCategory($category)
    {
        return $this->where('category', $category)
                    ->where('status', 'Active')
                    ->findAll();
    }

    /**
     * Search items by name or SKU
     */
    public function searchItems($keyword)
    {
        return $this->like('product_name', $keyword)
                    ->orLike('sku', $keyword)
                    ->where('status', 'Active')
                    ->findAll();
    }

    /**
     * Calculate total inventory value
     */
    public function getTotalInventoryValue()
    {
        $items = $this->where('status', 'Active')->findAll();
        $totalValue = 0;
        
        foreach ($items as $item) {
            $totalValue += $item['quantity'] * $item['unit_price'];
        }
        
        return $totalValue;
    }
}
