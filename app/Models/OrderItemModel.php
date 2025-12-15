<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * ORDER ITEMS MODEL
 * =====================================================
 * 
 * Purpose: Manages normalized order line items
 * This replaces the JSON items column in orders table
 * Ensures proper database normalization (3NF)
 * =====================================================
 */
class OrderItemModel extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'order_id',
        'inventory_id',
        'quantity',
        'unit_price',
        'subtotal',
        'notes'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = null;
    protected $deletedField = null;

    protected $validationRules = [
        'order_id' => 'required|integer',
        'inventory_id' => 'required|integer',
        'quantity' => 'required|integer|greater_than[0]',
        'unit_price' => 'required|decimal',
        'subtotal' => 'required|decimal'
    ];

    protected $validationMessages = [
        'order_id' => [
            'required' => 'Order ID is required',
            'integer' => 'Order ID must be a valid number'
        ],
        'inventory_id' => [
            'required' => 'Inventory item is required',
            'integer' => 'Inventory ID must be a valid number'
        ],
        'quantity' => [
            'required' => 'Quantity is required',
            'integer' => 'Quantity must be a whole number',
            'greater_than' => 'Quantity must be greater than 0'
        ],
        'unit_price' => [
            'required' => 'Unit price is required',
            'decimal' => 'Unit price must be a valid amount'
        ],
        'subtotal' => [
            'required' => 'Subtotal is required',
            'decimal' => 'Subtotal must be a valid amount'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert = ['calculateSubtotal'];
    protected $beforeUpdate = ['calculateSubtotal'];

    /**
     * Calculate subtotal before insert/update
     */
    protected function calculateSubtotal(array $data)
    {
        if (isset($data['data']['quantity']) && isset($data['data']['unit_price'])) {
            $data['data']['subtotal'] = $data['data']['quantity'] * $data['data']['unit_price'];
        }
        return $data;
    }

    /**
     * Get order items with inventory details
     * 
     * @param int $orderId Order ID
     * @return array
     */
    public function getOrderItemsWithDetails($orderId)
    {
        return $this->select('order_items.*, inventory.product_name, inventory.sku, inventory.unit')
            ->join('inventory', 'inventory.id = order_items.inventory_id')
            ->where('order_items.order_id', $orderId)
            ->findAll();
    }

    /**
     * Get total for an order
     * 
     * @param int $orderId Order ID
     * @return float
     */
    public function getOrderTotal($orderId)
    {
        $result = $this->selectSum('subtotal')
            ->where('order_id', $orderId)
            ->first();
        
        return $result['subtotal'] ?? 0.00;
    }

    /**
     * Add multiple items to an order
     * 
     * @param int $orderId Order ID
     * @param array $items Array of items to add
     * @return bool
     */
    public function addOrderItems($orderId, $items)
    {
        $this->db->transStart();
        
        foreach ($items as $item) {
            $item['order_id'] = $orderId;
            $item['subtotal'] = $item['quantity'] * $item['unit_price'];
            $this->insert($item);
        }
        
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }

    /**
     * Delete all items for an order
     * 
     * @param int $orderId Order ID
     * @return bool
     */
    public function deleteOrderItems($orderId)
    {
        return $this->where('order_id', $orderId)->delete();
    }

    /**
     * Check if sufficient inventory exists for order item
     * 
     * @param int $inventoryId Inventory item ID
     * @param int $quantity Requested quantity
     * @return bool
     */
    public function checkInventoryAvailability($inventoryId, $quantity)
    {
        $inventoryModel = new \App\Models\InventoryModel();
        $item = $inventoryModel->find($inventoryId);
        
        if (!$item) {
            return false;
        }
        
        return $item['quantity'] >= $quantity;
    }
}
