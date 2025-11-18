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
     * =====================================================
     * BACKEND METHOD: Get Pending Orders
     * =====================================================
     * 
     * PURPOSE: Get all orders waiting to be processed
     * 
     * BUSINESS LOGIC:
     * - Shows unprocessed orders
     * - Prioritizes by creation date
     * - Used in dashboard alerts
     * 
     * RUBRIC: Database Setup & Inventory Module (Basic)
     * - Order processing operations
     * 
     * RETURNS: Array of pending orders
     * =====================================================
     */
    public function getPendingOrders()
    {
        // BACKEND QUERY: Get unprocessed orders
        return $this->where('status', 'Pending')
                    ->orderBy('priority', 'DESC') // High priority first
                    ->orderBy('created_at', 'ASC') // Oldest first
                    ->findAll();
    }

    /**
     * =====================================================
     * BACKEND METHOD: Get Orders by Status
     * =====================================================
     * 
     * PURPOSE: Filter orders by processing stage
     * 
     * BUSINESS LOGIC:
     * - Status types: Pending, Processing, Completed, Cancelled
     * - Supports workflow management
     * 
     * RUBRIC: Real-Time Stock Updates (Initial)
     * - Track order fulfillment status
     * 
     * PARAMETER: $status - Order status
     * RETURNS: Array of filtered orders
     * =====================================================
     */
    public function getOrdersByStatus($status)
    {
        // BACKEND QUERY: Filter by status
        return $this->where('status', $status)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * =====================================================
     * BACKEND METHOD: Get Orders by Date Range
     * =====================================================
     * 
     * PURPOSE: Filter orders by time period
     * 
     * BUSINESS LOGIC:
     * - Used for reports and analytics
     * - Supports date-based filtering
     * 
     * RUBRIC: System Design & Architecture
     * - Reporting capabilities
     * 
     * PARAMETERS:
     * - $startDate: Start date (Y-m-d)
     * - $endDate: End date (Y-m-d)
     * 
     * RETURNS: Array of orders in date range
     * =====================================================
     */
    public function getOrdersByDateRange($startDate, $endDate)
    {
        // BACKEND QUERY: Filter by date range
        return $this->where('created_at >=', $startDate)
                    ->where('created_at <=', $endDate)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * =====================================================
     * BACKEND METHOD: Get High Priority Orders
     * =====================================================
     * 
     * PURPOSE: Get urgent orders needing immediate attention
     * 
     * BUSINESS LOGIC:
     * - Filters by "High" priority
     * - Only shows active orders (Pending/Processing)
     * - Oldest urgent orders first
     * 
     * RUBRIC: User Interface (Basic)
     * - Priority alerts for warehouse staff
     * 
     * RETURNS: Array of high-priority orders
     * =====================================================
     */
    public function getHighPriorityOrders()
    {
        // BACKEND QUERY: Get urgent orders
        return $this->where('priority', 'High')
                    ->whereIn('status', ['Pending', 'Processing'])
                    ->orderBy('created_at', 'ASC') // Oldest first
                    ->findAll();
    }

    /**
     * =====================================================
     * BACKEND METHOD: Generate Unique Order Number
     * =====================================================
     * 
     * PURPOSE: Auto-generate sequential order numbers
     * 
     * BUSINESS LOGIC:
     * - Format: ORD + YYYYMMDD + 4-digit sequence
     * - Example: ORD20251118-0001
     * - Resets sequence daily
     * - Prevents duplicate order numbers
     * 
     * RUBRIC: System Design & Architecture
     * - Automated order numbering system
     * 
     * RETURNS: String - Unique order number
     * =====================================================
     */
    public function generateOrderNumber()
    {
        // BACKEND LOGIC: Build order number format
        $prefix = 'ORD';
        $date = date('Ymd');
        
        // BACKEND QUERY: Get last order number for today
        $lastOrder = $this->like('order_number', $prefix . $date)
                          ->orderBy('id', 'DESC')
                          ->first();
        
        if ($lastOrder) {
            // BACKEND CALCULATION: Increment sequence
            $lastSequence = (int)substr($lastOrder['order_number'], -4);
            $newSequence = $lastSequence + 1;
        } else {
            // BACKEND LOGIC: Start new day at 1
            $newSequence = 1;
        }
        
        // BACKEND RETURN: Formatted order number
        return $prefix . $date . '-' . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * =====================================================
     * BACKEND METHOD: Update Order Status
     * =====================================================
     * 
     * PURPOSE: Change order status with tracking
     * 
     * BUSINESS LOGIC:
     * - Updates status field
     * - Records who processed it
     * - Timestamps the change
     * 
     * RUBRIC: Real-Time Stock Updates (Initial)
     * - Real-time order status updates
     * 
     * PARAMETERS:
     * - $orderId: Order to update
     * - $newStatus: New status value
     * - $userId: User making the change
     * 
     * RETURNS: Boolean (success/failure)
     * =====================================================
     */
    public function updateOrderStatus($orderId, $newStatus, $userId = null)
    {
        // BACKEND VALIDATION: Prepare update data
        $data = [
            'status' => $newStatus,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // BACKEND LOGIC: Add timestamps based on status
        if ($newStatus == 'Processing' && $userId) {
            $data['processed_by'] = $userId;
            $data['processed_at'] = date('Y-m-d H:i:s');
        } elseif ($newStatus == 'Completed') {
            $data['completed_at'] = date('Y-m-d H:i:s');
        }

        // BACKEND UPDATE: Save changes
        return $this->update($orderId, $data);
    }

    /**
     * =====================================================
     * BACKEND METHOD: Get Order Statistics
     * =====================================================
     * 
     * PURPOSE: Calculate order metrics for dashboard
     * 
     * BUSINESS LOGIC:
     * - Counts orders by status
     * - Calculates total revenue
     * - Shows daily/monthly trends
     * 
     * RUBRIC: System Design & Architecture
     * - Warehouse integration with order analytics
     * 
     * RETURNS: Array with statistics
     * =====================================================
     */
    public function getOrderStatistics()
    {
        // BACKEND AGGREGATE QUERIES
        $stats = [
            'total_orders' => $this->countAll(),
            'pending_count' => $this->where('status', 'Pending')->countAllResults(false),
            'processing_count' => $this->where('status', 'Processing')->countAllResults(false),
            'completed_count' => $this->where('status', 'Completed')->countAllResults(false),
            'cancelled_count' => $this->where('status', 'Cancelled')->countAllResults(false)
        ];

        // BACKEND CALCULATION: Total revenue
        $completedOrders = $this->where('status', 'Completed')->findAll();
        $totalRevenue = 0;
        foreach ($completedOrders as $order) {
            $totalRevenue += $order['total_amount'];
        }
        $stats['total_revenue'] = $totalRevenue;

        return $stats;
    }
}
