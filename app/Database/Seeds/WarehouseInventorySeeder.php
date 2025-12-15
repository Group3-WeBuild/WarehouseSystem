<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WarehouseInventorySeeder extends Seeder
{
    public function run()
    {
        // Create sample warehouses
        $warehouses = [
            [
                'warehouse_name' => 'Main Warehouse',
                'warehouse_code' => 'WH-001',
                'location' => 'Manila',
                'address' => '123 Main Street, Manila, Philippines',
                'capacity' => 5000.00,
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'warehouse_name' => 'North Distribution Center',
                'warehouse_code' => 'WH-002',
                'location' => 'Quezon City',
                'address' => '456 North Ave, Quezon City, Philippines',
                'capacity' => 3000.00,
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'warehouse_name' => 'South Warehouse',
                'warehouse_code' => 'WH-003',
                'location' => 'Makati',
                'address' => '789 South St, Makati, Philippines',
                'capacity' => 2500.00,
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($warehouses as $warehouse) {
            $existing = $this->db->table('warehouses')->where('warehouse_code', $warehouse['warehouse_code'])->get()->getRow();
            if (!$existing) {
                $this->db->table('warehouses')->insert($warehouse);
            }
        }

        // Create sample inventory items
        $inventory = [
            [
                'product_name' => 'Cement Portland',
                'sku' => 'CEM-001',
                'category' => 'Construction Materials',
                'description' => '40kg bag of Portland cement',
                'quantity' => 500,
                'unit' => 'bags',
                'unit_price' => 250.00,
                'reorder_level' => 100,
                'location' => 'Main Warehouse',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Steel Rebar 10mm',
                'sku' => 'STL-001',
                'category' => 'Construction Materials',
                'description' => '6-meter steel rebar, 10mm diameter',
                'quantity' => 800,
                'unit' => 'pieces',
                'unit_price' => 180.00,
                'reorder_level' => 200,
                'location' => 'Main Warehouse',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Plywood 4x8 Marine',
                'sku' => 'PLY-001',
                'category' => 'Wood Products',
                'description' => '4x8 feet marine plywood, 18mm',
                'quantity' => 150,
                'unit' => 'sheets',
                'unit_price' => 850.00,
                'reorder_level' => 50,
                'location' => 'Main Warehouse',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Paint White Latex',
                'sku' => 'PNT-001',
                'category' => 'Painting Supplies',
                'description' => '4-liter can of white latex paint',
                'quantity' => 75,
                'unit' => 'cans',
                'unit_price' => 420.00,
                'reorder_level' => 30,
                'location' => 'North Distribution Center',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Electrical Wire 2.0mm',
                'sku' => 'ELC-001',
                'category' => 'Electrical Supplies',
                'description' => '100-meter roll of 2.0mm electrical wire',
                'quantity' => 45,
                'unit' => 'rolls',
                'unit_price' => 1200.00,
                'reorder_level' => 20,
                'location' => 'North Distribution Center',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Gravel 3/4 inch',
                'sku' => 'GRV-001',
                'category' => 'Construction Materials',
                'description' => 'Construction gravel, 3/4 inch',
                'quantity' => 30,
                'unit' => 'cubic meters',
                'unit_price' => 800.00,
                'reorder_level' => 10,
                'location' => 'South Warehouse',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Hollow Blocks 4"',
                'sku' => 'BLK-001',
                'category' => 'Construction Materials',
                'description' => '4-inch hollow blocks',
                'quantity' => 5,
                'unit' => 'pieces',
                'unit_price' => 8.50,
                'reorder_level' => 1000,
                'location' => 'South Warehouse',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Roofing Sheets Corrugated',
                'sku' => 'ROF-001',
                'category' => 'Roofing Materials',
                'description' => '8-feet corrugated roofing sheets',
                'quantity' => 0,
                'unit' => 'sheets',
                'unit_price' => 380.00,
                'reorder_level' => 50,
                'location' => 'Main Warehouse',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($inventory as $item) {
            $existing = $this->db->table('inventory')->where('sku', $item['sku'])->get()->getRow();
            if (!$existing) {
                $this->db->table('inventory')->insert($item);
            }
        }

        // Create sample orders
        $orders = [
            [
                'order_number' => 'ORD-' . date('Ymd') . '-001',
                'order_type' => 'Customer',
                'customer_name' => 'ABC Construction Company',
                'customer_email' => 'orders@abcconstruction.com',
                'customer_phone' => '0917-123-4567',
                'items' => json_encode([
                    ['sku' => 'CEM-001', 'quantity' => 100, 'price' => 250.00],
                    ['sku' => 'STL-001', 'quantity' => 200, 'price' => 180.00]
                ]),
                'total_amount' => 61000.00,
                'status' => 'Pending',
                'priority' => 'High',
                'delivery_address' => '123 Project Site, Quezon City',
                'delivery_date' => date('Y-m-d', strtotime('+3 days')),
                'notes' => 'Urgent delivery required',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'order_number' => 'ORD-' . date('Ymd') . '-002',
                'order_type' => 'Customer',
                'customer_name' => 'XYZ Builders Inc',
                'customer_email' => 'purchasing@xyzbuilders.com',
                'customer_phone' => '0918-234-5678',
                'items' => json_encode([
                    ['sku' => 'PLY-001', 'quantity' => 50, 'price' => 850.00],
                    ['sku' => 'PNT-001', 'quantity' => 20, 'price' => 420.00]
                ]),
                'total_amount' => 50900.00,
                'status' => 'Processing',
                'priority' => 'Normal',
                'delivery_address' => '456 Site Location, Makati',
                'delivery_date' => date('Y-m-d', strtotime('+5 days')),
                'notes' => 'Contact before delivery',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($orders as $order) {
            $existing = $this->db->table('orders')->where('order_number', $order['order_number'])->get()->getRow();
            if (!$existing) {
                $this->db->table('orders')->insert($order);
            }
        }

        echo "\nSample data created:\n";
        echo "- 3 Warehouses\n";
        echo "- 8 Inventory items (including low stock and out of stock)\n";
        echo "- 2 Sample orders\n";
    }
}
