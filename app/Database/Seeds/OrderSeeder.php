<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * =====================================================
 * ORDER SEEDER
 * =====================================================
 * 
 * Purpose: Seeds sample orders (without JSON items field)
 * Order items will be in separate order_items table
 * =====================================================
 */
class OrderSeeder extends Seeder
{
    public function run()
    {
        // First, seed orders
        $orders = [
            [
                'order_number' => 'ORD-2025-001',
                'order_type' => 'Customer',
                'customer_name' => 'ABC Construction & Development Corp.',
                'customer_email' => 'fernando@abcconstruction.com',
                'customer_phone' => '+63 917 123 4567',
                'items' => null, // Will use order_items table instead
                'total_amount' => 75000.00,
                'status' => 'Completed',
                'priority' => 'High',
                'delivery_address' => 'Project Site: Ortigas Center, Pasig City',
                'delivery_date' => date('Y-m-d', strtotime('+5 days')),
                'processed_by' => 3,
                'processed_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'completed_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
                'notes' => 'Urgent delivery - construction deadline',
                'created_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-8 days'))
            ],
            [
                'order_number' => 'ORD-2025-002',
                'order_type' => 'Customer',
                'customer_name' => 'XYZ Builders & Associates',
                'customer_email' => 'maria@xyzbuilders.ph',
                'customer_phone' => '+63 917 234 5678',
                'items' => null,
                'total_amount' => 125000.00,
                'status' => 'Processing',
                'priority' => 'Normal',
                'delivery_address' => 'Project Site: BGC, Taguig City',
                'delivery_date' => date('Y-m-d', strtotime('+7 days')),
                'processed_by' => 3,
                'processed_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'completed_at' => null,
                'notes' => 'Regular delivery schedule',
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
            ],
            [
                'order_number' => 'ORD-2025-003',
                'order_type' => 'Customer',
                'customer_name' => 'Metro Property Developers Inc.',
                'customer_email' => 'jose@metroprop.com',
                'customer_phone' => '+63 917 345 6789',
                'items' => null,
                'total_amount' => 95000.00,
                'status' => 'Pending',
                'priority' => 'Normal',
                'delivery_address' => 'Project Site: Makati Avenue, Makati City',
                'delivery_date' => date('Y-m-d', strtotime('+10 days')),
                'processed_by' => null,
                'processed_at' => null,
                'completed_at' => null,
                'notes' => 'Awaiting payment confirmation',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
            ],
            [
                'order_number' => 'ORD-2025-004',
                'order_type' => 'Supplier',
                'customer_name' => 'Manila Steel Trading Corporation',
                'customer_email' => 'sales@manilasteel.com.ph',
                'customer_phone' => '+63 2 8123 4567',
                'items' => null,
                'total_amount' => 450000.00,
                'status' => 'Completed',
                'priority' => 'High',
                'delivery_address' => 'Main Warehouse Manila',
                'delivery_date' => date('Y-m-d', strtotime('-5 days')),
                'processed_by' => 6, // Procurement Officer
                'processed_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'completed_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'notes' => 'Restocking order - steel products',
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
            ],
            [
                'order_number' => 'ORD-2025-005',
                'order_type' => 'Transfer',
                'customer_name' => 'Internal Transfer',
                'customer_email' => null,
                'customer_phone' => null,
                'items' => null,
                'total_amount' => 0.00,
                'status' => 'Processing',
                'priority' => 'Normal',
                'delivery_address' => 'From Main Warehouse to North DC',
                'delivery_date' => date('Y-m-d', strtotime('+2 days')),
                'processed_by' => 3,
                'processed_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'completed_at' => null,
                'notes' => 'Warehouse balancing - paint products',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
        ];

        $builder = $this->db->table('orders');
        
        foreach ($orders as $order) {
            $existing = $builder->where('order_number', $order['order_number'])->get()->getRow();
            if (!$existing) {
                $builder->insert($order);
            }
        }

        // Now seed order items (normalized)
        $orderItems = [
            // ORD-2025-001 items
            [
                'order_id' => 1,
                'inventory_id' => 1, // Portland Cement
                'quantity' => 200,
                'unit_price' => 250.00,
                'subtotal' => 50000.00,
                'notes' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-12 days'))
            ],
            [
                'order_id' => 1,
                'inventory_id' => 4, // Marine Plywood
                'quantity' => 50,
                'unit_price' => 850.00,
                'subtotal' => 42500.00,
                'notes' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-12 days'))
            ],
            
            // ORD-2025-002 items
            [
                'order_id' => 2,
                'inventory_id' => 2, // Steel Rebar 10mm
                'quantity' => 500,
                'unit_price' => 180.00,
                'subtotal' => 90000.00,
                'notes' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days'))
            ],
            [
                'order_id' => 2,
                'inventory_id' => 3, // Steel Rebar 12mm
                'quantity' => 200,
                'unit_price' => 220.00,
                'subtotal' => 44000.00,
                'notes' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days'))
            ],
            
            // ORD-2025-003 items
            [
                'order_id' => 3,
                'inventory_id' => 1, // Portland Cement
                'quantity' => 300,
                'unit_price' => 250.00,
                'subtotal' => 75000.00,
                'notes' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
            ],
            [
                'order_id' => 3,
                'inventory_id' => 6, // Latex Paint White
                'quantity' => 50,
                'unit_price' => 480.00,
                'subtotal' => 24000.00,
                'notes' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
            ],
            
            // ORD-2025-004 items (Supplier order)
            [
                'order_id' => 4,
                'inventory_id' => 2, // Steel Rebar 10mm
                'quantity' => 2000,
                'unit_price' => 175.00, // Wholesale price
                'subtotal' => 350000.00,
                'notes' => 'Bulk purchase',
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 days'))
            ],
            [
                'order_id' => 4,
                'inventory_id' => 3, // Steel Rebar 12mm
                'quantity' => 1000,
                'unit_price' => 210.00,
                'subtotal' => 210000.00,
                'notes' => 'Bulk purchase',
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 days'))
            ],
            
            // ORD-2025-005 items (Transfer)
            [
                'order_id' => 5,
                'inventory_id' => 6, // Latex Paint White
                'quantity' => 100,
                'unit_price' => 0.00, // Internal transfer
                'subtotal' => 0.00,
                'notes' => 'Transfer from Main to North DC',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
        ];

        $itemsBuilder = $this->db->table('order_items');
        
        foreach ($orderItems as $item) {
            $itemsBuilder->insert($item);
        }

        echo "Orders and order items seeded successfully.\n";
    }
}
