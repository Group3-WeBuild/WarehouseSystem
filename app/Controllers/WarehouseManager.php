<?php
/**
 * =====================================================
 * WAREHOUSE MANAGER CONTROLLER - Backend Logic
 * =====================================================
 * 
 * This controller handles warehouse management functions:
 * - Inventory monitoring and management
 * - Stock movements (in/out)
 * - Staff supervision
 * - Order processing
 * - Warehouse reports
 * 
 * STUDENT NOTE: This is the BACKEND - it processes data
 * and sends it to VIEWS (frontend HTML files)
 * 
 * DATABASE TABLES USED:
 * - inventory (stock items)
 * - stock_movements (incoming/outgoing)
 * - orders (customer/supplier orders)
 * - warehouses (warehouse locations)
 * - staff (warehouse staff)
 * 
 * =====================================================
 */

namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\StockMovementModel;
use App\Models\OrderModel;
use App\Models\WarehouseModel;

class WarehouseManager extends BaseController
{
    protected $session;
    protected $inventoryModel;
    protected $stockMovementModel;
    protected $orderModel;
    protected $warehouseModel;

    /**
     * Constructor - Runs when controller is loaded
     * Initializes session, helpers, and models
     */
    public function __construct()
    {
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
        
        // Initialize models
        $this->inventoryModel = new InventoryModel();
        $this->stockMovementModel = new StockMovementModel();
        $this->orderModel = new OrderModel();
        $this->warehouseModel = new WarehouseModel();
    }

    /**
     * =====================================================
     * SECURITY CHECK - Authentication & Authorization
     * =====================================================
     * 
     * Ensures user is logged in and has Warehouse Manager role
     * =====================================================
     */
    private function checkAuth()
    {
        if (!$this->session->get('isLoggedIn')) {
            $this->session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to(base_url('login'));
        }

        $userRole = $this->session->get('role');
        $allowedRoles = ['Warehouse Manager', 'IT Administrator'];
        
        if (!in_array($userRole, $allowedRoles)) {
            $this->session->setFlashdata('error', 'You do not have permission to access this page.');
            return redirect()->to(base_url('dashboard'));
        }

        return null;
    }

    /**
     * =====================================================
     * VIEW: Warehouse Manager Dashboard (Main Page)
     * =====================================================
     * 
     * Route: /warehouse-manager/dashboard
     * Method: GET
     * 
     * What it does:
     * 1. Gets inventory statistics
     * 2. Gets recent stock movements
     * 3. Gets pending orders
     * 4. Calculates warehouse metrics
     * 
     * Data sent to view:
     * - $stats: Dashboard statistics
     * - $recentMovements: Latest stock movements
     * - $pendingOrders: Orders awaiting processing
     * - $user: Current user information
     * =====================================================
     */
    public function dashboard()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            // Get statistics
            $totalItems = $this->inventoryModel->countAllResults(false);
            $lowStockItems = $this->inventoryModel->where('quantity <=', 'reorder_level', false)->countAllResults(false);
            $outOfStockItems = $this->inventoryModel->where('quantity', 0)->countAllResults();
            
            // Get recent movements
            $recentMovements = $this->stockMovementModel
                ->orderBy('created_at', 'DESC')
                ->limit(10)
                ->find();
            
            // Get pending orders
            $pendingOrders = $this->orderModel
                ->where('status', 'Pending')
                ->orderBy('created_at', 'DESC')
                ->limit(10)
                ->find();

            $stats = [
                'totalItems' => $totalItems,
                'lowStockItems' => $lowStockItems,
                'outOfStockItems' => $outOfStockItems,
                'pendingOrders' => count($pendingOrders),
                'totalValue' => $this->calculateInventoryValue()
            ];

            $data = [
                'title' => 'Warehouse Manager Dashboard',
                'user' => [
                    'username' => $this->session->get('username'),
                    'name' => $this->session->get('name'),
                    'role' => $this->session->get('role')
                ],
                'stats' => $stats,
                'recentMovements' => $recentMovements ?? [],
                'pendingOrders' => $pendingOrders ?? []
            ];

