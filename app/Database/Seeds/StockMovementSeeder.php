<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * =====================================================
 * STOCK MOVEMENT SEEDER
 * =====================================================
 * 
 * Purpose: Seeds initial stock movements for audit trail
 * =====================================================
 */
class StockMovementSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Initial stock for Main Warehouse
            [
                'item_id' => 1, // Portland Cement
                'warehouse_id' => 1,
                'movement_type' => 'Initial Stock',
                'quantity' => 1500,
                'reference_number' => 'INIT-2025-001',
                'user_id' => 3, // Warehouse Manager
                'notes' => 'Initial stock count - Portland Cement Type 1',
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 days'))
            ],
            [
                'item_id' => 2, // Steel Rebar 10mm
                'warehouse_id' => 1,
                'movement_type' => 'Initial Stock',
                'quantity' => 2500,
                'reference_number' => 'INIT-2025-002',
                'user_id' => 3,
                'notes' => 'Initial stock count - Steel Rebar 10mm',
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 days'))
            ],
            [
                'item_id' => 3, // Steel Rebar 12mm
                'warehouse_id' => 1,
                'movement_type' => 'Initial Stock',
                'quantity' => 2000,
                'reference_number' => 'INIT-2025-003',
                'user_id' => 3,
                'notes' => 'Initial stock count - Steel Rebar 12mm',
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 days'))
            ],
            
            // Stock In movements
            [
                'item_id' => 1,
                'warehouse_id' => 1,
                'movement_type' => 'Stock In',
                'quantity' => 500,
                'reference_number' => 'PO-2025-101',
                'user_id' => 4,
                'notes' => 'Received from Philippine Cement Industries',
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 days'))
            ],
            [
                'item_id' => 4, // Marine Plywood
                'warehouse_id' => 1,
                'movement_type' => 'Stock In',
                'quantity' => 300,
                'reference_number' => 'PO-2025-102',
                'user_id' => 4,
                'notes' => 'Received from Pacific Lumber Supply',
                'created_at' => date('Y-m-d H:i:s', strtotime('-14 days'))
            ],
            
            // Stock Out movements
            [
                'item_id' => 1,
                'warehouse_id' => 1,
                'movement_type' => 'Stock Out',
                'quantity' => -200,
                'reference_number' => 'SO-2025-001',
                'user_id' => 4,
                'notes' => 'Delivered to ABC Construction - Order ORD-2025-001',
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days'))
            ],
            [
                'item_id' => 2,
                'warehouse_id' => 1,
                'movement_type' => 'Stock Out',
                'quantity' => -500,
                'reference_number' => 'SO-2025-002',
                'user_id' => 4,
                'notes' => 'Delivered to XYZ Builders - Order ORD-2025-002',
                'created_at' => date('Y-m-d H:i:s', strtotime('-8 days'))
            ],
            
            // Transfer movements
            [
                'item_id' => 6, // Latex Paint White
                'warehouse_id' => 2, // North DC
                'movement_type' => 'Transfer',
                'quantity' => 200,
                'reference_number' => 'TRF-2025-001',
                'user_id' => 3,
                'notes' => 'Transfer from Main Warehouse to North DC',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
            ],
            
            // Adjustment movements
            [
                'item_id' => 9, // Common Wire Nails
                'warehouse_id' => 3,
                'movement_type' => 'Adjustment',
                'quantity' => -50,
                'reference_number' => 'ADJ-2025-001',
                'user_id' => 5, // Inventory Auditor
                'notes' => 'Physical count variance - damaged goods removed',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
            ],
            
            // Return movements
            [
                'item_id' => 10, // Concrete Screws
                'warehouse_id' => 3,
                'movement_type' => 'Return',
                'quantity' => 20,
                'reference_number' => 'RET-2025-001',
                'user_id' => 4,
                'notes' => 'Customer return - unused items from project',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
        ];

        $builder = $this->db->table('stock_movements');
        
        foreach ($data as $movement) {
            $builder->insert($movement);
        }

        echo "Stock movements seeded successfully.\n";
    }
}
