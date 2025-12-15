<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * WAREHOUSE INVENTORY MODEL - Multi-Warehouse Support
 * =====================================================
 * 
 * Purpose: Manages inventory levels per warehouse
 * Links inventory items to specific warehouse locations
 * 
 * RUBRIC: Multi-Warehouse Management (Midterm)
 * "Users can view/manage stock by warehouse;
 *  smooth transfers"
 * 
 * Critical Methods:
 * - Get inventory by warehouse
 * - Transfer inventory between warehouses
 * - Get warehouse stock levels
 * - Update warehouse inventory with validation
 * =====================================================
 */
class WarehouseInventoryModel extends Model
{
    protected $table = 'warehouse_inventory';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'warehouse_id',
        'inventory_id',
        'quantity',
        'rack_location',
        'bin_number',
        'reserved_quantity',
        'available_quantity',
        'last_counted_at',
        'last_adjusted_by'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'warehouse_id' => 'required|numeric',
        'inventory_id' => 'required|numeric',
        'quantity' => 'required|numeric|greater_than_equal_to[0]',
        'reserved_quantity' => 'numeric|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'warehouse_id' => ['required' => 'Warehouse is required'],
        'inventory_id' => ['required' => 'Inventory item is required'],
        'quantity' => ['required' => 'Quantity is required', 'numeric' => 'Quantity must be a number'],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * =====================================================
     * GET INVENTORY BY WAREHOUSE
     * =====================================================
     * 
     * Retrieves all inventory items in a specific warehouse
     * with detailed information
     * 
     * RETURNS: Array of items with warehouse stock levels
     * =====================================================
     */
    public function getInventoryByWarehouse($warehouseId)
    {
        return $this->select('warehouse_inventory.*, inventory.product_name, inventory.sku, 
                            inventory.category, inventory.unit, inventory.unit_price,
                            inventory.reorder_level, inventory.status')
                    ->join('inventory', 'inventory.id = warehouse_inventory.inventory_id')
                    ->where('warehouse_inventory.warehouse_id', $warehouseId)
                    ->where('inventory.status', 'Active')
                    ->orderBy('inventory.product_name', 'ASC')
                    ->findAll();
    }

    /**
     * =====================================================
     * GET WAREHOUSE STOCK BY ITEM
     * =====================================================
     * 
     * Shows stock level of a single item across all warehouses
     * =====================================================
     */
    public function getStockByItemAllWarehouses($inventoryId)
    {
        return $this->select('warehouse_inventory.*, warehouses.warehouse_name, 
                            warehouses.warehouse_code, warehouses.location')
                    ->join('warehouses', 'warehouses.id = warehouse_inventory.warehouse_id')
                    ->where('warehouse_inventory.inventory_id', $inventoryId)
                    ->findAll();
    }

    /**
     * =====================================================
     * GET SPECIFIC WAREHOUSE INVENTORY
     * =====================================================
     * 
     * Gets single record of item in warehouse
     * =====================================================
     */
    public function getWarehouseInventoryRecord($warehouseId, $inventoryId)
    {
        return $this->where('warehouse_id', $warehouseId)
                    ->where('inventory_id', $inventoryId)
                    ->first();
    }

    /**
     * =====================================================
     * GET LOW STOCK ITEMS BY WAREHOUSE
     * =====================================================
     * 
     * Shows items below reorder level in specific warehouse
     * =====================================================
     */
    public function getLowStockItemsByWarehouse($warehouseId)
    {
        return $this->select('warehouse_inventory.*, inventory.product_name, inventory.sku,
                            inventory.reorder_level, inventory.unit, 
                            (inventory.reorder_level - warehouse_inventory.quantity) as additional_needed')
                    ->join('inventory', 'inventory.id = warehouse_inventory.inventory_id')
                    ->where('warehouse_inventory.warehouse_id', $warehouseId)
                    ->where('warehouse_inventory.quantity <=', 'inventory.reorder_level', false)
                    ->where('inventory.status', 'Active')
                    ->orderBy('warehouse_inventory.quantity', 'ASC')
                    ->findAll();
    }

    /**
     * =====================================================
     * GET OUT OF STOCK ITEMS BY WAREHOUSE
     * =====================================================
     * 
     * Shows items with zero quantity
     * =====================================================
     */
    public function getOutOfStockByWarehouse($warehouseId)
    {
        return $this->select('warehouse_inventory.*, inventory.product_name, inventory.sku')
                    ->join('inventory', 'inventory.id = warehouse_inventory.inventory_id')
                    ->where('warehouse_inventory.warehouse_id', $warehouseId)
                    ->where('warehouse_inventory.quantity', 0)
                    ->where('inventory.status', 'Active')
                    ->findAll();
    }

    /**
     * =====================================================
     * GET WAREHOUSE CAPACITY USAGE
     * =====================================================
     * 
     * Calculates warehouse utilization
     * =====================================================
     */
    public function getWarehouseCapacityUsage($warehouseId)
    {
        $result = $this->selectSum('quantity')
                       ->where('warehouse_id', $warehouseId)
                       ->get()
                       ->getRowArray();
        
        $totalQuantity = $result['quantity'] ?? 0;
        
        // Get warehouse capacity
        $db = \Config\Database::connect();
        $warehouse = $db->table('warehouses')
                       ->select('capacity')
                       ->where('id', $warehouseId)
                       ->get()
                       ->getRowArray();
        
        $capacity = $warehouse['capacity'] ?? 1000;
        $percentageUsed = ($totalQuantity / $capacity) * 100;
        
        return [
            'total_items' => $totalQuantity,
            'capacity' => $capacity,
            'percentage_used' => round($percentageUsed, 2),
            'available_space' => $capacity - $totalQuantity
        ];
    }

    /**
     * =====================================================
     * TRANSFER INVENTORY BETWEEN WAREHOUSES
     * =====================================================
     * 
     * Moves inventory from one warehouse to another
     * 
     * PARAMETERS:
     * - $fromWarehouse: Source warehouse ID
     * - $toWarehouse: Destination warehouse ID
     * - $inventoryId: Item ID
     * - $quantity: Amount to transfer
     * - $userId: User performing transfer
     * 
     * RETURNS: true on success, error message on failure
     * =====================================================
     */
    public function transferInventory($fromWarehouse, $toWarehouse, $inventoryId, $quantity, $userId)
    {
        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Step 1: Check source warehouse has sufficient stock
            $sourceStock = $this->getWarehouseInventoryRecord($fromWarehouse, $inventoryId);
            
            if (!$sourceStock || $sourceStock['available_quantity'] < $quantity) {
                throw new \Exception('Insufficient stock in source warehouse');
            }
            
            // Step 2: Decrease source warehouse stock
            $this->update($sourceStock['id'], [
                'quantity' => $sourceStock['quantity'] - $quantity,
                'available_quantity' => $sourceStock['available_quantity'] - $quantity,
                'last_adjusted_by' => $userId
            ]);
            
            // Step 3: Increase destination warehouse stock (or create record)
            $destStock = $this->getWarehouseInventoryRecord($toWarehouse, $inventoryId);
            
            if ($destStock) {
                // Update existing
                $this->update($destStock['id'], [
                    'quantity' => $destStock['quantity'] + $quantity,
                    'available_quantity' => $destStock['available_quantity'] + $quantity,
                    'last_adjusted_by' => $userId
                ]);
            } else {
                // Create new warehouse inventory record
                $this->insert([
                    'warehouse_id' => $toWarehouse,
                    'inventory_id' => $inventoryId,
                    'quantity' => $quantity,
                    'available_quantity' => $quantity,
                    'last_adjusted_by' => $userId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            // Step 4: Log the transfer in stock_movements
            $stockMovementModel = new StockMovementModel();
            $stockMovementModel->insert([
                'item_id' => $inventoryId,
                'movement_type' => 'Transfer',
                'quantity' => $quantity,
                'reference_number' => 'TRANSFER-' . date('YmdHis'),
                'user_id' => $userId,
                'notes' => "Transferred from Warehouse {$fromWarehouse} to Warehouse {$toWarehouse}",
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            // Step 5: Log to audit trail
            $session = \Config\Services::session();
            $auditModel = new AuditTrailModel();
            $auditModel->insert([
                'user_id' => $userId,
                'username' => $session->get('username') ?? 'System',
                'module' => 'Inventory',
                'controller' => 'WarehouseManager',
                'action' => 'TRANSFER',
                'table_name' => 'warehouse_inventory',
                'record_id' => $inventoryId,
                'new_values' => json_encode(['from' => $fromWarehouse, 'to' => $toWarehouse, 'quantity' => $quantity]),
                'description' => "Transferred {$quantity} units from warehouse {$fromWarehouse} to {$toWarehouse}",
                'status' => 'Success',
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'POST',
                'endpoint' => $_SERVER['REQUEST_URI'] ?? '/warehouse-manager/transfer',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return false;
            }
            
            return true;
            
        } catch (\Exception $e) {
            $db->transRollback();
            return $e->getMessage();
        }
    }

    /**
     * =====================================================
     * GET TOTAL INVENTORY VALUE
     * =====================================================
     * 
     * Calculates total value across all warehouses
     * =====================================================
     */
    public function getTotalInventoryValue()
    {
        $result = $this->selectSum('warehouse_inventory.quantity * inventory.unit_price as total_value')
                       ->join('inventory', 'inventory.id = warehouse_inventory.inventory_id')
                       ->where('inventory.status', 'Active')
                       ->get()
                       ->getRowArray();
        
        return $result['total_value'] ?? 0;
    }

    /**
     * =====================================================
     * GET INVENTORY VALUE BY WAREHOUSE
     * =====================================================
     * 
     * Calculates value for specific warehouse
     * =====================================================
     */
    public function getInventoryValueByWarehouse($warehouseId)
    {
        $result = $this->selectSum('warehouse_inventory.quantity * inventory.unit_price as total_value')
                       ->join('inventory', 'inventory.id = warehouse_inventory.inventory_id')
                       ->where('warehouse_inventory.warehouse_id', $warehouseId)
                       ->where('inventory.status', 'Active')
                       ->get()
                       ->getRowArray();
        
        return $result['total_value'] ?? 0;
    }

    /**
     * =====================================================
     * UPDATE AVAILABLE QUANTITY
     * =====================================================
     * 
     * Auto-calculates available quantity
     * (total - reserved)
     * =====================================================
     */
    public function updateAvailableQuantity($id)
    {
        $record = $this->find($id);
        if (!$record) return false;
        
        $available = $record['quantity'] - $record['reserved_quantity'];
        
        return $this->update($id, [
            'available_quantity' => $available
        ]);
    }
}
