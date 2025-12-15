<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * PURCHASE ORDER MODEL
 * =====================================================
 * 
 * Purpose: Manages purchase orders to vendors
 * Links to accounts payable (vendor invoices)
 * 
 * RUBRIC: Procurement & Accounts Payable Integration
 * =====================================================
 */
class PurchaseOrderModel extends Model
{
    protected $table = 'purchase_orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'po_number',
        'requisition_id',
        'vendor_id',
        'warehouse_id',
        'order_date',
        'expected_delivery_date',
        'actual_delivery_date',
        'total_amount',
        'status',
        'payment_terms',
        'shipping_address',
        'notes',
        'created_by'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Generate PO number
     */
    public function generatePONumber()
    {
        $year = date('Y');
        $month = date('m');
        
        $lastPO = $this->like('po_number', "PO-{$year}{$month}", 'after')
                       ->orderBy('id', 'DESC')
                       ->first();
        
        if ($lastPO) {
            $lastNumber = (int)substr($lastPO['po_number'], -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "PO-{$year}{$month}-{$newNumber}";
    }

    /**
     * Get all POs with details
     */
    public function getAllWithDetails()
    {
        return $this->select('purchase_orders.*,
                            vendors.vendor_name,
                            warehouses.warehouse_name,
                            users.name as created_by_name')
                    ->join('vendors', 'vendors.id = purchase_orders.vendor_id')
                    ->join('warehouses', 'warehouses.id = purchase_orders.warehouse_id')
                    ->join('users', 'users.id = purchase_orders.created_by')
                    ->orderBy('purchase_orders.order_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get pending delivery POs
     */
    public function getPendingDelivery()
    {
        return $this->whereIn('status', ['Sent', 'Confirmed', 'Shipped'])
                    ->orderBy('expected_delivery_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get overdue POs
     */
    public function getOverduePOs()
    {
        return $this->whereIn('status', ['Sent', 'Confirmed', 'Shipped'])
                    ->where('expected_delivery_date <', date('Y-m-d'))
                    ->findAll();
    }

    /**
     * Send PO to vendor
     */
    public function sendToVendor($id)
    {
        return $this->update($id, [
            'status' => 'Sent'
        ]);
    }

    /**
     * Mark PO as received
     */
    public function markReceived($id)
    {
        return $this->update($id, [
            'status' => 'Received',
            'actual_delivery_date' => date('Y-m-d')
        ]);
    }

    /**
     * Get POs by vendor
     */
    public function getPOsByVendor($vendorId)
    {
        return $this->where('vendor_id', $vendorId)
                    ->orderBy('order_date', 'DESC')
                    ->findAll();
    }

    /**
     * Create PO from requisition
     */
    public function createFromRequisition($requisitionId, $vendorId, $userId, $items)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Create PO
            $poData = [
                'po_number' => $this->generatePONumber(),
                'requisition_id' => $requisitionId,
                'vendor_id' => $vendorId,
                'warehouse_id' => 1, // Get from requisition
                'order_date' => date('Y-m-d'),
                'status' => 'Draft',
                'created_by' => $userId
            ];
            
            $poId = $this->insert($poData);
            
            // Create PO items
            $totalAmount = 0;
            foreach ($items as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $itemTotal;
                
                $db->table('purchase_order_items')->insert([
                    'po_id' => $poId,
                    'inventory_id' => $item['inventory_id'],
                    'quantity_ordered' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $itemTotal,
                    'status' => 'Pending',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            // Update PO total
            $this->update($poId, ['total_amount' => $totalAmount]);
            
            // Update requisition status
            $db->table('purchase_requisitions')->where('id', $requisitionId)->update([
                'status' => 'Ordered'
            ]);
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return false;
            }
            
            return $poId;
            
        } catch (\Exception $e) {
            $db->transRollback();
            return false;
        }
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        return [
            'total' => $this->countAllResults(),
            'pending_delivery' => count($this->getPendingDelivery()),
            'overdue' => count($this->getOverduePOs()),
            'received_this_month' => $this->where('status', 'Received')
                                          ->where('MONTH(actual_delivery_date)', date('m'))
                                          ->countAllResults()
        ];
    }
}