            return view('warehouse_manager/dashboard', $data);

        } catch (\Exception $e) {
            log_message('error', 'Warehouse Manager Dashboard Error: ' . $e->getMessage());
            
            // Return view with empty data on error
            return view('warehouse_manager/dashboard', [
                'title' => 'Warehouse Manager Dashboard',
                'user' => [
                    'username' => $this->session->get('username'),
                    'name' => $this->session->get('name'),
                    'role' => $this->session->get('role')
                ],
                'stats' => ['totalItems' => 0, 'lowStockItems' => 0, 'outOfStockItems' => 0, 'pendingOrders' => 0, 'totalValue' => 0],
                'recentMovements' => [],
                'pendingOrders' => []
            ]);
        }
    }

    /**
     * =====================================================
     * VIEW: Inventory Management Page
     * =====================================================
     * 
     * Route: /warehouse-manager/inventory
     * Method: GET
     * 
     * Shows all inventory items with search and filter
     * =====================================================
     */
    public function inventory()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            $inventory = $this->inventoryModel->orderBy('product_name', 'ASC')->findAll();
        } catch (\Exception $e) {
            $inventory = [];
        }

        $data = [
            'title' => 'Inventory Management',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'inventory' => $inventory
        ];

        return view('warehouse_manager/inventory', $data);
    }

    /**
     * =====================================================
     * VIEW: Stock Movements Page
     * =====================================================
     * 
     * Route: /warehouse-manager/stock-movements
     * Method: GET
     * 
     * Shows all stock in/out movements
     * =====================================================
     */
    public function stockMovements()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            $movements = $this->stockMovementModel
                ->orderBy('created_at', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            $movements = [];
        }

        $data = [
            'title' => 'Stock Movements',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'movements' => $movements
        ];

        return view('warehouse_manager/stock_movements', $data);
    }

    /**
     * =====================================================
     * VIEW: Order Processing Page
     * =====================================================
     * 
     * Route: /warehouse-manager/orders
     * Method: GET
     * 
     * Shows all orders for processing
     * =====================================================
     */
    public function orders()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            $orders = $this->orderModel->orderBy('created_at', 'DESC')->findAll();
        } catch (\Exception $e) {
            $orders = [];
        }

        $data = [
            'title' => 'Order Processing',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'orders' => $orders
        ];

        return view('warehouse_manager/orders', $data);
    }

    /**
     * =====================================================
     * VIEW: Reports Page
     * =====================================================
     * 
     * Route: /warehouse-manager/reports
     * Method: GET
     * 
     * Shows various warehouse reports
     * =====================================================
     */
    public function reports()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'title' => 'Warehouse Reports',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ]
        ];

        return view('warehouse_manager/reports', $data);
    }

    /**
     * =====================================================
     * AJAX ENDPOINTS - Inventory Management
     * =====================================================
     * 
     * These functions handle AJAX requests from frontend
     * They process data and return JSON responses
     * =====================================================
     */

    /**
     * ADD INVENTORY ITEM - AJAX Endpoint
     * Route: POST /warehouse-manager/add-item
     * Called from: inventory.php (Add Item Modal)
     */
    public function addItem()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        if ($this->request->getMethod() === 'post') {
            // Validation rules
            $rules = [
                'product_name' => 'required|min_length[3]|max_length[255]',
                'sku' => 'required|is_unique[inventory.sku]',
                'category' => 'required',
                'quantity' => 'required|numeric',
                'unit' => 'required',
                'unit_price' => 'required|decimal',
                'reorder_level' => 'required|numeric',
                'location' => 'required'
            ];

            if ($this->validate($rules)) {
                // Prepare inventory data
                $itemData = [
                    'product_name' => $this->request->getPost('product_name'),
                    'sku' => $this->request->getPost('sku'),
                    'category' => $this->request->getPost('category'),
                    'quantity' => $this->request->getPost('quantity'),
                    'unit' => $this->request->getPost('unit'),
                    'unit_price' => $this->request->getPost('unit_price'),
                    'reorder_level' => $this->request->getPost('reorder_level'),
                    'location' => $this->request->getPost('location'),
                    'description' => $this->request->getPost('description'),
                    'status' => 'Active'
                ];

                // Insert into database
                if ($this->inventoryModel->insert($itemData)) {
                    // Log the action
                    $this->logStockMovement([
                        'item_id' => $this->inventoryModel->getInsertID(),
                        'movement_type' => 'Initial Stock',
                        'quantity' => $itemData['quantity'],
                        'user_id' => $this->session->get('user_id'),
                        'notes' => 'Initial inventory entry'
                    ]);

                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Item added successfully'
                    ]);
                }
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add item',
                'errors' => $this->validator->getErrors()
            ]);
        }
    }

    /**
     * UPDATE INVENTORY ITEM - AJAX Endpoint
     * Route: POST /warehouse-manager/update-item/{id}
     * Called from: inventory.php (Edit Item Modal)
     */
    public function updateItem($id = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $itemId = $id ?? $this->request->getUri()->getSegment(3);

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'product_name' => 'required|min_length[3]|max_length[255]',
                'category' => 'required',
                'unit' => 'required',
                'unit_price' => 'required|decimal',
                'reorder_level' => 'required|numeric',
                'location' => 'required'
            ];

            if ($this->validate($rules)) {
                $itemData = [
                    'product_name' => $this->request->getPost('product_name'),
                    'category' => $this->request->getPost('category'),
                    'unit' => $this->request->getPost('unit'),
                    'unit_price' => $this->request->getPost('unit_price'),
                    'reorder_level' => $this->request->getPost('reorder_level'),
                    'location' => $this->request->getPost('location'),
                    'description' => $this->request->getPost('description')
                ];

                if ($this->inventoryModel->update($itemId, $itemData)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Item updated successfully'
                    ]);
                }
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update item',
                'errors' => $this->validator->getErrors()
            ]);
        }
    }

    /**
     * DELETE INVENTORY ITEM - AJAX Endpoint
     * Route: POST /warehouse-manager/delete-item/{id}
     * Called from: inventory.php (Delete Button)
     */
    public function deleteItem($id = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $itemId = $id ?? $this->request->getUri()->getSegment(3);

        // Soft delete by setting status to Inactive
        if ($this->inventoryModel->update($itemId, ['status' => 'Inactive'])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Item deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete item'
        ]);
    }

    /**
     * ADJUST STOCK - AJAX Endpoint
     * Route: POST /warehouse-manager/adjust-stock
     * Called from: inventory.php (Adjust Stock Modal)
     * 
     * Handles stock IN/OUT operations
     */
    public function adjustStock()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'item_id' => 'required|numeric',
                'movement_type' => 'required|in_list[Stock In,Stock Out]',
                'quantity' => 'required|numeric|greater_than[0]',
                'reason' => 'required'
            ];

            if ($this->validate($rules)) {
                $itemId = $this->request->getPost('item_id');
                $movementType = $this->request->getPost('movement_type');
                $quantity = $this->request->getPost('quantity');
                $reason = $this->request->getPost('reason');

                // Get current item
                $item = $this->inventoryModel->find($itemId);
                
                if (!$item) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Item not found'
                    ]);
                }

                // Calculate new quantity
                $newQuantity = $item['quantity'];
                if ($movementType === 'Stock In') {
                    $newQuantity += $quantity;
                } else {
                    // Check if sufficient stock for Stock Out
                    if ($item['quantity'] < $quantity) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Insufficient stock for this operation'
                        ]);
                    }
                    $newQuantity -= $quantity;
                }

                // Update inventory
                if ($this->inventoryModel->update($itemId, ['quantity' => $newQuantity])) {
                    // Log the movement
                    $this->logStockMovement([
                        'item_id' => $itemId,
                        'movement_type' => $movementType,
                        'quantity' => $quantity,
                        'user_id' => $this->session->get('user_id'),
                        'notes' => $reason
                    ]);

                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Stock adjusted successfully',
                        'newQuantity' => $newQuantity
                    ]);
                }
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to adjust stock',
                'errors' => $this->validator->getErrors()
            ]);
        }
    }

    /**
     * PROCESS ORDER - AJAX Endpoint
     * Route: POST /warehouse-manager/process-order/{id}
     * Called from: orders.php (Process Button)
     */
    public function processOrder($id = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $orderId = $id ?? $this->request->getUri()->getSegment(3);

        $orderData = [
            'status' => 'Processing',
            'processed_by' => $this->session->get('user_id'),
            'processed_at' => date('Y-m-d H:i:s')
        ];

        if ($this->orderModel->update($orderId, $orderData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Order marked as processing'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to process order'
        ]);
    }

    /**
     * COMPLETE ORDER - AJAX Endpoint
     * Route: POST /warehouse-manager/complete-order/{id}
     * Called from: orders.php (Complete Button)
     */
    public function completeOrder($id = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $orderId = $id ?? $this->request->getUri()->getSegment(3);

        $orderData = [
            'status' => 'Completed',
            'completed_at' => date('Y-m-d H:i:s')
        ];

        if ($this->orderModel->update($orderId, $orderData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Order completed successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to complete order'
        ]);
    }

    /**
     * =====================================================
     * HELPER FUNCTIONS - Support Functions
     * =====================================================
     * 
     * These functions provide data and perform calculations
     * =====================================================
     */

    /**
     * Calculate total inventory value
     */
    private function calculateInventoryValue()
    {
        try {
            $items = $this->inventoryModel->findAll();
            $totalValue = 0;
            
            foreach ($items as $item) {
                $totalValue += ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            }
            
            return number_format($totalValue, 2);
        } catch (\Exception $e) {
            return '0.00';
        }
    }

    /**
     * Log stock movement to database
     */
    private function logStockMovement($data)
    {
        try {
            $movementData = [
                'item_id' => $data['item_id'],
                'movement_type' => $data['movement_type'],
                'quantity' => $data['quantity'],
                'user_id' => $data['user_id'],
                'notes' => $data['notes'] ?? '',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->stockMovementModel->insert($movementData);
        } catch (\Exception $e) {
            log_message('error', 'Failed to log stock movement: ' . $e->getMessage());
        }
    }

    /**
     * Get low stock alerts
     */
    public function getLowStockAlerts()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            $lowStockItems = $this->inventoryModel
                ->where('quantity <=', 'reorder_level', false)
                ->where('status', 'Active')
                ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'items' => $lowStockItems
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to fetch low stock alerts'
            ]);
        }
    }
}
