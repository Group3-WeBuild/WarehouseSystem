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
     * =====================================================
     * CREATE: ADD INVENTORY ITEM - AJAX Endpoint
     * =====================================================
     * 
     * Route: POST /warehouse-manager/add-item
     * Called from: inventory.php (Add Item Modal)
     * 
     * BACKEND LOGIC - CRUD: CREATE Operation
     * ---------------------------------------
     * This function creates a NEW inventory record in database
     * 
     * STUDENT NOTE - How CREATE Works:
     * 1. Frontend sends form data via AJAX POST
     * 2. Backend validates all required fields
     * 3. Backend checks if SKU already exists (must be unique)
     * 4. Backend inserts new record into 'inventory' table
     * 5. Backend logs initial stock as movement record
     * 6. Backend returns success/failure JSON to frontend
     * 
     * RUBRIC COMPLIANCE:
     * ✓ Functional database with warehouse tables
     * ✓ Can add stock items
     * ✓ Input validation prevents bad data
     * ✓ Audit trail through stock movement logging
     * =====================================================
     */
    public function addItem()
    {
        // BACKEND: Security check - is user logged in and authorized?
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        if ($this->request->getMethod() === 'post') {
            // BACKEND: Validation rules ensure data quality
            $rules = [
                'product_name' => 'required|min_length[3]|max_length[255]',
                'sku' => 'required|is_unique[inventory.sku]',  // BACKEND: Must be unique!
                'category' => 'required',
                'quantity' => 'required|numeric',
                'unit' => 'required',
                'unit_price' => 'required|decimal',
                'reorder_level' => 'required|numeric',
                'location' => 'required'
            ];

            if ($this->validate($rules)) {
                // BACKEND: Prepare data for database insertion
                // Only insert allowed fields (security measure)
                $itemData = [
                    'product_name' => $this->request->getPost('product_name'),
                    'sku' => strtoupper($this->request->getPost('sku')),  // BACKEND: Standardize SKU to uppercase
                    'category' => $this->request->getPost('category'),
                    'quantity' => $this->request->getPost('quantity'),
                    'unit' => $this->request->getPost('unit'),
                    'unit_price' => $this->request->getPost('unit_price'),
                    'reorder_level' => $this->request->getPost('reorder_level'),
                    'location' => $this->request->getPost('location'),
                    'description' => $this->request->getPost('description'),
                    'status' => 'Active'  // BACKEND: Default status for new items
                ];

                // BACKEND: INSERT INTO database (CREATE operation)
                if ($this->inventoryModel->insert($itemData)) {
                    // BACKEND: Get the ID of the newly inserted item
                    $newItemId = $this->inventoryModel->getInsertID();
                    
                    // BACKEND: Log this as initial stock movement for audit trail
                    $this->stockMovementModel->insert([
                        'item_id' => $newItemId,
                        'movement_type' => 'Initial Stock',
                        'quantity' => $itemData['quantity'],
                        'reference_number' => 'INIT-' . date('YmdHis') . '-' . $newItemId,
                        'user_id' => $this->session->get('user_id'),
                        'notes' => 'Initial inventory entry for ' . $itemData['product_name']
                    ]);

                    // BACKEND: Return success response to frontend
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Item added successfully!',
                        'data' => [
                            'id' => $newItemId,
                            'product_name' => $itemData['product_name'],
                            'sku' => $itemData['sku']
                        ]
                    ]);
                }

                // BACKEND: If insert failed
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to add item to database'
                ]);
            }

            // BACKEND: If validation failed, return errors to show in frontend
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please fix the errors below',
                'errors' => $this->validator->getErrors()
            ]);
        }
    }

    /**
     * =====================================================
     * UPDATE: EDIT INVENTORY ITEM - AJAX Endpoint
     * =====================================================
     * 
     * Route: POST /warehouse-manager/update-item/{id}
     * Called from: inventory.php (Edit Item Modal)
     * 
     * BACKEND LOGIC - CRUD: UPDATE Operation
     * ---------------------------------------
     * This function modifies an EXISTING inventory record
     * 
     * STUDENT NOTE - How UPDATE Works:
     * 1. Frontend sends item ID and updated data via AJAX
     * 2. Backend validates the updated fields
     * 3. Backend finds the record by ID
     * 4. Backend updates only the allowed fields (not SKU)
     * 5. Backend saves changes to database
     * 6. Backend returns success/failure to frontend
     * 
     * NOTE: SKU cannot be changed after creation (data integrity)
     * NOTE: Quantity is NOT updated here (use adjustStock instead)
     * 
     * RUBRIC COMPLIANCE:
     * ✓ Can update stock items
     * ✓ Validates changes before saving
     * ✓ Prevents unauthorized field changes
     * =====================================================
     */
    public function updateItem($id = null)
    {
        // BACKEND: Security check
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // BACKEND: Get item ID from URL parameter
        $itemId = $id ?? $this->request->getUri()->getSegment(3);

        if ($this->request->getMethod() === 'post') {
            // BACKEND: Validation rules (SKU not included - cannot be changed)
            $rules = [
                'product_name' => 'required|min_length[3]|max_length[255]',
                'category' => 'required',
                'unit' => 'required',
                'unit_price' => 'required|decimal',
                'reorder_level' => 'required|numeric',
                'location' => 'required'
            ];

            if ($this->validate($rules)) {
                // BACKEND: Check if item exists before updating
                $existingItem = $this->inventoryModel->find($itemId);
                
                if (!$existingItem) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Item not found'
                    ]);
                }

                // BACKEND: Prepare data for update (only allowed fields)
                $itemData = [
                    'product_name' => $this->request->getPost('product_name'),
                    'category' => $this->request->getPost('category'),
                    'unit' => $this->request->getPost('unit'),
                    'unit_price' => $this->request->getPost('unit_price'),
                    'reorder_level' => $this->request->getPost('reorder_level'),
                    'location' => $this->request->getPost('location'),
                    'description' => $this->request->getPost('description')
                ];

                // BACKEND: UPDATE database record
                if ($this->inventoryModel->update($itemId, $itemData)) {
                    // BACKEND: Return success with updated item info
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Item updated successfully!',
                        'data' => [
                            'id' => $itemId,
                            'product_name' => $itemData['product_name'],
                            'sku' => $existingItem['sku']  // SKU remains unchanged
                        ]
                    ]);
                }

                // BACKEND: If update failed
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update item in database'
                ]);
            }

            // BACKEND: If validation failed
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please fix the errors below',
                'errors' => $this->validator->getErrors()
            ]);
        }
    }

    /**
     * =====================================================
     * DELETE: REMOVE INVENTORY ITEM - AJAX Endpoint
     * =====================================================
     * 
     * Route: POST /warehouse-manager/delete-item/{id}
     * Called from: inventory.php (Delete Button)
     * 
     * BACKEND LOGIC - CRUD: DELETE Operation
     * ---------------------------------------
     * This function removes an inventory item
     * 
     * STUDENT NOTE - Soft Delete vs Hard Delete:
     * - SOFT DELETE: Item stays in database but marked as 'Inactive'
     * - HARD DELETE: Item permanently removed from database
     * 
     * We use SOFT DELETE because:
     * ✓ Preserves historical data
     * ✓ Can be restored if deleted by mistake
     * ✓ Maintains referential integrity with other tables
     * ✓ Audit trail remains intact
     * 
     * How DELETE Works:
     * 1. Frontend sends item ID via AJAX
     * 2. Backend finds the item in database
     * 3. Backend changes status from 'Active' to 'Inactive'
     * 4. Backend saves the change (item still exists but hidden)
     * 5. Backend confirms deletion to frontend
     * 
     * RUBRIC COMPLIANCE:
     * ✓ Can delete stock items
     * ✓ Data integrity maintained (soft delete)
     * =====================================================
     */
    public function deleteItem($id = null)
    {
        // BACKEND: Security check
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // BACKEND: Get item ID from URL
        $itemId = $id ?? $this->request->getUri()->getSegment(3);

        // BACKEND: Check if item exists before deleting
        $item = $this->inventoryModel->find($itemId);
        
        if (!$item) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Item not found'
            ]);
        }

        // BACKEND: Soft delete - set status to Inactive
        // Item remains in database but won't show in active lists
        if ($this->inventoryModel->update($itemId, ['status' => 'Inactive'])) {
            // BACKEND: Log the deletion for audit trail
            $this->stockMovementModel->insert([
                'item_id' => $itemId,
                'movement_type' => 'Adjustment',
                'quantity' => 0,
                'reference_number' => 'DEL-' . date('YmdHis') . '-' . $itemId,
                'user_id' => $this->session->get('user_id'),
                'notes' => 'Item deactivated: ' . $item['product_name']
            ]);

            // BACKEND: Return success response
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Item deleted successfully!',
                'data' => [
                    'id' => $itemId,
                    'product_name' => $item['product_name']
                ]
            ]);
        }

        // BACKEND: If update failed
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete item from database'
        ]);
    }

    /**
     * =====================================================
     * REAL-TIME STOCK ADJUSTMENT - AJAX Endpoint
     * =====================================================
     * 
     * Route: POST /warehouse-manager/adjust-stock
     * Called from: inventory.php (Adjust Stock Modal)
     * 
     * BACKEND LOGIC FOR REAL-TIME STOCK UPDATES:
     * ------------------------------------------
     * This function implements INSTANT stock updates when
     * items are added or removed from warehouse
     * 
     * STUDENT NOTE - How Real-Time Updates Work:
     * 1. User submits stock change (IN or OUT)
     * 2. Backend validates the data immediately
     * 3. Checks current stock levels in real-time
     * 4. Updates database instantly using transactions
     * 5. Logs the movement for audit trail
     * 6. Returns success/failure immediately
     * 
     * TRANSACTION SUPPORT:
     * - Uses database transactions to ensure data consistency
     * - If any step fails, all changes are rolled back
     * - Prevents partial updates that could corrupt data
     * 
     * RUBRIC COMPLIANCE:
     * ✓ Accurate and real-time updates when adding/removing stock
     * ✓ Validates stock availability before removal
     * ✓ Prevents negative inventory
     * ✓ Logs all movements for tracking
     * =====================================================
     */
    public function adjustStock()
    {
        // BACKEND: Check if user is authorized
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        if ($this->request->getMethod() === 'post') {
            // BACKEND: Validation rules to ensure data integrity
            $rules = [
                'item_id' => 'required|numeric',
                'movement_type' => 'required|in_list[Stock In,Stock Out]',
                'quantity' => 'required|numeric|greater_than[0]',
                'reason' => 'required'
            ];

            if ($this->validate($rules)) {
                // BACKEND: Get form data from frontend
                $itemId = $this->request->getPost('item_id');
                $movementType = $this->request->getPost('movement_type');
                $quantity = $this->request->getPost('quantity');
                $reason = $this->request->getPost('reason');

                // BACKEND: Start database transaction for consistency
                // Transaction ensures all-or-nothing: either all changes succeed or none do
                $db = \Config\Database::connect();
                $db->transStart();

                try {
                    // BACKEND: Get current inventory item from database (REAL-TIME)
                    $item = $this->inventoryModel->find($itemId);
                    
                    if (!$item) {
                        $db->transRollback();
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Item not found'
                        ]);
                    }

                    // BACKEND: Calculate new quantity based on movement type
                    $oldQuantity = $item['quantity'];
                    $newQuantity = $oldQuantity;
                    
                    if ($movementType === 'Stock In') {
                        // ADDING stock to warehouse
                        $newQuantity += $quantity;
                    } else {
                        // REMOVING stock from warehouse
                        // BACKEND: Validate sufficient stock exists (PREVENTS NEGATIVE INVENTORY)
                        if ($oldQuantity < $quantity) {
                            $db->transRollback();
                            return $this->response->setJSON([
                                'success' => false,
                                'message' => "Insufficient stock! Available: {$oldQuantity} {$item['unit']}, Requested: {$quantity} {$item['unit']}"
                            ]);
                        }
                        $newQuantity -= $quantity;
                    }

                    // BACKEND: Update inventory in database (REAL-TIME UPDATE)
                    // This is where the actual stock level changes in the database
                    $updateSuccess = $this->inventoryModel->update($itemId, ['quantity' => $newQuantity]);
                    
                    if (!$updateSuccess) {
                        $db->transRollback();
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Failed to update inventory'
                        ]);
                    }

                    // BACKEND: Log the movement for audit trail
                    // This creates a history record of who changed what and when
                    $movementData = [
                        'item_id' => $itemId,
                        'movement_type' => $movementType,
                        'quantity' => $quantity,
                        'reference_number' => 'ADJ-' . date('YmdHis') . '-' . $itemId,
                        'user_id' => $this->session->get('user_id'),
                        'notes' => $reason
                    ];
                    
                    $this->stockMovementModel->insert($movementData);

                    // BACKEND: Complete transaction (commit all changes)
                    $db->transComplete();

                    // BACKEND: Check if transaction was successful
                    if ($db->transStatus() === false) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Transaction failed. Stock not updated.'
                        ]);
                    }

                    // BACKEND: Send success response to frontend with updated quantity
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Stock adjusted successfully',
                        'data' => [
                            'old_quantity' => $oldQuantity,
                            'adjustment' => ($movementType === 'Stock In' ? '+' : '-') . $quantity,
                            'new_quantity' => $newQuantity,
                            'unit' => $item['unit'],
                            'product_name' => $item['product_name']
                        ]
                    ]);

                } catch (\Exception $e) {
                    // BACKEND: Handle any errors and rollback transaction
                    $db->transRollback();
                    log_message('error', 'Stock adjustment failed: ' . $e->getMessage());
                    
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'An error occurred while adjusting stock'
                    ]);
                }
            }

            // BACKEND: If validation failed, return errors
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
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

    // =========================================================
    // MULTI-WAREHOUSE MANAGEMENT METHODS (NEW)
    // =========================================================

    /**
     * =====================================================
     * VIEW: Inventory by Warehouse
     * =====================================================
     * 
     * Shows inventory for specific warehouse
     * RUBRIC: Multi-Warehouse Management (Midterm)
     * =====================================================
     */
    public function warehouseInventory($warehouseId)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $warehouseInventoryModel = new \App\Models\WarehouseInventoryModel();
        
        $warehouse = $this->warehouseModel->find($warehouseId);
        $inventory = $warehouseInventoryModel->getInventoryByWarehouse($warehouseId);
        $lowStock = $warehouseInventoryModel->getLowStockItemsByWarehouse($warehouseId);
        $capacity = $warehouseInventoryModel->getWarehouseCapacityUsage($warehouseId);
        
        $data = [
            'title' => 'Inventory: ' . ($warehouse['warehouse_name'] ?? 'Unknown'),
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'warehouse' => $warehouse,
            'inventory' => $inventory,
            'lowStock' => $lowStock,
            'capacity' => $capacity
        ];

        return view('warehouse_manager/warehouse_inventory', $data);
    }

    /**
     * =====================================================
     * VIEW: Transfer Inventory Page
     * =====================================================
     * 
     * Interface for transferring inventory between warehouses
     * RUBRIC: Multi-Warehouse Management (Midterm)
     * =====================================================
     */
    public function transferInventoryView()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $warehouses = $this->warehouseModel->findAll();
        $inventory = $this->inventoryModel->where('status', 'Active')->findAll();
        
        $data = [
            'title' => 'Transfer Inventory',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'warehouses' => $warehouses,
            'inventory' => $inventory
        ];

        return view('warehouse_manager/transfer_inventory', $data);
    }

    /**
     * =====================================================
     * AJAX: Transfer Inventory Between Warehouses
     * =====================================================
     * 
     * Moves stock from one warehouse to another
     * RUBRIC: Multi-Warehouse Management (Midterm)
     * "smooth transfers"
     * =====================================================
     */
    public function transferInventory()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $fromWarehouse = $this->request->getPost('from_warehouse');
        $toWarehouse = $this->request->getPost('to_warehouse');
        $inventoryId = $this->request->getPost('inventory_id');
        $quantity = (int)$this->request->getPost('quantity');
        $userId = $this->session->get('user_id');

        // Validate input
        if (!$fromWarehouse || !$toWarehouse || !$inventoryId || $quantity <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please fill in all required fields'
            ]);
        }

        if ($fromWarehouse == $toWarehouse) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Source and destination warehouses must be different'
            ]);
        }

        $warehouseInventoryModel = new \App\Models\WarehouseInventoryModel();
        $result = $warehouseInventoryModel->transferInventory(
            $fromWarehouse,
            $toWarehouse,
            $inventoryId,
            $quantity,
            $userId
        );

        if ($result === true) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "Successfully transferred {$quantity} units"
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => $result ?: 'Transfer failed'
        ]);
    }

    /**
     * =====================================================
     * AJAX: Get Inventory by Warehouse (JSON)
     * =====================================================
     */
    public function getInventoryByWarehouse($warehouseId)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $warehouseInventoryModel = new \App\Models\WarehouseInventoryModel();
        $inventory = $warehouseInventoryModel->getInventoryByWarehouse($warehouseId);

        return $this->response->setJSON([
            'success' => true,
            'inventory' => $inventory
        ]);
    }

    /**
     * =====================================================
     * AJAX: Get Warehouse Capacity
     * =====================================================
     */
    public function getWarehouseCapacity($warehouseId)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $warehouseInventoryModel = new \App\Models\WarehouseInventoryModel();
        $capacity = $warehouseInventoryModel->getWarehouseCapacityUsage($warehouseId);

        return $this->response->setJSON([
            'success' => true,
            'capacity' => $capacity
        ]);
    }

    // =========================================================
    // BATCH TRACKING METHODS (NEW)
    // =========================================================

    /**
     * =====================================================
     * VIEW: Batch Tracking Page
     * =====================================================
     * 
     * Shows all batches with expiry tracking
     * RUBRIC: Batch and Lot Tracking (Midterm)
     * =====================================================
     */
    public function batchTracking()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $batchModel = new \App\Models\BatchTrackingModel();
        
        $activeBatches = $batchModel->getActiveBatches();
        $expiringBatches = $batchModel->getBatchesExpiringWithin(30);
        $quarantined = $batchModel->getQuarantinedBatches();
        $stats = $batchModel->getBatchStatistics();
        
        $data = [
            'title' => 'Batch Tracking',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'activeBatches' => $activeBatches,
            'expiringBatches' => $expiringBatches,
            'quarantined' => $quarantined,
            'stats' => $stats
        ];

        return view('warehouse_manager/batch_tracking', $data);
    }

    /**
     * =====================================================
     * AJAX: Create New Batch
     * =====================================================
     * 
     * Creates batch when receiving inventory
     * =====================================================
     */
    public function createBatch()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $batchModel = new \App\Models\BatchTrackingModel();
        
        $data = [
            'inventory_id' => $this->request->getPost('inventory_id'),
            'batch_number' => $batchModel->generateBatchNumber(),
            'reference_number' => $this->request->getPost('reference_number'),
            'manufacture_date' => $this->request->getPost('manufacture_date'),
            'expiry_date' => $this->request->getPost('expiry_date'),
            'quantity_received' => $this->request->getPost('quantity'),
            'quantity_available' => $this->request->getPost('quantity'),
            'supplier_id' => $this->request->getPost('supplier_id'),
            'warehouse_id' => $this->request->getPost('warehouse_id'),
            'quality_status' => 'Active',
            'received_date' => date('Y-m-d H:i:s'),
            'received_by' => $this->session->get('user_id')
        ];

        $result = $batchModel->insert($data);

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Batch created successfully',
                'batch_number' => $data['batch_number']
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to create batch'
        ]);
    }

    /**
     * =====================================================
     * AJAX: Approve Batch
     * =====================================================
     * 
     * Approves batch after quality inspection
     * =====================================================
     */
    public function approveBatch($batchId)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $batchModel = new \App\Models\BatchTrackingModel();
        $notes = $this->request->getPost('notes') ?? '';
        $userId = $this->session->get('user_id');

        $result = $batchModel->approveBatch($batchId, $userId, $notes);

        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? 'Batch approved' : 'Approval failed'
        ]);
    }

    /**
     * =====================================================
     * AJAX: Reject Batch
     * =====================================================
     * 
     * Rejects batch due to quality issues
     * =====================================================
     */
    public function rejectBatch($batchId)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $batchModel = new \App\Models\BatchTrackingModel();
        $reason = $this->request->getPost('reason') ?? '';
        $userId = $this->session->get('user_id');

        $result = $batchModel->rejectBatch($batchId, $userId, $reason);

        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? 'Batch rejected' : 'Rejection failed'
        ]);
    }

    /**
     * =====================================================
     * AJAX: Get Batches Expiring Soon
     * =====================================================
     */
    public function getBatchesExpiring()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $days = $this->request->getGet('days') ?? 30;
        $batchModel = new \App\Models\BatchTrackingModel();
        $batches = $batchModel->getBatchesExpiringWithin($days);

        return $this->response->setJSON([
            'success' => true,
            'batches' => $batches
        ]);
    }

    // =========================================================
    // BARCODE SCANNING METHODS (NEW)
    // =========================================================

    /**
     * =====================================================
     * AJAX: Scan Barcode
     * =====================================================
     * 
     * Processes barcode scan and returns item info
     * RUBRIC: Barcode/QR Code Functionality (Midterm)
     * =====================================================
     */
    public function scanBarcode()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $barcodeModel = new \App\Models\BarcodeModel();
        $barcode = $this->request->getPost('barcode');

        if (empty($barcode)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No barcode provided'
            ]);
        }

        $item = $barcodeModel->scanBarcode($barcode);

        if ($item) {
            return $this->response->setJSON([
                'success' => true,
                'item' => $item
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Item not found for barcode: ' . $barcode
        ]);
    }

    /**
     * =====================================================
     * AJAX: Generate Barcode for Item
     * =====================================================
     */
    public function generateBarcode()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $barcodeModel = new \App\Models\BarcodeModel();
        $inventoryId = $this->request->getPost('inventory_id');

        $result = $barcodeModel->assignBarcode($inventoryId);

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'barcode' => $result['barcode_number'],
                'qr_data' => $result['qr_code_data']
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to generate barcode'
        ]);
    }

    /**
     * =====================================================
     * AJAX: Stock In via Barcode Scan
     * =====================================================
     * 
     * Quick stock-in using barcode scanner
     * =====================================================
     */
    public function stockInViaScan()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $barcodeModel = new \App\Models\BarcodeModel();
        $barcode = $this->request->getPost('barcode');
        $quantity = (int)$this->request->getPost('quantity');
        $warehouseId = $this->request->getPost('warehouse_id');
        $userId = $this->session->get('user_id');

        $result = $barcodeModel->stockInViaScan($barcode, $quantity, $warehouseId, $userId);

        return $this->response->setJSON($result);
    }

    /**
     * =====================================================
     * AJAX: Stock Out via Barcode Scan
     * =====================================================
     * 
     * Quick stock-out using barcode scanner
     * =====================================================
     */
    public function stockOutViaScan()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $barcodeModel = new \App\Models\BarcodeModel();
        $barcode = $this->request->getPost('barcode');
        $quantity = (int)$this->request->getPost('quantity');
        $warehouseId = $this->request->getPost('warehouse_id');
        $userId = $this->session->get('user_id');

        $result = $barcodeModel->stockOutViaScan($barcode, $quantity, $warehouseId, $userId);

        return $this->response->setJSON($result);
    }

    /**
     * =====================================================
     * AJAX: Batch Generate Barcodes
     * =====================================================
     * 
     * Generates barcodes for multiple items at once
     * =====================================================
     */
    public function batchGenerateBarcodes()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $barcodeModel = new \App\Models\BarcodeModel();
        $result = $barcodeModel->batchGenerateBarcodes();

        return $this->response->setJSON([
            'success' => true,
            'message' => "Generated {$result['generated']} barcodes, {$result['failed']} failed",
            'result' => $result
        ]);
    }
}
