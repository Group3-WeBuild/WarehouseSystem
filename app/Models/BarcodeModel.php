<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * BARCODE MODEL - Barcode/QR Code Management
 * =====================================================
 * 
 * Purpose: Handles barcode generation and scanning
 * Fast and error-free material logging
 * 
 * RUBRIC: Barcode/QR Code Functionality (Midterm)
 * "Fully functional scanning for item in/out"
 * 
 * This model provides:
 * - Barcode number generation
 * - Barcode lookup (scan simulation)
 * - QR code data generation
 * - Multi-format barcode support
 * =====================================================
 */
class BarcodeModel extends Model
{
    protected $table = 'inventory';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'barcode_number',
        'qr_code_data',
        'barcode_generated_at',
        'barcode_enabled'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * =====================================================
     * SCAN BARCODE - Lookup item by barcode
     * =====================================================
     * 
     * Simulates barcode scanner input
     * Returns item details for rapid processing
     * =====================================================
     */
    public function scanBarcode($barcodeNumber)
    {
        $result = $this->db->table('inventory')
                          ->select('inventory.*, warehouses.warehouse_name')
                          ->join('warehouse_inventory', 'warehouse_inventory.inventory_id = inventory.id', 'left')
                          ->join('warehouses', 'warehouses.id = warehouse_inventory.warehouse_id', 'left')
                          ->where('inventory.barcode_number', $barcodeNumber)
                          ->where('inventory.status', 'Active')
                          ->get()
                          ->getRowArray();
        
        if (!$result) {
            // Try SKU as fallback
            $result = $this->db->table('inventory')
                              ->where('sku', $barcodeNumber)
                              ->where('status', 'Active')
                              ->get()
                              ->getRowArray();
        }
        
        return $result;
    }

    /**
     * =====================================================
     * SCAN QR CODE - Lookup item by QR data
     * =====================================================
     * 
     * QR code contains JSON data with item details
     * Returns parsed item info
     * =====================================================
     */
    public function scanQRCode($qrData)
    {
        // Try to decode as JSON first
        $decoded = json_decode($qrData, true);
        
        if ($decoded && isset($decoded['sku'])) {
            return $this->db->table('inventory')
                           ->where('sku', $decoded['sku'])
                           ->where('status', 'Active')
                           ->get()
                           ->getRowArray();
        }
        
        // Try as plain barcode number
        return $this->scanBarcode($qrData);
    }

    /**
     * =====================================================
     * GENERATE BARCODE NUMBER
     * =====================================================
     * 
     * Creates unique barcode (EAN-13 style)
     * Format: WBXXXXXXXXXXC
     * WB = WeBuild prefix
     * X = Unique digits
     * C = Check digit
     * =====================================================
     */
    public function generateBarcodeNumber()
    {
        // Prefix for WeBuild
        $prefix = '8888';
        
        // Get last barcode
        $lastItem = $this->db->table('inventory')
                            ->select('barcode_number')
                            ->like('barcode_number', $prefix, 'after')
                            ->orderBy('id', 'DESC')
                            ->limit(1)
                            ->get()
                            ->getRowArray();
        
        if ($lastItem && $lastItem['barcode_number']) {
            // Extract number portion
            $lastNum = (int)substr($lastItem['barcode_number'], 4, 8);
            $newNum = str_pad($lastNum + 1, 8, '0', STR_PAD_LEFT);
        } else {
            $newNum = '00000001';
        }
        
        $barcodeBase = $prefix . $newNum;
        $checkDigit = $this->calculateEAN13CheckDigit($barcodeBase);
        
        return $barcodeBase . $checkDigit;
    }

