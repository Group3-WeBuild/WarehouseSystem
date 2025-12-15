<?php
/**
 * =====================================================
 * PROCUREMENT OFFICER CONTROLLER
 * =====================================================
 * 
 * Purpose: Manages procurement workflow
 * - Purchase requisitions
 * - Purchase orders
 * - Vendor coordination
 * - Delivery tracking
 * 
 * RUBRIC: Procurement Officer Module
 * "Orders materials, ensures suppliers deliver on time,
 *  and coordinates with accounts payable"
 * =====================================================
 */

namespace App\Controllers;

use App\Models\PurchaseRequisitionModel;
use App\Models\PurchaseOrderModel;
use App\Models\VendorModel;
use App\Models\InventoryModel;
use App\Models\WarehouseModel;
use App\Models\AuditTrailModel;

class Procurement extends BaseController
{
    protected $session;
    protected $requisitionModel;
    protected $poModel;
    protected $vendorModel;
    protected $inventoryModel;
    protected $warehouseModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
        
        $this->requisitionModel = new PurchaseRequisitionModel();
        $this->poModel = new PurchaseOrderModel();
        $this->vendorModel = new VendorModel();
        $this->inventoryModel = new InventoryModel();
        $this->warehouseModel = new WarehouseModel();
    }

    /**
     * Security Check
     */
    private function checkAuth()
    {
        if (!$this->session->get('isLoggedIn')) {
            $this->session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to(base_url('login'));
        }

        $userRole = $this->session->get('role');
        $allowedRoles = ['Procurement Officer', 'Warehouse Manager', 'IT Administrator', 'Top Management'];
        
        if (!in_array($userRole, $allowedRoles)) {
            $this->session->setFlashdata('error', 'You do not have permission to access this page.');
            return redirect()->to(base_url('dashboard'));
        }

        return null;
    }

    /**
     * =====================================================
     * VIEW: Procurement Dashboard
     * =====================================================
     */
    public function dashboard()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $reqStats = $this->requisitionModel->getStatistics();
        $poStats = $this->poModel->getStatistics();
        $pendingApproval = $this->requisitionModel->getPendingApproval();
        $pendingDelivery = $this->poModel->getPendingDelivery();
        $overduePOs = $this->poModel->getOverduePOs();
        
        $data = [
            'title' => 'Procurement Dashboard',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'reqStats' => $reqStats,
            'poStats' => $poStats,
            'pendingApproval' => $pendingApproval,
            'pendingDelivery' => $pendingDelivery,
            'overduePOs' => $overduePOs
        ];

        return view('procurement/dashboard', $data);
    }

    /**
     * =====================================================
     * VIEW: Purchase Requisitions
     * =====================================================
     */
    public function requisitions()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $requisitions = $this->requisitionModel->getAllWithDetails();
        $warehouses = $this->warehouseModel->findAll();
        $inventory = $this->inventoryModel->where('status', 'Active')->findAll();
        
        $data = [
            'title' => 'Purchase Requisitions',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'requisitions' => $requisitions,
            'warehouses' => $warehouses,
            'inventory' => $inventory
        ];

        return view('procurement/requisitions', $data);
    }

    /**
     * =====================================================
     * VIEW: Purchase Orders
     * =====================================================
     */
    public function purchaseOrders()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $purchaseOrders = $this->poModel->getAllWithDetails();
        $vendors = $this->vendorModel->findAll();
        
        $data = [
            'title' => 'Purchase Orders',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'purchaseOrders' => $purchaseOrders,
            'vendors' => $vendors
        ];

        return view('procurement/purchase_orders', $data);
    }

    /**
     * =====================================================
     * VIEW: Vendors Management
     * =====================================================
     */
    public function vendors()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $vendors = $this->vendorModel->findAll();
        
        $data = [
            'title' => 'Vendors Management',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'vendors' => $vendors
        ];

        return view('procurement/vendors', $data);
    }

    /**
     * =====================================================
     * VIEW: Delivery Tracking
     * =====================================================
     */
    public function deliveryTracking()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $pendingDeliveries = $this->poModel->getPendingDelivery();
        $overdue = $this->poModel->getOverduePOs();
        
        $data = [
            'title' => 'Delivery Tracking',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'pendingDeliveries' => $pendingDeliveries,
            'overdue' => $overdue
        ];

        return view('procurement/delivery_tracking', $data);
    }

    /**
     * =====================================================
     * AJAX: Create Requisition
     * =====================================================
     */
    public function createRequisition()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $userId = $this->session->get('user_id');
        
        $requisitionData = [
            'requisition_number' => $this->requisitionModel->generateRequisitionNumber(),
            'requested_by' => $userId,
            'warehouse_id' => $this->request->getPost('warehouse_id'),
            'priority' => $this->request->getPost('priority') ?? 'Medium',
            'reason' => $this->request->getPost('reason'),
            'required_date' => $this->request->getPost('required_date'),
            'status' => 'Draft'
        ];

        $requisitionId = $this->requisitionModel->insert($requisitionData);

        if ($requisitionId) {
            // Add items
            $items = $this->request->getPost('items');
            if ($items) {
                $db = \Config\Database::connect();
                foreach ($items as $item) {
                    $db->table('requisition_items')->insert([
                        'requisition_id' => $requisitionId,
                        'inventory_id' => $item['inventory_id'],
                        'quantity_requested' => $item['quantity'],
                        'estimated_unit_price' => $item['unit_price'] ?? null,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            // Log audit
            AuditTrailModel::logAction([
                'user_id' => $userId,
                'username' => $this->session->get('username'),
                'module' => 'Procurement',
                'controller' => 'Procurement',
                'action' => 'CREATE',
                'table_name' => 'purchase_requisitions',
                'record_id' => $requisitionId,
                'description' => 'Created purchase requisition',
                'status' => 'Success',
                'endpoint' => '/procurement/create-requisition'
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Requisition created successfully',
                'requisition_number' => $requisitionData['requisition_number']
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to create requisition'
        ]);
    }

    /**
     * =====================================================
     * AJAX: Submit Requisition for Approval
     * =====================================================
     */
    public function submitRequisition()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $requisitionId = $this->request->getPost('requisition_id');
        $result = $this->requisitionModel->submitRequisition($requisitionId);

        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? 'Requisition submitted for approval' : 'Submission failed'
        ]);
    }

    /**
     * =====================================================
     * AJAX: Approve Requisition
     * =====================================================
     */
    public function approveRequisition()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $requisitionId = $this->request->getPost('requisition_id');
        $userId = $this->session->get('user_id');
        
        $result = $this->requisitionModel->approveRequisition($requisitionId, $userId);

        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? 'Requisition approved' : 'Approval failed'
        ]);
    }

    /**
     * =====================================================
     * AJAX: Reject Requisition
     * =====================================================
     */
    public function rejectRequisition()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $requisitionId = $this->request->getPost('requisition_id');
        $reason = $this->request->getPost('reason') ?? '';
        $userId = $this->session->get('user_id');
        
        $result = $this->requisitionModel->rejectRequisition($requisitionId, $userId, $reason);

        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? 'Requisition rejected' : 'Rejection failed'
        ]);
    }

    /**
     * =====================================================
     * AJAX: Create Purchase Order
     * =====================================================
     */
    public function createPurchaseOrder()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $userId = $this->session->get('user_id');
        
        $poData = [
            'po_number' => $this->poModel->generatePONumber(),
            'requisition_id' => $this->request->getPost('requisition_id'),
            'vendor_id' => $this->request->getPost('vendor_id'),
            'warehouse_id' => $this->request->getPost('warehouse_id'),
            'order_date' => date('Y-m-d'),
            'expected_delivery_date' => $this->request->getPost('expected_delivery_date'),
            'payment_terms' => $this->request->getPost('payment_terms'),
            'shipping_address' => $this->request->getPost('shipping_address'),
            'notes' => $this->request->getPost('notes'),
            'status' => 'Draft',
            'created_by' => $userId
        ];

        $poId = $this->poModel->insert($poData);

        if ($poId) {
            // Add items
            $items = $this->request->getPost('items');
            $totalAmount = 0;
            
            if ($items) {
                $db = \Config\Database::connect();
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
            }

            // Update total
            $this->poModel->update($poId, ['total_amount' => $totalAmount]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Purchase Order created successfully',
                'po_number' => $poData['po_number']
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to create Purchase Order'
        ]);
    }

    /**
     * =====================================================
     * AJAX: Send PO to Vendor
     * =====================================================
     */
    public function sendPOToVendor()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $poId = $this->request->getPost('po_id');
        $result = $this->poModel->sendToVendor($poId);

        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? 'PO sent to vendor' : 'Failed to send'
        ]);
    }

    /**
     * =====================================================
     * AJAX: Mark PO as Received
     * =====================================================
     */
    public function receivePO()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $poId = $this->request->getPost('po_id');
        $userId = $this->session->get('user_id');
        
        // Get PO details
        $po = $this->poModel->find($poId);
        
        if (!$po) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'PO not found'
            ]);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Mark PO as received
            $this->poModel->markReceived($poId);

            // Get PO items and update inventory
            $poItems = $db->table('purchase_order_items')
                         ->where('po_id', $poId)
                         ->get()
                         ->getResultArray();

            foreach ($poItems as $item) {
                // Update inventory quantity
                $inventory = $this->inventoryModel->find($item['inventory_id']);
                if ($inventory) {
                    $this->inventoryModel->update($item['inventory_id'], [
                        'quantity' => $inventory['quantity'] + $item['quantity_ordered']
                    ]);

                    // Log stock movement
                    $stockMovementModel = new \App\Models\StockMovementModel();
                    $stockMovementModel->insert([
                        'item_id' => $item['inventory_id'],
                        'movement_type' => 'Stock In',
                        'quantity' => $item['quantity_ordered'],
                        'reference_number' => $po['po_number'],
                        'user_id' => $userId,
                        'notes' => 'Received from PO: ' . $po['po_number'],
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }

                // Update item status
                $db->table('purchase_order_items')
                   ->where('id', $item['id'])
                   ->update([
                       'quantity_received' => $item['quantity_ordered'],
                       'status' => 'Received'
                   ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Transaction failed'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'PO received and inventory updated'
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * =====================================================
     * VIEW: Reports
     * =====================================================
     */
    public function reports()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $reqStats = $this->requisitionModel->getStatistics();
        $poStats = $this->poModel->getStatistics();
        
        $data = [
            'title' => 'Procurement Reports',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'reqStats' => $reqStats,
            'poStats' => $poStats
        ];

        return view('procurement/reports', $data);
    }

    /**
     * =====================================================
     * PRINT/EXPORT PDF REPORTS
     * =====================================================
     */

    /**
     * Print Purchase Orders Report as PDF
     */
    public function printPurchaseOrdersReport()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // Get filter parameters
        $status = $this->request->getGet('status');
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        $builder = $this->poModel->builder();
        $builder->select('purchase_orders.*, vendors.company_name as vendor_name');
        $builder->join('vendors', 'vendors.id = purchase_orders.vendor_id', 'left');
        $builder->where('DATE(purchase_orders.created_at) >=', $startDate);
        $builder->where('DATE(purchase_orders.created_at) <=', $endDate);
        
        if ($status) {
            $builder->where('purchase_orders.status', $status);
        }
        
        $builder->orderBy('purchase_orders.created_at', 'DESC');
        $purchaseOrders = $builder->get()->getResultArray();

        $stats = $this->poModel->getStatistics();

        $pdfService = new \App\Libraries\PdfService();
        $html = $pdfService->procurementReport($purchaseOrders, [
            'Pending' => $stats['pending'] ?? 0,
            'Approved' => $stats['approved'] ?? 0,
            'Received' => $stats['received'] ?? 0
        ]);

        return $pdfService->generateFromHtml($html, 'procurement_report_' . date('Y-m-d'), false);
    }

    /**
     * Print Requisitions Report as PDF
     */
    public function printRequisitionsReport()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // Get filter parameters
        $status = $this->request->getGet('status');
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        $builder = $this->requisitionModel->builder();
        $builder->select('purchase_requisitions.*, users.name as requester_name');
        $builder->join('users', 'users.id = purchase_requisitions.requested_by', 'left');
        $builder->where('DATE(purchase_requisitions.created_at) >=', $startDate);
        $builder->where('DATE(purchase_requisitions.created_at) <=', $endDate);
        
        if ($status) {
            $builder->where('purchase_requisitions.status', $status);
        }
        
        $builder->orderBy('purchase_requisitions.created_at', 'DESC');
        $requisitions = $builder->get()->getResultArray();

        $pdfService = new \App\Libraries\PdfService();
        $html = $pdfService->requisitionsReport($requisitions);

        return $pdfService->generateFromHtml($html, 'requisitions_report_' . date('Y-m-d'), false);
    }

    /**
     * =====================================================
     * ACTION: Create Vendor
     * =====================================================
     */
    public function createVendor()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'vendor_name' => $this->request->getPost('vendor_name'),
            'contact_person' => $this->request->getPost('contact_person'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'tax_id' => $this->request->getPost('tax_id'),
            'payment_terms' => $this->request->getPost('payment_terms') ?? 'Net 30',
            'status' => 'Active'
        ];

        try {
            $this->vendorModel->insert($data);
            $this->session->setFlashdata('success', 'Vendor created successfully.');
        } catch (\Exception $e) {
            log_message('error', 'Create Vendor Error: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Failed to create vendor.');
        }

        return redirect()->to(base_url('procurement/vendors'));
    }

    /**
     * =====================================================
     * ACTION: Update Vendor
     * =====================================================
     */
    public function updateVendor($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'vendor_name' => $this->request->getPost('vendor_name'),
            'contact_person' => $this->request->getPost('contact_person'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'tax_id' => $this->request->getPost('tax_id'),
            'payment_terms' => $this->request->getPost('payment_terms'),
            'status' => $this->request->getPost('status') ?? 'Active'
        ];

        try {
            $this->vendorModel->update($id, $data);
            $this->session->setFlashdata('success', 'Vendor updated successfully.');
        } catch (\Exception $e) {
            log_message('error', 'Update Vendor Error: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Failed to update vendor.');
        }

        return redirect()->to(base_url('procurement/vendors'));
    }
}
