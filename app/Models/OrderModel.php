<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * ORDER MODEL - Database Interaction
 * =====================================================
 * 
 * This model handles order management
 * Tracks customer and supplier orders
 * 
 * DATABASE TABLE: orders
 * =====================================================
 */

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'order_number',
        'order_type',
        'customer_name',
        'customer_email',
        'customer_phone',
        'items',
        'total_amount',
        'status',
        'priority',
        'delivery_address',
        'delivery_date',
        'processed_by',
        'processed_at',
        'completed_at',
        'notes'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'order_number' => 'required|is_unique[orders.order_number,id,{id}]',
        'order_type' => 'required|in_list[Customer Order,Supplier Order,Internal Transfer]',
        'status' => 'permit_empty|in_list[Pending,Processing,Completed,Cancelled]'
    ];

    protected $validationMessages = [
        'order_number' => [
            'required' => 'Order number is required',
            'is_unique' => 'This order number already exists'
        ],
        'order_type' => [
            'required' => 'Order type is required'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get pending orders
     */
    public function getPendingOrders()
    {
        return $this->where('status', 'Pending')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get orders by status
     */
    public function getOrdersByStatus($status)
    {
        return $this->where('status', $status)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get orders by date range
     */
    public function getOrdersByDateRange($startDate, $endDate)
    {
        return $this->where('created_at >=', $startDate)
                    ->where('created_at <=', $endDate)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get high priority orders
     */
    public function getHighPriorityOrders()
    {
        return $this->where('priority', 'High')
                    ->whereIn('status', ['Pending', 'Processing'])
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Generate unique order number
     */
    public function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = date('Ymd');
        
        // Get last order number for today
        $lastOrder = $this->like('order_number', $prefix . $date)
                          ->orderBy('id', 'DESC')
                          ->first();
        
        if ($lastOrder) {
            // Extract sequence number and increment
            $lastSequence = (int)substr($lastOrder['order_number'], -4);
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }
        
        return $prefix . $date . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
    }
}