    /**
     * =====================================================
     * CALCULATE EAN-13 CHECK DIGIT
     * =====================================================
     */
    private function calculateEAN13CheckDigit($barcodeBase)
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $digit = (int)$barcodeBase[$i];
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }
        $checkDigit = (10 - ($sum % 10)) % 10;
        return (string)$checkDigit;
    }

    /**
     * =====================================================
     * GENERATE QR CODE DATA
     * =====================================================
     * 
     * Creates JSON payload for QR code
     * Contains essential item identification
     * =====================================================
     */
    public function generateQRCodeData($inventoryId)
    {
        $item = $this->db->table('inventory')
                        ->select('id, sku, product_name, barcode_number, unit_price')
                        ->where('id', $inventoryId)
                        ->get()
                        ->getRowArray();
        
        if (!$item) return null;
        
        $qrData = [
            'system' => 'WITMS',
            'version' => '1.0',
            'item_id' => $item['id'],
            'sku' => $item['sku'],
            'name' => $item['product_name'],
            'barcode' => $item['barcode_number'],
            'price' => $item['unit_price'],
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        return json_encode($qrData);
    }

    /**
     * =====================================================
     * ASSIGN BARCODE TO ITEM
     * =====================================================
     * 
     * Generates and assigns barcode to inventory item
     * =====================================================
     */
    public function assignBarcode($inventoryId)
    {
        $barcodeNumber = $this->generateBarcodeNumber();
        $qrCodeData = $this->generateQRCodeData($inventoryId);
        
        $result = $this->db->table('inventory')
                          ->where('id', $inventoryId)
                          ->update([
                              'barcode_number' => $barcodeNumber,
                              'qr_code_data' => $qrCodeData,
                              'barcode_generated_at' => date('Y-m-d H:i:s'),
                              'barcode_enabled' => 1
                          ]);
        
        if ($result) {
            return [
                'barcode_number' => $barcodeNumber,
                'qr_code_data' => $qrCodeData
            ];
        }
        
        return false;
    }

    /**
     * =====================================================
     * GET ITEMS WITHOUT BARCODES
     * =====================================================
     * 
     * For batch barcode generation
     * =====================================================
     */
    public function getItemsWithoutBarcodes()
    {
        return $this->db->table('inventory')
                       ->where('barcode_number IS NULL')
                       ->orWhere('barcode_number', '')
                       ->where('status', 'Active')
                       ->get()
                       ->getResultArray();
    }

    /**
     * =====================================================
     * VALIDATE BARCODE FORMAT
     * =====================================================
     * 
     * Checks if barcode is valid EAN-13
     * =====================================================
     */
    public function validateBarcodeFormat($barcode)
    {
        // Must be 13 digits
        if (!preg_match('/^\d{13}$/', $barcode)) {
            return false;
        }
        
        // Validate check digit
        $checkDigit = $this->calculateEAN13CheckDigit(substr($barcode, 0, 12));
        return $checkDigit === substr($barcode, -1);
    }

    /**
     * =====================================================
     * BATCH GENERATE BARCODES
     * =====================================================
     * 
     * Generates barcodes for multiple items
     * =====================================================
     */
    public function batchGenerateBarcodes($inventoryIds = [])
    {
        $generated = 0;
        $failed = 0;
        
        if (empty($inventoryIds)) {
            // Get all items without barcodes
            $items = $this->getItemsWithoutBarcodes();
            $inventoryIds = array_column($items, 'id');
        }
        
        foreach ($inventoryIds as $id) {
            $result = $this->assignBarcode($id);
            if ($result) {
                $generated++;
            } else {
                $failed++;
            }
        }
        
        return [
            'generated' => $generated,
            'failed' => $failed,
            'total' => count($inventoryIds)
        ];
    }

    /**
     * =====================================================
     * PROCESS STOCK IN VIA SCAN
     * =====================================================
     * 
     * Quick stock-in using barcode scan
     * =====================================================
     */
    public function stockInViaScan($barcodeNumber, $quantity, $warehouseId, $userId)
    {
        $item = $this->scanBarcode($barcodeNumber);
        
        if (!$item) {
            return ['error' => 'Item not found with barcode: ' . $barcodeNumber];
        }
        
        // Use InventoryModel for stock adjustment
        $inventoryModel = new InventoryModel();
        $oldQty = $item['quantity'];
        $newQty = $oldQty + $quantity;
        
        $inventoryModel->update($item['id'], [
            'quantity' => $newQty
        ]);
        
        // Log movement
        $stockMovementModel = new StockMovementModel();
        $stockMovementModel->insert([
            'item_id' => $item['id'],
            'movement_type' => 'Stock In',
            'quantity' => $quantity,
            'reference_number' => 'SCAN-' . date('YmdHis'),
            'user_id' => $userId,
            'notes' => "Scanned in via barcode: {$barcodeNumber}",
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return [
            'success' => true,
            'item' => $item['product_name'],
            'sku' => $item['sku'],
            'old_quantity' => $oldQty,
            'new_quantity' => $newQty,
            'added' => $quantity
        ];
    }

    /**
     * =====================================================
     * PROCESS STOCK OUT VIA SCAN
     * =====================================================
     * 
     * Quick stock-out using barcode scan
     * =====================================================
     */
    public function stockOutViaScan($barcodeNumber, $quantity, $warehouseId, $userId)
    {
        $item = $this->scanBarcode($barcodeNumber);
        
        if (!$item) {
            return ['error' => 'Item not found with barcode: ' . $barcodeNumber];
        }
        
        if ($item['quantity'] < $quantity) {
            return ['error' => 'Insufficient stock. Available: ' . $item['quantity']];
        }
        
        $inventoryModel = new InventoryModel();
        $oldQty = $item['quantity'];
        $newQty = $oldQty - $quantity;
        
        $inventoryModel->update($item['id'], [
            'quantity' => $newQty
        ]);
        
        // Log movement
        $stockMovementModel = new StockMovementModel();
        $stockMovementModel->insert([
            'item_id' => $item['id'],
            'movement_type' => 'Stock Out',
            'quantity' => $quantity,
            'reference_number' => 'SCAN-' . date('YmdHis'),
            'user_id' => $userId,
            'notes' => "Scanned out via barcode: {$barcodeNumber}",
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return [
            'success' => true,
            'item' => $item['product_name'],
            'sku' => $item['sku'],
            'old_quantity' => $oldQty,
            'new_quantity' => $newQty,
            'removed' => $quantity
        ];
    }
}
